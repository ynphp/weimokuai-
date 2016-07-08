//对方空间页
var UserInfo = (function () {
    return {
        needPhoto: function ($this) {

            if (!$this.data("visited")) {
                $this.data("visited", true)
                var userId=$this.data('afp');
                $.ajax({url: "/v20/msg/invite_upload_photo.html", type: 'post', data: {userId: userId}, dataType: "json", success: function (data) {
                    if (data == -90) {
                        location.href = "/v20/user/interceptor_photo.html"
                    } else {
                        $.tips("已发送上传照片邀请");
                    }
                }});
            }else{
                $.tips("已经索要过照片了");
            }
        }, intercept: function ($this) {
            var Id = '#' + $this.data("value");
            $("#mask").removeClass("hidden");
            $(Id).removeClass("hidden").siblings(".simple_info").addClass("hidden");
        },
        cancelCheck: function () {
            $(".simple_info").addClass("hidden");
            $("#mask").addClass("hidden");
        }
    }
})();
$(function () {
    // 更换头像图片
    var src= $(".shadowBanner img").attr("src");
    src= src.replace("/90/110","/225/275");
    $(".shadowBanner img").attr("src",src);
    
    //返回顶部
    $(".another").on("tap",function(){
        $(window).scrollTop(0);
    });
    //点击事件 打招呼
    $('body').on("tap", '[data-sayhi]', function () {
		var datatt=$(this).attr("data-source");
		var isstrategy=$(this).attr("isstrategy");
		if(isstrategy=="true"){
			$(".StrategyA,.mask").show();
			return false;
		}		
		else{
			var $this = $(this), userId = $this.data('sayhi'), source = $this.data('source');
			if (!source) {
				source = '0';
			}
			$.waiting.show("正在打招呼...")
			$('[data-sayhi]').removeAttr('data-sayhi').removeClass('hello').addClass("hello_out").html('<i class="icon-bt-hello"></i>已打招呼');
			$.ajax({url: '/v20/msg/say_hello_one.html', data: {userId: userId, source: source}, dataType: 'json', type: 'post', success: function (data) {
				if (data == 13) {
					location.href = "/v20/user/hello_template.html?userId=" + userId;
				} else {//下一位
					$.waiting.hide()
					if (data == 6) {
						$.tips("你今天已经向Ta打过招呼了。");
					} else {
						$.tips("招呼已发出，请耐心等待Ta的回复");
					}
					//两秒后跳转下一个人
				   /* setTimeout(function () {
						location.href = "/v20/user/user_info.html";
					}, 2000);*/
	
				}
			}, error: function () {
				$.waiting.hide();
			}});
		
		}
    }).on("tap", '[data-attention]', function () {
        var userId = $(this).data("attention");
        var $this = $(this);

        if ($this.hasClass("hello_out")) {
            $.ajax({url: '/v20/remove_love.html', data: {userId: userId}, dataType: 'json', type: 'post', success: function (data) {
                $this.removeClass("hello_out").html("关注");
                $.tips("你取消了对Ta的关注");
            }});
        } else {
            $.ajax({url: '/v20/add_love.html', data: {userId: userId}, dataType: 'json', type: 'post', success: function (data) {
                $this.addClass("hello_out").html("取消关注")
                $.tips("你关注了Ta");
            }});
        }
    }).on("tap", "[data-value]", function () {
        UserInfo.intercept($(this))
    }).on("tap", ".closed,#mask", function () {
        UserInfo.cancelCheck();
    }).on("tap", "[data-afp]", function () {
        UserInfo.needPhoto($(this));
    }).on("tap", '#btnSendMsg,#write_msg', function () {
        if($.header) $.header.hide();
    }).on("tap", '[data-next]', function () {
        $(this).css({'background-color':'#ccc'})
    }).on("tap",".smallShow",function(){
        //显示头像大图        
        $(".shadowBanner").show();
        var a=$(".shadowBanner img").height();
        $(".shadowBanner img").css({position:"relative",top:"50%",marginTop:-a/2-50});

    }).on("tap",".shadowBanner",function(){
        $(this).hide();
    });
    $(".date_nav li").eq(0).trigger('tap');
    if($.header) $.header.show();
    $(window).scrollTop(-1);
    //认证头像
    if((!$(".phone").hasClass("noCheck") && $(".phone")[0]) || (!$(".identity").hasClass("noCheck") && $(".identity")[0])){
        $(".personIcon").css("display","inline-block");
    }
});

/******************************************************************
H5正向化，打招呼问题拦截 2015-06-15 Liuxx
*******************************************************************/
window.onload=function(){
	$.ajax({
		//url:"http://192.168.66.90:8080/php/test5.php",
		url:"/v20/msg/getQuestionAndAnswers.html",
		type:"post",
		dataType:"json",
		//jsonpCallback:"Jsoncallback",
		//data:{name:"Zepto",type:"JSONP"},
		success:function(data){
			var yy=JSON.stringify(data);
			var answers=data.answers;
			
			/*$(".StrategyA,.mask").show();*/

		    /*这里只需一层取各项数据*/
		    for(var i in answers){
				$(".StrategyA .tit_").html(data.question);
				$(".StrategyB .tit_").html(data.nextQuestion);
			}
			/****去JSON对象中的数组转成数组字符串****/
			var arr=JSON.stringify(data.answers);
			/******************在每个逗号(,)处进行分解 ss=arr.split(",");********************/
				/***********把数组字符串转成对象后循环*************/
				var aArr=eval(arr);
				for(var k=0; k<aArr.length; k++){
					var harr='';
					var html="<li>"+aArr[k]+"</li>";
					$(".StrategyInfo .box_ ul").append(html);
				}
			
		}
	})

}

