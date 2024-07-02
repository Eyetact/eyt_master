<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Session;

class FlashRepository
{
    public function setFlashSession($type, $message)
    {
        Session::flash('message', $message);
	    Session::flash('type', $type);
    }
}
