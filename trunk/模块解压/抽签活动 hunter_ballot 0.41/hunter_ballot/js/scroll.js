!function(){var t=['<div class="loading-cover">','<img class="loading-logo" src="http://z.sina.com.cn/static/css/images/loading.png">','<div class="loading-line"><span></span></div>','<div class="loading-number">0%</div>',"</div>"].join("");$("body").append($(t));var i=0,o=8,e=setInterval(function(){i+=o++,i>=101&&(i=100,clearInterval(e)),$(".loading-number").text(i+"%")},100)}();var ywy=window.ywy||{};ywy.scroll={width:0,height:0,scrollTop:0,rate:1,point:0,maxstage:1,lastleft:0,lasttop:0,touch:{},imgloaded:[],scrolling:!1,resize:function(){this.width=$(window).width(),this.height=$(window).height(),this.xrate=this.width/320,this.yrate=this.height/520,this.rate=this.width/this.height,this.doc=$(".pages-container"),this.maxstage=this.doc.children().length;this.height<400?this.doc.addClass("small"):this.doc.removeClass("small"),this.doc.css({}).children().css({width:this.width,height:this.height,position:"relative",overflow:"hidden"}),$("body").data("loaded","true")},repos:function(){var t=this;$(".control-content").each(function(i,o){var e=/resize-center/.test($(o).parents(".control").attr("class")),n=/resize-middle/.test($(o).parents(".control").attr("class")),s=/resize-right/.test($(o).parents(".control").attr("class")),a=/resize-bottom/.test($(o).parents(".control").attr("class")),c=($(o).parents(".control").data("left")||0)*(t.xrate-1)+"px",r=-(t.width-$(o).parents(".control").offset().left)*(t.xrate-1)+"px";try{var l=$(o).parents(".page").offset().top}catch(h){var l=0}var d=($(o).parents(".control").data("top")||0)*(t.yrate-1)+"px",u=-(t.height-$(o).parents(".control").offset().top-l)*(t.yrate-1)+"px";if(e)var p="0";else if(s)var p=r;else var p=c;if(n)var g=0;else if(a)var g=u;else var g=d;$(o).parents(".control").css({"-webkit-transform-origin":(e?"50%":s?"100%":"0")+(n?" 50%":a?" 100%":" 0"),"-webkit-transform":"translate("+p+","+g+") scaleX("+t.xrate+") scaleY("+t.yrate+")"})})},resizeBg:function(){var t=this;this.height/this.width>1.775?(this.doc.find(".bg>img,.front>img").each(function(i,o){$(this).css({height:t.height,width:"auto"})}),this.doc.find(".bg,.front").each(function(i,o){$(this).css({top:"","margin-top":"",left:"50%","margin-left":-(t.height/1136*640)/2})}),this.doc.find(".gravity>.front>img").each(function(i,o){$(this).css({height:t.height+10})}),this.doc.find(".gravity>.front").each(function(i,o){$(this).css({"margin-left":-((t.height+10)/1136*640)/2})})):(this.doc.find(".bg>img,.front>img").each(function(i,o){$(this).css({width:t.width,height:"auto"})}),this.doc.find(".bg,.front").each(function(i,o){$(this).css({left:"","margin-left":"",top:"50%","margin-top":-(t.width/640*1136)/2})}),this.doc.find(".gravity>.front>img").each(function(i,o){$(this).css({width:t.width+10})}),this.doc.find(".gravity>.front").each(function(i,o){$(this).css({"margin-top":-((t.width+10)/640*1136)/2})}))},imglazyload:function(t){if(!this.imgloaded[t]&&t<this.maxstage){var i=this.doc.find(".bg").eq(t),o=this.doc.find(".front").eq(t);i.data("img")&&i.append("<img src='"+i.data("img")+"'>"),o.data("img")&&o.append("<img src='"+o.data("img")+"'>"),this.resizeBg(),this.imgloaded[t]=!0}},onscroll:function(){var t=this;document.addEventListener("touchmove",function(t){t.preventDefault()},!1),$("body").on("touchstart",function(i){i=i.touches[0],t.touch.start=i.pageY,t.autoplay(!0),t.startTime=+new Date}),$("body").on("touchmove",function(i){return i.preventDefault(),t.touch.start?(i=i.touches[0],t.touch.end=i.pageY,t.touch.move=t.touch.end-t.touch.start,void(0===$(i.target).parents(".stackimg").length&&t.move(t.scrollTop+t.touch.move))):!1}),$("body").on("touchend",function(i){t.endTime=+new Date,t.touchDuration=t.endTime-t.startTime,t.touch.move=t.touch.move||0,t.scrollTop+=t.touch.move;var o=t.touch.move/t.touchDuration*300;t.move(t.scrollTop+o,"ani"),t.scrollTop+=o,t.showStage(Math.round(-t.scrollTop/t.height+.4),"scroll"),t.scrollTop>0?(t.scrollTop=0,t.move(0,"ani")):t.scrollTop<-(t.maxstage-1)*t.height&&(t.scrollTop=-(t.maxstage-1)*t.height,t.move(t.scrollTop,"ani")),t.touch={}})},ontouch:function(){var t=this;document.addEventListener("touchmove",function(t){t.preventDefault()},!1),$("body").on("touchstart",function(i){i=i.touches[0],t.touch.start=i.pageY,t.autoplay(!0)}),$("body").on("touchmove",function(i){return t.touch.start?(i=i.touches[0],t.touch.end=i.pageY,t.touch.move=t.touch.end-t.touch.start,void(0===$(i.target).parents(".stackimg").length&&t.move(-t.point*t.height+t.touch.move))):!1}),$("body").on("touchend",function(i){var o=t.point;o=t.touch.move>80?t.point>0?t.point-1:t.point:t.touch.move<-80&&t.point<t.maxstage-1?t.point+1:t.point,t.showStage(o),t.touch={}}),$(window).on("blur",function(){t.autopause()})},offtouch:function(){$("body").off("touchstart"),$("body").off("touchmove"),$("body").off("touchend")},move:function(t,i){var o=this;i?(this.doc.css({"-webkit-transform":"translate(0, "+t+"px)","-webkit-transition-duration":"0.5s"}),setTimeout(function(){o.doc.css({"-webkit-transition-duration":"0s"})},500)):this.doc.css({"-webkit-transform":"translate(0, "+t+"px);","-webkit-transition-duration":"0s"})},showStage:function(t,i){var o=this;if($(".arrow-up").hide(),t>=0&&t<this.maxstage){if(0!=t&&o.point==t);else{var e=ywy.doc.getSiteId();e&&ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+e+"?type=0&pageId="+ywy.doc.getPageId())}o.point=t,i||this.move(-this.height*t,!0);var n=o.doc.children().eq(t).data("notouch");n&&ywy.scroll.offtouch(),setTimeout(function(){$("#pager").children().removeClass("selected"),$("#pager").children().eq(t).addClass("selected"),o.doc.children().removeClass("active prev next"),o.doc.children().eq(t-1).addClass("prev"),o.doc.children().eq(t).addClass("active"),o.doc.children().eq(t+1).addClass("next"),ywy.doc.pageaudio(),ywy.doc.pagevideo(),t<o.maxstage-1&&$(".active").data("arrow")!==!1&&$(".arrow-up").show()},200)}},pause:function(){this.stagepause?(this.doc.children().eq(this.point).addClass("active"),this.stagepause=!1):(this.doc.children().eq(this.point).removeClass("active"),this.stagepause=!0)},gravity:function(){function t(t){var o=t.accelerationIncludingGravity,e=-1,n=Math.round(o.x/9.81*-90*e/2),s=Math.round((o.y+9.81)/9.81*-90*e/2);(Math.abs(n-i.lastleft)>3||Math.abs(s-i.lasttop)>3)&&(i.lastleft=n,i.lasttop=s,i.doc.find(".gravity .front").css({position:"absolute","-webkit-transform":"translate("+n+"px, "+s+"px)"}))}var i=this;window.DeviceMotionEvent?window.addEventListener("devicemotion",t,!1):alert("亲，建议你换一个支持重力感应的手机体验本页的炫酷效果哦~")},initPager:function(){var t=this,i=[];i.push('<div class="pager" id="pager">');for(var o=0;o<this.maxstage;o++)i.push('<a href="javascript:void(0);"></a>');i.push("</div>"),$("body").append($(i.join(""))),$("#pager").delegate("a","click",function(i){t.showStage($(i.target).index())})},initaudio:function(){var t=this;$("body").on("touchstart click","#audioplay",function(i){i.stopPropagation(),i.preventDefault(),t.audioplaying?($(this).removeClass("audioplaying"),t.audioplaying=!1,t.audio.pause()):($(this).addClass("audioplaying"),t.audioplaying=!0,t.audio.play())})},autoplay:function(t){var i=location.href.match(/disableMusic=(\w+)/),o=!1;if(i&&"true"===i[1]&&(o=!0),!o&&!this.bgmnode&&$("#bgm").length){var e=$("#bgm").data("src");e&&(this.audio=document.createElement("audio"),this.audioplay=$('<div id="audioplay"></div>'),this.audio.src=e,this.audio.loop="loop",$("#bgm").append(this.audio),this.audioplay.append($('<img class="musicimg" src="http://z.sina.com.cn/static/css/images/yy.png"/>')),$("body").append(this.audioplay),this.bgmnode=!0)}if(this.bgmnode&&!this.bgmplay&&!t){var n=navigator.userAgent.toLowerCase();(!/iphone|ipad|ipod|mac/.test(n)||/micromessenger/i.test(n))&&(this.audio.play(),this.audioplaying=!0,$("#audioplay").addClass("audioplaying"),this.bgmplay=!0)}this.bgmnode&&!this.bgmplay&&t&&(this.audio.play(),this.audioplaying=!0,$("#audioplay").addClass("audioplaying"),this.bgmplay=!0)},autopause:function(){this.bgm&&($("#bgm")[0].pause(),this.audioplaying=!1,$("#audioplay").removeClass("audioplaying"),this.bgm=!1)},typetext:function(){this.doc.find(".text").each(function(t,i){$(i).find("p").each(function(t,i){for(var o=[],e=$(i).text(),n=0;n<e.length;n++)o.push("<span>"+e.charAt(n)+"</span>");$(i).html(o.join(""))})})},init:function(){var t=this;this.resize(),this.repos(),$(window).on("resize",function(){t.resize(),t.repos()});var i=window.scrolltype=$("body").data("scrolltype");"flat"===i?this.onscroll():this.ontouch(),this.initaudio(),this.autoplay(),this.showStage(0)}},ywy.doc={getUA:function(){return navigator.userAgent},isWeixin:function(){return this.getUA().match(/MicroMessenger/i)},isIOS:function(){return this.getUA().match(/(iPhone|iPod|iPad);?/i)},isAndroid:function(){return this.getUA().match(/android/i)},getSiteId:function(){return $("body").data("siteid")},getPageId:function(t){return t&&$(t).parents(".fixed").length?"f":t?$(t).parents(".page").index()+1:ywy.scroll.point+1},getCtrlId:function(t){return $(t).data("uid")},log:function(t){var i=new Image,o="_ywy_log_"+ +new Date;window[o]=i,i.onload=i.onerror=i.onabort=function(){i.onload=i.onerror=i.onabort=null,window[o]=null,i=null},i.src=t+(t.indexOf("?")>0?"&":"?")+o},getData:function(t){for(var i=ywy.scroll.doc.children().eq(ywy.scroll.point),o=i.find(".control[data-type=input]"),e={},n=0,s=o.length;s>n;n++)e[o.eq(n).find(".control-content").data("name")]=o.eq(n).find(".control-content").val();return e},submit:function(t){var i=this.getData("json");$.ajax({type:"post",contentType:"application/json",dataType:"json",url:"http://z.sina.com.cn/s/collect/insert/"+this.getSiteId()+"?type=2&pageId="+this.getPageId()+"&controlId="+this.getCtrlId(t),data:JSON.stringify(i),success:function(t){alert(200===t.status?"提交成功!":t.message)}})},tel:function(t){location.href="tel://"+t},link:function(t){this.isWeixin()&&(t="http://z.sina.com.cn/r?u="+encodeURIComponent(t)),window.open(t)},button:function(){var t=this;$(".button-control").each(function(i,o){$(o).find(".control-content").on("click",function(i){var e=$(this).data("action"),n=$(this).data("actionvalue");switch(e){case"submit":t.submit($(o).parents(".control"));break;case"tel":t.tel(n);break;case"link":t.link(n);break;case"topage":ywy.scroll.showStage(n-1);break;case"tonext":ywy.scroll.showStage(ywy.scroll.point+1)}var s=t.getSiteId(),a=t.getPageId($(this).parents(".control")),c=t.getCtrlId($(this).parents(".control"));void 0!==s&&void 0!==a&&void 0!==c&&ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+s+"?type=1&pageId="+a+"&controlId="+c)})})},input:function(){$(".input-control").each(function(t,i){$(i).find(".control-content").on("blur",function(t){$("body").scrollTop(0)})})},stackimg:{touch:{},init:function(){var t=this;$(".stackimg-control").each(function(i,o){$(o).on("touchstart",function(i){i.preventDefault(),i=i.touches[0],t.touch.startX=i.pageX;var e=ywy.doc.getSiteId(),n=ywy.doc.getPageId(),s=ywy.doc.getCtrlId($(o).parents(".control"));void 0!==e&&void 0!==n&&void 0!==s&&ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+e+"?type=1&pageId="+n+"&controlId="+s)}),$(o).on("touchmove",function(i){return i.preventDefault(),t.touch.startX?(i=i.touches[0],t.touch.endX=i.pageX,t.touch.moveX=t.touch.endX-t.touch.startX,void t.move($(this),t.touch.moveX)):!1}),$(o).on("touchend",function(i){i.preventDefault(),t.touch.moveX&&t.show($(this),t.touch.moveX),t.touch={}})})},move:function(t,i){t.find("img").eq(0).css({"-webkit-transform-origin":"50% 20% 0","-webkit-transform":"translate("+i+"px, 0)"})},show:function(t,i){t.find("img").eq(0).css({"-webkit-transform-origin":"50% 20% 0","-webkit-transform":"translate("+i/Math.abs(i)*400+"px, 0)"}),setTimeout(function(){t.find("img").eq(0).css({"-webkit-transform":"translate(0, 0)"}),t.find(".stackimg").append(t.find(".stackimgholder").eq(0))},100)}},pageaudio:function(){var t=$(".active").find(".audio-control").find(".control-content[data-autoplay=true]");t.data("source")&&!this.audionode&&(this.audionode=document.createElement("audio"),this.audionode.src=t.data("source"),this.audionode.loop=""===t.attr("loop"),$("body").append(this.audionode)),t.data("source")?this.audionode.play():this.audionode&&(this.audionode.pause(),this.audionode.src="")},pagevideo:function(){this.videonode&&this.videonode.length&&(this.videonode[0].pause(),this.videonode.off("ended")),this.videonode=$(".active").find(".video-control").find(".control-content[data-autoplay=true]"),this.videonode.find("source").attr("src")&&(this.videonode[0].play(),this.videonode.on("ended",function(){ywy.scroll.showStage(ywy.scroll.point+1)}))},wb:function(){$(".wb-control").on("click",function(){var t=document.location.href,i=$('<input type="text" id="weibo-url" value="" />');i.attr("value",t),$(this).append(i),$("#weibo-url")[0].focus(),$("#weibo-url")[0].select(),document.execCommand("Copy"),$("#weibo-url").remove();var o=$(this)[0].getBoundingClientRect(),e=$('<div class="arrow_box top">已完成站点地址复制</div>');e.css({left:o.left-36+"px",top:o.bottom+20+"px"}),$("body").append(e),e.animate({opacity:1},300,function(){var t=this;setTimeout(function(){e.animate({opacity:0},300,function(){$(t).remove()})},2e3)})})},wx:function(){$(".wx-control").on("click",function(){$(".guoqing").css("display","none"),$(".guoqinged").css("display","block"),$("#share_bg").show()}),$("#share_bg").on("click",function(){$("#share_bg").hide(),$(".guoqing").css("display","block"),$(".guoqinged").css("display","none")})},openApp:function(t,i){if(!t&&!i)return void alert("暂未提供与您手机系统相对应的版本, 敬请期待!");if(t){var o=document.createElement("iframe");o.style.display="none";var e,n=document.body,s=function(t,e){"function"==typeof i&&i(e),window.removeEventListener("pagehide",a,!0),window.removeEventListener("pageshow",a,!0),o&&(o.onload=null,n.removeChild(o),o=null)},a=function(t){clearTimeout(e),s(t,!1)};window.addEventListener("pagehide",a,!0),window.addEventListener("pageshow",a,!0),o.onload=s,o.src=t,n.appendChild(o);var c=+new Date;e=setTimeout(function(){e=setTimeout(function(){var t=+new Date;t-c>1285?s(null,!1):s(null,!0)},1200)},60)}else"function"==typeof i&&i()},app:function(){var t=this;$(".app-control").on("click",function(){var i=t.getSiteId(),o=t.getPageId($(this).parents(".control")),e=t.getCtrlId($(this).parents(".control"));void 0!==i&&void 0!==o&&void 0!==e&&ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+i+"?type=1&pageId="+o+"&controlId="+e);var n=$(this).children(".control-content"),s=n.data("iosopen"),a=n.data("ioslink"),c=n.data("androidopen"),r=n.data("androidlink");t.isWeixin()?(a=a?"http://z.sina.com.cn/r?u="+encodeURIComponent(a):"",r=r?"http://z.sina.com.cn/r?u="+encodeURIComponent(r):"",window.open(t.isIOS()?a:r)):t.isIOS()?t.openApp(s,function(t){location.href=a}):t.openApp(c,function(t){location.href=r})})},paint:function(){var t=function(t,i,o,e,n,s,a,c,r){this.conNode=t,this.cover=o,this.coverType=a||"image",this.background=null,this.backCtx=null,this.mask=null,this.maskCtx=null,this.lottery=i,this.lotteryType=s||"image",this.resulttitle=c||"",this.resultdesc=r||"",this.width=e||300,this.height=n||100,this.clientRect=null,this.pointRadius=40,this.percent=30,this.posX=0,this.posY=0,this.maskColBlock=Math.floor(this.width/this.pointRadius),this.numBlock=this.maskColBlock*Math.floor(this.height/this.pointRadius),this.blocksFlag=[],this.ratio=0,this.complete=!1;for(var l=this.numBlock;l--;)this.blocksFlag[l]=1;this.drawMask()};t.prototype={createElement:function(t,i){var o=document.createElement(t);for(var e in i)o.setAttribute(e,i[e]);return o},evaluatePoint:function(t,i){var o=Math.floor(t/this.pointRadius)+Math.floor(i/this.pointRadius)*this.maskColBlock;if(o>=0&&o<this.numBlock&&(this.ratio+=this.blocksFlag[o],this.blocksFlag[o]=0,!this.complete)){var e=(this.ratio/this.numBlock*100).toFixed(2);if(e>=this.percent){this.complete=!0;var n=ywy.doc.getSiteId(),s=ywy.doc.getPageId(),a=ywy.doc.getCtrlId($(this.conNode).parents(".control"));void 0!==n&&void 0!==s&&void 0!==a&&ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+n+"?type=1&pageId="+s+"&controlId="+a);var c=this,r=$(c.conNode).parents(".control").data("action"),l=$(c.conNode).parents(".control").data("actionvalue");r&&ywy.action[r]&&ywy.action[r](l),window.shareData={},this.resulttitle&&(window.shareData.title="我"+this.resulttitle.substring(1,this.resulttitle.length),window.shareData.imgUrl=this.lottery,$(".sharepic").attr("src",this.lottery),$("title").html("我"+this.resulttitle.substring(1,this.resulttitle.length)),$(this.conNode).append('<h2 class="resulttitle">'+this.resulttitle+"</h2>")),this.resultdesc&&(window.shareData.desc=this.resultdesc,$(this.conNode).append('<p class="resultdesc">'+this.resultdesc+"</p>")),wxready.shareInit(),$(this.mask).animate({opacity:0},500,function(){c.resizeCanvas(c.mask,0,0)});var h=ywy.scroll.doc.children().eq(ywy.scroll.point).data("notouch");h||ywy.scroll.ontouch()}}},resizeCanvas:function(t,i,o){t.width=i,t.height=o,t.getContext("2d").clearRect(0,0,i,o)},bindEvent:function(){var t=this,i=/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()),o=i?"touchstart":"mousedown",e=i?"touchmove":"mousemove";if(i)this.conNode.addEventListener("touchmove",function(t){n&&t.preventDefault()},!1),this.conNode.addEventListener("touchend",function(t){n=!1},!1);else{var n=!1;this.conNode.addEventListener("mouseup",function(t){n=!1},!1)}this.mask.addEventListener(o,function(o){ywy.scroll.offtouch(),n=!0,$("#daubdesc").animate({opacity:0}),setTimeout(function(){$("#daubdesc").remove()},1e3);var e=document.documentElement;t.clientRect||(t.clientRect={left:0,top:0});var s=(i?o.touches[0].clientX:o.clientX)/ywy.scroll.xrate-t.clientRect.left+e.scrollLeft-e.clientLeft,a=(i?o.touches[0].clientY:o.clientY)/ywy.scroll.yrate-t.clientRect.top+ywy.scroll.point*ywy.scroll.height+e.scrollTop-e.clientTop;t.posX=s,t.posY=a,t.maskCtx.beginPath(),t.maskCtx.moveTo(t.posX-1,t.posY),t.maskCtx.lineTo(t.posX,t.posY),t.maskCtx.stroke(),t.evaluatePoint(t.posX,t.posY)},!1),this.mask.addEventListener(e,function(o){if(!i&&!n)return!1;var e=document.documentElement;t.clientRect||(t.clientRect={left:0,top:0});var s=(i?o.touches[0].clientX:o.clientX)/ywy.scroll.xrate-t.clientRect.left+e.scrollLeft-e.clientLeft,a=(i?o.touches[0].clientY:o.clientY)/ywy.scroll.yrate-t.clientRect.top+ywy.scroll.point*ywy.scroll.height+e.scrollTop-e.clientTop;t.maskCtx.beginPath(),t.maskCtx.moveTo(t.posX,t.posY),t.posX=s,t.posY=a,t.maskCtx.lineTo(t.posX,t.posY),t.maskCtx.stroke(),t.evaluatePoint(t.posX,t.posY)},!1)},drawLottery:function(){if("image"==this.lotteryType){var t=new Image,i=this;t.onload=function(){i.resizeCanvas(i.background,i.width,i.height),i.backCtx.drawImage(this,0,0,i.width,i.height)},t.src=this.lottery}else if("text"==this.lotteryType){this.width=this.width,this.height=this.height,this.resizeCanvas(this.background,this.width,this.height),this.backCtx.save(),this.backCtx.fillStyle="#FFF",this.backCtx.fillRect(0,0,this.width,this.height),this.backCtx.restore(),this.backCtx.save();var o=30;this.backCtx.font="Bold "+o+"px Arial",this.backCtx.textAlign="center",this.backCtx.fillStyle="#F60",this.backCtx.fillText(this.lottery,this.width/2,this.height/2+o/2),this.backCtx.restore(),this.drawMask()}},drawMask:function(){if(this.background=this.background||this.createElement("canvas",{style:"position:absolute;left:0;top:0;"}),this.mask=this.mask||this.createElement("canvas",{style:"position:absolute;left:0;top:0;"}),this.conNode.innerHTML.replace(/[\w\W]| /g,"")||(this.conNode.appendChild(this.background),this.conNode.appendChild(this.mask),this.clientRect=this.conNode?this.conNode.getBoundingClientRect():null,this.bindEvent()),this.backCtx=this.backCtx||this.background.getContext("2d"),this.maskCtx=this.maskCtx||this.mask.getContext("2d"),this.resizeCanvas(this.mask,this.width,this.height),this.maskCtx.strokeStyle="rgba(255,255,255,0)","color"==this.coverType)this.maskCtx.fillStyle=this.cover,this.maskCtx.fillRect(0,0,this.width,this.height),this.maskCtx.globalCompositeOperation="destination-out",this.maskCtx.strokeStyle="rgba(255,255,255,1)",this.maskCtx.lineWidth=this.pointRadius,this.maskCtx.lineCap="round";else if("image"==this.coverType){var t=new Image,i=this;t.onload=function(){i.maskCtx.drawImage(this,0,0,i.width,i.height),i.maskCtx.globalCompositeOperation="destination-out",i.maskCtx.strokeStyle="rgba(255,255,255,1)",i.maskCtx.lineWidth=i.pointRadius,i.maskCtx.lineCap="round",i.drawLottery()},t.src=this.cover}}},$(".paint-control .control-content").each(function(i,o){var e=$(this).data("back"),n=$(this).data("cover"),s=o.offsetWidth,a=o.offsetHeight;if($(this).data("result")){var c=$(this).data("result").split("{ywy}").length-1,r=Math.floor(c*Math.random()),l=$(this).data("result").split("{ywy}")[r],h=$(this).data("resulttitle").split("{ywy}")[r],d=$(this).data("resultdesc").split("{ywy}")[r];new t(this,l,n,s,a,"image","image",h,d)}else new t(this,e,n,s,a)})},random:function(){$(".random-control .control-content").each(function(t,i){i.offsetWidth,i.offsetHeight;if($(this).data("result")){var o=$(this).data("result").split("{ywy}").length-1,e=Math.floor(o*Math.random()),n=$(this).data("result").split("{ywy}")[e],s=$(this).data("resulttitle").split("{ywy}")[e],a=$(this).data("resultdesc").split("{ywy}")[e],c=$(this).data("showtitle")?"":"style='display:none;'",r=$(this).data("showpic")?"":"style='display:none;'",l=$(this).data("showdesc")?"":"style='display:none;'",h=$('<h2 class="randomtitle" '+c+">"+s+'</h2><div class="randompic" '+r+'><img src="'+n+'"></div><div class="randomdesc" '+l+">"+a+"</div>");$(this).append(h),window.shareData={},s&&(window.shareData.title=s),a&&(window.shareData.desc=a),n&&(window.shareData.imgUrl=n),wxready.shareInit()}})},praise:function(){function t(t){t=$.extend({obj:null,str:"+1",startSize:"12px",endSize:"30px",interval:600,color:"red",callback:function(){}},t),$("body").append("<span class='num'>"+t.str+"</span>");var i=$(".num"),o=t.obj.offset().left+t.obj.width()/2-10,e=t.obj.offset().top-10;i.css({position:"absolute",left:o+"px",top:e+"px","z-index":9999,"font-size":t.startSize,"line-height":t.endSize,color:t.color}),i.animate({"font-size":t.endSize,opacity:"0",top:e-parseInt(t.endSize)+"px"},t.interval,function(){i.remove(),t.callback()})}var i=this;$(".praise-control").each(function(o,e){var n,s=!0,a=i.getSiteId(),c=i.getPageId($(e)),r=i.getCtrlId($(e).parents(".control")),l="http://z.sina.com.cn/s/collect/praised/"+a+"?type=1&pageId="+c+"&controlId="+r;$.get(l,{},function(t){n=t.data,$(e).find(".praiseNumber").text(n)},"json"),$(e).on("click",function(){s&&($(e).find(".praise").css("display","none"),$(e).find(".praised").css("display","block"),n+=1,$(e).find(".praiseNumber").text(n),t({obj:$(this),str:"赞+1",callback:function(){}}),ywy.doc.log("http://z.sina.com.cn/s/collect/insert/"+a+"?type=1&pageId="+c+"&controlId="+r)),s=!1})})},init:function(){this.button(),this.input(),this.stackimg.init(),this.wb(),this.wx(),this.app(),this.paint(),this.praise(),this.random()}},ywy.action={submit:function(){ywy.doc.submit($(item).parents(".control"))},tel:function(t){ywy.doc.tel(t)},link:function(t){ywy.doc.link(t)},topage:function(t){ywy.scroll.showStage(t-1)},tonext:function(){ywy.scroll.showStage(ywy.scroll.point+1)},showctrl:function(t){for(var t=t.split(","),i=0,o=t.length;o>i;i++)$(".active .control[data-uid='"+t[i]+"']").children(".animate").css({"-webkit-animation-play-state":"running","-webkit-animation-fill-mode":"forwards"})}},setTimeout(function(){ywy.scroll.init(),ywy.doc.init()},500);