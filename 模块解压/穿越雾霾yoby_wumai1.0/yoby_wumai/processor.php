<?php
/**
 * 穿越雾埋模块处理程序
 *
 * @author Yoby
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Yoby_wumaiModuleProcessor extends WeModuleProcessor {
public function respond() {
 		global $_W;
       	 $rid = $this->rule;
        	$sql = "SELECT *  FROM " . tablename('yoby_wumai_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
	$now = time();	
	
	if ($row['start_time'] > $now) {
            return $this->respText("活动未开始,开始时间是:".date('Y-m-d H:i:s',$row['start_time'])."请耐心等待!");
        }
	if($row['end_time'] < $now)	{
		  return $this->respNews(array(
                        'Title' =>'活动结束啦',
                        'Description' => "活动已结束,下次早点来,点击查看排名!",
                        'PicUrl' => tomedia($row['hd_img']),
                        'Url' =>$this->createMobileUrl('rank', array('id' => $rid)),
            ));
	}else{
		return $this->respNews(array(
                        'Title' => $row['hd_title'],
                        'Description' => $row['hd_desc'],
                        'PicUrl' => tomedia($row['hd_img']),
                        'Url' => $this->createMobileUrl('index', array('id' => $rid)),
            ));
	}
		
	}
}