<?php
class TopClientUtil
{
	public $appkey = "1021796779";

	public $secretKey = "sandbox9cff350e47eb0b82cf095467f";

	public $gatewayUrl = "http://gw.api.tbsandbox.com/router/rest";

	public $format = "json";

	public $connectTimeout = 2000;

	public $readTimeout = 2000;

	protected $signMethod = "md5";

	protected $apiVersion = "2.0";

//	protected $sdkVersion = "top-sdk-php-20140606";
    protected $sdkVersion = "top-apitools";

    protected  $sessionKey = "6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786";

    protected  $refreshToken = "6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786";

    protected  $sign = "3FFE38C47EE374BFA358ABEB5BE0A778";

//    protected  $fields = "approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id";

    protected  $timestamp = "2014-06-10 13:58:51";

    //组参函数
    public function createStrParam ($paramArr) {
        $strParam = '';

        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
//                $strParam .= $key.'='.urlencode($val).'&amp;';
                $strParam .= $key.'='.urlencode($val).'&';
            }
        }

        return $strParam;
    }

    public function generateSign($params)
	{
		ksort($params);

		$stringToBeSigned = $this->secretKey;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->secretKey;

		return strtoupper(md5($stringToBeSigned));
	}

    public function execute($paramArr)
    {/*{{{*/
//        $paramArr = array(
//             'app_key' => $this->appkey,
//
//             'session' => $this->sessionKey,
//
//             'method' => 'taobao.items.onsale.get',
//
//             'format' => 'json',
//
//             'v' => '2.0',
//
//             'sign_method'=>'md5',
//
//             'timestamp' => date('Y-m-d H:i:s'),
//
//             'partner_id' => $this->sdkVersion,
//
//             'fields' => 'approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id',
//           );

        //生成签名
        $sign = $this->generateSign($paramArr);

        //组织参数
        $strParam = $this->createStrParam($paramArr);
        $strParam .= 'sign='.$sign;
    //    print_r("strParam=" . $strParam . "<br>" );

        //访问服务
//        $url = 'http://gw.api.tbsandbox.com/router/rest?'
        $url_prefix = strval(UtilsBox::getAppConf("app","taobao_url"));
        $url = $url_prefix.$strParam; //沙箱环境调用地址

//        $url = 'http://gw.api.tbsandbox.com/router/rest?'.$strParam; //沙箱环境调用地址
//        print_r("url=" . $url . "<br>" );

        $result = @Json_decode(UtilsBox::get($url),1);

        return $result;
     }/*}}}*/



     //该方法不能跑通，用来对比上面的方法
//    public function execute($request, $session = null)
//    {/*{{{*/
//        //组装系统参数
//        $sysParams["app_key"] = $this->appkey;
//        $sysParams["v"] = $this->apiVersion;
//        $sysParams["format"] = $this->format;
////        $sysParams["sign_method"] = $this->signMethod;
//        $sysParams["method"] = $request->getApiMethodName();
//        $sysParams["timestamp"] = $this->timestamp;
//        $sysParams["partner_id"] = $this->sdkVersion;
//
//        if(null == $session){
//            $session = $this->sessionKey;
//        }
//        if (null != $session)
//        {
//            $sysParams["session"] = $session;
//        }
//
//        //获取业务参数
//        $apiParams = $request->getApiParas();
//
//        //签名
////		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));
//        $sysParams["sign"] = $this->sign;
//
//        //系统参数放入GET请求串
//        $requestUrl = $this->gatewayUrl . "?";
//        foreach ($sysParams as $sysParamKey => $sysParamValue)
//        {
//            //写成&amp;是为了解决：php自动把&times换成×号
//            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&amp;";
//        }
//
//        if(null != $apiParams["fields"]){
//            $requestUrl .= "fields=" . ($apiParams["fields"]) . "&amp;";
//        }
//        $requestUrl = substr($requestUrl, 0, -1);
//
//        $requestUrl = "http://gw.api.tbsandbox.com/router/rest?sign=3FFE38C47EE374BFA358ABEB5BE0A778&timestamp=2014-06-10+13%3A58%3A51&v=2.0&app_key=1021796779&method=taobao.items.onsale.get&partner_id=top-apitools&session=6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786&format=json&fields=approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id";
////        print_r("requestUrl=" . $requestUrl . "<br>");
//        $topresp = (UtilsBox::get($requestUrl));
////        print_r($topresp . "<br>");
//        return $topresp;
//    }/*}}}*/

        public function refreshAuthToken()
        {/*{{{*/

            $paramArr = '';
            $paramArr = array(
             'appkey' => $this->appkey,

             'sessionkey' => $this->sessionKey,

             'refresh_token' => $this->refreshToken,

//             'sign_method' => 'md5', //this param musts't be added
           );

            print_r("url=" . 'line one' . "<br>" );

                //生成签名
            $sign = $this->generateRefreshSign($paramArr);

            //组织参数
            $strParam = $this->createStrParam($paramArr);
            $strParam .= 'sign='.$sign;
            //    print_r("strParam=" . $strParam . "<br>" );

            //访问服务
//        $url = 'http://gw.api.tbsandbox.com/router/rest?'
//            $url_prefix = strval(UtilsBox::getAppConf("app","taobao_url"));
//            $url = $url_prefix.$strParam; //沙箱环境调用地址

        $url = 'http://container.api.tbsandbox.com/container/refresh?'.$strParam; //沙箱环境调用地址
        print_r("url=" . $url . "<br>" );

            $result = @Json_decode(UtilsBox::get($url),1);

            print_r($result);
//
//            {
//                "sign": "B903AD4704E5E2637DB6CAE3822CB203",
//  "w2_expires_in": "0",
//  "w1_expires_in": "12960000",
//  "re_expires_in": "15546845",
//  "top_session": "610110738d861081d4366502a107896271755f5442867ed2074082786",
//  "expires_in": "12960000",
//  "r2_expires_in": "259200",
//  "refresh_token": "6102307d988530c1e25518019148aa0df08d70a0ab364c02074082786",
//  "r1_expires_in": "12960000"
//}
            return $result;
        }

    //only from refresh token sign generate.
    public function generateRefreshSign($params)
    {
        ksort($params);

        $stringToBeSigned = '';
        foreach ($params as $k => $v)
        {
            if("@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secretKey;

        return strtoupper(md5($stringToBeSigned));
    }

}