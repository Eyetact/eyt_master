<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaSetting extends Model
{
    use HasFactory;

    public $fillable = [
        'icon',
        'title',
        'url',
        'created_by',
        'active'
    ];
}
