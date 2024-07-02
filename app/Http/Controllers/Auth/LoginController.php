<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
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



    public function login(AuthRequest $request)
    {

        $field = filter_var($request['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        // dd($field );
        if (Auth::attempt([$field => $request['email'], 'password' => $request['password']])) {
            // Authentication passed
            return redirect()->intended('/dashboard')->with(['status' => true, 'message' => 'welcome ' . $request['email']]); // Redirect to the intended URL
        } else {
            return redirect()->back()->with([
                'status' => false,
                'message' => 'These credentials do not match our records.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        //1. invalidate the session
        Auth::logout();
        $request->session()->invalidate();
        //2. regenerate token
        $request->session()->regenerateToken();
        //3. add this token to the session
        //4. redirect to the login view with successfuly message
        return redirect()->route('login')->with(['status' => true, 'message' => 'logout successfully']);
    }
}
