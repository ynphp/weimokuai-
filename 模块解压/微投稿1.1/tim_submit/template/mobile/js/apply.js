$(document).ready(function() {
    var b = window.location.search.split("chief_id=")[1];
    $.ajax({
        url: "/data/chief/home",
        type: "get",
        dataType: "json",
        data: {
            chief_id: b
        }
    }).done(function(d) {
        if (d.ret == 0) {
            var c = template("header", d.data);
            $(".header ul").html(c);
            var c = '<div class="say-info"><p>' + d.data.tips + "</p></div>";
            $("body").append(c);
            $("body").attr("verify_mobile", d.data.verify_mobile);
            if (d.data.verify_mobile == "1") {
                $(".get-code-btn,.input-code").show()
            }
            if (d.data.nav.length == 2) {
                $(".header").addClass("two-list")
            } else {
                $(".header li:eq(1)").addClass("active")
            }
            a()
        } else {
            zAlert.Alert({
                content: d.msg,
                callback: function() {
                    zAlert.Close()
                }
            })
        }
    });
    function a() {
        function c() {
            $(".get-code em").text(60);
            $(".get-code").addClass("sending");
            var e = setInterval(function() {
                var f = parseInt($(".get-code em").text());
                if (f > 0) {
                    f--;
                    $(".get-code em").text(f)
                } else {
                    $(".get-code").removeClass("sending");
                    $(".get-code em").text(60);
                    clearInterval(e)
                }
            },
            1000)
        }
        $(".get-code").on("click",
        function() {
            if (!$(this).hasClass("sending")) {
                var e = $(".mobile-input").val();
                var f = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/;
                if (!e || !f.test(e)) {
                    zAlert.Alert({
                        content: "请输入正确的手机号",
                        callback: function() {
                            zAlert.Close()
                        }
                    });
                    return false
                }
                c();
                $.ajax({
                    url: "/data/chief/send_sms",
                    type: "post",
                    dataType: "json",
                    data: {
                        mobile: e
                    }
                }).done(function(g) {
                    if (g.ret == 0) {} else {
                        zAlert.Alert({
                            content: g.msg,
                            callback: function() {
                                zAlert.Close()
                            }
                        })
                    }
                })
            }
        });
        function d() {
            $(".img-btns .img-btn").on("click",
            function() {
                var e = $(this).attr("type");
                if ($("input." + e).data("img-source")) {
                    $(this).find("img,i").remove();
                    $("input." + e).data("img-source", "");
                    $("input." + e).val("")
                } else {
                    $("input." + e).trigger("click")
                }
            });
            $.validateImg = function(g) {
                var f = {
                    jpg: "/9j/4",
                    gif: "R0lGOD",
                    png: "iVBORw"
                };
                var i = g.indexOf(",") + 1;
                for (var h in f) {
                    if (g.indexOf(f[h]) === i) {
                        return h
                    }
                }
                return null
            };
            $(".img-file").on("change",
            function() {
                var f = this;
                var e = $(this)[0].files[0];
                var h = $(this).attr("typ");
                if (e) {
                    if (e.size > 2097151) {
                        zAlert.Alert({
                            content: "图片超过2M,无法上传",
                            callback: function() {
                                zAlert.Close()
                            }
                        })
                    } else {
                        var g = new FileReader();
                        g.readAsDataURL(e);
                        g.onload = function(i) {
                            var j = i.target.result;
                            if ($.validateImg(j)) {
                                $(f).data("img-source", j);
                                $(".img-btns .img-btn[type=" + h + "]").append("<img /><i>-</i>");
                                $.ajax({
                                    url: "/data/image/upload_gd",
                                    type: "post",
                                    dataType: "json",
                                    data: {
                                        image: $(f).data("img-source"),
                                        size: [{
                                            width: 80,
                                            height: 80
                                        }]
                                    }
                                }).done(function(k) {
                                    if (k.ret == 0) {
                                        $(".img-btns .img-btn[type=" + h + "] img").attr("src", k.data.thumb_img)
                                    }
                                })
                            } else {
                                zAlert.Alert({
                                    content: "图片格式错误",
                                    callback: function() {
                                        zAlert.Close()
                                    }
                                })
                            }
                        }
                    }
                }
            })
        }
        d();
        $(".sub-btn").on("click",
        function() {
            if ($(this).hasClass("disable")) {
                return false
            }
            var f = $.trim($("input.name").val()),
            e = $.trim($("input.mobile-input").val()),
            k = $.trim($("textarea.content").val()),
            g = $.trim($("input.verfy_code").val());
            if (!f) {
                zAlert.Alert({
                    content: "请输入姓名",
                    callback: function() {
                        zAlert.Close()
                    }
                });
                return false
            }
            var i = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9]|17[0-9])\d{8}$/;
            if (!e || !i.test(e)) {
                zAlert.Alert({
                    content: "请输入正确的手机号",
                    callback: function() {
                        zAlert.Close()
                    }
                });
                return false
            }
            if ($("body").attr("verify_mobile") == "1") {
                if (!g) {
                    zAlert.Alert({
                        content: "请输入验证码",
                        callback: function() {
                            zAlert.Close()
                        }
                    });
                    return false
                }
            }
            if (!k) {
                zAlert.Alert({
                    content: "请输入内容",
                    callback: function() {
                        zAlert.Close()
                    }
                });
                return false
            }
            var j = 0;
            $(".sub-btn").text("提交中，请耐心等待...").addClass("disable");
            function h(m, n) {
                var l = $(".img-file").eq(m);
                m++;
                if (m > 4) {
                    n();
                    return false
                } else {
                    if ($(l).data("img-source")) {
                        $.ajax({
                            url: "/data/image/upload_gd",
                            type: "post",
                            dataType: "json",
                            data: {
                                image: $(l).data("img-source"),
                                size: [{
                                    width: 80,
                                    height: 80
                                }]
                            }
                        }).done(function(o) {
                            if (o.ret == 0) {
                                $(l).data("img-source", o.data.fileputh);
                                if (m <= 4) {
                                    h(m, n)
                                }
                            } else {
                                $(".sub-btn").removeClass("disable").text("提交");
                                zAlert.Alert({
                                    content: o.msg,
                                    callback: function() {
                                        zAlert.Close()
                                    }
                                });
                                return false
                            }
                        })
                    } else {
                        h(m, n)
                    }
                }
            }
            h(j,
            function() {
                var l = [];
                $(".img-file").each(function() {
                    if ($(this).data("img-source")) {
                        l.push($(this).data("img-source"))
                    }
                });
                $.ajax({
                    url: "/data/chief/apply",
                    type: "post",
                    dataType: "json",
                    data: {
                        name: f,
                        mobile: e,
                        content: k,
                        verfy_code: g,
                        chief_id: b,
                        img: l
                    }
                }).done(function(m) {
                    if (m.ret == 0) {
                        $(".apply-form,.say-info").hide();
                        $(".success input").val(m.data.code);
                        $(".success").show()
                    } else {
                        $(".sub-btn").removeClass("disable").text("提交");
                        zAlert.Alert({
                            content: m.msg,
                            callback: function() {
                                zAlert.Close()
                            }
                        })
                    }
                })
            })
        })
    }
});