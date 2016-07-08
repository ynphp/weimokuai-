//账号设置页面
var Account = (function () {
    return{
        changePw: function () {
            $("#show_pwd").addClass("hidden");
            $("#log_out").hide();
            $("#change_pwd").removeClass("hidden");
        },
        logout:function(){
            if(confirm("亲，真的要走吗？Ta的来信便看不到了。")){
                $.post('/v20/user/logout.html', function (data) {
                    location.href = "/?from=5599";
                });
            }
        },
        backJob:function(){
            var time = 4;
            $('#timer').text(time);
            var itv = setInterval(function () {
                $('#timer').text(time--);
                if (time == 0) {
                    clearInterval(itv);
                    history.go(-1);
                }
            }, 1000);
        },
        checkIsEqualPassword: function (password) {
            //不能重复的字符
        	var flag = true;
        	var str = password.charAt(0);
        	  for (var i = 0; i < password.length; i++) {
        	   if (str != password.charAt(i)) {
        	    flag = false;
        	    break;
        	   }
        	  }
        	  return flag;            
        },
        checkIsPlusNumeric: function (password) {
            //不能为连续递增数字
        	var flag = true;// 如果全是连续数字返回true
        	var isNumeric = true;// 如果全是数字返回true
        	  for (var i = 0; i < password.length; i++) {
        	   if (isNaN(password.charAt(i))) {
        	    isNumeric = false;
        	    break;
        	   }
        	  }
        	  if (isNumeric) {// 如果全是数字则执行是否连续数字判断
        	   for (var i = 0; i < password.length; i++) {
        	    if (i > 0) {// 判断如123456
        	    	var num = parseInt(password.charAt(i) + "");
        	    	var num_ = parseInt(password.charAt(i - 1) + "") + 1;
        	     if (num != num_) {
        	      flag = false;
        	      break;
        	     }
        	    }
        	   }
        	  } else {
        	   flag = false;
        	  }
        	  return flag;        	
        },
        checkIsMinusNumeric: function (password) {
            //不能为连续递减数字
        	 var flag = true;// 如果全是连续数字返回true
        	 var isNumeric = true;// 如果全是数字返回true
        	  for (var i = 0; i < password.length; i++) {
        	   if (isNaN(password.charAt(i))) {
        	    isNumeric = false;
        	    break;
        	   }
        	  }
        	  if (isNumeric) {// 如果全是数字则执行是否连续数字判断
        	   for (var i = 0; i < password.length; i++) {
        	    if (i > 0) {// 判断如654321
        	    	var num = parseInt(password.charAt(i) + "");
        	    	var num_ = parseInt(password.charAt(i - 1) + "") - 1;
        	     if (num != num_) {
        	      flag = false;
        	      break;
        	     }
        	    }
        	   }
        	  } else {
        	   flag = false;
        	  }
        	  return flag;       	
        },
        submit: function () {
            var reg = /^[0-9A-Za-z]{6,20}$/;
            var t1 = $("#tex1").val();
            var t2 = $("#tex2").val();

            if (!t1||!t2)
            {	
            	$.tips("请填写密码");
            	return;
            }
            if( !reg.test(t2)) {
                $.tips("请输入6—20位的密码");
                return;
            }
            //不能为连续数字或重复的字符
            if(Account.checkIsEqualPassword(t2)||Account.checkIsPlusNumeric(t2)||Account.checkIsMinusNumeric(t2)){
            	 $.tips("您的密码过于简单！");
                 return;
            } 
    		
            $.waiting.show("处理中...");
            $.ajax({url: '/v20/info/change_pass.html', data: {old_pass: t1, new_pass: t2}, dataType: 'json', type: 'post', success: function (data) {
                $.waiting.hide();
                if (data == -2) {
                    $.tips("原密码输入有误");
                } else if (data == -3) {
                    $.tips("您的密码过于简单！");
                } else if (data == -1) {
                    $.tips("保存失败");
                } else if (data == 1) {
                    $("#change_pwd").addClass("hidden");
                    $("#change_pwd_success").removeClass("hidden");
                    Account.backJob();
                }
            },
                error: function () {
                    $.waiting.hide();
                    $.tips("网络出现异常");
                }
            });
        }
    }
})();

$(function () {
    //修改密码
    $("body").on("tap", "#change_password", function () {
        Account.changePw();
    });
    //提交修改密码
    $("body").on("tap", "#submit", function () {
        Account.submit();
    });
    $("body").on("tap", "#log_out", function () {
        Account.logout();
    });

})