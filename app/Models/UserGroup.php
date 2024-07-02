<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class UserGroup extends Model
{
    use HasFactory, HasRoles;
    protected $guarded = [];
    protected $guard_name = 'web';

    public function users(){
        return $this->hasMany(User::class, 'ugroup_id' );
    }

    public function parent(){
        return $this->belongsTo( UserGroup::class, 'group_id' );
    }
}
