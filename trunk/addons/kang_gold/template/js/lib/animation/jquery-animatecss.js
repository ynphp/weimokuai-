
(function($) {
    /**
     * Copyright 2014, Min Utvikler
     * http://minutvikler.no/
     *
     * Licensed under the MIT license.
     *
     * @author Kristoffer A. Iversen
     * @description A small plugin that simplifies adding animations to
     *              jQuery selections using animate.css. Provides a callback
     *              interface and an optional sleep thereafter before the
     *              callback is initiated.
     */
    $.fn.animateCss = function(name, callback, delay) {
        var func = function(elm, n, cb, d) {
            if (d === undefined || d === null) {
                d = 0;
            }
            elm.addClass('animated ' + n);
            elm.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
                elm.removeClass('animated ' + n);
                if (typeof cb === 'function') {
                    setTimeout(cb, d);
                }
            });
        }
        if (this.length > 1) {
            this.each(function() {
                func($(this), name, callback, delay);
            });
        }else{
            func(this, name, callback, delay);
        }
        return true;
    }
})(jQuery);

