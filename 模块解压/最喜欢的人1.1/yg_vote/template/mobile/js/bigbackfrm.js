/**



 **/

(function($) {

	$.fn.bigbackfrm = function(settings) {

		settings = jQuery.extend({
			width:0,
			alpha:0.5

		}, settings);
		var object = this;
		function inti(){
			if(settings.width == 0) settings.width = $(document.body).width();
			$(object).hide();
			$(object).css(
				{
					width:settings.width , 
					height:$(document.body).height(),
					opacity:settings.alpha,
					background:"#000000",
					position:"fixed",
					"top":0,
					left:0,
					"z-index":9
				}
			);
		}

		return inti();

	};

})(jQuery);

(function($) {

	$.fn.popfrm = function(settings) {

		settings = jQuery.extend({
			width:0,
			background:"",
			height:0

		}, settings);
		var object = this;
		function inti(){
			if(settings.width == 0) settings.width = $(object).outerWidth();
			if(settings.height == 0) settings.height = $(object).outerHeight();
			$(object).hide();
			$(object).css(
				{
					"margin-left": -settings.width*0.5 , 
					"margin-top":-settings.height*0.5,
					position:"fixed",
					"top":"50%",
					left:"50%",
					"z-index":19
				}
			);
			return {showpop:showpop,hidepop:hidepop,fixed:fixed};
		}
		
		var fixed = function(){
			settings.width = $(object).outerWidth();
			settings.height = $(object).outerHeight();
			$(object).css(
				{
					"margin-left": -settings.width*0.5 , 
					"margin-top":-settings.height*0.5
				}
			);
		}
		
		var showpop = function(){
			fixed();
			$(object).show();
			if(settings.background != ''){
				$(settings.background).show();
			}
		}
		
		var hidepop = function(){
			$(object).hide();
			if(settings.background != ''){
				$(settings.background).hide();
			}
		}

		return inti();

	};

})(jQuery);