$.waiting = (function () {//等待提示
    if (window.youyuan && window.youyuan.showWaiting && window.youyuan.hideWaiting) {
        return {
            show: function (msg) {
                window.youyuan.showWaiting(msg);
            },
            hide: function () {
                window.youyuan.hideWaiting();
            }
        }
    } else {
        if($('.waiting').length==0){
            $('<div class="waiting"></div>').prependTo('body');
        }
        $('.waiting').empty().hide();
        return {
            show: function (msg) {
                $('.waiting').html(msg).show();
            },
            hide: function () {
                $('.waiting').empty().hide();
            }
        }
    }
})();
$.tips = (function () {//等待提示
    if (window.youyuan && window.youyuan.toast) {
        return function (msg) {
            window.youyuan.toast(msg);
        };
    } else {
        if($('.tips').length==0){
            $('<div class="tipsDiv"><div class="tips"></div></div>').prependTo('body');
        }
        $('.tips').empty().hide();
        return function (msg) {
            $('.tips').html(msg).show();
            setTimeout(function () {
                $('.tips').empty().hide();
            }, 2000);
        }
    }
})();