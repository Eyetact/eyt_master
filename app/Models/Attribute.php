<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    // attributes of the attribute model
    protected $fillable = [
        'module',
        'name',
        'type',
        'min_length',
        'max_length',
        'steps',
        'input',
        'required',
        'default_value',
        'select_option',
        'constrain',
        'constrain2',
        'on_update_foreign',
        'on_delete_foreign',
        'is_enable',
        'is_system',
        'is_multi',
        'max_size',
        'file_type',
        'source',
        'target',
        'code',
        'attribute',
        'attribute2',
        'primary',
        'secondary',
        'fixed_value',
        'fk_type',
        'user_id',
        'multiple',
        'condition_attr',
        'condition_value'
    ];


    protected $guarded = [];

    public function moduleObj()
    {
        return $this->belongsTo(Module::class, 'module');
    }

    public function multis()
    {
        return $this->hasMany(Multi::class);
    }
}
