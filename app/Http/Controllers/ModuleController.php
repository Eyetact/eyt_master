<?php

namespace App\Http\Controllers;

use App\Services\GeneratorService;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Http\Requests\ModulePostRequest;
use App\Repositories\FlashRepository;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ModuleController extends Controller
{
    private $flashRepository;

    public $generatorService;

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
        if (request()->ajax()) {
            $module = Module::all();

            return datatables()->of($module)
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<button title="Change status" class="toggle-btn btn btn-' . ($row->is_enable ? 'danger' : 'success') . ' btn-xs" data-id="' . $row->id . '" data-state="' . ($row->is_enable ? 'disabled' : 'enabled') . '">' . ($row->is_enable ? 'Disable' : 'Enable') . '</button>';
                    $btn =  $btn . '&nbsp;&nbsp; <a class="btn btn-icon  btn-warning" href="' . route('module.edit', ['module' => $row->id]) . '"><i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i></a>';                                                        ;
                    $btn = $btn . '&nbsp;&nbsp;<a class="btn btn-icon  btn-danger delete-module delete-module" data-id="'.$row->id.'" data-toggle="tooltip" title="" data-original-title="Delete"> <i class="fa fa-trash-o"></i> </a>';
                    return $btn;
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('module.list', ['module' => new Module()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('module.create', ['module' => new Module()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModulePostRequest $request)
    {
        $request->validated();

        $module = Module::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_enable'=>1
        ]);

        $migrationName = "create_" . strtolower($request->name) . "_table";
        $modelClassName = Str::studly($request->name);
        // Create the migration
        Artisan::call('make:migration', [
            'name' => $migrationName,
            '--create' => $request->name,
        ]);

        $migrationFilePath = database_path("migrations") . "/" . date('Y_m_d_His') . "_$migrationName.php";
        File::put($migrationFilePath, $this->generateMigrationContent($request->name));

        // Create the model
        Artisan::call('make:model', [
            'name' => $modelClassName,
        ]);

        Artisan::call('migrate');

        if (!$module) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module.index');
        }
        $this->flashRepository->setFlashSession('alert-success', 'module created successfully.');
        return redirect()->route('module.index');
    }

     private function generateMigrationContent($tableName)
    {
        // Define the migration schema here based on $tableName
        $content = <<<EOT
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class Create{$tableName}Table extends Migration
        {
            public function up()
            {
                Schema::create('$tableName', function (Blueprint \$table) {
                    \$table->id();
                    \$table->timestamps();
                });
            }

            public function down()
            {
                Schema::dropIfExists('$tableName');
            }
        }
        EOT;

        return $content;
    }

    private function generateMigrationContentforRename($newTable, $oldTable)
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
                Schema::rename('$oldTable', '$newTable');
            }

            public function down()
            {
                Schema::rename('$newTable', '$oldTable');
            }
        };
        EOT;

        return $content;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        return view('module.create', ['module' => $module]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(ModulePostRequest $request, Module $module)
    {
        $request->validated();

        if ($module->name !== $request->name) {
            $migrationName = "rename_" . strtolower($module->name) . "_table";
            $modelClassName = Str::studly($request->name);
            // Create the migration
            Artisan::call('make:migration', [
                'name' => $migrationName,
                '--table' => $module->name,
            ]);

            $migrationFilePath = database_path("migrations") . "/" . date('Y_m_d_His') . "_$migrationName.php";
            File::put($migrationFilePath, $this->generateMigrationContentforRename($request->name, $module->name));
            // Create the model
            Artisan::call('make:model', [
                'name' => $modelClassName,
            ]);
            Artisan::call('migrate');

            $module->update(
                [
                    'name' => $request->name,
                    'description' => $request->description
                ]
            );
        }

        if (!$module) {
            $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
            return redirect()->route('module.index');
        }

        $this->flashRepository->setFlashSession('alert-success', 'Module updated successfully.');
        return redirect()->route('module.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        $module = module::find($module->id)->delete();
        if ($module) {
            return response()->json(['msg' => 'Module deleted successfully!'], 200);
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
}
