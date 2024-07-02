<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ConfigResource;
use App\Mail\SendOTP;
use App\Models\Admin\Software;
use App\Models\CustomerGroup;
use App\Models\Plan;
use App\Models\Subscription;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Admin\MList;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\NullableType;
use Carbon\Carbon;




class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @var UserRepository
     */
    protected $userRepositry;

    public function __construct()
    {
        $this->userRepositry = new UserRepository(app(User::class));
    }

    public function login(Request $request)
    {

        if (
            !Auth::attempt(
                $request->only([
                    'email',
                    'username',
                    'password',
                ])
            )
        ) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => __('Invalid credentials!'),
            ], 500);
        }

        $check = User::where('email', $request->email)
                       ->orWhere('username', $request->username)
                       ->first();

        if (!($check->hasRole('vendor'))) {

            return $this->returnError('Invalid credentials!');
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response([
            'status' => true,
            'code' => 200,
            'msg' => __('Log in success'),
            'data' => [
                'token' => $accessToken,
                'user' => UserResource::make(Auth::user()),
            ]
        ]);

    }


    public function store(UserRequest $request)
    {
        try {

            DB::beginTransaction();


            $serial = Software::where('serial_number', $request->serial_number)
                ->first();


            if (!$serial) {

                return $this->returnError('The serial number not correct!');
            }

            if ($serial->customer_id) {

                $vendor = User::find($serial->customer_id);
                $user = User::where('email', $request->email)
                    ->first();

                if ($user) {

                    if ($vendor->id == $user->id) {
                        if (Hash::check($request->password, $user->password)) {

                            // dd($user);


                            Auth::login($vendor);

                            $accessToken = Auth::user()->createToken('authToken')->accessToken;

                            return response([
                                'status' => true,
                                'code' => 200,
                                'msg' => __('Log in success'),
                                'data' => [
                                    'token' => $accessToken,
                                    'user' => UserResource::make(Auth::user()),
                                ]
                            ]);
                        }
                    }
                }
                return $this->returnError('The data not valid!');


            }

            if ($serial->customer_id == NULL) {

                if ($serial->customer_group_id != NULL) {


                    $user = User::where('email', $request->email)
                        ->first();

                    if ($user) {


                        if (Hash::check($request->password, $user->password)) {

                            // dd($user);

                            $serial->customer_id = $user->id;
                            $serial->save();

                            Auth::login($user);

                            $accessToken = Auth::user()->createToken('authToken')->accessToken;

                            return response([
                                'status' => true,
                                'code' => 200,
                                'msg' => __('Log in success'),
                                'data' => [
                                    'token' => $accessToken,
                                    'user' => UserResource::make(Auth::user()),
                                ]
                            ]);

                        }
                    return $this->returnError('The data not valid!');

                    }






                if (isset($request->email)) {
                    $check = User::where('email', $request->email)
                        ->first();

                    if ($check) {

                        return $this->returnError('The email address is already used!');
                    }
                }

                $user = $this->userRepositry->save($request);
                $user->assignRole('vendor');

                $plan_id=CustomerGroup::find($serial->customer_group_id)->plan_id;
                $plan = Plan::find($plan_id);

                $sub = new Subscription();
                $sub->user_id = $user->id;
                $sub->plan_id = $plan_id;
                $sub->start_date = Carbon::today();
                $sub->end_date = $sub->start_date->copy()->addDays($plan->period);
                $sub->save();


                DB::commit();
                Auth::login($user);

                $accessToken = Auth::user()->createToken('authToken')->accessToken;

                if ($user) {

                    $serial->customer_id = $user->id;
                    $serial->save();

                    return response([
                        'status' => true,
                        'code' => 200,
                        'msg' => __('User created succesfully'),
                        'data' => [
                            'token' => $accessToken,
                            'user' => UserResource::make(Auth::user()),
                        ]
                    ]);
                }

            }

            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return $this->returnError('Sorry! Failed in creating user');
        }
    }


    public function sendOtp(Request $request)
    {
        $user = User::where('username', $request->user)
                     ->orWhere('email', $request->user)
                     ->first();


        if ($user) {
            $otp = rand(100000, 999999);
            Mail::to($user->email)->send(new SendOTP($otp));

            $user->otp = $otp;
            $user->save();



            return $this->returnSuccessMessage('Code was sent');
        }

        return $this->returnError('Code not sent. User not found');
    }


    public function checkOTP(Request $request)
    {
        $user = User::where('otp',$request->otp)->first();
        if ($user) {

        if ((string)$user->otp == (string)$request->otp) {




            return $this->returnSuccessMessage('Code Correct');


        }

        return $this->returnError('Code not correct');
    }

    return $this->returnError('User not found');


    }

    public function changePassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if ($user) {


                $user->update([
                    'password' => Hash::make($request->password),
                ]);

                $user->otp = "";
                $user->save();

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('User not found!');
    }

    public function updateById(Request $request)
    {
        try {

            $user = User::find($request->user_id);
            if ($user) {

                if (isset($request->email)) {
                    $check = User::where('email', $request->email)
                        ->first();

                    if ($check) {

                        return $this->returnError('The email address is already used!');
                    }
                }

                if (isset($request->username)) {
                    $check = User::where('username', $request->username)
                        ->first();

                    if ($check) {

                        return $this->returnError('The user name is already used!');
                    }
                }





                $this->userRepositry->edit($request, $user);

                if ($request->password) {

                    $user->update([
                            'password' => Hash::make($request->password),
                        ]);

                }








                return $this->returnData('user', new UserResource($user), 'User updated successfully');


            }




            // unset($user->image);

            return $this->returnError('Sorry! Failed to find user');
        } catch (\Exception $e) {

            // return $e;

            return $this->returnError('Sorry! Failed in updating user');
        }
    }


    public function config(){


        $user=auth()->user();
        return $this->returnData('user', new ConfigResource($user), 'User updated successfully');


    }


}
