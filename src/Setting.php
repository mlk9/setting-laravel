<?php

/**
 * Setting File
 *
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */

namespace Mlk9\Setting;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

/**
 * Setting Class
 *
 * @category Setting
 * @package  Laravel
 * @author   Mohammad Maleki <malekii24@outlook.com>
 * @license  MIT https://github.com/mlk9/setting-laravel/blob/main/LICENSE
 * @link     https://github.com/mlk9/setting-laravel
 */
class Setting
{
    protected $table;

    /**
     * __construct function
     */
    public function __construct()
    {
        $this->table = config('ensetting.table','setting');
    }


    /**
     * Set Setting
     *
     * @param string $key   Key name
     * @param mixed  $value Value of key
     *
     * @return void
     */
    private function _setSingle($key, $value): void
    {
        $salts = config('ensetting.salts',['salt']);
        $value = $value === true ? "**TRUE**SL" : $value;
        $value = $value === false ? "**FALSE**SL" : $value;
        $salt = sha1($salts[rand(0,count($salts)-1)]);
        $valueEncrypt = Crypt::encryptString($value);
        if (is_null(DB::table($this->table)->where('key', $key)->get()->first())) {
            DB::table($this->table)->insert(['key' => $key, 'value' => $salt.$valueEncrypt]);
        } else {
            DB::table($this->table)->where('key', $key)->update(['value' => $salt.$valueEncrypt]);
        }
    }

    /**
     * Set Setting
     *
     * @param array $Setting Group set config
     *
     * @return void
     */
    private function _setArray($Setting): void
    {
        foreach ($Setting as $key => $value) {
            $this->_setSingle($key, $value);
        }
    }

    /**
     * Call custom document
     *
     * @param string $method    Method Name
     * @param mixed  $arguments Arguments of the method
     *
     * @return mixed
     */
    public function __Call($method, $arguments): mixed
    {
        if ($method == 'set') {
            if (count($arguments) == 2) {
                return call_user_func_array(array($this, '_setSingle'), $arguments);
            }
            if (count($arguments) == 1) {
                return call_user_func_array(array($this, '_setArray'), $arguments);
            }
        }
    }

    /**
     * Get Setting
     *
     * @param string $key     Key name
     * @param mixed  $default Default value not exist
     *
     * @return string
     */
    public function get($key, $default = null): string
    {
        $salts = config('ensetting.salts',['salt']);
        $valueEncrypt =  DB::table($this->table)->where('key', $key)->get()->first();
        if (is_null($valueEncrypt))
            return $default;
        try {
            foreach($salts as $salt)
            {
                $salt = sha1($salt);
                if(strpos($valueEncrypt->value,$salt) >= 0)
                {
                    $valueDecrypted = Crypt::decryptString(substr($valueEncrypt->value,0,strlen($salt)));
                }
            }
            $valueDecrypted = $valueDecrypted === "**TRUE**SL" ? true : $valueDecrypted;
            $valueDecrypted = $valueDecrypted === "**FALSE**SL" ? false : $valueDecrypted;
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
     * @return boolean
     */
    public function exists($key): bool
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
     * @return boolean
     */
    public function destroy($key): bool
    {
        if ($this->exists($key)) {
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
    public function all(): array
    {
        $allSetting = DB::table($this->table)->get(['key', 'value']);
        $decryptSetting = [];
        foreach ($allSetting as $data) {
            $decryptSetting[$data->key] = $this->get($data->key);
        }
        return $decryptSetting;
    }

    /**
     * Destroy all Setting
     *
     * @return bool
     */
    public function destroyAll(): bool
    {
        try {
            DB::table($this->table)->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Rechange All Salts 
     *
     * @return void
     */
    public function refreshSalts(): void
    {
        $all = $this->all();
        $this->set($all);
    }
}
