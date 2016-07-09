$(function(){

	$(".mod-hide").click(function(){
		clearInterval(modResize);
		$(this).parents(".modal").fadeOut(400);
		$(this).parent(".mod-area").animate({'top': 0}, 400 )
	})	

	var modResize = setInterval(function () {
		modalTop()
	},1000)
	
	setTimeout(function () {
		clearInterval(modResize);
	},8000)

});

function modal(obj,Oheight){
	obj = $("#" + obj )
	obj.find(".mod-area").css("top",0);
	obj.fadeIn(400);
	if(Oheight){
	  Oheight = Oheight
	}else{
		Oheight = .6
	}
	obj.find(".mod-con").css("max-height",$(window).height()*Oheight);
	obj.find(".mod-area").css({'margin-top': - parseInt(obj.find(".mod-area").height()/2)})
	obj.find(".mod-area").animate({'top': '50%'}, 600 )
}

function modalTop(obj){
	$(".modal").each(function() {
		if($(this).is(":visible")){
			$(this).find(".mod-area").animate({'margin-top': - parseInt($(this).find(".mod-area").height()/2)},300)
		}
	});
}
