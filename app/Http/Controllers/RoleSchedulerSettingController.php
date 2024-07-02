<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleSchedulerSettingController extends Controller
{
    protected $fillable = [
        'user_id',
        'role_id',
        'permission_id',
        'module_id',
        'scheduler_no',
        'type',
        'access_action_date_time',
        'model_access_action_permission',
        'status'
    ];

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
}
