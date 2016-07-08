<?php
defined('IN_IA') or exit('Access Denied');
class Beauty_zhongchouModuleSite extends WeModuleSite {
	//会员信息提取
	public function __construct(){
		global $_W;
		load()->model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$_W['openid']}'");
		if (empty($profile)) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($userinfo['avatar'])) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'openid' => $userinfo['openid'],
					'nickname' => $userinfo['nickname'],
					'avatar' => $userinfo['avatar']
				);
				$member = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$userinfo['openid']}'");
				if (empty($member['id'])) {
					pdo_insert('beatuty_zhongchou_member', $data);
				}else{
					pdo_update('beatuty_zhongchou_member', $data, array('id' =>$member['id']));
				}
			}
		}
	}

	/*微信端*/
	public function doMobileIndex() {
		
		global $_GPC, $_W;
		$data = $this -> module['config'];
		$rule = $data['content'];
		include $this -> template('index');
	}
	public function doMobileOpenshare() {
		
		global $_GPC, $_W;
		$data = $this -> module['config'];
		$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$_W['openid']}'");
		//众筹的钱
		$orders = pdo_fetchall("select * from".tablename('beatuty_zhongchou_orders')."where uniacid={$_W['uniacid']} and fromopenid='{$_W['openid']}' and status=1");
		$money = 0;
		foreach($orders as $key=> $value){
			$money = $money+$value['num'];
			$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$value['openid']}'"); 
			$orders[$key]['avatar'] = $profile['avatar'];
			$orders[$key]['nickname'] = $profile['nickname'];
		} 
		include $this -> template('openshare');
	}
	
	public function doMobileShareindex() {
		global $_GPC, $_W;
		//接受谁发送给自己的分享的openid
		$fromopenid = $_GPC['fromopenid'];
		//获取参数设置
		$data = $this -> module['config'];
		$rule = $data['content'];
		$op = $_GPC['op'];
		$fromprofile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$fromopenid}'"); 
		
		//支持排行
		if(empty($op)){
			if(!empty($fromopenid)){
			$res = pdo_fetch("select * from".tablename('beatuty_zhongchou_orders')."where uniacid={$_W['uniacid']} and openid='{$_W['openid']}' and fromopenid='{$fromopenid}' and status<>0");
			if(!empty($res)){
				$price = $res['num'];
			}
			$orders = pdo_fetchall("select * from".tablename('beatuty_zhongchou_orders')."where uniacid={$_W['uniacid']} and fromopenid='{$fromopenid}' and status<>0");
			if(!empty($orders)){
			foreach($orders as $key=>$value){
				$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$value['openid']}'"); 
				$orders[$key]['avatar'] = $profile['avatar'];
				$orders[$key]['nickname'] = $profile['nickname'];
				}
			}
		}
		}
