<?php

namespace App\Models;

use Eloquent as Model;

class Category extends Model
{
    public $table = 'category';

    public $fillable = [
        'category_name',
        'category_image',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'category_name' => 'string',
        'category_image' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    public static $rules = [
        'category_name' => 'required|unique:category,category_name',
        // 'category_image' => 'sometimes|required|dimensions:width=30,height=30|max:2048'
    ];

    public static $messages = [
        'category_name.required' => 'Category name is required.',
        'category_name.unique' => 'Category already exists!',
        'category_image.dimensions' => ' Add info for recommended size for Icon - 30*30'
    ];

    public function createdUser()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedUser()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
