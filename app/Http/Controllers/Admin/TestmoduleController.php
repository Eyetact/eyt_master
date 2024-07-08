<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Testmodule;
use App\Http\Requests\Admin\{StoreTestmoduleRequest, UpdateTestmoduleRequest};
use App\Models\UCGroup;
use App\Models\Attribute;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GeneratorService;
use App\Models\Module;
use App\Generators\GeneratorUtils;
use App\Models\User;
use App\Models\Limit;
use Illuminate\Http\Request;
use DB;





class TestmoduleController extends Controller
{
    use TestmoduleTrait;
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
            $testmodules = Testmodule::query();

               if (auth()->user()->access_table == "Group") {
                $group_ids = auth()->user()->groups()->pluck('group_id');

                $userids= UCGroup::whereIn('group_id', $group_ids)
                ->pluck('user_id');



                $testmodules = Testmodule::whereIn('user_id', $userids)->orWhere('customer_id',auth()->user()->id)->orWhere('assign_id',auth()->user()->id)->get();
            }

            if (auth()->user()->access_table == "Individual") {

                     $empl_ids = User::where('user_id',auth()->user()->id)->pluck('id');



                $testmodules = Testmodule::where('user_id', auth()->user()->id)->orWhereIn('user_id', $empl_ids)->orWhere('customer_id',auth()->user()->id)->orWhere('assign_id',auth()->user()->id)->get();

            }

