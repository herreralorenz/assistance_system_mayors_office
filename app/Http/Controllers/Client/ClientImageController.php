<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientImageController extends Controller
{
    //

    public function showClientImage(Request $request): StreamedResponse
    {
        
        // if (!Storage::disk('local')->exists("client_images/{$request['data']['clientApproveImage']}")) {
        //     abort(404); // Handle file not found
        // }
   
        if(!isset($request['data']['clientImage'])){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else if($request['data']['clientImage'] === null || $request['data']['clientImage'] === "null" || $request['data']['clientImage'] === 'undefined'){
            return Storage::disk('public')->response('images/ljfloader.webp');
        }else{
            return Storage::disk('local')->response("client_images/{$request['data']['clientImage']}");
        }
    }
    
  

}
