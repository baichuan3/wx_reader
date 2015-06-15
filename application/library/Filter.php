<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Àî²©·å
 * Date: 12-12-9
 * Time: ÏÂÎç12:33
 */
class Filter {

    public function _int($input) {
        return intval($input);
    }

    public function _string($input) {
        return ''.$input;
    }

	public function _trim($input, $charList = null) {
		if ($charList === null) return trim($input);
		else return trim($input, $charList);
	}

	public function _range($input, $low, $high) {
		if ($low !== 'x' && $input < intval($low)) return intval($low);
		if ($high !== 'x' && $input > intval($high)) return intval($high);
		return $input;
	}

	public function _enum($input) {
		$args = func_get_args();
		array_shift($args);
		if (!in_array($input, $args)) return false;
		if (!in_array($input, $args)) return $args[0];

		return $input;
	}

	public function _htmlEncode($input) {
		return htmlspecialchars($input);
	}

	public function _toUtf8($str){
		$gbk_str  = @iconv("UTF-8","GBK//IGNORE",$str);
		$utf8_str = @iconv("GBK","UTF-8//IGNORE",$gbk_str);
		if($str == $utf8_str) //is utf-8 
			return $str;
		$change_to_utf8 =  @iconv("GBK","UTF-8//IGNORE",$str);
		if("" != $change_to_utf8)
			return $change_to_utf8;
		return $str;
	} 

	public function _convertEncoding($input, $to, $from = '') {
		if (empty($input)) return $input;

		$auto = array('ASCII', 'UTF-8', 'GBK', 'GB2312', 'JIS');
		$encoding = mb_detect_encoding($input, $auto, true);
		if ($encoding === strtoupper($to)) return $input;

		if ($from === '') {
			return mb_convert_encoding($input, $to, $auto);
		} else {
			return mb_convert_encoding($input, $to, $from);
		}
	}
}
