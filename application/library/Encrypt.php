<?php
class Encrypt { 
    const ID_CRYPT_KEY   = 'hongbao360encodekeyzpw';
    static $ID_CRYPT_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    private static $key = '#:DHiE\'Q.@X]G:Pz\'vZazpw%&"SJXi85\'.h5ZY9X7#hj5p/k';

    public static function enc($str) 
    {/*{{{*/
        return sha1($str . self::$key);
    }           /*}}}*/

    static public function idEncode($txt)
    {/*{{{*/
        $array_1  = array(0,1,2,3,4,5,6,7,8,9);
        $array_2  = array('a','b','c','d','e','f','g','h','i','j');
        $len      = strlen($txt);
        if( $len<10)
        {
            $buquan = substr(md5($txt.self::ID_CRYPT_KEY), 0, (10-$len));
            $buquan = str_replace($array_1,$array_2,$buquan);
        }
        else
        {
            $buquan = '';
        }
        $txt = $buquan.$txt;
        $nh = 11;
        $ch = self::$ID_CRYPT_CHARS[$nh];
        $mdKey = md5(self::ID_CRYPT_KEY.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);

        $txt = str_replace('=','',base64_encode($txt));
        $tmp = '';
        $i   = 0;
        $j   = 0;
        $k   = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh+strpos(self::$ID_CRYPT_CHARS,$txt[$i])+ord($mdKey[$k++]))%61;
            $tmp .= self::$ID_CRYPT_CHARS[$j];
        }
        return $tmp;
    }/*}}}*/

    static public function idDecode($txt)
    {/*{{{*/
        $t_txt = $txt;
        $txt   = 'L'.$txt;
        $ch    = $txt[0];
        $nh    = strpos(self::$ID_CRYPT_CHARS,$ch);
        $mdKey = md5(self::ID_CRYPT_KEY.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt   = substr($txt,1);
        $tmp   = '';
        $i     = 0;
        $j     = 0;
        $k     = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos(self::$ID_CRYPT_CHARS,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=61;
            $tmp .= self::$ID_CRYPT_CHARS[$j];
        }
        $code =  intval(preg_replace('/[a-z]/i','',base64_decode($tmp)));
        if( self::idEncode($code) == $t_txt )
        {
            return $code;
        }
        return 0;
    }/*}}}*/

    static public function run($txt,$op='E',$lenth=0)
    {/*{{{*/
        $str_len = strlen($txt);
        if($str_len < $lenth)
            return false;
        
        if($op == 'E'){
            $sub = str_split($txt,$lenth);
            $en = self::idEncode($sub[0]);
            if(isset($sub[1]))
                return $en.$sub[1];
            else
                return $en;
        }else if($op == 'D'){
            $tem = str_split($txt,14);
            $dn = self::idDecode($tem[0]);
            if(isset($tem[1]))
                return $dn.$tem[1];
            else
                return $dn;
        }

    }/*}}}*/

}               



