var id,index=0,winH,flag=false,liIndex,sound,liH;
var myScroll;
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

var fileList = ['p1.jpg','p2.jpg','p3.jpg','pop.png','pop.png','pop-mi.png','pop-feng.png','pop-han.png','jp.jpg','jp2.jpg'];
function loadImage(imgIndex){
	var img = new Image();
	img.src = resourceUrl + "/img/"+fileList[imgIndex];
	var percent = Math.round((imgIndex / fileList.length) * 100);
	$("#loadPress span").css({width:percent+"%"});
	img.onload = function () {
		imgIndex++;
		if(imgIndex < fileList.length){
			loadImage(imgIndex);
		}else{
			sound=document.getElementById("sound");
			$("#loadPress span").css({width:"100%"});
			setTimeout(function(){
				$("#pageload").hide();
				dialogue(index);
			},400)
			myScroll = new IScroll('.main',{
				preventDefault: false
			});
		}
	}
}; 


$(function(){
	loadImage(index);
	winH=$(window).height();
	liH=[320,100,100,320,100,100,320,100,100,parseInt($(".list li").eq(9).height()),100,100,100]
	$(".warp").height(winH);
	$(".main").height(winH-92);
	$(".bottom").on("touchstart",function(){
		if(flag){
			$(".bottom em").hide();
			$(this).addClass("on");
			flag=false;
			$(".bottom .input").html("赞不赞↓");
		}
	})
	$(".choose li").on("touchstart",function(){
		$(".bottom .input").addClass("on");
		id=$(this).index();
		if(id == 0){
			$(".me .say p,.bottom .input").html("赞");
			$(".last").html("<img src='"+resourceUrl+"/img/mi.jpg'><div class='say'><p>羞羞哒<i class='bq4'></i>爱你哦，分享给好友吧！</p></div>");
			$(".popBox .goto").attr("href","http://www.oppo.com/cn/product/m/r7plus/online/index.html");
		}else if(id == 1){
			$(".me .say p,.bottom .input").html("赞");
			$(".last").html("<img src='"+resourceUrl+"/img/feng.jpg'><div class='say'><p>你真有眼光，快分享给朋友吧！<i class='bq6'></i></p></div>");
			$(".popBox .goto").attr("href","http://www.oppo.com/cn/product/m/r7/online/index.html");
		}else if(id == 2){
			$(".me .say p,.bottom .input").html("赞");
			$(".last").html("<img src='"+resourceUrl+"/img/han.jpg'><div class='say'><p>V587！快让小伙伴们知道吧<i class='bq6'></i></p></div>");
			
			$(".popBox .goto").attr("href","http://www.oppo.com/cn/product/m/r7plus/online/index.html");
		}
		$(".ren img").eq(id).show().siblings().hide();
		$(".bottom em").addClass("on").show();
		$(".enter").show();
	})
	$(".enter").on("touchstart",function(){
		$(".me,.last").show();
		myScroll.refresh();
		$(".bottom").removeClass("on");
		$(".bottom .input").removeClass("on");
		$(".bottom em").removeClass("on").hide();
		index=10;
		result(index);
		$(".enter").hide();
	})
	$(".pp").click(function(){
		var src=$(this).attr("data-img");
		var y=myScroll.y;
		liIndex=$(this).parents("li").index();
		var theTop=check(liIndex)+y;
		//console.log(y+"---------------"+liIndex+"---------------"+check(liIndex));
		$(".tanchu .pic img").attr("src",resourceUrl + "/img/"+src+".jpg");
		$(".tanchu .pic").css({"transform":"translate(0,"+(theTop)+"px)","-webkit-transform":"translate(0,"+(theTop)+"px)"});
		$(".tanchu .pic img").css({"transform":"scale(0.317) translate(0,0)","-webkit-transform":"scale(0.317) translate(0,0)"});
		$(".tanchu").show();
		setTimeout(function(){
			$(".tanchu").addClass("on");
			$(".tanchu .pic img").css({"transform":"scale(1) translate(-114px,"+((winH-946)/2-theTop)+"px)","-webkit-transform":"scale(1) translate(-114px,"+((winH-946)/2-theTop)+"px)"});
		},50)
	})
	$(".tanchu").click(function(){
		var y=myScroll.y; 
		$(this).removeClass("on");
		var theTop=check(liIndex)+y;
		$(".tanchu .pic").css({"transform":"translate(0,"+(theTop)+"px)","-webkit-transform":"translate(0,"+(theTop)+"px)"});
		$(".tanchu .pic img").css({"transform":"scale(0.317) translate(0,0)","-webkit-transform":"scale(0.317) translate(0,0)"});
		setTimeout(function(){
			$(".tanchu").hide();
		},500)
	})
})
function dialogue(index){
	var tt=(index==0)?1000:1500;
	if(index<10){
		var theli=$(".list li").eq(index);
		setTimeout(function(){
			if(index == 0||index == 3|| index == 6){
				sound.play();
			}
			theli.addClass("on");
			index++;
			var theH=check(index);
			if(theH>(winH-92)){
				var y=(winH-92)-theH;
				myScroll.scrollTo(0,y,300);
			}else{
				myScroll.scrollTo(0,0,300);
			}
			dialogue(index);
		},tt)
	}else{
		flag=true;
		$(".bottom em").show();
	}
}
function result(index){
	var tt=(index==10)?500:1500;
	if(index<12){
		var theli=$(".list li").eq(index);
		setTimeout(function(){
			if(index == 10){
				sound.play();
			}
			theli.addClass("on");
			index++;
			var theH=check(index);
			if(theH>(winH-92)){
				var y=(winH-92)-theH;
				myScroll.scrollTo(0,y,300);
			}
			$(".bottom div").html("");
			result(index);
		},tt)
	}else{
		setTimeout(function(){
			$(".pop").show();
		},2800)
	}
}
function check(x){
	var h=120;
	for(i=0;i<x;i++){
		h=h+liH[i];
	}
	return h;
}


