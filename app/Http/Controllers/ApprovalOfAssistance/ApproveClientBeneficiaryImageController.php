<?php

namespace App\Http\Controllers\ApprovalOfAssistance;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\User;


use Illuminate\Support\Facades\Auth;


class ApproveClientBeneficiaryImageController extends Controller
{
    //

    public function showClientApproveImage(Request $request): StreamedResponse
    {
        
        // if (!Storage::disk('local')->exists("client_images/{$request['data']['clientApproveImage']}")) {
        //     abort(404); // Handle file not found
        // }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewApproveAssistance')) {
            abort(400, 'Unauthorized access');
        }

    
        if(!isset($request['data']['clientApproveImage'])){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else if($request['data']['clientApproveImage'] === null || $request['data']['clientApproveImage'] === "null" || $request['data']['clientApproveImage'] === 'undefined'){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else{
            return Storage::disk('local')->response("client_images/{$request['data']['clientApproveImage']}");
        }
    }
    
    public function showBeneficiaryApproveImage(Request $request): StreamedResponse{

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewApproveAssistance')) {
            abort(400, 'Unauthorized access');
        }

        if(!isset($request['data']['beneficiaryApproveImage'])){
            return Storage::disk('local')->response("public/images/ljfloader.webp");
        }else if($request['data']['beneficiaryApproveImage'] === null || $request['data']['beneficiaryApproveImage'] === "null" || $request['data']['beneficiaryApproveImage'] === 'undefined'){
            return Storage::disk('local')->response("public/images/ljfloader.webp");
        }else{
            return Storage::disk('local')->response("beneficiary_images/{$request['data']['beneficiaryApproveImage']}");
        }
       
    }

}
