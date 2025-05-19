<?php

namespace App\Http\Controllers\ClaimingOfAssistance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\TransactionModel;
use App\Models\ClientBeneficiaryRelationshipModel;
use App\Models\AddressMetadataModel;

use App\Models\ClaimantModel;
use App\Models\ClaimantContactNumberModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionClaimStatusConditionModel;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ClaimAssistanceQRController extends Controller
{
    //


    public function claimAssistance(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('claimAssistance')  && !$user->hasPermissionTo('viewClaimAssistance')) {
            abort(400, 'Unauthorized access');
        }
        
        $validator = Validator::make($request->all(),[
            'data.claimant.claimDate' => 'date|required',
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }

        if($validatedData){

            if(!empty($request['data']['claimant']['first_name']) && !empty($request['data']['claimant']['last_name']) && !empty($request['data']['claimant']['contact_number'])){
                
                try{
                    DB::beginTransaction();

                    $claimant = ClaimantModel::firstOrCreate(
                        [
                            'first_name' => $request['data']['claimant']['first_name'],
                            'middle_name' => $request['data']['claimant']['middle_name'],
                            'last_name' => $request['data']['claimant']['last_name'],
                            'suffix_id' => $request['data']['claimant']['suffix'],
                        ],
                        [
                            'first_name' => strtoupper($request['data']['claimant']['first_name']) ?? null,
                            'middle_name' => strtoupper($request['data']['claimant']['middle_name']) ?? null,
                            'last_name' => strtoupper($request['data']['claimant']['last_name']) ?? null,
                            'suffix_id' => strtoupper($request['data']['claimant']['suffix']) ?? null,
                        ]
                    );

                    $claimantID = $claimant->id;

                    if($claimantID){
                        ClaimantContactNumberModel::firstOrCreate(
                            [
                                'claimant_id' => $claimantID,
                                'contact_number' => $request['data']['claimant']['contact_number'],
                            ],
                            [
                                'contact_number' => $request['data']['claimant']['contact_number'], 
                            ]
                        );

                    $transactionClaim = TransactionClaimModel::
                    whereHas('approveTransaction.transaction', function($query) use ($request){
                        $query->where('transaction.transaction_id','=',$request['data']['transactionID']);
                    })
                    ->firstOrFail();

                    $transactionClaim->claimant_id = $claimantID;

                    $transactionClaim->update();

                    $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::
                    whereHas('transactionClaim.approveTransaction.transaction', function($query) use ($request){
                        $query->where('transaction._transactionid','=',$request['data']['transactionID']);
                    })
                    ->firstOrFail();
                    
                    $transactionClaimStatusCondition->transaction_claim_status_id = 3;
                    $transactionClaimStatusCondition->remarks = $request['data']['claimant']['remarks'];
                    $transactionClaimStatusCondition->update();

                    DB::commit();

                    return response()->json(['message' => 'Transaction updated successfully'], 200);
                    
                    }else{

                        return response()->json(['message' => 'Transaction failed'], 400);

                    }
                }catch(Exception $e){
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        
                }catch (ModelNotFoundException $e) {
                    // if user is not found throws a 404 response
                    DB::rollBack();
                    return response()->json(['message' => 'Transaction not found'], 404);
                }
            }else{
                try{
                    DB::beginTransaction();
                
                    $transactionClaim = TransactionClaimModel::
                    whereHas('approveTransaction.transaction', function($query) use ($request){
                        $query->where('transaction.transaction_id','=',$request['data']['transactionID']);
                    })
                    ->firstOrFail();
    
                    $transactionClaimID = $transactionClaim->id;
    
                    $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::
                    whereHas('transactionClaim.approveTransaction.transaction', function($query) use ($request){
                        $query->where('transaction.transaction_id','=',$request['data']['transactionID']);
                    })
                    ->firstOrFail();
                    $transactionClaimStatusCondition->transaction_claim_status_id = 3;
                    $transactionClaimStatusCondition->update();
    
                    DB::commit();
    
                    return response()->json(['message' => 'Transaction updated successfully'], 200);
                }catch(Exception $e){
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        
                }catch (ModelNotFoundException $e) {
                    // if user is not found throws a 404 response
                    DB::rollBack();
                    return response()->json(['message' => 'Transaction not found'], 404);
                }
       
            }

        }

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


        $transactionModel = TransactionModel::with([
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
        ->get();

        $transactionsArray = $transactionModel->toArray();


       $transactionsArray = $transactionModel->toArray();

       foreach($transactionsArray  as $transactionKey => &$transactionValue){

         
           if(isset($transactionValue['client'])){
               $transactionValue['client']['region'] = $addressMapped[$transactionValue['client']['region_id']]['region_key'];
               $transactionValue['client']['province'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']][$transactionValue['client']['province_id']];
               $transactionValue['client']['city'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']][$transactionValue['client']['city_id']];
               $transactionValue['client']['barangay'] = $addressMapped[$transactionValue['client']['region_id']]['province_list'][$transactionValue['client']['province_id']]['municipality_list'][$transactionValue['client']['city_id']]['barangay_list'][$transactionValue['client']['barangay_id']][$transactionValue['client']['barangay_id']];
           }

           if(isset($transactionValue['beneficiary_transaction'])){
               foreach($transactionValue['beneficiary_transaction'] as $beneficiaryKey => &$beneficiaryValue){
                       $beneficiaryValue['region'] = $addressMapped[$beneficiaryValue['region_id']]['region_key'];
                       $beneficiaryValue['province'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']][$beneficiaryValue['province_id']];
                       $beneficiaryValue['city'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']][$beneficiaryValue['city_id']];
                       $beneficiaryValue['barangay'] = $addressMapped[$beneficiaryValue['region_id']]['province_list'][$beneficiaryValue['province_id']]['municipality_list'][$beneficiaryValue['city_id']]['barangay_list'][$beneficiaryValue['barangay_id']][$beneficiaryValue['barangay_id']];
               
               }
           }
       }

        return response()->json($transactionsArray);
    }
}
