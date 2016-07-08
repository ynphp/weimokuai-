window.requestAnimationFrame = (function(){
  return  window.requestAnimationFrame       ||
          window.webkitRequestAnimationFrame ||
          window.mozRequestAnimationFrame    ||
          function( callback ){
            window.setTimeout(callback, 1000 / 60);
          };
})();


var Zhu = {
		_elementStyle	: document.createElement('div').style,	
		_UC 			: RegExp("Android").test(navigator.userAgent)&&RegExp("UC").test(navigator.userAgent)? true : false,
		_weixin			: RegExp("MicroMessenger").test(navigator.userAgent)? true : false,
		_iPhone			: RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)? true : false,
		_Android		: RegExp("Android").test(navigator.userAgent)? true : false,
		_IsPC			: function(){ 
						var userAgentInfo = navigator.userAgent; 
						var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
						var flag = true; 
						for (var v = 0; v < Agents.length; v++) { 
							if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
						} 
						return flag; 
		} ,	
		_isOwnEmpty		: function (obj) { 
						for(var name in obj) { 
							if(obj.hasOwnProperty(name)) { 
								return false; 
							} 
						} 
						return true; 
					},
	
		// 判断浏览器内核类型
		_vendor			: function () {
							var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
								transform,
								i = 0,
								l = vendors.length;
					
							for ( ; i < l; i++ ) {
								transform = vendors[i] + 'ransform';
								if ( transform in document.createElement('div').style ) return vendors[i].substr(0, vendors[i].length-1);
							}
							return false;
						},
		// 判断浏览器来适配css属性值
		_prefixStyle	: function (style) {
							if ( this._vendor() === false ) return false;
							if ( this._vendor() === '' ) return style;
							return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
						},
	
		_translateZ : function(){
							if(Zhu._hasPerspective){
								return ' translateZ(0)';
							}else{
								return '';
							}
						}
		
	}

$.GetRandomNum = function (Min,Max)
{   
		var Range = Max - Min;   
		var Rand = Math.random();   
		return(Min + Math.round(Rand * Range));   
};

function LoadFn ( arr , fn , fn2){
		var loader = new PxLoader();
		for( var i = 0 ; i < arr.length; i ++)
		{
			loader.addImage(arr[i]);
		};
		
		loader.addProgressListener(function(e) {
				var percent = Math.round( e.completedCount / e.totalCount * 100 );
				if(fn2) fn2(percent)
		});	
		
		
		loader.addCompletionListener( function(){
			if(fn) fn();	
		});
		loader.start();	
};