<?php

namespace App\Models;

use Eloquent as Model;

class Testimonials extends Model
{
    public $table = 'testimonials';

    public $fillable = [
        'store_view',
        'name',
        'description',
        'image'
    ];

   public static $rules = [
        'store_view' => 'required',
        'name' => 'required',
        'description' => 'required'
    ];

    public static $messages = [

    ];
}
