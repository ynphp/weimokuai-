var Album = (function () {
    return {
        size: $("#photo_list li").length,
        index: isNaN($("#photo_index").val()) ? 0 : parseInt($("#photo_index").val()),
        scrollCallback:function(){},
        scroll: function () {
            $("#now").html(Album.index + 1);
            $("#all").html(Album.size);
            $("#photo_list li").animate({"-webkit-transform":"translateX("+(-Album.index * 100)+"%)"},200,'linear');
            Album.scrollCallback();
        },
        scrollLeft: function () {
            Album.index--;
            Album.index = Album.index<0?0:Album.index;
            Album.scroll();
        },
        scrollRight: function () {
            Album.index++;
            Album.index =  Album.index>Album.size - 1?Album.size - 1:Album.index;
            Album.scroll();
        },
        toggleMenu:function(){
            if($('#album_top').data('show')){
                $("#album_top").data('show',false).animate({"-webkit-transform":"translateY(0px)"},0,'linear');
                $("#album_bottom").animate({"-webkit-transform":"translateY(0px)"},0,'linear');
            }else{
                $("#album_top").data('show',true).animate({"-webkit-transform":"translateY(-40px)"},100,'linear');
                $("#album_bottom").animate({"-webkit-transform":"translateY(52px)"},100,'linear');

            }
        },
        init: function () {
            $("#photo_list").css("width", Album.size *100+"%");
            Album.scroll();
        }
    }

})();
//查看照片 相册页
$(function () {
    //阻止默认事件

    Album.init();
    $(document).on('touchmove mousemove', function (e) {
        e.preventDefault();
        return false;
    });
    $("body").on("click",'#photo_list li', function (e) {  //点击左半边向右滑动，点击右半边向做滑动
        var x=e.clientX;
        var width = $(window).width();
        if(x<width/2){
            Album.scrollLeft();
        }else{
            Album.scrollRight();
        }
    }).on("swipeLeft ", function () {//向左滑动手指
        Album.scrollRight();
    }).on("swipeRight", function () { //向右滑动手指
        Album.scrollLeft();
    })
})