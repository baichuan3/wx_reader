function setNavAction(obj){
    var $this = $(obj)
        $parent = $this.parent();
    $this.siblings().each(function(){
        $this.removeClass("on");
    })
}
function setNavPos(){
    var $win = $(window),
        defWinH = $win.height(),
        top = $win.scrollTop(),
        defToTop = (defWinH-116)/2,
        $nav =  $("#nav"),
        winH = $win.height(),
        toTop = (winH-116)/2 + top;
    $nav.css({'position': 'absolute'}).show();
    $nav.css({"top": toTop+"px"});
}
(function supportPosFixed(){
    'use strict';
    var fixed = document.createElement('div');
    fixed.setAttribute('style','background:red;width:10px;height:10px;position:fixed;top:-10px;left:-10px;');
    document.body.appendChild(fixed);
    var fixedEle     =  fixed.getBoundingClientRect();
    var supportFixed =  fixedEle.left === -10 && fixedEle.top === -10 && fixedEle.bottom === 0 && fixedEle.right === 0;
    fixed.parentNode.removeChild(fixed);
    window.supportFixed = supportFixed;
})();

$(function() {
    $('a[href*=#]').click(function() {
        setNavAction(this);
    });
    /**
    *首屏焦点图
    */ 
    var pics = $(".index_pictures");
    function showPics(){
        if(!pics.is(":animated")){
            pics.animate({
                "margin-left" : "-222px"
            },
            600,
            function(){
                pics.css("margin-left", "0");
                pics.append($(".index_pictures img:first"))
            })
        }
    }
    setInterval(showPics, 2300);

    /**
    *页面滚动导航位置设置
    */
    $(window).scroll(function(){
        var top = $(window).scrollTop(),
            $on = $("#nav .on"),
            $list = $("#nav a");
        //$("#nav").css("top",top+30+"px");
        if(top < 426){
            $on.removeClass("on");
            $list.eq(0).addClass("on");
        }else if( top >= 426 && top < 1066) {
            $on.removeClass("on");
            $list.eq(1).addClass("on");
        }else if( top >= 1066 && top < 1706 ){
            $on.removeClass("on");
            $list.eq(2).addClass("on");
        }else if(top >= 1706){
            $on.removeClass("on");
            $list.eq(3).addClass("on");
        }
    })
    var hash = location.hash;
    if(!!hash){
        $('a[href='+hash+']').click();
    }
    /**
    *页面resize导航定位
    */
    var $win = $(window),
        defWinH = $win.height(),
        defToTop = (defWinH-116)/2,
        $nav =  $("#nav");
    if(supportFixed){
        $nav.css({"top": defToTop+"px"}).show();
        $win.resize(function(){
            var winH = $win.height(),
                toTop = (winH-116)/2;
            $nav.css({"top": toTop+"px"});
        })
    }else{
        setNavPos();
        $win.resize(function(){
            setNavPos();
        });
        $win.scroll(function(){
            setTimeout(function(){
                setNavPos();
            }, 50);
        });
    }
});