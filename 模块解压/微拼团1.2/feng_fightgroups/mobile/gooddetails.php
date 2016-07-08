<?php
		$id = intval($_GPC['id']);
		$tuan_id = intval($_GPC['tuan_id']);
		if(!empty($id)){
			//商品
			$sql = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:id and uniacid=:uniacid';
			$params = array(':id'=>$id, ':uniacid'=>$weid);
			$goods = pdo_fetch($sql, $params);
			//得到图集
			 $advs = pdo_fetchall("select * from " . tablename('tg_goods_atlas') . " where g_id='{$id}'");
                foreach ($advs as &$adv) {
                    if (substr($adv['link'], 0, 5) != 'http:') {
                        $adv['link'] = "http://" . $adv['link'];
                    }
                }
            unset($adv);
			if(empty($goods)){
				message('未找到指定的商品.', $this->createMobileUrl('index'));
			}
			// 查出该商品的所有团购订单的团购ID
			$sql1  = "select DISTINCT tuan_id from".tablename('tg_order')."where g_id=:g_id and is_tuan=:is_tuan and status = 1";
			$params1  = array(':g_id' => $id,':is_tuan'=>1);
			$tuan_ids = pdo_fetchall($sql1,$params1);
			
			//查询相同团ID的订单数
			foreach ($tuan_ids as $key => $value) {	
				$sql2 = "select count(*) from".tablename('tg_order')."where tuan_id=:tuan_id and is_tuan=:is_tuan and status = 1";
				$params2  = array(':tuan_id' => $value['tuan_id'],':is_tuan'=>1);
				$total = pdo_fetchcolumn($sql2,$params2);

				/*团是否过期*/
				//团长订单
				$tuan_first_order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = '{$value['tuan_id']}' and tuan_first = 1");
				$hours=$tuan_first_order['endtime'];
				$time = time();
				$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
				$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
				
				$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
				$lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数)
				if($total < $goods['groupnum'] && $lasttime2>0){
					$arr = $value['tuan_id'];
					$num = $goods['groupnum']-$total;
				}
				
			}
			if(!empty($arr)){
				$tuan_first_order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = '{$arr}' and tuan_first = 1");
				$address=pdo_fetch("SELECT * FROM " . tablename('tg_address') . " WHERE openid = '{$_W['openid']}' and status = 1");

				$sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1 and pay_type <> 0";
				$params= array(':tuan_id'=>$arr);
				/*所有团ID相同的订单*/
				$alltuan = pdo_fetchall($sql, $params);
				$item = array();
				foreach ($alltuan as $nu => $all) {
				$item[$nu] = $all['id'];
				}
				//头像
				$profileall = pdo_fetchall("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}'");
			}
		}
	include $this->template('gooddetails');
?>