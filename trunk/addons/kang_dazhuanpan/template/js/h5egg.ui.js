
var timeoutss = [];
function disappearalls() {
    $("#p2-word1,#p2-word2,#p2-word3,#p2-word4,#p2-cap,#p2-btn,#p2-label,#person2-box").hide();
    $(".answer-box img").hide();
    // $(".answer-label").hide();
    $("#p3-2-call").hide();
    $("#answer-true img").hide();
    $("#answer-true").hide();
    while (timeoutss.length != 0) clearTimeout(timeoutss.pop());
}


function showp14() {
    disappearall();

    timeouts.push(setTimeout('$("#card .title").show().addClass("zoomInDown animated time_2");', 2500));
    timeouts.push(setTimeout('$("#card .sun").show().addClass("zoomIn animated time_0_5");', 200));
    timeouts.push(setTimeout('$("#card .card_box_bg").show().addClass("fadeIn animated time_0_75");', 1000));
    timeouts.push(setTimeout('$("#card .text").show().addClass("fadeIn animated time_2");', 4500));
    timeouts.push(setTimeout('$("#card .btn").show().addClass("fadeIn animated time_2");', 3500));
    timeouts.push(setTimeout('$("#card .itzooNo").show().addClass("tada animated time_2");', 1500));

    setTimeout(function () {
        $("#card .text").removeClass("fadeIn animated").addClass("swing animated infinite");
    }, 2400);
}

//$(function() {
$(document).on('ready', function() {


    $("#shareimg").click(function () {
        $("#pop_share").fadeToggle();
    });

    $("#pop_share").click(function () {
        $("#pop_share").fadeToggle();
    });
    $("#panel-close").click(function () {
        $("#panel_box").hide();
    });
    $("#savebtn").click(function () {
        $("#panel_box").hide();
    });
    $("#share_close").click(function () {
        $("#share_box").hide();
    });
    $("#sharebtn").click(function () {
        $("#share_box").hide();
    });

    $("#btn_share").click(function () {
        $("#pop_share").fadeIn(500);
        setTimeout(function () {
            $("#pop_share").hide();
        }, 6000);
    });

    /*----- home begin -----*/
    $("#homepage").click(function () {
        $("#homepage").fadeOut(300);
        $("#rulepage").fadeIn(500);
    });

    $("#rulepage").click(function () {
        $("#rulepage").fadeOut(300);

        setTimeout('$(".play .egg1").show().addClass("fadeIn animated time_0_75");', 100);
        setTimeout('$(".play .play_bg").show().addClass("fadeIn animated time_0_75");', 1000);
        setTimeout('$(".play .flag").show().addClass("fadeIn animated time_0_75");', 500);
        setTimeout('$(".play .feiji").show().addClass("flipInY animated time_0_5");', 1200);

        setTimeout(function(){
            $(".play .btn_egg").show().addClass("fadeIn animated time_0_75");
        },1200);

/*
        $('.play .egg1').show().animateCSS('flash', {
            duration: 3000,
            delay: 1000,
            easing: "linear",
            swarm: {
                time: 1000, random: false, kind: 'duration'
            },
            autoHide: false
        });
*/


        /*
        $('#btn_begin').fadeIn().animateCSS({

            'duration': '0.2s',
            'timing_function': 'ease-in',
            'css': {
                'transform': "scale(1.1, 1.1)"
            }

        }).animateCSS({

            'duration': '0.5s',
            'timing_function': 'ease-out',
            'css': {
                'transform': "scale(1, 1)"
            }

        }).queue(function(next) {

            console.log('Animation done!')
            next();

        });

         */

    });

    /*----- home end -----*/

    /*--- 抽奖主页面 begin ---*/
    $(".play").click(function () {

        $(".play .btn_egg").hide();

        //setTimeout('$(".play .egg2").show();'
        //    , 100);
        //setTimeout('$(".play .egg3").show();'
        //    , 500);

        setTimeout('getPrize();', 0);
    });

    /*--- 抽奖主页面 begin ---*/


    /*--- 抽奖信息框 begin ---*/

    $(".info_lost_fail").click(function () {

        resetEgg();
    });
    $(".info_lost_over").click(function () {
        resetEgg();
    });

    $(".info_lost_over_today").click(function () {
        //TODO 超过每天抽奖次数 就不能关闭提示

        resetEgg();
    });

    $(".info_lost_share").click(function () {
        //TODO 不做处理
        //TODO 分享后才能关闭分享提示
        resetEgg();
    });


    $("#panel_prize_close").click(function () {
        $("#panel_prize_box").hide();
    });

    $(".get_prize_close").click(function () {
        $("#panel_box_get_prize").hide();
    });


    /*--- 抽奖信息框 end ---*/


    /*--- 抽奖信息框 begin ---*/
    $("#btn_prize_1").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_1").hide();

        $("#panel_prize_box").hide();
    });
    $("#btn_prize_2").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_2").hide();

        $("#panel_prize_box").hide();
    });
    $("#btn_prize_3").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_3").hide();

        $("#panel_prize_box").hide();
    });
    $("#btn_prize_4").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_4").hide();

        $("#panel_prize_box").hide();
    });
    $("#btn_prize_5").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_5").hide();

        $("#panel_prize_box").hide();
    });
    $("#btn_prize_6").click(function () {
        $("#get_prize_box").show();
        $("#btn_prize_6").hide();

        $("#panel_prize_box").hide();
    });

    /*--- 抽奖信息框 end ---*/

});