            return DataTables::of($testmodules)
                ->addColumn('action', 'admin.testmodules.include.action')
                ->toJson();
        }

        return view('admin.testmodules.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {

        return view('admin.testmodules.create');
    }


    public function createLess()
    {

        return view('admin.testmodules.create-less');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(StoreTestmoduleRequest $request)
    {
          $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testmodule'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testmodule'))->first();
            $module_id = (int)$module?->id;



        // dd(auth()->user());
        if (auth()->user()->id != 1) {

            $sub = auth()->user()?->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
            if ((!(auth()->user()->hasRole('vendor')) || !(auth()->user()->hasRole('admin'))) && auth()->user()->user_id != 1) {
                $customer = User::find(auth()->user()->user_id);
                $sub = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
            }
            if($module->user_id != 1){

            $limit = Limit::where('module_id', $module_id)
                ->where('subscription_id', $sub?->id)
                ->first();


            if (!$limit) {
                $do = true;

                if ((!(auth()->user()->hasRole('vendor')) || !(auth()->user()->hasRole('admin'))) && auth()->user()->user_id != 1) {
                    $customer = User::find(auth()->user()->user_id);
                    $sub = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
                } elseif ((!(auth()->user()->hasRole('vendor')) && !(auth()->user()->hasRole('admin'))) && auth()->user()->user_id == 1) {
                    $do = false;
                }

                // dd($do);
                if ($do) {

                    $limit = new Limit();
                    $limit->plan_id = $sub->plan_id;
                    $limit->subscription_id = $sub->id;
                    $limit->data_limit = 0;
                    $limit->module_id = $module_id;
                    $limit->save();


                }

            }
            if($limit){
                $limit->data_limit = (int)$limit->data_limit + 1;
                $limit->save();
            }
            }
        }


        $insert = Testmodule::create($request->validated());

        if($module->is_system)
        {

  if (auth()->user()->hasRole('super')) {


            $insert->global = 1;
            $insert->status = "active";
            $insert->save();

        }

        if (auth()->user()->hasAnyRole(['vendor', 'admin'])) {
            if ($request->global == 0) {


                $insert->status = "inactive";
                $insert->save();


            }

            if ($request->global == 1) {


                $insert->status = "pending";
                $insert->save();


            }
        }


        }



             $attributes=Attribute::where('module',$module_id)->where('multiple',1)->get();
            foreach($attributes as $attr)
            {


            if ($request->has($attr->code)) {
            foreach ($request->input($attr->code) as $mId) {

                $model1=GeneratorUtils::singularSnakeCase(Module::find($attr->module)->name);

                        $model2=GeneratorUtils::singularSnakeCase($attr->constrain);

                        $table_name= $model1 . "_" . $model2;
                        $id1=$model1 . "_id";
                        $id2=$model2 . "_id";

                $values = array($id1 => $insert->id,$id2 => $mId);
                DB::table($table_name)->insert($values);

            }
        }

            }






        $this->customStore($request,$insert);

        if ($module->addable) {
            $parent = Module::find($module->parent_id);


            $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($parent->code);

            $data = new $modelName();
            foreach ($parent->fields as $value) {

                $attr = GeneratorUtils::singularSnakeCase($value->code);

                $data->$attr = $request[$attr];
            }
            $data->save();

            $insert->sub_id = $parent->id;
            $insert->data_id = $data->id;
            $insert->save();
        }

        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

            $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr = GeneratorUtils::singularSnakeCase($value->code);

                $data->$attr = $request[$attr];
            }
            $data->save();

             $insert->sub_id = $request->module;
              $insert->data_id = $data->id;
            $insert->save();

        }

        $parent_id = (int)Module::where('code','LIKE', '%' . 'Testmodule' . '%' )->first()?->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('testmodules.index')
            ->with('success', __('The testmodule was created successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            if(!empty($parent->migration)){
            // dd($parent);
            return redirect()
                ->route($parent.'.index')
                ->with('success', __('The testmodule  was created successfully.'));
            }
             return redirect()
            ->route('testmodules.index')
            ->with('success', __('The testmodule was created successfully.'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testmodule  $testmodule
     * @return
     */


     public function show(Testmodule $testmodule )
    {


          if($testmodule->sub_id)
          {
        $module = Module::find($testmodule->sub_id);
        $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
        $child =  $modelName::find($testmodule->data_id);


        return view('admin.testmodules.show', compact('testmodule','module','child'));
          }


        return view('admin.testmodules.show', compact('testmodule'));
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testmodule  $testmodule
     * @return
     */
    public function edit(Testmodule $testmodule)
    {
        return view('admin.testmodules.edit', compact('testmodule'));
    }

     public function editLess($id)
    {

        $testmodule = Testmodule::find($id);
        return view('admin.testmodules.edit-less', compact('testmodule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testmodule  $testmodule
     * @return
     */
    public function update(UpdateTestmoduleRequest $request, Testmodule $testmodule)
    {
        $testmodule->update($request->validated());



         $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testmodule'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testmodule'))->first();
            $module_id = (int)$module?->id;



           $attributes=Attribute::where('module',$module_id)->where('multiple',1)->get();


            foreach($attributes as $attr)
            {

            $relation = GeneratorUtils::singularSnakeCase(str_replace('_id','',$attr->code));



            if (isset($request[$attr->code])) {

               $testmodule->$relation()->sync($request[$attr->code]);

            } else {

            $testmodule->$relation()->detach();
            }

            }



        $this->customUpdate($request,$testmodule);

          if ($request->status == "active") {


            $testmodule->global = 1;
            $testmodule->status = "active";
            $testmodule->save();


        }


        if ($request->status == "inactive") {


             $testmodule->global = 0;
             $testmodule->status = "inactive";
             $testmodule->save();


        }


        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

                      if($request->module == $testmodule->sub_id)
                      {

            $data =  $modelName::find($testmodule->data_id);
            foreach ($module->fields as $value) {

                $attr =  GeneratorUtils::singularSnakeCase($value->code);

                $data->$attr = $request[$attr];
            }
            $data->save();
                      }

                      else{

                           $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr =  GeneratorUtils::singularSnakeCase($value->code);

                $data->$attr = $request[$attr];
            }
            $data->save();

             $testmodule->sub_id = $request->module;
              $testmodule->data_id = $data->id;
            $testmodule->save();

                      }


        }

             $parent_id = (int)Module::where('code','LIKE', '%' . 'Testmodule' . '%' )->first()?->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('testmodules.index')
            ->with('success', __('The testmodule was updated successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
            if(!empty($parent->migration)){
         return redirect()
            ->route('testmodules.index')
            ->with('success', __('The testmodule was updated successfully.'));
            }
             return redirect()
            ->route('testmodules.index')
            ->with('success', __('The testmodule was updated successfully.'));

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testmodule  $testmodule
     * @return
     */
    public function destroy(Testmodule $testmodule)
    {
        try {

     $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testmodule'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testmodule'))->first();
            $module_id = (int)$module?->id;


            if (auth()->user()->id != 1) {

                $sub = auth()->user()?->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
                if ((!(auth()->user()->hasRole('vendor')) || !(auth()->user()->hasRole('admin'))) && auth()->user()->user_id != 1) {
                    $customer = User::find(auth()->user()->user_id);
                    $sub = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
                }
                if($module->user_id != 1){
                    $limit = Limit::where('module_id', $module_id)
                        ->where('subscription_id', $sub?->id)
                        ->first();

                    if ($limit) {
                        $do = true;

                        if ((!(auth()->user()->hasRole('vendor')) || !(auth()->user()->hasRole('admin')) )&& auth()->user()->user_id != 1) {
                            $customer = User::find(auth()->user()->user_id);
                            $sub = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
                        } elseif ((!(auth()->user()->hasRole('vendor')) && !(auth()->user()->hasRole('admin'))) && auth()->user()->user_id == 1) {
                            $do = false;
                        }
                        if ($do) {




                            $limit->data_limit=(int)$limit->data_limit - 1;
                            $limit->save();
                        }

                    }
                }
            }


            if($testmodule->sub_id)
            {

            $module = Module::find($testmodule->sub_id);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
            $child =  $modelName::find($testmodule->data_id);
            $child->delete();
            }

            $testmodule->delete();


            return response()->json(['msg' => 'Item deleted successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Not deleted'], 500);
        }
    }

    public function assign(Request $request){
        // dd($request);
        $selected_row = $request->rows_selected;
        $g_id = $request->g_id;
        $c_id = $request->c_id;
        if(count($selected_row)){
            foreach ($selected_row as $key => $value) {
                $item = Testmodule::find($value['id']);
                $item->customer_id = (int)$c_id > 0 ? $c_id : null;
                $item->customer_group_id = (int)$g_id > 0 ? $g_id : null;
                $item->save();
            }
            return response()->json(['msg' => 'Items assigned successfully!'], 200);

        }
        return response()->json(['msg' => 'no items selected'], 500);
    }
}
