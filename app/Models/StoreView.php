<?php

namespace App\Models;

use Eloquent as Model;

class StoreView extends Model
{
    public $table = 'store_view';

    public $fillable = [
        'region',
        'language',
        'slug',
        'description',
        'meta_data',
        'meta_keywords',
        'status'
    ];

    // protected $casts = [
    //     'id' => 'integer',
    //     'category_name' => 'string',
    //     'category_image' => 'string',
    //     'created_by' => 'integer',
    //     'updated_by' => 'integer'
    // ];

    public static $rules = [
        'slug' => 'required|unique:store_view,slug',
        // 'category_image' => 'sometimes|required|dimensions:width=30,height=30|max:2048'
    ];

    public static $messages = [
        'slug.required' => 'name is required.',
        'slug.unique' => 'Slug already exists!',
        // 'category_image.dimensions' => ' Add info for recommended size for Icon - 30*30'
    ];

    // public function createdUser()
    // {
    //     return $this->hasOne(User::class, 'id', 'created_by');
    // }

    // public function updatedUser()
    // {
    //     return $this->hasOne(User::class, 'id', 'updated_by');
    // }
}
