<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable=['copyright_text','footer_text','meta_keywords','meta_description','logo_text','email','password','server','port','encryption','created_by','logo','footer_logo','web_icon','domain','url'];
}
