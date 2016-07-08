<?php
$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die(json_encode(array('success'=>false,'msg'=>"请先在服务器上安装memcached，方能使用本功能")));

$operation = $_POST['op']?$_POST['op']:'display';
$aid=@intval($_POST['aid']);
$reply=$memcache->get("j_act_vote_".$aid);
if(!$reply)die(json_encode(array('success'=>true,'msg'=>"活动不存在")));
$reply=unserialize($reply);

if($operation=="extend_vote"){
	$openid=$_POST['from_user'];
	if(!$openid)die(json_encode(array('success'=>false,'msg'=>"缺少身份标识")));
	$voter=intval($_POST['voter']);
	$vote_game_keys="vote_game_keys_".$aid;
	$vote_game_val="vote_game_val_".$aid;
	$voteAllkeys=$memcache->get($vote_game_keys);
	$self_key=$openid."_".$aid;
	if(!$voteAllkeys){
		$ary=array();
		array_push($ary,$self_key);
		$memcache->set($vote_game_keys,serialize($ary));
		$voteAllkeys=$memcache->get($vote_game_keys);
	}
	$voteAllkeys=unserialize($voteAllkeys);
	if(!is_array($voteAllkeys) || !$voteAllkeys)die(json_encode(array('success'=>false,'msg'=>"未知错误")));
	if(in_array($self_key,$voteAllkeys)){
		
	}else{
		array_push($voteAllkeys,$self_key);
		$memcache->set($vote_game_keys,serialize($voteAllkeys));
		$ary=array($voter);
		$memcache->set($self_key,serialize($ary));
	}
	
	
}elseif($operation=='getvoteresult'){
	
}