<?php
global $_W,$_GPC;
if(!empty($this->module['config']['appid'])&&!empty($this->module['config']['appsecret'])) {
	$this->auth();
}else{
	$user_agent  = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') === false) {
		die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
	}
}
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();

$openid=$_W['openid'];
$pid=$_GPC['pid'];
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");
$item=pdo_fetch("select * from ".tablename('enjoy_recuit_info')." where openid='".$openid."' and uniacid=".$_W['uniacid']."");

if(checksubmit('submit')){

	$data=array(
			'uniacid'=>$_W['uniacid'],
			'openid'=> $_GPC['openid'],
			'birth'=> $_GPC['birth'],
			'register'=> $_GPC['register'],
			'address'=> $_GPC['address'],
			'marriage'=> $_GPC['marriage'],
			'weight'=> $_GPC['weight'],
			'height'=> $_GPC['height'],
			'school'=> $_GPC['school'],
			'createtime'=> TIMESTAMP
	);

	if (!empty($item)) {
		//更新数据

		pdo_update('enjoy_recuit_info', $data, array('openid' => $openid,'uniacid'=>$_W['uniacid']));
		//更新时间
		pdo_update('enjoy_recuit_basic', array('createtime'=>TIMESTAMP), array('openid' => $openid,'uniacid'=>$_W['uniacid']));
		$message="更新信息成功！";
	} else {
		//插入数据
		pdo_insert('enjoy_recuit_info', $data);
		//更新时间
		pdo_update('enjoy_recuit_basic', array('createtime'=>TIMESTAMP), array('openid' => $openid,'uniacid'=>$_W['uniacid']));
		$message="添加信息成功！";
	}
		
	//message($message, $this->createMobileUrl('resume',array('pid'=>$pid)), 'success');
	header("location:".$this->createMobileUrl('resume',array('pid'=>$pid))."");

}
include $this->template('detail');