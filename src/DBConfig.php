<?php

/**
 * DBConfig File
 *
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */

namespace Mlk9\DBConfig;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * DBConfig Class
 *
 * @category Configure
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/config-storage-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/config-storage-laravel
 */
class DBConfig
{
    protected $table;

    /**
     * __construct function
     */
    public function __construct()
    {
        $this->table = 'configs';
    }

    /**
     * Set configure
     *
     * @param string $key   Key name
     * @param mixed  $value Value of key
     *
     * @return void
     */
    private function _setNone($key, $value)
    {
        $valueEncrypt =  Crypt::encryptString($value);
        if (is_null(DB::table($this->table)->where('key', $key)->get()->first())) {
            DB::table($this->table)->insert(['key' => $key, 'value' => $valueEncrypt]);
        } else {
            DB::table($this->table)->where('key', $key)->update(['value' => $valueEncrypt]);
        }
    }

    /**
     * Set configure
     *
     * @param array $configs Group set config
     *
     * @return void
     */
    private function _setArray($configs)
    {
        foreach ($configs as $key => $value) {
            $valueEncrypt =  Crypt::encryptString($value);
            if (is_null(DB::table($this->table)->where('key', $key)->get()->first())) {
                DB::table($this->table)->insert(['key' => $key, 'value' => $valueEncrypt]);
            } else {
                DB::table($this->table)->where('key', $key)->update(['value' => $valueEncrypt]);
            }
        }
    }

    /**
     * Call custom document
     *
     * @param string $method    Method Name
     * @param mixed  $arguments Arguments of the method
     *
     * @return void
     */
    public function __Call($method, $arguments)
    {
        if ($method == 'set') {
            if (count($arguments) == 2) {
                return call_user_func_array(array($this, '_setNone'), $arguments);
            }
            if (count($arguments) == 1) {
                return call_user_func_array(array($this, '_setArray'), $arguments);
            }
        }
    }


    /**
     * Get configure
     *
     * @param string $key     Key name
     * @param mixed  $default Default value not exist
     *
     * @return void
     */
    public function get($key, $default = null)
    {
        $valueEncrypt =  DB::table($this->table)->where('key', $key)->get()->first();
        if (is_null($valueEncrypt))
            return $default;
        try {
            $valueDecrypted = Crypt::decryptString($valueEncrypt->value);
            return $valueDecrypted;
        } catch (DecryptException $e) {
            return $default;
        }
    }


    /**
     * Key exist
     *
     * @param string $key Check exist
     *
     * @return bool
     */
    public function exists($key)
    {
        if (!is_null(DB::table($this->table)->where('key', $key)->get()->first())) {
            return true;
        }

        return false;
    }

    /**
     * Key destroy
     *
     * @param string $key destroy!
     *
     * @return bool
     */
    public function destroy($key)
    {
        if($this->exists($key))
        {
            DB::table($this->table)->where('key', $key)->delete();
            return true;
        }

        return false;
    }

    /**
     * Get all Setting
     *
     * @return array
     */
    public function all()
    {
        $allConfigs = DB::table($this->table)->get(['key','value']);
        $decryptConfigs = [];
        foreach ($allConfigs as $data) {
           $decryptConfigs[$data->key] = $this->get($data->key);
        }
        return $decryptConfigs;
    }

     /**
     * Destroy all Setting
     *
     * @return array
     */
    public function destroyAll()
    {
        return DB::table($this->table)->delete();
    }
}
