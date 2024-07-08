<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\TestModule;
use App\Http\Requests\Admin\{StoreTestModuleRequest, UpdateTestModuleRequest};
use App\Models\UCGroup;
use App\Models\Attribute;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GeneratorService;
use App\Models\Module;
use App\Generators\GeneratorUtils;




class TestModuleController extends Controller
{
    private $generatorService;

    public function __construct()
    {
        $this->generatorService = new GeneratorService();

    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {



        if (request()->ajax()) {
            $testModules = TestModule::query();

               if (auth()->user()->access_table == "Group") {
                $group_ids = auth()->user()->groups()->pluck('group_id');

                $userids= UCGroup::whereIn('group_id', $group_ids)
                ->pluck('user_id');



                $testModules = TestModule::whereIn('user_id', $userids)->get();
            }

            if (auth()->user()->access_table == "Individual") {

                $testModules = TestModule::where('user_id', auth()->user()->id)->get();

            }

            return DataTables::of($testModules)
                ->addColumn('action', 'admin.test-modules.include.action')
                ->toJson();
        }

        return view('admin.test-modules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {

        return view('admin.test-modules.create');
    }


    public function createLess()
    {

        return view('admin.test-modules.create-less');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(StoreTestModuleRequest $request)
    {
        $insert = TestModule::create($request->validated());

        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

            $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();

             $insert->sub_id = $request->module;
              $insert->data_id = $data->id;
            $insert->save();

        }

        $parent_id = Module::where('code','LIKE', '%' . 'TestModule' . '%' )->first()->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('test-modules.index')
            ->with('success', __('The testModule was created successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
            return redirect()
                ->route($parent.'.index')
                ->with('success', __('The testModule  was created successfully.'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TestModule  $testModule
     * @return
     */


     public function show(TestModule $testModule )
    {


        $module = Module::find($testModule->sub_id);
        $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
        $child =  $modelName::find($testModule->data_id);


        return view('admin.test-modules.show', compact('testModule','module','child'));
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TestModule  $testModule
     * @return
     */
    public function edit(TestModule $testModule)
    {
        return view('admin.test-modules.edit', compact('testModule'));
    }

     public function editLess($id)
    {

        $testModule = TestModule::find($id);
        return view('admin.test-modules.edit-less', compact('testModule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TestModule  $testModule
     * @return
     */
    public function update(UpdateTestModuleRequest $request, TestModule $testModule)
    {
        $testModule->update($request->validated());


        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

                      if($request->module == $testModule->sub_id)
                      {

            $data =  $modelName::find($testModule->data_id);
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();
                      }

                      else{

                           $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();

             $testModule->sub_id = $request->module;
              $testModule->data_id = $data->id;
            $testModule->save();

                      }


        }

             $parent_id = Module::where('code','LIKE', '%' . 'TestModule' . '%' )->first()->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('test-modules.index')
            ->with('success', __('The testModule was updated successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
         return redirect()
            ->route('test-modules.index')
            ->with('success', __('The testModule was updated successfully.'));

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TestModule  $testModule
     * @return
     */
    public function destroy(TestModule $testModule)
    {
        try {


            if($testModule->sub_id)
            {

            $module = Module::find($testModule->sub_id);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
            $child =  $modelName::find($testModule->data_id);
            $child->delete();
            }

            $testModule->delete();


            return response()->json(['msg' => 'Item deleted successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Not deleted'], 500);
        }
    }
}
