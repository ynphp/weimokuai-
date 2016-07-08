var load_img = [];

load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/qixi-1.mp3' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/qixi-2.mp3' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/qixi-3.mp3' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/qixi-4.m4a' );

load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2oviPepXXXXXcXpXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2c7y0epXXXXbsXXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2g0a6epXXXXXnXXXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2PUOPepXXXXcUXXXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2JMbLepXXXXaUXXXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB23gSFepXXXXbBXpXXXXXXXXXX_!!169328611.gif' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB23gSFepXXXXbBXpXXXXXXXXXX_!!169328611.gif' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB24hHqepXXXXb0XXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2ZJ56epXXXXXlXXXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2zVrtepXXXXcHXpXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2NBvvepXXXXaaXXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2BnSKepXXXXaDXpXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2su2uepXXXXbYXpXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2x8u0epXXXXaMXXXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2paLxepXXXXcAXpXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2AATfepXXXXXXXpXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2dG9FepXXXXbmXpXXXXXXXXXX_!!169328611.png' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2wGW6epXXXXXZXXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2xA2sepXXXXbDXXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2flnhepXXXXa5XXXXXXXXXXXX_!!169328611.jpg' );
load_img.push( 'http://image.goujx.com/bhsite/qixi/wai/TB2_VC8epXXXXbvXpXXXXXXXXXX_!!169328611.jpg' );

// 资源图片加载
jQuery.imgpreload(load_img, {
	all: function() {
		$('#loading').hide();
		$('#p-index').show();
		indexList();
	}
});

function alertWord(i) {
	$('#noYou').append(wordArr[i]);
}

function daojishi(i) {
	$('#daojishi').html(i);
}


var isCanSwiperight = false;

$(function() {
	$(document).on('touchmove', function(ev){ev.preventDefault()});
	
	var indexCanRight = true;
	//首页
	touch.on('#p-index', 'swiperight', function(ev) {
		if(!isCanSwiperight) return;
		if(!indexCanRight) return;
		indexCanRight = false;
		$('#p-index').hide();
		$('#p-chat').show();
		for(var i=0; i<wordArr.length; i++) {
			setTimeout("alertWord('" + i + "')", 300*(i+1));
		}
		setTimeout(function(){
			$('#wordSend').show();
		}, 3000);
	});
	
	touch.on('#slideIndex2', 'swipeup', function(ev) {
		if(!indexCanRight) return;
		indexCanRight = false;
		$('#p-index').hide();
		$('#p-chat').show();
		for(var i=0; i<wordArr.length; i++) {
			setTimeout("alertWord('" + i + "')", 300*(i+1));
		}
		setTimeout(function(){
			$('#wordSend').show();
		}, 3000);
	});
	
	//打字屏
	$('#chatImgSpan').bind('touchend',function(event) {
		event.preventDefault();
		$('#p-chat').hide();
		$('#p-weixin-1').show();
		
		$('#daojishi').show();
		for(var i=4,j=1; i>0; i--,j++) {
			setTimeout("daojishi('" + i + "')", 1000*j);
		}
		
		setTimeout(function() {
			$('#daojishi').hide();
			$("#weixin-1-max").show();
            $("#weixin-1-tk").show();
		}, 5000);
	});
	
	//约会
	$('#yuehuiOk').bind('touchend',function(event) {
		event.preventDefault();
		$('#p-weixin-1').hide();
		$('#p-weixin-2').show();
		setTimeout(function() {
			$("#weixin-2-max").show();
            $("#weixin-2-tk").show();
		}, 1000);
	});
	
	//购物车
	$('#gouwucheOk').bind('touchend',function(event) {
		event.preventDefault();
		document.getElementById('audio-3').play();
		$('#p-weixin-2').hide();
		$('#p-weixin-3').show();
		
		//接电话
		/*
		touch.on('#target', 'touchstart', function(ev) {
			ev.preventDefault();
		});
		var target = document.getElementById("target");
		if(target) {
			target.style.webkitTransition = 'all ease 0.4s';
			touch.on(target, 'swiperight', function(ev) {
				this.style.webkitTransform = "translate3d(" + 380 + "px,0,0)";
				setTimeout(function() {
					$('#p-weixin-3').hide();
					$('#p-weixin-4').show();
					document.getElementById('audio-3').pause();
					document.getElementById('audio-4').play();
				}, 600);
			});
		}
		*/
		$('#playArea').bind('touchend',function(event) {
			$('#p-weixin-3').hide();
			$('#p-weixin-4').show();
			document.getElementById('audio-3').pause();
			document.getElementById('audio-4').play();
			setTimeout(function() {
				$('#p-weixin-4').hide();
				$('#p-weixin-5').show();
			}, 9000);
		});
		
		//接电话
		$('#playArea2').bind('touchend',function(event) {
			$('#p-weixin-3').hide();
			$('#p-weixin-4').show();
			document.getElementById('audio-3').pause();
			document.getElementById('audio-4').play();
			setTimeout(function() {
				$('#p-weixin-4').hide();
				$('#p-weixin-5').show();
			}, 10000);
		});
	});
})

function wordSendClick() {
	$('#noYou').html('');
	$('#wordSend').hide();
	$('#sendWordSpan').show();
	setTimeout(function(){
		document.getElementById('audio-2').play();
		$("#chatImg").css("-webkit-transition", "all .5s ease-in-out");
	    $("#chatImg").css({"-webkit-transform":"translateY(0px)"});
	}, 500);
}
