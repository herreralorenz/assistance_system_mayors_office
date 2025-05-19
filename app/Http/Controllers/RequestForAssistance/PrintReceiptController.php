<?php

namespace App\Http\Controllers\RequestForAssistance;

use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PrintReceiptController extends Controller
{
    //
    public function requestReceipt(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('requestAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $addressMetadata = AddressMetadataModel::get();

        /**
        * Key mapped the address
        */
       $addressMapped = [];
       $regionCounter = 0;
       $region_list = $addressMetadata[0]['address_metadata'];

       uksort($region_list, 'strnatcmp');

        foreach ($region_list as &$region) {
            // Sort provinces inside each region
            if (isset($region['province_list'])) {
                uksort($region['province_list'], 'strnatcmp');
    
                foreach ($region['province_list'] as &$province) {
                    // Sort municipalities inside each province
                    if (isset($province['municipality_list'])) {
                        uksort($province['municipality_list'], 'strnatcmp');
    
                        foreach ($province['municipality_list'] as &$municipality) {
                            // Sort barangays inside each municipality
                            if (isset($municipality['barangay_list']) && is_array($municipality['barangay_list'])) {
                                sort($municipality['barangay_list'], SORT_NATURAL);
                            }
                        }
                    }
                }
            }
        }

       foreach($region_list as $regionKey => $regionValue){

           $regionArray = [
               'region_id' => $regionCounter,
               'region_key' => $regionKey,
               'region_name' => $regionValue['region_name']
           ];

           $addressMapped[$regionCounter] = $regionArray;

           $provinceCounter = 0;
           foreach($regionValue['province_list'] as $provinceKey => $provinceValue){
               $provinceArray = [
                   $provinceCounter => $provinceKey,
               ];
               
               $addressMapped[$regionCounter]['province_list'][$provinceCounter] = $provinceArray;

               $municipalityCounter = 0;
               foreach($provinceValue['municipality_list'] as $municipalityKey => $municipalityValue){
                   $municipalityArray = [
                       $municipalityCounter => $municipalityKey,
                   ];

                   $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter] = $municipalityArray;

                   $barangayCounter = 0;
                   foreach($municipalityValue['barangay_list'] as $barangayKey => $barangayValue){

                       $barangayArray = [
                           $barangayCounter => $barangayValue,

                       ];
                       $addressMapped[$regionCounter]['province_list'][$provinceCounter]['municipality_list'][$municipalityCounter]['barangay_list'][$barangayCounter] = $barangayArray;
                       $barangayCounter++;
                   }

                   $municipalityCounter++;
               }
               $provinceCounter++;
           }
           $regionCounter++;
       }


        $transaction = TransactionModel::
        with([
            'client.precint',
            'client.sex',
            'client.civilStatus',
            'client.clientOccupation',
            'beneficiaryTransaction.precint',
            'beneficiaryTransaction.sex',
            'beneficiaryTransaction.civilStatus',
            'beneficiaryTransaction.beneficiaryOccupation',
            'hospital',
            'agency',
            'agencyProgram',
            'assistanceType',
            'assistanceDescription',
            'assistanceCategory'
        ])
        ->where('transaction.id','=',$request['id'])
        ->get();

        $transactionArray = $transaction->toArray();

        if(isset($transactionArray[0]['beneficiary_transaction'][0])){
            $transactionArray[0]['beneficiary_transaction'][0]['region'] = $addressMapped[$transactionArray[0]['beneficiary_transaction'][0]['region_id']]['region_key'];
            $transactionArray[0]['beneficiary_transaction'][0]['province'] = $addressMapped[$transactionArray[0]['beneficiary_transaction'][0]['region_id']]['province_list'][$transactionArray[0]['beneficiary_transaction'][0]['province_id']][$transactionArray[0]['beneficiary_transaction'][0]['province_id']];
            $transactionArray[0]['beneficiary_transaction'][0]['city'] = $addressMapped[$transactionArray[0]['beneficiary_transaction'][0]['region_id']]['province_list'][$transactionArray[0]['beneficiary_transaction'][0]['province_id']]['municipality_list'][$transactionArray[0]['beneficiary_transaction'][0]['city_id']][$transactionArray[0]['beneficiary_transaction'][0]['city_id']];
            $transactionArray[0]['beneficiary_transaction'][0]['barangay'] = $addressMapped[$transactionArray[0]['beneficiary_transaction'][0]['region_id']]['province_list'][$transactionArray[0]['beneficiary_transaction'][0]['province_id']]['municipality_list'][$transactionArray[0]['beneficiary_transaction'][0]['city_id']]['barangay_list'][$transactionArray[0]['beneficiary_transaction'][0]['barangay_id']][$transactionArray[0]['beneficiary_transaction'][0]['barangay_id']];
        
            $transactionArray[0]['client']['region'] = $addressMapped[$transactionArray[0]['client']['region_id']]['region_key'];
            $transactionArray[0]['client']['province'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']][$transactionArray[0]['client']['province_id']];
            $transactionArray[0]['client']['city'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']]['municipality_list'][$transactionArray[0]['client']['city_id']][$transactionArray[0]['client']['city_id']];
            $transactionArray[0]['client']['barangay'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']]['municipality_list'][$transactionArray[0]['client']['city_id']]['barangay_list'][$transactionArray[0]['client']['barangay_id']][$transactionArray[0]['client']['barangay_id']];
        }else{
            $transactionArray[0]['client']['region'] = $addressMapped[$transactionArray[0]['client']['region_id']]['region_key'];
            $transactionArray[0]['client']['province'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']][$transactionArray[0]['client']['province_id']];
            $transactionArray[0]['client']['city'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']]['municipality_list'][$transactionArray[0]['client']['city_id']][$transactionArray[0]['client']['city_id']];
            $transactionArray[0]['client']['barangay'] = $addressMapped[$transactionArray[0]['client']['region_id']]['province_list'][$transactionArray[0]['client']['province_id']]['municipality_list'][$transactionArray[0]['client']['city_id']]['barangay_list'][$transactionArray[0]['client']['barangay_id']][$transactionArray[0]['client']['barangay_id']];
        }

        

        if($transaction->count() == 0){
            abort(404);
        }else{
            return response()->json(['transaction' => $transactionArray]);
        }
       
    }
}
