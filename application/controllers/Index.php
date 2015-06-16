<?php
/**
 * @name IndexController
 *
 * @desc 默认控制器
 */

class IndexController extends BaseController {
    public function indexAction()
    {/*{{{*/
        $this->forward('article','list');
        return false;
    }/*}}}*/
	
}
