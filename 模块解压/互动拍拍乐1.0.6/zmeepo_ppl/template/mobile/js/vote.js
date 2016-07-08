$(function() {
	//$('#dimmer1').dimmer('toggle');
	$.getJSON("function.php?do=getinfo", function(json){
		if(json){
			/**
			**
			首先获取当前状态。
				json[0].state = 1表示未上传
			 * 2表示上传中
			 * 3表示投票中
			*/
			if(json[0].state == 3){
				$(".loadmassage").hide();
				$("#votebox").show();
				$("#votebutton").html('<div class="fluid ui green button votebutton" id="votesubmit">就投他/她</div>');
				$.each(json, function(i,v){
					var columnid=chosecolumn();
					adduserinfo(v,columnid);
				});
					$('.select').on("click", function(){
                	    if ($("input[type='checkbox']:checked").length<votecannum){
                	        $(this).parent().find("input").click();
                			$('#dimmer'+$(this).parent().attr('name')).dimmer('toggle');
                	    }else{
                	        if($(this).parent().find("input").prop("checked")){
                			$('#dimmer'+$(this).parent().attr('name')).dimmer('toggle');
                    	        $(this).parent().find("input").click();
                	        }else{
                	        alert('最多选择'+votecannum+'个！');
                	          }
                	    }
                		});

			}else{
				//还没开始投票
				$(".loadmassage").html("<h1>投票还没开始/或者您已经投过票了，请返回等待下一轮投票开始！</h1>");
			}
		}
	});
	$("body").on("click","#votesubmit", function(){
	    if($("input[type='checkbox']:checked").length!=votecannum){
	       alert('请选择'+votecannum+'个选项再提交！');
	    }else{
			var valarr=new Array ();
			$.each($("input[type='checkbox']:checked"),function(i,n){
				valarr[i]=$("input[type='checkbox']:checked:eq("+i+")").val();
			});
			$.post("function.php?do=submit", { voteid: valarr},
			   function(data){
				   if(data== "sorry2"){
						 alert("请选"+votecannum+"个选项！");
				   }else if(data== "sorry"){
					    alert("对不起您已经投过票了！");
				   }else if(data== "sorry3"){
					    alert("对不起,投票已经结束！");
				   }else{
					   
				 alert("您已经成功投票了！");
				 
				   }
				   location.href='index.php';
			   });
		}
    });
	$('#closewindow').on("click",function(){
		WeixinJSBridge.invoke('closeWindow',{});
		})
		function adduserinfo(json,columnid){
			var userhtmls='<div class="ui left floated medium image userbox " name="'+json.id+'"><div class="select"></div><input type="checkbox" value="'+json.id+'" hidden /><div class="ui dimmer" id="dimmer'+json.id+'"><div class="content"><div class="center"><i class="icon circular inverted emphasized red checkmark"></i>你已经选择了他/她。</div></div></div> <a class="ui left corner label"> <div class="text">'+json.id+'</div> </a><img src="'+json.picurl+'"><div class="username">'+json.nickname+'</div></div>';
			$("#column"+columnid).append(userhtmls);
		}
			function chosecolumn(){
				if($("#column1").height()>=$("#column2").height()){
					return 2;
				}else{
					return 1;
				}
			}
	
});
		function onBridgeReady(){
		 WeixinJSBridge.call('hideOptionMenu');
		}
		
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
				document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			}
		}else{
			onBridgeReady();
		}