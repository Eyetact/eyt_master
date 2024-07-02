<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use App\Models\UserGroup;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Role;


class ProfileController extends Controller
{
    public function index($id = null)
    {
        $user_id = $id == null ? Auth::id() : $id;
        // dd( $user_id );
        $user = User::find($user_id);
        $subscriptions = $user->subscriptions;
        $last_used = json_encode($user->last_used);
        $groups = CustomerGroup::all();
        $ugroups = UserGroup::all();


        if (request()->ajax()) {
            // $subscriptions = Subscription::where('user_id', $user_id)->get();

            return datatables()->of($subscriptions)

                ->editColumn('plan_id', function ($row) {
                    return $row->plan_id ? $row->plan?->name : " ";
                })


                ->editColumn('user_id', function ($row) {
                    return $row->user->username;
                })

                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li class="dropdown-item">
                        <a  href="' . route('subscriptions.view', $row->id) . '">View or Edit</a>
                        </li>

                        <li class="dropdown-item">
                        <a  href="#" data-id="' . $row->id . '" class="subscription-delete">Delete</a>
                        </li>
                    </ul>
                </div>';

                    return $btn;
                })
                ->rawColumns(['plan_id', 'action'])

                ->addIndexColumn()
                ->make(true);
        }

        return view('profile.index', compact('user', 'groups', 'ugroups', 'last_used'));
    }

    public function update(Request $request, $id = null)
    {
        // dd($request->all());
        // dd($request->last_used);
        $user_id = $id == null ? Auth::id() : $id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email,' . $user_id,
            'phone' => 'required',
            'address' => 'required',
            'website' => 'required',
        ]);
        if (count($validator->errors()) > 0) {
            return redirect()->route('profile.index')->withErrors($validator->errors());
        }

        $user = User::where('id', $user_id)->first();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->website = $request->website;
        $user->access_table = $request->access_table;


        $user->group_id = $request->group_id;
        if (!$user->last_used) {

            // $user->last_used =json_encode($request->last_used,true);
            $user->last_used = $request->last_used;
        } else {
            $ar = json_decode($user->last_used, true);
            // $aa =array();
            // // dd($ar);
            // foreach( $ar as $a ){
            // array_push( $aa, (array)$a );

            // }
            array_push($ar, (array) $request->last_used[0]);
            $user->last_used = $ar;

        }

        // if( $user->group_id != $request->group_id){

        if (isset($request->ugroup_id)) {
            if (
                DB::table('model_has_roles')
                    ->where('model_type', "App\Models\UserGroup")
                    ->where('model_id', $request->ugroup_id)
                    ->first()
            ) {

                $role_id = DB::table('model_has_roles')
                    ->where('model_type', "App\Models\UserGroup")
                    ->where('model_id', $request->ugroup_id)
                    ->first()
                    ->role_id;

                $role = Role::find($role_id)->name;
                $user->assignRole($role);
                foreach (Role::find($role_id)->permissions as $p) {
                    $user->givePermissionTo($p);
                }
            }

        }





        // }

        $user->group_id = $request->group_id ?? 1;
        $user->ugroup_id = $request->ugroup_id ?? 1;
        $user->save();

        Session::flash('success', 'Profile updated successfully.');
        return redirect()->back();
    }

    public function changePassword(Request $request, $id = null)
    {
        $user_id = $id == null ? Auth::id() : $id;
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);
        if (count($validator->errors()) > 0) {
            return redirect()->route('profile.index')->withErrors($validator->errors());
        }

        $user = User::where('id', $user_id)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'Password has been changed successfully.');
        return redirect()->route('profile.index');
    }

    public function uploadProfileImage(Request $request, $id = null)
    {
        if ($request->hasFile('image_upload')) {
            $filename = $request->image_upload->getClientOriginalName();
            $destinationPath = 'uploads/users';
            $request->image_upload->move($destinationPath, $filename);
            $request->merge(['avatar' => $filename]);

            $user_id = $id == null ? Auth::id() : $id;
            $user = User::where('id', $user_id)->first();
            $user->avatar = $request->avatar;
            $user->save();

            Session::flash('success', 'Profile image updated successfully.');
            return redirect()->route('profile.index');
        }
    }
}
