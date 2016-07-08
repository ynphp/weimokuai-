<?php
global $_W,$_GPC;
$operation = $_GPC['op']?$_GPC['op']:'display';
$aid=intval($_GPC['aid']);
$reply=pdo_fetch("SELECT * FROM ".tablename('j_act_activity')." where id=:id ",array(':id'=>$aid));
if(!$reply)die(json_encode(array('success'=>true,'msg'=>"活动不存在")));

if($operation=="post"){
	
}elseif($operation=='getvoteresult'){
	
}