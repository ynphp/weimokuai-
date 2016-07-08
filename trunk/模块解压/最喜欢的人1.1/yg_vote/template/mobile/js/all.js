
function is_weixn(){  
	var ua = navigator.userAgent.toLowerCase();  
	if(ua.match(/MicroMessenger/i)=="micromessenger") {  
		return true;  
	} else {  
		return true;  
	}  
}

$(function(){
	if(!is_weixn()){
		//window.location = 'mobile.php?act=entry&eid=27&weid=2&id=1';
		//return;
	}	
});