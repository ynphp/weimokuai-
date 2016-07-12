<?php
/**
 * 微树洞模块处理程序
 *
 * @author yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yoby_sdModuleProcessor extends WeModuleProcessor {
public function respond() {
		global $_W;
		$content = $this->message['content'];
		$openid = $this->message['from'];
		$weid = $_W['uniacid'];
		$img =$this->module['config']['img1'];
	 preg_match('/^#(.*)/', $content, $matchs);
		

$key =trim($matchs[1]);
if(strlen($key)<15){
	
	return $this->respText("发送失败啦,不能少于5个字");
}

$arrcolor = array('1A531E','1E0155','FF7F00','AB291C','8E8460','017BBE');
$y = array_rand($arrcolor);		
$bgcolor = $arrcolor[$y];

$bid = pdo_fetchcolumn("SELECT  count(*)  FROM ".tablename('yoby_sd')."  where  weid={$weid}");//计算记录数
		$bid = $bid+1;
		$data = array(
			'weid'=>$weid,
			'content'=>$key,
			'bgcolor'=>$bgcolor,
			'createtime'=>time(),
			'openid'=>$openid,
			'bid'=>$bid,
		);
	pdo_insert('yoby_sd', $data);
	$news[0] = array(
				'title' =>'最新树洞内容,微信界面使用#开头也可以发树洞',
				'description' =>'',
				'picurl' =>$img,
				'url' =>$_W['siteroot'].$this->createMobileUrl('index'),
);
$rs =  pdo_fetchall("select * from ".tablename('yoby_sd')." where weid=$weid order by id desc  limit 8");
foreach($rs as $list){

	$news[] = array(
				'title' =>cutstr($list['content'], 30),
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('say',array('id'=>$list['id'])),
);
}	

	$news[] = array(
				'title' =>'更多>>',
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('index'),
);

return $this->respNews($news);		
	}
}