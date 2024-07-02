<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
