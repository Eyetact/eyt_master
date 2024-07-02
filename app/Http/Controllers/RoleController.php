<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionCount;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleSchedulerSetting;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Validation\Rule;
use Flash;
use Illuminate\Support\Facades\Session;
use App\Repositories\FlashRepository;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class RoleController extends Controller
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
    public function index()
    {

        if(auth()->user()->hasRole('super'))
        {

        $roles = Role::all();

        }


        else{
            if(auth()->user()->hasRole('vendor') || auth()->user()->hasRole('admin') ){
                $userId = auth()->user()->id;
                $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

                $roles = Role::whereIn('user_id', $usersOfCustomers)
                    ->orWhere('user_id', $userId)
                    ->get();
            }else{
                $userId = auth()->user()->user_id;
        $usersOfCustomers = User::where('user_id', $userId)->pluck('id');

        $roles = Role::whereIn('user_id', $usersOfCustomers)
            ->orWhere('user_id', $userId)
            ->get();
            }

        }
        $this->flashRepository = new FlashRepository;
        if (request()->ajax()) {



            return datatables()->of($roles)
                ->addColumn('action', 'company-action')
                // ->addColumn('action', function ($row) {
                //     // $btn = '<a class="btn-default  edit-role edit_form" data-path="'.route('role.edit', ['role' => $row->id]).'"> <button><i class="fa fa-edit"></i></button> </a>';
                //     $btn = '';
                //     if (Auth::user()->can('edit.role')) {
                //         $btn = $btn . '<a class="edit-role edit_form btn btn-icon btn-success mr-1 white" data-path="' . route('role.edit', ['role' => $row->id]) . '" data-name="' . $row->name . '" data-id=' . $row->id . ' title="Edit"> <i class="fa fa-edit"></i> </a>';
                //     }
                //     // $btn = $btn.'<button type="submit" class=" btn-danger delete-role" data-id="'.$row->id.'"><i class="fa fa-trash-o"></i>';
                //     if (Auth::user()->can('delete.role')) {
                //         $btn = $btn . '<a class="btn btn-icon btn-danger mr-1 white delete-role" data-id="' . $row->id . '" title="Delete"> <i class="fa fa-trash-o"></i> </a>';
                //     }
                //     return $btn;
                // })
                ->addColumn('action', 'role.action')
                ->addIndexColumn()
                ->make(true);
        }
        $allPermission = Permission::all();
        $groupPermission = $allPermission->groupBy('module');
        return view('role.index', ['role' => new Role(), 'allPermission' => $allPermission, 'groupPermission' => $groupPermission,'roles'=>$roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allPermission = Permission::where('attribute',NULL)->get();
        $groupPermission = $allPermission->groupBy('module');
        return view('role.create', ['role' => new Role(), 'allPermission' => $allPermission, 'groupPermission' => $groupPermission]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        // dd($inputData);

        $permission_data = $inputData['permission_data']; // all checked
        $schedule_no_edit = $inputData['schedule_no_edit']; // number of ( days or etc )
        $schedule_time_edit = $inputData['schedule_time_edit']; // type of number
        $schedule_no_delete = $inputData['schedule_no_delete']; // like edit
        $schedule_time_delete = $inputData['schedule_time_delete']; // like edit
        $permission_module = $inputData['permission_module']; // model

        $request->validate([
            'name' => ['required',
                Rule::unique('roles')->where(function ($query) use ($request) {
                    return $query->where('guard_name', $request['guard_name']);
                })
            ],
            'guard_name' => 'required|max:255'
        ]);

        $role = Role::create(['name' => $inputData['name'], 'guard_name' => $inputData['guard_name']]);
        $role->user_id = auth()->user()->id;
        $role->save();

        //error msg
        if (empty($role)) {
            $this->flashRepository->setFlashSession('alert-danger', 'Role not created.');
            return redirect()->route('role.index');
        }

        $role->syncPermissions($permission_data);

        $type = $request->schedule_time_delete;

        foreach ($request->schedule_no_delete as $key => $value) {

            PermissionCount::updateOrCreate(
                [
                    'role_id'=>$role->id,
                    'permission_id'=>$key,

                ],
                [
                    'count'=>$value,
                    'type' => $type[$key]
                ]
            );
        }

        $type = $request->schedule_time_edit;

        foreach ($request->schedule_no_edit as $key => $value) {
            PermissionCount::updateOrCreate(
                [
                    'role_id'=>$role->id,
                    'permission_id'=>$key,

                ],
                [
                    'count'=>$value,
                    'type' => $type[$key]
                ]
            );
        }

        //assigne all permission to role
        // if ($request->has('permission_data') && $role) {
        //     $permissions = Permission::whereIn('id',$inputData['permission_data'])->get();
        //     // dd($permissions);
        //     foreach ($permissions as $p) {
        //         $role->givePermissionTo($p);
        //         $p->assignRole($role);
        //         // dd($p);
        //     }
        //     // $role->syncPermissions($permissions);
        // }

        // $insertData = array();
        // for ($i = 0; $i < count($permission_data); $i++) {
        //     $permission_id = $permission_data[$i];
        //     $module_id = $permission_module[$permission_id];

        //     if (isset($schedule_no_edit[$permission_data[$i]])) {
        //         //For edit
        //         $scheduler_no = $schedule_no_edit[$permission_data[$i]];
        //         $type = $schedule_time_edit[$permission_data[$i]];

        //         $date = date('Y-m-d', strtotime('+ 10 days')); //set integer based on the type selected

        //         $insertData[] = array(
        //             'user_id' => auth()->user()->id,
        //             'role_id' => $role->id,
        //             'permission_id' => $permission_id,
        //             'module_id' => $module_id,
        //             'scheduler_no' => $scheduler_no,
        //             'type' => $type,
        //             'status' => ($scheduler_no == 0 ? 0 : 1),
        //             'access_action_date_time' => $date,
        //             'model_access_action_permission' => 'edit',
        //         );
        //     }

        //     if (isset($schedule_no_delete[$permission_data[$i]])) {
        //         //For delete
        //         $scheduler_no = $schedule_no_delete[$permission_data[$i]];
        //         $type = $schedule_time_delete[$permission_data[$i]];

        //         $date = date('Y-m-d', strtotime('+ 10 days')); //set integer based on the type selected

        //         $insertData[] = array(
        //             'user_id' => auth()->user()->id,
        //             'role_id' => $role->id,
        //             'permission_id' => $permission_id,
        //             'module_id' => $module_id,
        //             'scheduler_no' => $scheduler_no,
        //             'type' => $type,
        //             'status' => ($scheduler_no == 0 ? 0 : 1),
        //             'access_action_date_time' => $date,
        //             'model_access_action_permission' => 'delete',
        //         );
        //     }
        // }

        // RoleSchedulerSetting::insert($insertData);

        $this->flashRepository->setFlashSession('alert-success', 'Role created successfully.');

        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (empty($role)) {
            $this->flashRepository->setFlashSession('alert-danger', 'Role not found.');
            return view('role.index');
        }

        $allPermission = Permission::where('attribute',NULL)->get();
        // $roleScheduler = RoleSchedulerSetting::all();, 'roleScheduler' => $roleScheduler
        $groupPermission = $allPermission->groupBy('module');
        return view('role.create', ['role' => $role, 'allPermission' => $allPermission, 'groupPermission' => $groupPermission]);
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

        $role = Role::find($id);
        if (empty($role)) {
            $this->flashRepository->setFlashSession('alert-danger', 'Role not found.');
            return redirect()->route('role.index');
        }
        $inputData = $request->all();

        $permission_data = $inputData['permission_data'];

        $request->validate([
            'name' => ['required',
                Rule::unique('roles')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('guard_name', $request['guard_name']);
                })],
            'guard_name' => 'required|max:255'
        ]);

        $role->update(['name' => $request->name, 'guard_name' => $request->guard_name]);

        $role->syncPermissions($permission_data);

        $type = $request->schedule_time_delete;

        foreach ($request->schedule_no_delete as $key => $value) {

            PermissionCount::updateOrCreate(
                [
                    'role_id'=>$id,
                    'permission_id'=>$key,

                ],
                [
                    'count'=>$value,
                    'type' => $type[$key]
                ]
            );
        }

        $type = $request->schedule_time_edit;

        foreach ($request->schedule_no_edit as $key => $value) {
            PermissionCount::updateOrCreate(
                [
                    'role_id'=>$id,
                    'permission_id'=>$key,

                ],
                [
                    'count'=>$value,
                    'type' => $type[$key]
                ]
            );
        }


        $this->flashRepository->setFlashSession('alert-success', 'Role updated successfully.');

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role)
    {
        $roleDelete = Role::find($role)->delete();
        if ($roleDelete)
            return response()->json(['msg' => 'Role deleted successfully!']);

        return response()->json(['msg' => 'Something went wrong, Please try again'], 500);
    }
}
