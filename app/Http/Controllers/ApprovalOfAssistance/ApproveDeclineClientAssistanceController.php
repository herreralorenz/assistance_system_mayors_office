<?php

namespace App\Http\Controllers\ApprovalOfAssistance;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TransactionApproveModel;
use App\Models\TransactionApproveStatusConditionModel;
use App\Models\TransactionApproveAmountModel;
use App\Models\TransactionClaimModel;
use App\Models\TransactionClaimStatusConditionModel;
use App\Models\ClaimantModel;
use App\Models\ClaimantImageModel;
use App\Models\ClaimantContactNumberModel;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;


use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApproveDeclineClientAssistanceController extends Controller
{
    //

    public function updateApprovedClient(Request $request, string $id){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('editApproveAssistance') && !$user->hasPermissionTo('viewApproveAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(),[
            'data.remarks' => 'max:250|nullable|string',
            'data.amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'data.date_approve_decline' => 'date|required'
        ]);


        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }

        if($validatedData){
            try{
                DB::beginTransaction();

                $transactionApproveAppsorveStatusCondition = TransactionApproveStatusConditionModel::
                whereHas('transactionApprove.transaction', function ($query) use ($id){
                    $query->where('transaction.id','=',$id);
                })->update(
                    [
                        'remarks' => $request['data']['remarks'],
                        'status_condition_date' => $request['data']['date_approve_decline']
                    ]
                );

                $transactionApproveAmount = TransactionApproveAmountModel::firstOrCreate(
                    ['amount' => $request['data']['amount']],
                    ['amount' => $request['data']['amount']]

                );
                

                $transactionApproveAmountID = $transactionApproveAmount->id;



                $transactionApprove = TransactionApproveModel::
                where('transaction_id','=',$id)
                ->whereHas('transactionApproveStatusCondition',function ($query){
                    $query->where('transaction_approve_status_id','=','2');
                })
                ->update(
                    ['transaction_approve_amount_id' => $transactionApproveAmountID]
                );

                $changes = [
                    'description' => 'Transaction approve updated.'
                ];
                $tranApproveModel = new TransactionApproveModel();
                $auth = Auth::user();
                $authID = $auth->id;
                LogsHelper::log($authID, 2, $tranApproveModel, $id, json_encode($changes));


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


    public function declineClient(Request $request, string $id)
    {
        //

            $auth = Auth::user();
            $authID = $auth->id;
            $user = User::find($authID);
            if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('declineAssistance') && !$user->hasPermissionTo('viewApproveAssistance')) {
                abort(400, 'Unauthorized access');
            }


            $validator = Validator::make($request->all(),[
                'data.remarks' => 'max:250|nullable|string',
                'data.date_approve_decline' => 'date|required'
            ]);

            if($validator->fails()){
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            }else{
                $validatedData = $validator->validated();
                // return response()->json(['success' => $validatedData],200);
            }

            if($validatedData){
                try{
                    DB::beginTransaction();

                    $transactionApprove = TransactionApproveModel::where('transaction_id','=',$id)->firstOrFail();
                    $transactionApprove->transaction_approve_amount_id = null;
                    $transactionApproveID = $transactionApprove->id;
       

                    $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$transactionApproveID)->firstOrFail();
                    $transactionApproveStatusCondition->status_condition_date = $request->input('data.date_approve_decline');
                    $transactionApproveStatusCondition->remarks = $request->input('data.remarks');
                    $transactionApproveStatusCondition->transaction_approve_status_id = 3;
                    
                    $transactionApprove->update();
                    $transactionApproveStatusCondition->update();


                    $transactionClaim = TransactionClaimModel::where('transaction_approve_id','=',$transactionApproveID)->first();

                    if($transactionClaim){
                        $transactionClaimID = $transactionClaim->id;
                        $transactionClaimant = $transactionClaim->claimant_id;
                        
                        $transactionClaimStatusCondition = TransactionClaimStatusConditionModel::where('transaction_claim_id','=',$transactionClaimID)->first();
                        
                        $transactionClaimStatusCondition->delete();
                        $transactionClaim->delete();

                        $claimant = ClaimantModel::find($transactionClaimant);

                        if($claimant){
                            $claimantID = $claimant->id;
                
                            $claimantContactNumber = ClaimantContactNumberModel::where('claimant_id','=',$claimantID)->first();
                            $claimantImage = ClaimantImageModel::where('claimant_id','=',$claimantID)->first();
                            $claimantImageFileName = $claimantImage->file_name;
                
                            $filePath = 'claimant_images/'+$claimantImageFileName;
                
                            Storage::delete($filePath);
                            $claimantImage->delete();
                            $claimantContactNumber->delete();
                            $claimant->delete();
                        }
                        
                    }
            
                    $changes = [
                        'description' => 'Transaction declined.'
                    ];
                    $tranApproveModel = new TransactionApproveModel();
                    $auth = Auth::user();
                    $authID = $auth->id;
                    LogsHelper::log($authID, 8, $tranApproveModel, $transactionApprove->id, json_encode($changes));

                    DB::commit();

                    return response()->json(['message' => 'Transaction declined successfully'], 200);
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

    public function approveClient(Request $request, string $id)
    {
        //

        
        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('approveAssistance') && !$user->hasPermissionTo('viewApproveAssistance')) {
            abort(400, 'Unauthorized access');
        }

        $validator = Validator::make($request->all(),[
            'data.remarks' => 'max:250|nullable|string',
            'data.amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'data.date_approve_decline' => 'date|required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors()->all();
            return response()->json(['failed' => $errors]);
        }else{
            $validatedData = $validator->validated();
            // return response()->json(['success' => $validatedData],200);
        }

        if($validatedData){
            try{

                DB::beginTransaction();
    
                $transactionApprove = TransactionApproveModel::where('transaction_id','=',$id)->firstOrFail();;
    
            
                $amount = TransactionApproveAmountModel::firstOrCreate(
                    ['amount' => $request->input('data.amount')],
                    ['amount' => $request->input('data.amount')],
                );
                
                $transactionApprove->transaction_approve_amount_id = $amount->id;
                $transactionApproveID = $transactionApprove->id;
        
                $transactionApprove->update();
    
                $transactionApproveStatusCondition = TransactionApproveStatusConditionModel::where('transaction_approve_id','=',$transactionApproveID)->firstOrFail();
                $transactionApproveStatusCondition->remarks = $request->input('data.remarks');
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
                        'status_condition_date' => $request->input('data.date_approve_decline'),
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
