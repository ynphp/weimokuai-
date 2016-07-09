$(function(){
	//一行行向上滚动
	var _rollLish = $(".chatArea");
	var scrollTimer;
	_rollLish.hover(function(){
			clearInterval(scrollTimer);
	 },function(){
		 scrollTimer = setInterval(function(){
			 if(_rollLish.height() < _rollLish.find("ul").height()){
		 	  scrollTips( _rollLish );
			 }
		 }, 1800 );
	}).trigger("mouseleave");
	
  $(".Stips li").on('click', function () {
    var stips = $(this).text();
    	$(".Finput").focus();
		$(".Finput").val(stips);
  });
//	$(".Fbtn1").on('click', function () {
//		var input = $(".Finput").val();
//		if(input == '') {
//				var Tinput = '请输入您的评论！';
//				$("#mod-from .mod-text").html(Tinput);
//				modal("mod-from")
//				return false;
//		}else if(input.length > 15){
//				var Tinput = "评论字数不能超过15个字！";
//				$("#mod-from .mod-text").html(Tinput);
//				modal("mod-from")
//				return false;
//		}
//		
//		if($(".chatArea li").last().find('a').length === 1){
//			$(".chatArea li").last().append('<a href="javascript:;">' + input + '<i></i></a>')
//		}else{
//			$(".chatArea ul").append('<li><a href="javascript:;">' + input + '<i></i></a></li>')
//		}
//		$(".Finput").val('');
//		modal("comment")
//  });
//	$(".chatArea li a i").on('click', function () {
//		if($(this).parent('a').siblings('a').length === 1){
//			$(this).parent('a').remove();
//		}else{
//			$(this).parents('li').remove();
//		}
//	})
//	
//	$(".chatArea li a i").on('click', function () {
//		if($(this).parent('a').siblings('a').length === 1){
//			$(this).parent('a').remove();
//		}else{
//			$(this).parents('li').remove();
//		}
//	})

})

function scrollTips(obj){
	var $self = obj.find("ul:first"); 
	var lineWidth = $self.find("li:first").height();
	$self.animate({ "margin-top" : -lineWidth +"px" }, 500, function(){
		$self.css({"margin-top":0}).find("li:first").appendTo($self);
	})
}

