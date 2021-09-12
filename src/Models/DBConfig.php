<?php

namespace Mlk9\DBconfig\Models;

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
        $keyEncrypt =  Crypt::encryptString($key);
        $valueEncrypt =  Crypt::encryptString($value);
        $count = count(DB::select('select * from '.$this->table.' where key = :key', ['key' => $keyEncrypt]));
        if($count==0)
        {
            DB::insert('insert into '.$this->table.' (id, key, value) values (NULL, :key, :value)', ['key'=>$keyEncrypt,'value'=>$valueEncrypt]);
        }else{
            DB::update(
                'update '.$this->table.' set value = :value where key = :key',
                ['key'=>$keyEncrypt,'value'=>$valueEncrypt]
            );
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
        $keyEncrypt =  Crypt::encryptString($key);
        $valueEncrypt = DB::select('select * from '.$this->table.' where key = :key', ['key' => $keyEncrypt])->first();
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
        $keyEncrypt =  Crypt::encryptString($key);
        $count = count(DB::select('select * from '.$this->table.' where key = :key', ['key' => $keyEncrypt]));
        if ($count>0) {
            return true;
        }

        return false;
    }

}
