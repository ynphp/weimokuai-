function paging(but, scurPage, stotalPage, url) {
	var curPage = parseInt(scurPage);
	var totalPage = parseInt(stotalPage);
	var targetPage;
	if (but == 'first') {
		if (curPage <= 1) {
			return false;
		} else {
			targetPage = 1;
		}
	}
	if (but == 'pre') {
		if (curPage < 2) {
			return false;
		} else {
			targetPage = curPage - 1;
		}
	}
	if (but == 'next') {
		if (curPage >= totalPage) {
			return false;
		} else {
			targetPage = curPage + 1;
		}
	}
	if (but == 'end') {
		if (curPage >= totalPage) {
			return false;
		} else {
			targetPage = totalPage;
		}
	}
	window.location.href = url + "&c_p=" + targetPage;
}

function updateTopicVisitPerson(wxaccount, topicId) {
	if (wxaccount == null || wxaccount == 'null' || topicId == null
			|| topicId == 'null') {
		return false;
	}
	var url = "/topics?ac=updatePersonNum&wxaccount=" + wxaccount + "&topicId="
			+ topicId;
	$.get(url);
}

function updateArticleVisitPerson(wxaccount, aId) {
	if(wxaccount == null || wxaccount == 'null' || aId == null || aId == 'null'){
		return false;
	}
	var url ="articles?ac=updateVisitPerson&wxaccount=" + wxaccount + "&aId=" + aId;
	$.get(url);
}

function getArticleVisitPerson(wxaccount, aId) {
	if(wxaccount == null || wxaccount == 'null' || aId == null || aId == 'null'){
		return 0;
	}
	var url ="articles?ac=getVisitPerson&wxaccount=" + wxaccount + "&aId=" + aId;
	var xhr;
	if (window.ActiveXObject) {
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	} else if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}

	xhr.open('GET', url, false);
	xhr.send(null);
	return xhr.responseText;
}

function setShareInfo(basePath, wxaccount, userwx, aId){
	var url ="articles?ac=getArt&wxaccount=" + wxaccount + "&aId=" + aId;
	$.get(url,function(data){
		var obj = $.parseJSON(data);
		msg_cdn_url = obj.picUrl;
		msg_title = obj.title;
		msg_desc = obj.desc;
		
		var locUrl = obj.locUrl; 
		if(locUrl.indexOf('http') < 0){
			locUrl = basePath + locUrl;
		}
		if(locUrl.indexOf('?') > -1){
			locUrl += '&';
		}else {
			locUrl += '?';
		}
		locUrl += 'wxaccount=' + wxaccount +'&userwx=' + userwx;
		msg_link = locUrl;
	});
}

function checkInDiv(x, y) {
	var left = $("#bar_menu").offset().left;
	var top = $("#bar_menu").offset().top;
	var width = $("#bar_menu").width();
	var height = $("#bar_menu").height();
	if (x >= left && x <= (left + width) && y >= top && y <= (top + height)) {
		return true;
	}
	return false;
}

function hideMenu(e){
	var display = $("#bar_menu").css("display");
		if (display == "block") {
			 var tar = e.target.id;
			if (tar != "showMenu" && tar != "showMenu_") {
				var x = e.clientX;
				var y = e.clientY;
				var rs = checkInDiv(x, y);
				if (rs == false) {
					$("#bar_menu").hide();
				}
			}
		}
}

function showMenu(){
	var display = $("#bar_menu").css("display");
		if (display == "none") {
			$("#bar_menu").show();
		} else {
			$("#bar_menu").hide();
		}
}

function showNotice(text){
	if ($("#showNotice").length == 0) {
		var div = "<div id='showNotice'></div>"
		$(div).appendTo("body");
	}	
	var top = document.body.scrollTop;
	$("#showNotice").text(text);
	$("#showNotice").css("top", (top + 130));
	$("#showNotice").fadeIn(1000).delay(3000).fadeOut(1500);
}

function menuClick(obj,url){
	obj.style.backgroundColor = '#050505';
	window.location.href = url;
}

function resetAll() {
	document.form.reset();
}

function clearMsg() {
	$("#msg").text("");
}

function checkMM() {
	var browser = {
		versions : function() {
			var u = navigator.userAgent, app = navigator.appVersion;
			return {
				trident : u.indexOf('Trident') > -1, //IE内核
				presto : u.indexOf('Presto') > -1, //opera内核
				webKit : u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
				symbian : u.indexOf('Symbian') > -1, //塞班系统
				gecko : u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
				mobile : !!u.match(/AppleWebKit.*Mobile/)
						|| !!u.match(/Windows Phone/) || !!u.match(/Android/)
						|| !!u.match(/MQQBrowser/),
				ios : !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
				android : u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
				iPhone : u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
				iPad : u.indexOf('iPad') > -1, //是否iPad
				webApp : u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
			};
		}()
	}

	if (browser.versions.ios == true || browser.versions.android == true
			|| browser.versions.iPhone == true || browser.versions.iPad == true
			|| browser.versions.mobile == true) {
	}else{
		$.get("/logs?ac=webIllegal",{content:navigator.userAgent});
		window.location.href = 'error_mobile.html';
	}
}

function showScreenNotice_text(text, align){
	$("#bar_menu").hide();
	$("#howToPub").remove();
	var sn= "<div class='screenNotice' id='howToPub' onclick='hideScreenNotice();'><table width='100%' height='100%' border=0 style='color:#fff;'><tr height='70%' align="+align+"><td style='padding:0 30px'>"+text+"</td></tr><tr align='center' valign=top><td><input type='button' value='知道了' style='color: #fff;background-color: #000;border: 1px solid #fff;border-radius: 5px;width: 120px;height: 35px;'></td></tr></table></div>";
	$("body").append(sn);
	$("#howToPub").show();
}

function showScreenNotice_img(){
	$("#bar_menu").hide();
	$("#howToPub").remove();
	var sn = "<div class='screenNotice' id='howToPub' onclick='hideScreenNotice();'><table width='100%' height='100%' border=0 style='color:#fff;'><tr height='70%' align='right' valign='top'><td><img src='/static_/gztx.jpg' /></td></tr><tr align='center' valign=top><td><input type='button' value='知道了' style='color: #fff;background-color: #000;border: 1px solid #fff;border-radius: 5px;width: 120px;height: 35px;'></td></tr></table></div>";
	$("body").append(sn);
	$("#howToPub").show();
}

function hideScreenNotice(){
	$(".screenNotice").hide();
}

function optionDefaultSelect(obj, val) {
	$.each(obj, function(i, n) {
		if (n.value == val) {
			n.selected = "selected";
		}
	});
}