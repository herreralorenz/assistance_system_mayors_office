<?php

namespace App\Http\Controllers\ClaimingOfAssistance;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClaimClientBeneficiaryImageController extends Controller
{
    //

    public function showClientClaimImage(Request $request): StreamedResponse
    {
        
        // if (!Storage::disk('local')->exists("client_images/{$request['data']['clientApproveImage']}")) {
        //     abort(404); // Handle file not found
        // }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
            abort(400, 'Unauthorized access');
        }

        if(!isset($request['data']['clientClaimImage'])){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else if($request['data']['clientClaimImage'] === 'undefined'){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else{
            return Storage::disk('local')->response("client_images/{$request['data']['clientClaimImage']}");
        }
    }
    
    public function showBeneficiaryClaimImage(Request $request): StreamedResponse{

        // if (!Storage::disk('local')->exists("beneficiary_images/{$request['data']['beneficiaryApproveImage']}")) {
        //     abort(404); // Handle file not found
        // }

        $auth = Auth::user();
        $authID = $auth->id;
        $user = User::find($authID);
        if (!$user->hasRole('superAdmin') && !$user->hasPermissionTo('viewClaimAssistance')) {
            abort(400, 'Unauthorized access');
        }
        
        if(!isset($request['data']['beneficiaryClaimImage'])){
            return Storage::disk('local')->response("public/images/ljfloader.webp");
        }else if($request['data']['beneficiaryClaimImage'] === null || $request['data']['beneficiaryClaimImage'] === "null" ||  $request['data']['beneficiaryClaimImage'] === 'undefined'){
            return Storage::disk('local')->response("public/images/ljfloader.webp");
        }else{
            return Storage::disk('local')->response("beneficiary_images/{$request['data']['beneficiaryClaimImage']}");
        }
       
    }

}
