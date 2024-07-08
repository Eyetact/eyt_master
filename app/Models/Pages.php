<?php

namespace App\Models;

use Eloquent as Model;

class Pages extends Model
{
    public $table = 'page';

    public $fillable = [
        'store_view',
        'title',
        'name',
        'description',
        'meta_data',
        'meta_keywords',
        'slug'
    ];

    public static $rules = [
        'store_view' => 'required',
        'title' => 'required',
        'slug' => 'required|unique:page,slug'
    ];

    public static $messages = [];
}
