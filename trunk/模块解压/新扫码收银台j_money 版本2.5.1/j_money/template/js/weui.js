// JavaScript Document
//$.jweui.loading('baz'); 
jQuery.jweui = {
	loading:function(param) {
		var param=0;
		if(arguments.length){
			param=arguments[0];
		}
		if(param=='hide'){
			if($("#loadingToast").size())$("#loadingToast").remove();
		}else{
			var temphtml='<div id="loadingToast" class="weui_loading_toast"><div class="weui_mask_transparent"></div><div class="weui_toast"><div class="weui_loading"><div class="weui_loading_leaf weui_loading_leaf_0"></div><div class="weui_loading_leaf weui_loading_leaf_1"></div><div class="weui_loading_leaf weui_loading_leaf_2"></div><div class="weui_loading_leaf weui_loading_leaf_3"></div><div class="weui_loading_leaf weui_loading_leaf_4"></div><div class="weui_loading_leaf weui_loading_leaf_5"></div><div class="weui_loading_leaf weui_loading_leaf_6"></div><div class="weui_loading_leaf weui_loading_leaf_7"></div><div class="weui_loading_leaf weui_loading_leaf_8"></div><div class="weui_loading_leaf weui_loading_leaf_9"></div><div class="weui_loading_leaf weui_loading_leaf_10"></div><div class="weui_loading_leaf weui_loading_leaf_11"></div></div><p class="weui_toast_content">数据加载中</p></div></div>';
			if($("#loadingToast").size()){
				$("#loadingToast").show();
				return;
			}
			$("body").append(temphtml);
			$("#loadingToast").show();
		}
	},
	msg:function() {
		var title="提示";
		var content="";
		if(arguments.length==2){
			title=arguments[0];
			content=arguments[1];
		}else if(arguments.length==1){
			content=arguments[0];
		}
		var temp='<div class="weui_dialog_alert"><div class="weui_mask"></div><div class="weui_dialog"><div class="weui_dialog_hd"><strong class="weui_dialog_title">'+title+'</strong></div><div class="weui_dialog_bd">'+content+'</div><div class="weui_dialog_ft"><a href="javascript:$(\'.weui_dialog_alert\').remove()" class="weui_btn_dialog primary">确定</a></div></div></div>';
		$("body").append(temp);
	},
	menu:function(){
		var temphtml="";
		if(arguments.length>0){
			for(i in arguments){
				temphtml+='<div class="weui_actionsheet_cell">'+arguments[i]+'</div>';
			}
		}
		var temp='<div id="actionSheet_wrap"><div class="weui_mask_transition" id="mask"></div><div class="weui_actionsheet" id="weui_actionsheet"><div class="weui_actionsheet_menu">'+temphtml+'</div><div class="weui_actionsheet_action"><div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div></div></div></div>';
		if($("#actionSheet_wrap").size()>0)$("#actionSheet_wrap").remove();
		$("body").append(temp);
		
		var mask = $('#mask');
		var weuiActionsheet = $('#weui_actionsheet');
		weuiActionsheet.addClass('weui_actionsheet_toggle');
		mask.show().addClass('weui_fade_toggle').one('click', function () {
			hideActionSheet(weuiActionsheet, mask);
		});
		$('#actionsheet_cancel').one('click', function () {
			hideActionSheet(weuiActionsheet, mask);
		});
		weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');

		function hideActionSheet(weuiActionsheet, mask) {
			weuiActionsheet.removeClass('weui_actionsheet_toggle');
			mask.removeClass('weui_fade_toggle');
			weuiActionsheet.on('transitionend', function () {
				mask.hide();
			}).on('webkitTransitionEnd', function () {
				mask.hide();
			})
		}
	},
	
}