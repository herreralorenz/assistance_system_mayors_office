<?php

namespace App\Http\Controllers\ApprovalOfAssistance;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class BulkApproveAssistanceTableController extends Controller
{
    //

    public function bulkApprove(Request $request)
    {
        //

        try{


            
                $auth = Auth::user();
                $authID = $auth->id;
                $user = User::find($authID);
                if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('bulkApproveAssistance') && !$user->hasPermissionTo('viewApproveAssistance')) {
                    abort(400, 'Unauthorized access');
                }
            // return response()->json($request);
            $validator = Validator::make($request->all(),[
                'data.amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'data.date_approve_decline' => 'required|date'
            ]);

            if(!isset($request['data']['toApprove']) || empty($request['data']['toApprove'])){
                return response()->json(['message' => 'Transaction failed'], 400);
            }else{
                foreach($request['data']['toApprove'] as $key => $value){
                    DB::beginTransaction();
    
                    $transactionApprove = TransactionApproveModel::where('transaction_id','=',$value['transaction_approve']['id'])->firstOrFail();
    
            
                    $amount = TransactionApproveAmountModel::firstOrCreate(
                        ['amount' => $transactionApprove->transaction_approve_amount_id = $request->input('data.amount')],
                        ['amount' => $transactionApprove->transaction_approve_amount_id = $request->input('data.amount')],
                    );
                    
                    $transactionApprove->transaction_approve_amount_id = $amount->id;
                    $transactionApproveID = $transactionApprove->id;
            
                    $transactionApprove->update();
        
                    $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$transactionApproveID)->firstOrFail();
                    $transactionApproveStatusCondition->remarks = null;
                    $transactionApproveStatusCondition->transaction_approve_status_id = 2;
        
        
                    $transactionApproveStatusCondition->update();
                   
        
                    $transactionClaim = TransactionClaimModel::firstOrCreate(
                        [
                            'transaction_approve_id' => $transactionApproveID
                        ],
                        [
                            'transaction_approve_id' => $transactionApproveID,
                            'claimant_id' => null,
        
                        ]
                    );
        
                    $transactionClaimID = $transactionClaim->id;
        
                    $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::firstOrCreate(
                        [
                            'transaction_claim_id' => $transactionClaimID
                        ],
                        [
                            'transaction_claim_id' => $transactionClaimID,
                            'transaction_claim_status_id' => 1,
                            'status_condition_date' =>  $request->input('data.date_approve_decline'),
                            'remarks' => null,
                        ]
                    );
    
                    $changes = [
                        'description' => 'Transaction approved.'
                    ];
                    $tranApproveModel = new TransactionApproveModel();
                    $auth = Auth::user();
                    $authID = $auth->id;
                    LogsHelper::log($authID, 7, $tranApproveModel, $transactionApprove->id, json_encode($changes));
                    DB::commit();
    
                }

                return response()->json(['message' => 'Transaction updated successfully'], 200);
            }

            

            
       

        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);

        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
        
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
    
    
            $transaction = TransactionModel::select('id','transaction_id','client_id','agency_id','agency_program_id','assistance_type_id','date_request')
            ->with(['client' => function($query){
                $query->select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','suffix.suffix as client_suffix','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', client.middle_name,' ', client.last_name,' ',IFNULL(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','=','suffix.id');
            },'client.suffix'])
            ->with(['beneficiaryTransaction' => function ($query){
                $query->select(DB::raw("CONCAT(beneficiary.first_name,' ',beneficiary.middle_name,' ', beneficiary.last_name,' ',suffix.suffix) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','=','suffix.id');
            },'beneficiaryTransaction.suffix'])
            ->with('transactionApprove.transactionApproveStatusCondition')
            ->with(['agency','agencyProgram','assistanceType','transactionApprove'])
            ->with('transactionApprove.transactionApproveStatusCondition')
            ->whereHas('transactionApprove.transactionApproveStatusCondition', function($query){
                $query->where('transaction_approve_cond.transaction_approve_status_id', '=', '1')->orWhere('transaction_approve_cond.transaction_approve_status_id', '=', '3');  // Replace with your actual field and value
            })
            ->whereRaw(
                "transaction.date_request  >= ? AND transaction.date_request <= ?",
                [$request['data']['date_from'], $request['data']['date_to']]
            )
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
    
            $transaction_approve = TransactionApproveModel::where('')->count();
    
    
            foreach($transaction as $transactionKey => &$transactionValue){
                if(isset($transactionValue['client'])){
                    $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                    $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                    $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                    $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
                }
            }
    
            // $divideTransaction = [];
            // $divideCounter = 0;
            // $arrayCounter = 0;
            // foreach($transaction as $trans){
    
            //     if($divideCounter == 5){
            //         $arrayCounter++;
            //         $divideCounter = 0;
            //     }
            //     $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            //     $divideCounter++;
            // } 
    
            
            return response()->json(['transaction' => $transaction, 'transaction_approve_count' => $transaction_approve]);
       }

       public function searchClient(Request $request){
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
    
    
            $transaction = TransactionModel::select('id','transaction_id','client_id','agency_id','agency_program_id','assistance_type_id','date_request')
            ->with(['client' => function($query){
                $query->select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','suffix.suffix as client_suffix','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', client.middle_name,' ', client.last_name,' ',IFNULL(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','=','suffix.id');
            },'client.suffix'])
            ->with(['beneficiaryTransaction' => function ($query){
                $query->select(DB::raw("CONCAT(beneficiary.first_name,' ',beneficiary.middle_name,' ', beneficiary.last_name,' ',suffix.suffix) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','=','suffix.id');
            },'beneficiaryTransaction.suffix'])
            ->with('transactionApprove.transactionApproveStatusCondition')
            ->with(['agency','agencyProgram','assistanceType','transactionApprove'])
            ->with('transactionApprove.transactionApproveStatusCondition')
            ->whereHas('transactionApprove.transactionApproveStatusCondition', function($query){
                $query->where('transaction_approve_cond.transaction_approve_status_id', '=', '1')->orWhere('transaction_approve_cond.transaction_approve_status_id', '=', '3');  // Replace with your actual field and value
            })
            ->whereHas('client', function($query) use ($request) {
                $query->leftJoin('suffix', 'client.suffix_id', '=', 'suffix.id')
                      ->whereRaw(
                          "CONCAT(client.first_name, ' ', client.middle_name, ' ', client.last_name, ' ', IFNULL(suffix.suffix, '')) LIKE ?",
                          ['%' . $request['data']['client'] . '%']
                      );
            })
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
    
            $transaction_approve = TransactionApproveModel::count();
    
    
            foreach($transaction as $transactionKey => &$transactionValue){
                if(isset($transactionValue['client'])){
                    $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                    $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                    $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                    $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
                }
            }
    
            // $divideTransaction = [];
            // $divideCounter = 0;
            // $arrayCounter = 0;
            // foreach($transaction as $trans){
    
            //     if($divideCounter == 5){
            //         $arrayCounter++;
            //         $divideCounter = 0;
            //     }
            //     $divideTransaction[$arrayCounter][$divideCounter] = $trans;
            //     $divideCounter++;
            // } 
    
    
            return response()->json(['transaction' => $transaction, 'transaction_approve_count' => $transaction_approve]);
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


        $transaction = TransactionModel::select('id','transaction_id','client_id','agency_id','agency_program_id','assistance_type_id','date_request')
        ->with(['client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','suffix.suffix as client_suffix','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', client.middle_name,' ', client.last_name,' ',IFNULL(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','=','suffix.id');
        },'client.suffix'])
        ->with(['beneficiaryTransaction' => function ($query){
            $query->select(DB::raw("CONCAT(beneficiary.first_name,' ',beneficiary.middle_name,' ', beneficiary.last_name,' ',suffix.suffix) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','=','suffix.id');
        },'beneficiaryTransaction.suffix'])
        ->with('transactionApprove.transactionApproveStatusCondition')
        ->with(['agency','agencyProgram','assistanceType','transactionApprove'])
        ->with('transactionApprove.transactionApproveStatusCondition')
        ->whereHas('transactionApprove.transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', '1')->orWhere('transaction_approve_cond.transaction_approve_status_id', '=', '3');  // Replace with your actual field and value
        })
        ->where('transaction.transaction_id','LIKE','%'.$request['data']['transactionID'].'%')
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();

        $transaction_approve = TransactionApproveModel::count();


        foreach($transaction as $transactionKey => &$transactionValue){
            if(isset($transactionValue['client'])){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
            }
        }

        // $divideTransaction = [];
        // $divideCounter = 0;
        // $arrayCounter = 0;
        // foreach($transaction as $trans){

        //     if($divideCounter == 5){
        //         $arrayCounter++;
        //         $divideCounter = 0;
        //     }
        //     $divideTransaction[$arrayCounter][$divideCounter] = $trans;
        //     $divideCounter++;
        // } 


        return response()->json(['transaction' => $transaction, 'transaction_approve_count' => $transaction_approve]);
   }

   


   public function bulkTransactionApproveTable(){
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


        $transaction = TransactionModel::select('id','transaction_id','client_id','agency_id','agency_program_id','assistance_type_id','date_request')
        ->with(['client' => function($query){
            $query->select('client.id','client.first_name','client.middle_name','client.last_name','client.suffix_id','suffix.suffix as client_suffix','client.birthdate','client.street','client.region_id','client.province_id','client.city_id','client.barangay_id',DB::raw("CONCAT(client.first_name, ' ', IFNULL(client.middle_name,''),' ', client.last_name,' ',IFNULL(suffix.suffix, '')) as full_name"))->leftJoin('suffix','client.suffix_id','=','suffix.id');
        },'client.suffix'])
        ->with(['beneficiaryTransaction' => function ($query){
            $query->select(DB::raw("CONCAT(beneficiary.first_name,' ',IFNULL(beneficiary.middle_name,''),' ', beneficiary.last_name,' ',IFNULL(suffix.suffix,'')) as full_name"))->leftJoin('suffix','beneficiary.suffix_id','=','suffix.id');
        },'beneficiaryTransaction.suffix'])
        ->with('transactionApprove.transactionApproveStatusCondition')
        ->with(['agency','agencyProgram','assistanceType','transactionApprove'])
        ->whereHas('transactionApprove.transactionApproveStatusCondition', function($query){
            $query->where('transaction_approve_cond.transaction_approve_status_id', '=', '1')->orWhere('transaction_approve_cond.transaction_approve_status_id', '=', '3');  // Replace with your actual field and value
        })
        ->orderBy('created_at', 'desc')
        ->limit(100)
        ->get();


        foreach($transaction as $transactionKey => &$transactionValue){
            if(isset($transactionValue['client'])){
                $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
                $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
                $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
                $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
            }
        }

        // $divideTransaction = [];
        // $divideCounter = 0;
        // $arrayCounter = 0;
        // foreach($transaction as $trans){

        //     if($divideCounter == 5){
        //         $arrayCounter++;
        //         $divideCounter = 0;
        //     }
        //     $divideTransaction[$arrayCounter][$divideCounter] = $trans;
        //     $divideCounter++;
        // } 

        $todayDate = Carbon::now();

        return response()->json(['transaction' => $transaction, 'dateToday' => $todayDate->toDateString()]);
   }

   
}
