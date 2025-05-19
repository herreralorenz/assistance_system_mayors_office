<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogsHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate user credentials
        $credentials = $request->validate([
            'data.login.email' => 'required|email',
            'data.login.password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt([
            'email' => $credentials['data']['login']['email'],
            'password' => $credentials['data']['login']['password']
        ])) {
            // Regenerate session for security
            $request->session()->regenerate();
            // $request->session()->regenerateToken();

            return redirect()->intended('/admin'); 
        }

        return response()->json(['unauthorize' => 'wrong credentials'],401);
        // If login fails, redirect back with errors
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('data.login.email'); // Keep only email input for UX
    }

    public function logout(Request $request)
    {
        
        Auth::guard('web')->logout(); // Logs out the user
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token

    
        return redirect('/'); // Redirect to login page
    }
}
