<?php
/**
 * @name ArticleController
 *
 * @desc Article---控制器
 */

class ArticleController extends BaseController {

    public function listAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $start = intval($request->getQuery("start",'0','trim'));
        $count = intval($request->getQuery("count",'30','trim'));
        $res = ArticleModel::articlelist($start, $count);

        $total_count = ArticleModel::articleTotalCount();

        $json = json_encode($res);
//        print_r($res);

        //导出到视图的变量列表
        $this->_view->datainfo = $res;
        $this->_view->start = strval($start);
        $this->_view->count = strval($count);
        $this->_view->total_count = strval($total_count);
        $this->display('index');
        return false;

    }/*}}}*/

}



