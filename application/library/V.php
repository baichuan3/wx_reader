<?php
/*
 * 将时间戳转换成阅读友好的文字，规则如下：
 *     1. 小于1分钟的，显示xx秒钟前
 *     2. 小于1小时的，显示xx分钟前
 *     3. 大于等于1小时，并且是今天内的，显示今天H:i
 *     4. 大于等于1小时，并且是昨天内的，显示昨天H:i
 *     5. 比昨天还早的，显示Y-m-d H:i
 * @param $timestamp int 要格式化的时间戳
 * @return String 阅读友好的文字
 */
class V {
	public static function formatDate($timestamp) {
		// 如果时间差 <= 0秒，则修正为1秒钟前
		$delta = max(1, time() - $timestamp);

		// 规则1: 小于1分钟的，显示xx秒钟前
		if ($delta < 60) {
			return $delta . '秒钟前';
		}
		
		// 规则2: 小于1小时的，显示xx分钟前
		if ($delta < 3600) {
			return floor($delta / 60) . '分钟前';
		}

		// 规则3: 大于等于1小时，并且是今天内的，显示今天H:i
		$beginOfToday = strtotime(date('Y-m-d 0:0:0'));
		if ($timestamp >= $beginOfToday) {
			return '今天' . date('H:i', $timestamp);
		}

		// 规则4: 大于等于1小时，并且是昨天内的，显示昨天H:i
		if ($timestamp >= strtotime('-1 day', $beginOfToday)) {
			return '昨天' . date('H:i', $timestamp);
		}

		// 规则5: 比昨天还早的，显示Y-m-d H:i
		return date('Y-m-d H:i', $timestamp);
	}

	public static function e($input) {
		echo $input;
	}

	public static function h($input) {
		// string htmlspecialchars ( string $string [, int $flags = ENT_COMPAT | ENT_HTML401 [, string $encoding = 'UTF-8' [, bool $double_encode = true ]]] )
		if (func_num_args() <= 1) {
			echo htmlspecialchars($input);
			return;
		} else {
			echo call_user_func_array('htmlspecialchars', func_get_args());
		}
	}

	public static function u($input) {
		echo urlencode($input);
	}

	public static function j($input) {
		echo str_replace(array('\\', "'", '"'), array('\\\\', "\'", '\"'), $input);
	}
}
