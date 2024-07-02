<?php

use App\Exports\UsersExport;
use App\Generators\GeneratorUtils;
use App\Helpers\Helper;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DataController;
use App\Imports\UsersImport;
use App\Models\Admin\Element;
use App\Models\Admin\Unit;
use App\Models\Attribute;
use App\Models\Module;
use App\Models\UCGroup;
use App\Models\Admin\Category;
use App\Models\Admin\Component;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\MailboxController;
use App\Http\Controllers\MenuManagerController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleManagerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\MailsController;
use App\Http\Controllers\Admin\SoftwareController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Maatwebsite\Excel\Facades\Excel;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
// Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/dashboard', function () {
        return view('index');
    });
    Route::get('/', function () {
        return view('index');
    });

    Route::controller(ModuleController::class)->group(function () {
        Route::get('/module', 'index')->name('module.index');
        Route::get('module/create', 'create')->name('module.create');
        Route::post('module/store', 'store')->name('module.store');
        Route::get('module/{module}/edit', 'edit')->name('module.edit');
        Route::post('module/{module}', 'update')->name('module.update');
        Route::get('remove_module/{module}', 'destroy')->name('module.destroy');
        Route::post('/module/{moduleId}/updateStatus', 'updateStatus')->name('module.updateStatus');
    });

    Route::controller(AttributeController::class)->group(function () {
        Route::get('/attribute', 'index')->name('attribute.index');
        Route::get('attribute/create', 'create')->name('attribute.create');
        Route::post('attribute/store', 'store')->name('attribute.store');
        Route::get('attribute/{attribute}/edit', 'edit')->name('attribute.edit');
        Route::post('attribute/{attribute}', 'update')->name('attribute.update');
        Route::get('remove_attribute/{attribute}', 'destroy')->name('attribute.destroy');
        Route::post('/attribute/{attributeId}/updateStatus', 'updateStatus')->name('attribute.updateStatus');
        Route::get('/attribute-by-module/{module}', 'getAttrByModel')->name('attribute.get');
        Route::get('/attribute-by-module2/{module}', 'getAttrByModel2')->name('attribute.get');
        Route::get('/data-by-module/{model_id}/{attr_condition}', 'getDataByModel')->name('data.get');
    });

    Route::controller(MenuManagerController::class)->group(function () {
        Route::get('/menu', 'index')->name('menu.index');
        Route::get('menu/create', 'create')->name('menu.create');
        Route::post('menu/store', 'store')->name('menu.store');
        Route::post('menu/menu_update', 'menu_update')->name('menu.menu_update');
        Route::get('menu/{menu}/edit', 'edit')->name('menu.edit');
        Route::post('menu/{menu}', 'update')->name('menu.update');
        Route::delete('menu/{menu}', 'destroy')->name('menu.destroy');
    });

    Route::prefix('profile')->group(function () {
        Route::get('{id?}', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('update/{id?}', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('change-password/{id?}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('profile-upload/{id?}', [ProfileController::class, 'uploadProfileImage'])->name('profile.upload-image');
    });

    Route::get('example-datatable', function () {
        return view('example_datatable.index');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/setting', 'index')->name('setting.index');
        //        Route::get('/setting/countries', 'countries')->name('setting.countries')->middleware('permission:countries.setting');
        //        Route::get('/setting/status/{id}/{status}', 'settingCountry')->name('setting.status')->middleware('permission:countries.status');
        //        Route::get('/setting/states', 'states')->name('setting.states')->middleware('permission:states.setting');
        //        Route::get('/setting/cities', 'cities')->name('setting.cities')->middleware('permission:cities.setting');
        Route::post('/setting', 'store')->name('setting.store');
        Route::post('/storeUrl', 'storeUrl')->name('setting.store.url');
    });

    Route::controller(SmtpController::class)->group(function () {
        Route::get('/smtp', 'index')->name('smtp.index');
        Route::get('smtp/create', 'create')->name('smtp.create');
        Route::post('smtp/store', 'store')->name('smtp.store');
        Route::get('smtp/{smtp}/edit', 'edit')->name('smtp.edit');
        Route::post('smtp/{smtp}', 'update')->name('smtp.update');
        Route::delete('smtp/{smtp}', 'destroy')->name('smtp.destroy');
    });

    Route::controller(MailboxController::class)->group(function () {
        Route::get('/main_mailbox', 'index')->name('main_mailbox.index');
        Route::get('main_mailbox/create', 'create')->name('main_mailbox.create');
        Route::post('main_mailbox/store', 'store')->name('main_mailbox.store');
        Route::get('main_mailbox/{main_mailbox}/edit', 'edit')->name('main_mailbox.edit');
        Route::post('main_mailbox/{main_mailbox}', 'update')->name('main_mailbox.update');
        Route::delete('main_mailbox/{main_mailbox}', 'destroy')->name('main_mailbox.destroy');
    });

    Route::controller(MailsController::class)->group(function () {
        Route::get('/all_mails', 'index')->name('all_mails');
        Route::get('/mails/{id}', 'mails')->name('inbox');
        Route::get('/fetch/{id}/{mail_id}', 'getDataByUID')->name('fetch');
        Route::post('/sendReply/{id?}', 'sendReply')->name('sendReply');
    });

    Route::post('theme-setting/update', [Helper::class, 'update'])->name('update.theme');

    Route::controller(ModuleManagerController::class)->group(function () {
        Route::get('/module_manager', 'index')->name('module_manager.index');
        Route::get('module_manager/create', 'create')->name('module_manager.create');
        Route::post('module_manager/store', 'store')->name('module_manager.store');
        Route::post('module_manager/storeFront', 'storeFront')->name('module_manager.storeFront');
        Route::post('module_manager/storeLabel', 'storeLabel')->name('module_manager.storelabel');
        Route::post('module_manager/menu_update', 'menu_update')->name('module_manager.menu_update');
        Route::get('module_manager/{module}/edit', 'edit')->name('module_manager.edit');
        // Route::post('module_manager/{menu}', 'update')->name('module_manager.update');
        Route::post('module_manager/update/{module}', 'update')->name('module_manager.update');
        Route::delete('module_manager/{menu}', 'destroy')->name('module_manager.destroy');
        Route::post('module_manager/switch-delete', 'deleteORRestore')->name('module_manager.deleteORRestore');
        Route::get('force-delete/{id}', 'forceDelete')->name('force-delete');

        // update is deleted menu item
        Route::post('module_manager/menu_delete', 'menuDelete')->name('module_manager.menu_delete');

        Route::get('add-sub/{id}', 'addSub')->name('module_manager.addSub');
        Route::post('add-sub/{id}', 'storeSub')->name('module_manager.storSub');

        Route::post('add-sub-post', 'storeSubPost')->name('module_manager.storSubPost');
    });
    Route::get('/myadmins/{user_id}', [UserController::class, 'myAdmins'])->name('users.myadmins');

    //orders
    Route::get('/myorders', [UserController::class, 'myOrders'])->name('users.myorders');

    Route::get('/vendors', [UserController::class, 'vendors'])->name('users.vendors');
    Route::get('/admins', [UserController::class, 'admins'])->name('users.admins');
    Route::get('/public-vendors', [UserController::class, 'publicVendors'])->name('users.pvendors');
    Route::get('/users', [UserController::class, 'users'])->name('users.users');

    Route::post('add-user', [UserController::class, 'store'])->name('users.store');

    Route::get('add-user', [UserController::class, 'create'])->name('users.create');
    Route::get('add-vendor', [UserController::class, 'createvendor'])->name('vendor.create');
    Route::get('add-public-vendor', [UserController::class, 'createPublicVendor'])->name('pvendor.create');
    Route::get('add-admin', [UserController::class, 'createAdmin'])->name('admin.create');

    Route::get('add-admin', [UserController::class, 'createAdmin'])->name('admin.create');

    Route::get('/user/{id}', [UserController::class, 'show'])->name('users.view');

    Route::post('/user/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::controller(RoleController::class)
        ->prefix('role')
        ->group(function () {
            Route::get('/', 'index')->name('role.index');
            Route::get('create', 'create')->name('role.create')->middleware('can:create.role');
            Route::post('store', 'store')->name('role.store')->middleware('can:create.role');
            Route::get('{role}/edit', 'edit')->name('role.edit')->middleware('can:edit.role');
            Route::post('{role}', 'update')->name('role.update')->middleware('can:edit.role');
            Route::post('delete/{role}', 'destroy')->name('role.destroy')->middleware('can:delete.role');
            Route::get('permission', 'assignPermissionList')->name('role.permission.index');
        });

    Route::controller(PermissionController::class)
        ->prefix('permission')
        ->group(function () {
            Route::get('/', 'index')->name('permission.index')->middleware('can:view.permission');
            Route::get('create', 'create')->name('permission.create')->middleware('can:create.permission');
            Route::post('store', 'store')->name('permission.store')->middleware('can:create.permission');
            Route::get('{permission}/edit', 'edit')->name('permission.edit')->middleware('can:edit.permission');
            Route::post('update', 'update')->name('permission.update')->middleware('can:edit.can');
            Route::delete('{permission}', 'destroy')->name('permission.destroy')->middleware('can:delete.permission');

            Route::post('permission/delete/{id}', 'deleteSinglePermission')->name('permission.delete')->middleware('can:delete.permission');

            Route::post('module/store', 'moduleStore')->name('permission.module');
        });

    //plan
    Route::get('/plans', [planController::class, 'index'])->name('plans.index');

    Route::get('add-plan', [planController::class, 'create'])->name('plans.create');
    Route::post('add-plan', [planController::class, 'store'])->name('plans.store');

    Route::get('/plan/{id}', [planController::class, 'show'])->name('plans.view');
    Route::post('update-plan/{id}', [planController::class, 'update'])->name('plans.update');

    Route::post('/plan/delete/{id}', [planController::class, 'destroy'])->name('plans.destroy');

    //subscription
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

    Route::get('add-subscription', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('add-subscription', [SubscriptionController::class, 'store'])->name('subscriptions.store');

    Route::get('/subscription/{id}', [SubscriptionController::class, 'show'])->name('subscriptions.view');
    Route::post('update-subscription/{id}', [SubscriptionController::class, 'update'])->name('subscriptions.update');

    Route::post('/subscription/delete/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    //customer group
    Route::get('/groups', [CustomerGroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/sub/{id}', [CustomerGroupController::class, 'sub'])->name('groups.sub');

    Route::get('add-group', [CustomerGroupController::class, 'create'])->name('groups.create');
    Route::post('add-group', [CustomerGroupController::class, 'store'])->name('groups.store');

    Route::get('/group/{id}', [CustomerGroupController::class, 'show'])->name('groups.view');
    Route::get('/showCustomer/{id}', [CustomerGroupController::class, 'showCustomer'])->name('groups.view2');
    Route::post('update-group/{id}', [CustomerGroupController::class, 'update'])->name('groups.update');

    Route::post('/group/delete/{id}', [CustomerGroupController::class, 'destroy'])->name('groups.destroy');

    //user group
    Route::get('/user-groups', [UserGroupController::class, 'index'])->name('ugroups.index');
    Route::get('/user-groups/sub/{id}', [UserGroupController::class, 'sub'])->name('ugroups.sub');

    Route::get('add-user-group', [UserGroupController::class, 'create'])->name('ugroups.create');
    Route::post('add-user-group', [UserGroupController::class, 'store'])->name('ugroups.store');

    Route::get('/user/group/{id}', [UserGroupController::class, 'show'])->name('ugroups.view');
    Route::get('/user/group/users/{id}', [UserGroupController::class, 'showUsers'])->name('ugroups.view2');
    Route::post('update-user-group/{id}', [UserGroupController::class, 'update'])->name('ugroups.update');

    Route::post('/user-group/delete/{id}', [UserGroupController::class, 'destroy'])->name('ugroups.destroy');

    Route::get('/filesmanager', [FileManagerController::class, 'index'])->name('files');
    Route::post('/filesmanager', [FileManagerController::class, 'newFile'])->name('files');
    Route::get('/new-folder', [FileManagerController::class, 'newFolder'])->name('newfolder');
    Route::post('/new-folder', [FileManagerController::class, 'newFolder'])->name('newfolder');

    Route::get('/new-file', [FileManagerController::class, 'newFile'])->name('newfile');
    Route::post('/new-file', [FileManagerController::class, 'newFile'])->name('newfile');

    Route::get('/view-folder/{id}', [FileManagerController::class, 'viewfolder'])->name('viewfolder');

    Route::get('/show-folder/{id}', [FileManagerController::class, 'showFolder'])->name('showfolder');
    Route::post('update-folder/{id}', [FileManagerController::class, 'updateFolder'])->name('folder.update');

    Route::post('/folder/delete/{id}', [FileManagerController::class, 'destroyFolder'])->name('folder.destroy');

    Route::get('/show-file/{id}', [FileManagerController::class, 'showFile'])->name('showfile');
    Route::post('update-file/{id}', [FileManagerController::class, 'updateFile'])->name('file.update');

    Route::post('/file/delete/{id}', [FileManagerController::class, 'destroyFile'])->name('file.destroy');

    Route::get('/file/download/{id}', [FileManagerController::class, 'downloadFile'])->name('downloadfile');

    Route::get('/file/share/{id}', [FileManagerController::class, 'shareFile'])->name('sharefile');

    Route::get('/file/images/{id}', [FileManagerController::class, 'images'])->name('images');
    Route::get('/file/videos/{id}', [FileManagerController::class, 'videos'])->name('videos');
    Route::get('/file/docs/{id}', [FileManagerController::class, 'docs'])->name('docs');
    Route::get('/file/music/{id}', [FileManagerController::class, 'music'])->name('music');
    Route::get('/file/search/{key}', [FileManagerController::class, 'search'])->name('search');

    Route::get('/file/open/{id}', [FileManagerController::class, 'openFile'])->name('open.file');

    Route::get('/file/open/{id}', [FileManagerController::class, 'openFile'])->name('open.file');

    Route::get('testview', function () {
        return view('test');
    });
});


include_once base_path('routes/generator/generator.php');

Route::get('clear', function () {
    Artisan::call('optimize:clear');
    echo 'done';

});

Route::get('reg-perm', function () {});

Route::get('/get-mixtures/{id}', function ($id) {
    $componentSetId = $id;

    if ($componentSetId) {
        $mixtures = App\Models\Admin\Mixture::where('components_set_id', $componentSetId)->get();
    } else {
        $mixtures = [];
    }

    return response()->json($mixtures);
})->name('get-mixtures');

Route::get('/get-categories/{id}', function ($id) {
    try {
        $componentSetId = $id;

        if ($componentSetId) {
            $components_set = App\Models\Admin\ComponentsSet::find($componentSetId);
            $categories = collect([]);

            if ($components_set) {
                $set_component = json_decode($components_set->set_component);
                $categoryIds = collect($set_component)->pluck('id');

                foreach ($categoryIds as $categoryId) {
                    $component = App\Models\Admin\Component::find($categoryId);
                    $compo_category = json_decode($component->compo_category, true);
                    $compo_category_collection = collect($compo_category)->pluck('id');

                    foreach ($compo_category_collection as $categoryId) {
                        $category = App\Models\Admin\Category::find($categoryId);
                        if ($category && !$categories->contains('id', $category->id)) {
                            $categoryName = $category->classification->class_child ?? '';
                            $category->categoryName = $categoryName;
                            $categories->push($category);
                        }
                    }
                }
            }
        } else {
            $categories = collect([]);
        }

        return response()->json($categories);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('get-categories');

Route::get('/get-components/{id}', function ($id) {
    $componentSetId = $id;

    if ($componentSetId) {
        $components_set = App\Models\Admin\ComponentsSet::find($componentSetId);

        $components = collect([]);

        if ($components_set) {
            $set_component = json_decode($components_set->set_component);
            $componentIds = collect($set_component)->pluck('id');

            foreach ($componentIds as $componentId) {
                $component = App\Models\Admin\Component::find($componentId);
                if ($component) {
                    $components->push($component);
                }
            }
        }
    } else {
        $components = [];
    }

    return response()->json($components);
})->name('get-components');

Route::get('/get-compononets-by-main/{id}', function ($id) {
    $mainPartId = $id;

    if ($mainPartId) {
        $components = App\Models\Admin\Component::where('main_part_id', $mainPartId)->with('main_part')->get();

        $components = $components->map(function ($component) {
            $component->inlet = $component->main_part->main_inlet;
            $component->type = $component->main_part->main_type;

            return $component;
        });
    } else {
        $components = [];
    }

    return response()->json($components);
})->name('get-compos');

Route::get('/get-components-by-category/{category_id}/{cset_id}', function ($category_id, $cset_id) {
    try {
        $componentSetId = $cset_id;
        $catId = $category_id;
        $components = collect([]);

        if ($componentSetId) {
            $components_set = App\Models\Admin\ComponentsSet::find($componentSetId);

            if ($components_set) {
                $set_component = json_decode($components_set->set_component);
                $componentsId = collect($set_component)->pluck('id');

                foreach ($componentsId as $componentId) {
                    $component = App\Models\Admin\Component::find($componentId);
                    $compo_category = json_decode($component->compo_category, true);
                    $compo_category_collection = collect($compo_category)->pluck('id');

                    if ($compo_category_collection->contains($catId)) {
                        $components->push($component);
                    }
                }
            }
        }

        return response()->json($components);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('get-components-by-category');

Route::get('/get-max-min/{component_id}/{category_id}', function ($component_id, $category_id) {
    $componentId = $component_id;
    $categoryId = $category_id;

    if ($categoryId) {
        $category_values = App\Models\Admin\Component::where('id', $componentId)->first();

        $compo_category = json_decode($category_values->compo_category, true);
        // dd( $compo_category);

        $compo_category = array_combine(array_map('intval', array_keys($compo_category)), array_values($compo_category));

        foreach ($compo_category as $item) {
            if (isset($item['id']) && $item['id'] == $categoryId) {
                $minimum = $item['minimum'];
                $maximum = $item['maximum'];
                $default = $item['default'];

                return response()->json([
                    'minimum' => $minimum,
                    'maximum' => $maximum,
                    'default' => $default,
                ]);
            }
        }
    }

    return response()->json([]);
})->name('get-mixman');

Route::get('/get-unit-by-component/{id}/{cset_id}', function ($id, $cset_id) {
    $componentId = $id;
    $componentSetId = $cset_id;

    if ($componentId) {
        $component = App\Models\Admin\Component::with('unit')->find($componentId);
        $component_set = App\Models\Admin\ComponentsSet::with('main_part')->find($componentSetId);

        $componentData = [
            'unit' => $component->unit->unit_code,
            'concentration' => $component->compo_concentration,
            'type' => $component_set->main_part->main_type,
        ];
    }

    return response()->json($componentData);
})->name('get-unit');

Route::get('/get-type-by-component/{id}', function ($id) {
    $componentSetId = $id;

    if ($componentSetId) {
        $component_set = App\Models\Admin\ComponentsSet::with('main_part')->find($componentSetId);

        $componentData = [
            'type' => $component_set->main_part->main_type,
        ];
    }

    return response()->json($componentData);
})->name('get-type');

// Route::get('/get-components-by-category/{category_id}/{cset_id}', function ($category_id,$cset_id) {
//     try {
//         $componentSetId = $cset_id;
//         $catId = $category_id;

//         if ($componentSetId) {
//             $components_set = App\Models\Admin\ComponentsSet::find($componentSetId);
//             $categories = collect([]);

//             if ($components_set) {
//                 $set_component = json_decode($components_set->set_component);
//                 $componentsId = collect($set_component)->pluck('id');

//                 foreach ($componentsId as $componentId) {
//                     $component = App\Models\Admin\Component::find($componentId);
//                     $compo_category = json_decode($component->compo_category, true);
//                     $compo_category_collection = collect($compo_category)->pluck('id');

//                     foreach ($compo_category_collection as $categoryId) {
//                         $category = App\Models\Admin\Category::find($categoryId);
//                         if ($category && !$categories->contains('id', $category->id)) {
//                             $categoryName = $category->classification->class_child ?? '';
//                             $category->categoryName = $categoryName;
//                             $categories->push($category);
//                         }
//                     }
//                 }
//             }
//         } else {
//             $categories = collect([]);
//         }

//         return response()->json($categories);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// })->name('get-categories');

// Route::get('/get-compononets-by-main/{id}', function ($id) {
//     $mainPartId = $id;

//     if ($mainPartId) {
//         $components = App\Models\Admin\Component::where('main_part_id', $mainPartId)->get();
//     } else {
//         $components = [];
//     }

//     return response()->json($components);
// })->name('get-compos');

Route::get('searchtargetfromsource/{main_model}/{main_model_id}/{for_key_attr_name}/{target_result_attr}', function ($main_model, $main_model_id, $for_key_attr_name, $target_result_attr) {
    //main model
    $main_model = 'App\Models\Admin\\' . GeneratorUtils::setModelName($main_model);
    $element = $main_model::find($main_model_id);

    $target_model = explode('_', $for_key_attr_name);
    $id = $for_key_attr_name;
    $target_model = 'App\Models\Admin\\' . GeneratorUtils::setModelName($target_model[0]);
    // dd($target_model);

    $target_data = $target_model::find($element->$for_key_attr_name);
    $target_data->$target_result_attr;
    return response()->json(
        [
            'data' => $target_data->$target_result_attr,
            'data_id' => $target_data->id,
            'id' => $id,
        ],
        200,
    );
});


Route::get('get-belongs-to/{id}', function ($id) {
    // $attributes = Attribute::where('module', $id)->where('type', 'foreignId')->get();

    $attributes = Attribute::where('module', $id)
        ->where(function ($query) {

            $query->where('type', 'foreignId')->orWhere('type', 'informatic')->orWhere('type', 'doublefk')->orWhere('primary', 'lookup')->orWhere('type', 'fk');
        })


        ->get();

    $options = '';
    $options = '<option  >-- select --</option>';


    foreach ($attributes as $key => $value) {
        $all = GeneratorUtils::setModelName(explode('_', $value->code)[0]);
        $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))?->first();

        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code) . '" >' . $model->name . '</option>';
    }

    return $options;
});

Route::get('get-relations-modules/{id}', function ($id) {
    // $attributes = Attribute::where('module', $id)->where('type', 'foreignId')->get();




    //this is for bt

    // $attributes = Attribute::where('module', $id)
    // ->where(function ($query) {
    //     $query->where('type', 'foreignId')
    //         ->orWhere('type', 'informatic')
    //         ->orWhere('type', 'doublefk')
    //         ->orWhere('primary', 'lookup')
    //         ->orWhere('type', 'fk');
    // })

    // ->get();

    // $basedAttributes = Attribute::where('module', $id)
    // ->where(function ($query) {
    //     $query->where('type', 'fk')
    //           ->where('fk_type','based');

    // })

    // ->get();




    //this is for hm
    $module =  Module::find($id);

    $code = GeneratorUtils::singularSnakeCase($module->code);

    $attributes2 = Attribute::where('constrain', $code)
        ->orWhere('constrain2', $code)
        ->where(function ($query) {

            $query->where('type', 'foreignId')->orWhere('type', 'informatic')->orWhere('type', 'doublefk')->orWhere('primary', 'lookup')->orWhere('type', 'fk');
        })


        ->get();

    $options = '';
    $options = '<option  >-- select --</option>';

    //    foreach ($attributes as $key => $value) {

    //     $all =  GeneratorUtils::setModelName( explode('_', $value->code)[0] );
    //     $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))
    //     ->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))
    //     ?->first();

    //        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    //    }


    foreach ($attributes2 as $key => $value) {
        $model = Module::find($value->module);

        $options .= '<option data-id="' . $value->module . '" value="' . GeneratorUtils::singularSnakeCase($model->code) . '" >' . $model->name . '</option>';
    }


    //    foreach ($basedAttributes as $key => $value) {

    //     $all =  GeneratorUtils::setModelName($value->constrain2 );
    //     $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))
    //     ->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))
    //     ?->first();

    //        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    //    }

    return $options;
});

Route::get('get-relations-multi/{id}', function ($id) {
    // $attributes = Attribute::where('module', $id)->where('type', 'foreignId')->get();





    $attributes = Attribute::where('module', $id)
        ->where(function ($query) {
            $query->where('type', 'foreignId')->orWhere('type', 'informatic')->orWhere('type', 'doublefk')->orWhere('primary', 'lookup')->orWhere('type', 'fk');
        })


        ->get();

    // $basedAttributes = Attribute::where('module', $id)
    // ->where(function ($query) {
    //     $query->where('type', 'fk')
    //           ->where('fk_type','based');

    // })

    // ->get();




    //this is for hm
    $module =  Module::find($id);

    $code = GeneratorUtils::singularSnakeCase($module->code);

    $attributes2 = Attribute::where('constrain', $code)
        ->orWhere('constrain2', $code)
        ->where(function ($query) {

            $query->where('type', 'foreignId')->orWhere('type', 'informatic')->orWhere('type', 'doublefk')->orWhere('primary', 'lookup')->orWhere('type', 'fk');
        })


        ->get();

    $options = '';


    foreach ($attributes as $key => $value) {
        $all = GeneratorUtils::setModelName(explode('_', $value->code)[0]);
        $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))?->first();


        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code) . '" >' . $model->name . '</option>';
    }

    foreach ($attributes2 as $key => $value) {
        $model = Module::find($value->module);


        $options .= '<option data-id="' . $value->module . '" value="' . GeneratorUtils::singularSnakeCase($model->code) . '" >' . $model->name . '</option>';
    }


    //    foreach ($basedAttributes as $key => $value) {

    //     $all =  GeneratorUtils::setModelName($value->constrain2 );
    //     $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))
    //     ->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))
    //     ?->first();

    //        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    //    }

    return $options;
});


Route::get('get-belongs-to-multi/{id}', function ($id) {

    // $attributes = Attribute::where('module', $id)->where('type', 'foreignId')->get();



    //this is for bt
    $attributes = Attribute::where('module', $id)
        ->where(function ($query) {
            $query->where('type', 'foreignId')
                ->orWhere('type', 'informatic')
                ->orWhere('type', 'doublefk')
                ->orWhere('primary', 'lookup')
                ->orWhere('type', 'fk');
        })

        ->get();


    // $basedAttributes = Attribute::where('module', $id)
    // ->where(function ($query) {
    //     $query->where('type', 'fk')
    //           ->where('fk_type','based');

    // })

    // ->get();



    //this is for hm
    // $module =  Module::find($id);
    // $code = GeneratorUtils::singularSnakeCase($module->code);

    // $attributes2 = Attribute::where('constrain', $code)
    //                         ->orWhere('constrain2',$code)
    //                         ->where(function ($query) {
    //                         $query->where('type', 'foreignId')
    //                         ->orWhere('type', 'informatic')
    //                         ->orWhere('type', 'doublefk')
    //                         ->orWhere('primary', 'lookup')
    //                         ->orWhere('type', 'fk');
    //                       })

    //                        ->get();


    $options = '';




    foreach ($attributes as $key => $value) {

        $all =  GeneratorUtils::setModelName(explode('_', $value->code)[0]);
        $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))
            ->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))
            ?->first();


        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    }



    //    foreach ($attributes2 as $key => $value) {


    //     $model = Module::find($value->module);



    //        $options .= '<option data-id="' . $value->module . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    //    }


    //    foreach ($basedAttributes as $key => $value) {

    //     $all =  GeneratorUtils::setModelName($value->constrain2 );
    //     $model = Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase($all))
    //     ->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase($all))
    //     ?->first();


    //        $options .= '<option data-id="' . $model->id . '" value="' . GeneratorUtils::singularSnakeCase($model->code)  . '" >' . $model->name . '</option>';
    //    }



    return $options;
});

Route::get('getsource/{id}', function ($id) {
    $attributes = Attribute::where('module', $id)->where('type', 'foreignId')->get();

    $options = '<option>-- select --</option>';

    foreach ($attributes as $key => $value) {
        $options .= '<option value="' . explode('_', $value->code)[0] . '" >' . $value->name . '</option>';
    }
    return $options;
});
Route::get('gettarget/{code}', function ($code) {
    $main_model = Module::where('code', $code)->first();

    $attributes = Attribute::where('module', $main_model->id)
        ->where('type', 'foreignId')
        ->get();

    $options = '<option>-- select --</option>';

    foreach ($attributes as $key => $value) {
        $options .= '<option  value="' . $value->code . '" >' . $value->name . '</option>';
    }

    return $options;
});

Route::post('assign-record/{model}', function (Request $request, $model) {
    foreach ($request->ids as $id) {
        $fullClass = 'App\Models\Admin\\' . GeneratorUtils::setModelName($model);
        $record = $fullClass::find($id);

        $newRecord = $record->replicate();
        $newRecord->assign_id = auth()->user()->id;
        $newRecord->user_id = null;
        $newRecord->save();
    }
    return redirect()->back();
})->name('assign-record');

Route::resource('data', DataController::class);



Route::post('assign-cgroup', function (Request $request) {



    $userIds = explode(',', $request->user_ids);

    $currentTimestamp = now();

    foreach ($userIds as $userid) {

        $record = User::find($userid);

        if ($record) {
            foreach ($request->ids as $id) {


                $ifExist = UCGroup::where('user_id', $record->id)->where('group_id', $id)->first();

                if (!$ifExist) {

                    $c = new UCGroup();
                    $c->group_id = $id;
                    $c->user_id = $record->id;
                    $c->created_at = $currentTimestamp;
                    $c->updated_at = $currentTimestamp;
                    $c->save();
                }
            }
        }
    }
    return redirect()->back()->with('success', 'Groups assigned successfully.');
})->name('assign-cgroup');




Route::resource('data', DataController::class);

Route::post('export-template', function (Request $request) {


    return (new UsersExport($request->module,true))->download('template.xlsx');

})->name('export-template');


Route::post('export-data', function (Request $request) {
    return (new UsersExport($request->module))->download('data.xlsx');
})->name('export-data');

Route::post('import-data', function (Request $request) {

    $file = $request->file;
    Excel::import(new UsersImport($request->module), $file);

    return redirect('/')->with('success', 'All good!');
})->name('import-data');

Route::resource('categories', App\Http\Controllers\CategoryController::class);
Route::resource('store_view', App\Http\Controllers\StoreViewController::class);
Route::resource('pages', App\Http\Controllers\PagesController::class);
Route::resource('sliders', App\Http\Controllers\SlidersController::class);
Route::resource('testimonials', App\Http\Controllers\TestimonialsController::class);
