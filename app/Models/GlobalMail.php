<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalMail extends Model
{
    use HasFactory;

    public $fillable = [
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address'
    ];
}
