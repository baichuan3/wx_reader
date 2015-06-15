
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>天天抽奖汇 | 让抽奖融入生活</title>
    <link rel="shortcut ico" href="/resource/image/favicon.ico" type="image/x-icon">
    <link href="/resource/css_v2/style.css" rel="stylesheet">

</head>
<body>
<div class="sec sec-top">
    <div class="conwrap">
        <h1 class="logo"><a href="/"><img src="/resource/image/logo.png" alt="天天汇"></a></h1>

        <div class="downs downs-top">
            <a href="/soon.php" target="_blank" class="iphone"></a>
            <a href="/luckyclub.apk" class="android"></a>
        </div>

        <div class="weixin"><img src="/resource/image/qrcode.png" alt='扫一扫下载“天天抽奖汇”'></div>
    </div>
    <div class="imgwrap">
        <img src="/resource/image/body-bg.jpg" alt="">
    </div>
</div>
<div class="sec sec-a">
    <div class="imgwrap">
        <img src="/resource/image/a-bg.jpg" alt="">
    </div>
</div>
<div class="sec sec-b">
    <div class="imgwrap">
        <img src="/resource/image/b-bg.jpg" alt="">
    </div>
</div>
<div class="sec sec-c">
    <div class="imgwrap">
        <img src="/resource/image/c-bg.jpg" alt="">
    </div>
</div>
<div class="sec sec-d">
    <div class="imgwrap">
        <img src="/resource/image/d-bg.jpg" alt="">
    </div>
</div>
</div>

<!--[if IE 6]>
    <script src="js/DD_belatedPNG.js"></script>
    <script>
        DD_belatedPNG.fix('*, img');
    </script>
<![endif]-->

<script src="/resource/js_v2/jquery.min.js"></script>
<script src="/resource/js_v2/common.js"></script>
<script type="text/javascript">
$(function() {
    function init() {
        var _w = $(window).width();

        var _scale = _w/2048;

        if(_scale >= 1) {
            _scale = 1;
        } else if(_scale <= 0.5) {
            _scale = 0.5;
        }

        $(".logo img").css({
            width: 250 * _scale + "px"
        });
        $(".logo").css({
            left: 340 * _scale + "px",
            top: 93 * _scale + "px"
        });

        $(".downs-top").css({
            left: 506 * _scale + "px",
            top: 533 * _scale + "px"
        });
        $(".downs-top a.android").css({
            marginTop: 10 * _scale + "px"
        });
        $(".downs-btm").css({
            left: 776 * _scale + "px",
            top: 351 * _scale + "px"
        });
        $(".downs a").css({
            width: 308 * _scale + "px",
            height: 95 * _scale + "px"
        });
        $(".downs-btm a").css({
            marginRight: 61 * _scale + "px"
        });

        $(".info").css({
            paddingTop: 527 * _scale + "px"
        });
        $(".info p").css({
            fontSize: 20 * _scale + "px"
        });

        $(".weixin").css({
            top: 446 * _scale + "px",
            left: 1232 * _scale + "px",
            width: 200 * _scale + "px"
        });
    }

    init();
    $(window).resize(function() {
        init();
    });
});
</script>
</body>
</html>
