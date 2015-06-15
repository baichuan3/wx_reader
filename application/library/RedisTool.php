<?php
Yaf_Loader::import(APPLICATION_PATH."/application/library/redislib/lib/Predis.php");

class RedisTool{
    static public $_redis = null;
    public function getInstance($confs = ''){
        if(!$confs){
            list($host,$port,$db,$password) = explode(":",Yaf_Registry::get("config")->application->redis_conf);
        }else
            list($host,$port,$db) = explode(":",$confs);
        if(!(self::$_redis instanceof Predis_Client)){
            if($password)
                self::$_redis  = new Predis_Client(array("host"=>$host,"port"=>$port,"database"=>$db, "password" => $password));
            else
                self::$_redis  = new Predis_Client(array("host"=>$host,"port"=>$port,"database"=>$db));
        }
        return self::$_redis;
    }

}
