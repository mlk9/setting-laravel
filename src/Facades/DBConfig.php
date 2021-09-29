<?php

/**
 * DBConfig Facade File
 * 
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */


namespace Mlk9\DBConfig\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Mlk9\DBconfig Methods
 *
 * @method   void set(string $key, mixed $value) Sets Mlk9\DBConfig 
 * @method   void set($array = ['KEY' => 'VALUE']) Group Set Mlk9\DBConfig 
 * @method   void get(string $key, mixed $default) Gets Mlk9\DBConfig 
 * @method   void exists(string $key) Exists Mlk9\DBConfig 
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */
class DBConfig extends Facade
{
    /**
     * Define facade function
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return 'dbconfig';
    }
}
