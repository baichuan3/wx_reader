<?php
/**
 * @name ItemController
 *
 * @desc Item---控制器
 */

class ItemController extends BaseController {

    protected $userinfo = null;

//    public function init()
//    {/* {{{ */
//        //初始化日志路径，系统参数
//        LogManager::setLogPath(Yaf_Registry::get('config')->application->log_path);
//        LogManager::getApiLog()->log('init item controller succ');
//        //print_r($this->logger);
//    }/*}}}*/

////    获取行程list
//    public function listFromTbAction()
//    {/*{{{*/
//
//        $request = Yaf_Registry::get('request');
//        $fmt = strval($request->getQuery("fmt",'html','trim'));
//        $page_no = intval($request->getQuery("page_no",'1','trim'));
//        $page_size = intval($request->getQuery("page_size",'20','trim'));
//
//        //$page_no为0时取最新count条的trip
//
//        //海游列表数据
////        $res = ItemModel::getList();
//        $res = $this -> getList($page_no, $page_size);
////        print_r($res);
//
//        $json = json_encode($res);
//        if($fmt == 'web') {
//            $this->headJson();
//            echo $json;
//            return false;
//        }
//
//        //导出到视图的变量列表
//        $this->_view->datainfo = $res;
//        $this->display('index');
//        return false;
//
//    }/*}}}*/

    //获取行程list
    public function importAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $lt_uid_v = intval($this->lt_uid);

//        $tb_uid = $this->getRequest()->getCookie("tb_uid",'');
//        print("tb_uid_str is not empty, tb_uid=" .  $tb_uid);

        if($lt_uid_v > 0){
//        echo("lt_uid_str is not empty");
//            $tb_uid = $this->getRequest()->getCookie($lt_uid_str . "tb_uid",'');
//            $tb_uname = $this->getRequest()->getCookie($tb_uid . "tb_uname",'');
//            $tb_token = $this->getRequest()->getCookie($tb_uid . "tb_token",'');

              $tb_uid = $this->getRequest()->getCookie($this->lt_uid."tb_uid",'');
              $tb_uname = $this->getRequest()->getCookie($tb_uid."tb_uname",'');
              $tb_token = $this->getRequest()->getCookie($tb_uid."tb_token",'');

            if($tb_uid){
    //            echo("tb_uid_str is not empty");
//                print_r($tb_uid);

                $arr = array();
                $arr['tb_uid'] =  $tb_uid;
                $arr['tb_token'] =  $tb_token;
                $arr['tb_uname'] =  $tb_uname;
                $arr['login_from'] =  'tb';

                $this->userinfo = $arr;
//                $this->userinfo['login_from'] = 'tb';
            }
        }

//        $tb_token = $this->getRequest()->getCookie("tb_token",'');
//        $tb_uid = $this->getRequest()->getCookie("lt2tb_uid",'');
//        $tb_uname = $this->getRequest()->getCookie("tb_uid2name",'');

         $this->_view->userinfo = $this->userinfo;
//        $this->_view->lt_uid = $lt_uid_str;

   # for test
//        $uid = "1407020000082978";
        if(!$this->userinfo){
            $from = "wap";

            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            $iphone = (strpos($agent, 'iphone')) ? true : false;
            $android = (strpos($agent, 'android')) ? true : false;

            if($iphone || $android){
                $from = "wap";
            }else{
                $from = "web";
            }

            $res = '';
            $res = array('from' => $from);
            $this->_view->datainfo = json_encode($res);

            $this->display('tbloginpre');
            return false;
        }

        $fmt = strval($request->getQuery("fmt",'html','trim'));
        $page_no = intval($request->getQuery("page_no",'1','trim'));
        $page_size = intval($request->getQuery("page_size",'10','trim'));

        $uid = $this->lt_uid;
        $res = $this -> getList($page_no, $page_size);

        $itemsLt = ItemModel::itemlist($uid);

        $res = $this->filterLtItem($res, $itemsLt);
//        print_r($res);
//        exit;

//        print_r(json_encode($res));
//        exit;

        $json = json_encode($res);
        if($fmt == 'web') {
            $this->headJson();
            echo $json;
            return false;
        }

