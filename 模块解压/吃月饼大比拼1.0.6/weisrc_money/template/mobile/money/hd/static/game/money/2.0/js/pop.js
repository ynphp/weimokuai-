jQuery(function($){
	$("[data-pop]").bind("click",function(){
		var obj = $(this).attr("data-pop");
		    $(".pop").hide();
			$("#"+obj).fadeIn();
			if (obj=="rankinglist"){ window.loaded(); }
	});
	$("[data-close]").bind("click",function(){
		var obj = $(this).attr("data-close");
		if (obj=="1"){ $(this).parents(".pop").fadeOut(); }
    })
    $("#invite-share,#tips").bind("click",function(){
		$(this).fadeOut();
    })
    $(".close").bind("click",function(){
		$(".cover").fadeOut();
    })
});
