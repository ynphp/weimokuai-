<?php
global $_W,$_GPC;
include_once INC_PATH.'web/set/navs.php';
$op = !empty($_GPC['op'])?trim($_GPC['op']):'set';
load()->func('file');
if($op == 'set'){
	load()->func('tpl');
	$panel_heading = '基本设置';
	$setting = pdo_fetch("SELECT * FROM ".tablename('meepo_tu_set')." WHERE uniacid = '{$_W['uniacid']}' limit 1");
	if($_W['ispost']){
		$data = array(
			'title'=>$_GPC['title'],
			'uniacid'=>$_W['uniacid'],
			'wx_name'=>$_GPC['wx_name'],
			'wx_num'=>$_GPC['wx_num'],
			'share_title'=>$_GPC['share_title'],
			'share_content'=>$_GPC['share_content'],
			'share_img'=>$_GPC['share_img']
		);
		
		if(!empty($_GPC['share_img'])){
			file_delete($_GPC['old_share_img']);
		}else{
			unset($data['share_img']);
		}
		
		if(!empty($setting)){
			pdo_update('meepo_tu_set',$data,array('uniacid'=>$_W['uniacid']));
		}else{
			pdo_insert('meepo_tu_set',$data);
		}
		message('提交成功',referer(),'success');
	}
	
	if(empty($setting)){
		$setting = array(
		
			'title'=>'联盟微信墙,吐槽表白心愿,有你更精彩',
			'wx_name'=>'大学生创业联盟',
			'wx_num'=>'微信号：imeepos',
			'share_title'=>'联盟微信墙,全民来吐槽',
			'share_content'=>'联盟微信墙,吐槽表白心愿,有你更精彩',
			'share_img' =>'', 
		);
		
	}
	include $this->template('settings');

}

