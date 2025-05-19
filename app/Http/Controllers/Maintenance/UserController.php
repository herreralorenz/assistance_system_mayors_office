<?php

namespace App\Http\Controllers\Maintenance;

use App\Helpers\LogsHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;





use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    //


    public function deleteUserDetails(string $id){
        try{

            DB::beginTransaction();

            $userAuth = Auth::user();
            $userAuthID = $userAuth->id;
            $user= User::find($userAuthID);
    
            $this->authorize('delete', $user);

            $userDel = User::where('users.id','!=',1)->where('users.id','=',$id);
            $userDel->delete();

            $changes = [
                'description' => 'User deleted.'
            ];

            $userModel = new User();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 8, $userModel, $authID, json_encode($changes));

          
            DB::commit();

            return response()->json(['success' => 'User successfully deleted'], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
        } catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    public function updateUserDetails(Request $request, string $id){
        try{

            $userAuth = Auth::user();
            $userAuthID = $userAuth->id;
            
            $user= User::find($userAuthID);
    
            $this->authorize('update', $user);

            Validator::extend('email_validation', function ($attribute, $value, $parameters, $validator) use ($id,$request) {
             $user = User::find($id);

             if($user->email === $request['data']['user'][0]['email']){
                return true;
             }else if(User::where('email','=',$request['data']['user'][0]['email'])->first()){
                return false;
             }
                
            });

            $validator = Validator::make($request->all(), [
                'data.user.0.last_name' => 'max:250|required|string',
                'data.user.0.first_name' => 'max:250|required|string',
                'data.user.0.middle_name' => 'max:250|nullable|string',
                'data.user.0.suffix' => 'nullable|exists:suffix,id',
                'data.user.0.email' => 'required|email|email_validation',
                'data.user.0.password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            } else {
                $validatedData = $validator->validated();
                // return response()->json(['success' => $validatedData],200);
            }

            if($validatedData){
                DB::beginTransaction();
                $userUpdate = User::where('users.id','=',$id)
                ->where('users.id','!=',1);

                $userUpdate->update(
                    [
                        'last_name' => $request['data']['user'][0]['last_name'],
                        'first_name' => $request['data']['user'][0]['first_name'],
                        'middle_name' => $request['data']['user'][0]['middle_name'],
                        'suffix_id'  => $request['data']['user'][0]['suffix'],
                        'email' => $request['data']['user'][0]['email'],
                        'password' => Hash::make($request['data']['user'][0]['password']),
                    ]
                );
               
                $changes = [
                    'description' => 'User updated.'
                ];
    
                $userModel = new User();
        
                $auth = Auth::user();
                $authID = $auth->id;
        
                LogsHelper::log($authID, 2, $userModel, $authID, json_encode($changes));

                DB::commit();

                
                return response()->json(['success' => $validatedData], 200);
            }

            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
        } catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'User not found'], 404);
        }
     
    }

    public function addUserDetails(Request $request){
        try{

           

            $validator = Validator::make($request->all(), [
                'data.user.0.last_name' => 'max:250|required|string',
                'data.user.0.first_name' => 'max:250|required|string',
                'data.user.0.middle_name' => 'max:250|nullable|string',
                'data.user.0.suffix' => 'nullable|exists:suffix,id',
                'data.user.0.email' => 'required|email|unique:users,email',
                'data.user.0.password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.* )(?=.*[^a-zA-Z0-9]).{8,16}$/'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->json(['failed' => $errors]);
            } else {
                $validatedData = $validator->validated();
                // return response()->json(['success' => $validatedData],200);
            }

            if($validatedData){
                DB::beginTransaction();
                User::create(
                    [
                        'last_name' => strtoupper($request['data']['user'][0]['last_name']) ?? null,
                        'first_name' => strtoupper($request['data']['user'][0]['first_name']) ?? null,
                        'middle_name' => strtoupper($request['data']['user'][0]['middle_name']) ?? null,
                        'suffix_id'  => $request['data']['user'][0]['suffix'],
                        'email' => $request['data']['user'][0]['email'],
                        'password' => Hash::make($request['data']['user'][0]['password']),
                    ]
                    );
               
    
                $changes = [
                    'description' => 'User added.'
                ];
    
                $userModel = new User();
        
                $auth = Auth::user();
                $authID = $auth->id;
        
                LogsHelper::log($authID, 1, $userModel, $authID, json_encode($changes));

                DB::commit();

                
                return response()->json(['success' => $validatedData], 200);
            }

            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
        } catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        }
     
    }

    public function getUserDetails(string $id){
        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('viewAny',$user);

        
        $user = User::
        leftJoin('suffix','suffix.id','=','users.suffix_id')
        ->select('users.*','suffix.id as suffix_id','suffix.suffix as suffix')
        ->where('users.id','=',$id)
        ->where('users.id','!=','1')
        ->get();


        return response()->json(['user' => $user]);
        
    }

    public function getUsers(){

        $userAuth = Auth::user();
        $userAuthID = $userAuth->id;
        
        $user= User::find($userAuthID);

        $this->authorize('viewAny',$user);
        

        $user = User::
        with('suffix')
        ->where('id','!=','1')
        ->get();

        $countUsers = $user->count();
        $userArray = $user->toArray();

        $userDivide = [];
        $col = 0;
        $row = 0;
        foreach($userArray as $userKey => $userValue){

            if($col == 5){
                $row++;
                $col=0;
            }
            $userDivide[$row][$col] = $userValue;
            $col++;
        }
        return response()->json(['users' => $userDivide, 'countUsers' => $countUsers]);
    }
}
