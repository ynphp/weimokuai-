<?php
/**
 * 失物招领模块处理程序
 *
 * @author Yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yoby_lostModuleProcessor extends WeModuleProcessor {
	public function respond() {
	global $_W;
		$content = $this->message['content'];
		$openid = $this->message['from'];
		$weid = $_W['uniacid'];
		preg_match('/(?:失物|寻物|认领|招领)(.*)/', $content, $matchs);
		$m = mb_substr($content,0,2,'utf-8');
		if($m=="失物" || $m=="寻物"){
        $key = $matchs[1];
        $condition =" and type=0  and   content like '%$key%'  ";
			$rss = pdo_fetchall("SELECT * FROM ".tablename('lost')." where weid=$weid  $condition ORDER   BY  id  DESC LIMIT 6 ");
				$news[0] = array(
				'title' =>"失物/寻物查找{$key}结果",
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('fm'),
);
		
		}elseif($m=="认领" || $m=="招领"){
	        $key = $matchs[1];
        $condition =" and type=1 and content like '%$key%'  ";
			$rss = pdo_fetchall("SELECT * FROM ".tablename('lost')." where weid=$weid  $condition ORDER   BY  id  DESC LIMIT 6 ");
						$news[0] = array(
				'title' =>"认领/招领查找{$key}结果",
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('fm'),
);
		}else{
		   $condition =" ";
			$rss= pdo_fetchall("SELECT * FROM ".tablename('lost')." where weid=$weid  $condition ORDER   BY  id  DESC LIMIT 6 ");
						$news[0] = array(
				'title' =>"最新失物招领",
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('fm'),
);
		}

if(!empty($rss)){		
		
	foreach($rss as $list){
$str = "时间: ".$list['createtime']."\n";
$str.= "联系人: ".$list['xm']."\n";
$str.= "手机/电话: ".$list['mobile']."\n";
$str.= "详情: ".$list['content'];

	$news[] = array(
				'title' =>$str,
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('chakan',array('id'=>$list['id'])),
);
}	
}else{
	$news[] = array(
				'title' =>'暂无您要搜索的信息 点击进入发布寻物/招领启事吧',
				'description' =>'',
				'picurl' =>'',
				'url' =>$this->createMobileUrl('fabu'),
);

}
return $this->respNews($news);
		
	}
}