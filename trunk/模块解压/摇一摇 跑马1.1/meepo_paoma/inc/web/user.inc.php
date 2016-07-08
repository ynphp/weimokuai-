<?php
		global $_W,$_GPC;
		$activity_table = 'meepo_paoma_activity';
		$user_table = 'meepo_paoma_user';
		$reply_table = 'meepo_paoma_reply';
		$id = intval($_GPC['id']);
		$rotate_id = intval($_GPC['rotate_id']);
		if(empty($rotate_id)){
				message('错误、轮数不存在！');
		}
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
		    $where = " weid = :weid AND rid = :rid";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
			
			if($op == 'list'){
			  $params = array();
        $where = " weid = :weid AND rid = :rid AND rotate_id = :rotate_id";
				$params[':weid'] = $_W['uniacid'];
				$params[':rid'] = $id;
				$params[':rotate_id'] = $rotate_id;
				$sql = "SELECT * FROM ".tablename($user_table)." WHERE {$where} ORDER BY point DESC,createtime ASC  LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
				$lists = pdo_fetchall($sql,$params);
				//VAR_DUMP($lists);
				$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($user_table) . " WHERE {$where} ", $params);
			  $pager = pagination($total, $pindex, $psize);
		   }elseif($op == 'delete'){
					$user_id = intval($_GPC['user_id']);
					if(empty($user_id)){
		           message('删除项目不存在',$this->createWebUrl("manage",array('id'=>$id,'rotate_id'=>$rotate_id,'page'=>$pindex)),"error");
					}
					pdo_delete($user_table,array('id'=>$user_id,'rid'=>$id,'weid'=>$_W['uniacid']));
					message('删除成功',$this->createWebUrl("user",array('id'=>$id,'rotate_id'=>$rotate_id,'page'=>$pindex)),"success");
			 }else{
			   message('访问错误');
			 }

		if(checksubmit('delete')){
			//批量删除
			$select = $_GPC['select'];
			if(empty($select)){
				message('请选择删除项',$this->createWebUrl("user",array('id'=>$id,'rotate_id'=>$rotate_id,'page'=>$pindex)),"error");
			}
			foreach ($select as $se) {
				pdo_delete($user_table,array('id'=>$se,'rid'=>$id,'weid'=>$_W['uniacid']));
				
				
			}
			message('批量删除成功',$this->createWebUrl("user",array('id'=>$id,'rotate_id'=>$rotate_id,'page'=>$pindex)),"success");
		}
    include $this->template('user');
		
	 