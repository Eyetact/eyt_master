<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_system',
        'code',
        'user_id',
        'parent_id',
        'migration',
        'is_delete',
        'type',
        'status',
        'shared',
        'addable'

    ];

    public function fields(){
        return $this->hasMany(Attribute::class, 'module');
    }

    public function menu(){
        return $this->hasOne(MenuManager::class, 'module_id');
    }

    public function childs(){
        return $this->hasMany(Module::class, 'parent_id');
    }
}