/*** 关闭弹窗 ***/
$(".close_btnR").click(function(){
	$(".StrategyInfo,.mask").hide();
});
/*** 事件跳转处理 ***/
$(document).on("tap",".StrategyInfo .box_ li",function(){
			var $this = $(".h5-hello-Btn"), userId = $this.data('sayhi'), source = $this.data('source');
			if (!source) {
				source = '0';
			}
			$.waiting.show("正在打招呼...")
			$('[data-sayhi]').removeAttr('data-sayhi').removeClass('hello').addClass("hello_out").html('<i class="icon-bt-hello"></i>已打招呼');
			$.ajax({url: '/v20/msg/say_hello_one.html', data: {userId: userId, source: source}, dataType: 'json', type: 'post', success: function (data) {
				if (data == 13) {
					location.href = "/v20/user/hello_template.html?userId=" + userId;
				} else {//下一位
					$.waiting.hide()
					if (data == 6) {
						$(".tips").css({"z-index":"2000","border":"1px solid #5c5c5c !important"})
						$.tips("你今天已经向Ta打过招呼了。");
					} else {
						$(".tips").css({"z-index":"2000","border":"1px solid #5c5c5c !important"})
						$.tips("招呼已发出，请耐心等待Ta的回复");
					}
					//两秒后跳转下一个人
				   /* setTimeout(function () {
						location.href = "/v20/user/user_info.html";
					}, 2000);*/
	
				}
			}, error: function () {
				$.waiting.hide();
			}});
			
	$(".StrategyA").hide();
	$(".StrategyB").show();
})
/*** 跳转按钮处理 ***/
$(document).on("touchstart",".StrategyInfo .box_ li",function(event){
	$(event.target).addClass("lion");
}).on("touchend",".StrategyInfo .box_ li",function(event){
	$(event.target).removeClass("lion");
});
/*** 去付费吧 ***/
$(document).on("tap",".spokbtn",function(){
	location.href="/v20/charge/pay_intercept.html";
})

/***文字广告***/
 function getAdN(){
			
			/***********************
			注明：获取广告数据接口 
			1. 用户ID是：userId
			2. 版位号是：position
			3. 回调方法：jsoncallback
			*******************************/
			$.ajax({
				//url:"http://192.168.66.90:8080/php/test7.php",
				url:"/v20/user/tip.html",
				type:"GET",
				data:{position:"weixin-userspace-01"},
				dataType:"jsonp",
				jsonp: 'Jsoncallback',
				success:function(data){
					//alert(data)
					var yy=JSON.stringify(data);
					var item=data.advert;
					if(data!=''){
						for(var i=0;i<data.advert.length;i++){
							var h = "",jsId=$("body").data("jsid"),userId=$("body").data("userid");
							if(item[i].linkType==1){
								var linkTypeN='target="_blank"';
							}
							if(item[i].linkType==0){
								var linkTypeN='';
							}
							var	html ='<li class="adli" tty="'+i+'">';
								html+='<a href="'+item[i].linkUrl+'" class="adStatistics" activeid="'+item[i].activeid+'" positionid="weixin-userspace-01" provinceid="'+data.province+'" userid="'+userId+'" adtype="'+item[i].type+'">'+item[i].content+'</a>';
								html+='</li>';
							$("#ul1").append(html);		
							// 加入统计信息
							$.get("http://x.youyuan.com/adStatistics.gif?eventid="+item[i].activeid+"&positionid=weixin-userspace-01&provinceid="+data.province+"&userid="+userId+"&adtype=OnLoad");
						}
						$("#ul1").on("click",".adStatistics",function(){
							var activeid=$(this).attr("activeid"),provinceid=$(this).attr("provinceid"),userid=$("body").attr("userid");
							// 点击统计
							if($(this).attr("activeid")) {
								$.get("http://x.youyuan.com/adStatistics.gif?eventid="+activeid+"&positionid=weixin-userspace-01&provinceid="+provinceid+"&userid="+userid+"&adtype=Click");
							}
							
						});
						//广告轮播
						i>0 && scrT();
						if($("#ul1").html()!="") {
							$("#ul1").show();
							$("#noticeDiv").show(200);
						}			
					}
				},
				error:function(status){
				// 此处放失败后执行的代码
				}
			});
        }
        var moveObj={T:0,w:0,timer:null}

		function move(obj,iTarget)
		    {
		        clearInterval(obj.timer);
		        obj.timer=setInterval(function (){
		            var iSpeed=(iTarget - moveObj.T)/8;
		            iSpeed=iSpeed>0?Math.ceil(iSpeed):Math.floor(iSpeed);
		            moveObj.T+=iSpeed;
		            obj.style.top=moveObj.T%moveObj.w+'px';
		            if(moveObj.T==iTarget){
		                clearInterval(obj.timer);
		            }
		        },30);
		    }
		function scrT(){
		   try{
		       //向上滚动
		       var oBox=document.getElementById('notice_box');
		       var oUl=oBox.getElementsByTagName('ol')[0];
		       var aLi=oUl.children;
		        if(aLi.length>1){
		            oUl.innerHTML+=oUl.innerHTML;
		            moveObj.w=oUl.offsetHeight/2;
		            var count=0;
		            clearInterval(moveObj.timer);
		            moveObj.timer=setInterval(function(){
		                count++;
		                move(oUl,-count*aLi[0].offsetHeight)
		            },2000)
		        }
		   }catch(e){}
		}
        getAdN();