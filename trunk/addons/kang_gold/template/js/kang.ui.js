
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
    $("#btn_close_help").click(function () {
        $("#helppage").fadeOut(300);
        $("#homepage").fadeIn(500);
    });

    $("#btn_start").click(function () {
        $("#homepage").fadeOut(300);

        $("#guesspage").fadeIn(300);

        //TODO WKL 获取过去一分钟金价数据

        var price_last = getLastPrice();

        $("#price_buy").text(price_last);
        $("#price_last1").text(price_last);
        $("#price_last2").text(price_last);

        loadLastPrice();

    });

    $("#btn_help").click(function () {
        $("#rulepage").fadeIn(300);

        $("#rulepage .border .bg .content #game_rules").fadeOut(0);
        $("#rulepage .border .bg .content #game_prizes").fadeIn(300);

        $("#btn_prize").css("z-index","88");
        $("#btn_rule").css("z-index","3");
    });

    $("#btn_close_rule").click(function () {
        $("#rulepage").fadeOut(300);
    });


    /*----- home end -----*/


    /*----- guess begin -----*/
    $("#btn_up").click(function () {
        $("#guesspage").fadeOut(300);
        $("#orderpage").fadeIn(300);

        startGame();

        user_guess_type = "up";
        $("#guess_type").html("涨");
        $("#guess_target").html("高于");
        $("#guess_icon").attr("src","../addons/kang_gold/template/images/order/icon_up.png");
    });

    $("#btn_down").click(function () {
        $("#guesspage").fadeOut(300);
        $("#orderpage").fadeIn(300);

        startGame();

        user_guess_type = "down";
        $("#guess_type").html("跌");
        $("#guess_target").html("低于");
        $("#guess_icon").attr("src","../addons/kang_gold/template/images/order/icon_down.png");
    });


    /*----- guess end -----*/


    /*----- result begin -----*/

    $("#btn_continue").click(function () {
        $("#homepage").fadeOut(300);

        $("#guesspage").fadeIn(300);

        //TODO WKL 获取过去一分钟金价数据

        getLastPrice();

        loadLastPrice();
    });

    $("#btn_more, #resultpage .share").click(function () {

        $("#sharepage").fadeIn(300);

    });

    $("#sharepage").click(function () {
        $("#sharepage").fadeOut(300);
    });
    /*----- result end -----*/


    /*----- helppage begin -----*/
    $("#btn_prize").click(function () {
        $("#rulepage .border .bg .content #game_rules").fadeOut(0);
        $("#rulepage .border .bg .content #game_prizes").fadeIn(300);

        $("#btn_prize").css("z-index","88");
        $("#btn_rule").css("z-index","3");
    });


    $("#btn_rule").click(function () {
        $("#rulepage .border .bg .content #game_rules").fadeIn(300);
        $("#rulepage .border .bg .content #game_prizes").fadeOut(0);

        $("#btn_prize").css("z-index","3");
        $("#btn_rule").css("z-index","88");
    });

    /*----- helppage end -----*/


    /*----- shop begin -----*/
    $("#homepage .get_prize").click(function () {
        alert("homepage 去兑换奖品了！");

        $("#homepage").fadeOut(300);

        $("#shoppage").show();

    });



    /*----- shop end -----*/




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

        //resetEgg();
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
        // 分享后才能关闭分享提示
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