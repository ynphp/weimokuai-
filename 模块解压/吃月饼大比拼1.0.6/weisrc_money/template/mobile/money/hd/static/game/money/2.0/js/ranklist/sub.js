if ($("#ranking-list").length > 0){   
	var rankScroll;
	function loaded () {
		rankScroll = new IScroll('#ranking-list', { mouseWheel: true, click :true });
	}
	window.onload=function(){
		loaded();
	};
};
if ($("#about").length > 0){   
	var aboutScroll;
	function aboutloaded () {
		aboutScroll = new IScroll('#about', { mouseWheel: true, click :true });
	}
	window.onload=function(){
		aboutloaded();
	};
};
