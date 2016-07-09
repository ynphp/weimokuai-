<?php
global $_W,$_GPC;

load()->func('tpl');
$forum = getSet();
$table = 'meepo_bbs_topics';
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

$sql = "SELECT * FROM ".tablename('meepo_bbs_set')." WHERE uniacid = :uniacid";
$params = array(':uniacid'=>$_W['uniacid']);
$row = pdo_fetch($sql,$params);
$forum = unserialize($row['set']);

$title = $forum['title'];
load()->model('mc');

//分类列表

//查看用户组
$params = array(':uniacid'=>$_W['uniacid']);
$where = "";
$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid $where ORDER BY displayorder DESC";
$list = pdo_fetchall($sql,$params);
if(empty($_W['member']['uid'])){
	$user['groupid'] = -1;
}else{
	$user = mc_fetch($_W['member']['uid'],array('groupid'));
}
foreach ($list as $li) {
	$li['icon'] = tomedia($li['icon']);
	$group = unserialize($li['post_group']);
	if(in_array($user['groupid'], (array)$group)){
		$cats[] = $li;
	}
}

unset($list);

$id = $_GPC['id'];
$id = intval($_GPC['id']);

$sql = "SELECT * FROM ".tablename('meepo_bbs_topics')." WHERE id = :id";
$params = array(':id'=>$id);
$setting = pdo_fetch($sql,$params);
$setting['thumb'] = iunserializer($setting['thumb']);


$cats = getCat();

if($_W['ispost']){
	
	$data = array();
	if(empty($_GPC['title'])){
		message('标题不能为空',referer(),error);
	}
	if(!empty($_GPC['fid'])){
		$return = check_post($_GPC['fid']);
		if(is_error($return)){
			message($return['message'],$this->createMobileUrl('forum'),error);
		}
	}else{
		message('请选择版块',referer(),error);
	}
	$sql = "SELECT createtime FROM ".tablename('meepo_bbs_topics')." WHERE uid = :uid ORDER BY createtime DESC limit 1";
	$params = array(':uid'=>$_W['member']['uid']);
	$lasttime = pdo_fetchcolumn($sql,$params);
	if(empty($forum['post_time'])){
		$forum['post_time'] = 0;
	}
	if(empty($lasttime)){
		$lasttime = time()-$forum['post_time'];
	}
	if((time()-$lasttime) < $forum['post_time']){
		message($forum['post_time'].'秒内不能重复发帖',$this->createMobileUrl('forum'),error);
	}
	
	
	$data['uniacid'] = $_W['uniacid'];
	$data['uid'] = $_W['member']['uid'];
	$data['title'] = trim($_GPC['title']);
	$data['last_reply_at'] = time();
	$data['createtime'] = time();
	$data['fid'] = $_GPC['fid'];
	$data['content'] = trim($_GPC['content']);
	$data['content'] = $data['content'];
	$data['tab'] = 'new';
	if(!empty($_GPC['thumb'])){
		foreach ($_GPC['thumb'] as $thumb){
			$th[] = save_media(tomedia($thumb));
		}
		$data['thumb'] = iserializer($th);
	}
	
	
	if(empty($id)){
		pdo_insert('meepo_bbs_topics',$data);
		$id = pdo_insertid();
		$credit = getCredit($data['fid']);
		update_credit_post($id);
	}else{
		unset($data['uniacid']);
		unset($data['uid']);
		pdo_update('meepo_bbs_topics',$data,array('id'=>$id));
	}
	message('',$this->createMobileUrl('forum'));
}


include $this->template($tempalte.'/templates/forum/post');
