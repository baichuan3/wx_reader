<?php
class HelperModel{
    /**前端打点统计页面加载时间
     *
     */
    public static function feLog($pos='head',$name){/*{{{*/
        $result = "<script>";
        $felog = '';

        if($pos=='head'){
            $felog = "var FELOG = {};";
        }
        $felog .= "FELOG['".$name."'] = new Date().getTime();";
        $result .= $felog;
        $result .= "</script>";
        return $result;
    }/*}}}*/

    /**基础curl实现，支持毫秒级
     *
     */
    public static function curl($url,$timeout=1100,$connect_timeout=2000){
        $b = self::microtime_float() ;
        $new_ch = curl_init();
        curl_setopt($new_ch,CURLOPT_URL, $url );
        curl_setopt($new_ch,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($new_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.)");
        //curl_setopt($new_ch,CURLOPT_TIMEOUT,   $timeout);
        curl_setopt($new_ch,CURLOPT_TIMEOUT_MS,   $timeout);
        curl_setopt($new_ch,CURLOPT_CONNECTTIMEOUT_MS,   $connect_timeout);
        curl_setopt($new_ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($new_ch);
        $e = self::microtime_float() ;
        qLogInfo("CloudSearch.QSSWEB.HONGBAO.CURL_TIME","used:".($e-$b)."\turl:".$url."\tsettime:".$timeout."\tsetconnecttime:".$connect_timeout);
        if(curl_errno($new_ch))
        {
            qLogInfo("CloudSearch.QSSWEB.HONGBAO.CURL_BAD", "curl_errno:".curl_errno($new_ch)."\tcurl_error:". curl_error($new_ch)."\tused:".($e-$b)."s\turl:".$url);
        }
        curl_close($new_ch);
        return $result;
    }

    /**基础curl实现，支持毫秒级
     *
     */
    public static function curl_post($url,$args,$timeout=1000,$connect_timeout=300){
        $b = self::microtime_float() ;
        $new_ch = curl_init();
        curl_setopt($new_ch,CURLOPT_URL, $url );
        curl_setopt($new_ch,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($new_ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.)");
        //curl_setopt($new_ch,CURLOPT_TIMEOUT,   $timeout);
        curl_setopt($new_ch,CURLOPT_TIMEOUT_MS,   $timeout);
        curl_setopt($new_ch,CURLOPT_CONNECTTIMEOUT_MS,   $connect_timeout);
        curl_setopt($new_ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($new_ch,CURLOPT_POST, true);
        curl_setopt($new_ch,CURLOPT_POSTFIELDS, $args);
        $result = curl_exec($new_ch);
        $e = self::microtime_float() ;
        qLogInfo("CloudSearch.QSSWEB.CULR_TIME","used:".($e-$b)."\turl:".$url."\tsettime:".$timeout."\tsetconnecttime:".$connect_timeout);
        if(curl_errno($new_ch))
        {
            qLogInfo("CloudSearch.QSSWEB.CULR_BAD", "curl_errno:".curl_errno($new_ch)."\tcurl_error:". curl_error($new_ch)."\tused:".($e-$b)."s\turl:".$url);
        }
        curl_close($new_ch);
        return $result;
    }

    /** 去除javascript相关标签和事件
     *
     */
    public function strip_html($htmlcode){/*{{{*/
        $htmlcode = trim($htmlcode);	
        $search = array(
            "'<script[^>]*?>.*?</script>'si",//过滤SCRIPT标记
            "'<iframe[^>]*?>.*?</iframe>'si" //过滤IFRAME标记
        ); 
        $replace = "";

        $aDisabledAttributes = array(
            'onabort', 'onactivate', 'onafterprint', 
            'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 
            'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 
            'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 
            'onbeforeupdate', 'onblur', 'onbounce', 
            'oncellchange', 'onchange', 'onclick', 
            'oncontextmenu', 'oncontrolselect', 'oncopy', 
            'oncut', 'ondataavaible', 'ondatasetchanged', 
            'ondatasetcomplete', 'ondblclick', 'ondeactivate', 
            'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 
            'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 
            'onerror', 'onerrorupdate', 'onfilterupdate', 
            'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 
            'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 
            'onlayoutcomplete', 'onload', 'onlosecapture', 
            'onmousedown', 'onmouseenter', 'onmouseleave',
            'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 
            'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 
            'onpaste', 'onpropertychange', 'onreadystatechange', 
            'onreset', 'onresize', 'onresizeend', 'onresizestart', 
            'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 
            'onselect', 'onselectionchange', 'onselectstart', 
            'onstart', 'onstop', 'onsubmit', 'onunload'
        );

        $htmlcode = preg_replace($search,$replace,$htmlcode);
        $aDisabledAttributes = @implode('|', $aDisabledAttributes);
        $htmlcode_new = preg_replace('/<(.*?)>/ie', 
            "'<' . preg_replace(array(
                '/text\/javascript/i', 
                '/(" . $aDisabledAttributes . ")[ \\t\\n]*/i'), 
        array('text\/html', 'data-evt'), stripslashes('\\1')) . '>'", $htmlcode );
        if(!$htmlcode_new)
            return $htmlcode;
        return $htmlcode_new;
    }/*}}}*/

    /**是否全英文字符或数字
     *
     */
    public static function isAllLetter($str){/*{{{*/
        for($i=0;$i<strlen($str);$i++)
        {
           if( ord($str[$i]) > 127) return false;
        }
        return true;
    }/*}}}*/


    /** 处理query,去除换行符，截取120个字符
     *
     */
    public static function formatQuery($query){
        $query = self::trimBr(trim($query));
        return self::iSubstr($query,120);
    }

    public static function iSubStr($str, $len) {   
        $i = 0;
        $tlen = 0;
        $tstr = '';
        while ($tlen < $len) {
            $chr = mb_substr($str, $i, 1, 'utf8');   
            $chrLen = ord($chr) > 127 ? 3 : 1;
            if ($tlen + $chrLen > $len) break;
            $tstr .= $chr;
            $tlen += $chrLen;
            $i++;
        }
        return $tstr;   
    }

    public static function getRealIP(){
        $realip = '0.0.0.0';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;

                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
        return $realip;
    }

	//根据ip获取地址位置信息，调用公司的ip查询接口
	public static function getAddrByIp($ip){
		if(empty($ip)){
			return false;
		}
		//api config
		$src = 'so_news';
		$prikey='a24a7151557b91035858d164ddbe2e69';
		$r = rand();

		$guid = md5('ip='.$ip.'r='.$r.'src='.$src.$prikey);
		//api get 
		$api_ip = 'http://api.ip.360.cn/?src='.$src.'&r='.$r.'&ip='.$ip.'&guid='.$guid;

		$result = @self::curl($api_ip, 1000);
		if($result){
			$result = @json_decode($result, true);
			if(isset($result['errno']) && $result['errno'] === 0){
				return $result;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 判断IP是否内网
	 * @param string $ip
	 * @return boolean
	 */
	public static function allow_internal_ip($ip='') {
	    //这里填上内网IP模式
	    $allow_internal_ip = array('/^10\.18\..+/');
	    if ($ip == "") return false;
	    @reset($allow_internal_ip);
	    while(list($k,$v) = each($allow_internal_ip)) {
	        if(preg_match($v,$ip)) return true;
	    }
	    return false;
	}
    public static function formatDate($timestamp) 
    {/*{{{*/
        // 如果时间差 <= 0秒，则修正为1秒钟前}
        $delta = max(1, time() - $timestamp);

        // 规则1: 小于1分钟的，显示xx秒钟前
        if ($delta < 60) {
            return $delta . '秒钟前';
        }

        // 规则2: 小于1小时的，显示xx分钟前
        if ($delta < 3600) {
            return floor($delta / 60) . '分钟前';
        }

        // 规则2.1: 大于1小时小于12小时，显示xx小时前
        if ($delta < 3600*24) {
            return floor($delta / 3600) . '小时前';
        }

        // 规则5: 比昨天还早的，显示Y-m-d H:i
        return date('Y年n月j日', $timestamp);
    }/*}}}*/
}
