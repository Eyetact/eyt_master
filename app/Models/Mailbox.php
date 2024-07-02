<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailbox extends Model
{
    use HasFactory;
    protected $fillable=['mailer_id','mailbox_name','created_by','smtp','imap','role_id','group_id'];

    public function smtps()
    {
        return $this->belongsTo(Smtp::class,'smtp');
    }

    public function imaps()
    {
        return $this->belongsTo(Smtp::class,'imap');
    }
}
