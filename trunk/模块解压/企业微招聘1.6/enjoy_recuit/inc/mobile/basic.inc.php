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
$openid=$_W['openid'];
$item=pdo_fetch("select * from ".tablename('enjoy_recuit_basic')." where openid='".$openid."' and uniacid=".$_W['uniacid']."");
if(empty($item['avatar'])){
	$item['avatar']=pdo_fetchcolumn("select avatar from ".tablename('enjoy_recuit_fans')." where openid='".$openid."' and uniacid=".$_W['uniacid']."");
}else{
	$item['avatar']=tomedia($item['avatar']);
}
$pid=$_GPC['pid'];
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");

if(checksubmit('submit1')){
	// var_dump($_GPC);
	// var_dump($_FILES['file']['name']);
	// exit();
	$data=array(
			'uniacid'=>$_W['uniacid'],
			'openid'=> $_GPC['openid'],
			'uname'=> $_GPC['uname'],
			'sex'=> $_GPC['sex'],
			'age'=> $_GPC['age'],
			'ed'=> $_GPC['ed'],
			'mobile'=> $_GPC['mobile'],
			'email'=> $_GPC['email'],
			//	'avatar'=> $_GPC['avatar'],
			'present'=> $_GPC['present'],
			'createtime'=> TIMESTAMP
	);

	if (!empty($item['uname'])) {
		//更新数据

		pdo_update('enjoy_recuit_basic', $data, array('openid' => $openid,'uniacid'=>$_W['uniacid']));
			
	} else {
		//插入数据
		pdo_insert('enjoy_recuit_basic', $data);
			
	}
	if(!empty($_FILES['file']['name'])){
			
		$this->ImgUpload();
	}
	header("location:".$this->createMobileUrl('resume',array('pid'=>$pid))."");



}

include $this->template('basic');