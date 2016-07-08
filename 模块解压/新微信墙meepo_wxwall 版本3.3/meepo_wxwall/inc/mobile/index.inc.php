<?php
global $_W,$_GPC;
$act = !empty($_GPC['act'])?$_GPC['act']:'index';
$eid = !empty($_GPC['eid'])?intval($_GPC['eid']):1;

$setting = pdo_fetch("SELECT * FROM ".tablename('meepo_tu_set')." WHERE uniacid = '{$_W['uniacid']}' limit 1");

$eid = intval($_GPC['eid']);//0是全部， 1是表白，2是吐槽，3是心愿 '联盟微信墙，有你更精彩，全民来吐槽';
if($act == 'index'){
	$title = $setting['title'];
	$wx_name = $setting['wx_name'];
	$wx_num = $setting['wx_num'];
	$share_title = $setting['share_title'];
	$share_content = $setting['share_content'];
	$share_img = $setting['share_img'];
	include $this->template('index2');
}

if($act == 'getOne'){
	$id = !empty($_GPC['id'])?intval($_GPC['id']):'0';
	
	$tu_data = pdo_fetch("SELECT * FROM".tablename('meepo_tu_data')." WHERE uniacid='{$_W['uniacid']}' AND id='{$id}' ");
	
	if($tu_data){
		$value = array(
			'info'=>$id,
			'status'=>1,
			'data'=>$tu_data
		);
	}else{
		$value = array(
			'info'=>'未知错误,请重试',
			'status'=>0,
			'data'=>''
		);
	}
	die(json_encode($value));

}
if($act == 'getComment'){
	//eid=1460&start=0&count=20
	$eid = !empty($_GPC['class_id'])?intval($_GPC['class_id']):'75';
	$start = !empty($_GPC['start'])?intval($_GPC['start']):'0';
	$count = !empty($_GPC['start'])?intval($_GPC['count']):'20';
	
	$date = pdo_fetchall("SELECT * FROM ".tablename('meepo_tu_comment')." WHERE uniacid='{$_W['uniacid']}' AND eid = '{$eid}' limit ".$start.",".$count);
	$value = array();
	if($date){
		foreach ($date as $key=>&$da){
			$da['row'] = $key;
			$da['time'] = date('Y-m-d h:i:s A',$da['time']);
			$da['time_r'] = date('Y-m-d h:i A',$da['time_r']);
		}
		$value = array(
			'total_count' => count($date),
			'status'=> 1,
			'size'=> count($date),
			'info'=> $eid,
			'data'=>$date,
		);
	}else{
		$value = array(
			'status'=> -2,
			'size'=> 0,
			'info'=> '没有更多，去发布一条吧！',
			'data'=>'',
		);
	}
	
	die(json_encode($value));

}
if($act == 'postComment'){
		global $_W,$_GPC;
		$eid = !empty($_GPC['comment_id'])?intval($_GPC['comment_id']):'';
		$com_count = pdo_fetch("SELECT com_count FROM".tablename('meepo_tu_data')." WHERE id='{$eid}'");
		
		if(empty($_GPC['comment_content'])){
			$value=array('status'=>0,'info'=>'请输入发表内容');
			die(json_encode($value));
		}
		$data = array(
			'uniacid'=>$_W['uniacid'],
			'con'=>$_GPC['comment_content'],
			'nick'=>!empty($_GPC['comment_nick'])?$_GPC['comment_nick']:'匿名',
			'eid'=>$eid,
			'time'=>time(),
			'time_r'=>time(),
		);
		$color = array('rgba(239, 112, 39, .85)','rgba(0, 175, 215, .85','rgba(229, 182, 0, .85)');
		$data['color'] = $color[array_rand($color)];
		if(pdo_insert('meepo_tu_comment',$data)){
			//更新回复状态
			pdo_query("UPDATE ".tablename('meepo_tu_data')." SET com_count = com_count + 1 WHERE id = '{$eid}'");
			
			$value=array('status'=>1,'eid'=>$eid,'info'=>'发布成功');
		}else{
			$value=array('status'=>0,'eid'=>$eid,'info'=>'发布失败,请重试');
		}
		
		die(json_encode($value));
}
if($act == 'postContent'){
	global $_W,$_GPC;
	//"post_content=222222222&post_class=1&post_nick=111222222222"
		if(empty($_GPC['post_content'])){
			$value=array('status'=>0,'info'=>'请输入发表内容');
			die(json_encode($value));
		}
		$data = array(
			'uniacid'=>$_W['uniacid'],
			'con'=>$_GPC['post_content'],
			'class'=>$_GPC['post_class'],
			'nick'=>!empty($_GPC['post_nick'])?$_GPC['post_nick']:'匿名',
			'time'=>time(),
			'time_r'=>time(),
		);
		if($_GPC['post_class']==1){
			$data['class_name'] = '表白';
			$data['color'] = 'rgba(239, 112, 39, .85)';
		}elseif($_GPC['post_class']==2){
			$data['class_name'] = '吐槽';
			$data['color'] = 'rgba(0, 175, 215, .85)';
		}elseif($_GPC['post_class']==3){
			$data['class_name'] = '心愿';
			$data['color'] = 'rgba(229, 182, 0, .85)';
		}
		if(pdo_insert('meepo_tu_data',$data)){
			$value=array('status'=>1,'info'=>'发布成功');
		}else{
			$value=array('status'=>0,'info'=>'发布失败,请重试');
		}
		
		die(json_encode($value));
}
if($act == 'getContent'){
	global $_W,$_GPC;
	$eid = !empty($_GPC['class_id'])?intval($_GPC['class_id']):0;
	if($eid){
		$where .= " AND class = '{$eid}'";
	}
	$num = pdo_fetchcolumn("SELECT * FROM ".tablename('meepo_tu_data')." WHERE uniacid = '{$_W['uniacid']}' {$where}");
	$start = !empty($_GPC['start'])?intval($_GPC['start']):0;
	$count = !empty($_GPC['count'])?intval($_GPC['count']):20;
	$content = pdo_fetchall("SELECT * FROM ".tablename('meepo_tu_data')." WHERE uniacid = '{$_W['uniacid']}' {$where} ORDER BY time DESC LIMIT ".$start.",".$count);
	
	if(!empty($content)){
		foreach ($content as $key=>&$con){
			unset($con['uniacid']);
			$con['time'] = date('Y-m-d h:i:s A',$con['time']);
			$con['time_r'] = date('Y-m-d h:i A',$con['time_r']);
		}
		$return = array(
		    'status' => 1,
		    'info' => $eid,
		    'size' => count($content),
		    'data' => $content
		);
	}else{
		$return = array(
		    'status' => 0,
		    'info' => '加载完毕',
		    'size' => 0,
		    'data' => ''
		);
	}
	
	die(json_encode($return));
}

