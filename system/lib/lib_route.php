<?php
/**
 * URL处理类
 * @copyright   Copyright(c) 2011
 * @author      yuansir <yuansir@live.cn/yuansir-web.com>
 * @version     1.0
 */
final class Route{
        public $url_query;
        public $url_type;
        public $route_url = array();


        public function __construct() {
                $this->url_query = parse_url($_SERVER['REQUEST_URI']);      
        }
        /**
         * 设置URL类型
         * @access      public
         */
        public function setUrlType($url_type = 2){
                if($url_type > 0 && $url_type <3){
                        $this->url_type = $url_type;
                }else{
                        trigger_error("指定的URL模式不存在！");
                }
        }

        /**
         * 获取数组形式的URL  
         * @access      public
         */
        public function getUrlArray(){
                $this->makeUrl();
                return $this->route_url;
        }
        /**
         * @access      public
         */
        public function makeUrl(){
                switch ($this->url_type){
                        case 1:
                                $this->querytToArray();
                                break;
                        case 2:
                                $this->pathinfoToArray();
                                break;
                }
        }
        /**
         * 将query形式的URL转化成数组
         * @access      public
         */
        public function querytToArray(){
                $arr = !empty ($this->url_query['query']) ?explode('&', $this->url_query['query']) :array();
                print_r(' arr is found 0.0 ');
                print_r($arr);
                print_r(' PATH_INFO is found 0.0 ');
//                $arr2 = !empty ($this->url_query['PATH_INFO']) ?explode('&', $this->url_query['PATH_INFO']) :array();
//                print_r($arr2);
                $array = $tmp = array();
                if (count($arr) > 0) {
                        foreach ($arr as $item) {
                                $tmp = explode('=', $item);
                                $array[$tmp[0]] = $tmp[1];
                        }
                        if (isset($array['app'])) {
                                $this->route_url['app'] = $array['app'];
                                unset($array['app']);
                        } 
                        if (isset($array['c'])) {
                                $this->route_url['c'] = $array['c'];
                                unset($array['c']);
                        } 
                        if (isset($array['a'])) {
                                $this->route_url['a'] = $array['a'];
                                unset($array['a']);
                        }
                        if(count($array) > 0){
                                $this->route_url['params'] = $array;
                        }
                }else{
                        $this->route_url = array();
                }   
        }
        /**
         * 将PATH_INFO的URL形式转化为数组
         * @access      public
         */
        public function pathinfoToArray(){
            $arr = !empty ($this->url_query['query']) ?explode('&', $this->url_query['query']) :array();
            print_r(' arr is found ');
            print_r($arr);
            url_query
        }
}


