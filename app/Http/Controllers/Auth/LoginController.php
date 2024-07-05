<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'dashboard';
    //RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /*
    public function login(Request $request)
    {
        // dd($request->all());
        $loginData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($loginData['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        // dd($field );
        if (Auth::attempt([$field => $loginData['email'], 'password' => $loginData['password']])) {
            // Authentication passed
            return redirect()->intended('/dashboard'); // Redirect to the intended URL
        }else{
            dd($field);
        }

        // Authentication failed
        return back()->withErrors(['login' => 'Invalid login or password']);
    }*/
}
