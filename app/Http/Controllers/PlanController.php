<?php

namespace App\Http\Controllers;

use App\Generators\GeneratorUtils;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use App\Models\Limit;
use App\Http\Requests\PlanRequest;
use Spatie\Permission\Models\Permission;



class PlanController extends Controller
{


    public function index()
    {

        if (auth()->user()->hasRole('super')) {

            $plans = Plan::all();


        } else {
            if (auth()->user()->hasRole('vendor') || auth()->user()->hasRole('admin')) {

                $userId = auth()->user()->id;


                $ids = User::where('user_id', $userId)->pluck('id');


                $plans = Plan::where('user_id', $userId)
                    ->orWhereIn('user_id', $ids)
                    ->get();

            } else {

                $userId = auth()->user()->user_id;


                $ids = User::where('user_id', $userId)->pluck('id');


                $plans = Plan::where('user_id', $userId)
                    ->orWhereIn('user_id', $ids)
                    ->get();

            }
        }

        if (request()->ajax()) {


            return datatables()->of($plans)
                ->editColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" alt="user-img" class="avatar-xl rounded-circle mb-1">' : "<span>No Image</span>";
                })
                ->addColumn('action', 'plans.action')
                ->rawColumns(['image', 'action'])

                ->addIndexColumn()
                ->make(true);
        }
        return view('plans.list', compact('plans'));
    }


    public function create()
    {

        $permissions = Permission::all();
        $user_permissions = Permission::where('type', 'user')->get();
        $customer_permissions = Permission::where('type', 'customer')->get();
        $allPermission = Permission::all();
        $groupPermission = $allPermission->groupBy('module');

        // $availableModel= (auth()->user()->model_limit) - (auth()->user()->current_model_limit);
        // $availableData= auth()->user()->data_limit;


        if (auth()->user()->hasRole('super')) {

            $modulesSuper=Module::where('user_id',auth()->user()->id)->pluck('id');
            $permissions = Permission::whereIn('module',$modulesSuper)->get();
            $user_permissions = Permission::where('type', 'user')->get();
            $customer_permissions = Permission::where('type', 'customer')->get();
            $allPermission = Permission::whereIn('module',$modulesSuper)->get();
            $groupPermission = $allPermission->groupBy('module');


            $availableModel = 1000000;
            $availableData = 1000000;

        }

        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('vendor')) {

            $availableModel = auth()->user()->model_limit - auth()->user()->current_model_limit;

            $modules = Module::where('user_id', auth()->user()->id)->get();

            $total = 0;

            // foreach ($modules as $module) {
            //     $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($module->code);

            //     $users = User::where('user_id', auth()->user()->id)->pluck('id');
            //     $total += $modelName::whereIn('user_id', $users)->orWhere('user_id', auth()->user()->id)->count();




            //     // foreach ($users as $user) {
            //     //     $totalCustomer += $modelName::whereIn('user_id', [$user->id])->count();
            //     // }

            //     // $totalAdmin += $modelName::whereIn('user_id', [auth()->user()->id])->count();
            // }

            // $total = $totalCustomer + $totalAdmin;
            $availableData = auth()->user()->data_limit - auth()->user()->count;
        }





        return view('plans.create', compact('permissions', 'user_permissions', 'customer_permissions', 'allPermission', 'groupPermission', 'availableModel', 'availableData'));
    }

    public function store(Request $request)
    {

        // dd($request->permissions);

        $plan = Plan::create($request->except('permissions', 'checkAll', 'limit'));
        $plan->user_id = auth()->user()->id;
        $plan->save();

        $limits = $request->limit;
        if (isset ($request->checkAll)) {
            foreach ($request->checkAll as $key => $check) {
                if (isset ($limits[$key])) {

                    $limit = new Limit();
                    $limit->plan_id = $plan->id;
                    $limit->module_id = $key;

                    $value = $limits[$key];
                    // echo $value . '\n';
                    if ($value == -1) {
                        $limit->data_limit = 100000;
                    } else {
                        $limit->data_limit = $value;
                    }
                    $limit->save();
                }
            }
        }
        // return;





        if (isset ($request->permissions)) {
            if ($request->permissions) {
                foreach ($request->permissions as $p) {

                    $per = Permission::find($p);

                    $plan->permissions()->save($per);
                }
            }
        }


        return redirect()->route('plans.index')
            ->with('success', 'Plan has been added successfully');
        ;



    }


    public function show($id)
    {
        $permissions = Permission::all();
        $user_permissions = Permission::where('type', 'user')->get();
        $customer_permissions = Permission::where('type', 'customer')->get();
        $plan = Plan::findOrFail($id);
        $allPermission = Permission::all();
        $groupPermission = $allPermission->groupBy('module');

        if (auth()->user()->hasRole('super')) {
            $modulesSuper=Module::where('user_id',auth()->user()->id)->pluck('id');
            $permissions = Permission::whereIn('module',$modulesSuper)->get();
            $user_permissions = Permission::where('type', 'user')->get();
            $customer_permissions = Permission::where('type', 'customer')->get();
            $allPermission = Permission::whereIn('module',$modulesSuper)->get();
            $groupPermission = $allPermission->groupBy('module');


            $availableModel = 1000000;
            $availableData = 1000000;

        }

        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('vendor')) {

            $availableModel = auth()->user()->model_limit - auth()->user()->current_model_limit;

            $modules = Module::where('user_id', auth()->user()->id)->get();

            $total = 0;

            // foreach ($modules as $module) {
            //     $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($module->code);

            //     $users = User::where('user_id', auth()->user()->id)->pluck('id');
            //     $total += $modelName::whereIn('user_id', $users)->orWhere('user_id', auth()->user()->id)->count();

            //     // foreach ($users as $user) {
            //     //     $totalCustomer += $modelName::whereIn('user_id', [$user->id])->count();
            //     // }

            //     // $totalAdmin += $modelName::whereIn('user_id', [auth()->user()->id])->count();
            // }

            // $total = $totalCustomer + $totalAdmin;
            $availableData = auth()->user()->data_limit - auth()->user()->count;
        }
        return view('plans.show', compact('plan', 'permissions', 'user_permissions', 'customer_permissions', 'allPermission', 'groupPermission', 'availableModel', 'availableData'));
    }


    // public function edit(string $id)
    // {
    //     $plan = Plan::findOrFail($id);
    //     return view('plans.show',compact('plan'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plan = Plan::findOrFail($id);

        if ($request->has('image') && $plan->image && File::exists($plan->image)) {
            unlink($plan->image);
        }

        $plan->update($request->except('permissions', 'checkAll', 'limit'));

        $limits = $request->limit;
        if (isset ($request->checkAll)) {

            $limts = Limit::where('plan_id', $plan->id)->where('subscription_id',null)->delete();
            foreach ($request->checkAll as $key => $check) {
                if (isset ($limits[$key])) {

                    $limit = new Limit();
                    $limit->plan_id = $plan->id;
                    $limit->module_id = $key;

                    $value = $limits[$key];
                    // echo $value . '\n';
                    if ($value == -1) {
                        $limit->data_limit = 100000;
                    } else {
                        $limit->data_limit = $value;
                    }
                    $limit->save();
                }
            }
        }





        if (isset ($request->permissions)) {
            $plan->permissions()->detach();
            if ($request->permissions) {
                foreach ($request->permissions as $p) {

                    $per = Permission::find($p);

                    $plan->permissions()->save($per);
                }


                $subs = $plan->subscriptions;
                foreach ($subs as $sub) {
                    $user = $sub->user;
                    foreach ($plan->permissions as $p) {
                        $user->givePermissionTo($p);
                    }

                }



            }
        }
        return redirect()->route('plans.index')
            ->with('success', 'Plan has been updated successfully');
    }

    public function destroy($id)
    {
        if (Plan::find($id)->delete()) {
            return response()->json(['msg' => 'Plan deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }
}
