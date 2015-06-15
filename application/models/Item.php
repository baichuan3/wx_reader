<?php

class ItemModel {

    public $secretKey = "sandbox9cff350e47eb0b82cf095467f";
    public $url = "http://gw.api.tbsandbox.com/router/rest";

    //1.获取商品列表
    public static function getList()
    {/*{{{*/
        $res = '';
        $url = UtilsBox::getAppConf("app","item_get_onsale") ;
//        $url = sprintf($url);
        $res = @Json_decode(UtilsBox::get($url),1);
        return $res;
    }/*}}}*/

    protected static function generateSign($params)
    {

        $secretKey = "sandbox9cff350e47eb0b82cf095467f";

        ksort($params);

        $stringToBeSigned = secretKey;
        foreach ($params as $k => $v)
        {
            if("@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= secretKey;

        return strtoupper(md5($stringToBeSigned));
    }

    public static function getItem($num_iid) {
        /*{{{*/
        /*
         * $c = new TopClient;
$c->appkey = appkey;
$c->secretKey = secret;
$req = new ItemGetRequest;
$req->setFields("num_iid,title,nick,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,
        ems_fee,has_discount,freight_payer,item_img,wap_desc");
$req->setNumIid(2100505588789);
$resp = $c->execute($req, $sessionKey);


        $requestUrl = "http://gw.api.tbsandbox.com/router/rest?sign=3FFE38C47EE374BFA358ABEB5BE0A778&
        timestamp=2014-06-10+13%3A58%3A51&v=2.0&app_key=1021796779&method=taobao.items.onsale.get&
        partner_id=top-apitools&session=6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786&
        format=json&fields=approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,
        has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id";



        if(null == $session){
            $session = $this->sessionKey;
        }
        if (null != $session)
        {
            $sysParams["session"] = $session;
        }

        		//获取业务参数
		$apiParams = $request->getApiParas();

        		//签名
//		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));
        $sysParams["sign"] = $this->sign;

		//系统参数放入GET请求串
		$requestUrl = $this->gatewayUrl . "?";
		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
            //写成&amp;是为了解决：php自动把&times换成×号
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&amp;";
		}

        if(null != $apiParams["fields"]){
            $requestUrl .= "fields=" . ($apiParams["fields"]) . "&amp;";
        }

         */

//        $arr = array();
//
//        $arr["app_key"] = "1021796779";
//        $arr["session"] = "6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786";
//        $arr["v"] = "2.0";
//        $arr["format"] = "json";
//        $arr["method"] = "taobao.item.get";
//        $arr["timestamp"] = date("Y-m-d H:i:s");
//        $arr["partner_id"] = "top-apitools";
//        $arr['fields'] = "num_iid,title,nick,pic_url,num,valid_thru,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,
//                ems_fee,has_discount,freight_payer,item_img,wap_desc";
//        $arr['sign'] = ItemModel::generateSign($arr);
//
//        $url = "http://gw.api.tbsandbox.com/router/rest";

//        $header = array("uid:$uid");
//        $vars = http_build_query($arr);
//        $res = @Json_decode(UtilsBox::post($url,$vars,array()),1);
//        return $res;

        $res = '';
        $url = UtilsBox::getAppConf("app","item_get_info") ;

//        $timestamp = "2014-06-16+00%3A21%3A13";

        $url .= $num_iid;
        $url = substr($url, 0, -1);



//        $test_url = "http://api.com?val=%s&val2=%s";
//        $test_url = sprintf($test_url,"aaa", "bbb");
//        print_r("testurl=" . $test_url . "<br>");

//        print_r($url . $num_iid . "<br>");
//        $url = sprintf($url,$num_iid);

        print_r("start" . "<br>");
        print_r("num_iid=" . $num_iid . "<br>");
        print_r("url=" . $url . "<br>");
        print_r("end" . "<br>");
        $res = @Json_decode(UtilsBox::get($url),1);
        return $res;
    }/*}}}*/

    //import item
    public static function import($arr,$uid)
    {/*{{{*/
        $res = '';
        $url = UtilsBox::getAppConf("app","item_import_api") ;
//        $header = array("uid:$uid");
        $header = array();
        $vars = http_build_query($arr);
        $res = @Json_decode(UtilsBox::post($url,$vars,$header),1);
        return $res;
    }/*}}}*/

    //remove item
    public static function remove($arr,$uid)
    {/*{{{*/
        $res = '';
        $url = UtilsBox::getAppConf("app","item_del_api") ;
//        $header = array("uid:$uid");
        $header = array();
        $vars = http_build_query($arr);
        $res = @Json_decode(UtilsBox::post($url,$vars,$header),1);
        return $res;
    }/*}}}*/

    //remove item
    public static function itemlist($uid)
    {/*{{{*/
        $res = '';
        $url = UtilsBox::getAppConf("app","item_list_api") ;
        $url = sprintf($url,$uid);

        //not work
//        $url .= $uid;
//        $url = substr($url, 0, -1);

        $res = @Json_decode(UtilsBox::get($url),1);
        return $res;
    }/*}}}*/

}
