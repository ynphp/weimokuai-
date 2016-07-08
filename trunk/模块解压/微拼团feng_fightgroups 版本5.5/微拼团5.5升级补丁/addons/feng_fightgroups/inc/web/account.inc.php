<?php
	global $_W,$_GPC;
	$this -> backlists();
	load() -> func('tpl');
	$ops = array('display', 'edit', 'delete','account','record','data');
	$op = in_array($_GPC['op'], $ops) ? $_GPC['op'] : 'display';
	//商家列表显示
	if($op == 'display'){
		$uniacid=$_W['uniacid'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$merchants = pdo_fetchall("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$uniacid} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		foreach($merchants as$key=>$value){
			$account =  pdo_fetch("SELECT amount,no_money FROM ".tablename('tg_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$value['id']}");
			$merchants[$key]['amount'] = $account['amount'];
			$merchants[$key]['no_money'] = $account['no_money'];
		}
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_merchant') . " WHERE uniacid = '{$uniacid}'");
		$pager = pagination($total, $pindex, $psize);
		
	}
	//商家编辑
	if ($op == 'account') {
		$id = $_GPC['id'];
		$merchant = pdo_fetch("SELECT * FROM ".tablename('tg_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$id}");
		$account =  pdo_fetch("SELECT amount,no_money FROM ".tablename('tg_merchant_account')." WHERE uniacid = {$_W['uniacid']} and merchantid={$id}");
		if (checksubmit('submit')) {
			$money = $_GPC['money'];
			$mm = $money;
			if(!empty($merchant['percent'])){
				$money = (1-$merchant['percent']*0.01)*$money;
			}else{
				$money = $money;
			}
			if(is_numeric($money)){
				if($money<1){
					message('到账金额需要大于1元！', referer(), 'error');
					return false;
				}
				$result = $this -> finance($merchant['openid'], 1, $money * 100, '', '商家提现');
				if (is_error($result)) {
					message('微信钱包提现失败: ' . $result['message'], '', 'error');exit;
				}
				if($result){
					$res=$this->updateaccount(0-$mm,$id);
					pdo_insert("tg_merchant_record",array('merchantid'=>$id,'money'=>$mm,'uid'=>$_W['uid'],'createtime'=>TIMESTAMP,'uniacid'=>$_W['uniacid']));
				}
			}else{
				message('结算金额输入错误！', referer(), 'error');
				return false;
			}
			message('结算成功！', referer(), 'success');
		}
	}
	if($op == 'record') {
		$id = $_GPC['id'];
		$merchant = pdo_fetch("SELECT thumb,name,openid FROM ".tablename('tg_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$id}");
		$list = pdo_fetchall("select * from".tablename('tg_merchant_record')."where merchantid='{$id}' and uniacid={$_W['uniacid']} ");
	}
	if($op == 'data'){
		$id = $_GPC['id'];
		$con =  "uniacid = '{$_W['uniacid']}' and mobile<>'虚拟' and merchantid = '{$id}' "  ;
		$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
		$endtime = empty($_GPC['time']['end']) ? strtotime(date('Y-m-d'))+86400 : strtotime($_GPC['time']['end'])+86400;
		$s = $starttime;
		$e = $endtime;
		$list = array();
		$j=0;
		while($e >= $s){
			$listone = pdo_fetchall("SELECT id  FROM " . tablename('tg_order') . " WHERE $con   AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array( ':createtime' => $e-86400, ':endtime' => $e));
			$status1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE $con and status<>0   AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array( ':createtime' => $e-86400, ':endtime' => $e));
			$status4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_order') . " WHERE $con and status=4   AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array( ':createtime' => $e-86400, ':endtime' => $e));
			$list[$j]['gnum'] = count($listone);
			$list[$j]['status4'] = $status4;
			$list[$j]['status1'] = $status1;
			$list[$j]['createtime'] =  $e-86400;
			$j++;
			$e = $e-86400;
		}
	//	echo "<pre>";print_r(date('Y-m-d H:i:s',$starttime)."====".date('Y-m-d H:i:s',$endtime));exit;
		$day = $hit = $status4 = $status1 = array();
		if (!empty($list)) {
			foreach ($list as $row) {
				$day[] = date('m-d', $row['createtime']);
				$hit[] = intval($row['gnum']);
				$status4[] = intval($row['status4']);
				$status1[] = intval($row['status1']);
			}
		}
		
		for ($i = 0; $i = count($hit) < 2; $i++) {
			$day[] = date('m-d', $endtime);
			$hit[] = $day[$i] == date('m-d', $endtime) ? $hit[0] : '0';
		}
//		include $this -> template('web/data');
	}
	include $this->template('web/account');
	
	if($op == 'delete') {}
?>