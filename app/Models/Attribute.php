<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function moduleObj(){
        return $this->belongsTo(Module::class, 'module');
    }

    public function multis(){
        return $this->hasMany(Multi::class);
    }

    public function activemultis()
    {
        return $this->hasMany(Multi::class)->where('type_of_calc',NULL)->orWhere('type_of_calc','two');
    }

}
