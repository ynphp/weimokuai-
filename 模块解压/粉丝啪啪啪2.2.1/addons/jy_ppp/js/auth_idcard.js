//认证页面
var Idcard = (function () {
    return {
        param: false,
        checkNub: function () {
            //校验输入是否正确
            var name=$("#cardName").val();
            var cardNub = $("#cardNo").val();
            if (name == '' || cardNub == '') {
                return false;
            }
            var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
            var reg2=/^\W+$/;
            if(!reg2.test(name) || name.length<2 )
            {
                $.tips("请输入正确的姓名");
                return false;
            }
            if (!reg.test(cardNub)) {
                $.tips("身份证输入不合法");
                return false;
            }
            Idcard.param={cardNo:  cardNub, name:name };
            return true;
        },
        submit: function () {
            if (!Idcard.checkNub()) {
                return;
            }
            $.waiting.show("处理中...");
            $.ajax({url: '/v20/info/do_auth_idcard.html', data: Idcard.param, dataType: 'json', type: 'post', success: function (text) {
                $.waiting.hide();
                if(text){
                	if(text.status == -1){
                		$("#mask").removeClass("hidden");
                	}else{
                		if(text.needFee==1){
                			$("#doubiTip").html("本次验证消耗你60豆币！");
                		}
                		$("#section2").removeClass("hidden");
                        $("#section1").addClass("hidden");
                        $("#checkName").html(text.name);
                        $("#checkSex").html(text.sex == 0 ? "男" : "女");
                        $("#checkBirth").html(text.birthday);
                        $("#checkAdress").html(text.area);
                        $("#checkNub").html(text.cardNo);
                        $("#checkInfor").addClass("hidden");
                	}
                    
                }else{
                    $.tips("核查失败")
                }
            }, error: function () {
                $.waiting.hide();
                $.tips("核查失败");
                }
            })
        }
    }
})();
$(function () {
    //点击提交
    $("body").on("tap", '#card_submit', function () {
        Idcard.submit();
    }).on("tap", '.btn_cancel', function () {
    	$("#mask").addClass("hidden");
    });

})