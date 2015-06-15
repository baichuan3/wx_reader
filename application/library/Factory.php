<?php
/**
 * Description of Factory
 *
 * @author yangjian
 */
class Factory {
    public static function DB_Factory($config){
        $port = $config['port']?$config['port']:3306;
        $dns = "mysql:host=".$config['host'].";dbname=".$config['dbname'].";port=".$port;
        $pdo = new PDO($dns, $config['name'], $config['password']);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $pdo->query( 'SET NAMES '.$config['charset']);
        return $pdo;
    }
    public static function Get_Simple_PDO($config_name){
        $config = Yaf_Registry::get("config")->get($config_name);
        $pdo = Factory::DB_Factory($config);
        return new SimplePDO($pdo);
    }
}