        //导出到视图的变量列表
        $this->_view->datainfo = $res;
        $this->display('import');
        return false;

    }/*}}}*/



    public function filterLtItem($itemRes, $itemsLt)
    {/*{{{*/
        $index = 0;
        $arr = array();

        $itemLtTmp = array();
        foreach ($itemsLt['items'] as $itemExist){

            $itemLtTmpTmp['tx' . $index] = $itemExist['num_iid'];
            $index ++;
        }
        $itemLtTmp = array_values($itemLtTmpTmp);


        $index = 0;
        $itemTb = $itemRes['items_onsale_get_response']['items'];
        if(!empty($itemTb)){
            foreach ($itemTb['item'] as $item){
                //{"item_get_response":{"item":{"approve_status":"onsale","auction_point":0,"cid":50010388,"
                //delist_time":"2014-07-07 02:12:39","desc":"<p><img src=\"http:\/\/126.am\/beK6f3\" alt=\"\u624b\u673a\u4e8c\u7ef4\u7801\u8d2d\u4e70\u901a\u9053\"><\/p>",
                //"detail_url":"http:\/\/item.tbsandbox.com\/item.htm?id=2100508907871&spm=2014.1021796779.0.0","ems_fee":"15.00","express_fee":"12.00","freight_payer":"buyer",
                //"has_discount":false,"has_invoice":false,"has_showcase":false,"has_warranty":false,"increment":"0.00","input_pids":"","input_str":"","is_virtual":false,
                //"list_time":"2014-06-30 02:12:39","location":{"city":"\u5317\u4eac","state":"\u5317\u4eac"},"modified":"2014-06-13 15:17:18","nick":"sandbox_c_1","num":106,"num_iid":2100508907871,
                //"outer_id":"okbuy-bolv88-16973966","post_fee":"10.00","postage_id":0,"price":"291.00","product_id":0,"property_alias":"","props":"2703164:3713435;22196:46240;21541:38487;21921:28391;21921:44901;21921:28392",
                //"stuff_status":"new","title":"aa\u6c99\u7bb1\u6d4b\u8bd5\u963f\u8fea\u8fbe\u65af \u7537 \u8fd0\u52a8\u978b \u56e2\u961f\u7cfb\u5217\u76ae\u9769\u9762\u8010\u78e8\u8db3\u7403\u978b\u9760\u624b","type":"fixed","valid_thru":7}}}
                if(! (in_array($item['num_iid'], $itemLtTmp))){
                    $tmparr = array();
                    $tmparr['num_iid'] = $item['num_iid'];
                    $tmparr['price'] = $item['price'];
                    $tmparr['pic_url'] = $item['pic_url'];
                    $tmparr['title'] = $item['title'];

                    $arr['ix' . $index] = $tmparr;
                    $index ++;
                }
            }
        }

        //奇数个滤掉
        $itemsTmp = array();
        $itemsTmp = array_values($arr);
        if(count($itemsTmp) % 2 != 0){
//            print_r($itemsTmp);
//            exit;
            $itemsTmp = array_slice($itemsTmp, 0, (count($itemsTmp) -1));
        }

        $arrret['uid'] = $this->lt_uid;
        $arrret['items'] = $itemsTmp;

        return $arrret;

    }/*}}}*/


