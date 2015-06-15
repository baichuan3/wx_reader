<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <!--    <meta content="width=device-width" name="viewport">-->
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">-->
    <!--    <meta content="telephone=no" name="format-detection" />-->

    <link href="/resource/css/base.css" rel="stylesheet">

    <link href="/resource/css/bootstrap.css" rel="stylesheet"/>
    <link href="/resource/css/item.css" rel="stylesheet">


    <script src="/resource/js/jquery.min.js"></script>

    <script>
        $(document).ready(function(){
            var admin_ctl = '<?php echo $admin_ctl;  ?>';
            if("1" != admin_ctl){
                admin_ctl = "0";
            }

            var lt_uid = '<?php echo $lt_uid;  ?>';
            if(!lt_uid){
                lt_uid = "0";
            }

            $('#tb_login_pre').click(function() {
                window.location.href = "/item/tblogin?uid=" + lt_uid + "&admin_ctl=" + admin_ctl;
            });
        });
    </script>

</head>

<body class="i_tb_body">

<div class="i_tb">
    <ul class="list-group">
        <li class="list-group-item i_tb_li">
             <span class="i_tb_e">
            <a id="tb_login_pre" title="淘宝登录" href="javascript:void(0)">
                <img src="/resource/img/oauth_taobao_2.png" width='120' height='33'>
                <span class="i_tb_txt">淘宝登录</span>
            </a>
               </span>
        </li>
    </ul>
</div>

</body>
</html>
