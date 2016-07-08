<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$user_table = 'meepo_paoma_user';
$activity_table = 'meepo_paoma_activity';
$reply_table = 'meepo_paoma_reply';
if($_W['isajax']){
	$rid = intval($_GPC['rid']);
	$rotate_id = intval($_GPC['rotate_id']);
	$pnum = intval($_GPC['pnum']);
	$maxshake = pdo_fetchcolumn("SELECT `maxshake` FROM ".tablename($reply_table)." WHERE weid=:weid AND rid = :rid",array(":weid"=>$weid,":rid"=>$rid));
  $check = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename($user_table)." WHERE rotate_id = :rotate_id AND weid = :weid AND rid = :rid AND point >= :point",array(":rotate_id"=>$rotate_id,':weid'=>$weid,':rid'=>$rid,':point'=>$maxshake));
	if($pnum == 100){
	  $pnum = 10;
	}
	if($check >= $pnum){
	  pdo_update($activity_table,array('status'=>2),array('rid'=>$rid,'id'=>$rotate_id));
		$status = -1;
	}else{
	  $status = 0;
	}
	
	$data = pdo_fetchall("SELECT * FROM ".tablename($user_table)." WHERE rid = :rid AND rotate_id = :rotate_id AND weid = :weid ORDER BY point DESC,createtime ASC  LIMIT 0,9",array(':rid'=>$rid,':rotate_id'=>$rotate_id,':weid'=>$weid));
	
	if(is_array($data)){
			foreach($data as &$row){
					$row['progress'] = sprintf("%.2f", ($row['point']/$maxshake)*100 );
			}
			unset($row);
	}
	
	
	$lists = array();
	$lists['ret'] = 0;
	$lists['data']['status'] = $status ;
	$lists['data']['players'] = $data;
	
	die(json_encode($lists));
}