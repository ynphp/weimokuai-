<?php
/**
 * 七夕爱几何模块定义
 *
 * @author 宇光
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yg_qxModule extends WeModule {
	public $table_reply = 'yg_qx_reply';
	public $table_oauth = 'yg_qx_oauth';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		if ($rid == 0) {
			$reply = array(
				'title'=> '天长地久',
				'description' => '天长地久',
				'starttime' => time(),
				'endtime' => time() + 10 * 84400,
				'status' => 1,
				'indexmsg' => MODULE_URL.'template/mobile/img/67d07a18a47ca552.png',
				'music1'=>MODULE_URL.'template/mobile/mp3/bg.mp3',
				'indexpic1'=>MODULE_URL.'template/mobile/img/45a9a83b39372509.png',
				'music2'=>MODULE_URL.'template/mobile/mp3/01.mp3',
				'indexpic2'=>MODULE_URL.'template/mobile/img/ffe3578386f90c0c.jpg',
				'music3'=>MODULE_URL.'template/mobile/mp3/03.mp3',
				'indexpic3'=>MODULE_URL.'template/mobile/img/5c989b46c7c60b7d.jpg',
				'music4'=>MODULE_URL.'template/mobile/mp3/05.mp3',
				'indexpic4'=>MODULE_URL.'template/mobile/img/c3874c8a1755a47d.jpg',
				'music5'=>MODULE_URL.'template/mobile/mp3/06.mp3',
				'indexpic5'=>MODULE_URL.'template/mobile/img/4ed70e4ea91dbc5a.jpg',
				'music6'=>MODULE_URL.'template/mobile/mp3/07.mp3',
				'indexpic6'=>MODULE_URL.'template/mobile/img/59a61f2292ceb377.jpg',
				'music7'=>MODULE_URL.'template/mobile/mp3/08.mp3',
				'indexpic7'=>MODULE_URL.'template/mobile/img/5c675728a38a65fa.jpg',
				'music8'=>MODULE_URL.'template/mobile/mp3/bg.mp3',
				'indexpic8'=>MODULE_URL.'template/mobile/img/bb7f77b0b038a3b0.png',
				'music9'=>MODULE_URL.'template/mobile/mp3/bg.mp3',
				'indexpic9'=>MODULE_URL.'template/mobile/img/a4309dc6b3e8e9be.png',
			
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$i = 1;
		
		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'thumb' => $_GPC['thumb'],
			'description' => $_GPC['description'],
			'starttime' => strtotime($_GPC['time'][start]),
			'endtime' => strtotime($_GPC['time'][end]),
			'status' => intval($_GPC['status']),			
			'indexmsg' => $_GPC['indexmsg'],
			'indexpic1' => $_GPC['indexpic1'],
			'indexpic2' => $_GPC['indexpic2'],
			'indexpic3' => $_GPC['indexpic3'],
			'indexpic4' => $_GPC['indexpic4'],
			'indexpic5' => $_GPC['indexpic5'],
			'indexpic6' => $_GPC['indexpic6'],
			'indexpic7' => $_GPC['indexpic7'],
			'indexpic8' => $_GPC['indexpic8'],
			'indexpic9' => $_GPC['indexpic9'],
			
			'music1' => $_GPC['music1'],
			'music2' => $_GPC['music2'],
			'music3' => $_GPC['music3'],
			'music4' => $_GPC['music4'],
			'music5' => $_GPC['music5'],
			'music6' =>	$_GPC['music6'],
			'music7' => $_GPC['music7'],
			'music8' => $_GPC['music8'],
			'music9' => $_GPC['music9'],
			'sharepic' =>$_GPC['sharepic'],
			'sharedesc' => $_GPC['sharedesc'],
			'sharetitle' => $_GPC['sharetitle'],
			'createtime' => TIMESTAMP,
			);
		
		if (empty($id)) {
			pdo_insert($this->table_reply, $insert);
		} else {
			unset($insert['createtime']);
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		$replies = pdo_fetchall("SELECT id  FROM ".tablename($this->table_reply)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->table_reply, "id IN ('".implode("','", $deleteid)."')");
	}


	public function settingsDisplay($settings) {
		
		global $_GPC, $_W;
		if(checksubmit()) {
			$cfg = array();
			$cfg['appid'] = $_GPC['appid'];
			$cfg['secret'] = $_GPC['secret'];
			if($this->saveSettings($cfg)) {

				$insert= array(
				'weid' => $_W['weid'],
				'appid'  => $cfg['appid'],
				'secret'  => $cfg['secret'],
				);
				$result= pdo_fetch("select * from ".tablename($this->table_oauth)." where 1=1 and weid={$_W['weid']}");
				if (empty($result)) {

					pdo_insert($this->table_oauth, $insert);
				} else {			
					pdo_update($this->table_oauth, $insert, array('id' => $result['id']));
				}
				message('保存成功', 'refresh');
			}
		}	
			$config = '已授权';
			
		include $this->template('setting');
	}


}