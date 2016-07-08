var Photo=(function(){ 
    return {
            T:0,
            w:0,
            sT:false,
            timer2:null,
        loading:function(img){
            $("#change_icon div").remove();
            $('#change_icon').append('<div class="checking">上传中...</div><div class="uploading"></div>');
        },
        changePhoto:function(data){
            if (data.re == 1) {
                $("#change_icon form").remove();
                $("#change_icon div").remove();
                $('#change_icon').append('<div class="checking">审核中</div>');
                 $('#change_icon img').attr({src: 'http://pd.youyuan.com/resize/photo/n/n/n/81/99' + data.path});
            } else {
                $.tips("上传失败，图片不得超过3M！");
            }
        },
        move:function(obj,iTarget)
            {
                clearInterval(obj.timer);
                obj.timer=setInterval(function (){
                    var iSpeed=(iTarget - Photo.T)/8;
                    iSpeed=iSpeed>0?Math.ceil(iSpeed):Math.floor(iSpeed);
                    Photo.T+=iSpeed;
                    obj.style.top=Photo.T%Photo.w+'px';
                    if(Photo.T==iTarget){
                        clearInterval(obj.timer);
                    }
                },30);
            },
        scrT:function(){
           try{
               //向上滚动
                if(Photo.sT)return;
               Photo.sT=true;
               var oBox=document.getElementById('notice_box');
               var oUl=oBox.getElementsByTagName('ol')[0];
               var aLi=oUl.children;
                if(aLi.length>1){
                    oUl.innerHTML+=oUl.innerHTML;
                    Photo.w=oUl.offsetHeight/2;
                    var count=0;
                    clearInterval(Photo.timer2);
                    Photo.timer2=setInterval(function(){
                        count++;
                        Photo.move(oUl,-count*aLi[0].offsetHeight)
                    },2000)
                }
           }catch(e){}
        }
    }
})();
var Index = (function () {
    return {
        getTarget: function () {
            var target = window.location.hash;
            if (target && target.length > 0) {
                target = target.substring(1);
            }
            return target
        },
        getAd : function(){
            var url = "",$adList = $("#ul1"),key=$adList.data('key');
            if(!$adList[0]){return;}
            $.post(url,{"position":key},function(data){
                if(data && data['advert'] && data['advert'].length>0){
                    var h = "",jsId=$("body").data("jsid"),userId=$("body").data("userid"), i=0,item,provinceId=data["province"],advert=data['advert'];
                    for(;item=advert[i];i++) {
                        var $i = $('<li><a href=""></a></li>');
                        if (i == 0 && $adList.html() == "") {
                            $i.addClass("first");
                        }

                        $i.find("a").data("activeId", item['activeid']);
                        $i.find("a").data("positionid", key);
                        $i.find("a").data("provinceid", provinceId);
                        $i.find("a").data("userid", userId);
                        $i.find("a").data("adtype", item["type"]);
                        var link = item['linkUrl'];
                        if (link.indexOf("${") > 0) {
                            link = link.replace(/\$\{jsId\}/gi, jsId);
                            link = link.replace(/\$\{userId\}/gi, userId);
                        }
                        $i.find("a").attr("href", link).html(item['content']);
                        if (item['linkType'] == 1) {
                            $i.find("a").attr("target", '_blank');
                        }

                        $adList.append($i);
                        // 加入统计信息
                        $.get("http://x.youyuan.com/adStatistics.gif?eventid=" + item["activeid"] + "&positionid=" + key + "&provinceid=" + provinceId + "&userid=" + userId + "&adtype=OnLoad&_=16");
                    }
                    $adList.on("touchstart","a",function () {
                        // 点击统计
                        if($(this).data("activeId")) {
                            $.get("http://x.youyuan.com/adStatistics.gif?eventid=" + $(this).data("activeId") + "&positionid=" + $(this).data("positionid") + "&provinceid=" + $(this).data("provinceid") + "&userid=" + $(this).data("userid") + "&adtype=Click&_=16");
                        }
                    });
                    if($adList.html()!="") {
                        $adList.show();
                        $("#noticeDiv").show(200);
                    }
                }else{
                    if($adList.html()=="") {
                        $adList.hide();
                        $("#noticeDiv").hide();
                    }
                }
            });
            if($adList.html()!="") {
                $adList.show();
                $("#noticeDiv").show(200);
            }
        },
        getAdN : function(){
			
			/***********************
			注明：获取广告数据接口 
			1. 用户ID是：userId
			2. 版位号是：position
			3. 回调方法：jsoncallback
			*******************************/
			ajax({
				//url:"http://192.168.66.90:8080/php/test7.php",
				url:"",
				type:"POST",
				data:{position:"H5-A1"},
				dataType:"jsonp",
				callback:"Jsoncallback",
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
								html+='<a href="'+item[i].linkUrl+'" class="adStatistics" activeid="'+item[i].activeid+'" positionid="H5-A1" provinceid="'+data.province+'" userid="'+userId+'" adtype="'+item[i].type+'">'+item[i].content+'</a>';
								html+='</li>';
							$("#ul1").append(html);		
							// 加入统计信息
							$.get("http://x.youyuan.com/adStatistics.gif?eventid="+item[i].activeid+"&positionid=H5-A1&provinceid="+data.province+"&userid="+userId+"&adtype=OnLoad");
						}
						$("#ul1").on("click",".adStatistics",function(){
							var activeid=$(this).attr("activeid"),provinceid=$(this).attr("provinceid"),userid=$("body").attr("userid");
							// 点击统计
							if($(this).attr("activeid")) {
								$.get("http://x.youyuan.com/adStatistics.gif?eventid="+activeid+"&positionid=H5-A1&provinceid="+provinceid+"&userid="+userid+"&adtype=Click");
							}
							
						});
						if($("#ul1").html()!="") {
							$("#ul1").show();
							$("#noticeDiv").show(200);
						}
					}
				},
				fail:function(status){
				// 此处放失败后执行的代码
				}
			});
        },
        init: function () {
            if (window.youyuan) {youyuan.store($("#GUID").val());}
            // $.header.show();
            if(getCookie("showBrowser")){
                $(".book_mark").remove();
            }
            browser();
            // 加载广告信息
            // this.getAdN();
            setTimeout(Photo.scrT,1000)
        },
        tipPhotoInterceptor: function () {
        	$.ajax({url: '',
                success: function (data) {
                	if(data == 1){
                		$("#mask").removeClass("hidden");
                	}
                }, error: function () {
                    $.tips("网络不畅，请稍后再试");
                }
            })
        },
        tipHongniangInterceptor: function () {
        	$.ajax({url: '',
                success: function (data) {
                	if(data == 1){
                		$("#hongniang_mask").removeClass("hidden");
                	}
                }, error: function () {
                    $.tips("网络不畅，请稍后再试");
                }
            })
        }
    }
})();

