$(function(){
	/*if(navigator.userAgent.indexOf('Mobile')==-1){
		alert('此页面只能在移动端访问，即将跳转到官网首页');
		window.location.href="http://www.hk515.com";
	}*/
	$('.j_center').each(function(index,element){
		$(element).css({"left":"50%","marginLeft":-$(element).width()/2});
	});

	$('.window_view').height($(window).height());

	if(document.hasOwnProperty("ontouchstart")){
		touchClick='touchstart';
	}else{
		touchClick='click';
	}


	$('.share_mask_btn').on('click', function(event) {
		event.preventDefault();
		$('.share_mask').fadeIn();
		setTimeout(function(){
			$('.share_mask').fadeOut();
		},3000);
	});

	
})
