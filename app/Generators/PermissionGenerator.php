<?php

namespace App\Generators;

use App\Models\Module;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\Crud;
use App\Models\Attribute;

class PermissionGenerator
{
    /**
     * Generate new permissions to confg.permissions.permissions(used for peermissios seeder).
     *
     * @param array $request
     * @param int $id
     * @return void
     */
    public function generate(array $request,$id)
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $model = GeneratorUtils::setModelName($request['code'], 'default');
        $modelNameSingular = GeneratorUtils::cleanSingularLowerCase($model);
        try {
            //code...
            $this->insertRoleAndPermissions(strtolower($modelNameSingular),$id);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function regenerate($id)
    
    {
        $module = Module::find($id);
        $module->code = str()->snake(str_replace(['.','/','\\','-',' ','!','@','#','$','%','^','&','*','(',')','+','=','<','>',',','{','}','[',']',':',';','"','\''], '', str($module->code)->lower()));
        $module->save();
        $model = GeneratorUtils::setModelName($module->code, 'default');
        $modelNameSingular = GeneratorUtils::singularSnakeCase($model);
        try {
            //code...
            $this->insertRoleAndPermissions(strtolower($modelNameSingular),$id);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function generateForAttr(array $request,$id)
    {
        // $attr = GeneratorUtils::setModelName($request['code'], 'default');
        $attrNameSingular = GeneratorUtils::singularSnakeCase($request['code']);

        $this->insertRoleAndPermissionsForAttr(strtolower($attrNameSingular),$id);
    }

    /**
     * remove permissions from confg.permissions.permissions.
     *
     * @param $id $id
     *
     * @return void
     */
    public function remove($id)
    {

        $crud = Crud::find($id);
        $model = GeneratorUtils::setModelName($crud->name, 'default');
        $modelNameSingular = GeneratorUtils::cleanSingularLowerCase($model);
        $this->removeRoleAndPermissions($modelNameSingular);

        $path = config_path('permission.php');

        $newPermissionFile = str_replace($crud->permissions, '', file_get_contents($path));

        file_put_contents($path, $newPermissionFile);
    }

    /**
     * Insert new role & permissions then give an admin that permissions.
     *
     * @param array $request
     * @return void
     */
    protected function insertRoleAndPermissions(string $model,$id)
    {

        $role = Role::find(1);

        Permission::create(['name' => "view.$model" , 'module' => $id , 'guard_name' => 'web']);
        Permission::create(['name' => "create.$model", 'module' => $id , 'guard_name' => 'web']);
        Permission::create(['name' => "edit.$model", 'module' => $id , 'guard_name' => 'web']);
        Permission::create(['name' => "delete.$model", 'module' => $id , 'guard_name' => 'web']);

        $role->givePermissionTo([
            "view.$model",
            "create.$model",
            "edit.$model",
            "delete.$model"
        ]);

        $user = auth()->user();
        // $role2 = $user->getRoleNames()->first(); // Retrieve the first role assigned to the user


        // if ($role2 && $role2 !== $role->name) {
        //     $role2 = Role::where('name',$role2)->first();
        //     $role2->givePermissionTo([
        //         "view.$model",
        //         "create.$model",
        //         "edit.$model",
        //         "delete.$model"
        //     ]);
        // }

        $user->givePermissionTo([
            "view.$model",
            "create.$model",
            "edit.$model",
            "delete.$model"
        ]);


    }

    protected function insertRoleAndPermissionsForAttr(string $attr,$id)
    {

        $role = Role::find(1);
        $model_id=Attribute::find($id)->module;



        Permission::create(['name' => "view.$attr" ,'module' => $model_id,'attribute' => $id , 'guard_name' => 'web']);
        Permission::create(['name' => "edit.$attr", 'module' => $model_id,'attribute' => $id , 'guard_name' => 'web']);
        Permission::create(['name' => "delete.$attr", 'module' => $model_id,'attribute' => $id , 'guard_name' => 'web']);


        $role->givePermissionTo([
            "view.$attr",
            "edit.$attr",
            "delete.$attr"
        ]);

        $user = auth()->user();
        // $role2 = $user->getRoleNames()->first(); // Retrieve the first role assigned to the user


        // if ($role2 && $role2 !== $role->name) {
        //     $role2 = Role::where('name',$role2)->first();
        //     $role2->givePermissionTo([
        //         "view.$model",
        //         "create.$model",
        //         "edit.$model",
        //         "delete.$model"
        //     ]);
        // }

        $user->givePermissionTo([
            "view.$attr",
            // "create.$attr",
            "edit.$attr",
            "delete.$attr"
        ]);


    }

    /**
     * remove permissions from database and admin.
     *
     * @param string $model [explicite description]
     *
     * @return void
     */
    protected function removeRoleAndPermissions(string $model)
    {
        $role = Role::findByName('admin');

        $role->revokePermissionTo([
            "$model view",
            "$model create",
            "$model edit",
            "$model delete"
        ]);

        Permission::where('name' , "$model view")->first()->delete();
        Permission::where('name' , "$model create")->first()->delete();
        Permission::where('name' , "$model edit")->first()->delete();
        Permission::where('name' , "$model delete")->first()->delete();


    }
}
