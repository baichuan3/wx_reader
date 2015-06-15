<?php
class UtilsBox
{/*{{{*/
    const DATE_TOKEN = '-';
    const TIME_TOKEN = ':';
    const IP_TOKEN   = '.';

    static public function call($url,$timeout=20)
    {/*{{{*/
        $ret = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $r = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);  
        if($status == 200)
            $ret = $r;
        return $ret; 
    }/*}}}*/
    static public function post($url,$vars,$header=array(),$timeout=20)
    {/*{{{*/
        $ret = '';
        $ch = curl_init();
        if($header){
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header); 
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);

        if(substr($url, 0, 5) == 'https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $r = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);  
        if($status == 200)
            $ret = $r;
        return $ret; 
    }/*}}}*/
    static public function get($url,$timeout=20)
    {/*{{{*/
        return self::call($url,$timeout);
    }/*}}}*/
    static public function getAppConf($type="",$name="")
    {/*{{{*/
        $res = '';
        if($type && $name)
            $res =  Yaf_Registry::get("config")->$type->$name;
        return $res;
    }/*}}}*/
    static public function getMem_confs()
    {/*{{{*/
        $str_mes = self::getAppConf("application","memcacheconfig");
        $res = array();
        if($str_mes){
            $arr = explode('|',$str_mes);
            if($arr){
                foreach($arr as $mes){
                    if($mes){
                        $r = array();
                        $mes_arr = explode(':',$mes);
                        $r['host'] = $mes_arr[0];
                        $r['port'] = $mes_arr[1];
                        $res[] = $r;
                    }
                }
            }
        }
        return $res;
    }   /*}}}*/

    static public function ins_key($params)
    {/*{{{*/
        return md5(self::getAppConf("application",'work_key')."|".$params);
    }/*}}}*/
    public function safeShow($string)
    {/*{{{*/
        return $string ? htmlentities($string, ENT_QUOTES,'UTF-8') : "";
    }/*}}}*/

    static function replaceString($sample,$string)
    {/*{{{*/
        if(strpos($string,$sample) !== false)
            $string = str_replace($sample,"",$string);
        return $string;
    }/*}}}*/

    static function dhtmlspecialchars($string)
    {/*{{{*/
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = self::dhtmlspecialchars($val);
            }
        } else {
            $string = htmlentities($string, ENT_QUOTES,'UTF-8');
        }
        return $string;
    }/*}}}*/

    static function cutstring($string, $length, $dot = '...',$charset="utf-8")
    {/*{{{*/
        if(strlen($string)<= $length)
        {
            return $string;
        }
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
        $strcut = '';
        if(strtolower($charset) == 'utf-8')
        {
            $n = $tn = $noc = 0;
            while($n < strlen($string))
            {
                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1; $n++; $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2; $n += 2; $noc += 2;
                } elseif(224 <= $t && $t <= 239) {
                    $tn = 3; $n += 3; $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4; $n += 4; $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5; $n += 5; $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6; $n += 6; $noc += 2;
                } else {
                    $n++;
                }

                if($noc >= $length) {
                    break;
                }
            }
            if($noc > $length)
            {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        }
        else
        {
            for($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }
        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        return $strcut.$dot;
    }/*}}}*/

    static function cutstr($string, $length = 40, $etc = '...') 
    {/*{{{*/
        if(mb_strwidth($string, "UTF-8") <= $length){
            return $string;
        }   
        for($i = 0; $i < $length; $i++){
            if(ord(substr($string, 0, 1)) > 127){
                $i++;
                if($i < $length){
                    $newstr[] = substr($string, 0, 3); 
                    $string = substr($string, 3); 
                }   
            }else{
                $newstr[] = substr($string, 0, 1); 
                $string = substr($string, 1); 
            }   
        }   
        return join($newstr) . $etc;
    } 
   /*}}}*/


    //解析当月日历，每周有哪几号
    public static function composeCalendar()
    {/*{{{*/
        $ret = array();

        $numberDays = date('t');
        for($i=0;$i<$numberDays;$i++)
            $out[$i] = $i+1;

        $weekday1 = date('w',strtotime(date("Y-m-1")));
        for($i=0;$i<$weekday1;$i++)
            array_unshift($out,"");

        foreach($out as $k=>$v)
        {
            $row = intval($k/7);
            $col = $k%7;
            $ret[$row][$col] = $v;
        }

        return $ret;
    }/*}}}*/

    static public function toGbk($s)
    {/*{{{*/
        if (is_array($s))
        {
            foreach ($s as $k => $v)
            {
                $s[$k] = iconv('utf-8', 'gbk', $v);
            }
            return $s;
        }
        return iconv('utf-8', 'gbk', $s);
    }/*}}}*/

    static public function toUtf8($s)
    {/*{{{*/
        if (is_array($s))
        {
            $d = array();
            foreach ($s as $k => $v)
            {
                $d[iconv('gbk', 'utf-8', $k)] = iconv('gbk', 'utf-8', $v);
            }
            return $d;
        }
        return iconv('gbk', 'utf-8', $s);
    }/*}}}*/

    static public function isInt($n)
    {/*{{{*/
        if (is_numeric($n) && false === strpos($n, '.'))
        {
            return true;
        }
        return false;
    }/*}}}*/

    static public function isTel($tel)
    {/*{{{*/    
        //$regex = '/^1((3[0-9])|(4[57])|(5[012356789])|(8[012356789]))[0-9]{8}$/';
        $regex = '/^\d{8,11}$/';
        if (preg_match($regex, $tel) == 0) {
            return false;          
        }       
        return true;

    }/*}}}*/ 

    static public function isPositiveInt($n)
    {/*{{{*/
        if (self::isInt($n) && 0 < $n)
        {
            return true;
        }
        return false;
    }/*}}}*/

    static public function checkEmail($email)
    {/*{{{*/
        $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
        return preg_match($pregEmail,$email);  
    }/*}}}*/

    static public function getImgUrl($url,$online=0)
    {/*{{{*/
        return "http://123.126.42.25".$url."&udversion=301";
        if($online)
            return "http://123.126.42.25".$url."&udversion=301";
        else
            return "http://180.149.138.87".$url."&udversion=301";
    }/*}}}*/

    static public function isNonnegativeInt($n)
    {/*{{{*/
        if (self::isInt($n) && 0 <= $n)
        {
            return true;
        }
        return false;
    }/*}}}*/

    static public function isValidDate($date)
    {/*{{{*/
        $year  = intval(strtok($date, self::DATE_TOKEN));
        $month = intval(strtok(self::DATE_TOKEN));
        $day   = intval(strtok(self::DATE_TOKEN));
        if (false !== strtok(self::DATE_TOKEN))
        {
            return false;
        }
        return checkdate($month, $day, $year);
    }/*}}}*/

    static public function isValidTime($time)
    {/*{{{*/
        $hour   = intval(strtok($time, self::TIME_TOKEN));
        $minute = intval(strtok(self::TIME_TOKEN));
        $second = intval(strtok(self::TIME_TOKEN));
        if (false !== strtok(self::TIME_TOKEN))
        {
            return false;
        }
    if (0 > $hour || 23 < $hour)
    {
        return false;
    }
    if (0 > $minute || 59 < $minute)
    {
        return false;
    }
    if (0 > $second || 59 < $second)
    {
        return false;
    }
    return true;
    }/*}}}*/

    static public function isValidDateTime($dateTime)
    {/*{{{*/
        if (!self::isValidDate(substr($dateTime, 0, 10)))
        {
            return false;
        }
        if (!self::isValidTime(substr($dateTime, 11)))
        {
            return false;
        }
        return true;
    }/*}}}*/

    static public function isValidIp($ip)
    {/*{{{*/
        $tok = array();
        $toker = strtok($ip, '.');
        while ($toker !== false) 
        {
            $tok[] = $toker;
            $toker = strtok('.');
        }
        if (count($tok) != 4)
        {
            return false;
        }
        foreach ($tok as $v)
        {
            if (!is_numeric($v) || $v > 255 || $v < 0)
            {
                return false;
            }
        }
        return true;
    }/*}}}*/

    static public function isEarlyThan($firstDate, $lastDate)
    {/*{{{*/
        if (!self::isValidDate($firstDate) || !self::isValidDate($lastDate))
        {
            return false;
        }
        $firstDate = strtotime($firstDate);
        $lastDate  = strtotime($lastDate);
        if ( !$firstDate || !$lastDate)
        {
            return false;
        }
        if ($lastDate < $firstDate)
        {
            return false;
        }
        return true;
    }/*}}}*/

    public static function fetchFile($path)
    {/*{{{*/
        $filelist = array();
        $currentfilelist = @scandir($path);
        if (is_array($currentfilelist))
        {
            foreach ($currentfilelist as $file)
            {
                if ($file == "." || $file == ".." || $file == ".svn")
                {
                    continue;
                }
                $file = "$path/$file";

                if (is_dir($file))
                {
                    foreach(self::fetchFile($file) as $tmpFile)
                    {
                        $filelist[] = $tmpFile;
                    }
                    continue;
                } 

                $filelist[]= $file;
            }
        }
        return $filelist; 
    }/*}}}*/

    static public function getFilesBySuffix($filelist,$suffix)
    {/*{{{*/
        if (count($filelist)>0)
        {
            $newfilelist = array();
            foreach($filelist as $file)
            {
                if (self::getFileSuffix($file) == $suffix)
                {
                    $newfilelist[] = $file;
                }
            }
            if (count($newfilelist) > 0) return $newfilelist;
            return array();
        }
        return array();

    }/*}}}*/

    static public function getFileSuffix($fileName)
    {/*{{{*/
        $pointPos = strrpos($fileName,".");
        if ($pointPos)
        {
            return substr($fileName,$pointPos+1,strlen($fileName) - $pointPos);
        }
    }/*}}}*/

    static public function strToAry($str)
    {/*{{{*/
        $last = null;
        $ary = array();
        $str = unpack('C*', $str);
        $len = count($str) + 1;
        for($i = 1; $i < $len; $i ++)
        {
            $letter = $str[$i];
            if ($last === null)
            {
                if ($letter > 0x80 && $letter < 0xff)
                {
                    $last = $letter;
                    continue;
                }
                $ary[] = pack('c', $letter);
            }
            else if ($last > 0x80 && $last < 0xff)
            {
                if ($letter > 0x39 && $letter < 0x7f)
                {
                    $ary[] = pack('c2', $last,$letter);
                    $last = null;
                }
                else
                {
                    $ary[] = $last;
                    $ary[] = $letter;
                    $last = null;
                }
            }
        }
        return $ary;
    }/*}}}*/

}/*}}}*/
