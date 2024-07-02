<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModulePostRequest;
use App\Models\Attribute;
use App\Models\MenuManager;
use App\Models\Module;
use App\Repositories\FlashRepository;
use App\Services\GeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Generators\GeneratorUtils;

class ModuleManagerController extends Controller
{
    private $flashRepository;
    protected $generatorService;

    public function __construct()
    {
        $this->flashRepository = new FlashRepository;
        $this->generatorService = new GeneratorService();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $moduleData = Module::get();
        return view('module_manager.menu', ['menu' => new MenuManager(), 'data' => $data, 'moduleData' => $moduleData]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  void
     */
    public function store(ModulePostRequest $request)
    {

        // try {
        //     DB::beginTransaction();
        $module = Module::create([
            'name' => $request->name,
            'is_system' => isset($request->is_system) ? 1 : 0,
            'code' => str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower())),
            'user_id' => auth()->user()->id,
            'type' => isset($request->mtype) ? $request->mtype : null,
            'status' => isset($request->status) ? $request->status : null,


        ]);

        $request->code = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $requestData = $request->all();
        $request->validated();
        $this->generatorService->generateModel($request->all()); // model
        $this->generatorService->generateMigration($request->all(), $module->id); // migration
        Artisan::call('migrate'); // run php artisan mnigrate in background
        $this->generatorService->generateController($request->all()); // migration
        $this->generatorService->generateRequest($request->all()); // req
        $this->generatorService->generateRoute($request->all()); // route
        $this->generatorService->generateViews($request->all()); // views
        $this->generatorService->generatePermission($request->all(), $module->id);


        if (!empty($request->fields[0])) {
            foreach ($request->fields as $i => $attr) {
                $createArr = [

                    'module' => $module->id,
                    'name' => $attr,
                    'type' => $request['column_types'][$i],
                    'min_length' => $request['min_lengths'][$i],
                    'max_length' => $request['max_lengths'][$i],
                    'steps' => $request['steps'][$i],
                    'input' => $request['input_types'][$i],
                    'required' => $request['requireds'][$i],
                    'default_value' => $request['default_values'][$i],
                    'select_option' => $request['select_options'][$i],
                    'constrain' => $request['constrains'][$i],
                    'on_update_foreign' => $request['on_update_foreign'][$i],
                    'on_delete_foreign' => $request['on_delete_foreign'][$i],
                    'is_enable' => isset($request['is_enable'][$i]) ? 1 : 0,
                    'is_system' => isset($request['is_system'][$i]) ? 1 : 0,
                    'max_size' => $request['files_sizes'][$i],
                    'file_type' => $request['file_types'][$i],

                ];

                // dd($createArr);
                $attribute = Attribute::create($createArr);
            }
        }

        if ($module) {



            $lastSequenceData = MenuManager::where('parent', '0')->where('menu_type', $requestData['menu_type'])->where('include_in_menu', 1)->orderBy('id', 'desc')->first();
            $sequence = 0;
            if ($lastSequenceData) {
                $sequence = $lastSequenceData->sequence + 1;
            }

            $createData = array(
                'name' => $requestData['name'],
                'module_id' => $module->id,
                'include_in_menu' => (isset($requestData['include_in_menu']) ?? 0),
                'menu_type' => $requestData['menu_type'],
                'path' => str_replace(' ', '', $requestData['path']),
                'sequence' => $sequence,
                'parent' => 0,
                'sidebar_name' => $requestData['sidebar_name'],
            );
            $menuManager = MenuManager::create($createData);
        }

        if (!$menuManager) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module_manager.index');
        }
        $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        return redirect()->route('module_manager.index');

