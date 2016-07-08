<?php
/**
 * 捷讯乐分享模块处理程序
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_shareModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid=$this->rule;
		$openid = $this->message['from'];
		$reply = pdo_fetch("SELECT * FROM ".tablename('j_share_reply')." WHERE rid = :rid", array(':rid' =>$rid));
		$response[] = array(
			'title' => urlencode($reply['title']),
			'description' => urlencode($reply['description']),
			'url' => $reply['url'],
			'picurl' => '',
		);
		$a=$this->_sendText($openid,$response);
		//return $this->respText(implode("##",$a));
	}
	private function _sendText($openid,$ary){
		global $_W;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$token = $acc->fetch_token();
		
		$postarr=array(
			"touser"=>$openid,
			"msgtype"=>"news",
			"news"=>array("articles"=>$ary)
		);
		load()->func('communication');
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
		$content = ihttp_post($url,urldecode(json_encode($postarr)));
		if(is_error($content))return 0;
		$result = @json_decode($content['content'], true);
		return $result;
	}
}