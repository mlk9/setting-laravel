<?php

/**
 * Setting Facade File
 * 
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */


namespace Mlk9\Setting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Mlk9\Setting Methods
 *
 * @method   void set(string $key, mixed $value) Sets Mlk9\Setting 
 * @method   void set($array = ['KEY' => 'VALUE']) Group Set Mlk9\Setting 
 * @method   string get(string $key, mixed $default) Gets Mlk9\Setting 
 * @method   array all() Get All Mlk9\Setting 
 * @method   bool exists(string $key) Exists Mlk9\Setting 
 * @method   bool destroy(string $key) Destroy A Key Mlk9\Setting 
 * @method   bool destroyAll() Destroy All Keys Mlk9\Setting 
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */
class Setting extends Facade
{
    /**
     * Define facade function
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'setting';
    }
}
