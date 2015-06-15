<?php
class TopClient
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

    protected  $sign = "3FFE38C47EE374BFA358ABEB5BE0A778";

    protected  $fields = "approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id";

    protected  $timestamp = "2014-06-10 13:58:51";

	protected function generateSign($params)
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

	public function curl($url, $postFields = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}
		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields))
		{

            print('top client execute flag<br>');
            print_r("here" . '<br>');
            print('top client execute flag<br>');
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

//	public function execute($request, $session = null)
//	{
//		//组装系统参数
//		$sysParams["app_key"] = $this->appkey;
//		$sysParams["v"] = $this->apiVersion;
//		$sysParams["format"] = $this->format;
//		$sysParams["sign_method"] = $this->signMethod;
//		$sysParams["method"] = $request->getApiMethodName();
//		$sysParams["timestamp"] = date("Y-m-d H:i:s");
//		$sysParams["partner_id"] = $this->sdkVersion;
//
//        if(null == $session){
//            $session = $this->sessionKey;
//        }
//		if (null != $session)
//		{
//			$sysParams["session"] = $session;
//		}
//
////        print('top client execute flag<br>');
////        print_r("here" . '<br>');
////        print('top client execute flag<br>');
//
//		//获取业务参数
//		$apiParams = $request->getApiParas();
//
//		//签名
//		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));
////        print('top client execute flag<br>');
////        print_r($sysParams["sign"] . '<br>');
////        print('top client execute flag<br>');
//
//		//系统参数放入GET请求串
//		$requestUrl = $this->gatewayUrl . "?";
//		foreach ($sysParams as $sysParamKey => $sysParamValue)
//		{
//			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
//		}
//		$requestUrl = substr($requestUrl, 0, -1);
//
//		//发起HTTP请求
//		try
//		{
////            print('top client execute flag<br>');
////            print_r("here" . '<br>');
////            print('top client execute flag<br>');
//			$resp = $this->curl($requestUrl, $apiParams);
////            print('top client execute flag<br>');
////            print_r($resp . '<br>');
////            print('top client execute flag<br>');
//		}
//		catch (Exception $e)
//		{
//			return $e->getMessage();
//		}
//
//		//解析TOP返回结果
//		$respWellFormed = false;
//		if ("json" == $this->format)
//		{
//			$respObject = json_decode($resp);
//			if (null !== $respObject)
//			{
//				$respWellFormed = true;
//				foreach ($respObject as $propKey => $propValue)
//				{
//					$respObject = $propValue;
//				}
//			}
//		}
//		else if("xml" == $this->format)
//		{
//			$respObject = @simplexml_load_string($resp);
//			if (false !== $respObject)
//			{
//				$respWellFormed = true;
//			}
//		}
//
//		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
//		if (false === $respWellFormed)
//		{
//			return "HTTP_RESPONSE_NOT_WELL_FORMED";
//		}
//
//		//如果TOP返回了错误码，记录到业务错误日志中
//		if (isset($respObject->code))
//		{
//
//		}
//		return $respObject;
//	}


    public function execute($request, $session = null)
    {
        //组装系统参数
        $sysParams["app_key"] = $this->appkey;
        $sysParams["v"] = $this->apiVersion;
        $sysParams["format"] = $this->format;
//        $sysParams["sign_method"] = $this->signMethod;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = $this->timestamp;
        $sysParams["partner_id"] = $this->sdkVersion;

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
		$requestUrl = substr($requestUrl, 0, -1);

        $requestUrl = "http://gw.api.tbsandbox.com/router/rest?sign=3FFE38C47EE374BFA358ABEB5BE0A778&timestamp=2014-06-10+13%3A58%3A51&v=2.0&app_key=1021796779&method=taobao.items.onsale.get&partner_id=top-apitools&session=6102203ebb11fa7da4a54da32869a7f53a896176eaa89cb2074082786&format=json&fields=approve_status,num_iid,title,nick,type,cid,pic_url,num,props,valid_thru,list_time,price,has_discount,has_invoice,has_warranty,has_showcase,modified,delist_time,postage_id,seller_cids,outer_id";
//        print_r("requestUrl=" . $requestUrl . "<br>");
        $topresp = (UtilsBox::get($requestUrl));
//        print_r($topresp . "<br>");
        return $topresp;
    }

}