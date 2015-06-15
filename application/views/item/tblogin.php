<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
<!--    <meta content="width=device-width" name="viewport">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">-->
<!--    <meta content="telephone=no" name="format-detection" />-->

</head>

<body>
<?php

session_start();

include_once(APPLICATION_PATH . "/application/views/item/tbconfig.php");

$res = @Json_decode($datainfo,1);
$from = $res['from'];

if(!$from){
    $from = "wap";
}
//$from = "wap";

//保存时间请求参数
$state = time();
//$_SESSION["tb_state"] = $state;
$ret_url = get_auth_url($from);

//保存来路URL，最后将返回
$login_php = APPLICATION_PATH . "/application/views/item/tblogin.php";
$back_url = empty($_GET['tb_callback']) ? $login_php : $_GET['tb_callback'];
//$_SESSION['back_url'] = $back_url;

//print("lt_uid=" . $lt_uid);
//print('<br/>');
//print("ret_url=" . $ret_url);
$_SESSION['lt_uid'] = $lt_uid;

//header("location:".$ret_url);

header("location:".$ret_url);

//页面调用
function get_auth_url($from){
	$url= "https://oauth.taobao.com/authorize";//https://oauth.taobao.com/authorize?response_type=code&client_id=21234035&redirect_uri=http://www.zocms.com/oauthLogin.php&state=1
	$params = array(
				"response_type"	=>	"code",
				"client_id"		=>	tb_appid,
				"redirect_uri"	=>	tb_callback_url,
                "view"          =>  $from,
//				"state"			=>	$state
			);
	foreach($params as $key=>$val){
		$get[] = $key."=".urlencode($val);
	}
	
	return $url."?".join("&", $get);
}
?>

</body>
</html>
