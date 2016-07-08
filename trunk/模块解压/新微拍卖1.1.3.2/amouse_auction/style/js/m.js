(function ($) {

    $.fn.mTab = function (config) {
        var defaults = {
            activeIndex: 0 //默认第几个tab显示
        };
        var tab = {index:0};

        var p = $.extend(defaults, config);

        var g = $(this);
        tab.target = g;
        var oldIndex = p.activeIndex;
        var menus = g.children(".tab_header").children("li");

        var body = g.children(".tab_container").children("li");
        for (var i = 0; i < menus.length; i++) {
            $(menus[i]).removeClass("selected");
            $(body[i]).hide();
            (function (i) {
                $(menus[i]).tap(
                    function () {
                        $(menus[oldIndex]).removeClass("selected");
                        $(menus[i]).addClass("selected");
                        $(body[oldIndex]).hide();
                        $(body[i]).show();
                        tab.index = i;
                        oldIndex = i;
                    });
            })(i);

        }
        $(body[p.activeIndex]).show();
        $(menus[p.activeIndex]).addClass("selected");
        tab.menus = menus;
        return tab;
    }
})(Zepto);



(function ($) {
    $.mDialog = function (config) {
        var defaults = {
            title: null,
            message: null,
            buttons: [{
                text: "ok", handle: function () {
                    g.dialog.remove();
                    g.mask.remove();
                }
            }]
        };
        var p = $.extend(defaults, config);
        var g = g || {};
        g.mask = $("<div class='mui_dia_mask'></div>");
        $("body").append(g.mask);

        var dia = dia || {};
        dia.warp = $("<div class='mui_dia'></div>");
        if (p.title) {
            dia.title = $("<div class='title'>" + p.title + "</div>");
            dia.warp.append(dia.title);
        }
       
            dia.content = $("<div class='content'></div>");
            dia.content.append(p.message);
           
        
        dia.warp.append(dia.content);


        if (p.buttons) {
            dia.fooder = $("<div class='fooder'></div>");
            for (var i = 0; i < p.buttons.length; i++) {
                var btn = $("<a>" + p.buttons[i].text + "</a>");
                btn.on("touchstart", p.buttons[i].handle);
                dia.fooder.append(btn);

            }
            dia.warp.append(dia.fooder);

        }
        g.dialog = dia.warp;

        $("body").append(dia.warp);

        return {
            close: function () {
                g.dialog.remove();
                g.mask.remove();
            }
        }
    }
})(Zepto);

(function ($) {
    $.mCountdown = function (config) {
        var defaults = {
            now: Date.now(),
            doms: [{ el: "body", tim: "2015/1/1 22:30:00" }],
            countLabel: "距结束：",
            countCss: "",
            completeLabel: "已结束",
            completeCss: ""

        };
        var p = $.extend(defaults, config);
        var now = p.now;
        function countdown() {
             
            if (p.doms) {
                for (var i = 0; i < p.doms.length; i++) {
                   
                    var diff = Date.parse(p.doms[i].tim) - now;
                    if (diff > 0) {

                        var d = Math.floor(diff / 1000 / 60 / 60 / 24);
                        var h = Math.floor(diff / 1000 / 60 / 60 % 24);
                        var m = Math.floor(diff / 1000 / 60 % 60);
                        var s = Math.floor(diff / 1000 % 60);
                        $("#" + p.doms[i].el).html(p.countLabel + "<span class='" + p.countCss + "'>" + d + "天" + "" + h + "小时" + "" + m + "分" + "" + s + "秒</span>");
                        //console.log(p.countLabel + "<span class='" + p.countCss + "'>" + d + "</span>天" + "<span class='" + p.countCss + "'>" + h + "</span>时" + "<span class='" + p.countCss + "'>" + m + "</span>分" + "<span class='" + p.countCss + "'>" + s + "</span>秒");
                    }
                    else {
                        $("#" + p.doms[i].el).html("<span class='" + p.completeCss + "'>" + p.completeLabel + "</span>");
                    }
                    //console.log(now);
                }
            }
            now = now + 1000;
        }

        //now = now.getTime()
        var timer = setInterval(countdown, 1000);

        return {
            over: function () {
                clearInterval(timer);
            }
        }


    }
})(Zepto);

