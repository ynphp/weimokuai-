$('body').on("tap", '[this-goback]', function (e) {//网页后退到第几步
	$("#div1,#div3,#nav1").show();
	$("#div2,#nav2").hide();
    }
).on("tap", '[showblack]', function (e) {
	$("#div1,#div3,#nav1").hide();
	$("#div2,#nav2").show();
	
});