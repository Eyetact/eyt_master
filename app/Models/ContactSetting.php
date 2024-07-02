<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;
    public $fillable = [
        'email',
        'phone',
        'address',
        'latitude',
        'longitude',
        'created_by'
    ];
}