//    public function getList()
//    {/*{{{*/
//        $c = new TopClientUtil();
//
////        $c->appkey = appkey;
////        $c->secretKey = secret;
////        echo 'mark start';
////        try{
//        $topreq = new ItemsOnsaleGetRequestModel();
//
////        var_dump($topreq);
////        }catch (Exception $e){
////        print_r($e);
////    }
////        print_r('topClient start : ');
////        $topreq->setFields("approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id");
////        setFields("approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id");
//
//        $topresp = $c->execute($topreq);
////        print_r(UtilsBox::toGbk($topresp));
////        print_r($topresp);
//
////        print_r('topClient getList : ' . "$topresp");
//        return $topresp;
//
//    }/*}}}*/


    //获取行程list
    public function detailAction()
    {/*{{{*/

        $request = Yaf_Registry::get('request');
        $fmt = strval($request->getQuery("fmt",'html','trim'));
        $num_iid = intval($request->getQuery("num_iid",'0','trim'));

        $res = $this->getItem($num_iid);

//        wap_detail_url	 String	 否	http://auction1.wap.taobao.com/auction/item_detail-0db0-1234567.jhtml	适合wap应用的商品详情url ，该字段只在taobao.item.get接口中返回
        $wap_detail_url = $res['item_get_response']['item']['wap_detail_url'];
        $detail_url = $res['item_get_response']['item']['detail_url'];
//        print_r($wap_detail_url);
//        exit;
        header("Location:".$detail_url);
//        header("Location:".$wap_detail_url);
        return false;

    }/*}}}*/


    //taobao.items.onsale.get 文档：http://api.taobao.com/apidoc/api.htm?spm=0.0.0.0.ijykim&path=cid:4-apiId:18
    public function getList($page_no, $page_size)
    {/*{{{*/
        $c = new TopClientUtil();

//        $c->appkey = appkey;
//        $c->secretKey = secret;
//        echo 'mark start';
//        try{
        $paramArr = array(
            'app_key' => '1021796779',

            'session' => '6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786',

            'method' => 'taobao.items.onsale.get',

            'format' => 'json',

            'v' => '2.0',

            'sign_method'=>'md5',

            'timestamp' => date('Y-m-d H:i:s'),

            'partner_id' => 'top-apitools',

            'fields' => 'approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id',

            'page_no' => $page_no,

            'page_size' => $page_size,
        );

//        var_dump($topreq);
//        }catch (Exception $e){
//        print_r($e);
//    }
//        print_r('topClient start : ');
//        $topreq->setFields("approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id");
//        setFields("approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id");
//        $c->refreshAuthToken();
        $topresp = $c->execute($paramArr);
//        print_r(UtilsBox::toGbk($topresp));
//        print_r($topresp);

//        print_r('topClient getList : ' . "$topresp");
        return $topresp;

    }/*}}}*/

    public function getItem($num_iid)
    {/*{{{*/
        $c = new TopClientUtil();

//        $c->appkey = appkey;
//        $c->secretKey = secret;
//        echo 'mark start';
//        try{
        $paramArr = '';
        $paramArr = array(
            'app_key' => '1021796779',

            'session' => '6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786',

            'method' => 'taobao.item.get',

            'format' => 'json',

            'v' => '2.0',

            'sign_method'=>'md5',

            'timestamp' => date('Y-m-d H:i:s'),

            'partner_id' => 'top-apitools',

            'fields' => 'wap_detail_url,detail_url,num_iid,title,nick,type,cid,seller_cids,props,input_pids,input_str,desc,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,has_invoice,has_warranty,has_showcase,modified,increment,approve_status,postage_id,product_id,auction_point,property_alias,item_img,prop_img,sku,video,outer_id,is_virtual',

            'num_iid' => $num_iid,
        );
//        print_r($paramArr);

        $topresp = $c->execute($paramArr);

        return $topresp;
    }/*}}}*/


    //获取行程list
    public function tbloginAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');
//       $fmt = strval($request->getQuery("fmt",'html','trim'));
//       $page_no = intval($request->getQuery("page_no",'1','trim'));
//
//        $json = json_encode($res);
//        if($fmt == 'web') {
//            $this->headJson();
//            echo $json;
//            return false;
//        }


//        $lt_uid_str = strval($request->getQuery("uid",'0','trim'));
//        $this->_view->lt_uid = $lt_uid_str;
//
//        //导出到视图的变量列表
        $from = "wap";

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $iphone = (strpos($agent, 'iphone')) ? true : false;
        $android = (strpos($agent, 'android')) ? true : false;

        if($iphone || $android){
            $from = "wap";
        }else{
            $from = "web";
        }

        $res = array('from' => $from);
        $this->_view->datainfo = json_encode($res);
        $this->display('tblogin');
        return false;
    }/*}}}*/


    //获取行程list
    public function tbloginpreAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $this->display('tbloginpre');
        return false;
    }/*}}}*/

    //获取行程list
    public function tbcallbackAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');
        $code = strval($request->getQuery("code",'html','trim'));
//        $page_no = intval($request->getQuery("page_no",'1','trim'));
//        $page_size = intval($request->getQuery("page_size",'20','trim'));

        $arr = array('code' => $code);
//
//        //导出到视图的变量列表
        $res = '';
        $this->_view->datainfo = json_encode($arr);
        $this->display('tbcallback');
        return false;
    }/*}}}*/


    //import item
    public function addimportAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $uid = strval($request->getQuery("uid",'','trim'));
//        $uid = "1406290000286384";
        $uid = $this->lt_uid;
        $num_iids = strval($request->getQuery("num_iids",'','trim'));

        $idsarr = preg_split('/,/', $num_iids, 10);

        $callback = strval($request->getQuery("callback",'','trim'));

        $res = $this->initOutPut();
