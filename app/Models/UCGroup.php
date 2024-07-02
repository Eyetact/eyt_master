<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UCGroup extends Model
{
    use HasFactory;

    public function customergroup(){
        return $this->belongsTo( CustomerGroup::class, 'group_id' );
    }

    public function usergroup(){
        return $this->belongsTo( UserGroup::class, 'group_id' );
    }
}
