<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pragn extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['customer_id', 'customer_group_id'  ];

    /**
     * The attributes that should be cast.
     *
     * @var string[]
     */
    protected $casts = ['created_at' => 'datetime:d/m/Y H:i', 'updated_at' => 'datetime:d/m/Y H:i'];

    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->user_id = auth()->user()->id;
        });
    }

    
    
	public function customer()
	{
		return $this->belongsTo(\App\Models\User::class , "customer_id");
	}
	public function group()
	{
		return $this->belongsTo(\App\Models\CustomerGroup::class , "customer_group_id");
	}
    

}
