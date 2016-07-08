<?php
/**
 * 女神来了模块订阅器
 *
 */


defined('IN_IA') or exit('Access Denied');

class Fm_photosvoteModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W, $_GPC;
		load()->func('communication');
		$type = $this->message['type'];
		$uniacid = $GLOBALS['_W']['uniacid'];
		$acid = $GLOBALS['_W']['acid'];
		//$uniacid = $_W['acid'];
		
		$rid = intval($this->params['rule']);		
		$rid = $this->rule;
		$openid = $this->message['fromusername'];
		
		$cfg = $this->module['config'];
		//file_put_contents(IA_ROOT.'/receive.txt', iserializer($cfg));
		if ($this->message['event'] == 'unsubscribe' && $cfg['isopenjsps']) {
			$record = array(
				'updatetime'=> TIMESTAMP,
				'follow' 	=> '0',
				'followtime'=> TIMESTAMP
			);
			pdo_update('mc_mapping_fans', $record, array('openid' => $openid, 'acid' => $acid, 'uniacid' => $uniacid));


			$fmvotelog = pdo_fetchall("SELECT * FROM ".tablename('fm_photosvote_votelog')." WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(':from_user' => $openid,':uniacid' => $uniacid));
			
			foreach ($fmvotelog as $log) {
				$fmprovevote = pdo_fetch("SELECT * FROM ".tablename('fm_photosvote_provevote')." WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(':from_user' => $log['tfrom_user'],':uniacid' => $uniacid));
				pdo_update('fm_photosvote_provevote', array(
					'lasttime' => TIMESTAMP,
					'photosnum' => $fmprovevote['photosnum'] - 1,
					'hits' => $fmprovevote['hits'] - 1,					
				), array(
					'from_user' => $log['tfrom_user'],
					'uniacid' => $uniacid,
				));
			}
			
			pdo_delete('fm_photosvote_votelog', array('from_user' => $openid));
			pdo_delete('fm_photosvote_bbsreply', array('from_user' => $openid));
			
		}
	}
}