///区域三级联动
(function ($) {
    $.areaDialog = function (config) {
        var defaults = {
            url: null,
            
            buttons: [{
                text: "ok", handle: function () {
                    g.dia.remove();
                }
            }]
        };
        var p = $.extend(defaults, config);
         
        var g = g || {};
        g.dia = $("<div class='mui_dia_acc'></div>");
        g.warp = $("<div class='warp'></div>");
       
        g.warp.append("<h1 class='title'>选择省市区</h1>");
        g.scontainer = $("<div class='chooser'></div>");
        g.areaAll = $("<a href='javascript:' class='chooseItem'>全部地区</a>");
        g.lastItem=false;
        g.areaAll.on("click", function () {
            reChoose(0,1);
        });
        g.scontainer.append(g.areaAll);
       




        g.warp.append(g.scontainer);
        g.list = $("<div class='alist'></div>");
        g.list.height(document.body.clientHeight - 200);
        g.warp.append(g.list);
        g.btnbar = $("<div class='btnbar'></div>");
        for (var b in p.buttons) {
            
            var btn = $("<a href='javascript:' class='sbtn'>" + p.buttons[b].text + "</a>");
            btn.on("click", p.buttons[b].handle);
            g.btnbar.append(btn);
        }
        g.warp.append(g.btnbar);
        g.dia.append(g.warp);
        $("body").append(g.dia);
        if (p.url) {
            getItemList(0, 1);
        }
        function getItemList(pid,level) {
             
            if (level <= 3) {
                g.list.empty();
                getData(p.url, function (e) {
                    for (var i in e) {
                        var item = $("<a class='item' data-id='" + e[i].codeid + "' >" + e[i].cityName + "</a>");

                        item.on("click", function (ev) {
                             
                            var selectedElement=$(ev.srcElement);
                            var selectedItem = $("<a href='javascript:' class='chooseItem' data-id='" + selectedElement.attr("data-id") + "'>" + selectedElement.text() + "</a>");
                            selectedItem.on("click", function () {
                                reChoose(selectedElement.attr("data-id"), level+1);
                            });
                            console.log("level:"+level);
                            if (level < 3) {
                                g.scontainer.append(selectedItem);
                                getItemList(selectedElement.attr("data-id"), level + 1);
                            }
                            else {
                                if (!g.lastItem) {
                                    g.scontainer.append(selectedItem);
                                    g.lastItem = true;
                                }
                                else {
                                    g.scontainer.children().last().remove()
                                    g.scontainer.append(selectedItem);
                                }
                            }
                           
                        });
                        g.list.append(item);
                    }
                }, pid);
            }
        }

        function getData(url, handler, pid) {
             
            $.ajax({
                url: url,
                success: handler,
                data: {"pid":pid}
            });
        }

        function reChoose(pid, lv) {
            console.log(g.scontainer.children());
            for (var i = g.scontainer.children().length-1; i >=lv; i--) {
                console.log(g.scontainer.children()[i]);
                $(g.scontainer.children()[i]).remove();

            }
            getItemList(pid, lv);
            if (lv < 4) {
                g.lastItem = false;
            }
        }

        function getArea() {
            var areas = [];
            for (var i = 1; i < g.scontainer.children().length ; i++) {
                var child=$(g.scontainer.children()[i]);
                areas.push({ "name": child.text(), "code": child.attr("data-id") });
                 
            }

            return areas;
        }


        return {
            close: function () {
                g.dia.remove();
            },
            getArea: function () {
                return getArea();
            }
        }
    }
})(Zepto);