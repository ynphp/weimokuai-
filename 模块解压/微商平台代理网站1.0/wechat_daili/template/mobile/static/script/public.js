$(document).ready(function(){
//头部导航样式
$(function(){
    var time = null;
    var list = $("#mainNavMenu");
    var box = $("#mainNavBox");
    var lista = list.find("a");
    
    for(var i=0,j=lista.length;i<j;i++){
        if(lista[i].className == "now"){
            var olda = i;
        }
    }
    
    var box_show = function(hei){
        box.stop().animate({
            height:hei,
            opacity:1
        },200);
    }
    
    var box_hide = function(){
        box.stop().animate({
            height:0,
            opacity:0
        },200);
    }
    
    lista.hover(function(){
        lista.removeClass("now");
        $(this).addClass("now");
        clearTimeout(time);
        var index = list.find("a").index($(this));
        box.find(".cont").hide().eq(index).show();
        var _height = box.find(".cont").eq(index).height()+0;
        box_show(_height)
    },function(){
        time = setTimeout(function(){   
            box.find(".cont").hide();
            box_hide();
        },50);
        lista.removeClass("now");
        lista.eq(olda).addClass("now");
    });
    
    box.find(".cont").hover(function(){
        var _index = box.find(".cont").index($(this));
        lista.removeClass("now");
        lista.eq(_index).addClass("now");
        clearTimeout(time);
        $(this).show();
        var _height = $(this).height()+0;
        box_show(_height);
    },function(){
        time = setTimeout(function(){       
            $(this).hide();
            box_hide();
			
        },50);
        lista.removeClass("now");
		 box_hide();
        lista.eq(olda).addClass("now");
    });
});
//右侧导航效果
(function(){
	$('.rtService .close_se a').click(function(){
		$('.rtService').css('height','auto');
	   $('.rtService').find('.se_main').hide();
		$('.rtService').find('.open_rtser').show();
	});
	
	 $('.rtService .open_rtser').click(function(){
		$('.rtService').css('height','auto');
		 $('.rtService').find('.se_main').show();
		$('.rtService').find('.open_rtser').hide();
	});
	
	$('.rtService .weixin_cont').mouseover(function(){
		$(this).find('.weixin_show').show();	
		}).mouseout(function(){
		$(this).find('.weixin_show').hide();	
		});
	
	 $('.go_top').click(function(){
		$('body,html').animate({scrollTop: 0}, 800);
	 });
})();
});