$(function(){
	$("input[type='text']").not(".no").each(function(){
		$(this).placeholder();
	});
	$(".tabs").each(function(){
		$(this).tabs();
	});
	resize();
	$(window).resize(function(event) {
		resize();
	});

	show(".pt_pgre");


	$(".pt_pgcv").find('.cast i').fadeIn(500).fadeOut(500);
	setInterval(function(){
		$(".pt_pgcv").find('.cast i').fadeIn(500).fadeOut(500);
	}, 1500);
	$(".pt_pgcv").bind('move', function(e){
		if(Math.abs(e.deltaY)<5){
			return;
		}

		var $obj=$(".bg-1");
		if(e.deltaX>0){
			swip(".pt_pgcv", ".pt_pgtt");
			$("."+$obj.attr('data-curr')).find('.tt.t2').fadeIn(1000, function(){
				$("."+$obj.attr('data-curr')).find('.tt.t1').fadeIn(500);
			});
			stopsound();
			if($obj.attr('data-sou1')!=""){
				playsound($obj.attr('data-sou1'));
			}
		}
	});
	$(".pgtt").bind('move', function(e){
		if(Math.abs(e.deltaY)<5){
			return;
		}

		var $obj=$(this);
		if(e.deltaX>0 && onswip==0){
			swip("."+$obj.attr('data-curr'), "."+$(this).attr('data-next'));
			$("."+$obj.attr('data-next')).find('.tt.t2').fadeIn(1000, function(){
				$("."+$obj.attr('data-next')).find('.tt.t1').fadeIn(500);
			});
			stopsound();
			if($obj.attr('data-sou2')!=undefined){
				playsound($obj.attr('data-sou2'));
			}
		}
	});

	$(".gd").each(function(){
		var $obj=$(this);
		$obj.find("a").click(function(event) {
			if(dianzan()){
				$(this).prevAll().andSelf().addClass('on');
				$(this).nextAll().removeClass('on');

				zanSubmit();
			}

			return false;
		});
	});
	$(".in_logo").click(function(event) {
		$(".pt_rank").show();
	});
	try{
		soundManager.onready(function() {
			sm = soundManager;
		});
	}catch(e){

	}

//$("a.play, a.stop")
	$("#play_listen").click(function(event) {

		var disc_time=1000*60*5;
		var disc_degs=10800;
		var src=$(this).next(".im").attr("rel");

		if($(this).hasClass('play')){
			playVoice();
			$(this).next(".im").attr("style",
				"transition-duration:"+disc_time+"ms;"+
				"-ms-transition-duration:"+disc_time+"ms;"+
				"-o-transition-duration:"+disc_time+"ms;"+
				"-moz-transition-duration:"+disc_time+"ms;"+
				"-webkit-transition-duration:"+disc_time+"ms;"+
				"transform:"+"rotate(" + disc_degs + "deg);"+
				"-ms-transform:"+"rotate(" + disc_degs + "deg);"+
				"-o-transform:"+"rotate(" + disc_degs + "deg);"+
				"-moz-transform:"+"rotate(" + disc_degs + "deg);"+
				"-webkit-transform:"+"rotate(" + disc_degs + "deg);"+
				"background-image:"+"url("+src+")"
			);


			$(this).removeClass('play').addClass('stop');
		}else{
			stopVoice();
			$(this).next(".im").attr("style", "background-image:"+"url("+src+")");
			$(this).removeClass('stop').addClass('play');


		}
		return false;
	});



});
var onswip=0;
/*main*/
//

/*call*/
//
function resize(){
	var ht=$(window).height();
	$(".flht").height(ht);
}
function show(name){
	$(".page").hide();
	$(name).show();
}
function swip(curr, next){
	onswip=1;
	$(curr).fadeOut(500);
	$(next).fadeIn(500, function(){
		onswip=0;
	});
}
function playsound(name){
	soundManager.stopAll();

	var thisSound = sm.createSound({
		url:RES_PATH+"img/"+name+".mp3",
		loops: 1000
	});
	thisSound.play();
}


function stopsound(){
	soundManager.stopAll();
}