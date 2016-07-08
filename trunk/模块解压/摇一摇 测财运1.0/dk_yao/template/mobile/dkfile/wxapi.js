var loadJS = function(url,callback){ 
	var head = document.getElementsByTagName('head'); 
	if(head&&head.length){ 
		head = head[0]; 
	}else{ 
		head = document.body; 
	} 
	var script = document.createElement('script'); 
	script.src = url; 
	script.type = "text/javascript"; 
	head.appendChild( script); 
	script.onload = script.onreadystatechange = function(){ 
		if ((!this.readyState) || this.readyState == "complete" || this.readyState == "loaded" ){ 
			callback(); 
		} 
	}
} 
var wxMsg = {
		signature:false,
		code:-2,
		message:"",
		appId:'aaa'
};
loadJS("http://res.wx.qq.com/open/js/jweixin-1.0.0.js",function(){
	wx.config({
	    debug: false, 
	    appId: 'aaa',
	    timestamp:'aaa',
	    nonceStr: 'aaa', 
	    signature: 'aaa',
	    jsApiList: ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage',
	                  'onMenuShareQQ','onMenuShareWeibo','hideMenuItems',
	                  'showMenuItems','hideAllNonBaseMenuItem','showAllNonBaseMenuItem',
	                  'translateVoice','startRecord','stopRecord',
	                  'onRecordEnd','playVoice','pauseVoice','stopVoice',
	                  'uploadVoice','downloadVoice','chooseImage',
	                  'previewImage','uploadImage','downloadImage',
	                  'getNetworkType','openLocation','getLocation',
	                  'hideOptionMenu','showOptionMenu','closeWindow',
	                  'scanQRCode','chooseWXPay','openProductSpecificView',
	                  'addCard','chooseCard', 'openCard'
	                ]
		});
	//sig error
	wx.error(function(res){
		wxMsg.signature = false;
		wxMsg.code = 1;
		wxMsg.message = "ǩ��ʧ�ܣ�";
	});
	//sig success
	wx.ready(function(){
		wxMsg.signature = true;
		try {
			readyWeixin();
		} catch (e) {
		}
		// Ҫ���صĲ˵���
		try {
			wx.hideMenuItems({
			    menuList: [
			               "menuItem:originPage",
			               "menuItem:copyUrl",
			               "menuItem:openWithQQBrowser",
			               "menuItem:openWithSafari",
			               "menuItem:share:email",
			               "menuItem:readMode",
			               "menuItem:editTag",
			               "menuItem:jsDebug",
			               "menuItem:delete",
			               "menuItem:editTag",
			               "menuItem:share:qq"
			               ] // Ҫ���صĲ˵��ֻ�����ء������ࡱ�͡������ࡱ��ť������menu�����¼3
			});
		} catch (e) {
		}
	});
});

//ͼƬ����
var images = {
	    localId: [],//����Id
	    serverId: [],//��Ѷ������Id
	    picUrl:[],//ͼƬ������url
	    upLength:0,
	    callBack:null
};

function upPic(i){
	 var  length = images.localId.length;
	try {
		  	if(wxMsg.signature  && length > i){
					  wx.uploadImage({
					        localId: images.localId[i],
					        success: function (res) {
						  	  //��ѶͼƬId
					          images.serverId.push(res.serverId);
					          //����ͼƬ
					          loadJS();
					        },
					        fail: function (res) {
					          $('.ui-loader').hide
					          alert(JSON.stringify(res));
					        }
				 });
		  	}else{
		  		alert('��ʱ�޷��ϴ�');
		  	}
		} catch (e) {
				$('.ui-loader').hide
				alert('�ϴ�ͼƬ����');
		}
}

//�ϴ����Լ��ķ���������
function callbackUpload(upMsg){
	 images.upLength ++ ;
	if(upMsg.state){
		 images.picUrl.push(upMsg.info);
         if(images.localId.length > images.upLength){
       	  upPic(images.upLength);
         }else{
          $('.ui-loader').hide();
          if(typeof(images.callBack) == 'function'){
        	  images.callBack(upMsg.info);
          }
       	  alert('�ϴ��ɹ�');
         }
	}else{
		alert(upMsg.info);
	}
}
//�ϴ���Ƭ
function uploadPic(localIds,callBack){
	  try {
		  images.localId = localIds; 
		  images.upLength = 0;
		  images.serverId = [];
		  images.picUrl = [];
		  images.callBack = callBack;
		} catch (e) {
			
	  }
	  upPic(0);
};





