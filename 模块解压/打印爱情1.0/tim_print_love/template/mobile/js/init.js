function DrawText(g, f, k) {
	var j = CTXARR[g],
		h = IMGARR[g];
	j.drawImage(h, 45 * f, 44 * k, 45, 44, 45 * f, 44 * k, 45, 44)
}
function MainDraw() {
	var e, f;
	if (CLICKSTOP) {
		if (11 == d[D_NUM][D_Y]) {
			return CLICKSTOP = !1, D_Y += 4, D_X = 0, e = 0, DefaultX = Math.max(0, Math.min(280, 45 * D_X)), MainTransLateY = b[D_NUM + 1] + 44 * (D_Y - 1) - 44, 1 == D_NUM && ($(".gamebox").css("transform", "translate(0,-884px)"), f = new Image, f.src = "http://statics.oneplus.cn/promotion/mrx_h5/images/x/1.png", f.onload = function() {
				$(".xlz1").addClass("back").html("");
				var a = setInterval(function() {
					e++;
					var g = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0];
					17 == g[e] && $(".xlz1img").show(), g[e] || (clearInterval(a), CLICKSTOP = !0), $(".xlz1")[0].style.backgroundPosition = "0px " + -139 * g[e] + "px"
				}, 50)
			}, playsound(2)), 2 == D_NUM && (playsound(3), $(".gamebox").css("transform", "translate(0,-1990px)"), f = new Image, f.src = "http://statics.oneplus.cn/promotion/mrx_h5/images/x/2.png", f.onload = function() {
				$(".xlz2").addClass("back").html("");
				var a = setInterval(function() {
					e++;
					var g = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0];
					21 == g[e] && $(".xlz2img").show(), g[e] || (clearInterval(a), CLICKSTOP = !0), $(".xlz2")[0].style.backgroundPosition = "0px " + -139 * g[e] + "px"
				}, 50)
			}), 3 == D_NUM && (playsound(4), $(".gamebox").css("transform", "translate(0,-3394px)"), f = new Image, f.src = "http://statics.oneplus.cn/promotion/mrx_h5/images/x/3.png", f.onload = function() {
				$(".xlz3").addClass("back").html("");
				var a = setInterval(function() {
					e++, 32 == e && (clearInterval(a), CLICKSTOP = !0), $(".xlz3")[0].style.backgroundPosition = "0px " + -139 * e + "px"
				}, 50)
			}), 4 == D_NUM && (playsound(5), $(".gamebox").css("transform", "translate(0,-4640px)"), f = new Image, f.src = "http://statics.oneplus.cn/promotion/mrx_h5/images/x/4.png", f.onload = function() {
				$(".xlz4").addClass("back").html("");
				var a = setInterval(function() {
					e++, 34 == e && (clearInterval(a), CLICKSTOP = !0), $(".xlz4")[0].style.backgroundPosition = "0px " + -139 * e + "px"
				}, 50)
			}), 5 == D_NUM && (playsound(6), $(".gamebox").css("transform", "translate(0,-6023px)"), f = new Image, f.src = "http://statics.oneplus.cn/promotion/mrx_h5/images/x/5.png", f.onload = function() {
				$(".xlz5").addClass("back").html("");
				var a = setInterval(function() {
					e++, 35 == e && (clearInterval(a), CLICKSTOP = !0), $(".xlz5")[0].style.backgroundPosition = "0px " + -139 * e + "px"
				}, 50)
			}), ClearTimer(), playsound(8), $(".s1-2-2").css({
				left: 130
			}), TIMER6 = setTimeout(function() {
				$(".s1-2-2").css({
					top: 90
				}), $(".szhen")[0].style.backgroundPosition = "0px 0px"
			}, 150), $(".s1-4").removeClass("on"), void 0
		}
		DrawText(D_NUM, D_X, D_Y), D_X++, SCROLLTOP = -MainTransLateY, DefaultX = Math.max(0, Math.min(280, 45 * D_X)), $(".s1-2-2").css("left", DefaultX), MainTransLateY = b[D_NUM + 1] + 44 * (D_Y - 1) - 44, $(".gamebox").css("transform", "translate(0,-" + MainTransLateY + "px)"), D_X >= d[D_NUM][D_Y] && ($(".s1-2-2").css("left", 0), D_Y++, D_X = 0, $(".s1-2-3 , .s1-2-4").addClass("hover"), setTimeout(function() {
			$(".s1-2-3 , .s1-2-4").removeClass("hover")
		}, 100), D_Y >= d[D_NUM].length && (D_NUM++, D_X = 0, D_Y = 0), D_NUM >= 6 ? (ClearTimer(), playsound(8), $(".s1-2-2").css({
			left: 130
		}), TIMER6 = setTimeout(function() {
			$(".s1-2-2").css({
				top: 90
			}), $(".szhen")[0].style.backgroundPosition = "0px 0px"
		}, 150), $(".s1-4").removeClass("on"), $(".s1-4").unbind("touchstart").bind("touchstart", function() {
			pageLastAnimate()
		})) : (clearTimeout(TIMER3), TIMER3 = setTimeout(function() {
			clearInterval(TIMER2), TIMER2 = setInterval(MainDraw, 200)
		}, 150)))
	}
}
function pageLastAnimate() {
	$(".ajaxloading").show(), ClearTimer(), $(".lastdivbox .btn1 , .lastdivbox .btn2 , .lastdivbox .btn3").hide(), 
	CanvasDraw($("#lastcanvas")[0], "http://statics.oneplus.cn/promotion/mrx_h5/images/cs", 36, function() {
		$(".lastdivbox .btn1 , .lastdivbox .btn2 , .lastdivbox .btn3").fadeIn()
	}, function(e) {
		100 == e && (setTimeout(function() {
			$(".gamebox").css("transform", "translate(0,-7420px)").unbind("touchstart")
		}, 30), $(".lastdivbox").show()), $(".loads").html("loading...<br />" + e + "%")
	})
}
function cunt() {
	20 == ZHEN_INDEX && (sub = !0, add = !1), 12 == ZHEN_INDEX && (add = !0, sub = !1), add && ZHEN_INDEX++, sub && ZHEN_INDEX--
}
function ZHEN_ANIMATE() {
	$(".szhen")[0].style.backgroundPosition = "0px " + -121 * ZHEN_INDEX + "px", cunt()
}
function ClearTimer() {
	clearInterval(TIMER), clearInterval(TIMER2), clearTimeout(TIMER3), clearInterval(TIMER4), clearTimeout(TIMER5), clearTimeout(TIMER6)
}
function CanvasDraw(w, v, u, t, r) {
	function n() {
		if (0 !== p % (Zhu._Android ? 2 : 3)) {
			return p++, requestAnimationFrame(n), void 0
		}
		var a = new Image;
		return a.src = v + "/" + ++o + ".png", 17 == o && "http://statics.oneplus.cn/promotion/mrx_h5/images/c" == v && ($(".s1-2 , .s1-3 , .s1-4 , .s1-5").hide(), 
			$(".sound-on").hide(), CanvasDraw($(".lastdivbox_txt canvas")[0], "http://statics.oneplus.cn/promotion/mrx_h5/images/d", 30, function() {}, function() {})), 
		a.onload = function() {
			q.clearRect(0, 0, w.width, w.height), q.drawImage(a, 0, 0, w.width, w.height)
		}, o > u ? (t && t(), void 0) : (p++, requestAnimationFrame(n), void 0)
	}
	var p, o, h, g, q = w.getContext("2d");
	for ($(w).width(), $(w).height(), p = 1, o = 0, h = [], g = 1; u > g; g++) {
		h.push(v + "/" + g + ".png")
	}
	LoadFn(h, function() {
		n(), $(".ajaxloading").hide()
	}, function(e) {
		r(e)
	})
}
var i, s, IMGARR, CTXARR, CLICKSTOP, add, sub, d = [
	[8, 0, 7, 10, 7, 0, 4, 4, 7],
	[10, 10, 10, 10, 10, 10, 10, 11, 0, 0, 0, 10, 10, 5, 9, 5, 0, 7, 8, 8],
	[10, 10, 10, 10, 10, 10, 10, 11, 0, 0, 0, 9, 4, 8, 7, 0, 10, 7, 7, 10, 10, 10, 0, 10, 4, 5, 9],
	[10, 10, 10, 10, 10, 10, 10, 11, 0, 0, 0, 8, 7, 10, 7, 10, 8, 8, 0, 3, 8, 10, 6, 9],
	[10, 10, 10, 10, 10, 10, 10, 11, 0, 0, 0, 3, 6, 7, 4, 7, 6, 0, 10, 9, 9, 7, 0, 4, 6, 5],
	[10, 10, 10, 10, 10, 10, 10, 11, 0, 0, 0, 10, 7, 7, 0, 9, 9, 3, 0, 8, 0, 7]
],
	b = [48, 86, 594, 1708, 3103, 4355, 5730],
	c = [440, 963, 1400, 1147, 1152, 988],
	D_NUM = 0,
	D_X = 0,
	D_Y = 0,
	TIMER = null,
	TIMER2 = null,
	TIMER3 = null,
	TIMER4 = null,
	TIMER5 = null,
	TIMER6 = null,
	ZHEN_INDEX = 0,
	DefaultX = 0,
	MainTransLateY = 0,
	IsPc = !1,
	SCROLLTOP = 0,
	_SCROLLTOP = 0;
