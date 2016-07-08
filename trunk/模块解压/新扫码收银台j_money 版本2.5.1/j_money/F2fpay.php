<?php
require_once '../addons/j_money/alipay/AopSdk.php';
require_once '../addons/j_money/alipay/function.inc.php';

class F2fpay {
	
	public function barpay($out_trade_no, $auth_code, $total_amount, $subject,$config) {
		
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content.="\"scene\":\"bar_code\",";
		$biz_content.="\"auth_code\":\"" . $auth_code . "\",";
		$biz_content.="\"total_amount\":\"" . $total_amount. "\",\"discountable_amount\":\"0.00\",";
		$biz_content.="\"subject\":\"" . $subject . "\",\"body\":\"goodpay\",";
		$biz_content.="\"timeout_express\":\"5m\"}";
		//echo $biz_content;
		//writeLog("response: ".var_export($result,true));
		$request = new AlipayTradePayRequest();
		$request->setBizContent ($biz_content);
		
		$response = aopclient_request_execute ( $request,$config);
		return $response;
	}
	
	
	public function qrpay($out_trade_no,  $total_amount, $subject,$config) {
		
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\",";
		$biz_content.="\"total_amount\":\"" . $total_amount
		. "\",\"discountable_amount\":\"0.00\",";
		$biz_content.="\"subject\":\"" . $subject . "\",\"body\":\"支付宝扫码付款\",";
		$biz_content.="\"timeout_express\":\"5m\"}";
		//echo $biz_content;
		$request = new AlipayTradePrecreateRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request,$config);
		return $response;
	}
	
	
	public function query($out_trade_no,$config) {	
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeQueryRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request,$config);
		return $response;
	}
	
	
	public function cancel($out_trade_no,$config) {
		$biz_content="{\"out_trade_no\":\"" . $out_trade_no . "\"}";
		$request = new AlipayTradeCancelRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request,$config);
		return $response;
	}
	
	public function refund($trade_no,
			$refund_amount, $out_request_no,$config) {
		$biz_content = "{\"trade_no\":\"". $trade_no . "\",\"refund_amount\":\""
						. $refund_amount
						. "\",\"out_request_no\":\""
								. $out_request_no
								. "\",\"refund_reason\":\"reason\",\"store_id\":\"store001\",\"terminal_id\":\"terminal001\"}";
		
		$request = new AlipayTradeRefundRequest();
		$request->setBizContent ( $biz_content );
		$response = aopclient_request_execute ( $request,$config);
		return $response;
	}
}