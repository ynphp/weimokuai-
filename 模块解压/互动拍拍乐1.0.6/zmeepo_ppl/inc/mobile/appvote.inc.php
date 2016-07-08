<?php
global $_W,$_GPC;
$activity_table = 'meepo_paipaile_activity';
$photo_table = 'meepo_paipaile_photo';
$vote_table = 'meepo_paipaile_vote';
if($_W['isajax']){
	$actid = intval($_GPC['actid']);
	$rid = intval($_GPC['rid']);
	$check = pdo_fetchcolumn("SELECT `onoff` FROM ".tablename($this->activity_table)." WHERE weid = :weid AND id = :id AND rid = :rid",array(":weid"=>$_W['uniacid'],':id'=>$actid,':rid'=>$rid));
	if($check != 2){
	  $data[0]['state'] = 0;	
	}else{
			
			$data = pdo_fetchall("SELECT * FROM ".tablename($photo_table)." WHERE weid = :weid AND actid = :actid AND rid = :rid",array(":weid"=>$_W['uniacid'],":actid"=>$actid,':rid'=>$rid));
			
			if(is_array($data) && !empty($data)){
					foreach($data as &$row){
							$row['state'] = 3;
					}
					unset($row);
			}
	}
	die(json_encode($data));
}