<?php

namespace Database\Seeders;

use App\Models\Module;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $date = Carbon::now()->format('Y-m-d H:i:s');
        $module = [
            ['name' => 'User', 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'Role', 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'Permission', 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'Plan', 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'Subscription', 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'Attribute', 'created_at' => $date, 'updated_at' => $date],
        ];

        $permission = [
            ['name' => 'create.user', 'guard_name' => 'web', 'module' => 1, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.user', 'guard_name' => 'web', 'module' => 1, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.user', 'guard_name' => 'web', 'module' => 1, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.user', 'guard_name' => 'web', 'module' => 1, 'created_at' => $date, 'updated_at' => $date],

            ['name' => 'create.role', 'guard_name' => 'web', 'module' => 2, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.role', 'guard_name' => 'web', 'module' => 2, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.role', 'guard_name' => 'web', 'module' => 2, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.role', 'guard_name' => 'web', 'module' => 2, 'created_at' => $date, 'updated_at' => $date],

            ['name' => 'create.permission', 'guard_name' => 'web', 'module' => 3, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.permission', 'guard_name' => 'web', 'module' => 3, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.permission', 'guard_name' => 'web', 'module' => 3, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.permission', 'guard_name' => 'web', 'module' => 3, 'created_at' => $date, 'updated_at' => $date],

            ['name' => 'create.plan', 'guard_name' => 'web', 'module' => 4, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.plan', 'guard_name' => 'web', 'module' => 4, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.plan', 'guard_name' => 'web', 'module' => 4, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.plan', 'guard_name' => 'web', 'module' => 4, 'created_at' => $date, 'updated_at' => $date],

            ['name' => 'create.subscription', 'guard_name' => 'web', 'module' => 5, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.subscription', 'guard_name' => 'web', 'module' => 5, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.subscription', 'guard_name' => 'web', 'module' => 5, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.subscription', 'guard_name' => 'web', 'module' => 5, 'created_at' => $date, 'updated_at' => $date],

            ['name' => 'create.attribute', 'guard_name' => 'web', 'module' => 6, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'edit.attribute', 'guard_name' => 'web', 'module' => 6, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'delete.attribute', 'guard_name' => 'web', 'module' => 6, 'created_at' => $date, 'updated_at' => $date],
            ['name' => 'view.attribute', 'guard_name' => 'web', 'module' => 6, 'created_at' => $date, 'updated_at' => $date],
        ];
        Module::insert($module);
        Permission::insert($permission);
        $role = Role::where('name','super')->first();
        $role->givePermissionTo(Permission::all());
    }
}
