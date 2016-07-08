<?php
/**
 * 充值卡模块微站定义
 *
 * @author mcoder
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Mcoder_rechargeModuleSite extends WeModuleSite {

	//充值卡列表
	public function doWeblist(){
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = $_GPC['status'];

			$condition = " o.weid = :weid AND o.isdel = 0";
			$paras = array(':weid' => $_W['uniacid']);

			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = TIMESTAMP;
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.addtime >= :starttime AND o.addtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}

			if (!empty($_GPC['cardno'])) {
				$condition .= " AND (o.cardno  LIKE  '{$_GPC['cardno']}') ";
			}

			if (!empty($_GPC['cardse'])) {
				$condition .= " AND (o.cardse  LIKE  '{$_GPC['cardse']}') ";
			}
			if (!empty($_GPC['cardamount'])) {
				$condition .= " AND (o.cardamount = '{$_GPC['cardamount']}') ";
			}
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}


			$sql = 'SELECT COUNT(*) FROM ' . tablename('mcoder_recharge_card') . ' AS `o`  WHERE ' . $condition;
			$total = pdo_fetchcolumn($sql, $paras);



			if ($total > 0) {

				if ($_GPC['export'] != 'export') {
					$limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
				} else {
					$limit = '';
					//$condition = " o.weid = :weid";
					//$paras = array(':weid' => $_W['uniacid']);
				}

				$sql = 'SELECT  `o`.* FROM ' . tablename('mcoder_recharge_card') . ' AS `o`  WHERE ' .
						$condition . ' ORDER BY `o`.`addtime` DESC ' . $limit;

				//var_dump($sql);
				//exit;
				$list = pdo_fetchall($sql,$paras);

				$pager = pagination($total, $pindex, $psize);


				$orderstatus = array (
					'1' => array('css' => 'success', 'name' => '已使用'),
					'0' => array('css' => 'warning', 'name' => '未使用'),
				);

				//var_dump($list);

				foreach ($list as &$value) {
					$s = $value['status'];
					//var_dump($value);
					$value['statuscss'] = $orderstatus[$value['status']]['css'];
					$value['status'] = $orderstatus[$value['status']]['name'];
				}
				//var_dump($list);
				if ($_GPC['export'] != '') {
					/* 输入到CSV文件 */
					$html = "\xEF\xBB\xBF";

					/* 输出表头 */
					$filter = array(
						'tid' => 'ID',						
						'cardno' => '卡号',
						'cardse' => '卡密',
						'cardamount' => '充值金额',
						'addtime' => '生成时间',						
						'status' => '状态',
						'openid' => '使用者',
						'usedtime' => '使用时间',
					);

					foreach ($filter as $key => $title) {
						$html .= $title . "\t,";
					}
					$html .= "\n";

					foreach ($list as $k => $v) {
						foreach ($filter as $key => $title) {
							if ($key == 'addtime' || ($key == 'usedtime' && $v[$key] != '')) {
								$html .= date('Y-m-d H:i:s', $v[$key]) . "\t, ";
							} else {
								$html .= $v[$key] . "\t, ";
							}
						}
						$html .= "\n";
					}
					/* 输出CSV文件 */
					header("Content-type:text/csv");
					header("Content-Disposition:attachment; filename=充值卡数据.csv");
					echo $html;
					exit();

				}
			}

		}elseif($operation == 'delete'){

			$tid = intval($_GPC['tid']);
			pdo_update('mcoder_recharge_card', array('isdel' => '1'), array('tid' => $tid));
			message('删除成功！', referer(), 'success');

		}elseif($operation == 'delall'){
	        $ids= implode(",", $_GPC['delete']);
	        $sqls= "update ".tablename('mcoder_recharge_card')." set isdel = 1 where tid in(".$ids.")";
	        pdo_query($sqls);
	        message('批量删除成功！', referer(), 'success');
		}
		include $this->template('list');
	}

	//生成充值卡
	public function doWebadd(){
		global $_W, $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

		if ($operation == 'doadd') {
			//生成充值卡
			if(empty($_GPC['cardpre']) || empty($_GPC['cardnums']) || empty($_GPC['cardamount'])){
				message('请将卡前缀、充值卡数量、充值卡金额填写完整！', referer(), 'error');
			}

			if($_GPC['cardnums']<=0){
				message('生成的充值卡数量不能少于1个！', referer(), 'error');
			}
			
			$cardpre = (string)$_GPC['cardpre'];
			$cardnums = intval($_GPC['cardnums']);
			$cardamount = (float)$_GPC['cardamount'];
			$weid = $_W['uniacid'];

			$sql = "insert into ".tablename('mcoder_recharge_card')." (cardno,cardse,cardamount,addtime,weid) values ";
			for($i=0;$i<$cardnums;$i++){

				//$cardno = $cardpre.md5(uniqid(md5(microtime(true)),true));
				$timestring = explode(' ',microtime());
				$cardno = $cardpre.substr($timestring[1], 5).($timestring[0]*10000000).$i; 
				$cardse = $this->getRandChar();
				$addtime = TIMESTAMP;
				$sql .= "('$cardno','$cardse','$cardamount','$addtime',$weid),"; 
			}

			$sql = substr( $sql ,0, strlen($sql)-1);
			pdo_run($sql);
			message('成功生成'.$cardnums.'张充值卡！', referer(), 'success');

		}elseif($operation == 'display'){
			include $this->template('add');
		}		
	}

	public function doMobilerecharge(){
		global $_GPC, $_W;
		$operation = $_GPC['op'];

		if($operation == 'dorecharge'){
			if(empty($_GPC['cardno']) || empty($_GPC['cardse'])){
				message('请将充值卡卡号和密码填写完整！', $this->createMobileUrl('recharge'), 'error');
			}else{
				$cardno = (string)$_GPC['cardno'];
				$cardse = (string)$_GPC['cardse'];
			}
			$row = pdo_fetch("SELECT * FROM " . tablename('mcoder_recharge_card') . " WHERE weid= :weid AND cardno= :cardno", array(':weid' => $_W['uniacid'],":cardno" => $cardno));
			if(!$row){
				message('此充值卡不存在！', $this->createMobileUrl('recharge'), 'error');
			}elseif($row['cardse'] != $cardse){
				message('充值卡密码不对！', $this->createMobileUrl('recharge'), 'error');
			}elseif($row['status'] == 1){
				message('该充值卡已被充值过！', $this->createMobileUrl('recharge'), 'error');
			}elseif($row['cardse'] == $cardse){
				$data = array(
					'status' => 1,
					'usedtime' => TIMESTAMP,
					'openid' => $_W['openid'],
				);

				//充值到余额
				$fee = $row['cardamount'];
				if($fee>0){
					$credit = 'credit2';
					$fuid = mc_openid2uid($_W['openid']);
					$fuser = mc_fetch($fuid,array('nickname'));
					$record[] = $fuid;
					$record[] = '通过充值卡充值'.$fee.'元！';
					mc_credit_update($fuid, $credit, $fee, $record);
				}

				pdo_update('mcoder_recharge_card', $data, array('tid' => $row['tid']));



				message('充值成功！', $this->createMobileUrl('recharge'), 'success');
			}else{
				//充值失败
				message('充值失败！', $this->createMobileUrl('recharge'), 'error');
			}
		}else{
			include $this->template('recharge');
		}
	}

	//生成密码
	private function getRandChar($length=8){
		$str = null;
		$strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
		//$strPol = "0123456789";
		$max = strlen($strPol)-1;

		for($i=0;$i<$length;$i++){
		$str.=$strPol[rand(0,$max)];
		}

		return $str;
	}

	//生成卡号
}