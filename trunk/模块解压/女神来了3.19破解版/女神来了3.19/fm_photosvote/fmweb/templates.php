<?php
/**
 * [Fmoons System] Copyright (c) 2014 FMOONS.COM
 * Fmoons isNOT a free software, it under the license terms, visited http://www.fmoons.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tempdo = empty($_GPC['tempdo']) ? "" : $_GPC['tempdo'];
$pageid = empty($_GPC['pageid']) ? "" : $_GPC['pageid'];
$apido = empty($_GPC['apido']) ? "" : $_GPC['apido'];
$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
	$rid = intval($_GPC['rid']);
if($operation == 'display') {
	$rid = intval($_GPC['rid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$templates = pdo_fetchall("SELECT * FROM ".tablename($this->table_templates)." WHERE uniacid = '{$_W['uniacid']}' or uniacid = 0 ORDER BY name ASC, createtime DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_templates) . " WHERE uniacid = '{$_W['uniacid']}' or uniacid = 0");
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('templates');
} elseif($operation == 'post') {
	load()->func('tpl');
	$id = intval($_GPC['id']);
	$rid = intval($_GPC['rid']);
	if (!empty($id)) {
		$item = pdo_fetch("SELECT * FROM ".tablename($this->table_templates)." WHERE id = :id" , array(':id' => $id));
	} 
	$files = array('1' =>'photosvote.html', '2'=>'tuser.html', '3'=>'paihang.html', '4' =>'reg.html', '5'=>'des.html');
	if (checksubmit('submit')) {
		
		define('REGULAR_STYLENAME', '/^(([a-z]+[0-9]+)|([a-z]+))[a-z0-9]*$/i');
		
		if(!preg_match(REGULAR_STYLENAME, $_GPC['stylename'])) {
			message('必须输入模板标识，格式为 字母（不区分大小写）+ 数字,不能出现中文、中文字符');
		}
		if (empty($_GPC['title'])) {
			message('标题不能为空，请输入标题！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'version' => $_GPC['version'],
			'description' => $_GPC['description'],
			'author' => $_GPC['author'],
			'thumb' => $_GPC['thumb'],
			'url' => $_GPC['url'],
			'type' => 'all',
			'createtime' => TIMESTAMP
		);
		if (empty($id)) {
			if ($_GPC['stylename'] == $item['templates']) {
				message('该模板标识已存在，请更换');
			}
			$data['name'] = $_GPC['stylename'];
			pdo_insert($this->table_templates, $data);
			$aid = pdo_insertid();
		} else {
			$data['name'] = $item['name'];
			unset($data['createtime']);
			pdo_update($this->table_templates, $data, array('id' => $id));
		}
		message('模板更新成功！', $this->createWebUrl('templates', array('op' => 'display', 'rid' => $rid)), 'success');
	}
	include $this->template('templates');
} elseif($operation == 'designer') {

	include $this->template('templates');
} elseif($operation == 'default') {
	$rid = intval($_GPC['rid']);
	
	if (!empty($rid) && !empty($_GPC['templatesname']) && $rid <> 0) {
		pdo_update($this->table_reply, array('templates' => $_GPC['templatesname']), array('rid' => $rid));
		$fmdata = array(
			"success" => 1,
			"msg" => '设置默认模板成功！'
		);
		echo json_encode($fmdata);
		exit();	
		//message('设置默认模板成功！', $this->createWebUrl('index', array('rid' => $rid)), 'success');
	}else{
		$fmdata = array(
			"success" => -1,
			"msg" => '设置默认模板错误！'
		);
		echo json_encode($fmdata);
		exit();	
		//message('设置默认模板错误！', $this->createWebUrl('index', array('rid' => $rid)), 'error');
	}
	
} elseif($operation == 'delete') {
	load()->func('file');
	$id = intval($_GPC['id']);
	$row = pdo_fetch("SELECT id,thumb,stylename FROM ".tablename($this->table_templates)." WHERE id = :id", array(':id' => $id));
	if (empty($row)) {
		message('抱歉，模板不存在或是已经被删除！');
	}
	if (!empty($row['thumb'])) {
		file_delete($row['thumb']);
	}
	pdo_delete($this->table_templates, array('id' => $id));
	pdo_delete($this->table_designer, array('stylename' => $row['stylename']));
	message('删除成功！', $this->createWebUrl('templates', array('op' => 'display', 'rid' => $rid)), 'success');
} elseif ($operation == 'api') {
	
}

function gettemplates($pagetype) {
	
	switch ($pagetype) {
	  case '1':
	    $name = 'photosvote.html';
	    break;
	  case '2':
	    $name = 'tuser.html';
	    break;
	  case '3':
	    $name = 'paihang.html';
	    break;
	  case '4':
	    $name = 'reg.html';
	    break;
	  case '5':
	    $name = 'des.html';
	    break;
	  
	  default:
	    $name = 'photosvote.html';
	    break;
	}
	return $name;
}
function getnames($names) {
	switch ($names) {
	  case 'photosvote.html':
	    $name = '投票首页';
	    break;
	  case 'tuser.html':
	    $name = '投票详情页';
	    break;
	  case 'tuserphotos.html':
	    $name = '投票相册展示页';
	    break;
	  case 'reg.html':
	    $name = '注册报名页';
	    break;
	  case 'paihang.html':
	    $name = '排行榜页';
	    break;
	  case 'des.html':
	    $name = '活动详情页';
	    break;
	  
	  default:
	    $name = '女神来了';
	    break;
	}
	return $name;
}
