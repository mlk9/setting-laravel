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
use Illuminate\Support\Facades\Config;
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
        $table = config('ensetting.table','setting');
        $salts = config('ensetting.salts',['salt']);
        $value = $value === true ? "**TRUE**SL" : $value;
        $value = $value === false ? "**FALSE**SL" : $value;
        $salt = sha1($salts[rand(0,count($salts)-1)]);
        $valueEncrypt = Crypt::encryptString($value);
        if (is_null(DB::table($table)->where('key', $key)->get()->first())) {
            DB::table($table)->insert(['key' => $key, 'value' => $salt.$valueEncrypt]);
        } else {
            DB::table($table)->where('key', $key)->update(['value' => $salt.$valueEncrypt]);
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
        $table = config('ensetting.table','setting');
        $salts = config('ensetting.salts',['salt']);
        $valueEncrypt =  DB::table($table)->where('key', $key)->get()->first();
        if (is_null($valueEncrypt))
            return $default;
        try {
            foreach($salts as $salt)
            {
                $salt = sha1($salt);
                if(strpos($valueEncrypt->value,$salt) >= 0)
                {
                    $valueDecrypted = Crypt::decryptString(substr($valueEncrypt->value,strlen($salt)));
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
        $table = config('ensetting.table','setting');
        if (!is_null(DB::table($table)->where('key', $key)->get()->first())) {
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
        $table = config('ensetting.table','setting');

        if ($this->exists($key)) {
            DB::table($table)->where('key', $key)->delete();
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
        $table = config('ensetting.table','setting');

        $allSetting = DB::table($table)->get(['key', 'value']);
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
        $table = config('ensetting.table','setting');

        try {
            DB::table($table)->delete();
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
        $this->set($this->all());
    }

    /**
     * Replace All Configs App Provider
     *
     * @return void
     */
    public function replaceAllConfigs() : void {
        foreach ($this->all() as $key => $value) {
            Config::set($key,$value);
        }
    }

    /**
     * Replace Configs App Provider
     *
     * @return void
     */
    public function replaceConfigs(array $configs = []) : void {
        foreach ($configs as $key => $value) {
            Config::set($key,$this->all()[$value] ? $this->all()[$value] : null);
        }
    }
}
