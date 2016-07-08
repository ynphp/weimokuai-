window.onload = function(){
	
		/*初始化*/
		var wWidth = document.documentElement.clientWidth;
		var wHeight = document.documentElement.clientHeight;
		var oBox = document.getElementById("box");
		oBox.style.width = wWidth + 'px';
		oBox.style.height = wHeight + 'px';


		var oBtn = document.getElementById("btn_bt");
		var oP = document.getElementById("content");
		var timeStart = 0;
		var timeEnd = 0;

		
		function absorbEvent_(event) {
		var e = event || window.event;
		e.preventDefault && e.preventDefault();
		e.stopPropagation && e.stopPropagation();
		e.cancelBubble = true;
		e.returnValue = false;
		return false;
		}
		function preventLongPressMenu(node) {
		node.ontouchstart = absorbEvent_;
		node.ontouchmove = absorbEvent_;
		node.ontouchend = absorbEvent_;
		node.ontouchcancel = absorbEvent_;
		}
		preventLongPressMenu(oBtn);

		/*触摸事件*/
		oBtn.addEventListener("touchstart",function(){

			timeStart = (new Date()).valueOf();
			oBtn.className = "active";
			
		},false);

		oBtn.addEventListener("touchend",function(){
			timeEnd = (new Date()).valueOf();
			time = (timeEnd - timeStart)/1000;
			oBtn.className = "";
			var text = '';
			var text2 = '';
			var title = '';

		if(time >0 && time <= 0.6) {
	        text = '<h2>'+time+'</h2>&nbsp;秒<br/>要决战到天亮的节奏啊！';
	        text2 = ''+time+'秒,你还差得远呢?';
	        title = '我按出了'+time+'秒，你能按出1秒吗？';
	    } else if(time > 0.6 && time <= 0.9) {
	        text = '<h2>'+time+'</h2>&nbsp;秒<br/>差距只在呼吸间!';
	        text2 = ''+time+'秒,差距只在呼吸间!';
	        title = '我按出了'+time+'秒，你能按出1秒吗？';
	    } else if(time >0.9 && time <= 1.0) {
	        text = '<h2>'+time+'</h2>&nbsp;秒<br/>叼爆了! 你是开挂了吧！';
	        text2 = ''+time+'秒,叼爆了! 你是开挂了吧！';
	        title = '我按出了'+time+'秒，你能按出1秒吗？';
	    }else if(time > 1 && time <= 1.1) {
	        text = '<h2>'+time+'</h2>&nbsp;秒<br/>人中极品，人品要爆发了！';
	        text2 = '1.00秒,完美';
	        title = '我按出了'+time+'秒，你能按出1秒吗？';
	    } else {
	        text = '<h2>'+time+'</h2>&nbsp;秒<br/>过了，过了，你火星时间吧？';
	        text2 = ''+time+'秒，你火星时间吧？！';
	        title = '我按出了'+time+'秒，你能按出1秒吗？';
	    }

	    oP.innerHTML = text ;
	    document.title = title;

		},false);
		
		var aShare = document.getElementById("share_a");
		var divShare = document.getElementById("share");
		aShare.addEventListener("touchstart",function(){
			divShare.style.display = "block";
			document.addEventListener("touchmove",function(){
				divShare.style.display = "none";
			},false)
		},false);


	

}