$.stopPropagation = function (e) {
    e = e || window.event;
    if (e.stopPropagation) { //W3C阻止冒泡方法
        e.stopPropagation();
    } else {
        e.cancelBubble = true; //IE阻止冒泡方法
    }
};

function getCookie(name){
    //获取cookie
    var str=document.cookie;
    var arr=str.split('; ');
    for(var i=0; i<arr.length; i++){
        var arr2=arr[i].split('=');
        if(arr2[0]==name){
            return arr2[1];
        }
    }
    return '';
}

function browser(){
    var u = navigator.userAgent.toLowerCase(), target = Index.getTarget();
    //底部显示弹窗
    function showAttention($bt)
    {
        $(".book_mark").hide();
        if(target && target == 'myspace'){
            if($bt.length>0){
                $bt.show();
            }
        }else{
            $("body").on("tap",".see_more",function(){
                var b=Index.getTarget();
                if((b && b == 'found') || (b && b=="mail")){
                    return;
                }
                setTimeout(function(){
                    if($bt.length>0){
                        $bt.show();
                    }
                },1000)
            });
        }
    }
    switch(true){
        //检测不同的浏览器
        case u.indexOf('mqq') > -1 || u.indexOf('opios') > -1 || u.indexOf('uc')> -1 || u.indexOf('tiantian') > -1 || u.indexOf('360 aphone'):
            showAttention( $(".book_mark_botCenter"));
            break;
        case  u.indexOf('iphone') > -1 && u.indexOf('safari') > -1:
            showAttention( $(".book_mark_botCenter"));
            break;
        case  u.indexOf('sogou') > -1:
            showAttention( $(".book_mark_bot_Right"));
            break;
        default:$(".book_mark").hide();
            break;
    }
    /* if(window["isLBBrowser"]){
     showAttention( $(".book_mark_bot_Right"));
     }*/
}

$.fn.isShow = function () {
    return $(this).css('display') != 'none';
}


$('body').on("tap", '[data-goback]', function (e) {//网页后退到第几步
        $.stopPropagation(e);
        var i = $(this).data('goback');
        i = i ? i : -1;
        window.history.go(i);
        return false;
}).on("tap", '[href]', function (e) {//打开连接
    var target = e.currentTarget;
    if(target.tagName == 'A'){
        return;
    }else{
        e.preventDefault();//阻止默认事件
        $.stopPropagation(e);//组织事件冒泡
        var url = $(this).attr('href');
        setTimeout(function(){
            location.href = url;
        },300);   
        return false;
    }
}).on("tap","[data-remove]",function(){
    $(".book_mark").remove();
    document.cookie="showBrowser=1;path=/";
}).on("tap",".close,.gray_col,.blue_col,.pink_col",function(){
    //关闭弹窗
    $(".Receive_alert").addClass('hidden');
});