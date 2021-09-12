<?php

namespace Mlk9\DBConfig\Facades;

use Illuminate\Support\Facades\Facade;

class DBConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dbconfig';
    }
}