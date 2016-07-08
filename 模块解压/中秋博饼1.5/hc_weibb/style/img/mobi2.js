KISSY.use('node,io',function(S,Node,IO){
	var $=Node.all;

    var REG={
        name:/^[a-zA-Z0-9\u4e00-\u9fa5]{2,12}$/,
        phone:/(^(([0\+]\d{2,3}-)?(0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$)|(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/,
        wxid:/^[a-zA-Z][a-zA-Z0-9_-]{5,19}$/,
        number:/^[+\-]?\d+(\.\d+)?$/
    }

    //注册提交
    var submitReg=$('#J_submitReg');
    var name=$('#name');
    var phone=$('#phone');
    var DATA={}

    submitReg.on('click',function(){
        //姓名
        if(name.length==1){
            var nv=S.trim(name.val());
            if(nv==''){
                alert('姓名不能为空！');
                return false;
            }else if(!REG.name.test(nv)){
                alert('请填写正确的姓名！');
                return false;
            }
            DATA.name=nv;
        }
        //手机
        if(phone.length==1){
            var pv=S.trim(phone.val());
            if(pv==''){
                alert('手机号不能为空！');
                return false;
            }else if(!REG.phone.test(pv)){
                alert('请填写正确的手机号！');
                return false;
            }
            DATA.phone=pv;
        }

        //请求
        IO.post($(this).attr('data-action'),DATA,function(data){
        	/*
				status: 状态
				url: 成功后的跳转页面
        	*/
        	if(data.status==200){
        		window.location.href=data.url;
        	}else{
        		alert('提交失败，请重试！');
        	}
        },'json');
    });
});
window.addEventListener("orientationchange", function(){
	  if(window.orientation != 0){
	      alert('为保证最佳浏览体验，请使用竖屏 :)');
	  }
	}, false);

function onBridgeReady(){
	 WeixinJSBridge.call('showOptionMenu');
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
