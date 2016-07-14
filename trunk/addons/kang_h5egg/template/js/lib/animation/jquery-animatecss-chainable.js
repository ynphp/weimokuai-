(function( $ ){


$.fn.animateCSS = function( anime ) {

    var props = {
        'transition-property': 'all', 
        '-webkit-transition-property': 'all'
    }, delay = 0, duration = 0, repeat = 1, queue = "fx";
    
    if (anime.queue) {
        queue = anime.queue;
    }

	if (anime.duration) {
	    $.extend(props, {
	        "transition-duration": anime.duration,
	        "-webkit-transition-duration": anime.duration
	    });
	    // Transform s to ms
	    if (anime.duration.indexOf("ms") == -1) {
	        duration = parseFloat(anime.duration) * 1000;
	    }
	    else duration = parseInt(anime.duration);
	}
	
	if (anime.timing_function) {
	    $.extend(props, {
	        "transition-timing-function": anime.timing_function,
	        "-webkit-transition-timing-function": anime.timing_function
	    });
	}
	
	if (anime.delay) {
	    $.extend(props, {
	        "transition-delay": anime.delay,
	        "-webkit-transition-delay": anime.delay
	    });
	    // Transform s to ms
	    if (anime.delay.indexOf("ms") == -1) {
	        delay = parseFloat(anime.delay) * 1000;
	    }
	    else delay = parseInt(anime.delay);
	}
	
	var obj = $(this), css = anime.css;
	
	var doAnimation = function(next) {
	    obj.css(props).css(css);
	    // Move on to next effect on queue
	    next();
	}
	
	$(this).queue(queue, doAnimation).delay(delay + duration * repeat, queue);
    
    return this;
	
};

})(jQuery);
