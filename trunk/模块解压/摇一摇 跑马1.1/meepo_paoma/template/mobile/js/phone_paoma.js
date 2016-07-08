$(function() {
    var m = $(window).width() < $(window).height() ? $(window).width() * 1: $(window).height() * 1;
    var o = false;
    var l = $(".shake-icon");
    var t = $(".shake-icon").offset().top + $(".shake-icon").height() + 20;
    var a = $(".memo").css({
        top: t + "px"
    });
	var count = 0;
	

    $(window).on("resize", 
    function() {
        var u = $(".shake-icon").offset().top + $(".shake-icon").height() + 20;
        $(".memo").css({
            top: u + "px"
        })
    });
    var p = /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent);
    a.html("点一下图标开启音乐更刺激");
    l.one("click", 
    function() {
        f();
        a.html("听从现场指挥摇动手机")
    });
    if (window.DeviceMotionEvent) {
        window.addEventListener("devicemotion", g, false)
    } else {
        a.html("您的手机不支持摇一摇，您可以通过点击图标参与。");
        l.on("click", 
        function() {
            var u = Math.random() * 10;
            if (u < 5) {
                f();
                $("body").addClass("shakeing");
                q()
            } else {
                $("body").removeClass("shakeing")
				 q();
            }
        })
    }
    function h() {
		var n = ['加油~~~',count];
        return n[(Math.floor(Math.random() * n.length))]
    }
    var c = document.getElementById("ShakeAudio");
    function f() {
        if (c) {
            if (c.paused) {
                c.play()
            }
        }
    }
    var r = 500;
	var ksl = false;
    if (!p) {
        r = 800
    }
    var s = 0;
    var k,
    j,
    i,
    e,
    d,
    b;
    function g(w) {
        var v = w.accelerationIncludingGravity;
        var y = (new Date()).getTime();
		 
        if ((y - s) > 80) {
            var u = y - s;
            s = y;
            k = v.x;
            j = v.y;
            i = v.z;
            var x = Math.abs(k + j + i - e - d - b) / u * 10000;
            if (x > r) {
                f();
                $("body").addClass("shakeing");
				
				count++;
                	q();
				 
            } else {
                $("body").removeClass("shakeing")
            }
            e = k;
            d = j;
            b = i
        }
    }
    function q() {
		 
		 
	 
        if (!o) {
            $.getJSON("paoma.php", {
                scene_id: scene_id,
                uid: uid
            },
            function(u) {
                if (u.ret == 2001) {
                    o = true;
                    a.html("该微现场没有开启摇一摇游戏");
					  
                } else {
                    if (u.ret == 2002) {
                        a.html("淡定，淡定，游戏还没开始！");
						   
                    } else {
                        if (u.ret == 0) {
							 
                            a.html(h()+u.msg)
                        }
						 if (u.ret == 1) {
							 
                            a.html(u.msg)
                        }
                    }
                }
            }).complete(function() {})
        }
   
}
});