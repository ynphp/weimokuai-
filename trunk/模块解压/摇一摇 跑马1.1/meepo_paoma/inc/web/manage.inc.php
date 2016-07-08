<?php
		global $_W,$_GPC;
		$activity_table = 'meepo_paoma_activity';
		$user_table = 'meepo_paoma_user';
		$reply_table = 'meepo_paoma_reply';
		$id = intval($_GPC['id']);
		if(empty($id)){
		   message('错误、规则不存在！');
		}
		$reply = pdo_fetch("SELECT * FROM " . tablename($reply_table) . " WHERE rid = :rid", array(':rid' => $id));
		if(empty($reply)){
		   message('活动规则不存在！');
		}
		$pindex = max(1, intval($_GPC['page']));
	  $psize = 20;
		$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
		//pdo_delete($activity_table,array('weid'=>$_W['uniacid']));
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$sql = "SELECT * FROM ".tablename($activity_table)." WHERE {$where} ORDER BY id ASC  LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($activity_table) . " WHERE {$where} ORDER BY id ASC", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'post'){
					$rotate_id = intval($_GPC['rotate_id']);
					if(!empty($rotate_id)){
		           $activity = pdo_fetch("SELECT * FROM ".tablename($activity_table)." WHERE id = :id AND weid = :weid",array(':id'=>$rotate_id,':weid'=>$_W['uniacid']));
					}else{
						   $activity['status'] = 0;
							 $activity['pnum'] = 3;
					}
					if(checksubmit('submit')){
						 $rotate_id = intval($_GPC['rotate_id']);
						 $data = array();
						 $data['weid'] = $_W['uniacid'];
						 $data['pnum'] = intval($_GPC['pnum']);
					   if(empty($rotate_id)){
								$data['status'] = 0;
								$data['createtime'] = time();
								$data['rid'] = $id;
								pdo_insert($activity_table,$data);
								message('新增轮数成功',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"success");
						 }else{
								$data['status'] = intval($_GPC['status']);
								pdo_update($activity_table,$data,array("weid"=>$_W['uniacid'],"id"=>$rotate_id,"rid"=>$id));
								message('编辑成功',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"success");
						 }
					
				  }
			 }elseif($op == 'delete'){
					$rotate_id = intval($_GPC['rotate_id']);
					if(empty($rotate_id)){
		           message('删除项目不存在',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"error");
					}
					pdo_delete($activity_table,array('id'=>$rotate_id,'rid'=>$id,'weid'=>$_W['uniacid']));
					pdo_delete($user_table,array('rotate_id'=>$rotate_id,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"success");
			 }elseif($op == 'ajax'){
					$rotate_id = intval($_GPC['rotate_id']);
					
					$user_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($user_table) . " WHERE weid = :weid AND rid = :rid AND rotate_id = :rotate_id ",array(':weid'=>$_W['uniacid'],':rid'=>$id,':rotate_id'=>$rotate_id));
					die(json_encode(array('user'=>$user_total)));
			 }else{
			   message('访问错误');
			 }

		if(checksubmit('delete')){
			//批量删除
			$select = $_GPC['select'];
			if(empty($select)){
				message('请选择删除项',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"error");
			}
			foreach ($select as $se) {
				pdo_delete($activity_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
				pdo_delete($user_table,array('rotate_id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
				
				
			}
			message('批量删除成功',$this->createWebUrl("manage",array('id'=>$id,'page'=>$pindex)),"success");
		}
    include $this->template('manage');
		
	 