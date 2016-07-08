<?php
global $_W,$_GPC;
$reply_table = 'meepo_paoma_reply';
$activity_table = 'meepo_paoma_activity';
$user_table = 'meepo_paoma_user';
$rotate_id = intval($_GPC['rotate_id']);
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
if($rotate_id){
$data = pdo_fetchall("SELECT * FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND point != 0 ORDER BY point DESC,createtime ASC LIMIT 0,19",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id));

}else{
message('轮数错误');
}
$theone = pdo_fetch("SELECT `point`,`createtime` FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND openid = :openid",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id,':openid'=>$_W['openid']));
if(!empty($theone)){
		if($theone['createtime'] != 0){
				$over = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND point >= :point AND createtime <=:creatime AND openid != :openid",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id,':point'=>$theone['point'],':creatime'=>$theone['createtime'],':openid'=>$_W['openid']));
		}else{
				$over = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($user_table)." WHERE rid = :rid AND weid = :weid AND rotate_id = :rotate_id  AND point >= :point AND createtime >=:creatime AND openid != :openid",array(':rid'=>$rid,':weid'=>$weid,':rotate_id'=>$rotate_id,':point'=>$theone['point'],':creatime'=>$theone['createtime'],':openid'=>$_W['openid']));
		}
}else{
message('错误、您本轮排名无结果');
}
include $this->template('phb');