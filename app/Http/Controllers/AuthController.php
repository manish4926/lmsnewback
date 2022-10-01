<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Role;

use Auth;
use Session;
use Validator;
use View;
use Cookie;
use Carbon\Carbon;
use App\Http\Requests;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $profile = !empty($user) ? $user->getProfile() : '';
            if(empty($profile)) {
                $profile = array();
                $profile['theme']['options'] = '';
                $profile['sidebar']['options'] = '';
                $profile['dataversion']['options'] = '';
            }
            $this->userProfile = $profile;
            View::share(['userProfile' => $this->userProfile ]);
            return $next($request);
        });
    }


    public function register(Request $request)     //Home Page
    {

        /*if (Auth::check()) {
            return redirect()->route('dashboard');
        }*/

        return view('register');
    }

    public function postRegister(Request $request)
	{
		$this->validate($request, [
			'fname' => 'required|alpha',
			'lname' => 'required|alpha',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required'
        ]);

        $firstname 		= $request['fname'];
		$lastname 		= $request['lname'];
		$email 			= $request['email'];
		$password 		= bcrypt($request['password']);
		
		$user = new User;
		$user->firstname = $firstname;
		$user->lastname = $lastname;
		$user->email = $email;
		$user->password = $password;
		$user->save();
		$user->roles()->attach(Role::where('name','Counsellor')->first());

		//Auth::login($user, true);
		if($request->ajax()){
			
            return "Success";
        }
		//return redirect()->back();
        return redirect()->route('dashboard');
	}

    public function login(Request $request)     //Home Page
    {

        if (Auth::check()) {
            $user = Auth::user();
            if($user->hasRole('Admin')) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('followuplist');
        }
        $theme = json_decode($request->cookie('theme'),true);

        return view('login',compact('theme'));
    }


	public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'pass' => 'required'
        ]);
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['pass'], 'status' => 1])) {
        		$user = Auth::user();
	        	if($request->ajax()){
	                return "Success";
	            }
                if($user->hasRole('Admin') == true){
                    return redirect()->route('dashboard');
                }
                if($user->hasRole('FrontDesk') == true){
                    return redirect()->route('addform');
                }
	            return redirect()->route('dashboard');	//In this true will remember your password
	        }
	    if($request->ajax()){
            return "Failed";
        }
        Session::flash('popupMessage', 'popup-login');
        return redirect()->back()->withInput();
    }


    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('dashboard');
    }

    public function assignRoles(Request $request)
    {
        $user = Auth::user();
        $userslist = User::whereHas('roles', function($q)
                        {
                            $q->where('name', '=', 'Admin');
                            $q->orWhere('name', '=', 'Counsellor');
                            $q->orWhere('name', '=', 'FrontDesk');
                        })
                    ->get();
        return view('admin.roles',compact('user','userslist'));
    }
    public function postAdminAssignRoles(Request $request)
    {
        $user = User::where('role_id', $request['email'])->first();
        $user->roles()->detach();
        if($request['role_admin']) {
            $user->roles()->attach(Role::where('name','Admin')->first());
        }
        if($request['role_counsellor']) {
            $user->roles()->attach(Role::where('name','Counsellor')->first());
        }
        if($request['role_frontdesk']) {
            $user->roles()->attach(Role::where('name','FrontDesk')->first());
        }
        
        if(empty($request['role_admin']) AND empty($request['role_counsellor']) AND empty($request['role_frontdesk']))
        {
            $user->roles()->attach(Role::where('name','Counsellor')->first());
        }
        return redirect()->back();
    }

    public function profileOptions(Request $request)
    {
        $user = Auth::user();
        $profile = $user->getProfile();

        
        return view('user.profileoptions',compact('user', 'profile','request'));
    }

    public function profileOptionsSubmit(Request $request)
    {
        $user = Auth::user();
        $user = User::where('email', $user->email)->first();
        $user->profile()->detach();
        $user->profile()->attach('',['category' => 'theme', 'options' => $request->theme]);
        $user->profile()->attach('', ['category' => 'sidebar', 'options' => $request->sidebar]);
        $user->profile()->attach('', ['category' => 'dataversion', 'options' => $request->dataversion]);


        $theme = json_encode(array('themecolor' => $request->theme, 'themebackground' => ''));
        Cookie::queue('theme', $theme, 2628000);


        Session::flash('successMessage', 'Profile Successfully Updated');
        return redirect()->back();
        
    }

}
