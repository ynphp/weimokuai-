<?php
global $_W, $_GPC;
$this -> backlists();
//$this->myconstruct();
load() -> func('tpl');
checklogin();
$this -> checkmode();
$gettime = $this -> module['config']['checkgettime'];//自动签收时间
$weid = $_W['uniacid'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$merchants = pdo_fetchall("SELECT * FROM " . tablename('tg_merchant') . " WHERE uniacid = '{$_W['uniacid']}'  ORDER BY id DESC");
$allgoods = pdo_fetchall("select gname,id from".tablename('tg_goods')."where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
if ($operation == 'display') {
	//更新团状态
	$this->updategourp();
	//更新团状态
	$allnogettime = pdo_fetchall("select * from".tablename('tg_order')."where uniacid='{$_W['uniacid']}' and status=2 and is_hexiao=1 and issettlement=0");
	if(empty($gettime)){
		$gettime = 5;
	}
	$now = time();
//	echo "<pre>";print_r($allnogettime);exit;
	foreach($allnogettime as $key =>$value){
		if($value['merchantid']){
			$shouldgettime = $value['sendtime']+$gettime*24*3600;
			if($shouldgettime<$now){
				$res=pdo_update('tg_order',array('issettlement'=>1),array('id'=>$value['id']));
				if($res){
						$this->updateaccount($value['price'],$value['merchantid']);
				}
			}
		}
		
	}
			
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = "  uniacid = :weid";
	$paras = array(':weid' => $_W['uniacid']);

	$status = $_GPC['status'];
	$transid = $_GPC['transid'];
	$pay_type = $_GPC['pay_type'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= " AND  createtime >= :starttime AND  createtime <= :endtime ";
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if(trim($_GPC['goodsid'])!=''){
			$condition .= " and g_id like '%{$_GPC['goodsid']}%' ";
		}
	if(trim($_GPC['goodsid2'])!=''){
		$condition .= " and g_id like '%{$_GPC['goodsid2']}%' ";
	}
	if (!empty($_GPC['merchantid'])) {
		$condition .= " AND  merchantid={$_GPC['merchantid']} ";
	}
	if (!empty($_GPC['transid'])) {

		$condition .= " AND  transid =  '{$_GPC['transid']}'";
	}
	if (!empty($_GPC['pay_type'])) {

		$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
	} elseif ($_GPC['pay_type'] === '0') {
		$condition .= " AND  pay_type = '{$_GPC['pay_type']}'";
	}
	if (!empty($_GPC['keyword'])) {
		$condition .= " AND  orderno LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['hexiaoma'])) {
		$condition .= " AND  hexiaoma LIKE '%{$_GPC['hexiaoma']}%'";
	}
	if (!empty($_GPC['member'])) {
		$condition .= " AND (addname LIKE '%{$_GPC['member']}%' or mobile LIKE '%{$_GPC['member']}%')";
	}
	if ($status != '') {
		$condition .= " AND  status = '" . intval($status) . "'";
	}
	$sql = "select  * from " . tablename('tg_order') . " where $condition and mobile<>'虚拟' and is_hexiao<>0  ORDER BY createtime DESC " . "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$list = pdo_fetchall($sql, $paras);
	$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'info', 'name' => '余额支付'), '2' => array('css' => 'success', 'name' => '在线支付'), '3' => array('css' => 'warning', 'name' => '货到付款'));
	$orderstatus = array('0' => array('css' => 'default', 'name' => '待付款'), '1' => array('css' => 'info', 'name' => '已付款'), '2' => array('css' => 'warning', 'name' => '待消费'), '3' => array('css' => 'success', 'name' => '已消费'), '4' => array('css' => 'success', 'name' => '已完成'), '5' => array('css' => 'success', 'name' => '已取消'), '6' => array('css' => 'danger', 'name' => '待退款'), '7' => array('css' => 'success', 'name' => '已退款'));
	foreach ($list as &$value) {
		$options  = pdo_fetch("select title,productprice,marketprice,stock from " . tablename("tg_goods_option") . " where id=:id limit 1", array(":id" => $value['optionid']));
		$value['optionname'] = $options['title'];
		$s = $value['status'];
		$value['statuscss'] = $orderstatus[$value['status']]['css'];
		$value['status'] = $orderstatus[$value['status']]['name'];
		$value['css'] = $paytype[$value['pay_type']]['css'];
		if ($value['pay_type'] == 2) {
			if (empty($value['transid'])) {
				$value['paytype'] = '微信支付';
			} else {
				$value['paytype'] = '微信支付';
			}
		} else {
			$value['paytype'] = $paytype[$value['pay_type']]['name'];
		}
		$goodsss = pdo_fetch("select * from" . tablename('tg_goods') . "where id = '{$value['g_id']}'");
		$value['gid'] = $goodsss['id'];
		$value['gname'] = $goodsss['gname'];
		$value['gimg'] = $goodsss['gimg'];
		$value['merchant'] = pdo_fetch("select name from" . tablename('tg_merchant') . "where id = '{$value['merchantid']}' and uniacid={$_W['uniacid']}");
	}
	$all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and mobile<>'虚拟' and is_hexiao<>0 ");
	$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=0 and mobile<>'虚拟' and is_hexiao<>0");
	$status1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=1 and mobile<>'虚拟' and is_hexiao<>0");
	$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and mobile<>'虚拟' and is_hexiao<>0");
	$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=3 and mobile<>'虚拟' and is_hexiao<>0");
	$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=4 and mobile<>'虚拟' and is_hexiao<>0");
	$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=5 and mobile<>'虚拟' and is_hexiao<>0");
	$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=6 and mobile<>'虚拟' and is_hexiao<>0");
	$status7 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=7 and mobile<>'虚拟' and is_hexiao<>0");
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE $condition and mobile<>'虚拟' and is_hexiao<>0", $paras);
	$pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$all = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}'  and mobile<>'虚拟' and is_hexiao<>0");
	$status0 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=0 and mobile<>'虚拟' and is_hexiao<>0");
	$status1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=1 and mobile<>'虚拟' and is_hexiao<>0");
	$status2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=2 and mobile<>'虚拟' and is_hexiao<>0");
	$status3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=3 and mobile<>'虚拟' and is_hexiao<>0");
	$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=4 and mobile<>'虚拟' and is_hexiao<>0");
	$status5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=5 and mobile<>'虚拟' and is_hexiao<>0");
	$status6 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=6 and mobile<>'虚拟' and is_hexiao<>0");
	$status7 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE uniacid='{$_W['uniacid']}' and status=7 and mobile<>'虚拟' and is_hexiao<>0");
	$item = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id = :id", array(':id' => $id));
	if ($item['status'] == 7) {
		$refund_record = pdo_fetch("SELECT * FROM " . tablename('tg_refund_record') . " WHERE orderid = :id", array(':id' => $id));
	}
	if (empty($item)) {
		message("抱歉，订单不存在!", referer(), "error");
	}
	if ($item['veropenid']){
		if($item['veropenid'] == 'houtai'){
			$item['verstore'] = '后台核销';
			$item['vername'] = '后台核销';
		}else{
			$list = pdo_fetch("select * from" . tablename('tg_saler') . "where uniacid='{$_W['uniacid']}' and openid = '{$item['veropenid']}'");
			if($list['storeid']){
				$storeid_arr = explode(',', $list['storeid']);
				$storename   = '';
				foreach ($storeid_arr as $k => $v) {
					if ($v) {
						$store = pdo_fetch("select * from" . tablename('tg_store') . "where id='{$v}'");
						$storename .= $store['storename'] . "/";
					}
				}
				$storename               = substr($storename, 0, strlen($storename) - 1);
			}else{
				$storename = '全局核销员';
			}
			$item['verstore'] = $storename;
			$item['vername'] = $list['nickname'];
		}
	}
	
	if (checksubmit('getgoods')) {
		if ($_GPC['recvname']=='' || $_GPC['recvmobile']=='' || $_GPC['recvaddress']=='' || $_GPC['addresstype']=='') {
			message('请输入完整信息！');
		} else {
			pdo_update('tg_order', array('addname' => $_GPC['recvname'], 'mobile' => $_GPC['recvmobile'], 'address' => $_GPC['recvaddress'],'addresstype'=>$_GPC['addresstype']), array('id' => $id));
		}
		message('修改成功！', referer(), 'success');
	}
	if($_GPC['check']=='check'){
		$id = $_GPC['id'];
		
		$res=pdo_update('tg_order', array('status' => 4, 'is_hexiao'=>2,'issettlement'=>1,'veropenid' => 'houtai','sendtime'=>TIMESTAMP,'gettime'=>TIMESTAMP), array('id' => $id));
		if($res){
				$this->updateaccount($item['price'],$item['merchantid']);
		}
		message('确认核销操作成功！', referer(), 'success');
	}
	if (checksubmit('confirmsend')) {
		$res=pdo_update('tg_order', array('status' => 4, 'is_hexiao'=>2,'issettlement'=>1,'veropenid' => 'houtai','sendtime'=>TIMESTAMP,'gettime'=>TIMESTAMP), array('id' => $id));
		if($res){
				$this->updateaccount($item['price'],$item['merchantid']);
		}
		message('确认核销操作成功！', referer(), 'success');
	}
	if (checksubmit('cancelsend')) {
		$res=pdo_update('tg_order', array('status' => 2,'issettlement'=>0), array('id' => $id));
		if($res){
				$this->updateaccount(0-$item['price'],$item['merchantid']);
		}
		message('取消核销操作成功！', referer(), 'success');
	}
	if (checksubmit('refund')) {
		$refund_id = $_GPC['refund_id'];
		//页面获取的退款订单号
		$refund_ids = pdo_fetch("select * from" . tablename('tg_order') . "where id='{$refund_id}'");
		$res=$this->refund($refund_ids['orderno'],'',2);
		if($res=='success'){
			message('退款成功了！', referer(), 'success');
		} else {
			message('退款失败，服务器正忙，请稍等等！', referer(), 'fail');
		}
	}
	if (checksubmit('confrimpay')) {
		pdo_update('tg_order', array('status' => 1, 'pay_type' => 2, 'remark' => $_GPC['remark']), array('id' => $id));
		// //设置库存
		// $this->setOrderStock($id);
		message('确认订单付款操作成功！', referer(), 'success');
	}
	$item['user'] = pdo_fetch("SELECT * FROM " . tablename('tg_address') . " WHERE id = {$item['addressid']}");
	$goods = pdo_fetchall("select * from" . tablename('tg_goods') . "WHERE id={$item['g_id']}");
	$item['goods'] = $goods;
} elseif ($operation == 'delete') {
	/*订单删除*/
	$orderid = intval($_GPC['id']);
	if (pdo_delete('tg_order', array('id' => $orderid))) {
		message('订单删除成功', $this -> createWebUrl('order', array('op' => 'display')), 'success');
	} else {
		message('订单不存在或已被删除', $this -> createWebUrl('order', array('op' => 'display')), 'error');
	}
}elseif($operation == 'tuikuan') {
	/*处理退款*/
	$checkboxs = $_GPC['checkbox'];
	
	$success_num =0;
	$fail_num =0;
	foreach($checkboxs as$k=>$value){
		$refund_ids = pdo_fetch("select * from".tablename('tg_order')."where id='{$value}'");
		$res = $this->refund($refund_ids['orderno'],'',2);
		if($res == 'success'){
			$success_num+=1;
		}else{
			$fail_num+=1;
		}
	}
	message('退款操作成功，成功！'.$success_num.'人,失败'.$fail_num.'人', referer(), 'success');
/*处理退款*/
} elseif ($operation == 'refundall') {}elseif($operation == 'output'){
	
	$status = $_GPC['status'];
	$keyword = $_GPC['keyword'];
	$member = $_GPC['member'];
	$time = $_GPC['time'];
	$transid = $_GPC['transid'];
	$paytype = $_GPC['pay_type'];
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']);
	$condition = " uniacid='{$_W['uniacid']}' and is_hexiao <>0 ";
	if(trim($_GPC['goodsid'])!=''){
			$condition .= " and g_id like '%{$_GPC['goodsid']}%' ";
		}
	if(trim($_GPC['goodsid2'])!=''){
		$condition .= " and g_id like '%{$_GPC['goodsid2']}%' ";
	}
	if (!empty($_GPC['merchantid'])) {
		$condition .= " AND  merchantid={$_GPC['merchantid']} ";
	}
	if ($status != '') {
		$condition .= " AND status= '{$status}' ";
	}
	if ($keyword != '') {
		$condition .= " AND g_id = '{$keyword}'";
	}
	if (!empty($member)) {
		$condition .= " AND (addname LIKE '%{$member}%' or mobile LIKE '%{$member}%')";
	}
	if (!empty($time)) {
		$condition .= " AND  createtime >= $starttime AND  createtime <= $endtime  ";
	}
	if (!empty($transid)) {

		$condition .= " AND  transid =  '{$transid}'";
	}
	if (!empty($paytype)) {
		$condition .= " AND  pay_type = '{$paytype}'";
	}
	$orders = pdo_fetchall("select * from" . tablename('tg_order') . "where $condition  and mobile<>'虚拟'");
//	echo "<pre>";print_r($condition);exit;
	switch($status){
		case NULL: 
		$str = '全部订单_' . time();
		break;
		case 1: 
		$str = '已支付订单_' . time();
		break;
		case 2: 
		$str = '待消费订单' . time();
		break;
		case 3: 
		$str = '已完成订单' . time();
		break;
		case 4:
		$str = '已完成订单' . time();
		break;
		case 5:
		$str = '已取消订单' . time();
		break;
		case 6: 
		$str = '待退款订单' . time();
		break;
		case 7:
		$str = '已退款订单' . time();
		break;
		default:
		$str = '待支付订单' . time();break;
	}
	

	/* 输入到CSV文件 */
	$html = "\xEF\xBB\xBF";
	/* 输出表头 */
	$filter = array('aa' => '订单编号', 'bb' => '姓名', 'cc' => '电话', 'dd' => '总价(元)', 'ee' => '状态', 'ff' => '下单时间', 'gg' => '商品名称', 'hh' => '收货地址', 'ii' => '微信订单号', 'jj' => '快递单号', 'kk' => '快递名称','ll'=>'地址类型','mm'=>'商品规格');
	foreach ($filter as $key => $title) {
		$html .= $title . "\t,";
	}
	$html .= "\n";
	foreach ($orders as $k => $v) {
		if ($v['status'] == '0') {
			$thisstatus = '未支付';
		}
		if ($v['status'] == '1') {
			$thisstatus = '已支付';
		}
		if ($v['status'] == '2') {
			$thisstatus = '待消费';
		}
		if ($v['status'] == '3') {
			$thisstatus = '已完成';
		}
		if ($v['status'] == '4') {
			$thisstatus = '已完成';
		}
		if ($v['status'] == '5') {
			$thisstatus = '已取消';
		}
		if ($v['status'] == '6') {
			$thisstatus = '待退款';
		}
		if ($v['status'] == '7') {
			$thisstatus = '已退款';
		}
		if ($v['status'] == '') {
			$thisstatus = '全部订单';
		}
		$thistatus = '待发货';
		$goods = pdo_fetch("select * from" . tablename('tg_goods') . "where id = '{$v['g_id']}' and uniacid='{$_W['uniacid']}'");
		$time = date('Y-m-d H:i:s', $v['createtime']);
		$options  = pdo_fetch("select title,productprice,marketprice,stock from " . tablename("tg_goods_option") . " where id=:id limit 1", array(":id" => $v['optionid']));
		if(empty($options['title'])){
			$options['title'] = '不限';
		}
		$orders[$k]['aa'] = $v['orderno'];
		$orders[$k]['bb'] = $v['addname'];
		$orders[$k]['cc'] = $v['mobile'];
		$orders[$k]['dd'] = $v['price'];
		$orders[$k]['ee'] = $thisstatus;
		$orders[$k]['ff'] = $time;
		$orders[$k]['gg'] = $goods['gname'];
		$orders[$k]['ii'] = $v['transid'];
		$orders[$k]['mm'] = $options['title'];
		foreach ($filter as $key => $title) {
			$html .= $orders[$k][$key] . "\t,";
		}
		$html .= "\n";
	}
	/* 输出CSV文件 */
	header("Content-type:text/csv");
	header("Content-Disposition:attachment; filename={$str}.csv");
	echo $html;
	exit();
}
include $this -> template('web/check');
?>