<?php

namespace Bonabrian\Otp\Facades;

use Illuminate\Support\Facades\Facade;

class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-otp';
    }
}
