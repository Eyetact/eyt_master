<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Testing;
use App\Http\Requests\Admin\{StoreTestingRequest, UpdateTestingRequest};
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





class TestingController extends Controller
{
    use TestingTrait;
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
            $testings = Testing::query();

               if (auth()->user()->access_table == "Group") {
                $group_ids = auth()->user()->groups()->pluck('group_id');

                $userids= UCGroup::whereIn('group_id', $group_ids)
                ->pluck('user_id');



                $testings = Testing::whereIn('user_id', $userids)->orWhere('customer_id',auth()->user()->id)->orWhere('assign_id',auth()->user()->id)->get();
            }

            if (auth()->user()->access_table == "Individual") {

                     $empl_ids = User::where('user_id',auth()->user()->id)->pluck('id');



                $testings = Testing::where('user_id', auth()->user()->id)->orWhereIn('user_id', $empl_ids)->orWhere('customer_id',auth()->user()->id)->orWhere('assign_id',auth()->user()->id)->get();

            }

            return DataTables::of($testings)
                ->addColumn('action', 'admin.testings.include.action')
                ->toJson();
        }

        return view('admin.testings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {

        return view('admin.testings.create');
    }


    public function createLess()
    {

        return view('admin.testings.create-less');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(StoreTestingRequest $request)
    {
          $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testing'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testing'))->first();
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


        $insert = Testing::create($request->validated());

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

        $parent_id = (int)Module::where('code','LIKE', '%' . 'Testing' . '%' )->first()?->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('testings.index')
            ->with('success', __('The testing was created successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            if(!empty($parent->migration)){
            // dd($parent);
            return redirect()
                ->route($parent.'.index')
                ->with('success', __('The testing  was created successfully.'));
            }
             return redirect()
            ->route('testings.index')
            ->with('success', __('The testing was created successfully.'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testing  $testing
     * @return
     */


     public function show(Testing $testing )
    {


          if($testing->sub_id)
          {
        $module = Module::find($testing->sub_id);
        $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
        $child =  $modelName::find($testing->data_id);


        return view('admin.testings.show', compact('testing','module','child'));
          }


        return view('admin.testings.show', compact('testing'));
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testing  $testing
     * @return
     */
    public function edit(Testing $testing)
    {
        return view('admin.testings.edit', compact('testing'));
    }

     public function editLess($id)
    {

        $testing = Testing::find($id);
        return view('admin.testings.edit-less', compact('testing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testing  $testing
     * @return
     */
    public function update(UpdateTestingRequest $request, Testing $testing)
    {
        $testing->update($request->validated());



         $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testing'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testing'))->first();
            $module_id = (int)$module?->id;



           $attributes=Attribute::where('module',$module_id)->where('multiple',1)->get();


            foreach($attributes as $attr)
            {

            $relation = GeneratorUtils::singularSnakeCase(str_replace('_id','',$attr->code));



            if (isset($request[$attr->code])) {

               $testing->$relation()->sync($request[$attr->code]);

            } else {

            $testing->$relation()->detach();
            }

            }



        $this->customUpdate($request,$testing);

          if ($request->status == "active") {


            $testing->global = 1;
            $testing->status = "active";
            $testing->save();


        }


        if ($request->status == "inactive") {


             $testing->global = 0;
             $testing->status = "inactive";
             $testing->save();


        }


        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

                      if($request->module == $testing->sub_id)
                      {

            $data =  $modelName::find($testing->data_id);
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

             $testing->sub_id = $request->module;
              $testing->data_id = $data->id;
            $testing->save();

                      }


        }

             $parent_id = (int)Module::where('code','LIKE', '%' . 'Testing' . '%' )->first()?->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('testings.index')
            ->with('success', __('The testing was updated successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
            if(!empty($parent->migration)){
         return redirect()
            ->route('testings.index')
            ->with('success', __('The testing was updated successfully.'));
            }
             return redirect()
            ->route('testings.index')
            ->with('success', __('The testing was updated successfully.'));

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testing  $testing
     * @return
     */
    public function destroy(Testing $testing)
    {
        try {

     $module =  Module::where('code', GeneratorUtils::singularSnakeCase('Testing'))->orWhere('code', GeneratorUtils::pluralSnakeCase('Testing'))->first();
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


            if($testing->sub_id)
            {

            $module = Module::find($testing->sub_id);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
            $child =  $modelName::find($testing->data_id);
            $child->delete();
            }

            $testing->delete();


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
                $item = Testing::find($value['id']);
                $item->customer_id = (int)$c_id > 0 ? $c_id : null;
                $item->customer_group_id = (int)$g_id > 0 ? $g_id : null;
                $item->save();
            }
            return response()->json(['msg' => 'Items assigned successfully!'], 200);

        }
        return response()->json(['msg' => 'no items selected'], 500);
    }
}
