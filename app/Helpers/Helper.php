<?php

namespace App\Helpers;

use App\Models\MenuManager;
use App\Models\Permission;
use App\Models\ThemeSetting;
use App\Models\Module;
use App\Models\UCGroup;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class Helper
{
    public static function update(Request $request)
    {
        $classes = $request->classes;
        $themeSetting = ThemeSetting::where('user_id', Auth::id())->first();
        if (!filled($themeSetting)) {
            $themeSetting = new ThemeSetting();
        }
        $classes = Str::replace('app', '', $classes);
        $classes = Str::replace('sidebar-mini', '', $classes);
        $themeSetting->user_id = Auth::id();
        $themeSetting->theme_classes = $classes;
        $themeSetting->save();
        return true;
    }

    public static function getThemeClasses()
    {
        $themeSetting = ThemeSetting::where('user_id', Auth::id())->first();
        if (filled($themeSetting)) {
            return $themeSetting->theme_classes;
            // Use $themeClasses as needed
        }
    }

    // public static function getMenu($type) {
    //     $data=MenuManager::with('children.children.children')->where('parent','0')->where('menu_type',$type)->orderBy('id','asc')->get();
    //     return $data;
    // }

    // public static function getMenu($type)
    // {



        // if (auth()->user()->access_table == "Group") {
        //     $group_ids = auth()->user()->groups()->pluck('group_id');

        //     $userids= UCGroup::whereIn('group_id', $group_ids)
        //     ->pluck('user_id');

        //     $module_ids = Module::whereIn('user_id', $userids)
        //         ->pluck('id');
        //         // dd($module_ids);

        //     $data = MenuManager::with('children.children.children')
        //         ->where('parent', '0')
        //         ->where('menu_type', $type)
        //         ->whereIn('module_id', $module_ids)
        //         ->orderBy('sequence', 'asc')
        //         ->get();

        //     return $data;
        // }

        // if (auth()->user()->access_table == "Individual") {

        //     $module_ids = Module::where('user_id', auth()->user()->id)
        //         ->pluck('id');

        //     $data = MenuManager::with('children.children.children')
        //         ->where('parent', '0')
        //         ->where('menu_type', $type)
        //         ->whereIn('module_id', $module_ids) // Filter by module_id
        //         ->orderBy('sequence', 'asc')
        //         ->get();

        //     return $data;

        // }

        //global

    //     $data = MenuManager::with('children.children.children')->where('parent', '0')->where('menu_type', $type)->orderBy('sequence', 'asc')->get();
    //     return $data;
    // }


    public static function getMenu($type)
    {

        // $super= Auth::user()->hasRole('super');
        // if($super)
        // {

        //    $data = MenuManager::with('children.children.children')->where('parent', '0')->where('menu_type', $type)->orderBy('sequence', 'asc')->get();
        //    return $data;
        // }


        $module_ids = Module::where('user_id', auth()->user()->id)
                ->pluck('id');

            $data = MenuManager::with('children.children.children')
                ->where('parent', '0')
                ->where('menu_type', $type)
                ->whereIn('module_id', $module_ids) // Filter by module_id
                ->orderBy('sequence', 'asc')
                ->get();

            return $data;

    }

    public static function canWithCount($name, $created_at)
    {

        $super= Auth::user()->hasRole('super');
        if($super)
        {
            return true;
        }


        $role = Role::where('name', Auth::user()->getRoleNames()->first())->first();
        $permission = Permission::where('name', $name)->first();
        $count = $permission->getCountByrole($role->id); // number
        $count_type = $permission->getCountByrole($role->id, 1); // days or week etc



        if ($count == 0) {
            return true;
        }

        switch ($count_type) {
            case 'day':
                $date = Carbon::parse($created_at);
                $now = Carbon::now();
                $diff = $date->diffInDays($now);
                // return $created_at;
                if ($diff < $count) {
                    return true;
                }
                break;

            case 'week':
                $date = Carbon::parse($created_at);
                $now = Carbon::now();
                $diff = $date->diffInWeeks($now);
                // echo $diff;
                if ($diff < $count) {
                    return true;
                }
                break;

            case 'month':
                $date = Carbon::parse($created_at);
                $now = Carbon::now();
                $diff = $date->diffInMonths($now);
                // echo $diff;
                if ($diff < $count) {
                    return true;
                }
                break;
            case 'year':
                $date = Carbon::parse($created_at);
                $now = Carbon::now();
                $diff = $date->diffInYears($now);
                // echo $diff;
                if ($diff < $count) {
                    return true;
                }
                break;

            default:
                # code...
                break;
        }

        return false;

    }


    public static function canForSpecificUser($name)
    {

        $super= Auth::user()->hasRole('super');
        if($super)
        {
            return true;
        }



        $permission = Permission::where('name', $name)->first();
        $per_id=$permission->id;
        $exist= DB::table('permission_has_models')
                   ->where('permission_id', $per_id)
                   ->where('model_type', "App\Models\User")
                   ->where('model_id', auth()->user()->id)
                   ->first();

           if($exist)
           {
            return true;
           }

           return false;

    }

}


