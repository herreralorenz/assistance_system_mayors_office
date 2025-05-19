<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
class AdminController extends Controller
{
    //
    public function __construct(){


    
   
    }

    public function index()
    {
    
        // Cookie::queue(Cookie::forget('XSRF-TOKEN'));
            return view('admin');
        
    }
}
