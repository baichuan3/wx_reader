
$(document).ready(function(){

    var path = window.location.pathname, base_url = '';
    if(path.match(/index.php/)) {
        base_url = '../';
    }
    /*
     $(".weimi_web").click(function(){
     alert("WEB寰背姝ｅ湪鍔犵揣瀹屽伐涓€傘€�")
     })
     */
    var pics = $(".index_pictures");
    var showPics = function(){
        if(!pics.is(":animated")){
            pics.animate({
                    "margin-left" : "-213px"
                },
                600,
                function(){
                    pics.css("margin-left", "0");
                    pics.append($(".index_pictures img:first"))
                })
        }
    }
    setInterval(showPics, 2300)

    var toTop = 248;

    window.onscroll = function(){
        var sTop = document.body.scrollTop + document.documentElement.scrollTop;
        if(sTop == 0 ){
            toTop = 248;
        }else{
            toTop = sTop;
        }
        updataNav();
    }

    var nav = setInterval(function(){
        $(".nav_list").animate({
            top: toTop
        }, 200)
    }, 600)

})