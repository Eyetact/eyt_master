<?php

namespace App\Http\Controllers;

use App\Models\Multi;
use App\Services\GeneratorService;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Module;
use App\Models\User;
use App\Http\Requests\AttributePostRequest;
use App\Repositories\FlashRepository;
use Illuminate\Support\Facades\Artisan;
use App\Generators\GeneratorUtils;

class AttributeController extends Controller
{
    private $flashRepository;
    private $generatorService;

    public function __construct()
    {
        $this->flashRepository = new FlashRepository;
        $this->generatorService = new GeneratorService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {


        if (auth()->user()->hasRole('super')) {

            $attributes = Attribute::all();
        } else {

            if (auth()->user()->hasRole('vendor') || auth()->user()->hasRole('admin')) {

                $userId = auth()->user()->id;


                $ids = User::where('user_id', $userId)->pluck('id');


                $attributes = Attribute::where('user_id', $userId)
                    ->orWhereIn('user_id', $ids)
                    ->get();
            } else {

                if (auth()->user()->hasRole('vendor') || auth()->user()->hasRole('admin')) {


                    $userId = auth()->user()->id;


                    $ids = User::where('user_id', $userId)->pluck('id');


                    $attributes = Attribute::where('user_id', $userId)
                        ->orWhereIn('user_id', $ids)
                        ->get();
                } else {


                    $userId = auth()->user()->user_id;


                    $ids = User::where('user_id', $userId)->pluck('id');


                    $attributes = Attribute::where('user_id', $userId)
                        ->orWhereIn('user_id', $ids)
                        ->get();
                }
            }
        }

        if (request()->ajax()) {

            // $attribute = Attribute::all();



            // if (auth()->user()->access_table == "Group") {
            //     $group_ids = auth()->user()->groups()->pluck('group_id');

            //     $userids= UCGroup::whereIn('group_id', $group_ids)
            //     ->pluck('user_id');



            //     $attribute = Attribute::whereIn('user_id', $userids)->get();
            // }

            // if (auth()->user()->access_table == "Individual") {

            //     $attribute = Attribute::where('user_id', auth()->user()->id)->get();

            // }

            return datatables()->of($attributes)
                ->addColumn('module', function ($row) {
                    return $row->moduleObj->name;
                })
                //         ->addColumn('action', function ($row) {
                //             $btn = '<div class="dropdown">
                //     <a class=" dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                //         aria-haspopup="true" aria-expanded="false">
                //         <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                //     </a>

                //     <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                //     <li class="dropdown-item">
                //     <a href="#" id="edit_item"  data-path="' . route('attribute.edit', $row->id) . '">View or Edit</a>
                //     </li>
                //         <li class="dropdown-item">
                //         <a class="delete-attribute" href="#" data-id="' . $row->id . '" class="attribute-delete">Delete</a>
                //         </li>
                //     </ul>
                // </div>';

                //             return $btn;
                //         })
                ->addColumn('action', 'attribute.action')
                ->rawColumns(['action'])

                ->addIndexColumn()
                ->make(true);
        }

        $all = Module::where('is_delete', 0)->where('migration', '!=', NULL)->orWhere('id', '<=', 3)->get();

        // if (auth()->user()->access_table == "Group") {
        //     $group_ids = auth()->user()->groups()->pluck('group_id');

        //     $userids= UCGroup::whereIn('group_id', $group_ids)
        //     ->pluck('user_id');

        //     $all = Module::whereIn('user_id', $userids)
        //         ->get();


        // }

        // if (auth()->user()->access_table == "Individual") {

        //     $all = Module::where('user_id', auth()->user()->id)
        //         ->get();


        // }


        $options = '';
        $options = '<option  >-- select --</option>';

        foreach ($all as $key => $value) {
            $code = $value->code != null ? $value->code : $value->name;
            $options .= '<option data-id="' . $value->id . '" value="' . GeneratorUtils::singularSnakeCase($code)  . '" >' . $value->name . '</option>';
        }

        return view('attribute.list', ['attribute' => new Attribute(), 'all' => $options, 'attributes' => $attributes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {
        $moduleData = Module::where('is_delete', 0)->where('migration', '!=', NULL)->orWhere('id', '<=', 3)->get();

        // if (auth()->user()->access_table == "Group") {
        //     $group_ids = auth()->user()->groups()->pluck('group_id');

        //     $userids= UCGroup::whereIn('group_id', $group_ids)
        //     ->pluck('user_id');

        //     $moduleData = Module::whereIn('user_id', $userids)
        //         ->get();


        // }

        // if (auth()->user()->access_table == "Individual") {

        //     $moduleData = Module::where('user_id', auth()->user()->id)
        //         ->get();


        // }

        $all = Module::where('is_delete', 0)->where('migration', '!=', NULL)->get();

        return view('attribute.create', ['attribute' => new Attribute(), 'moduleData' => $moduleData, 'all' => $all]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributePostRequest $request)
    {
        $request->validated();
        $requestData = $request->all();
        // dd(  $requestData);

        $condition_value = '';
        $condition_attr = '';
        if(isset($requestData['condition_attr'])){
            $condition_attr = $requestData['condition_attr'];
            foreach ($requestData['condition_value'] as  $value) {
                $condition_value .= $value . '|';
            }
        }



        // dd($requestData);

        $attr = Attribute::where('name', $request['name'])->where('module', $request['module'])->first();
        if ($attr) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('attribute.index');
        }
        $enumValues = '';
        if (isset($request['fields_info'])) {
            $count = count($request['fields_info']);
            foreach ($request['fields_info'] as $key => $value) {
                if ($value['default'] == 1) {
                    $request['default_values'] = $value['value'];
                }
                if ($key == $count) {

                    $enumValues .= $value['value'];
                } else {

                    $enumValues .= $value['value'] . '|';
                }
            }

            $request['select_options'] = $enumValues;
        }

        // dd($request);

        $createArr = [

            'module' => $request['module'],
            'name' => str(str_replace('.', '', $request['name']))->lower(),
            'type' => $request['column_types'],
            'min_length' => $request['min_lengths'],
            'max_length' => $request['max_lengths'],
            'steps' => $request['steps'],
            'input' => $request['input_types'],
            'required' => isset($request['requireds']) ? 'yes' : 'no',
            'default_value' => $request['default_values'],
            'select_option' => $request['select_options'],
            'constrain' => $request['constrains'],
            'constrain2' => isset($request['constrains2']) ? $request['constrains2'] : null,
            'on_update_foreign' => $request['on_update_foreign'],
            'on_delete_foreign' => $request['on_delete_foreign'],
            'is_enable' => isset($request['is_enable']) ? 1 : 0,
            'is_system' => isset($request['is_system']) ? 1 : 0,
            'is_multi' => isset($request['is_multi']) ? 1 : 0, //for multi select
            'max_size' => $request['files_sizes'],
            'file_type' => $request['file_types'],
            'source' => $request['source'],
            'target' => $request['target'],
            'code' => str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower())),
            'attribute' => isset($request['attribute']) ? $request['attribute'] : ' ',
            'attribute2' => isset($request['attribute2']) ? $request['attribute2'] : null,
            'primary' => isset($request['primary']) ? $request['primary'] : null,
            'secondary' => isset($request['secondary']) ? $request['secondary'] : null,
            'fixed_value' => isset($request['fixed_value']) ? $request['fixed_value'] : null,
            'fk_type' => isset($request['fk_type']) ? $request['fk_type'] : null,
            'user_id' => auth()->user()->id,
            'multiple' => isset($request['multiple']) ? 1 : 0,
            'condition_attr' => $condition_attr,
            'condition_value' => $condition_value
        ];
        // dd($createArr);
        $attribute = Attribute::create($createArr);

        // dd($attribute);

        if (isset($requestData['fk_type']) && $requestData['fk_type']== 'condition') {


            $attribute->attribute = $attribute->condition_attr;
            $attribute->save();

        }

        if (isset($requestData['fk_type']) && $requestData['fk_type']== 'based') {


            $attribute->attribute = $attribute->condition_attr;
            $attribute->save();

        }

        if (isset($requestData['multi'])) {


            foreach ($requestData['multi'] as $key => $value) {
                $m = new Multi();
                $m->name =$value['name'];
                $m->type = $value['type'];
                $m->source = isset($value['source']) ? $value['source'] : '';
                $m->select_options = isset($value['select_options']) ? $value['select_options'] : '';
                $m->attribute_id = $attribute->id;
                $m->constrain = isset($value['constrain']) ? $value['constrain'] : '';
                $m->attribute = isset($value['attribute']) ? $value['attribute'] : '';
                $m->code = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($value['name'])->lower()));

                $m->save();
            }
        }

        try {
            $this->generatorService->reGenerateModel($request['module']);

            if(!isset($requestData['multiple']))
            {
            $this->generatorService->reGenerateMigration($request['module']);
            Artisan::call("migrate");
            }

            $this->generatorService->reGenerateController($request['module']);
            $this->generatorService->reGenerateRequest($request['module']);
            $this->generatorService->reGenerateViews($request['module']);
            $this->generatorService->generatePermissionForAttr($createArr, $attribute->id);


        } catch (\Throwable $th) {

            // $this->generatorService->removeMigration($request['module'], $attribute->id);
            // $attribute->delete();
            $this->generatorService->reGenerateModel($request['module']);
            $this->generatorService->reGenerateController($request['module']);
            $this->generatorService->reGenerateRequest($request['module']);
            $this->generatorService->reGenerateViews($request['module']);
        }

        Artisan::call("optimize:clear");


        if (isset($requestData['multiple'])) {


            $model1=GeneratorUtils::singularSnakeCase(Module::find($requestData['module'])->name);
            $model2=GeneratorUtils::singularSnakeCase($requestData['constrains']);

            $table_name= $model1 . "_" . $model2;
            $id1=$model1 . "_id";
            $id2=$model2 . "_id";

            $this->generatorService->generateMultipleMigration( $table_name,$id1,$id2);

        }


        // dd($requestData['multi']);

        if (!$attribute) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('attribute.index');
        }

