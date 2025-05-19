<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ClientBeneficiaryTransactionImageController extends Controller
{
    //

    public function showClientTransactionImage(Request $request): StreamedResponse
    {

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }
        
        // if (!Storage::disk('local')->exists("client_images/{$request['data']['clientApproveImage']}")) {
        //     abort(404); // Handle file not found
        // }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        if(!isset($request['data']['clientTransactionImage'])){
            return Storage::disk('local')->response("public/images/congonylogo.webp");
        }else if($request['data']['clientTransactionImage'] === null || $request['data']['clientTransactionImage'] === "null" || $request['data']['clientTransactionImage'] === 'undefined'){
            return Storage::disk('local')->response("public/images/congonylogo.webp");
        }else{
            return Storage::disk('local')->response("client_images/{$request['data']['clientTransactionImage']}");
        }
    }
    
    public function showBeneficiaryTransactionImage(Request $request): StreamedResponse{

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewTransaction')) {
            abort(400, 'Unauthorized access');
        }

        if(!isset($request['data']['beneficiaryTransactionImage'])){
            return Storage::disk('local')->response("public/images/congonylogo.webp");
        }else if($request['data']['beneficiaryTransactionImage'] === null || $request['data']['beneficiaryTransactionImage'] === "null" || $request['data']['beneficiaryTransactionImage'] === 'undefined'){
            return Storage::disk('local')->response("public/images/congonylogo.webp");
        }else{
            return Storage::disk('local')->response("beneficiary_images/{$request['data']['beneficiaryTransactionImage']}");
        }
       
    }
}
