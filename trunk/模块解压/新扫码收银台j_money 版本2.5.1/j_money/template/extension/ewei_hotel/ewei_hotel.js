// JavaScript Document
function ewei_hotel_js(uniacid,printid){
	isPaying=true;
	var weid=uniacid;
	swal({
		title: "微酒店付款", 
		type: "input",
		html:true,
		showLoaderOnConfirm: true,
		showCancelButton: true,   
		closeOnConfirm: false,
		confirmButtonText: "确认",
		cancelButtonText: "关闭",  
		inputPlaceholder: "请输入订单号" 
		}, function(inputValue){
		if (inputValue === false) return false;      
		if (inputValue === "") {
			swal.showInputError("请输入订单号");
			return false
		}
		swal({title:"请稍后",showConfirmButton:false });
		ewei_hotel_get_order(weid,inputValue,printid);
	});
}
function ewei_hotel_get_order(uniacid,orderid,printid){
	$.post("./index.php?i="+uniacid+"&c=entry&do=extension&m=j_money&extend_modal=ewei_hotel&op=getorder",{"ordersn":orderid},function(data){
		console.log(data);
		var result=eval("("+data+")");
		if(result.success){
			var _item=result.msg;
			var status=parseInt(_item.status);
			if(status!=1){
				swal("该订单不符合规定，必须是已确认订单方可支付。");
				return;
			}
			swal({
				title: "是否确认进行收款？",   
				text: "客户名称："+_item.name+"<br>客户电话："+_item.mobile+"<br>支付金额：￥"+_item.cprice+"元<br>状态："+_item.cprice+"",
				html: true,
				showCancelButton:true,
				closeOnConfirm: false,
				confirmButtonText: "确认",
				cancelButtonText: "关闭",
				}, function(isConfirm){
					isPaying=false;
					if (isConfirm) {
						console.log(_item.ordersn);
						old_orderid=_item.ordersn;
						extendPay(old_orderid,_item.cprice,function(){ewei_hotel_pay_success(old_orderid)},printid);
					}
				}
			);
		}else{
			swal(data);
		}
	})
}
function ewei_hotel_pay_success(osn){
	$.post("./index.php?i="+uniacid+"&c=entry&do=extension&m=j_money&extend_modal=ewei_hotel&op=paysuccess",{"ordersn":osn},function(data){
		console.log(data);
	})	
}