for (i = 1; i < b.length; i++) {
	s = "position:absolute;left:48px;top:" + b[i] + "px;", $(".gamebox").append('<canvas id="canvas' + i + '" style="' + s + '" width="450" height="' + c[i - 1] + '"></canvas>	')
}
IMGARR = [document.getElementById("img1"), document.getElementById("img2"), document.getElementById("img3"), document.getElementById("img4"), document.getElementById("img5"), document.getElementById("img6")], CTXARR = [document.getElementById("canvas1").getContext("2d"), document.getElementById("canvas2").getContext("2d"), document.getElementById("canvas3").getContext("2d"), document.getElementById("canvas4").getContext("2d"), document.getElementById("canvas5").getContext("2d"), document.getElementById("canvas6").getContext("2d")], CLICKSTOP = !0, add = !0, sub = !1, $(".s1-4").bind("touchstart", function(e) {
	e.preventDefault(), CLICKSTOP && ($(this).addClass("on"), playsound(7), $(".gamebox")[0].style[Zhu._prefixStyle("transform")] = "translate3d(0," + -MainTransLateY + "px,0)", $(".s1-2-2").css({
		top: 13
	}), ClearTimer(), TIMER6 = setTimeout(function() {
		$(".s1-2-2").css({
			left: DefaultX
		})
	}, 150), add = !0, sub = !1, ZHEN_INDEX = 0, TIMER4 = setInterval(ZHEN_ANIMATE, 30), TIMER5 = setTimeout(function() {
		TIMER = setInterval(MainDraw, 200)
	}, 200))
}).bind("touchend", function(e) {
	e.preventDefault(), ClearTimer(), playsound(8), $(".s1-2-2").css({
		left: 130
	}), TIMER6 = setTimeout(function() {
		$(".s1-2-2").css({
			top: 90
		}), $(".szhen")[0].style.backgroundPosition = "0px 0px"
	}, 150), $(this).removeClass("on")
}), $(".gamebox").bind("touchstart", function(f) {
	var e, g;
	_SCROLLTOP = SCROLLTOP, e = IsPc ? f || event : event || f, IsPc ? e.pageX : e.touches[0].clientX, g = IsPc ? e.pageY : e.touches[0].clientY, $(".gamebox")[0].ontouchmove = function(j) {
		var h, k;
		j.preventDefault(), h = IsPc ? j || event : event || j, IsPc ? h.pageX : h.touches[0].clientX, k = IsPc ? h.pageY : h.touches[0].clientY, SCROLLTOP = _SCROLLTOP + (k - g), SCROLLTOP = Math.min(50, Math.max(-MainTransLateY, SCROLLTOP)), this.style[Zhu._prefixStyle("transform")] = "translate3d(0," + SCROLLTOP + "px,0)"
	}, f.preventDefault()
}).bind("touchend", function(e) {
	e.preventDefault(), $(".gamebox")[0].ontouchmove = null
}), document.addEventListener("touchmove", function(e) {
	return e.preventDefault(), !1
}, !1);