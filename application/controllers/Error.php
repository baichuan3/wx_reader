<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php

 */
class ErrorController extends Yaf_Controller_Abstract {
	public function errorAction($exception) {
        print_r($exception);exit;
		//$this->getView()->assign("exception", $exception);
	}
}
