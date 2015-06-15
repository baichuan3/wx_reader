<?php
Class Huid
{/*{{{*/
    // cookie settings 
    const HUID_NAME     = '_hb_gid';
    const HUID_DOMAIN   = 'hongbao.so.com';
    const HUID_PATH     = '/';
    const HUID_EXPIRE   = 946080000; //the number of 30 years' seconds
    const HUID_VERSION  = '10';
    const NEW_HUID_KEY  = '_hb_gid_is_new';
    const NEW_HUID_VAL  = 1;
    const HUID_VALID    = 0;
    const HUID_ERR      = 2;
    // about render huid 
    const HUID_SEPARTOR = '_';
    const CHECK_KEY     = '*kH7';
    const CHECK_KEY_POS = 4; //explode the decoded string, we get an array, the 4th element is check_key
    const IP_POS        = 2; 
    const TIMESTAMP_POS = 0; 
    const TIMESTAMP_LEN = 10; 
    const HUID_ARY_CNT  = 5;
    const BINARY_36     = 36; //use 36 binary for getting shorter numbers
    const BINARY_10     = 10;
    // about mcrypt 
    const MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128;
    const MCRYPT_MODE   = MCRYPT_MODE_ECB;
    const MCRYPT_KEY    = 'O$J*GL2K)-^HYU!E'; 
    const ECB_IV        = '1234567812345678'; // ECB mode don't need IV, just need a 16 length string 
    
    /*
     * returns : huid string
    */
    public static function getHuid()
    {/*{{{*/
        header('P3P:CP=.');
        self::writeCookie(self::NEW_HUID_KEY, ''); // expire the is_new flag
        $cur_huid = isset($_COOKIE[self::HUID_NAME]) ? $_COOKIE[self::HUID_NAME] : null;
        if ( false !== self::checkHuid($cur_huid) )
            return $cur_huid;
        $new_huid = self::renderHuid();
        if ( self::writeCookie(self::HUID_NAME, $new_huid, time()+self::HUID_EXPIRE) )
        {
            if ( is_null($cur_huid) ) // write the is_new flag
                self::writeCookie(self::NEW_HUID_KEY, self::NEW_HUID_VAL);
            return $new_huid;
        }
        return '';
    }/*}}}*/


    public static function getHuidToInt()
    {/*{{{*/
        $str_huid = self::getHuid();
        $str = substr(trim($str_huid,"="),5,9);
        //echo $str."\n";
        if(!$str)
            return false;
        $int_str = CreateMid::str2ID($str);
        if(is_numeric($int_str)){
            #$redis = RedisTool::getInstance();
            #$redis->hset(ApiModel::HB_HTABLE,$str_huid,$int_str);
            #$redis->hset(ApiModel::HB_HTABLE,$int_str,$str_huid);
            return $int_str;
        }
        return false;
    }/*}}}*/

    /*
     * returns : huid array, huid string and is_new status
    */
    public static function getHuidDetail()
    {/*{{{*/
        header('P3P:CP=.');
        self::writeCookie(self::NEW_HUID_KEY, '');
        $cur_huid = isset($_COOKIE[self::HUID_NAME]) ? $_COOKIE[self::HUID_NAME] : null;
        $ret      = array('huid' => '', 'is_new' => self::HUID_VALID);
        if ( false !== self::checkHuid($cur_huid) )
        {
            $ret['huid'] = $cur_huid;
            return $ret;
        }
        $new_huid = self::renderHuid();
        if ( self::writeCookie(self::HUID_NAME, $new_huid, time()+self::HUID_EXPIRE) )
        {
            $ret['huid'] = $new_huid;
            if ( is_null($cur_huid) )
            {
                self::writeCookie(self::NEW_HUID_KEY, self::NEW_HUID_VAL);
                $ret['is_new'] = self::NEW_HUID_VAL; // write the is_new flag
            }
            else
                $ret['is_new'] = self::HUID_ERR;

        }
        return $ret;
    }/*}}}*/

    public static function checkCurHuid()
    {/*{{{*/
        return isset($_COOKIE[self::HUID_NAME]) ? self::checkHuid( $_COOKIE[self::HUID_NAME] ) : false;
    }/*}}}*/

    public static function huidIsNew()
    {/*{{{*/
        if ( isset($_COOKIE[self::NEW_HUID_KEY]) && self::NEW_HUID_VAL === $_COOKIE[self::NEW_HUID_KEY] )
            return true;
        else
            return false;
    }/*}}}*/

    public static function getHuidIp($huid)
    {/*{{{*/
        $huid_ary  = self::checkHuid($huid);
        if ( false === $huid_ary )
            return '';
        return long2ip(self::to10($huid_ary[self::IP_POS]));
    }/*}}}*/

    public static function getHuidTime($huid)
    {/*{{{*/
        $huid_ary  = self::checkHuid($huid);
        if ( false === $huid_ary )
            return '';
        return substr(self::to10($huid_ary[self::TIMESTAMP_POS]), 0, self::TIMESTAMP_LEN);
    }/*}}}*/

    private static function renderHuid()
    {/*{{{*/
        $huid_str  =  self::getShortTimeStr() . self::HUID_SEPARTOR . self::to36(ip2long($_SERVER['REMOTE_ADDR'])); 
        $huid_str .= self::HUID_SEPARTOR . self::to36($_SERVER['REMOTE_PORT']) . self::HUID_SEPARTOR . self::CHECK_KEY; 
        return self::HUID_VERSION . self::mcryptEncode($huid_str);
    }/*}}}*/

    public static function checkHuid($huid_str)
    {/*{{{*/
        if ( is_null($huid_str) ) 
            return false;

        $version_len = strlen(self::HUID_VERSION);
        $version = substr($huid_str, 0, $version_len);
        if( 0 !== strcmp(self::HUID_VERSION, $version))
            return false;

        $huid_ary  = explode(self::HUID_SEPARTOR, self::mcryptDecode(substr($huid_str, $version_len)));
        if ( self::HUID_ARY_CNT != count($huid_ary) )
            return false;

        if( 0 !== strcmp(self::CHECK_KEY, $huid_ary[self::CHECK_KEY_POS]) )
            return false;
        return $huid_ary;
    }/*}}}*/

    public static function mcryptEncode($str)
    {/*{{{*/
        return base64_encode(mcrypt_encrypt(self::MCRYPT_CIPHER, self::MCRYPT_KEY, $str, self::MCRYPT_MODE, self::ECB_IV)); 
    }/*}}}*/

    public static function mcryptDecode($str)
    {/*{{{*/
        return trim(mcrypt_decrypt(self::MCRYPT_CIPHER, self::MCRYPT_KEY, base64_decode($str), self::MCRYPT_MODE,self::ECB_IV)); 
    }/*}}}*/

    private static function writeCookie($key, $value, $expire=0)
    {/*{{{*/
        return setcookie($key, $value, $expire, self::HUID_PATH, self::HUID_DOMAIN);
    }/*}}}*/

    private static function getShortTimeStr()
    {/*{{{*/
        $ary = explode(' ', microtime());
        return self::to36($ary[1]) . self::HUID_SEPARTOR . self::to36(substr($ary[0], 2));
    }/*}}}*/

    private static function to36($num)
    {/*{{{*/
        return base_convert($num, self::BINARY_10, self::BINARY_36);
    }/*}}}*/

    private static function to10($num)
    {/*{{{*/
        return base_convert($num, self::BINARY_36, self::BINARY_10);
    }/*}}}*/

    /*{{{*/  /*    ===for test===
    public static function writeHuid($path='/tmp/') 
    {
        $huid  = self::renderHuid(); 
        $fname = $path . 'huid_' . date('mdhi') . '.log';
        error_log($huid . " \n", 3, $fname);
    }

    private static function w($d, $stop=true)
    {
        echo '<pre>';
        var_dump($d);
        echo '</pre>';
        if ($stop)  exit;
    }
    /* /*}}}*/

}/*}}}*/