//		if ($op == 'pay') {
//			$fromopenid = $_GPC['fromopenid'];
//			message("ss=".$fromopenid);exit;
//			header("location: " . $this -> createMobileUrl('topay',array('fromopenid'=>$fromopenid)));
//		}
		include $this -> template('shareindex');
	}

	public function doMobileTopay() {
		global $_GPC, $_W;
		$data = $this -> module['config'];
		$fromopenid = $_GPC['fromopenid'];
		$myopenid = $_W['openid'];
		$fromprofile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$fromopenid}'"); 
		
		if(checksubmit('submit')){
			$fromopenid = $_GPC['fromopenid'];
			$myopenid = $_W['openid'];
			$num = $_GPC['money'];
			$content = $_GPC['content'];
			$data=array(
			'ordersn'=>date('ymdhis') . random(4, 1),
			'openid'=>$_W['openid'],
			'num'=>$num,
			'fromopenid'=>$fromopenid,
			'content'=>$content,
			'uniacid'=>$_W['uniacid'],
			'createtime'=>TIMESTAMP
			);
//		echo "<pre>";
//		print_r($data);
//		exit;
			pdo_insert('beatuty_zhongchou_orders',$data);
			$orderid = pdo_insertid();
			header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));	
		}else{
			include $this -> template('topay');
		}
		
	}
	public function doMobilePay() {
		global $_GPC, $_W;
		$orderid = $_GPC['orderid'];
		if (empty($orderid)) {
      	  message('抱歉，参数错误！', '', 'error');
    	}else{
    		$order = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_orders') . " WHERE id =$orderid");
    		$params['tid'] = $order['ordersn'];
    		$params['user'] = $_W['fans']['from_user'];
    		$params['fee'] = $order['num'];
    		$params['title'] = $_W['account']['name'];
    		$params['ordersn'] = $order['ordersn'];
   	 }
		$params['module'] = "beauty_zhongchou";
		include $this->template('pay');
}
	public function doMobileAjaxpay() {
		global $_GPC, $_W;
		load() -> func('communication');
		$fromopenid = $_GPC['fromopenid'];
		$myopenid = $_W['openid'];
		$num = 1;
		$content = $_GPC['content'];
		include_once '../addons/beauty_zhongchou/WxPay.Api.php';
		$WxPayApi = new WxPayApi();
		$input = new WxPayUnifiedOrder();
		//				$key=$this->module['config']['apikey'];//商户支付秘钥（API秘钥）
		$key = 'xLeBESmggiQMGpba2ieADmRQDlDPmIL8';
		$account_info = pdo_fetch("select * from" . tablename('account_wechats') . "where uniacid={$_W['uniacid']}");
		//身份标识（appid）
		$appid = $account_info['key'];
		//$mchid=$this->module['config']['mchid'];//微信支付商户号(mchid)
		$mchid = '1239385202';
		$input -> SetAppid($appid);
		$input -> SetMch_id($mchid);
		$input -> SetBody("test");
		$input -> SetAttach("test");
		$input -> SetOut_trade_no($mchid . date("YmdHis"));
		$input -> SetTotal_fee($num);
		$input -> SetTime_start(date("YmdHis"));
		$input -> SetTime_expire(date("YmdHis", time() + 600));
		$input -> SetGoods_tag("test");
		$input -> SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input -> SetTrade_type("JSAPI");
		$input -> SetOpenid($myopenid);
		$result = $WxPayApi -> unifiedOrder($input, 6, $key);
		//随机字符串
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < 32; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		//签名
		$sign = $input->SetSign($key);
		$data=array();
		$data['pack'] = $result['prepay_id'];
		$data['appId'] = $appid;
		$data['timeStamp'] = TIMESTAMP;
		$data['nonceStr'] = $str;
		$data['signType'] = "MD5";
		$data['paySign'] = $sign;
		
		//生成订单
//		$data2 = array(
//			'uniacid' => $_W['uniacid'],
//			'openid' => $myopenid,
//			'ordersn' => date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99)),
//			'content'=>$content,
//			'num' => $num,
//			'status' => 0,//订单状态，-1取消状态，0普通状态，1为已付款，2为已发货，3为成功
//			'fromopenid'=>$fromopenid,
//			'content'=>
//			'createtime' => TIMESTAMP
//		);
		pdo_insert('beatuty_zhongchou_orders',$data2);
		$orderid = pdo_insertid();
		$order = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_orders') . " WHERE id =$orderid");
		$params['tid'] = $order['ordersn'];
    	$params['user'] = $_W['fans']['from_user'];
    	$params['fee'] = $order['num'];
    	$params['title'] = $_W['account']['name'];
    	$params['ordersn'] = $order['ordersn'];
		$params['module'] = "beatuty_zhongchou";
		//转成json
		$dataall = array(
			'data'=>$data,
			'params'=>$params
		);
		
		return json_encode($dataall);
		
	}
	public function doMobileFund() {
		global $_GPC, $_W;
		$num = $_GPC['num'];
		$content = $_GPC['content'];
		$fromopenid = $_GPC['fromopenid'];
		$data=array(
			'ordersn'=>date('ymdhis') . random(4, 1),
			'openid'=>$_W['openid'],
			'num'=>$num,
			'fromopenid'=>$fromopenid,
			'content'=>$content,
			'uniacid'=>$_W['uniacid'],
			'createtime'=>TIMESTAMP,
		);
		pdo_insert('beatuty_zhongchou_orders',$data);
	}

	/*web端*/
	public function doWebOrder() {
		global $_GPC, $_W;
		load() -> func('tpl');
		$weid = $_W['uniacid'];
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$condition = 'uniacid=:weid';
			$paras = array(':weid' => $_W['uniacid']);
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND  ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			//获取所有场次
			$sql = "select DISTINCT fromopenid from" . tablename('beatuty_zhongchou_orders') . "where $condition order by createtime desc " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$orders = pdo_fetchall($sql, $paras);
			foreach($orders as $key =>$value){
				$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '{$value['fromopenid']}'"); 
				$orders[$key]['nickname'] = $profile['nickname'];
				$allorder = pdo_fetchall("select * from".tablename('beatuty_zhongchou_orders')."where fromopenid='{$value['fromopenid']}' order by createtime desc");
				$price=0;
				foreach($allorder as $k=>$v){
					$odr= pdo_fetch("select * from".tablename('beatuty_zhongchou_orders')."where id={$v['id']}");
					$orders[$key]['ordersn'] = $odr['ordersn'];
					$price = $odr['num'] + $price;
				}
				
				$orders[$key]['num'] = $price;
				$orders[$key]['shu'] = count($allorder);
			}
			$total = count($orders);
			$pager = pagination($total, $pindex, $psize);
		}
		if ($operation == 'detail') {
				$fromopenid = $_GPC['fromopenid'];
				$oo = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$fromopenid}'"); 
				$allorder = pdo_fetchall("select * from".tablename('beatuty_zhongchou_orders')."where fromopenid='{$fromopenid}' and uniacid={$_W['uniacid']} order by createtime desc");
				foreach($allorder as $k=>$v){
					$profile = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_member') . " WHERE uniacid ={$_W['uniacid']} and openid = '{$v['openid']}'"); 
					$allorder[$k]['nickname'] = $profile['nickname'];
					$allorder[$k]['avatar'] = $profile['avatar'];
				}
			
		}
		if ($operation == 'delete') {
			$fromopenid = $_GPC['fromopenid'];
			pdo_delete('beatuty_zhongchou_orders', array('fromopenid' => $fromopenid));
			message('删除场次成功', $this -> createWebUrl('order'), 'success');

		}

		include $this -> template('order');

	}

	public function payResult($params) {
		global $_W;
		$fee = intval($params['fee']);
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
		$data['pay_type'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}
		// //货到付款
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
			$data['ptime'] = TIMESTAMP;
		}
		if ($params['result'] == 'success') {
			$data['ptime'] = TIMESTAMP;
		}
		$order = pdo_fetch("SELECT * FROM " . tablename('beatuty_zhongchou_orders') . " WHERE ordersn='{$params['tid']}'");
		if ($order['status'] != 1) {
			pdo_update('beatuty_zhongchou_orders', $data, array('ordersn' => $params['tid']));
		}
		if ($params['from'] == 'return') {
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				echo "<script>alert(' 支付成功!'); location.href='".$this->createMobileUrl('shareindex',array('fromopenid'=>$order['fromopenid']))."';</script>";
				exit;
			} else {
				echo "<script> alert(' 支付成功!');location.href='".$_W['siteroot'].'app/'.$this->createMobileUrl('shareindex',array('fromopenid'=>$order['fromopenid']))."';</script>";
				exit;
			}
		}

	}
}
