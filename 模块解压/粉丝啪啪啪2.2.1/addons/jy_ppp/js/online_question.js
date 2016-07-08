//在线提问
var Online=(function(){
    return {              
            submit:function(val1,val2){
                $.ajax({url:"/v20/online_question_send.html",
                	data:{id:$("#id").val(), categoryId: $("#categoryId").val(),content:val1,phone:val2},
                	type:'post',dataType:'json',
                	error:function(){
                		$.tips("网络不畅，请稍后再试.");	
                	},
                	success:function(data){
                		if(data==0){
                			location.href = "/v20/online_question_succ.html?categoryId="+$("#categoryId").val();
                		}else if(data==-1){
                			$.tips("发送失败，请重新发送.");
                		}else if(data==-2){
                			$.tips("用户不存在.");
                		}else if(data==-3){
                			$.tips("10秒内不允许连续发信!");
                		}else if(data==-4){
                			$.tips("请填写正确的手机号码.");
                		}else if(data==-5){
                			$.tips("不能少于10个汉字.");
                		}
                    }
                });
            }
        };

})();

$(function () {
    //聚焦
    $("body").on('tap','#sendBtn',function(){
            var val1=$("#tex1").val();
            var val2=$("#tex2").val();
          if(val1&&val2)
          {
              Online.submit(val1,val2);
          }else{
        	  $.tips("内容不能为空.");
          }
    });

});