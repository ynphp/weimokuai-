new Image().src="../addons/hunter_ballot/images/decode.png";
new Image().src="../addons/hunter_ballot/images/234.png";
var start, showDecode, jumpToDecode, lastTime, lastAcc, isStarted = false;

start = function() {
	isStarted = true;
	$('.decode').hide();
	$('.result').show();
	setTimeout(showDecode, 3000);
}

showDecode = function(){
	$('.result').hide();
	$('.decode').show();
	setTimeout(jumpToDecode, 3000);
}

jumpToDecode = function(){
	// var urls = [
	// "../addons/hunter_ballot/images/1.jpg", 
	// "../addons/hunter_ballot/images/2.jpg", 
	// "../addons/hunter_ballot/images/3.jpg", 
	// "../addons/hunter_ballot/images/4.jpg", 
	// "../addons/hunter_ballot/images/5.jpg", 
	// "../addons/hunter_ballot/images/6.jpg", 
	// "../addons/hunter_ballot/images/7.jpg", 
	// "../addons/hunter_ballot/images/8.jpg", 
	// "../addons/hunter_ballot/images/9.jpg", 
	// "../addons/hunter_ballot/images/10.jpg", 
	// "../addons/hunter_ballot/images/11.jpg", 
	// "../addons/hunter_ballot/images/12.jpg", 
	// "../addons/hunter_ballot/images/13.jpg", 
	// "../addons/hunter_ballot/images/14.jpg", 
	// "../addons/hunter_ballot/images/15.jpg", 
	// "../addons/hunter_ballot/images/16.jpg", 
	// "../addons/hunter_ballot/images/17.jpg", 
	// "../addons/hunter_ballot/images/18.jpg", 
	// "../addons/hunter_ballot/images/19.jpg", 
	// ];
	// var jumpTo = urls[parseInt(Math.random() * urls.length)];
	// window.location = jumpTo;
	window.location = "../addons/hunter_ballot/template/mobile/ballot.html";
}

$('.do').click(start);

//摇一摇
$(window).on('deviceorientation', function(e) {
	if (isStarted) {
		return true;
	}
	if (!lastAcc) {
		lastAcc = e;
		return true;
	}
	var speed = e.alpha + e.beta + e.gamma - lastAcc.alpha - lastAcc.beta - lastAcc.gamma;
	if (Math.abs(speed) > 100) {
		start();
	}
	lastAcc = e;
});

//微信分享  失效了，有时间的可以根据官方公布的 JS-SDK进行开发

// 自定义分享数据的调用
sharedata = {
	title: '微信JS-SDK Demo',
	desc: '微信JS-SDK,帮助第三方为用户提供更优质的移动web服务',
	link: 'http://demo.open.weixin.qq.com/jssdk/',
	imgUrl: 'http://mmbiz.qpic.cn/mmbiz/icTdbqWNOwNRt8Qia4lv7k3M9J1SKqKCImxJCt7j9rHYicKDI45jRPBxdzdyREWnk0ia0N5TMnMfth7SdxtzMvVgXg/0',
	success: function(){
		alert('xixi');
	}
};


// var shareMeta = {
// 	img_url: "http://www.imeiwen.com/2015/thumbnail.gif",
// 	image_width: 100,
// 	image_height: 100,
// 	link: 'http://www.imeiwen.com/2015/index.html',
// 	title: "2015乙未羊，为自己摇枚新年签！",
// 	desc: "这是对过去的感悟和对新年的祈望，希望它能为你带来好运...",
// 	appid: ''
// };
// document.addEventListener('WeixinJSBridgeReady', function () {
// 	WeixinJSBridge.on('menu:share:appmessage', function(){
// 		WeixinJSBridge.invoke('sendAppMessage', shareMeta);
// 	});
// 	WeixinJSBridge.on('menu:share:timeline', function(){
// 		WeixinJSBridge.invoke('shareTimeline', shareMeta);
// 	});
// 	WeixinJSBridge.on('menu:share:weibo', function(){
// 		WeixinJSBridge.invoke('shareWeibo', {
// 			content: shareMeta.title + shareMeta.desc,
// 			url: shareMeta.link
// 		});
// 	});
// });