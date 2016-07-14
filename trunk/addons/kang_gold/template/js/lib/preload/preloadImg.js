var preloadimg = function(arr,comp){
	var n = 0;
	var loadImg = function(src){
		var img = new Image();
		img.onload = function(){
			n++;
			var t = Math.round(n/l*508);
			// $("#loadingBar").css("width",t);
			if(n == l){
				comp();
			}
		}
		img.src = src;
	}
	if(typeof(arr) == "string"){
		var l = 1;
		var w = new loadImg(arr);
	}else{
		var l = arr.length;
		for(var i=0;i<l;i++){
			var w = new loadImg(arr[i]);
		}
	}
}