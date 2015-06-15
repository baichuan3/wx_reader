<?php

class ArticleModel {

    public static function articlelist($start, $count)
    {/*{{{*/
        $res = '';

        $end = $start + $count;
        $sql = " select mid,openid,sourcename,headimage,title,url,content168,imglink,created_at,docid,last_modified from wx_reader order by last_modified desc limit ";
        $sql = $sql . strval($start) . "," . strval($end);
//        print_r($sql);
        $db_data = Yaf_Registry::get("db")->FetchAll($sql);
//        print_r($db_data);

        $arr = array();
        $i = 0;
        if($db_data){
            foreach($db_data as $da_data_info){
                $data_info = array();
                foreach($da_data_info as $key => $value){
                    $data_info[$key] = $value;
                }

                $data_info["account_url"] = "http://weixin.sogou.com/gzh?openid=" . $da_data_info["openid"];
                $data_info["time"] = ArticleModel::transTime($da_data_info["last_modified"]);

                $arr[$i] = $data_info;
                $i++;
            }
        }

        $res = array();
        $res["articles"] = $arr;

        return $res;
    }/*}}}*/

    public static function transTime($ustime) {
        $ytime = date("Y-m-d H:i",$ustime);
        $rtime = date("n月j日 H:i",$ustime);
        $htime = date("H:i",$ustime);
        $time = time() - $ustime;
        $todaytime = strtotime("today");
        $time1 = time() - $todaytime;
        if($time < 60){
            $str = '刚刚';
            }else if($time < 60 * 60){
            $min = floor($time/60);
            $str = $min.'分钟前';
            }else if($time < $time1){
            $str = '今天 '.$htime;
            }else{
           $str = $rtime;
        }
       return $str;
    }

}
