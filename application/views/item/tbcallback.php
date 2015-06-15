<?php
header("Content-type:text/html; charset=UTF-8;");
include_once(APPLICATION_PATH . "/application/views/item/tbconfig.php");
session_start();

$res = @Json_decode($datainfo,1);
$code = $res['code'];
//foreach ($datainfo['weitravel_list'] as $item

if( !isset($code)||empty($code) )
{
	echo "<span style='font-size:12px;line-height:24px;'>请求非法或超时!&nbsp;&nbsp;<a href='/item/tblogin'>返回首页</a></span>";
	exit;
}
else
{
//	//参数验证
//	if( $_GET["state"]!=$_SESSION["tb_state"] )
//	{
//		//echo "网站获取用于第三方应用防止CSRF攻击失败。";
//		echo "<span style='font-size:12px;line-height:24px;'>请求非法或超时!&nbsp;&nbsp;<a href='/item/tblogin'>返回首页</a></span>";
//		exit;
//	}
	
//	$code = $_GET["code"]; // 通过访问https://oauth.taobao.com/authorize获取code
	                                                     
	// 请求参数
	$postfields = array (
			'grant_type' => "authorization_code",
			'client_id' => tb_appid,
			'client_secret' => tb_appkey,
			'code' => $code,
			'redirect_uri' => tb_callback_url,
            'view'       =>  "wap",
	);
	
	$url = 'https://oauth.taobao.com/token';
	
	$token = json_decode ( curl ( $url, $postfields ) );
	$access_token = $token->access_token;
	$_SESSION['tb_access_token'] = $access_token;

	//保存用户信息
	/*$user_info['user_id'] = $token -> taobao_user_id;
	$user_info['user_name'] = $token -> taobao_user_nick;
	$user_info['user_domain'] = "";
	$user_info['user_profile'] = "";
	$user_info['user_token'] = $token -> access_token;
	$user_info['user_type'] = "taobao";
	$_SESSION['user_info'] = $user_info;*/
	
	$uname = $token -> taobao_user_nick;
	$tb_uid = $token -> taobao_user_id;
	$sign = md5($uname.$openid.substr($openid, 2, 4));

//    print_r('lt_uid=' . $lt_uid);
    $lt_uid = $_SESSION['lt_uid'];

//    setcookie("lt2tb_uid", $tb_uid, time()+31536000000);
//    setcookie("lt_uid2uname", $uname, time()+31536000000);

    setcookie($lt_uid."tb_uid", $tb_uid, time()+31536000000);
    setcookie($tb_uid."tb_uname", $uname, time()+31536000000);
    setcookie($tb_uid."tb_token", $access_token, time()+31536000000);

//    print($uname);
//    print("</br>");
//    print($tb_uid);
//    print("</br>");
//    print("</br>");
//    print("after" . $lt_uid);
//    $lt_uid = "10000";
			
	$go_url = "/item/import?uid=".$lt_uid."&admin_ctl=1&tb_uid=".$tb_uid;

//    print_r('go_url=' . $go_url);
	header("location:".$go_url);
}
 
 //POST请求函数
function curl($url, $postFields = null)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_FAILONERROR, false );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	
	if (is_array ( $postFields ) && 0 < count ( $postFields )) {
		$postBodyString = "";
		foreach ( $postFields as $k => $v ) {
			$postBodyString .= "$k=" . urlencode ( $v ) . "&";
		}
		unset ( $k, $v );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, substr ( $postBodyString, 0, - 1 ) );
	}
	$reponse = curl_exec ( $ch );
	if (curl_errno ( $ch )) {
		throw new Exception ( curl_error ( $ch ), 0 );
	} else {
		$httpStatusCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		if (200 !== $httpStatusCode) {
			throw new Exception ( $reponse, $httpStatusCode );
		}
	}
	curl_close ( $ch );
	return $reponse;
}
 
?>
