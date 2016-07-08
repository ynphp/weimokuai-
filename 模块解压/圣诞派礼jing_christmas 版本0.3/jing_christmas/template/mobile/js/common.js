/**
 * Created by tanytree on 2015/10/12.
 */
//首页图片预加载方法
function _PreLoadImg(b, e) {
    var c = 0,
        a = {},
        d = 0;
    for (src in b) {
        d++;
    }
    for (src in b) {
        a[src] = new Image();
        a[src].onload = function() {
            if (++c >= d) {
                e(a)
            }
        };
        a[src].src = b[src];
    }
}

$(function(){
    //按钮标签点击颜色切换
    $(".aBtnList ul li a").click(function(){
        $(this).addClass("on").parent().siblings().find("a").removeClass('on');
    });
    //点击关闭弹窗
    $(".fullBg").on('click',function(){
        window.location=window.location;
        $(".oWindow").fadeOut(500);
        $(".fullBg").fadeOut(1000);
    });
    $(".oShare").on('click',function(){
        $(".oWindow").fadeOut(500);
        $(".fullBg").fadeOut(1000);
    });    
    //登录表单显示表达式

});
//点击叉号关闭弹窗
function aClosed(){
    $(".oWindow").fadeOut(500);
    $(".fullBg").fadeOut(1000);
}
//显示分享弹窗
function showShare(){
    $(".fullBg").show();
    $(".oShare").show();
}
//显示关注弹窗
function showPwd(){
    $(".fullBg").show();
    $(".pwpage").show();
}



