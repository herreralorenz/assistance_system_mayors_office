<?php

namespace App\Http\Controllers\ClaimingOfAssistance;

use App\Helpers\LogsHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ClaimantModel;
use App\Models\ClaimantContactNumberModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionClaimStatusConditionModel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ClaimUnclaimClientAssistanceController extends Controller
{
    //

    public function updateClaimedClient(Request $request, string $id){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('claimAssistance')) {
            abort(400, 'Unauthorized access');
        }


        $validator = Validator::make($request->all(),[
            'data.claimant.claim_date' => 'date|required',
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
                            'first_name' => strtoupper($request['data']['claimant']['first_name']) ?? null,
                            'middle_name' => strtoupper($request['data']['claimant']['middle_name']) ?? null,
                            'last_name' => strtoupper($request['data']['claimant']['last_name']) ?? null,
                            'suffix_id' => $request['data']['claimant']['suffix'] ? strtoupper($request['data']['claimant']['suffix']) : null,
                        ]
                    );

                    $claimantID = $claimant->id;


                    $claimantContactNumber = ClaimantContactNumberModel::updateOrCreate(
                        [
                            'claimant_id' => $claimantID,
                            'contact_number' => $request['data']['claimant']['contact_number']
                        ],
                        [
                            'contact_number' => $request['data']['claimant']['contact_number']
                        ]
                    );
                
       


                    $transactionClaim = TransactionClaimModel::
                    whereHas('approveTransaction.transaction',function($query) use ($id){
                        $query->where('transaction.id','=',$id);
                    })
                    ->firstOrFail();


                    $transactionClaim->claimant_id = $claimantID;
                    $transactionClaim->update();

                    $changes = [
                        'description' => 'Transaction claim updated.'
                    ];
                    $transClaimModel = new TransactionClaimModel();
        
                    $auth = Auth::user();
                    $authID = $auth->id;
        
                    LogsHelper::log($authID, 2, $transClaimModel, $authID, json_encode($changes));

                    DB::commit();

                    return response()->json(['message' => 'Transaction updated successfully'], 200);

                }catch(ModelNotFoundException $e){
                    DB::rollBack();
                    return response()->json(['message' => 'Transaction not found'], 404);

                }catch(Exception $e){
                    DB::rollBack();
                    return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
                }
                
            }

        }
    }

    public function claimClient(Request $request, string $id){


        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('claimAssistance') && !$user->hasPermissionTo('claimAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(),[
            'data.claimant.claim_date' => 'date|required',
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
                            'suffix_id' => $request['data']['claimant']['suffix'] ? strtoupper($request['data']['claimant']['suffix']) : null,
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
                    whereHas('approveTransaction.transaction', function($query) use ($id){
                        $query->where('transaction.id','=',$id);
                    })
                    ->firstOrFail();

                    $transactionClaim->claimant_id = $claimantID;

                    $transactionClaim->update();

                    $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::
                    whereHas('transactionClaim.approveTransaction.transaction', function($query) use ($id){
                        $query->where('transaction.id','=',$id);
                    })
                    ->firstOrFail();
                    
                    $transactionClaimStatusCondition->transaction_claim_status_id = 3;
                    $transactionClaimStatusCondition->remarks = $request['data']['claimant']['remarks'];
                    $transactionClaimStatusCondition->update();

                    $changes = [
                        'description' => 'Transaction claimed.'
                    ];
                    $transClaimModel = new TransactionClaimModel();
        
                    $auth = Auth::user();
                    $authID = $auth->id;
        
                    LogsHelper::log($authID, 6, $transClaimModel, $authID, json_encode($changes));

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
                    whereHas('approveTransaction.transaction', function($query) use ($id){
                        $query->where('transaction.id','=',$id);
                    })
                    ->firstOrFail();
    
                    $transactionClaimID = $transactionClaim->id;
    
                    $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::
                    whereHas('transactionClaim.approveTransaction.transaction', function($query) use ($id){
                        $query->where('transaction.id','=',$id);
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
    

    public function unclaimClient(Request $request, string $id){
        try{

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance') && !$user->hasPermissionTo('unclaimAssistance')) {
            abort(400, 'Unauthorized access');
        }    

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('unclaimAssistance')) {
            abort(400, 'Unauthorized access');
        }

        DB::beginTransaction();

         $transactionClaim = TransactionClaimModel::
         whereHas('approveTransaction.transaction', function($query) use ($id){
            $query->where('transaction.id','=',$id);
        })
         ->firstOrFail();
         

         $transactionClaim->claimant_id = null;

         $transactionClaim->update();

        $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::
        whereHas('transactionClaim.approveTransaction.transaction', function($query) use ($id){
            $query->where('transaction.id','=',$id);
        })
        ->firstOrFail();
        
        $transactionClaimStatusCondition->transaction_claim_status_id = 2;

        $transactionClaimStatusCondition->update();

        $changes = [
            'description' => 'Transaction unclaimed.'
        ];
        $transClaimModel = new TransactionClaimModel();

        $auth = Auth::user();
        $authID = $auth->id;

        LogsHelper::log($authID, 5, $transClaimModel, $authID, json_encode($changes));

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
