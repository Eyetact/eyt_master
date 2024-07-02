<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    use HasFactory;
    protected $fillable=['mailer_id','transport_type','email','password','mail_server','port','encryption_mode','imap_mail_server','imap_port','imap_encryption_mode','authentication_mode','sender_address','delivery_address','created_by'];

    public function mailbox()
    {
        return $this->hasMany(Mailbox::class,'smtp','id');
    }

    public function imap_mailbox()
    {
        return $this->hasMany(Mailbox::class,'imap','id');
    }
}
