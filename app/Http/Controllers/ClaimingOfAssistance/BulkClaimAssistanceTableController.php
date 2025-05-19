<?php

namespace App\Http\Controllers\ClaimingOfAssistance;

use App\Http\Controllers\Controller;


use App\Models\ClientModel;
use App\Models\BeneficiaryModel;
use App\Models\TransactionModel;
use App\Models\AddressMetadataModel;

use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\TransactionApproveAmountModel;
use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionClaimStatusConditionModel;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;


use Illuminate\Support\Facades\Auth;
use App\Models\User;


class BulkClaimAssistanceTableController extends Controller
{
    //

    public function bulkClaim(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('claimAssistance') && !$user->hasPermissionTo('viewClaimAssistance')) {
            abort(400, 'Unauthorized access');
        }

        foreach($request['data']['toClaim'] as $key => $value){
            
            try{
                DB::beginTransaction();
                
                $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::where('transaction_claim_id','=',$value['id'])->firstOrFail();
                $transactionClaimStatusCondition->transaction_claim_status_id = 3;
                $transactionClaimStatusCondition->update();
                
                DB::commit();

            }catch(Exception $e){
                DB::rollBack();
                return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
    
            }catch (ModelNotFoundException $e) {
                // if user is not found throws a 404 response
                DB::rollBack();
                return response()->json(['message' => 'Transaction not found'], 404);
            }
        }

        
        return response()->json(['message' => 'Transaction updated successfully'], 200);
    }

    public function searchDateRequest(Request $request){

        


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



       $transactionClaim = TransactionClaimModel::with([
       'transactionClaimStatusCondition',
       'approveTransaction.transactionApproveStatusCondition',
       'approveTransaction.transactionApproveAmount',
       'approveTransaction.transaction.client' => function($query){
           $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",client.middle_name," ",client.last_name) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
       },
       'approveTransaction.transaction.agency',
       'approveTransaction.transaction.agencyProgram',
       'approveTransaction.transaction.assistanceType',
       'approveTransaction.transaction.assistanceDescription',
       'approveTransaction.transaction.client.sex',
       'approveTransaction.transaction.client.suffix',
       'approveTransaction.transaction.client.civilStatus',
       'approveTransaction.transaction.client.precint',
       'approveTransaction.transaction.client.sex',
       'approveTransaction.transaction.beneficiaryTransaction' => function($query){
           $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
       },
       // 'approveTransaction.transaction.beneficiaryTransaction.suffix',
       // 'approveTransaction.transaction.beneficiaryTransaction.sex',
       // 'approveTransaction.transaction.beneficiaryTransaction.civilStatus',
       // 'approveTransaction.transaction.beneficiaryTransaction.precint',
       ]
       )
       ->whereHas('transactionClaimStatusCondition', function($query){
           $query->where('transaction_claim_cond.transaction_claim_status_id', '=', '1')->orWhere('transaction_claim_cond.transaction_claim_status_id', '=', '2');
       })
       ->whereHas('approveTransaction.transaction', function($query) use ($request) {
           $query->where('transaction.date_request','>=',$request['data']['date_from'])->where('transaction.date_request','<=',$request['data']['date_to']);
       }) 
       ->orderBy('created_at', 'desc')
       ->limit(100)
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

       $transactionClaimCount  = TransactionClaimModel::count();

       return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);
    }


    public function searchTransactionID(Request $request){

        
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



       $transactionClaim = TransactionClaimModel::with([
       'transactionClaimStatusCondition',
       'approveTransaction.transactionApproveStatusCondition',
       'approveTransaction.transactionApproveAmount',
       'approveTransaction.transaction.client' => function($query){
           $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",client.middle_name," ",client.last_name) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
       },
       'approveTransaction.transaction.agency',
       'approveTransaction.transaction.agencyProgram',
       'approveTransaction.transaction.assistanceType',
       'approveTransaction.transaction.assistanceDescription',
       'approveTransaction.transaction.client.sex',
       'approveTransaction.transaction.client.suffix',
       'approveTransaction.transaction.client.civilStatus',
       'approveTransaction.transaction.client.precint',
       'approveTransaction.transaction.client.sex',
       'approveTransaction.transaction.beneficiaryTransaction' => function($query){
           $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
       },
       // 'approveTransaction.transaction.beneficiaryTransaction.suffix',
       // 'approveTransaction.transaction.beneficiaryTransaction.sex',
       // 'approveTransaction.transaction.beneficiaryTransaction.civilStatus',
       // 'approveTransaction.transaction.beneficiaryTransaction.precint',
       ]
       )
       ->whereHas('transactionClaimStatusCondition', function($query){
           $query->where('transaction_claim_cond.transaction_claim_status_id', '=', '1')->orWhere('transaction_claim_cond.transaction_claim_status_id', '=', '2');
       })
       ->whereHas('approveTransaction.transaction', function($query) use ($request) {
           $query->where('transaction.transaction_id','LIKE','%'.$request['data']['transactionID'].'%');
       })
       ->orderBy('created_at', 'desc')
       ->limit(100)
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

       $transactionClaimCount  = TransactionClaimModel::count();

       return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);
    }

    public function searchClient(Request $request){
        //
        


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



        $transactionClaim = TransactionClaimModel::with([
        'transactionClaimStatusCondition',
        'approveTransaction.transactionApproveStatusCondition',
        'approveTransaction.transactionApproveAmount',
        'approveTransaction.transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",client.middle_name," ",client.last_name) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'approveTransaction.transaction.agency',
        'approveTransaction.transaction.agencyProgram',
        'approveTransaction.transaction.assistanceType',
        'approveTransaction.transaction.assistanceDescription',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.client.suffix',
        'approveTransaction.transaction.client.civilStatus',
        'approveTransaction.transaction.client.precint',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.beneficiaryTransaction' => function($query){
            $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
        },
        // 'approveTransaction.transaction.beneficiaryTransaction.suffix',
        // 'approveTransaction.transaction.beneficiaryTransaction.sex',
        // 'approveTransaction.transaction.beneficiaryTransaction.civilStatus',
        // 'approveTransaction.transaction.beneficiaryTransaction.precint',
        ]
        )
        ->whereHas('transactionClaimStatusCondition', function($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id', '=', '1')->orWhere('transaction_claim_cond.transaction_claim_status_id', '=', '2');
        })
        ->whereHas('approveTransaction.transaction.client', function($query) use ($request) {
            $query->leftJoin('suffix', 'client.suffix_id', '=', 'suffix.id')
                  ->whereRaw(
                      "CONCAT(client.first_name, ' ', client.middle_name, ' ', client.last_name, ' ', IFNULL(suffix.suffix, '')) LIKE ?",
                      ['%' . $request['data']['client'] . '%']
                  );
        })
        ->orderBy('created_at', 'desc')
        ->limit(100)
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

        $transactionClaimCount  = TransactionClaimModel::count();

        return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount]);
    }

    public function bulkTransactionClaimTable(Request $request)
    {
        //
         //
        
         $auth = Auth::user();
         $authID = $auth->id;
         $user = User::find($authID);
         if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('claimAssistance')) {
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



        $transactionClaim = TransactionClaimModel::with([
        'transactionClaimStatusCondition',
        'approveTransaction.transactionApproveStatusCondition',
        'approveTransaction.transactionApproveAmount',
        'approveTransaction.transaction.client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name',DB::raw('CONCAT(client.first_name," ",IFNULL(client.middle_name,"")," ",client.last_name," ",IFNULL(suffix.suffix, "")) as full_name'),'client.suffix_id','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id')->leftJoin('suffix','suffix.id','client.suffix_id');
        },
        'approveTransaction.transaction.agency',
        'approveTransaction.transaction.agencyProgram',
        'approveTransaction.transaction.assistanceType',
        'approveTransaction.transaction.assistanceDescription',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.client.suffix',
        'approveTransaction.transaction.client.civilStatus',
        'approveTransaction.transaction.client.precint',
        'approveTransaction.transaction.client.sex',
        'approveTransaction.transaction.beneficiaryTransaction' => function($query){
            $query->select('beneficiary.id','beneficiary.first_name','beneficiary.middle_name','beneficiary.last_name','beneficiary.suffix_id',DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ',beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','suffix.id');
        },
        // 'approveTransaction.transaction.beneficiaryTransaction.suffix',
        // 'approveTransaction.transaction.beneficiaryTransaction.sex',
        // 'approveTransaction.transaction.beneficiaryTransaction.civilStatus',
        // 'approveTransaction.transaction.beneficiaryTransaction.precint',
        ]
        )->whereHas('transactionClaimStatusCondition', function($query){
            $query->where('transaction_claim_cond.transaction_claim_status_id', '=', '1')->orWhere('transaction_claim_cond.transaction_claim_status_id', '=', '2');
        })
        ->orderBy('created_at', 'desc')
        ->limit(100)
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

        $transactionClaimCount  = TransactionClaimModel::count();

        $todayDate = Carbon::now();

        return response()->json(['transaction_claim' => $divideTransaction, 'transaction_claim_count' => $transactionClaimCount,'todayDate' => $todayDate->toDateString()]);
    }
}
