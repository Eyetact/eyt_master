<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Repositories\FlashRepository;
use Illuminate\Validation\Rule;
use App\Models\Module;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class PermissionController extends Controller
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
        if(request()->ajax()) {
            if(auth()->user()->hasRole('super')){
                $perms = Permission::select('*');
            }else{
                $perms  = auth()->user()->permissions;
            }
            return datatables()->of($perms)
            ->addColumn('module', function($row){

                $module_name = Module::find($row->module);
                return ($module_name->name ?? $row->module);
            })



            ->addColumn('action', 'permission.action')
            ->addIndexColumn()
            ->make(true);
        }
        return view('permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('super')){
        $moduleList = Module::all();
        }
        else{

            $userId = auth()->user()->id;

            $ids = User::where('user_id', $userId)->pluck('id');
            $moduleList = Module::where('user_id', $userId)
            ->orWhereIn('user_id',$ids)
            ->get();
        }




        // dd( $moduleList );
        $permission = Permission::whereNull('id')->get();

        // dd($moduleList);
        // return view('permission.create', ['permission' =>new Permission() ]);
        return view('permission.create', ['permission' => $permission, 'moduleList' => $moduleList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [];

        foreach($input['name'] as $key => $val)
        {
            $rules['name.'.$key] = ['required', Rule::unique('permissions','name')->where(function ($query) use ($request){
                                            return $query->where('guard_name', $request['guard_name']);
                                        })
                                    ];
            $messages['name.'.$key.'.unique'] = 'The Permission Name has already been taken.';
        }

        $rules['module'] = 'required|max:255';
        $rules['guard_name'] = 'required|max:255';
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)
                        ->withInput();
        }

        $permissionData = array();
        $now = Carbon::now('utc')->toDateTimeString();
        foreach($request->name as $value){
            $permissionData[] = [
                'name' => $value,
                'guard_name' => $request->guard_name,
                'module' => $request->module,
                // 'type' => $request->type,
                // 'count' => $request->count,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        // dd($request->count);
        $permission = Permission::insert($permissionData);

        if(empty($permission)){
            $this->flashRepository->setFlashSession('alert-danger', 'Permission not created.');
            return redirect()->route('permission.index');
        }

        $this->flashRepository->setFlashSession('alert-success', 'Permission created successfully.');
        return redirect()->route('permission.index');
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
    public function edit(Permission $permission)
    {
        $moduleList = Module::all();
        if(empty($permission)){
            $this->flashRepository->setFlashSession('alert-danger', 'Permission not found.');
            return view('permission.index');
        }
        $permission = Permission::where('module',$permission->module)->get();

        return view('permission.edit', ['permission' => $permission, 'moduleList' => $moduleList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->all();

        $permissionData = array();
        $inputData = array();
        for ($i=0; $i < count($input['name']); $i++) {
            if(isset($input['id'][$i])){
                $permissionData[$i]['id'] = $input['id'][$i];
                $permissionData[$i]['name'] = $input['name'][$i];

                $inputData['name'][] = $input['name'][$i];
                $inputData['id'][] = $input['id'][$i];
            }else{
                $permissionData[$i]['id'] = null;
                $permissionData[$i]['name'] = $input['name'][$i];

                $inputData['name'][] = $input['name'][$i];
                $inputData['id'][] = null;
            }

            $permissionData[$i]['guard_name'] = 'web';
            $permissionData[$i]['module'] = $input['module'];
        }

        $inputData['guard_name'] = 'web';
        $inputData['module'] = $request->module;

        $rules = [];
        foreach($inputData['name'] as $key => $val){
            if($inputData['id'][$key]){
                $rules['name.'.$key] = ['required', Rule::unique('permissions','name')->ignore($inputData['id'][$key])->where(function ($query) use ($request){
                        return $query->where('guard_name', $request['guardName']);
                    })
                ];
            }else{
                $rules['name.'.$key] = ['required', Rule::unique('permissions','name')->where(function ($query) use ($request){
                        return $query->where('guard_name', $request['guardName']);
                    })
                ];
            }
            $messages['name.'.$key.'.unique'] = 'The Permission Name has already been taken.';
        }

        $rules['module'] = 'required|max:255';
        // $rules['guard_name'] = 'required|max:255';
        $validator = Validator::make($inputData,$rules,$messages);

        if ($validator->fails()) {
            // dd($validator->getMessageBag()->toArray());
            return response()->json( array( 'errors' => $validator->getMessageBag()->toArray() ), 400);
        }else{

            for ($i=0; $i <count($permissionData) ; $i++) {
                if(empty($permissionData[$i]['id'])){
                     Permission::insert($permissionData[$i]);
                }else{
                    $model = Permission::find($permissionData[$i]['id']);
                    $model->update($permissionData[$i]);
                }
            }

            $this->flashRepository->setFlashSession('alert-success', 'Permission updated successfully.');
            // return response()->json( array( 'msg' => 'Permission updated successfully.' ), 200);
            return redirect()->route('permission.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $permissionDelete = Permission::find($request->id)->delete();
        if($permissionDelete)
            return response()->json(['msg' => 'Permission deleted successfully!']);

        return response()->json(['msg' => 'Something went wrong, Please try again'],500);
    }

    public function deleteSinglePermission($id)
    {
        if (Permission::find($id)->delete()) {
            return response()->json(['msg' => 'Permission deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }


    public function moduleStore(Request $request){
        $request->validate([
            'name' => 'required|unique:modules,name',
        ]);
        $group = Module::create([
            'name' => $request->name,
        ]);

        if ($request->name == trim($request->name) && str_contains($request->name, ' ')) {
            $module_name = str_replace(' ', '', $request->name);
        } else {
            $module_name = strtolower($request->name);
        }

        $module_name = strtolower($module_name);

        $permission_array = array(
            'create.'.$module_name,
            'edit.'.$module_name,
            'delete.'.$module_name,
            'view.'.$module_name
        );

        $permissionData = array();
        $now = Carbon::now('utc')->toDateTimeString();
        foreach($permission_array as $value){
            $permissionData[] = [
                'name' => $value,
                'guard_name' => 'web',
                'module' => $group->id,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        $permission = Permission::insert($permissionData);

        if(empty($permission)){
            $this->flashRepository->setFlashSession('alert-danger', 'Permission not created.');
            return redirect()->route('permission.index');
        }

        if($group){
            return response($group);
        }
    }
}
