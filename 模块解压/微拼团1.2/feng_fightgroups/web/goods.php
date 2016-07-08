<?php
	$ops = array('display', 'edit', 'delete'); // 只支持此 3 种操作.
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';

	if($op == 'display'){
		$pindex = max(1, intval($_GPC['page'])); //当前页码
		$psize = 10;	//设置分页大小 
		$goodses = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$weid}' ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_goods') . "WHERE uniacid = '{$weid}'"); //记录总数
		$pager = pagination($total, $pindex, $psize);

		include $this->template('goods');
	}
	//商品编辑
	if ($op == 'edit') {
		$id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:id ';
			$params = array(':id'=>$id);
			$goods = pdo_fetch($sql, $params);
			//获取当前图集
			$listt = pdo_fetchall("SELECT * FROM" . tablename('tg_goods_atlas') .  "WHERE g_id = '{$id}' ");

			$piclist = array();

				if(is_array($listt)){

					foreach($listt as $p){

						$piclist[] = $p['thumb'];

					}

				}
			if(empty($goods)){
				message('未找到指定的商品.', $this->createWebUrl('goods'));
			}

			$orders = pdo_fetchall("SELECT * FROM" . tablename('tg_order') .  "WHERE g_id = '{$id}'");
			$arr = array();
			foreach ($orders as $key => $value) {
				$arr['endtime'] = $value['endtime'];
			}
			$endtime = $arr['endtime'];
		}
		if (checksubmit()) {
			$images = $_GPC['img'];//获取图集
			$data = $_GPC['goods']; // 获取打包值
			empty($data['gname']) && message('请填写商品名称');
//			empty($data['fk_typeid']) && message('请选择商品分类');
//			empty($data['gsn']) && message('请填写商品货号');
			empty($data['gnum']) && message('请填写商品库存');
			empty($data['gprice']) && message('请填写商品团购价');
			empty($data['oprice']) && message('请填写商品商品单买价');
			empty($data['gimg']) && message('请上传图片');
			empty($data['gdesc']) && message('请填写商品简介');
//			empty($data['gubtime']) && message('请填写商品上架时间');
			$data['gdesc'] = htmlspecialchars_decode($data['gdesc']);
			$data['gubtime'] = strtotime($data['gubtime']);	
			$data['endtime'] = $_GPC['endtime'];		
            if(empty($goods)){
				$data['uniacid'] = $weid;
				$data['createtime'] = TIMESTAMP;
				$ret = pdo_insert('tg_goods', $data);
				if (!empty($ret)) {
					$id = pdo_insertid();
				}
				if($images){
	                foreach ($images as $key => $value){
	                    $data1 = array(
	                        'thumb' => $images[$key], 
	                        'g_id' => $id
	                        );
	                    pdo_insert('tg_goods_atlas',$data1);
	                }
	            }
			} else {
				if($images){
					pdo_delete('tg_goods_atlas',  array('g_id' => $id));
				    foreach ($images as $key => $value){
				        $data2 = array(
				            'thumb' => $images[$key], 
				            'g_id' => $id
				            );
				        pdo_insert('tg_goods_atlas',$data2);
				    }
				}
				$ret = pdo_update('tg_goods', $data, array('id'=>$id));
			}
			message('商品信息保存成功', $this->createWebUrl('goods'), 'success');
			// if (!empty($ret)) {
				
			// } else {
			// 	message('商品信息保存失败');
			// }
		}
		include $this->template('goodsadd');
	}
	
	if($op == 'delete') {
		$id = intval($_GPC['id']); //要删除的商品的id
		if(empty($id)){
			message('未找到指定商品分类');
		}
		$result = pdo_delete('tg_goods', array('id'=>$id, 'uniacid'=>$weid));
		if(intval($result) == 1){
			message('删除商品成功.', $this->createWebUrl('goods'), 'success');
		} else {
			message('删除商品失败.');
		}
	}
?>