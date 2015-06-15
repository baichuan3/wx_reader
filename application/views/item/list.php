<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>橱窗管理 - 天天抽奖汇</title>
    <meta name="keywords" content="微博抽奖 抽奖 商品 橱窗 ">
    <meta name="description" content="天天抽奖汇 - 最新最火|最好玩的微博抽奖App">
    <meta content="width=device-width" name="viewport">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta content="telephone=no" name="format-detection" />

<!--    <link href="http://static.qhimg.com/!c957e3af/reset.css" rel="stylesheet">-->
    <link href="/resource/css/base.css" rel="stylesheet">
    <link href="/resource/css/bootstrap.css" rel="stylesheet"/>
    <link href="/resource/css/custom.css?v=1.0.2" rel="stylesheet">
    <link href="/resource/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="/resource/css/item.css" rel="stylesheet">

    <script src="/resource/js/jquery.min.js"></script>
    <script src="/resource/js/bootstrap.min.js"></script>
    <script src="/resource/iCheck/icheck.js"></script>
    <script src="/resource/js/custom.min.js"></script>



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

            var edit_mode = '<?php echo $edit_mode;  ?>';
            if(!edit_mode){
                edit_mode = "0";
            }
            if(edit_mode > 0){
                $("#diary-list").addClass('g-clearfix');
            }
//            alert(admin_ctl);
//            alert(lt_uid);

            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $('.nav.navbar-nav li a').click(function() {
                var titleId = $(this).attr('data-title');

                if(titleId == '1') {
                    location.href = 'list?admin_ctl=' + admin_ctl + '&uid=' + lt_uid;
                } else if(titleId == '2') {
                    location.href = 'list?admin_ctl=' + admin_ctl + '&uid=' + lt_uid + '&edit_mode=1';
                } else if(titleId == '3') {
                    location.href = 'import?admin_ctl=' + admin_ctl + '&uid=' + lt_uid;
                }
            });

            var li_num = $("#diary-list li").length;
//            alert("li_num=" + li_num);
            if(li_num > 0){
                $('#diary-wrapper').show();
                $('#bottom-navbar').show();
                $('#diary-hit').hide();
                $('#diary-hit-admin').hide();
            }else{
                $('#diary-wrapper').hide();
                $('#bottom-navbar').hide();
                if(admin_ctl > 0){
                    $('#diary-hit').hide();
                    $('#diary-hit-admin').show();
                }else{
                    $('#diary-hit').show();
                    $('#diary-hit-admin').hide();
                }
            }

            //$('#btn-primary-p').click(function() {
            $('#btn-primary-p').click(function() {
                var data = {};

                var ids = "";
                $('#import_ck:checked').each(function(){ //由于复选框一般选中的是多个,所以可以循环输出选中的值
//                    alert($(this).parent().parent().parent().prev().attr('href'));
//                    var tmpid = $(this).parent().parent().parent().prev().attr('href');
                    var tmpid = $(this).parent().next().attr('value');
//                    alert(tmpid);

                    if(ids.length > 0){
                        ids = ids + ",";
                    }
                    ids = ids + tmpid;
                });

//                alert(ids);
                data["num_iids"] = ids;

                ///item/detail?num_iid=2100506421109
                for (x in data)
                {
//                    alert(data[x]);
                }

                $.ajax({
                    url: '/item/removeimport?uid=' + lt_uid + '&admin_ctl=' + admin_ctl + '&num_iids=' + ids,
                    type: 'get',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.result) {
                            alert('移除成功');
                            location.reload();
                        } else {
                             alert('移除失败');
                      }
                    }
                });
            });

        });
    </script>
</head>
<body>

    <nav class="navbar navbar-inverse" role="banner">

        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".bs-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <p href="sinaweibo://userinfo?uid=2758197137" class="navbar-brand">橱窗管理</p>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                <ul class="nav navbar-nav ">
                    <li>
                        <a href="javascript:void 0" data-title='1'>橱窗展示</a>
                    </li>
                    <?php if ($admin_ctl == 1) { ?>
                        <li>
                            <a href="javascript:void 0" data-title='2'>商品管理</a>
                        </li>
                        <li>
                            <a href="javascript:void 0" data-title='3'>商品导入</a>
                        </li>
                    <?php  } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div id="diary-wrapper">
        <ul  id="diary-list" class="i_ul">
            <?php

             $i = 0;
             foreach ($datainfo['items'] as $item) {
                if($i++ > 7) {
                    break;
                }
             ?>
             <li class="i_li left">
                 <a href="/item/detail?num_iid=<?php echo $item['num_iid']; ?>">
                    <img src="<?php echo $item['pic_url']; ?>" width='135' height='135'>
                    <p class="i_txt"><?php echo $item['title']; ?></p>
                    <p class="i_pri">￥<?php echo $item['price']; ?></p>
                 </a>

                 <?php if ($admin_ctl == 0) { ?>
<!--                         <p>-->
<!--                             <a href="" class="btn btn-primary-red">立即购买</a>-->
<!--                         </p>-->
                 <?php  } ?>

                 <?php if ($edit_mode == 1) { ?>
                     <div class="mask" id="amdin_ctl">
                         <div class="mask_middle">
                             <input type="checkbox" id="import_ck" style="margin:5px">
                             <input type="hidden" id="import_iid" value="<?php echo $item['num_iid']; ?>">
                             <p class="mask_text">移除商品</p>
                         </div>
                     </div>
                 <?php  } ?>
             </li>

            <?php } ?>
        </ul>
    </div>

    <div id="diary-hit" class="i_hit">
        <p class="i_hit_txt">橱窗里还没有商品哦.</p>
    </div>
    <div id="diary-hit-admin" class="i_hit">
        <p class="i_hit_txt">您的橱窗里还没有商品哦.</p>
        <p class="i_hit_txt i_hit_long_txt">点击右上角菜单->商品导入，登录淘宝后，就可以把淘宝店铺的商品导入到橱窗中展示.</p>
        <p class="i_hit_txt i_hit_long_txt">橱窗里的商品，同时会显示在抽奖微博页面，快去导入吧.</p>
    </div>

    <?php if ($edit_mode == 1) { ?>
        <nav id="bottom-navbar" class="navbar navbar-default navbar-fixed-bottom" role="navigation">
            <button type="button" id="btn-primary-p" class="btn btn-default btn-lg btn-block navbar-btn">移除</button>
        </nav>
    <?php } ?>

</body>
</html>
