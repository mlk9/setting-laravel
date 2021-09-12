<?php

namespace Mlk9\DBconfig\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class DBConfig
{
    protected $table;
    public function __construct()
    {
        $this->table = 'configs';
    }

    /**
     * set configure
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key,$value)
    {
        $valueEncrypt =  Crypt::encryptString($value);
        if (is_null(DB::table($this->table)->where('key',$key)->get())) {
            DB::table($this->table)->insert(['key'=>$key,'value'=>$valueEncrypt]);
        }else{
            DB::table($this->table)->where('key',$key)->update(['value'=>$valueEncrypt]);
        }
    }

    /**
     * get configure
     *
     * @param string $key
     * @param mixed $default = null
     * @return void
     */
    public function get($key,$default=null)
    {
        $valueEncrypt =  DB::table($this->table)->where('key',$key)->get()->first();
        if(is_null($valueEncrypt))
            return $default;
        try
        {
            $valueDecrypted = Crypt::decryptString($valueEncrypt->value);
            return $valueDecrypted;
        } catch (DecryptException $e) {
            return $default;
        }
    }


    /**
     * key exist
     *
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        if (!is_null(DB::table($this->table)->where('key',$key)->get())) {
            return true;
        }

        return false;
    }

}
