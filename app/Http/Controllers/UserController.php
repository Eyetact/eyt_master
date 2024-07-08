<?php

namespace App\Http\Controllers;

use App\Models\Admin\Software;
use App\Models\CustomerGroup;
use App\Models\Order;
use App\Models\UCGroup;
use App\Models\UserGroup;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Role;
use App\Repositories\FlashRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{

    private $flashRepository;

    public function __construct()
    {
        $this->flashRepository = new FlashRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function vendors()
    {
        if (request()->ajax()) {

            if (auth()->user()->hasRole('super')) {

                $users = User::role('vendor')->get();


            } else {

                $userId = auth()->user()->id;
                $users = User::role('vendor')
                    ->where('user_id', $userId)
                    ->get();



                // $users = User::whereIn('id', $usersOfCustomers)->get();


            }



            return datatables()->of($users)
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? '<img src="' . $row->ProfileUrl . '" alt="user-img" class="avatar-xl rounded-circle mb-1">' : "<span>No Image</span>";
                })
                ->addColumn('admin', function ($row) {
                    return $row->admin->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li class="dropdown-item">
                        <a  href="' . route('profile.index', $row->id) . '">View or Edit</a>
                        </li>

                        <li class="dropdown-item">
                        <a  href="#" data-id="' . $row->id . '" class="user-delete">Delete</a>
                        </li>
                    </ul>
                </div>';

                    return $btn;
                })
                ->rawColumns(['avatar', 'action'])

                ->addIndexColumn()
                ->make(true);
        }
        return view('users.vendors');
    }
    public function users()
    {
        if (auth()->user()->hasRole('super')) {

            $users = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['super', 'vendor', 'admin']);
            })->get();


        } else {

            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('vendor')) {

                $userId = auth()->user()->id;
                $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

                $users = User::whereIn('user_id', $usersOfCustomers)
                    ->orWhere('user_id', $userId)
                    ->whereDoesntHave('roles', function ($query) {
                        $query->whereIn('name', ['super', 'vendor', 'admin']);
                    })
                    ->get();
            } else {

                $userId = auth()->user()->user_id;
                $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

                $users = User::whereIn('user_id', $usersOfCustomers)
                    ->orWhere('user_id', $userId)
                    ->whereDoesntHave('roles', function ($query) {
                        $query->whereIn('name', ['super', 'vendor', 'admin']);
                    })
                    ->get();
            }
        }

        if (request()->ajax()) {
            // $users = User::role('user')->get();




            return datatables()->of($users)
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? '<img src="' . $row->ProfileUrl . '" alt="user-img" class="avatar-xl rounded-circle mb-1">' : "<span>No Image</span>";
                })
                ->addColumn('admin', function ($row) {
                    return $row?->admin?->name;
                })
                ->addColumn('action', 'users.action')
                ->rawColumns(['avatar', 'action'])

                ->addIndexColumn()
                ->make(true);

        }
        return view('users.users', compact('users'));
    }

    public function admins()
    {
        if (request()->ajax()) {

            if (auth()->user()->hasRole('super')) {

                $users = User::role('admin')->get();



            } else {

                $userId = auth()->user()->id;
                $usersOfCustomers = User::role('admin')
                    ->where('user_id', $userId)
                    ->pluck('id');

                $users = User::whereIn('id', $usersOfCustomers)

                    ->get();
            }


            return datatables()->of($users)
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? '<img src="' . $row->ProfileUrl . '" alt="user-img" class="avatar-xl rounded-circle mb-1">' : "<span>No Image</span>";
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li class="dropdown-item">
                        <a  href="' . route('profile.index', $row->id) . '">View or Edit</a>
                        </li>

                        <li class="dropdown-item">
                        <a  href="#" data-id="' . $row->id . '" class="user-delete">Delete</a>
                        </li>
                    </ul>
                </div>';

                    return $btn;
                })
                ->rawColumns(['avatar', 'action'])

                ->addIndexColumn()
                ->make(true);
        }
        return view('users.admins');
    }


    public function myAdmins($user_id)
    {
        if (request()->ajax()) {
            $users = User::where('user_id', $user_id)->get();

            return datatables()->of($users)
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? '<img src="' . $row->ProfileUrl . '" alt="user-img" class="avatar-xl rounded-circle mb-1">' : "<span>No Image</span>";
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <li class="dropdown-item">
                        <a  href="' . route('profile.index', $row->id) . '">View or Edit</a>
                        </li>

                        <li class="dropdown-item">
                        <a  href="#" data-id="' . $row->id . '" class="user-delete">Delete</a>
                        </li>
                    </ul>
                </div>';

                    return $btn;
                })
                ->rawColumns(['avatar', 'action'])

                ->addIndexColumn()
                ->make(true);
        }

    }


    public function myOrders()
    {
        if (request()->ajax()) {

            $software_id= Software::where('customer_id',auth()->user()->id)->first();
            $orders=Order::where('software_id', $software_id->id)->get();

            return datatables()->of( $orders)


            ->addColumn('mixture', function ($row) {
                return $row->mixture_id ? $row->mixture->mix_name : '';})

                ->rawColumns(['mixture'])

                ->addIndexColumn()
                ->make(true);
        }

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::all();


        if (auth()->user()->hasRole('super')) {

            $roles = Role::where('name', '!=', 'admin')
                ->where('name', '!=', 'vendor')
                ->where('name', '!=', 'super')
                ->get();

        } else {
            $userId = auth()->user()->id;
            $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

            $roles = Role::whereIn('user_id', $usersOfCustomers)
                ->where('name', '!=', 'admin')
                ->where('name', '!=', 'vendor')
                ->where('name', '!=', 'super')

                ->orWhere('user_id', $userId)
                ->where('name', '!=', 'admin')
                ->where('name', '!=', 'vendor')
                ->where('name', '!=', 'super')
                ->get();
        }
        return view('users.create-user', compact('groups', 'roles'));
    }

    public function createAdmin()
    {
        $groups = CustomerGroup::all();

        return view('users.create-admin', compact('groups'));
    }
    public function createvendor()
    {
        $groups = CustomerGroup::all();

        return view('users.create-vendor', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {

        $u = User::where('email',$request->email)->orWhere('username',$request->username)->first();

        if ($u) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            switch ($request->role) {
                case 'admin':
                    return redirect()->route('users.admins');
                    break;

                case 'vendor':
                    return redirect()->route('users.vendors');
                    break;
                case 'user':
                    return redirect()->route('users.users');
                    break;


            }
            return redirect()->route('module_manager.index');
        }
        $c_group = 1;
        switch ($request->role) {
            case 'admin':
                $c_group = 2;
                break;

            case 'vendor':
                $c_group = 3;
                break;



        }
        // dd( $request->all() );
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'website' => $request->website,
            'address' => $request->address,
            'phone' => $request->phone,
            'avatar' => $request->avatar,
            'access_table' => $request->access_table ? $request->access_table : "Individual",
            'password' => bcrypt($request->password),
            'user_id' => Auth::user()->id,
            'group_id' => $c_group,
            'ugroup_id' => 1,
        ]);
        if ($request->group_id):
            foreach ($request->group_id as $id) {
                $c = new UCGroup();
                $c->group_id = $id;
                $c->user_id = $user->id;
                $c->save();
                if (($request->role != "admin") || ($request->role != "vendor")) {


                    $role_db = DB::table('model_has_roles')
                        ->where('model_type', "App\Models\UserGroup")
                        ->where('model_id', $id)
                        ->first();
                    if ($role_db) {
                        $role_id = $role_db->role_id;
                        $role = Role::find($role_id)->name;



                        $user->assignRole($role);
                        foreach (Role::find($role_id)->permissions as $p) {
                            $user->givePermissionTo($p);
                        }
                    }

                }
            }
            // dd($request->group_id);
        endif;

        $plan = Plan::where('name', 'Free Plan')->first();

        if (!$plan) {
            $plan = new Plan();
            $plan->name = 'Free Plan';
            $plan->price = 0;
            $plan->period = 14;
            $plan->save();
        }

        if (($request->role == "admin") || ($request->role == "vendor")) {
            $sub = new Subscription();
            $sub->user_id = $user->id;
            $sub->plan_id = $plan->id;
            $sub->save();

            $sub->start_date = Carbon::today();
            $sub->end_date = $sub->start_date->copy()->addDays($plan->period);
            $sub->save();

            $user->assignRole($request->role);
        } else {
            // $user->assignRole('user'); // TODO::
            $user->assignRole($request->role);
            // foreach (Role::find($role_id)->permissions as $p) {
            //     $user->givePermissionTo($p);
            // }
        }




        switch ($request->role) {
            case 'admin':
                return redirect()->route('users.admins')
                    ->with('success', 'Subscription has been added successfully');
                break;

            case 'vendor':
                return redirect()->route('users.vendors')
                    ->with('success', 'Subscription has been added successfully');
                break;
            case 'user':
                return redirect()->route('users.users')
                    ->with('success', 'Subscription has been added successfully');
                break;


        }

        return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 'work';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::find($id)->delete()) {
            return response()->json(['msg' => 'User deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }
}
