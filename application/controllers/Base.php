<?php

class BaseController extends Yaf_Controller_Abstract {

//    protected $userinfo = null;

    public function init()
    {/* {{{ */
        //初始化日志路径，系统参数
        LogManager::setLogPath(Yaf_Registry::get('config')->application->log_path);

//    $request = Yaf_Registry::get('request');
//
//        print_r('execute here');
    }
    /* }}} */


    public function _safeShow($str) 
    {/* {{{ */
        return UtilsBox::dhtmlspecialchars($str);
    }
    /* }}} */

    public function _safeFilter(&$request, $paramsArr)
    {/* {{{ */
        if (is_string($paramsArr)) {
            $paramsArr = explode(",", $paramsArr);
        }
        $maxlength = count($paramsArr);
        for ($i = 0; $i < $maxlength; $i++) {
            $var = $paramsArr[$i];
            if (array_key_exists($var, $request) && $request[$var])
                $request[$var] = strip_tags(trim($request[$var]));
            //     $request[$var] = trim($request[$var]);
        }
    }

/* }}} */

    protected function goToAct($cname, $aname, $param = array())
    {/* {{{ */
        $addr = '/' . $cname;
        $addr.= '/' . $aname;
        if (empty($param)) {
            header('location: ' . $addr);
            exit;
        }

        $addr.= '/?' . http_build_query($param);
        header('location: ' . $addr);
        exit;
    }

/* }}} */

    /**
     * 指定格式头信息输出
     */
    public function headJson() 
    {/*{{{*/
        header("HTTP/1.1 200 OK");
        header('Content-type: text/json; charset=utf-8');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Pragma: no-cache");
    }/*}}}*/

    public function echoCallback($callback, $json) 
    {/*{{{*/
        header('Content-type: text/javascript');  
        $callback = htmlspecialchars($callback, ENT_QUOTES);
        echo " $callback($json);";
    }/*}}}*/

    public function initOutPut()                                                          
    {/*{{{*/
        return array(
            "error"  => 0,
            "data" => "",
            "msg" => "succ",
        );

    }/*}}}*/

    public function outPut($res,$callback)
    {/*{{{*/
        $json = json_encode($res);
        if($callback) {
            $this->echoCallback($callback, $json);
        }else {
            $this->headJson();
            echo $json;
        }   
    }/*}}}*/
}
