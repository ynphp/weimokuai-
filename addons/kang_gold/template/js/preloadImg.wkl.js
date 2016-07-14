var gameStart = function () {

    console.log("----  预加载完毕  ----");

    $('#helppage').fadeIn();

    setTimeout(function () {
        $("#loading").fadeOut(300);

        //alert("加载完毕");
    }, 1300);
};

var imgArr = [

    '../addons/kang_bigwheel/template/images/address/address_bg.png',
    /*
    '../addons/kang_bigwheel/template/images/address/address_dizhi.png',
    '../addons/kang_bigwheel/template/images/address/address_name.png',
    '../addons/kang_bigwheel/template/images/address/address_phone.png',
    '../addons/kang_bigwheel/template/images/address/address_youbian.png',

    '../addons/kang_bigwheel/template/images/btn/btn_begin.png',
    '../addons/kang_bigwheel/template/images/btn/btn_egg.png',
    '../addons/kang_bigwheel/template/images/btn/btn_get_prize.png',
    '../addons/kang_bigwheel/template/images/btn/btn_read.png',
    '../addons/kang_bigwheel/template/images/btn/btn_submit.png',

    '../addons/kang_bigwheel/template/images/common/clound.png',
    '../addons/kang_bigwheel/template/images/common/loading.png',
    '../addons/kang_bigwheel/template/images/common/over.png',
    '../addons/kang_bigwheel/template/images/common/sharetip.png',

    '../addons/kang_bigwheel/template/images/guanzhu/guanzhu_bg.png',
    '../addons/kang_bigwheel/template/images/guanzhu/guanzhu_qrcode.png',

    '../addons/kang_bigwheel/template/images/home/page1.png',
    '../addons/kang_bigwheel/template/images/home/page2.png',

    '../addons/kang_bigwheel/template/images/play/play_bg.png',
    '../addons/kang_bigwheel/template/images/play/play_egg_1.png',
    '../addons/kang_bigwheel/template/images/play/play_egg_2.png',
    '../addons/kang_bigwheel/template/images/play/play_egg_3.png',
    '../addons/kang_bigwheel/template/images/play/play_feiji.png',
    '../addons/kang_bigwheel/template/images/play/play_flag.png',

    '../addons/kang_bigwheel/template/images/prize/prize_1.png',
    '../addons/kang_bigwheel/template/images/prize/prize_2.png',
    '../addons/kang_bigwheel/template/images/prize/prize_3.png',
    '../addons/kang_bigwheel/template/images/prize/prize_4.png',
    '../addons/kang_bigwheel/template/images/prize/prize_5.png',
    '../addons/kang_bigwheel/template/images/prize/prize_6.png',
    '../addons/kang_bigwheel/template/images/prize/prize_fail.png'
    */
];

preloadimg(imgArr, gameStart);


/*
 $(document).ready(function(){

 });

 window.onload = function() {
 $('#homepage').animateCSS('flash', {
 duration: 3000,
 delay: 1000,
 easing: "linear",
 swarm: {
 time: 100, random: false, kind: 'duration'
 },
 autoHide: false
 });
 };
 */