<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/resource/css_wx/inspector.css" rel="stylesheet" type="text/css">
    <link href="/resource/css_wx/main.css" rel="stylesheet" type="text/css">
    <link href="/resource/css_wx/base.css" rel="stylesheet" type="text/css">

    <link rel="shortcut icon" href="/resource/image/favicon.ico">

    <title>传送门 -- 微信公众账号和文章的导航及推荐</title>
    <meta name="keywords" content="传送门,微信公众平台,公众平台,微信公众账号导航,微信公众账号列表,公众账号,公众账号导航,公众账号列表,二维码,微信二维码,微信推送,微信,微信传送门" />
    <meta name="description" content="微信公众账号导航及推荐,在线阅读微信公众账号推送的文章,微信公众账号文章的展示及推荐,传送门chuansong.me" />

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


    <div style="float: left">上一页</div>

<span style="font-size: 1em;font-weight: bold">



<strong>1</strong>

&nbsp;


<a href="/article/list?start=25">2</a>

&nbsp;


<a href="/article/list?start=50">3</a>

&nbsp;


<a href="/article/list?start=75">4</a>

&nbsp;


<a href="/article/list?start=100">5</a>

&nbsp;


<a href="/article/list?start=125">6</a>

&nbsp;


<a href="/article/list?start=150">7</a>

&nbsp;


<a href="/article/list?start=175">8</a>

&nbsp;



<strong>...</strong>
&nbsp;

<a href="/article/list?start=950">39</a>
&nbsp;
<a href="/article/list?start=975">40</a>

</span>


    <a href="/article/list?start=25" style="float: right">下一页</a>


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
