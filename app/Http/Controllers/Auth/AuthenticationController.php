<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticationController extends Controller
{
    //

    public function __construct(){
        
    }

    public function index(Request $request){

        // Check if the user is already authenticated
        
        if (Auth::check()) {
            return redirect()->intended('/request/details'); 
        }else{
            // If not authenticated, show the login page
            return view('authentication');
        }


    }

}