        $this->flashRepository->setFlashSession('alert-success', 'Attribute created successfully.');
        return redirect()->route('attribute.index');
    }

    public function test($id)
    {
        $this->generatorService->reGenerateViews($id);
    }


    public function getAttrByModel(Module $module)
    {
        $attributes = Attribute::where('module', $module->id)->get();
        $options = '<option disabled selected>-- select --</option>';

        foreach ($attributes as $key => $value) {
            $options .= '<option data-id="' . $value->id . '" value="' . $value->code . '" >' . $value->name . '</option>';
        }
        return $options;
    }


    public function getAttrByModel2(Module $module)
    {
        $attributes = Attribute::where('module', $module->id)->get();
        $options = '<option disabled selected>-- select --</option>';

        foreach ($attributes as $key => $value) {
            $options .= '<option data-id="' . $value->id . '" value="' . $value->id . '" >' . $value->name . '</option>';
        }
        return $options;
    }

    public function getDataByModel($model_id,$attr_condtion)
    {
        $module =  Module::find($model_id);
        if($model_id == 1 || $model_id == 2 || $model_id == 3 || $model_id == 4 || $model_id == 5)
        {
            $modelName = "App\Models\\".GeneratorUtils::setModelName($module->code);
        }
        else{
        $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
        }
        $query =  $modelName::all()->pluck($attr_condtion,'id');
        // dd($query);

        $options = '<option disabled selected>-- select --</option>';

        foreach ($query as $key => $value) {
            $options .= '<option data-id="' . $key . '" value="' . $value . '" >' . $value . '</option>';
        }

        return $options;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return
     */
    public function edit(Attribute $attribute)
    {
        $module = Module::find($attribute->module);
        return view('attribute.edit', ['attribute' => $attribute, 'module' => $module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributePostRequest $request, Attribute $attribute)
    {


        $condition_value = '';

        if(isset($request['condition_value'])){

            foreach ($request['condition_value'] as  $value) {
                $condition_value .= $value . '|';
            }


        $attribute->condition_value = $condition_value;
        }

        $attribute->name = str(str_replace('.', '', $request['name']))->lower();
        $attribute->is_enable = isset($request['is_enable']) ? 1 : 0;
        $attribute->is_system = isset($request['is_system']) ? 1 : 0;
        $attribute->is_multi = isset($request['is_multi']) ? 1 : 0; //for multi select
        $attribute->source = isset($request['source']) ? $request['source'] : NULL;
        // $attribute->target = isset($request['target']) ? $request['target'] : NULL;



        $attribute->min_length = $request['min_lengths'];
        $attribute->max_length = $request['max_lengths'];


        $enumValues = '';
        if (isset($request['fields_info'])) {
            $count = count($request['fields_info']);
            // dd($count);
            $i = 1;
            foreach ($request['fields_info'] as $value) {

                if ($value['default'] == 1) {
                    $attribute->default_value = $value['value'];
                }
                if ($i == $count) {

                    $enumValues .= $value['value'];
                } else {

                    $enumValues .= $value['value'] . '|';
                }
                $i++;
            }

            $attribute->select_option = $enumValues;
        }

        $attribute->save();


        if (isset($request['multi'])) {
            $attribute->multis()->delete();

            // dd($request['multi']);
            foreach ($request['multi'] as $key => $value) {
                $m = new Multi();
                $m->name = str()->snake(str_replace('.', '', str($value['name'])->lower()));
                $m->type = $value['type'];
                $m->source = isset($value['source']) ? $value['source'] : '';
                $m->code = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($value['name'])->lower()));
                $m->select_options = isset($value['select_options']) ? $value['select_options'] : '';
                $m->attribute_id = $attribute->id;
                if(isset($value['constrain'])){
                    $m->constrain = isset($value['constrain']) ? $value['constrain'] : '';
                }
                if(isset($value['attribute'])){
                    $m->attribute = isset($value['attribute']) ? $value['attribute'] : '';
                }
                $m->save();
            }


            // $this->generatorService->reGenerateMigration($attribute->module);
            // Artisan::call("migrate");



        }
        $this->generatorService->reGenerateController($attribute->module);
        $this->generatorService->reGenerateModel($attribute->module);
        $this->generatorService->reGenerateRequest($attribute->module);
        $this->generatorService->reGenerateViews($attribute->module);

        Artisan::call("optimize:clear");


        if (!$attribute) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('attribute.index');
        }
        $this->flashRepository->setFlashSession('alert-success', 'Attribute updated successfully.');
        return redirect()->route('attribute.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $attribute = Attribute::find($attribute->id);
        $id = $attribute->module;
        if ($attribute) {
            $this->generatorService->removeMigration($id, $attribute->id);
            Artisan::call("migrate");
            $attribute->delete();
            $this->generatorService->reGenerateModel($id);
            $this->generatorService->reGenerateController($id);
            $this->generatorService->reGenerateRequest($id);
            $this->generatorService->reGenerateViews($id);


            return response()->json(['msg' => 'Attribute deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }

    public function updateStatus(Request $request, $attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);
        $attribute->is_enable = $request->state === 'enabled' ? 1 : 0;
        $attribute->save();
        return response()->json(['message' => 'Attribute status toggled successfully']);
    }
}
