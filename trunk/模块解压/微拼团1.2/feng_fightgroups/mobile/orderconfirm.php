<?php
    load()->func('communication');
	global $_W, $_GPC;
	$openid = $_W['openid'];
	$groupnum=intval($_GPC['groupnum']);//团购人数	
	
	$id = intval($_GPC['id']);//商品id
	$tuan_id = intval($_GPC['tuan_id']);
	$all = array(
		'groupnum' =>$groupnum, 
		'id'=> $id
		);
	if (!empty($id)) {
		$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = $id");
		//地址
		$adress = pdo_fetch("select * from ".tablename('tg_address')."where openid='$openid' and status=1");
      	if(!empty($groupnum)){
      		if($groupnum>1){
      			$price = $goods['gprice'];
      			$is_tuan=1;
      			if(empty($tuan_id)){
      				$tuan_first = 1;
      			}else{
      				$tuan_first = 0;
      			}

      		}else{
      			$price = $goods['oprice'];
      			$is_tuan=0;
      			$tuan_first = 0;
      		}
      	}
      	
      }
      // if(empty($tuan_id)){
      // 	$tuan_id = -1;
      // }
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'gnum' => 1,
			'openid' => $openid,
            'ptime' =>'',//支付成功时间
			'orderno' => date('md') . random(4, 1),
			'price' => $price,
			'status' => 0,//订单状态，-1取消状态，0普通状态，1为已付款，2为已发货，3为成功
			'addressid' => $adress['id'],
			'g_id' => $id,
			'tuan_id'=>$tuan_id,
			'is_tuan'=>$is_tuan,
			'tuan_first' => $tuan_first,
			'starttime'=>TIMESTAMP,
			'endtime'=>$goods['endtime'],
			'createtime' => TIMESTAMP
		);
			pdo_insert('tg_order', $data);
			$orderid = pdo_insertid();
			if(empty($tuan_id)){
				pdo_update('tg_order',array('tuan_id' => $orderid), array('id' => $orderid));
			}
			//引导关注
			if (!empty($share_data['url'])) {
				if (!empty($openid)) {
					$account = account_fetch($_W['acid']);//获取公众号信息
					$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$account['access_token']['token']."&openid=".$openid."&lang=zh_CN";
					$re = ihttp_get($url);//ihttp_get()封装的 http GET 请求方法
					if ($re['code'] == 200) {
						$content = json_decode($re['content'],true);
					}
				}
				if($content['subscribe'] == 1){
					header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));
				}else{
					die("<script>alert('请关注我们后,进入我的订单继续付款');location.href='".$share_data['url']."';</script>");
					// header("location: ".$share_data['url']);
				}
			}else{
				header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));
			}
			
			// header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));
		}

		
		
	include $this->template('confirm');