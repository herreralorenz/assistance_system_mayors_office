<?php

namespace App\Http\Controllers\SMS;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\SMSMessageModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\User;



class SMSMessageController extends Controller
{
    //

   


    public function getMessages(){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('SMSMessage')) {
            abort(400, 'Unauthorized access');
        }

        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('viewAny', $user);

        $smsMessage  = SMSMessageModel::get();

        return response()->json($smsMessage);
    }

    public function deleteMessage(Request $request, string $id){
        try{

            
            $auth = Auth::user();
            $authID = $auth->id;
            $user = User::find($authID);
            if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('SMSMessage')) {
                abort(400, 'Unauthorized access');
            }

            
            $userAuth = Auth::user();
            $userAuthID = $userAuth->id;
            
            $user= User::find($userAuthID);
    
            $this->authorize('viewAny', $user);


            DB::beginTransaction();

          
            $smsMessage = SMSMessageModel::where('id','=',$id);
            $smsMessage->delete();
            DB::commit();

            return response()->json(['message' => 'Message successfully deleted'],200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        } 
    }

    public function addMessage(Request $request){
        try{

            DB::beginTransaction();

        
            $userAuth = Auth::user();
            $userAuthID = $userAuth->id;
            $user= User::find($userAuthID);
            $this->authorize('viewAny', $user);

            $validator = Validator::make($request->all(),[
                'data.message.subject' => 'max:100|string',
                'data.message.message' => 'max:160|string',
            ]);

            if($validator->fails()){
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            }else{
                $validatedData = $validator->validated();
            }

            if($validatedData){
                SMSMessageModel::create([
                    'subject' => $request['data']['message']['subject'],
                    'message' => $request['data']['message']['message'],
                ]);
            }


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

    public function updateMessage(Request $request, string $id){

        try{

            $auth = Auth::user();
            $authID = $auth->id;
            $user = User::find($authID);
            if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('SMSMessage')) {
                abort(400, 'Unauthorized access');
            }
            
            $userAuth = Auth::user();
            $userAuthID = $userAuth->id;
            
            $user= User::find($userAuthID);
    
            $this->authorize('viewAny', $user);

         
            $validator = Validator::make($request->all(),[
                'data.message.subject' => 'max:100|string|nullable',
                'data.message.message' => 'max:160|string',
            ]);

            if($validator->fails()){
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            }else{
                $validatedData = $validator->validated();
            }

            if($validatedData){
                DB::beginTransaction();
                $smsMessage = SMSMessageModel::where('id','=',$id)->update([
                    'subject' => $request['data']['message']['subject'],
                    'message' => $request['data']['message']['message']
                ]);

                DB::commit();

                return response()->json(['message' => 'Message successfully updated'],200);
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
}