//微信改版
$(function () {
    //验证 取消验证
    $("body").on('change', '#province select', function () {
        var $this = $(this),value=$this.val(),  $value =$("#province span"),text=$this.find('option:selected').text();
        $.ajax({url: '',
            data: {con_province: value},
            type: 'post', dataType: 'json',
            success: function (re) {
                    $value.text(text);
                location.reload();
            }, error: function () {
                $.tips("网络不畅，请稍后再试");
            }
        })
    }).on("tap",".see_more",function(){
            //刷新头部页眉
            $.header.hide();
            $.header.show();
    }).on("tap", '#mask .btn_sure', function () {
        $("#mask").addClass("hidden");
        location.href='/v20/info/photo_upload.html';
    }).on("tap", '#mask .btn_cancel', function () {
    	$("#mask").addClass("hidden");
    }).on("tap", '#hongniang_mask .hn_btn', function () {
    		$("#hongniang_mask").addClass("hidden");
    		$.ajax({url: '',
	          success: function (data) {
	          	if(data == 1){ //不拦截 直接提示’领取成功‘
	          			$.tips("领取成功");
	          	}else{ //跳拦截页面
	          			location.href='/v20/info/hongniang_service_interceptor.html';
	          	}
	          }, error: function () {
	              $.tips("网络不畅，请稍后再试");
	          }
	      })
    }).on("tap", '#hongniang_mask .hn_closed', function () {
    	$("#hongniang_mask").addClass("hidden");
    });
    Index.init();
    Index.tipPhotoInterceptor();
    Index.tipHongniangInterceptor();
});