$(function() {
	setInterval(function(){
		var firstLi=$(".scrollBox li").first(),
			heiLi=$(".scrollBox li").first().height()-10,
			len=$(".scrollBox li").length;
		if(len>1){
			$(".scrollBox ul").animate({"marginTop":-heiLi+"px"},1000,function(){
				$(this).append(firstLi);
				$(this).css("marginTop",10+"px")
			})
		}else{
			return false;
		}
	},5000)
	
	//ȫ�ֵ�ajax���ʣ�����ajax����ʱsesion��ʱ 
	$.ajaxSetup({ 
		contentType : "application/x-www-form-urlencoded;charset=utf-8", 
		complete : function(XMLHttpRequest, textStatus) { 
			var sessionstatus = XMLHttpRequest.status; // ͨ��XMLHttpRequestȡ����Ӧͷ��sessionstatus�� 
			if (sessionstatus == 403) { 
//				�����ʱ�ʹ��� ��ָ��Ҫ��ת��ҳ�� 
				window.location.href='/pc/login.do';
			} 
		} 
	}); 
})