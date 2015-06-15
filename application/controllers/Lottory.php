<?php
/**
 * @name LottoryController
 *
 * @desc 抽奖汇页面---控制器
 */

class LottoryController extends BaseController {

    protected $userinfo = null;

//    public function init()
//    {/* {{{ */
//        //初始化日志路径，系统参数
//        LogManager::setLogPath(Yaf_Registry::get('config')->application->log_path);
//        LogManager::getApiLog()->log('init item controller succ');
//        //print_r($this->logger);
//    }/*}}}*/

////    获取行程list
    public function indexAction()
    {/*{{{*/

        $request = Yaf_Registry::get('request');
        $fmt = strval($request->getQuery("fmt",'html','trim'));

//        $json = json_encode($res);
        if($fmt == 'web') {
            $this->headJson();
//            echo $json;
            return false;
        }

        //导出到视图的变量列表
//        $this->_view->datainfo = $res;
        $this->display('index');
        return false;

    }/*}}}*/

    public function appUrlAction()
    {/*{{{*/
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $iphone = (strpos($agent, 'iphone')) ? true : false;
        $android = (strpos($agent, 'android')) ? true : false;
        $androidbig = (strpos($agent, 'android 4')) ? true : false;

        if($iphone){
            header("Location:https://itunes.apple.com/us/app/hai-you/id773050075?ls=1&mt=8");
        }

        if($android){
//            if($androidbig){
//                echo "<script>alert('您的手机版本不支持flash.');window.location.href='http://hitrip.sinaapp.com/'</script>";
//            }else{
//                header("Location:http://www.haitrip.cn/hitrip.apk");
//            }
            header("Location:http://www.choujianghui.com/luckyclub.apk");
        }
        return false;

    }/*}}}*/
}



