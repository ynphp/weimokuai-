<?php
/**
 * 多商品砍价模块定义
 *
 * @author 宇光
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yg_kanjModule extends WeModule {
	
	public $table_reply = 'yg_kanj_reply';
	public $table_oauth = 'yg_kanj_oauth';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		if ($rid == 0) {
			$reply = array(
				'title'=> '中秋抢月饼!',
				'description' => '中秋抢月饼，送大奖！',
				'starttime' => time(),
				'endtime' => time() + 10 * 84400,
				'status' => 1,
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		
		}
		//'indexbg' => MODULE_URL.'template/mobile/images/0begin.png',
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
			'starttime' => strtotime($_GPC['time']['start']),
			'endtime' => strtotime($_GPC['time']['end']),
			'status' => intval($_GPC['status']),			
			'sharepic' =>$_GPC['sharepic'],
			'sharedesc' => $_GPC['sharedesc'],
			'sharetitle' => $_GPC['sharetitle'],
			'toppic' => $_GPC['toppic'],
			'advertising' => $_GPC['advertising'],
			'versions' => $_GPC['versions'],
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