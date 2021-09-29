<?php

namespace Mlk9\DBConfig;

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
    private function setNone($key,$value)
    {
        $valueEncrypt =  Crypt::encryptString($value);
        if (is_null(DB::table($this->table)->where('key',$key)->get()->first())) {
            DB::table($this->table)->insert(['key'=>$key,'value'=>$valueEncrypt]);
        }else{
            DB::table($this->table)->where('key',$key)->update(['value'=>$valueEncrypt]);
        }
    }

    /**
     * set configure
     *
     * @param array $configs
     * @return void
     */
    private function setArray($array)
    {
        foreach ($array as $key => $value) {
            $valueEncrypt =  Crypt::encryptString($value);
            if (is_null(DB::table($this->table)->where('key',$key)->get()->first())) {
                DB::table($this->table)->insert(['key'=>$key,'value'=>$valueEncrypt]);
            }else{
                DB::table($this->table)->where('key',$key)->update(['value'=>$valueEncrypt]);
            }
        }
    }

    public function __Call($method,$arguments)
    {
        if($method=='set')
        {
            if (count($arguments) == 2) {
                return call_user_func_array(array($this,'setNone'), $arguments);
            }   
            if (count($arguments) == 1) {
                return call_user_func_array(array($this,'setArray'), $arguments);
            }
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
        if (!is_null(DB::table($this->table)->where('key',$key)->get()->first())) {
            return true;
        }

        return false;
    }

}
