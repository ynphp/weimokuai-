$(document).ready(function(){
	$(".select").click(function(){
		$(".popmian").slideDown("slow");
		$(".pay").parent().show();
		$(".select").parent().parent().hide();
		$('.bread').parent('div').addClass("popbg");
	});
	$(".qita input").live("focus",function(){
		$(".popmian ul").find(".choose").remove();
		$(".qita input").val("");
	});
	$(".popmian ul").find("a").click(function(){
		$(".popmian ul").find(".choose").remove();
		$(this).append("<div class=\"choose\"><span></span></div>");
		var text =$(this).attr("date-param");
		$("#price").val(text);
		
	});
	
	$(".qita input").keyup(function(){
		$(".popmian ul").find(".choose").remove();
		var text =$(this).val();
		var value = text.replace(/[^\d.]/g,""); 
		$("#price").val(value);
	});
	$(".pay").click(function(){
		var pay = $("#price").val();
		if(pay<=0)
		{
			 alert("请选择金额!");
			 return;
		}
		if(pay<0.01)
		{
			 alert("最小金额为0.01元!");
			 return;
		}
		if(!$("#check").is(':checked'))
		{
			 alert("请确认认筹协议!");
			 return;
		}
		if(date3<=0)
		{
			 alert("活动已结束");
			 return;
		}
		if(price<=0)
		{
			 alert("众筹额度已满");
			 return;
		}
		if(pay>price)
		{
			 alert("当前众筹额度只剩"+price+"元");
			 $("#price").val(price);
			 $(".qita input").val(price);
			 return;
		}
		
		$(this).attr("class","unpay cheng");
		$(".weipay").removeClass("display");
		var data = $("#info_form").serialize();
		
	    var themeid = $('#themeid').val();
	    var memberid = $("#memberid").val();
	    var fprice = $("#price").val();
	    
	    $(".weipay").addClass("display");
	    $.ajax({
	    	type:'POST',
	    	url:payurl,
	    	data:{themeid:themeid,memberid:memberid,price:fprice},
	    	success:function(data){
	    		data  = eval("(" + data +")");
	            if(data.status==0){
	            	window.location.href=data.msg;
	            }else{
	            	$(".weipay").removeClass("display");
					$(".unpay").attr("class","pay cheng");
					alert(data.msg);
	            }
	        }    
	    });
/*        $.get("index.php?g=Wap&m=Crowdfunding&a=pay&"+data,function(json){
        	$(".weipay").addClass("display");
        	if(json.status==0){
				window.location.href=json.msg;
			}
			else{
				$(".weipay").removeClass("display");
				$(".unpay").attr("class","pay cheng");
				alert(json.msg);
			}
        },"json");*/
	});
	
	$(".support").click(function(){
		$(".protocol").removeClass("display");
	});
	$(".close").click(function(){
		$(".protocol").addClass("display");
	});
	$(".yao").click(function(){
		$(".friend").removeClass("display");
	});
	$(".friend").click(function(){
		$(".friend").addClass("display");
	});
	$(".pophead a").click(function(){
		$('.bread').parent('div').removeClass("popbg");
    	$(".popmian").slideUp("slow");
		$(".pay").parent().hide();
		$(".select").parent().parent().show();
	});
	$(".first").click(function(){
		$(".popmian").slideDown("slow");
		$(".pay").parent().show();
		$(".select").parent().parent().hide();
		$('.bread').parent('div').addClass("popbg");
	});
	$(".all_art").click(function(){
		$(".discription").removeClass("display");
	});
	$(".art_close").click(function(){
		$(".discription").addClass("display");
	});
});