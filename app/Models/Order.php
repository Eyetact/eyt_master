<?php

namespace App\Models;

use App\Models\Admin\Mixture;
use App\Models\Admin\Software;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function software()
    {
        return $this->belongsTo(Software::class);
    }

    public function mixture()
    {
        return $this->belongsTo(Mixture::class);
    }
}