//
        //验证信息
        if(!$num_iids || !$uid )
        {/*{{{*/
            $res['error'] = 1;
            $res['msg'] = "parm_err";
            $this->outPut($res,$callback);
            return false;

        }/*}}}*/

        $index = 0;
        $arrlist = array();

        foreach($idsarr as $num_iid){
            //{"item_get_response":{"item":{"approve_status":"onsale","auction_point":0,"cid":50010388,"delist_time":"2014-07-07 02:12:39","desc":"<p><img src=\"http:\/\/126.am\/beK6f3\" alt=\"\u624b\u673a\u4e8c\u7ef4\u7801\u8d2d\u4e70\u901a\u9053\"><\/p>","detail_url":"http:\/\/item.tbsandbox.com\/item.htm?id=2100508907871&spm=2014.1021796779.0.0","ems_fee":"15.00","express_fee":"12.00","freight_payer":"buyer","has_discount":false,"has_invoice":false,"has_showcase":false,"has_warranty":false,"increment":"0.00","input_pids":"","input_str":"","is_virtual":false,"list_time":"2014-06-30 02:12:39","location":{"city":"\u5317\u4eac","state":"\u5317\u4eac"},"modified":"2014-06-13 15:17:18","nick":"sandbox_c_1","num":106,"num_iid":2100508907871,"outer_id":"okbuy-bolv88-16973966","post_fee":"10.00","postage_id":0,"price":"291.00","product_id":0,"property_alias":"","props":"2703164:3713435;22196:46240;21541:38487;21921:28391;21921:44901;21921:28392","stuff_status":"new","title":"aa\u6c99\u7bb1\u6d4b\u8bd5\u963f\u8fea\u8fbe\u65af \u7537 \u8fd0\u52a8\u978b \u56e2\u961f\u7cfb\u5217\u76ae\u9769\u9762\u8010\u78e8\u8db3\u7403\u978b\u9760\u624b","type":"fixed","valid_thru":7}}}
            $itemarr = $this->getItem($num_iid);

            if($itemarr){
                $tmpitemarr = $itemarr['item_get_response']['item'];
                $tmparr = array();
                $tmparr['num_iid'] = $tmpitemarr['num_iid'];
                $tmparr['price'] = $tmpitemarr['price'];
                $tmparr['pic_url'] = $tmpitemarr['pic_url'];
                $tmparr['title'] = $tmpitemarr['title'];

                $arrlist['ix' . $index] = $tmparr;
                $index ++;
            }
        }

        $arr['uid'] = $uid;
        $arr['items'] = json_encode(array_values($arrlist));

        $res = ItemModel::import($arr,$uid);
//        $res['result'] = true;
//        $res = json_encode($res);


        $this->outPut($res,$callback);
        return false;
    }/*}}}*/


    //import item
    public function removeimportAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $uid = strval($request->getQuery("uid",'','trim'));
//        $uid = "1406290000286384";
        $num_iids = strval($request->getQuery("num_iids",'','trim'));

        $callback = strval($request->getQuery("callback",'','trim'));

        $res = $this->initOutPut();
//
        //验证信息
        if(!$num_iids || !$uid )
        {/*{{{*/
            $res['error'] = 1;
            $res['msg'] = "parm_err";
            $this->outPut($res,$callback);
            return false;

        }/*}}}*/

        $arr['uid'] = $uid;
        $arr['num_iids'] = $num_iids;

        $res = ItemModel::remove($arr,$uid);
//        $res['result'] = true;
//        $res = json_encode($res);


        $this->outPut($res,$callback);
        return false;
    }/*}}}*/

    //import item
    public function listAction()
    {/*{{{*/
        $request = Yaf_Registry::get('request');

        $uid = strval($request->getQuery("uid",'','trim'));
//        $uid = '1406290000286384';
        $this->_view->lt_uid = $uid;
//        $this->_view->lt_uid = $this->lt_uid;

        $fmt = strval($request->getQuery("fmt",'html','trim'));


        $res = ItemModel::itemlist($uid);

        $json = json_encode($res);
        if($fmt == 'web') {
            $this->headJson();
            echo $json;
            return false;
        }

        //导出到视图的变量列表
        $this->_view->datainfo = $res;
        $this->display('list');
        return false;

    }/*}}}*/

}



