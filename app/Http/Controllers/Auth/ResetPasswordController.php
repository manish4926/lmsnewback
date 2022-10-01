<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Auth;
use Password; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request)
    {
        if (isset($_SERVER['QUERY_STRING'])) {
        $token = $_SERVER['QUERY_STRING'];
        } else {
            $token = "";
        }
        //dd($token);
        if(empty($token)) {
            return view('auth.passwords.email');
        } else {
            return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]    
            );
        }
        
        
    }
}
