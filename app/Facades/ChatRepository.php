<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChatRepository extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'chatRepository';
    }
}
