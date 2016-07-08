<?php
/**
 * 拼团模块微站定义
 *
 * @author 甜筒君
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Feng_fightgroupsModuleSite extends WeModuleSite {
//会员信息提取
	public function __construct(){
		global $_W;
		load()->model('mc');
		$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		if (empty($profile)) {
			$userinfo = mc_oauth_userinfo();
			if (!empty($userinfo['avatar'])) {
				$data = array(
					'uniacid' => $_W['uniacid'],
					'from_user' => $userinfo['openid'],
					'nickname' => $userinfo['nickname'],
					'avatar' => $userinfo['avatar']
				);
				$member = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$userinfo['openid']}'");
				if (empty($member['id'])) {
					pdo_insert('tg_member', $data);
				}else{
					pdo_update('tg_member', $data, array('id' =>$member['id']));
				}
			}
		}
	}
	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为微信端页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	//微信端首页
	
	//微信端填写收货地址页面
	public function doMobileCreateAdd() {
		global $_GPC, $_W;
        $groupnum=$_GPC['groupnum'];
        $g_id = intval($_GPC['g_id']);
        $tuan_id = intval($_GPC['tuan_id']);
       
        $all = array(
            'g_id' =>$g_id,
            'groupnum' =>$groupnum
            );
    	$operation = $_GPC['op'];
        $id=$_GPC['id'];
        $weid = $_W['uniacid'];
        $openid = $_W['openid'];
    	if ($operation == 'display') {
            if($id){
                $addres = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
                if(!empty($all)){
                    $addresschange = 1;
                } 
            }  		
        }elseif($operation == 'conf'){
            if(!empty($all)){
                   $con = 1;
                } 
        }elseif ($operation == 'post') { 
                if(!empty($id)){
                    $status = pdo_fetch("SELECT * FROM " . tablename('tg_address')."where id={$id}");
                    $data=array(
                        'openid' => $openid,
                        'uniacid'=>$weid,
                        'cname'=>$_GPC['lxr_val'],
                        'tel'=>$_GPC['mobile_val'],
                        'province'=>$_GPC['province_val'],
                        'city'=>$_GPC['city_val'],
                        'county'=>$_GPC['area_val'],
                        'detailed_address'=>$_GPC['address_val'],
                        'status'=>$status['status'],
                        'addtime'=>time()
                    );
                    if(pdo_update('tg_address',$data,array('id' => $id))){ 
                    echo 1;
                    exit;
                    }else{   
                        echo 0;
                        exit;

                    }
                }else{
                     $data1=array(
                    'openid' => $openid,
                    'uniacid'=>$weid,
                    'cname'=>$_GPC['lxr_val'],
                    'tel'=>$_GPC['mobile_val'],
                    'province'=>$_GPC['province_val'],
                    'city'=>$_GPC['city_val'],
                    'county'=>$_GPC['area_val'],
                    'detailed_address'=>$_GPC['address_val'],
                    'status'=>'1',
                    'addtime'=>time()
                );
                $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
                pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
                     if(pdo_insert('tg_address',$data1)){
                   
                    echo 1;
                    exit;
                    }else{                      
                        echo 0;
                        exit;
                    }                 
                }
               
        }elseif($operation == 'deletes'){

        if($id){
                    if(pdo_delete('tg_address',array('id' => $id )))
                    {
                             echo 1;
                             exit;
                    }else{
                            echo 0;
                            exit;
                    }        
                }else{
                echo 2;
                exit;
             }
         
        }elseif($operation == 'moren'){    
            if(!empty($id))
            {
                $moren =  pdo_fetch("SELECT * FROM".tablename('tg_address')."where status=1 and openid='$openid'");
                pdo_update('tg_address',array('status' => 0),array('id' => $moren['id']));
                    if(pdo_update('tg_address',array('status' =>1),array('id' => $id))){
                        echo 1;
                        exit;
                    }else{
                        echo 0;
                        exit;
                    }

            }else{
                    echo 2;
                    exit; 
                }

        }
        
        include $this->template('createadd');
	}

	public function doMobileMyGroup() {
	    global $_W, $_GPC;
		$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and openid='{$_W['openid']}' and is_tuan = 1 and status = 1 order by ptime desc");
	    foreach ($orders as $key => $order) {

			$goods = pdo_fetch("SELECT * FROM ".tablename('tg_goods')."WHERE id = {$order['g_id']}");

			$orders[$key]['groupnum'] = $goods['groupnum'];
			$orders[$key]['gprice'] = $goods['gprice'];
			$orders[$key]['gid'] = $goods['id'];
			$orders[$key]['gname'] = $goods['gname'];
			$orders[$key]['gimg'] = $goods['gimg'];
	        $sql2 = "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1";
	        $params2 = array(':tuan_id'=>$order['tuan_id']);
	        $alltuan = pdo_fetchall($sql2, $params2);
	        $item = array();
	        foreach ($alltuan as $num => $all) {
	        	$item[$num] = $all['id'];
	        }
	        $orders[$key]['itemnum'] = count($item);

	        $sql3="SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = :tuan_id and status = 1 and tuan_first =:tuan_first";
	        $params3  = array(':tuan_id' => $order['tuan_id'],':tuan_first'=>1);
	        $tuan_first_order = pdo_fetch($sql3,$params3);
	        $hours=$tuan_first_order['endtime'];
	        $time = time();
	        $date = date('Y-m-d H:i:s',$tuan_first_order['ptime']);
	        $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
	        $date1 = date('Y-m-d H:i:s',$time);
	        $orders[$key]['lasttime'] = strtotime($endtime)-strtotime($date1);
	         // message('goods='. $orders[$key]['lasttime']);exit();
		}
		include $this->template('mygroup');

	}
	public function doMobileIndex() {
		global $_W, $_GPC;
		$pindex = max(1, intval($_GPC['page'])); //当前页码
		$psize = 2;	//设置分页大小                                                               
		$goodses = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$_W['uniacid']}'  ORDER BY id desc LIMIT ".(1-1)* $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_goods') . "WHERE uniacid = '{$_W['uniacid']}'"); //记录总数
		$pager = pagination($total, $pindex, $psize);

	include $this->template('index');
	}

	//团购
	public function doMobileGroup() {
		global $_W, $_GPC;
	  	$url=$_W['siteurl'];
	  	$tuan_id = intval($_GPC['tuan_id']);

	  	if(!empty($tuan_id)){
		  	$profile = pdo_fetch("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}' and from_user = '{$_W['openid']}'");
		  	$profileall = pdo_fetchall("SELECT * FROM " . tablename('tg_member') . " WHERE uniacid ='{$_W['uniacid']}'");
		    //取得该团所有订单
		    $orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE uniacid ='{$_W['uniacid']}' and tuan_id = {$tuan_id} and status = 1 order by id asc");
		    //取一个订单$order
		    $order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE  tuan_id = {$tuan_id} and status = 1");
		   //若没有参团则$myorder为空
		    $myorder = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status = 1");
		  	$goods = pdo_fetch("SELECT * FROM".tablename('tg_goods')."WHERE id = {$order['g_id']}");
		    //该团购已有订单数count($item),已付款的订单
		    $sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1 and pay_type <> 0";
		    $params= array(':tuan_id'=>$order['tuan_id']);
		    $alltuan = pdo_fetchall($sql, $params);
		    $item = array();
		    foreach ($alltuan as $num => $all) {
		    $item[$num] = $all['id'];
		    }
		     /*$n ：剩余人数，$nn 该团只有一人*/
		    $n = intval($goods['groupnum']) - count($item);
		    $nn = intval($goods['groupnum'])-1;
		    $arr = array();
		    for ($i=0; $i <$n ; $i++) { 
		       $arr[$i]=0;
		    }
		    /*团是否过期*/
		    //团长订单
		    $tuan_first_order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id} and tuan_first = 1");
		    $hours=$tuan_first_order['endtime'];
		    $time = time();
		    $date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
		    $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
		  
		    $date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
		    $lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
		    $lasttime = $tuan_first_order['endtime'];
	  	}
 		 include $this->template('group');
	}

	//微信端商品详情页
	public function doMobileGoodDetails() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端商品详情页ajax
	public function doMobileIndexAjax() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端团购流程详情页
	public function doMobileRules() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端填订单信息确认页面
	public function doMobileOrderConfirm() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单详情页面
	public function doMobileOrderDetails() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端订单页面
	public function doMobilemyOrder() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端取消订单
	public function doMobileCancelMyOrder() {
		$this -> __mobile(__FUNCTION__);
	}
	
	//微信端确认收货
	public function doMobileConfirMreceipt() {
		$this -> __mobile(__FUNCTION__);
	}
	//微信端收货地址管理页面
	public function doMobileAddManage() {
		$this -> __mobile(__FUNCTION__);
	}

	//微信端个人中心页面
	public function doMobilePerson() {
		$this -> __mobile(__FUNCTION__);
	}

	public function doMobilePay() {
		$this -> __mobile(__FUNCTION__);
	}
	/*＝＝＝＝＝＝＝＝＝＝＝＝＝＝以下为后台页面管理＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
	//后台商品管理页面
	public function doWebGoods() {
		$this -> __web(__FUNCTION__);
	}
	
	//后台订单管理页面
	public function doWebOrder() {
		global $_W,$_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];
			$is_tuan = $_GPC['is_tuan'];
			$condition = " o.uniacid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}

			if (!empty($_GPC['pay_type'])) {
				$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
			} elseif ($_GPC['pay_type'] === '0') {
				$condition .= " AND o.pay_type = '{$_GPC['pay_type']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.orderno LIKE '%{$_GPC['keyword']}%'";
			}
			 if (!empty($_GPC['member'])) {
				$condition .= " AND (a.cname LIKE '%{$_GPC['member']}%' or a.tel LIKE '%{$_GPC['member']}%')";
			 }
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			if ($is_tuan != '') {
				$pp = 1;
				$condition .= " AND o.is_tuan = 1";
			}
			$sql = "select o.* , a.cname,a.tel from ".tablename('tg_order')." o"
					." left join ".tablename('tg_address')." a on o.addressid = a.id "
					. " where $condition ORDER BY o.status DESC, o.createtime DESC "
					. "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);
			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'info', 'name' => '余额支付'),
					'2' => array('css' => 'success', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款')
			);
			$orderstatus = array (
					'9' => array('css' => 'default', 'name' => '已取消'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as &$value) {
				$s = $value['status'];
				$value['statuscss'] = $orderstatus[$value['status']]['css'];
				$value['status'] = $orderstatus[$value['status']]['name'];
				$value['css'] = $paytype[$value['pay_type']]['css'];
				if ($value['pay_type'] == 2) {
					if (empty($value['transid'])) {
						$value['paytype'] = '支付宝支付';
					} else {
						$value['paytype'] = '微信支付';
					}
				} else {
					$value['paytype'] = $paytype[$value['pay_type']]['name'];
				}
			}

			$total = pdo_fetchcolumn(
						'SELECT COUNT(*) FROM ' . tablename('tg_order') . " o "
						." left join ".tablename('tg_address')." a on o.addressid = a.id "
						." WHERE $condition", $paras);
			$pager = pagination($total, $pindex, $psize);
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$is_tuan = intval($_GPC['is_tuan']);
			$item = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id = :id", array(':id' => $id));
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
					message('请输入快递单号！');
				}
				
				pdo_update(
					'tg_order',
					array(
						'status' => 2,						
						'express' => $_GPC['express'],
						
						'expresssn' => $_GPC['expresssn'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('shopping_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'tg_order',
					array(
						'status' => 1
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('tg_order', array('status' => 3), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('refund')) {
				// pdo_update('tg_order', array('status' => 3), array('id' => $id));
				message('退款成功！', referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('tg_order', array('status' => 1), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('tg_order', array('status' => 0), array('id' => $id));
				//设置库存
				$this->setOrderStock($id, false);
				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('shopping_order', array('status' => 1, 'pay_type' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id);
				message('确认订单付款操作成功！', referer(), 'success');

			}

			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('shopping_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				pdo_update('shopping_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}

			if (checksubmit('open')) {
				pdo_update('tg_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}
			// $dispatch = pdo_fetch("SELECT * FROM " . tablename('shopping_dispatch') . " WHERE id = :id", array(':id' => $item['dispatch']));
			// if (!empty($dispatch) && !empty($dispatch['express'])) {
			// 	$express = pdo_fetch("select * from " . tablename('shopping_express') . " WHERE id=:id limit 1", array(":id" => $dispatch['express']));
			// }
			$item['user'] = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " WHERE id = {$item['addressid']}");
			$goods = pdo_fetchall("select * from" . tablename('tg_goods') ."WHERE id={$item['g_id']}");
			$item['goods'] = $goods;
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			$tuan_id = intval($_GPC['tuan_id']);
			if(!empty($tuan_id)){
	            if(pdo_delete('tg_order', array('tuan_id' => $tuan_id))){
	             	message('团订单删除成功', $this->createWebUrl('order', array('op' => 'tuan')), 'success');
	            }	
			}
			if (pdo_delete('tg_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
			} else {
				message('订单不存在或已被删除', $this->createWebUrl('order', array('op' => 'display')), 'error');
			}
		} elseif ($operation == 'tuan') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$is_tuan = $_GPC['is_tuan'];
			$condition = "uniacid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND tuan_id LIKE '%{$_GPC['keyword']}%'";
			}
			if ($is_tuan != '') {
				$condition .= " AND is_tuan = 1";
			}
			$sql = "select DISTINCT tuan_id from".tablename('tg_order').
			"where $condition order by createtime desc ". "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$tuan_id = pdo_fetchall($sql,$paras);
			foreach ($tuan_id as $key => $tuan) {
				$alltuan = pdo_fetchall("select * from".tablename('tg_order')."where tuan_id={$tuan['tuan_id']}");
				$ite = array();
            	foreach ($alltuan as $num => $all) {
              		$ite[$num] = $all['id'];
              		$goods = pdo_fetchall("select * from".tablename('tg_goods')."where id = {$all['g_id']}");
              	}
              	$tuan_id[$key]['itemnum'] = count($ite);
             	$tuan_id[$key]['groupnum'] = $goods['groupnum'];

              	$tuan_first_order = pdo_fetch("SELECT * FROM".tablename('tg_order')."where tuan_id={$tuan['tuan_id']} and tuan_first = 1");
              	$hours=$tuan_first_order['endtime'];
              	$time = time();
              	$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
              	$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
              	$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
              	$lasttime = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
              	$tuan_id[$key]['lasttime'] = $lasttime;
			}
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order')." WHERE $condition ", $paras);
			$pager = pagination($total, $pindex, 10);			
		} elseif ($operation == 'tuan_detail'){
			$num = intval($_GPC['num']);
			$tuan_id = intval($_GPC['tuan_id']);//指定团的id
			$is_tuan = intval($_GPC['is_tuan']);
			$orders = pdo_fetchall("SELECT * FROM " . tablename('tg_order') . " WHERE tuan_id = {$tuan_id}");
			foreach ($orders as $key => $order) {
		        $address = pdo_fetch("SELECT * FROM".tablename('tg_address')."where id={$order['addressid']}");
		        $orders[$key]['cname'] = $address['cname'];
		        $orders[$key]['tel'] = $address['tel'];
		        $orders[$key]['province'] = $address['province'];
		        $orders[$key]['city'] = $address['city'];
		        $orders[$key]['county'] = $address['county'];
		        $orders[$key]['detailed_address'] = $address['detailed_address'];
		        $goods = pdo_fetch("select * from".tablename('tg_goods')."where id={$order['g_id']}");	
			}
			$goodsid  = array();
			foreach ($orders as $key => $value) {
				$goodsid['id'] = $value['g_id'];
			}
			$goods2 = pdo_fetch("SELECT * FROM " . tablename('tg_goods') . " WHERE id = {$goodsid['id']}");
			if (empty($orders)) {
				message("抱歉，该团购不存在!", referer(), "error");
			}
			foreach ($orders as $key => $value) {
				$it['status'] = $value['status'];
			}
			//是否过期
			$sql2= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and tuan_first = :tuan_first";
			$params2 = array(':tuan_id'=>$tuan_id,':tuan_first'=>1);
			$tuan_first_order = pdo_fetch($sql2, $params2);
			$hours=$tuan_first_order['endtime'];
			$time = time();
			$date = date('Y-m-d H:i:s',$tuan_first_order['createtime']); //团长开团时间
			$endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));
			
			$date1 = date('Y-m-d H:i:s',$time); /*当前时间*/
			$lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）
			//确认发货
			if (checksubmit('confirmsend')) {
				pdo_update(
					'tg_order',
					array(
						'status' => 2					
					),
					array('tuan_id' => $tuan_id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			//取消发货
			if (checksubmit('cancelsend')) {
				
				pdo_update(
					'tg_order',
					array(
						'status' => 1
					),
					array('tuan_id' => $tuan_id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			//确认完成订单
			if (checksubmit('finish')) {
				pdo_update('tg_order', array('status' => 3), array('tuan_id' => $tuan_id));
				message('订单操作成功！', referer(), 'success');
			}
			//取消完成订单（状态为已支付）
			if (checksubmit('cancel')) {
				pdo_update('tg_order', array('status' => 1), array('tuan_id' => $tuan_id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			//取消支付
			if (checksubmit('cancelpay')) {
				pdo_update('tg_order', array('status' => 0), array('tuan_id' => $tuan_id));
				message('取消团订单付款操作成功！', referer(), 'success');
			}
			//确认支付
			if (checksubmit('confrimpay')) {
				pdo_update('tg_order', array('status' => 1, 'pay_type' => 2),  array('tuan_id' => $tuan_id));
				message('团订单付款操作成功！', referer(), 'success');
			}
		}
		include $this->template('order');
	}
	//后台会员管理页面
	public function doWebMember() {
		$this -> __web(__FUNCTION__);
	}
	public function __web($f_name){
		global $_W,$_GPC;
		checklogin();
		$weid = $_W['uniacid'];
		load()->func('tpl');
		include_once  'web/'.strtolower(substr($f_name,5)).'.php';
	}
	
	public function __mobile($f_name){
		global $_W,$_GPC;
		/*checkauth();*/
		$weid = $_W['uniacid'];
		$share_data = $this->module['config'];
		include_once  'mobile/'.strtolower(substr($f_name,8)).'.php';
	}
    public function payResult($params) {
		global $_W;
		$fee = intval($params['fee']);	
		$data = array('status' => $params['result'] == 'success' ? 1 : 0);
		$paytype = array('credit' => 1, 'wechat' => 2, 'alipay' => 2, 'delivery' => 3);
		$data['pay_type'] = $paytype[$params['type']];
		if ($params['type'] == 'wechat') {
			$data['transid'] = $params['tag']['transaction_id'];
		}

		$sql = 'SELECT `g_id` FROM ' . tablename('tg_order') . ' WHERE `orderno` = :orderid';
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $params['tid']));
		$sql = 'SELECT * FROM ' . tablename('tg_goods') . ' WHERE `id` = :id';
		$goodsInfo = pdo_fetch($sql, array(':id' => $goodsId));
		// 更改库存
		if (!empty($goodsInfo['gnum'])) {
			pdo_update('tg_goods', array('gnum' => $goodsInfo['gnum'] - 1), array('id' => $goodsId));
		}
		// //货到付款
		if ($params['type'] == 'delivery') {
			$data['status'] = 1;
			$data['starttime'] = TIMESTAMP;
			$data['ptime'] = TIMESTAMP;
		}
		if($params['result'] == 'success'){
			$data['ptime'] = TIMESTAMP;
			$data['starttime'] = TIMESTAMP;
		}	
		pdo_update('tg_order', $data, array('orderno' => $params['tid']));
		$tuan_id = pdo_fetch("select * from".tablename('tg_order') . "where orderno = '{$params['tid']}'");
		
		if ($params['from'] == 'return') {
			$setting = uni_setting($_W['uniacid'], array('creditbehaviors'));
			$credit = $setting['creditbehaviors']['currency'];
			if ($params['type'] == $credit) {
				if($tuan_id['is_tuan'] == 0){
					message('支付成功！', $this->createMobileUrl('orderdetails',array('id' => $params['tid'])), 'success');
				}else{
					message('支付成功！', $this->createMobileUrl('group',array('tuan_id' => $tuan_id['tuan_id'])), 'success');
				}
			} else {
				if($tuan_id['is_tuan'] == 0){
					message('支付成功！', '../../app/' . $this->createMobileUrl('orderdetails',array('id' => $params['tid'])), 'success');
			    }else{
			   		message('支付成功！', '../../app/' . $this->createMobileUrl('group',array('tuan_id' => $tuan_id['tuan_id'])), 'success');
			   	}
			}
		}

	}

}
