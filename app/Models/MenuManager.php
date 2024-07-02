<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuManager extends Model
{
    use HasFactory;
    protected $table='menus';
    protected $fillable=[
        'module_id',
        'menu_type',
        'name',
        'code',
        'path',
        'status',
        'include_in_menu',
        'meta_title',
        'meta_description',
        'created_date',
        'assigned_attributes',
        'sequence',
        'parent',
        'is_delete',
        'deleted_at',
        'sidebar_name'
    ];

    public function children()
    {
        return $this->hasMany(MenuManager::class, 'parent');
    }

    public function childrens()
    {
        return $this->children()->where('is_delete',0)->where('include_in_menu', 1)->orderBy('sequence', 'asc')->get();
    }

    public function parent()
    {
        return $this->belongsTo(MenuManager::class, 'parent');
    }
    public function module(){
        return $this->belongsTo(Module::class);
    }
}
