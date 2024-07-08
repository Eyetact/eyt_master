<?php

namespace App\Models;

use Eloquent as Model;

class Sliders extends Model
{
    public $table = 'sliders';

    public $fillable = [
        'store_view',
        'name',
        'description',
        'web_image',
        'mobile_image',
        'primary_button_title',
        'primary_button_url',
        'secondary_button_title',
        'secondary_button_url',
    ];

    public static $rules = [
        'store_view' => 'required',
        'name' => 'required',
        'description' => 'required'
    ];

    public static $messages = [];
}
