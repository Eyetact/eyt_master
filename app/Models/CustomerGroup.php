<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function customers(){
    //     return $this->hasMany(User::class, 'group_id' );
    // }

    public function customers()
    {
        return $this->belongsToMany(User::class,'u_c_groups','group_id','user_id');
    }


    public function parent(){
        return $this->belongsTo( CustomerGroup::class, 'group_id' );
    }
}
