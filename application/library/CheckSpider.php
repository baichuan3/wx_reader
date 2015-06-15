<?php
/**
 * 监测蜘蛛或慢查询攻击，封杀ip
 * @author liufuqiang
 */

class CheckSpider{


    public static $key_prefix  = "_$@$@_";//防止key冲突
    public static $key_prefix_consume  = "_#$%&_";//slowquery 防止key冲突
    public static $limit_time  = 30;//单位时间
    public static $limit_visit = 600;//单位时间限制次数
    public static $append_time = 600;//触发限制延长时间

    public static $limit_time_consume  = 20;//单位时间
    public static $limit_visit_consume = 500;//单位时间限制次数
    public static $append_time_consume = 300;//触发限制延长时间
    public static $base_consume_key    = "__#base_consume#__";//基准consume key
    public static $base_consume        = 300;//基准consume 时间，毫秒

    /**取ip前两段校验
     *
     */
    private function getIpPrefix($ip){
        $ips = explode(".",$ip);
        return $ips[0].".".$ips[1];
    }

    /**是否白名单ip
     *
     */
    public static function isWhiteIp($ip){/*{{{*/
		$config = Yaf_Registry::get('config');
        $iplist = explode("|",$config->application->ip_list);
        if(in_array(self::getIpPrefix($ip),$iplist)){
            return true;
        }
        return false;
    }/*}}}*/
    /*
     *监测ip频度
     */
    public static function check($ip)
    {
        //ip白名单直接放行
        if(self::isWhiteIp($ip)){
            return false;
        }

        $key = self::$key_prefix.$ip;
        $memcache =  Yaf_Registry::get('memcache');

        $limit_visit = self::$limit_visit;
        
        //blackip
        if($memcache->get("spider_ip_".$ip)){
            $limit_visit = ceil($limit_visit/100);
        }

        $ip_visit = (int)$memcache->get($key);
        if(!$ip_visit){
            $memcache->set($key,1,false,self::$limit_time);
        }else{
            if($ip_visit >= $limit_visit){//命中限制
                //延长封杀时间
                $memcache->replace($key, $ip_visit+1,false,self::$append_time);
                return true;
            }else{//没命中限制，+1
                $memcache->increment($key,1);
            }
        }
        return false;
    }

    /*
     *监测ip慢查询频度
     */
    public static function checkSlow($ip,$consume)
    {
        //ip白名单直接放行
        if(self::isWhiteIp($ip)){
            return false;
        }

        $consume = (int)$consume;
        $key = self::$key_prefix_consume.$ip;
        $memcache =  Yaf_Registry::get('memcache');

        //基准consume
        $base_consume = $memcache->get(self::$base_consume_key);
        if(!$base_consume){//如果没有基准，设置缺省值
            $memcache->set(self::$base_consume_key,self::$base_consume);
        }
        $base_consume = max(self::$base_consume,$base_consume);

        if($consume < $base_consume){//直接返回ok
            return false;
        }

        $ip_visit = (int)$memcache->get($key);
        if(!$ip_visit){
            $memcache->set($key,1,false,self::$limit_time_consume);
        }else{
            if($ip_visit >= self::$limit_visit_consume){//命中限制
                //延长封杀时间
                $memcache->replace($key, $ip_visit+1,false,self::$append_time_consume);
                return true;
            }else{//没命中限制，+1
                $memcache->increment($key,1);
            }
        }
        return false;
    }

    /*
     *重置ip频度
     */
    public static function resetIp($ip)
    {
        $key = self::$key_prefix.$ip;
        $consume_key = self::$key_prefix_consume.$ip;
        Yaf_Registry::get('memcache')->delete($key);
        Yaf_Registry::get('memcache')->delete($consume_key);
    }
}
?>
