<?php

namespace App\Http\Controllers\ClaimingOfAssistance;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\AddressMetadataModel;
use App\Models\TransactionClaimModel;

use Illuminate\Support\Facades\DB;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class SearchClaimController extends Controller
{
    //

    public function searchClaimClient(Request $request){

        $addressMetadata = AddressMetadataModel::get();

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
            abort(400, 'Unauthorized access');
        }    


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



        $transactionClaim = TransactionClaimModel::
        with([
        'transactionClaimStatusCondition.transactionClaimStatus',
        'approveTransaction.transaction.beneficiaryTransaction',
        'approveTransaction.transaction.agency',
        'approveTransaction.transaction.agencyProgram',
        'approveTransaction.transaction.assistanceType',
        'approveTransaction.transaction.assistanceDescription',
        'approveTransaction.transaction.client' => function($query){
          $query->select('client.id','client.first_name','client.middle_name','.client.last_name','client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', COALESCE(client.middle_name, ''), ' ', client.last_name, ' ', COALESCE(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','suffix.id');
        },
        ])
        ->whereHas('approveTransaction.transaction.client', function($query) use ($request){
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
        // ->orderBy('transaction.created_at', 'desc')
        ->get();

       
        foreach($transactionClaim as $transactionKey => &$transactionValue){
            if(isset($transactionValue['approveTransaction'])){
               
                $transactionValue['approveTransaction']['transaction']['client']['region'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['region_key'];
                $transactionValue['approveTransaction']['transaction']['client']['province'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']][$transactionValue['approveTransaction']['transaction']['client']['province_id']];
                $transactionValue['approveTransaction']['transaction']['client']['city'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']][$transactionValue['approveTransaction']['transaction']['client']['city_id']];
                $transactionValue['approveTransaction']['transaction']['client']['barangay'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']]['barangay_list'][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']];
            }
        }

        $divideTransaction = [];
        $divideCounter = 0;
        $arrayCounter = 0;
        foreach($transactionClaim as $trans){

            if($divideCounter == 5){
                $arrayCounter++;
                $divideCounter = 0;
            }
            $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            $divideCounter++;
        } 

        $transactionClaimCount  = $transactionClaim->count();

        return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);

    }

    public function searchTransactionID(Request $request){

        
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
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


        $transactionClaim = TransactionClaimModel::
        with([
        'transactionClaimStatusCondition.transactionClaimStatus',
        'approveTransaction.transaction.beneficiaryTransaction',
        'approveTransaction.transaction.agency',
        'approveTransaction.transaction.agencyProgram',
        'approveTransaction.transaction.assistanceType',
        'approveTransaction.transaction.assistanceDescription',
        'approveTransaction.transaction.client' => function($query){
          $query->select('client.id','client.first_name','client.middle_name','.client.last_name','client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', COALESCE(client.middle_name, ''), ' ', client.last_name, ' ', COALESCE(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','suffix.id');
        },
        ])
        ->whereHas('approveTransaction.transaction', function($query) use ($request){
            $query->where(
                'transaction.transaction_id',
                'like',
                '%'.$request->input('data.transaction_id').'%'
            );
        })
        // ->orderBy('transaction.created_at', 'desc')
        ->get();

        $transaction_approve = $transactionClaim->count();
    


        foreach($transactionClaim as $transactionKey => &$transactionValue){
            if(isset($transactionValue['approveTransaction'])){
                $transactionValue['approveTransaction']['transaction']['client']['region'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['region_key'];
                $transactionValue['approveTransaction']['transaction']['client']['province'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']][$transactionValue['approveTransaction']['transaction']['client']['province_id']];
                $transactionValue['approveTransaction']['transaction']['client']['city'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']][$transactionValue['approveTransaction']['transaction']['client']['city_id']];
                $transactionValue['approveTransaction']['transaction']['client']['barangay'] = $addressMapped[$transactionValue['approveTransaction']['transaction']['client']['region_id']]['province_list'][$transactionValue['approveTransaction']['transaction']['client']['province_id']]['municipality_list'][$transactionValue['approveTransaction']['transaction']['client']['city_id']]['barangay_list'][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']][$transactionValue['approveTransaction']['transaction']['client']['barangay_id']];
            }
        }

        $divideTransaction = [];
        $divideCounter = 0;
        $arrayCounter = 0;
        foreach($transactionClaim as $trans){

            if($divideCounter == 5){
                $arrayCounter++;
                $divideCounter = 0;
            }
            $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            $divideCounter++;
        } 

        $transactionClaimCount  = $transactionClaim->count();


        return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);

    }
}
