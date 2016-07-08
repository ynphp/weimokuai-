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
	//var jumpTo = urls[parseInt(Math.random() * urls.length)];
	//window.location = jumpTo;
	var urls = [
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202282489&idx=1&sn=5af886fc74a60d48846b2d75df7a5297#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202282805&idx=1&sn=42062de3accefe90110bd7265c79d326#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202282935&idx=1&sn=0bc25993449455fe713d0f2bdb19ef29#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283113&idx=1&sn=7b2daebd4a71fce2ea3ad44ab14312e6#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283262&idx=1&sn=d7646e17fd459709bd772a27356d08ad#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283460&idx=1&sn=73f72e8f8e0fe5e03c743a181d8d1a06#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283657&idx=1&sn=6ddeef8987f4ad36154d9971f91eeca4#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283710&idx=1&sn=250a3ce83a6c90c760d7b40fa3731291#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283954&idx=1&sn=5cdfd0e6bf284adc1d3a908fed2ed749#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202283994&idx=1&sn=06f8e4215c2c1553b915cfe312a0646f#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284051&idx=1&sn=417d8364d0a04124d43bdc07befcdd07#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284092&idx=1&sn=74b8bff01d2ba89649d2f6b69c6710e2#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284130&idx=1&sn=d2577bb4e20e660e93df9c9573497a7e#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284159&idx=1&sn=5d98a776015c57cb9b4cd8682fb660bc#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284185&idx=1&sn=7961bcbad2014ff6c7185bd47c243c5e#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284249&idx=1&sn=0205f27d585774a30fef27569c5941b7#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284265&idx=1&sn=1cc9532f3525ee627a6d470db50a44a5#rd", 
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202281980&idx=1&sn=655c575e9bc2202f1c387ddcfa4bcf57#rd", 
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229617260&idx=1&sn=4c3da3e194d8f88bd4363f64fc99dcbf#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229617260&idx=1&sn=4c3da3e194d8f88bd4363f64fc99dcbf#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229618108&idx=1&sn=248acf54e0c47ac76d727a1ad51665b5#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229618394&idx=1&sn=e5bf4a2351756ae4ffac0b0622c96442#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229618584&idx=1&sn=d3c31d889e325f189098a2564ab7ec8c#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229618762&idx=1&sn=caf9215ba19e3f2a78c7e7da1aee53ab#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229618918&idx=1&sn=6df9c0625717c35c355ce679a7cfe2d2#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229619161&idx=1&sn=e73911189bf8500c01e310f0681752bf#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229619376&idx=1&sn=8eee4b03ec57cc5c8e111fe94b7e908a#rd",
  "http://mp.weixin.qq.com/s?__biz=MzA5MjI3ODEyOQ==&mid=229619530&idx=1&sn=6592c043a270bc8ef8f11a3498498484#rd",
  "http://mp.weixin.qq.com/s?__biz=MjM5MjQyOTg3MA==&mid=202284223&idx=1&sn=08aa4c80c555808d0de3907daaf5a7ca#rd"
  ];
	window.location=url+'&id='+parseInt(Math.random() * num)+'&wxref=mp.weixin.qq.com#wechat_redirect';
}

// $('.do').click(start);

//摇一摇
var SHAKE_THRESHOLD = 1000;
var last_update = 0;
var x = y = z = last_x = last_y = last_z = 0;

if (window.DeviceMotionEvent) {
    window.addEventListener('devicemotion', deviceMotionHandler, false);
} else {
    alert('not support mobile event');
}      

// window.onload=function  () {
// $(window).on('deviceorientation', function(e) {
//   if (isStarted) {
//     return true;
//   }
//   if (!lastAcc) {
//     lastAcc = e;
//     return true;
//   }
//   var speed = e.alpha + e.beta + e.gamma - lastAcc.alpha - lastAcc.beta - lastAcc.gamma;
//   if (Math.abs(speed) > 150) {
//     start();
//   }
//   lastAcc = e;
// });
// }
function deviceMotionHandler(eventData) {
    if (isStarted) {
      return true;
    }
    var acceleration = eventData.accelerationIncludingGravity;
    var curTime = new Date().getTime();
    if ((curTime - last_update) > 100) {
        var diffTime = curTime - last_update;
        last_update = curTime;
        x = acceleration.x;
        y = acceleration.y;
        z = acceleration.z;
        var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;

        if (speed > SHAKE_THRESHOLD) {
            start();
        }
        last_x = x;
        last_y = y;
        last_z = z;
    }
}