if($act == 'debug'){
	$str = '{"status":1,"info":0,"size":20,"data":[{"id":"1454","class":"1","nick":"\u533f\u540d","time":"2014-11-06 10:23:17","time_r":"2014-11-06 10:23","con":"\u4e0d\u60f3\u559c\u6b22\u8c01\uff0c\u5c31\u662f\u4e0d\u559c\u6b22","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1453","class":"1","nick":"\u533f\u540d","time":"2014-11-06 06:17:10","time_r":"2014-11-06 06:17","con":"\u674e\u5c1a","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1434","class":"3","nick":"\u533f\u540d","time":"2014-10-30 09:06:49","time_r":"2014-10-30 09:06","con":"\u6709\u4eba\u4e00\u8d77\u7a77\u6e38\u54c8\u5c14\u6ee8\u5417\uff0c","com_count":"1","good":"0","color":"rgba(229, 182, 0, .85)","class_name":"\u5fc3\u613f"},{"id":"1360","class":"1","nick":"\u5c0f\u70ae\u7070","time":"2014-10-20 17:28:32","time_r":"2014-10-20 17:28","con":"\u674e\u8363\u6211\u8981\u7ed9\u4f60\u751f\u5b69\u5b50\uff01","com_count":"2","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1345","class":"1","nick":"\u533f\u540d","time":"2014-10-17 17:35:40","time_r":"2014-10-17 17:35","con":"wtt\u6211\u559c\u6b22\u4f60","com_count":"3","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1384","class":"1","nick":"\u533f\u540d","time":"2014-10-22 23:20:05","time_r":"2014-10-22 23:20","con":"JH\uff5e\uff5e\u559c\u6b22\u4f60\u5466","com_count":"1","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1381","class":"1","nick":"\u533f\u540d","time":"2014-10-22 22:26:27","time_r":"2014-10-22 22:26","con":"\u6d4b\u7ed813-1\u5409\u7965  \u6211\u597d\u559c\u6b22\u4f60\u554a  \u54b1\u4fe9\u4e00\u5757\u5427","com_count":"2","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1452","class":"1","nick":"\u533f\u540d","time":"2014-11-04 15:55:15","time_r":"2014-11-04 15:55","con":"\u4e00\u76f4\u5f88\u7231\u4ed6\uff0c\u8868\u793a\u5df2\u88ab\u62c9\u9ed1","com_count":"1","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1190","class":"1","nick":"\u7537\u4eba","time":"2014-08-16 13:07:52","time_r":"2014-08-16 13:07","con":"\u4e1c\u5317\u4eba\u4e0d\u60f3\u548c\u5357\u65b9\u4eba\u8c08\u604b\u7231","com_count":"11","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1451","class":"1","nick":"\u533f\u540d","time":"2014-11-04 10:37:52","time_r":"2014-11-04 10:37","con":"\u80e1\u6d0b\u6211\u559c\u6b22\u4f60","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1442","class":"1","nick":"\u533f\u540d","time":"2014-11-01 22:42:43","time_r":"2014-11-01 22:42","con":"\u6211\u770b\u4e0a\u4f1a\u8ba1\u7cfb\u5927\u56db\u4e00\u4e2a\u5973\u7684\u4e86\uff0c\u5979\u975e\u5e38\u53ef\u7231\uff0c\u5927\u773c\u775b\uff0c\u6253\u626e\u4e5f\u65f6\u9ae6\uff0c\u7ecf\u5e38\u6234\u5e3d\u5b50\uff0c\u542c\u4eba\u8bf4\u5979\u597d\u50cf\u53eb\u4ec0\u4e48\u4e91\uff0c\u6709\u4eba\u8ba4\u8bc6\u5417","com_count":"10","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1450","class":"1","nick":"\u6211\u662f\u4f60\u7684\u5927\u767d\u5154","time":"2014-11-03 19:51:31","time_r":"2014-11-03 19:51","con":"\u732a\u732a\uff0c\u6211\u559c\u6b22\u4f60","com_count":"1","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1449","class":"3","nick":"532555","time":"2014-11-03 18:09:45","time_r":"2014-11-03 18:09","con":"\uff0c\uff0c\uff0c\uff0c\u3002\u3002\u3002\u3002\u3002","com_count":"0","good":"0","color":"rgba(229, 182, 0, .85)","class_name":"\u5fc3\u613f"},{"id":"1448","class":"1","nick":"\u533f\u540d","time":"2014-11-03 14:20:20","time_r":"2014-11-03 14:20","con":"\u662f","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1447","class":"1","nick":"\u533f\u540d","time":"2014-11-02 22:52:25","time_r":"2014-11-02 22:52","con":"\u7cdf\u4e86\uff0c\u611f\u89c9\u559c\u6b22\u4e0a\u4e00\u4e2a\u59d1\u5a18\u4e86","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1446","class":"1","nick":"\u6750\u51b6","time":"2014-11-02 15:00:25","time_r":"2014-11-02 15:00","con":"\u6750\u63a714\u7684\u674e\u7490\u5f88\u53ef\u7231 \u8d8a\u770b\u8d8a\u559c\u6b22","com_count":"1","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1445","class":"1","nick":"\u6750\u51b6","time":"2014-11-02 14:56:00","time_r":"2014-11-02 14:56","con":"\u6750\u63a7","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1444","class":"1","nick":"\u533f\u540d","time":"2014-11-01 23:23:16","time_r":"2014-11-01 23:23","con":"MZ","com_count":"0","good":"0","color":"rgba(239, 112, 39, .85)","class_name":"\u8868\u767d"},{"id":"1443","class":"2","nick":"\u533f\u540d","time":"2014-11-01 22:46:31","time_r":"2014-11-01 22:46","con":"\u5927\u534a\u591c\u5728\u6c34\u623f\u6d17\u8863\u670d\u7684\u90fd\u662f\u7eff\u8336\u5a4a\uff01\u8981\u8138\u4e0d","com_count":"0","good":"0","color":"rgba(0, 175, 215, .85)","class_name":"\u5410\u69fd"},{"id":"1123","class":"2","nick":"\u533f\u540d","time":"2014-08-03 01:31:46","time_r":"2014-08-03 01:31","con":"\u4ed6\u59b9\u7684\u4e00\u89c9\u8d77\u6765\u901b\u7a7a\u95f4\u5c31\u770b\u5230\u90a3\u4e2a\u8d31\u4eba\u5728\u79c0\u6069\u7231\uff0c\u6076\u5fc3\u6b7b\u4e86\u3002PS\uff1a\u662f\u670b\u53cb\u524d\u5973\u53cb","com_count":"1","good":"0","color":"rgba(0, 175, 215, .85)","class_name":"\u5410\u69fd"}]}';
	
	print_r(json_decode($str));

}

function getContent(){
	global $_W,$_GPC;
	$eid = intval($_GPC['eid']);//0是全部， 1是表白，2是吐槽，3是心愿
	$start = intval($_GPC['start']);//起始页码
	$count = intval($_GPC['count']);//加载数目
	$where = "";
	if($eid == 0){
	}else{
		$where .= " AND class = {$eid}";
	}
	$content = pdo_fetchall("SELECT * FROM ".tablename('meepo_tu_data')." WHERE uniacid = '{$_W['uniacid']}' ".$where." ORDER BY time DESC LIMIT ". $start . "," . $count);
	if(!empty($content)){
		foreach ($content as &$con){
			unset($con['uniacid']);
			$con['time'] = date('Y-m-d h:i:s A',$con['time']);
			$con['time_r'] = date('Y-m-d h:i A',$con['time_r']);
		}
	}
	return $content;

}
