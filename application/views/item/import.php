<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>商品导入 - 天天抽奖汇</title>
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
            }else{
                $('#diary-wrapper').hide();
                $('#bottom-navbar').hide();
                $('#diary-hit').show();
            }

//            $('#btn-primary-p').click(function() {
            $('#btn-primary-p').click(function() {
                var data = {};

                var ids = "";
                $('#import_ck:checked').each(function(){ //由于复选框一般选中的是多个,所以可以循环输出选中的值
//                    alert($(this).parent().parent().parent().prev().attr('href'));
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
                    url: '/item/addimport?uid=' + lt_uid + '&admin_ctl=' + admin_ctl + '&num_iids=' + ids,
                    type: 'get',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        if (data.result) {
                            alert('导入成功');
                            location.reload();
                        } else {
                            alert('导入失败');
                        }
                    }
                });

            });

         });
    </script>
</head>
<body>

    <nav class="navbar navbar-inverse " role="navigation">

        <div class="container-fluid" >
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header clearfix">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#lt-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <p href="sinaweibo://userinfo?uid=2758197137" class="navbar-brand">橱窗管理</p>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="lt-navbar-collapse">
                <ul class="nav navbar-nav">
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

<!--    <div>-->
<!--        <ul id="diary-list" class="i_ul">-->
    <div id="diary-wrapper">
        <ul id="diary-list" class="g-clearfix i_ul">
            <?php
            session_start();

             $i = 0;
             foreach ($datainfo['items'] as $item) {
                if($i++ > 399) {
                    break;
                }
             ?>
             <li class="i_li left">
                 <a href="/item/detail?num_iid=<?php echo $item['num_iid']; ?>">
                     <img src="<?php echo $item['pic_url']; ?>" width='135' height='135'>
                     <p class="i_txt"><?php echo $item['title']; ?></p>
                     <p class="i_pri">￥<?php echo $item['price']; ?></p>
                 </a>

                 <div class="mask">
                   <div class="mask_middle">
                     <input type="checkbox" id="import_ck" style="margin:5px">
                     <input type="hidden" id="import_iid" value="<?php echo $item['num_iid']; ?>">
                     <p class="mask_text">导入商品</p>
                   </div>
                 </div>
             </li>
            <?php } ?>

        </ul>
    </div>

    <div id="diary-hit" class="i_hit">
        <p class="i_hit_txt">您还没有淘宝商品哦.</p>
    </div>


    <nav id="bottom-navbar" class="navbar navbar-default navbar-fixed-bottom" role="navigation">
           <button type="button" id="btn-primary-p" class="btn btn-default btn-lg btn-block navbar-btn">导入</button>
    </nav>

<!--    <script src="http://s0.qhimg.com/lib/jquery/1111.js"></script>-->
    <script>
        var tpl = '\
       <li class="i_li left">\
            <a href="/item/detail?num_iid=%num_iid%">\
                <img src="%pic_url%" width="135" height="135">\
                <p class="i_txt">%title%</p>\
                <p class="i_pri">￥%price%</p>\
            </a>\
            <div class="mask">\
               <div class="mask_middle">\
                  <input type="checkbox" id="import_ck" style="margin:5px">\
                  <input type="hidden" id="import_iid" value="%num_iid%">\
                  <p class="mask_text">导入商品</p>\
               </div>\
            </div>\
       </li>';

        var loadingPage = false;
        function dScroll() {

            if(loadingPage){
                return;
            }
            var $el = $('#diary-list'),
                body = document.body,
                clientHeight = document.documentElement.clientHeight,
                height = $el.height(),
                offset = $el.offset(),
                li_num = $("#diary-list li").length,
                page_no = Math.ceil(li_num/10);

            //20是下面悬浮的button的近似高度
            if (offset.top + height + 20 <= body.scrollTop + clientHeight) {
//                alert("page_no"+page_no);
                loadingPage = true;

                var admin_ctl = '<?php echo $admin_ctl;  ?>';
                if("1" != admin_ctl){
                    admin_ctl = "0";
                }

                var lt_uid = '<?php echo $lt_uid;  ?>';
                if(!lt_uid){
                    lt_uid = "0";
                }

                var url = '/item/import?fmt=web&page_no=' + page_no + '&page_size=10' + '&uid=' + lt_uid + '&admin_ctl=' + admin_ctl;
                $.getJSON(url, function(data) {
                    loadingPage = false;
                    var list = data.items,
                        html = '';

                    // 没有更多数据了
                    if (list.length <= 0) {
                        $(document).off('scroll', dScroll);
                        return;
                    }

                    for (var i = 0, len = list.length; i < len; i++) {
                        html += tpl.replace(/%(\w+)%/g, function(match, p1) {return list[i][p1];});
                    }
                    $el.append(html);

                    $('input').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                });
            }
        }
        $(document).on('scroll', dScroll);

        function setCreatePosition() {
            var $lis = $('#diary-list li'),
                firstRowTop = $lis.eq(0).offset().top,
                len = $lis.length,
                item = null;
            for (var i = 0; i < len; i++) {
                if ($lis.eq(i).offset().top > firstRowTop) {
                    break;
                }
                item = $lis.eq(i);
            }

            var right = $(document.body).width() - (item.offset().left + item.width());
            $('.creat').css('margin-right', right);
        }
        setCreatePosition();
        $(window).on('resize', setCreatePosition);
    </script>


</body>
</html>
