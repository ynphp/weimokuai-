﻿"undefined" == typeof Cmn && (Cmn = {}),
"undefined" == typeof Cmn.Func && (Cmn.Func = {}),
function() {
    var a,
    b,
    c,
    d,
    e;
    Cmn.Func.BaiduMapKey = "9KAiuPrKKoy1soPCrBrmskPg",
    Cmn.Func.GetAddrByLbs = function(a, b) {
        function c(c) {
            var d = b; (void 0 == d || "" == d) && (d = Cmn.Func.BaiduMapKey),
            $.ajax({
                type: "Post",
                url: "http://api.map.baidu.com/geocoder/v2/?ak=" + d + "&location=" + c.coords.latitude + "," + c.coords.longitude + "&output=json&pois=0",
                contentType: "application/x-www-form-urlencoded",
                dataType: "jsonp",
                jsonp: "callback",
                success: function(b) {
                    return 0 == b.status ? a(b.result.addressComponent) : a(null),
                    !0
                },
                error: function() {
                    return a(null),
                    !1
                }
            })
        }
        navigator.geolocation ? navigator.geolocation.getCurrentPosition(c, 
        function() {
            a(null)
        }) : a(null)
    },
    Cmn.Func.GetCurPosition = function(a) {
        navigator.geolocation ? navigator.geolocation.getCurrentPosition(a, 
        function() {
            a(null)
        }) : a(null)
    },
    Cmn.Func.IsHorizontalScreen = function() {
        return 0 == window.orientation || 180 == window.orientation ? !1: 90 == window.orientation || -90 == window.orientation ? !0: void 0
    },
    Cmn.Func.GetWindowsWidth = function() {
        return $("body").width()
    },
    Cmn.Func.GetWindowsHeight = function() {
        return $("body").width() * $(window).height() / $(window).width()
    },
    a = null,
    b = null,
    c = !1,
    d = !1,
    Cmn.Func.MobileAdaptiveMode = {
        Width: "Width",
        WidthHeight: "WidthHeight",
        WidthCutOutHeight: "WidthCutOutHeight"
    },
    e = function(a) {
        var b,
        c,
        d;
        if (void 0 != a && "" != a && Cmn.Func.IsString(a)) {
            if (Cmn.Func.IsHorizontalScreen()) return Cmn.DebugLog("是横屏" + Cmn.Func.GetWindowsHeight() + " window.height:" + $(window).height() + "window.width:" + $(window).width()),
            b = window.screen.width / window.screen.height,
            b > 1 && (b = 1 / b),
            c = $(window).width(),
            Cmn.Func.IsIOS() && (Cmn.Func.IsIphone4() ? c = 1120: Cmn.Func.IsIPad() || (c = 1136), $("body").width(c), $("[name='viewport']").attr("content", "width=" + c + ",user-scalable=no;")),
            $("body").width(c),
            d = c * b,
            $("body .AdviseVerticalImg").length <= 0 ? $("body").append("<div class='AdviseVerticalImg' style='position:fixed; left:0px;top:0px;z-index:10001;background:rgba(00, 00, 00, 1) none repeat scroll 0 0 !important;filter:Alpha(opacity=100);background:#000000;width:" + c + "px;height:" + (d + 1) + "px;text-align:center;'> " + "<img height='" + .8 * d + "px' src='" + a + "' /></div>") : ($(".AdviseVerticalImg").width(c), $(".AdviseVerticalImg img").height(.8 * d), $(".AdviseVerticalImg").fadeIn(100)),
            $(".AdviseVerticalImg").off("touchstart").on("touchstart", 
            function(a) {
                a.preventDefault(),
                Cmn.DebugLog("触摸横屏提示层")
            }),
            !0;
            $(".AdviseVerticalImg").hide(),
            $(".AdviseVerticalImg").off("touchstart")
        }
        return ! 1
    },
    Cmn.Func.MobileAdaptive = function(f, g, h, i, j) {
        var k,
        l,
        m,
        n,
        o;
        void 0 == i && (i = Cmn.Func.MobileAdaptiveMode.Width),
        e(h) || (i == Cmn.Func.MobileAdaptiveMode.Width || i == Cmn.Func.MobileAdaptiveMode.WidthCutOutHeight ? ($("body").width(f), Cmn.Func.IsIOS() ? (Cmn.DebugLog("是IOS系统"), k = f, Cmn.Func.IsIphone4() ? Cmn.Func.IsHorizontalScreen() && (k = 1120) : Cmn.Func.IsIPad() || Cmn.Func.IsHorizontalScreen() && (k = 1136), $("body").width(k), $("[name='viewport']").attr("content", "width=" + k + ",user-scalable=no;")) : null != navigator.userAgent.match(/Nexus/i) ? (Cmn.DebugLog("操作系统Nexus"), $("body").css("zoom", 100 * ($(window).width() / f) + "%")) : null != navigator.userAgent.match(/android\s*[\d\.]+/i) ? (Cmn.DebugLog("是安卓系统"), l = Cmn.Func.GetAndroidVersion(), Cmn.DebugLog("安卓版本小于4.4:.." + l), Cmn.DebugLog("安卓版本 4.2.2,window.screen.width:" + window.screen.width + " window.devicePixelRatio:" + window.devicePixelRatio), m = 160 * f / window.screen.width * window.devicePixelRatio, 400 >= m ? $("[name='viewport']").attr("content", "width=" + f + ", user-scalable=no, target-densitydpi=" + m.toFixed(0) + ";") : 4.2 == l ? $("[name='viewport']").attr("content", "width=" + f + ", user-scalable=no, target-densitydpi=400;") : (a = $(window).width(), b = $(window).height(), Cmn.DebugLog("window.Width:" + a + "window.Height:" + b), n = a / f, $("[name='viewport']").attr("content", "width=device-width,initial-scale=" + n + ",maximum-scale=" + n + ",minimum-scale=" + n + ",user-scalable=no;"))) : null != navigator.userAgent.match(/Windows Phone/i) ? Cmn.DebugLog("Windows Phone") : (Cmn.DebugLog("是其他操作系统"), a = $(window).width(), b = $(window).height(), Cmn.DebugLog("window.Width:" + a + "window.Height:" + b), n = a / f, $("[name='viewport']").attr("content", "width=device-width,initial-scale=" + n + ",maximum-scale=" + n + ",minimum-scale=" + n + ",user-scalable=no;")), i == Cmn.Func.MobileAdaptiveMode.WidthCutOutHeight && (Cmn.DebugLog("是WidthCutOutHeight  GetWindowsHeight:" + Cmn.Func.GetWindowsHeight() + " mainContentHeight：" + g + " bodyHeight:" + $("body").height()), Cmn.Func.GetWindowsHeight() > g ? (Cmn.DebugLog("满足条件，需要隐藏滚动条"), $("body").height() > Cmn.Func.GetWindowsHeight() && ($("body").height(Cmn.Func.GetWindowsHeight()), $("body").css("overflow-y", "hidden"))) : $("body").css("overflow-y", "scroll"))) : i == Cmn.Func.MobileAdaptiveMode.WidthHeight && (Cmn.Func.IsIOS() ? (k = f, Cmn.Func.IsIphone4() ? Cmn.Func.IsHorizontalScreen() ? k = 1120: f * $(window).height() / $(window).width() < g && (k = g / ($(window).height() / $(window).width())) : Cmn.Func.IsIPad() ? f * $(window).height() / $(window).width() < g && (k = g / ($(window).height() / $(window).width())) : k = Cmn.Func.IsHorizontalScreen() ? 1136: f, $("body").width(k), Cmn.Func.IsWeiXin() || Cmn.Func.IsIPad() ? $("[name='viewport']").attr("content", "width=" + k + ",user-scalable=no;") : Cmn.Func.IsIphone4() ? Cmn.Func.IsHorizontalScreen() ? ($("body").width(826), $("[name='viewport']").attr("content", "width=826,user-scalable=no;")) : $("[name='viewport']").attr("content", "width=" + k + ",user-scalable=no;") : $("[name='viewport']").attr("content", "width=" + k + ",initial-scale=0.5,maximum-scale=0.5,minimum-scale=0.5,user-scalable=no;"), Cmn.DebugLog("windowWidth11:" + $(window).width() + " windowHeight:" + $(window).height() + " mainContentWidth:" + f)) : (a = $(window).width(), b = $(window).height(), d ? $("body").width(a) : (n = a / f, n > b / g && (n = b / g), $("body").width(a / n), Cmn.DebugLog("window.Width:" + a + "window.Height:" + b + " _multiple：" + n + "  body.width:" + $("body").width() + "  body.Height:" + $("body").height()), m = 160 * a / n / window.screen.width * window.devicePixelRatio, 400 >= m ? ($("[name='viewport']").attr("content", "width=" + a / n + ", user-scalable=no, target-densitydpi=" + m.toFixed(0) + ";"), d = !0) : (l = Cmn.Func.GetAndroidVersion(), 4.2 == l ? ($("[name='viewport']").attr("content", "width=" + a / n + ", user-scalable=no, target-densitydpi=400;"), d = !0) : $("[name='viewport']").attr("content", "width=device-width,initial-scale=" + n + ",maximum-scale=" + n + ",minimum-scale=" + n + ",user-scalable=no;")))))),
        o = function() {
            function c() {
                Cmn.Func.IsHorizontalScreen() && $(window).width() >= $(window).height() || 0 == Cmn.Func.IsHorizontalScreen() && $(window).width() <= $(window).height() ? (void 0 != j && j(), i == Cmn.Func.MobileAdaptiveMode.WidthHeight ? Cmn.Func.IsHorizontalScreen() ? Cmn.Func.MobileAdaptive(g, f, h, i) : Cmn.Func.MobileAdaptive(f, g, h, i) : Cmn.Func.MobileAdaptive(f, g, h, i)) : setTimeout(c, 10)
            }
            $(".AdviseVerticalImg").hide();
            var b = $(window).width();
            Cmn.Func.IsIOS() || $("[name='viewport']").attr("content", "width=device-width,user-scalable=no;"),
            Cmn.DebugLog("旋转" + window.orientation + "  _widthBeforChange:" + b + "  window.width:" + $(window).width()),
            c()
        },
        0 == c && ($(window).on("orientationchange", o), c = !0, void 0 != j && j(), $("[name='viewport']").length < 1 && alert("页面上必须要加上默认的viewport。(<meta content='width=device-width,user-scalable=no;' name='viewport' />)"), Cmn.DebugLog(navigator.userAgent + "  自适应方案：" + i), i == Cmn.Func.MobileAdaptiveMode.WidthHeight && Cmn.Func.IsHorizontalScreen() && (Cmn.Func.MobileAdaptive(g, f, h, i), void 0 != j && j())),
        Cmn.DebugLog("自适应后viewport:" + $("[name='viewport']").attr("content"))
    },
    Cmn.Func.GetAndroidVersion = function() {
        var a = navigator.userAgent.match(/android\s*[\d\.]+/i)[0].replace(/android\s*/i, "");
        return a.indexOf(".") > 0 && (a = a.match(/[\d]+\.[\d]+/i)),
        a
    },
    Cmn.Func.SaveImgToLocal = function(a) {
        var b,
        c;
        if (a = Cmn.Func.GetAbsoluteUrl(a), Cmn.Func.IsWeiXin()) b = new Array,
        b[0] = a,
        WeixinJSBridge.invoke("imagePreview", {
            current: $(this).attr("src"),
            urls: b
        });
        else {
            for (c = window.open(a, "", "width=1, height=1, top=5000, left=5000");
            "complete" != c.document.readyState && "complete" != c.document.readyState;);
            c.document.execCommand("SaveAs"),
            c.close()
        }
    },
    Cmn.Func.Shake = function(a, b) {
        function l(a) {
            var l,
            m,
            n,
            o;
            0 != IsShake && (l = a.accelerationIncludingGravity, m = (new Date).getTime(), m - e > 100 && (n = m - e, e = m, f = l.x, g = l.y, h = l.z, o = 1e4 * (Math.abs(f + g + h - i - j - k) / n), o > d && b && IsShake && (IsShake = !1, c.shakeCallbackFn()), i = f, j = g, k = h))
        }
        var d,
        e,
        f,
        g,
        h,
        i,
        j,
        k,
        c = this;
        if (c.shakeCallbackFn = function() {},
        1 == arguments.length) {
            if ("function" == typeof arguments[0] && (c.shakeCallbackFn = arguments[0]), "boolean" == typeof arguments[0]) return window.IsShake = !0,
            "undefined"
        } else c.shakeCallbackFn = b;
        d = a || 800,
        e = 0,
        window.IsShake = !0,
        window.IsBindShake = $("body").attr("shake"),
        window.IsBindShake || (window.addEventListener("devicemotion", l, !1), $("body").attr("shake", "true"))
    },
    Cmn.Func.ListenDeviceOrientation = function(a) {
        var c,
        b = this;
        b.deviceorientation || (b.deviceorientation = function() {}),
        "function" == typeof a && (b.deviceorientation = a),
        b.beforAlpha = "",
        b.beforBeta = "",
        b.beforGamma = "",
        b.zRotationOrientation = "",
        c = "Vertical",
        orientation || (orientation = "Vertical"),
        b.isBindListenDeviceOrientation || (window.addEventListener("deviceorientation", 
        function(a) {
            var g,
            d = Math.ceil(a.beta),
            e = Math.ceil(a.alpha),
            f = Math.ceil(a.gamma);
            "" == b.beforAlpha && (b.beforAlpha = e),
            "" == b.beforBeta && (b.beforBeta = d),
            "" == b.beforGamma && (b.beforGamma = f),
            c = Math.abs(d) < 60 ? "Horizontal": "Vertical",
            e && b.beforAlpha != e && (g = b.beforAlpha - e, Math.abs(g) > 4 && (g = -1 * (g / Math.abs(g))), b.beforAlpha = e, b.deviceorientation(a, g, c))
        },
        !0), b.isBindListenDeviceOrientation = !0)
    },
    Cmn.Func.TouchSlide = function(a, b, c, d, e, f) {
        var h,
        i,
        j,
        k,
        g = $(a);
        return g.length < 1 ? !1: (h = null, i = null, j = "", f || (f = "horizontal"), k = {
            touchstart: "createTouch" in document ? "touchstart": "mousedown",
            touchmove: "createTouch" in document ? "touchmove": "mousemove",
            touchend: "createTouch" in document ? "touchend": "mouseup"
        },
        g.off(k.touchstart).on(k.touchstart, 
        function(a) {
            a = event.touches ? event.touches[0] : a,
            null == h && null == i && (h = a.pageX),
            i = a.pageY
        }), g.off(k.touchmove).on(k.touchmove, 
        function(a) {
            var g,
            k,
            d = event.touches ? event.touches[0] : a;
            return null == h && null == i ? (a.preventDefault(), void 0) : (g = Math.abs(d.pageX - h), k = Math.abs(d.pageY - i), g > k ? (d.pageX - h > 0 ? Math.abs(d.pageX - h) > b && (j = "right", c && c(j, g), e && "1" != e ? (h = d.pageX, i = d.pageY) : (h = null, i = null)) : d.pageX - h < 0 && Math.abs(d.pageX - h) > b && (j = "left", c && c(j, g), e && "1" != e ? (h = d.pageX, i = d.pageY) : (h = null, i = null)), "vertical" != f && a.preventDefault()) : k > g && (d.pageY - i > 0 ? Math.abs(d.pageY - i) > b && (j = "down", c && c(j, k), e && "1" != e ? (h = d.pageX, i = d.pageY) : (h = null, i = null)) : d.pageY - i < 0 && Math.abs(d.pageY - i) > b && (j = "up", c && c(j, k), e && "1" != e ? (h = d.pageX, i = d.pageY) : (h = null, i = null)), Cmn.DebugLog(k + "==" + g), "horizontal" != f && a.preventDefault()), void 0)
        }), g.off(k.touchend).on(k.touchend, 
        function() {
            h = null,
            i = null,
            j && d && d(j),
            j = ""
        }), void 0)
    },
    Cmn.Func.ImageLazyLoading = function(a, b, c, d, e) {
        function k(a) {
            var c,
            h,
            i,
            b = a + e;
            for (b > g.length && (b = g.length), c = a; b > c; c++) h = g[c],
            void 0 !== $(h).attr(d) ? (i = $(h).attr(d), i && l(i, h, c)) : f++
        }
        function l(a, b, c) {
            var d = new Image;
            d.onload = function() {
                m(b, a, !1, c)
            },
            d.onerror = function() {
                m(b, a, !0, c)
            },
            d.src = a,
            d.complete && m(b, a, !1, c)
        }
        function m(a, g, l) {
            if ($(a).attr(d)) {
                $(a).removeAttr(d),
                f++;
                var n = Math.ceil(100 * (f / h));
                n = f == h ? 100: n >= 100 ? 100: n,
                l ? i.push(g) : $(a).attr("src", g),
                b && b(n, g),
                f >= h ? c && c(i) : (j++, 0 == j % e && k(j))
            }
        }
        var f,
        g,
        h,
        i,
        j;
        return a || (a = "body"),
        d || (d = "lazypath"),
        e || (e = 3),
        f = 0,
        g = $(a).find("img[" + d + "]") || $(a).find("img"),
        h = g.length,
        0 == h ? (b && b(100, ""), c && c(i), !1) : (i = [], j = 0, k(0), void 0)
    },
    Cmn.Func.FrameAnimation = function() {
        var a = function(a, b, c, d) {
            this.frames = $(a).eq(0).css({
                visibility: "visible"
            }).siblings().css({
                visibility: "hidden"
            }),
            this.index = 0,
            this.interval = 0,
            this.Play(b, c, d)
        };
        return a.prototype = {
            Run: function(a, b, c) {
                var d = this;
                d.frames.eq(d.index).css({
                    visibility: "visible"
                }).siblings().css({
                    visibility: "hidden"
                }),
                d.interval = setInterval(function() {
                    d.index++,
                    d.frames.eq(d.index).css({
                        visibility: "visible"
                    }).siblings().css({
                        visibility: "hidden"
                    }),
                    d.frames.length - 1 <= d.index && (b--, 0 >= b ? (d.Stop(), d.index = 0, c && c()) : d.index = 0)
                },
                a)
            },
            Play: function(a, b, c) {
                this.index = 0,
                this.Run(a, b, c)
            },
            Stop: function() {
                window.clearInterval(this.interval)
            }
        },
        function(b, c, d, e) {
            return new a(b, c, d, e)
        }
    } (),
    Cmn.Func.AnimteQueue = function() {
        var a = function() {
            this.index = 0,
            this.queue = [],
            this.position = [],
            this.isStopQueue = !0
        };
        return a.prototype = {
            InitPostion: function(a) {
                this.index = a;
                for (var b = a; b < this.position.length; b++)"function" == typeof this.position[b] && this.position[b].apply(this)
            },
            Add: function(a) {
                return this.queue.push(a),
                this
            },
            Run: function(a) {
                var c,
                b = this;
                b.index = a,
                c = function() {
                    b.index++,
                    b.index < b.queue.length && b.isStopQueue && b.Run(b.index)
                },
                this.position.push(this.queue[b.index].apply(this, [c]))
            },
            Start: function() {
                this.isStopQueue = !0,
                this.Run(this.index)
            },
            Stop: function() {
                this.isStopQueue = !1
            }
        },
        function() {
            return new a
        }
    } (),
    Cmn.Func.Html5FileUpload = function() {
        var a = function() {};
        return function() {
            return new a
        }
    } ()
} ();