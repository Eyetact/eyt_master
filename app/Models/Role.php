<?php
namespace App\Models;

use App\Models\RoleSchedulerSetting;
use Spatie\Permission\Models\Role as Model;

class Role extends Model{
    protected $appends = array('permission_data');

    public function getPermissionDataAttribute()
    {
        return $this->permissions->pluck('id', 'id');
    }

    public function scheduler() {
        return $this->hasMany(RoleSchedulerSetting::class,'role_id','id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