        //     DB::commit();
        // } catch (Exception $ex) {
        //     DB::rollback();
        //     dd($ex);
        // }

    }


    public function storeFront(ModulePostRequest $request)
    {

        // try {
        //     DB::beginTransaction();
        $module = Module::create([
            'name' => $request->name,
            'is_system' => isset($request->is_system) ? 1 : 0,
            'code' => str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower())),
            'user_id' => auth()->user()->id,
            'type' => isset($request->mtype) ? $request->mtype : null,
            'status' => isset($request->status) ? $request->status : null,


        ]);

        $request->code = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $requestData = $request->all();
        $request->validated();
        $this->generatorService->generateModel($request->all()); // model
        $this->generatorService->generateMigration($request->all(), $module->id); // migration
        Artisan::call('migrate'); // run php artisan mnigrate in background
        $this->generatorService->generateController($request->all()); // migration
        $this->generatorService->generateRequest($request->all()); // req
        $this->generatorService->generateRoute($request->all()); // route
        $this->generatorService->generateViews($request->all()); // views
        $this->generatorService->generatePermission($request->all(), $module->id);


        if (!empty($request->fields[0])) {
            foreach ($request->fields as $i => $attr) {
                $createArr = [

                    'module' => $module->id,
                    'name' => $attr,
                    'type' => $request['column_types'][$i],
                    'min_length' => $request['min_lengths'][$i],
                    'max_length' => $request['max_lengths'][$i],
                    'steps' => $request['steps'][$i],
                    'input' => $request['input_types'][$i],
                    'required' => $request['requireds'][$i],
                    'default_value' => $request['default_values'][$i],
                    'select_option' => $request['select_options'][$i],
                    'constrain' => $request['constrains'][$i],
                    'on_update_foreign' => $request['on_update_foreign'][$i],
                    'on_delete_foreign' => $request['on_delete_foreign'][$i],
                    'is_enable' => isset($request['is_enable'][$i]) ? 1 : 0,
                    'is_system' => isset($request['is_system'][$i]) ? 1 : 0,
                    'max_size' => $request['files_sizes'][$i],
                    'file_type' => $request['file_types'][$i],

                ];

                // dd($createArr);
                $attribute = Attribute::create($createArr);
            }
        }

        if ($module) {



            $lastSequenceData = MenuManager::where('parent', '0')->where('menu_type', $requestData['menu_type'])->where('include_in_menu', 1)->orderBy('id', 'desc')->first();
            $sequence = 0;
            if ($lastSequenceData) {
                $sequence = $lastSequenceData->sequence + 1;
            }

            $createData = array(
                'name' => $requestData['name'],
                'module_id' => $module->id,
                'include_in_menu' => 1,
                'menu_type' => $requestData['menu_type'],
                'path' => str_replace(' ', '', $requestData['path']),
                'sequence' => $sequence,
                'parent' => 0,
                'sidebar_name' => $requestData['sidebar_name'],
            );
            // dd($createData);
            $menuManager = MenuManager::create($createData);
        }

        if (!$menuManager) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module_manager.index');
        }
        $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        return redirect()->route('module_manager.index');

        //     DB::commit();
        // } catch (Exception $ex) {
        //     DB::rollback();
        //     dd($ex);
        // }

    }

    public function storeLabel(Request $request)
    {
        // dd($request->all());

        // try {
        //     DB::beginTransaction();
        $module = Module::create([
            'name' => $request->name,
            'is_system' => isset($request->is_system) ? 1 : 0,
            'code' => $request->name,
            'user_id' => auth()->user()->id,

        ]);

        $requestData = $request->all();



        if ($module) {



            $lastSequenceData = MenuManager::where('parent', '0')->where('menu_type', $requestData['menu_type'])->where('include_in_menu', 1)->orderBy('id', 'desc')->first();
            $sequence = 0;
            if ($lastSequenceData) {
                $sequence = $lastSequenceData->sequence + 1;
            }

            $createData = array(
                'name' => $requestData['name'],
                'module_id' => $module->id,
                'include_in_menu' => 1,
                'menu_type' => $requestData['menu_type'],
                'path' => '',
                'sequence' => $sequence,
                'parent' => 0,
                'sidebar_name' => $requestData['name'],
            );
            $menuManager = MenuManager::create($createData);
        }

        if (!$menuManager) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module_manager.index');
        }
        $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        return redirect()->route('module_manager.index');

        //     DB::commit();
        // } catch (Exception $ex) {
        //     DB::rollback();
        //     dd($ex);
        // }

    }

    public function menu_update(Request $request)
    {
        // dd($request);
        if ($request->type == 'storfront') {
            $dataArray = json_decode($request['storfront_json'], true);
        } else {
            $dataArray = json_decode($request['admin_json'], true);
        }
        // dd($request->all(),$dataArray);
        $data = $this->processArray($dataArray);

        return response()->json(['success' => true]);
    }

    public function processArray($dataArray)
    {
        foreach ($dataArray as $item) {
            $data = MenuManager::find($item['id']);
            $data->sequence = $item['sequence'];
            $data->parent = $item['parent'];
            $data->save();
            // Check if there are children and recursively process them
            if (isset($item['children']) && is_array($item['children']) && count($item['children']) > 0) {
                $this->processArray($item['children']);
            }
        }
    }

    public function update(Request $request, $id)
    {

        $module = Module::find($id);

        if (!empty($request->name)):
            $module->update(
                [
                    'is_system' => isset($request->is_system) ? 1 : 0,
                    'name' => $request->name,
                    'type' => isset($request->mtype) ? $request->mtype : null,
                    'status' => isset($request->status) ? $request->status : null,

                ]
            );

            $menu = MenuManager::where('module_id', $module->id)->first();

            if($menu->menu_type == 'storfront'){
                $request->include_in_menu = 1;
            }
            $menu->update(
                [
                    'name' => $request->name,
                    'sidebar_name' => $request->sidebar_name,
                    'include_in_menu' => isset($request->include_in_menu) ? 1 : 0,
                ]
            );
        endif;

        if (!$module) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module_manager.index');
        }

        $this->generatorService->reGenerateModel($request['module']);
        // $this->generatorService->reGenerateMigration($request['module']);
        $this->generatorService->reGenerateController($request['module']);
        $this->generatorService->reGenerateRequest($request['module']);
        $this->generatorService->reGenerateViews($request['module']);
        $this->generatorService->reGenerateFormWithSub($request['module']); // sub

        Artisan::call("optimize:clear");
        try {
            $this->generatorService->reGeneratePermission($request['module']);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $this->flashRepository->setFlashSession('alert-success', 'Module updated successfully.');
        return redirect()->route('module_manager.index');
    }

    private function generateMigrationContentforDelete($table)
    {
        // Define the migration schema here based on $newTable
        $content = <<<EOT
        <?php
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            public function up()
            {
                Schema::dropIfExists('$table');
            }

            public function down()
            {
            }
        };
        EOT;

        return $content;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $migrationName = "delete_" . strtolower($module->name) . "_table";
        $modelClassName = Str::studly($module->name);
        // Create the migration
        Artisan::call('make:migration', [
            'name' => $migrationName,
        ]);

        $migrationFilePath = database_path("migrations") . "/" . date('Y_m_d_His') . "_$migrationName.php";
        File::put($migrationFilePath, $this->generateMigrationContentforDelete(strtolower($module->name)));

        Artisan::call('migrate');
        $module = Module::find($module->id)->delete();
        if ($module) {
            return response()->json(['msg' => 'Module deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }

    public function deleteORRestore(Request $request)
    {

        $model = Module::find($request->model_id);
        $model->is_delete = $request->is_delete;
        $model->save();

        $menu = MenuManager::where('module_id', $model->id)->first();
        $menu->is_delete = $request->is_delete;
        $menu->save();

        if ($model) {
            if ($request->is_delete == 1) {
                return response()->json(['msg' => 'Module deleted successfully!'], 200);
            }
            return response()->json(['msg' => 'Module restored successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }

    }

    public function updateStatus(Request $request, $moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->is_enable = $request->state === 'enabled' ? 1 : 0;
        $module->save();
        return response()->json(['message' => 'Module status toggled successfully']);
    }

    public function menuDelete(Request $request)
    {
        $menuManager = MenuManager::find($request->menu_id);
        if ($menuManager) {
            $menuManager->is_deleted = $request->is_deleted;
            $menuManager->deleted_at = $request->is_deleted == 1 ? Carbon::now()->format('Y-m-d H:i:s') : null;
            $menuManager->save();
            if ($request->is_deleted == 1) {
                $message = 'Menu Temperory Deleted successfully, You can restore within 30 days.';
            } else {
                $message = 'Menu Restored successfully.';
            }
            return response()->json(['is_deleted' => $menuManager->is_deleted, 'message' => $message], 200);
        } else {
            return response()->json(['message' => 'Menu not found.'], 200);
        }
    }

    public function edit(Module $module)
    {

        return view('module_manager.menu-edit', compact('module'));
    }

    public function addSub($id)
    {
        return view('module_manager.subform', compact('id'));
    }


    public function storeSub(Request $request, $id)
    {

        // try {
        //     DB::beginTransaction();

        $addable = $request->addable;
        $module = Module::create([
            'name' => $request->name,
            'is_system' => isset($request->is_system) ? 1 : 0,
            'shared' => isset($request->shared) ? 1 : 0,
            'addable' => isset($request->addable) ? 1 : 0,
            'is_sub' => 1,
            'code' => $request->code,
            'user_id' => auth()->user()->id,
            'parent_id' => $id,
            'migration' => $id,

        ]);

        // dd( $request->all() );

        if ((!isset($request->shared) || $request->shared == 0) || $request->addable) {
            $attr = Attribute::find($request->attr_id);
            $new_attr = $attr->replicate();
            $new_attr->created_at = Carbon::now();
            $new_attr->module = $module->id;
            $new_attr->save();
        }

        $this->generatorService->reGenerateFormWithSub($id); // sub


        $this->generatorService->generateModel($request->all()); // model
        $this->generatorService->generateMigration($request->all(), $module->id); // migration
        Artisan::call('migrate'); // run php artisan mnigrate in background
        $this->generatorService->generateController($request->all()); // migration
        $this->generatorService->generateRequest($request->all()); // req
        $this->generatorService->generateRoute($request->all()); // route
        $this->generatorService->generateViews($request->all()); // views
        $this->generatorService->generatePermission($request->all(), $module->id);



        if ($module) {

            $menuParent = MenuManager::where('module_id', $id)->first()->id;

            $requestData = $request->all();

            $lastSequenceData = MenuManager::where('parent', $menuParent)->where('menu_type', $requestData['menu_type'])->where('include_in_menu', 1)->orderBy('id', 'desc')->first();
            $sequence = 0;
            if ($lastSequenceData) {
                $sequence = $lastSequenceData->sequence + 1;
            }

            $model = GeneratorUtils::setModelName($request['code']);

            $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($model);

            $createData = array(
                'name' => $requestData['name'],
                'module_id' => $module->id,
                'include_in_menu' => $addable ? 1 : 0, // add menu label for addable only
                'menu_type' => $requestData['menu_type'],
                'path' => $modelNamePluralLowercase,
                'sequence' => $sequence,
                'parent' => $menuParent,
                'sidebar_name' => $request->name,
            );
            $menuManager = MenuManager::create($createData);
        }


        $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        return redirect()->route('module_manager.index');

        //     DB::commit();
        // } catch (Exception $ex) {
        //     DB::rollback();
        //     dd($ex);
        // }

    }

    public function storeSubPost(Request $request)
    {
        // dd(!isset($request->shared));
        // dd($request->all());

        // try {
        //     DB::beginTransaction();



        $module = Module::create([
            'name' => $request->name,
            'is_system' => 0,
            'code' => str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower())),
            'is_sub' => 1,
            'user_id' => auth()->user()->id,
            'parent_id' => $request->parent_id,
            'migration' => $request->parent_id,
            'shared' => isset($request->shared) ? 1 : 0,
            'addable' => isset($request->addable) ? 1 : 0,

        ]);
        $request->code = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));
        
        
        //default sub
        if (!isset($request->shared)) {

            $constrain = Module::find($request->parent_id);
            $constrainAtrr = Attribute::find($request->attr_id);
            $lookUpAttr = [

                'module' => $module->id,
                'name' => $constrain->name,
                'type' => "foreignId",
                'min_length' => null,
                'max_length' => null,
                'steps' => null,
                'input' => "select",
                'required' => 'yes',
                'default_value' => null,
                'select_option' => null,
                'constrain' => $constrain->code,
                'on_update_foreign' => "1",
                'on_delete_foreign' => "1",
                'is_enable' => 1,
                'is_system' => 0,
                'is_multi' => 0, //for multi select
                'max_size' => null,
                'file_type' => null,
                'source' => null,
                'target' => null,
                'code' => $constrain->code . "_id",
                'attribute' => $constrainAtrr->code,
                'user_id' => auth()->user()->id,
            ];

            $attribute = Attribute::create($lookUpAttr);
        }


        // if( ( !isset($request->shared) || $request->shared == 0 ) || $request->addable  ){
        //     $attr = Attribute::find($request->attr_id);
        //     $new_attr = $attr->replicate();
        //     $new_attr->created_at = Carbon::now();
        //     $new_attr->module = $module->id;
        //     $new_attr->save();
        // }

        $this->generatorService->reGenerateFormWithSub($request->parent_id); // sub
        $this->generatorService->generateModel($request->all()); // model
        $this->generatorService->generateMigration($request->all(), $module->id); // migration
        $this->generatorService->generateController($request->all()); // migration
        $this->generatorService->generateRequest($request->all()); // req
        $this->generatorService->generateRoute($request->all()); // route
        $this->generatorService->generateViews($request->all()); // views
        $this->generatorService->generatePermission($request->all(), $module->id);


        // re create view for duplcate attr views
        if ((!isset($request->shared)) || $request->addable) {
            $this->generatorService->reGenerateModel($module->id);
            $this->generatorService->reGenerateMigration($module->id);
            $this->generatorService->reGenerateController($module->id);
            $this->generatorService->reGenerateRequest($module->id);
            $this->generatorService->reGenerateViews($module->id);
            $this->generatorService->reGeneratePermission($module->id);
        }

        Artisan::call("migrate");



        if ($module) {

            $menuParent = MenuManager::where('module_id', $request->parent_id)->first()->id;

            $requestData = $request->all();

            $lastSequenceData = MenuManager::where('parent', $menuParent)->where('menu_type', $requestData['menu_type'])->where('include_in_menu', 1)->orderBy('id', 'desc')->first();
            $sequence = 0;
            if ($lastSequenceData) {
                $sequence = $lastSequenceData->sequence + 1;
            }

            $model = GeneratorUtils::setModelName($request['code']);

            $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($model);

            $createData = array(
                'name' => $requestData['name'],
                'module_id' => $module->id,
                'include_in_menu' => isset($request->shared) ? ( isset($request->addable) ? 1 : 0 ) : 1,
                'menu_type' => $requestData['menu_type'],
                'path' => $modelNamePluralLowercase,
                'sequence' => $sequence,
                'parent' => $menuParent,
                'sidebar_name' => $request->name,
            );
            $menuManager = MenuManager::create($createData);
        }


        $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        return redirect()->route('module_manager.index');

        //     DB::commit();
        // } catch (Exception $ex) {
        //     DB::rollback();
        //     dd($ex);
        // }

    }

    public function forceDelete($id)
    {

        $model = Module::find($id);
        foreach ($model->fields as $attribute) {
            if ($attribute) {
                $this->generatorService->removeMigration($id, $attribute->id);
                $attribute->delete();
            }
        }
        $this->generatorService->removeMigrationTable($id);



        $model->delete();
        $menu = MenuManager::where('module_id', $id)->first();

        $menus = MenuManager::where('parent', $menu->id)->update(['parent' => 0]);

        $menu->delete();



        $this->flashRepository->setFlashSession('alert-success', 'Module Was Deleted successfully.');
        return redirect()->route('module_manager.index');

    }

}
