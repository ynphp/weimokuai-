<?php
/**
 * 活动中心模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class J_activityModule extends WeModule {
	public $tablename = 'j_activity_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}else{
			$reply=array(
				'joinstarttime'=>strtotime(date("Y-m-d")),
				'joinendtime'=>strtotime(date("Y-m-d")),
				'starttime'=>strtotime(date("Y-m-d")),
				'endtime'=>strtotime(date("Y-m-d")),
			);
		}
		load()->func('tpl');
		$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return true;
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		include('../addons/j_activity/phpqrcode.php');
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid'=> $_W['uniacid'],
			'picture' => $_GPC['picture'],
			'qrcode' => $_GPC['qrcode'],
			'clientpic' => $_GPC['clientpic'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'info' => htmlspecialchars_decode($_GPC['info']),
			'rule' => htmlspecialchars_decode($_GPC['rule']),
			'content' => htmlspecialchars_decode($_GPC['content']),
			'appendcode' => $_GPC['appendcode'],
			'quota' => intval($_GPC['quota']),
			'joinstarttime' => strtotime($_GPC['jointime']['start']),
			'joinendtime' => strtotime($_GPC['jointime']['end']),
			'starttime' => strtotime($_GPC['acttime']['start']),
			'endtime' => strtotime($_GPC['acttime']['end']),
			'applicants'=>intval($_GPC['applicants']),
			'status'=>intval($_GPC['status']),
			'usertype'=>intval($_GPC['usertype']),
			'credit_join'=>intval($_GPC['credit_join']),
			'credit_in'=>intval($_GPC['credit_in']),
			'credit_append'=>intval($_GPC['credit_append']),
		);
		$parama=array();
		if(isset($_GPC['parama-key'])){
			foreach ($_GPC['parama-key'] as $index => $row) {
				if(empty($row))continue;
				
				$parama[urlencode($row)]=urlencode($_GPC['parama-val'][$index]);
			}
		}
		if(isset($_GPC['parama-key-new'])){
			foreach ($_GPC['parama-key-new'] as $index => $row) {
				if(empty($row))continue;
				echo $_GPC['parama-val'][$index];
				$parama[urlencode($row)]=urlencode($_GPC['parama-val-new'][$index]);
			}
		}
		$insert['parama']=urldecode(json_encode($parama));
		if (empty($id)) {
			$insert['status']=1;
			pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		global $_W;
		$replies = pdo_fetchall("SELECT id, picture,qrcode,clientpic FROM ".tablename($this->tablename)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				file_delete($row['picture']);
				file_delete($row['qrcode']);
				file_delete($row['clientpic']);
				$deleteid[] = $row['id'];
				pdo_delete("j_activity_winner",array('aid'=>$row['id']));
			}
		}
		
		pdo_delete($this->tablename, "id IN ('".implode("','", $deleteid)."')");
		message('删除成功', '', 'success');
		return true;
	}
	
	public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
                'iscredit' => intval($_GPC['iscredit']),
				'test' => $_GPC['test'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		load()->func('tpl');
		include $this->template('setting');
    }
}