<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TransactionModel;

use App\Models\AddressMetadataModel;

use App\Models\User;

use Illuminate\Support\Facades\Auth;



class BulkPrintingOfReceiptController extends Controller
{
    //
    public function searchClient(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('bulkPrintingOfreceipt') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }
    
        if(!isset($request['data']['client_fullname']) || empty($request['data']['client_fullname'])){
            return response()->json([]);
        }else{
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
        
    
        $transactionModel = TransactionModel::
           with([
           'transactionApprove.transactionApproveStatusCondition',
           'transactionApprove.transactionClaim.transactionClaimStatusCondition',
           'transactionApprove.transactionApproveAmount',
           'agency',
           'agencyProgram',
           'assistanceType',
           'assistanceDescription',
           'assistanceCategory',
           'client.clientOccupation',
           'client.precint',
           'client.contactNumber',
           'client.clientIdentification.otherIdentificationType',
           'client.clientIdentification.identificationType',
           'client.sex',
           'client.civilStatus',
           'client.suffix',
           'client.image',
           'beneficiaryTransaction.beneficiaryOccupation',
           'beneficiaryTransaction.contactNumber',
           'beneficiaryTransaction.civilStatus',
           'beneficiaryTransaction.suffix',
           'beneficiaryTransaction.sex',
           'beneficiaryTransaction.image',
           'beneficiaryTransaction.precint',
           'beneficiaryTransaction.beneficiaryIdentification.identificationType',
           'hospital.hospitalType'
           ])
           ->whereHas('client',function($query) use($request){
                $fullNameParts = explode(' ', $request['data']['client_fullname']);
    
                $query->leftJoin('suffix', 'client.suffix_id', '=', 'suffix.id');
    
                foreach ($fullNameParts as $namePart) {
                    $query->where(function ($subQuery) use ($namePart) {
                        $subQuery->where('client.first_name', 'LIKE', '%' . $namePart . '%')
                                ->orWhere('client.middle_name', 'LIKE', '%' . $namePart . '%')
                                ->orWhere('client.last_name', 'LIKE', '%' . $namePart . '%')
                                ->orWhere('suffix.suffix', 'LIKE', '%' . $namePart . '%');
                    });
                }
           })
           ->orderBy('transaction.created_at', 'desc')
           ->get();
    
           $transactionCount = $transactionModel->count();
           $transactionArray = $transactionModel->toArray();
    
           foreach($transactionArray as $key => &$transactionValue){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
                
                if(isset($transactionValue['beneficiary_transaction'])){
                    foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                        $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                        $beneficiaryValue['province'] =  $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                        $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                        $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
                    }
                }
           
            }
           

    

           return response()->json(['transaction' => $transactionArray,'transactionCount' => $transactionCount]);
    
        }
        
    }
    
    public function searchTransactionID(Request $request){

        if(!isset($request['data']['transactionID']) || empty($request['data']['transactionID'])){
            return response()->json([]);
        }else{
            $addressMetadata = AddressMetadataModel::get();

            /**
            * Key mapped the address
            */
           $addressMapped = [];
           $regionCounter = 0;
           $region_list = $addressMetadata[0]['address_metadata'];
    
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
        
    
        $transactionModel = TransactionModel::
           with([
           'transactionApprove.transactionApproveStatusCondition',
           'transactionApprove.transactionClaim.transactionClaimStatusCondition',
           'transactionApprove.transactionApproveAmount',
           'agency',
           'agencyProgram',
           'assistanceType',
           'assistanceDescription',
           'assistanceCategory',
           'client.clientOccupation',
           'client.precint',
           'client.contactNumber',
           'client.clientIdentification.otherIdentificationType',
           'client.clientIdentification.identificationType',
           'client.sex',
           'client.civilStatus',
           'client.suffix',
           'client.image',
           'beneficiaryTransaction.beneficiaryOccupation',
           'beneficiaryTransaction.contactNumber',
           'beneficiaryTransaction.civilStatus',
           'beneficiaryTransaction.suffix',
           'beneficiaryTransaction.sex',
           'beneficiaryTransaction.image',
           'beneficiaryTransaction.precint',
           'beneficiaryTransaction.beneficiaryIdentification.identificationType',
           'hospital.hospitalType'
           ])
           ->where('transaction.transaction_id','=',$request['data']['transactionID'])
           ->orderBy('transaction.created_at', 'desc')
           ->get();
    
           $transactionCount = $transactionModel->count();
           $transactionArray = $transactionModel->toArray();
    
           foreach($transactionArray as $key => &$transactionValue){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
                
                if(isset($transactionValue['beneficiary_transaction'])){
                    foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                        $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                        $beneficiaryValue['province'] =  $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                        $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                        $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
                    }
                }
           
            }
           

    

           return response()->json(['transaction' => $transactionArray,'transactionCount' => $transactionCount]);
    
        }
        
    }

    public function searchDate(Request $request){
        if((!isset($request['data']['from_date']) || empty($request['data']['from_date'])) && (!isset($request['data']['to_date']) || empty($request['data']['to_date']))){
            return response()->json([]);
        }else{
            $addressMetadata = AddressMetadataModel::get();

            /**
            * Key mapped the address
            */
           $addressMapped = [];
           $regionCounter = 0;
           $region_list = $addressMetadata[0]['address_metadata'];
    
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
        
    
        $transactionModel = TransactionModel::
           with([
           'transactionApprove.transactionApproveStatusCondition',
           'transactionApprove.transactionClaim.transactionClaimStatusCondition',
           'transactionApprove.transactionApproveAmount',
           'agency',
           'agencyProgram',
           'assistanceType',
           'assistanceDescription',
           'assistanceCategory',
           'client.clientOccupation',
           'client.precint',
           'client.contactNumber',
           'client.clientIdentification.otherIdentificationType',
           'client.clientIdentification.identificationType',
           'client.sex',
           'client.civilStatus',
           'client.suffix',
           'client.image',
           'beneficiaryTransaction.beneficiaryOccupation',
           'beneficiaryTransaction.contactNumber',
           'beneficiaryTransaction.civilStatus',
           'beneficiaryTransaction.suffix',
           'beneficiaryTransaction.sex',
           'beneficiaryTransaction.image',
           'beneficiaryTransaction.precint',
           'beneficiaryTransaction.beneficiaryIdentification.identificationType',
           'hospital.hospitalType'
           ])
           ->where('transaction.date_request','>=',$request['data']['from_date'])
           ->where('transaction.date_request','<=',$request['data']['to_date'])
           ->orderBy('transaction.created_at', 'desc')
           ->get();
    
           $transactionCount = $transactionModel->count();
           $transactionArray = $transactionModel->toArray();
    
           foreach($transactionArray as $transactionKey => &$transactionValue){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];

                if(isset($transactionValue['beneficiary_transaction'])){
                    foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                        $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                        $beneficiaryValue['province'] =  $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                        $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                        $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
                    }
                }
           
            }
           

    

           return response()->json(['transaction' => $transactionArray,'transactionCount' => $transactionCount]);
    
        }
    }
}
