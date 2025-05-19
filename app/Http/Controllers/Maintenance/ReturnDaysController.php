<?php

namespace App\Http\Controllers\Maintenance;

use App\Helpers\LogsHelper;
use Illuminate\Http\Request;



use App\Models\SettingsModel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ReturnDaysController extends Controller
{
    //
    public function returnDays(Request $request){

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('returnDays')) {
            abort(400, 'Unauthorized access');
        }


        $returnDays = SettingsModel::first();

        return response()->json(['return_days' => $returnDays['return_days']]);
    }

    public function updateReturnDays(Request $request){
        try{


            $auth = Auth::user();
                $authID = $auth->id;
                $user = User::find($authID);
                if (!$user->hasRole('superAdmin') || !$user->hasPermissionTo('returnDays')) {
                    abort(400, 'Unauthorized access');
                }

            DB::beginTransaction();

            $returnDays = SettingsModel::first();
            $returnDays->return_days = $request['data']['returnDays'];
            $returnDays->update();

            $changes = [
                'description' => 'Return days updated'
            ];

            $settingsModel = new SettingsModel();
    
            $auth = Auth::user();
            $authID = $auth->id;
    
            LogsHelper::log($authID, 2, $settingsModel, $authID, json_encode($changes));

            DB::commit();
            return response()->json(['success' => 'Successfully updated'], 200);
        }catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user', 'error' => $e], 500);
        } catch (ModelNotFoundException $e) {
            // if user is not found throws a 404 response
            DB::rollBack();
            return response()->json(['message' => 'Transaction not found'], 404);
        }
       

    }
}
