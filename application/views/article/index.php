<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/resource/css_wx/inspector.css" rel="stylesheet" type="text/css">
    <link href="/resource/css_wx/main.css" rel="stylesheet" type="text/css">
    <link href="/resource/css_wx/base.css" rel="stylesheet" type="text/css">

    <link rel="shortcut icon" href="/resource/image/favicon.ico">

    <title>白川阅读 -- 微信公众账号和文章的导航及推荐</title>
    <meta name="keywords" content="微信公众平台,公众平台,微信公众账号导航,微信公众账号列表,公众账号,公众账号导航,公众账号列表,二维码,微信二维码,微信推送,微信" />
    <meta name="description" content="微信公众账号导航及推荐,在线阅读微信公众账号推送的文章,微信公众账号文章的展示及推荐" />

</head>
<body>


<div>
<div class=" pmsg_container main wrapper hidden "></div>
<div class=" main wrapper">
<div class="home_page contents content main_content">
<div>
<div>
<div class=" focus_feed">
<div class="main e_col w4_5 main_col">
<div>
<div>
<div tabindex="-1">
<div class="feed_body" style="border-top: 1px solid #e0e0e0">




<?php
    foreach ($datainfo['articles'] as $article) {
?>

<div class="pagedlist_item" tabindex="-1" >
    <div class="feed_item">
        <div class="e_col p1 w4_5">
            <div class="feed_item_photo">
                <div>
                    <a href="<?php echo $article['account_url']; ?>">
                        <img class="profile_photo_img" src="<?php echo $article['headimage']; ?>" width="50"
                             alt="<?php echo $article['sourcename']; ?>" height="50">
                    </a>
                </div>
            </div>
            <strong class="feed_item_title">
<span class="light_gray normal">
<a class="topic_name" href="<?php echo $article['account_url']; ?>">
<span class="name_text">
<span><?php echo $article['sourcename']; ?></span>
</span>
</a>
.
<span class="timestamp">
<?php echo $article['time']; ?>
</span>
</span>
            </strong>
            <div class="home_feed_item_row">
                <div>
                    <div class="feed_item_question">
                        <h2>
<span>
<a class="question_link" href="<?php echo $article['url']; ?>" target="_blank">
    <div class="question_text_icons">
        <span></span>
    </div>
    <?php echo $article['title']; ?>
</a>
</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>


</div>
<div class="w4_5" style="text-align: center;">

    <?php
        $start = intval($start);
        $count = intval($count);
        $total_count = intval($total_count);
        if(($start - $count) < 0){
       ?>
            <div style="float: left">上一页</div>
       <?php
        }else{
       ?>
            <a href="/article/list?start=<?php echo ($start - $count);  ?>" style="float: left">上一页</a>
        <?php
        }
    ?>


<span style="font-size: 1em;font-weight: bold">

    <?php
        $page = 1;
        $middle_page=10;
        $more_count=0;
//        $total_count = intval($total_count);
//        $count = intval($count);
        $total_page = intval(ceil(floatval($total_count) / $count));
        for(;(($page-1) * $count < $total_count); $page++){
            if($total_page <= $middle_page){
           ?>
                <a href="/article/list?start=<?php echo (($page - 1) * $count);  ?>"><?php echo $page;  ?></a>
                &nbsp;
           <?php
            }else{
                if(($page <= ($middle_page-2)) || ($page >= ($total_page - 1))){
               ?>
                    <a href="/article/list?start=<?php echo (($page-1) * $count);  ?>"><?php echo $page;  ?></a>
                    &nbsp;
               <?php
                }else{
                    if($more_count < 1){
                       $more_count++;
                   ?>
                        <strong>...</strong>&nbsp;
                   <?php
                    }
                }
            }
         }
    ?>

</span>


    <?php
    //$start = intval($start);
//    $count = intval($count);
//    $total_count = intval($total_count);
    if(($start + $count) > $total_count){
        ?>
        <div style="float: right">下一页</div>
    <?php
    }else{
        ?>
        <a href="/article/list?start=<?php echo ($start + $count);  ?>" style="float: right">下一页</a>
    <?php
    }
    ?>


</div>
</div>
</div>
</div>
</div>


</div>
</div>
</div>
</div>
</div>
</div>

</div>
</body>

<script language="javascript" src="http://upcdn.b0.upaiyun.com/libs/jquery/jquery-1.4.2.min.js"></script>
<script language="javascript" src="/resource/js_wx/common.js"></script>

</html>
