<?php
//ini_set('display_errors',1);            //错误信息
//ini_set('display_startup_errors',1);    //php启动错误信息
//error_reporting(-1);                    //打印出所有的 错误信息
//直接屏幕输出错误信息
//--------------------------------------------------
//如果要输出到文件就加这一句
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); //将出错信息输出到一个文本文件

define('APPLICATION_PATH', dirname(__FILE__));
//phpinfo();
//print_r(get_loaded_extensions());
date_default_timezone_set('PRC');
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap()->run(); // 开始执行
?>

