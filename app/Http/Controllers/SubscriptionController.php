<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;


class SubscriptionController extends Controller
{

    public function index()
    {

        if (auth()->user()->hasRole('super')) {

            $subscriptions = Subscription::all();

        } else {

            if(auth()->user()->hasRole('vendor') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('public_vendor') ){

            $userId = auth()->user()->id;
            $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

            $subscriptions = Subscription::whereIn('created_by', $usersOfCustomers)
                ->orWhere('created_by', $userId)
                ->get();
            }

            else{


                $userId = auth()->user()->user_id;
                $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

                $subscriptions = Subscription::whereIn('created_by', $usersOfCustomers)
                    ->orWhere('created_by', $userId)
                    ->get();

            }
        }


        if (request()->ajax()) {



            return datatables()->of($subscriptions)

                ->editColumn('plan_id', function ($row) {
                    return $row->plan_id ? $row->plan?->name : " ";
                })


                ->editColumn('user_id', function ($row) {
                    return $row->user->username;
                })
                ->addColumn('action', 'subscriptions.action')


                ->rawColumns(['plan_id', 'action'])

                ->addIndexColumn()
                ->make(true);
        }
        return view('subscriptions.list',compact('subscriptions'));
    }

    public function create()
    {

        // $roleNames = ['super', 'admin', 'vendor'];
        // $users = User::role($roleNames)->get();

        $roleNames = ['admin', 'public_vendor'];

        if (auth()->user()->hasRole('super')) {

            // $users = User::role('admin')->get();
            // $plans = Plan::all();

            // $groups = CustomerGroup::all();

            $userId = auth()->user()->id;
            $usersOfCustomers = User::role($roleNames)
                ->where('user_id', $userId)
                ->pluck('id');

            $users = User::whereIn('id', $usersOfCustomers)

                ->get();

            $ids = User::where('user_id', $userId)->pluck('id');


            $plans = Plan::where('user_id', $userId)
                ->orWhereIn('user_id', $ids)
                ->get();


            $groups = CustomerGroup::where('created_by', $userId)
                ->orWhereIn('created_by', $ids)
                ->get();



        } else {

            $userId = auth()->user()->id;
            $usersOfCustomers = User::role('vendor')
                ->where('user_id', $userId)
                ->pluck('id');

            $users = User::whereIn('id', $usersOfCustomers)

                ->get();

            $ids = User::where('user_id', $userId)->pluck('id');


            $plans = Plan::where('user_id', $userId)
                ->orWhereIn('user_id', $ids)
                ->get();


            $groups = CustomerGroup::where('created_by', $userId)
                ->orWhereIn('created_by', $ids)
                ->get();
        }

        return view('subscriptions.create', compact('users', 'plans', 'groups'));

    }

    public function store(Request $request)
    {

        if ($request->group_id) {
            $group = CustomerGroup::find($request->group_id);
            $group->plan_id = $request->plan_id;
            $group->save();

            foreach ($group->customers as $customer) {

                $sub = new Subscription();
                $sub->user_id = $customer->id;
                $sub->plan_id = $request->plan_id;
                $sub->created_by = auth()->user()->id;
                $sub->save();

                $plan = Plan::find($sub->plan_id);

                $sub->start_date = Carbon::today();
                $sub->end_date = $sub->start_date->copy()->addDays($plan->period);
                $sub->save();


                $user = User::find($customer->id);
                if ($sub->status == 'active') {
                    foreach ($plan->permissions as $p) {
                        $user->givePermissionTo($p);
                    }
                }
            }

            return redirect()->route('subscriptions.index')
                ->with('success', 'Subscription has been added successfully');
        }

        $sub = Subscription::create($request->all());


        $plan = Plan::find($sub->plan_id);

        $sub->created_by = auth()->user()->id;
        $sub->start_date = Carbon::today();
        $sub->end_date = $sub->start_date->copy()->addDays($plan->period);
        $sub->save();
        $user = User::find($request->user_id);
        if ($sub->status == 'active') {

            foreach ($plan->permissions as $p) {
                $user->givePermissionTo($p);
            }
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription has been added successfully');



    }

    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);

        $roleNames = ['admin', 'vendor'];

        if (auth()->user()->hasRole('super')) {

            // $users = User::role('admin')->get();
            // $plans = Plan::all();

            // $groups = CustomerGroup::all();

            $userId = auth()->user()->id;
            $usersOfCustomers = User::role('admin')
                ->where('user_id', $userId)
                ->pluck('id');

            $users = User::whereIn('id', $usersOfCustomers)

                ->get();

            $ids = User::where('user_id', $userId)->pluck('id');


            $plans = Plan::where('user_id', $userId)
                ->orWhereIn('user_id', $ids)
                ->get();


            $groups = CustomerGroup::where('created_by', $userId)
                ->orWhereIn('created_by', $ids)
                ->get();



        } else {

            $userId = auth()->user()->id;
            $usersOfCustomers = User::role('vendor')
                ->where('user_id', $userId)
                ->pluck('id');

            $users = User::whereIn('id', $usersOfCustomers)

                ->get();

            $ids = User::where('user_id', $userId)->pluck('id');


            $plans = Plan::where('user_id', $userId)
                ->orWhereIn('user_id', $ids)
                ->get();


            $groups = CustomerGroup::where('created_by', $userId)
                ->orWhereIn('created_by', $ids)
                ->get();
        }

        return view('subscriptions.show', compact('subscription', 'users', 'plans'));

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
        $subscription = Subscription::findOrFail($id);

        $subscription->update($request->except('user_id'));

        $plan = Plan::find($subscription->plan_id);
        $user = User::find($subscription->user_id);

        if ($subscription->status == 'active') {
            foreach ($plan->permissions as $p) {
                $user->givePermissionTo($p);
            }
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription has been updated successfully');
    }

    public function destroy($id)
    {
        if (Subscription::find($id)->delete()) {
            return response()->json(['msg' => 'Subscription deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }

}
