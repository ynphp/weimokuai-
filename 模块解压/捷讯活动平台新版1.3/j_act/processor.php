<?php
/**
 * 捷讯活动平台模块处理程序
 *
 * @author 捷讯设计
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class J_actModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$openid=$this->message['from'];
		$rid=$this->rule;
		$keyword=$this->message['content'];
		$reply = pdo_fetch("SELECT * FROM ".tablename('j_act_reply')." WHERE rid = :rid", array(':rid' =>$rid));
		$repeatWord = pdo_fetch("SELECT content FROM ".tablename('rule_keyword')." WHERE rid = :rid", array(':rid' =>$rid));
		$repeatWord=str_replace("^","",$repeatWord);
		$aid=$reply['aid'];
		if (!empty($reply)) {
			switch($reply['atype']){
				case 0:
					$url=$this->createMobileUrl('view',array('id'=>$reply['aid']));
					$response[] = array(
						'title' => $reply['title'],
						'description' => $reply['description'],
						'picurl' => $_W['attachurl'].$reply['cover'],
						'url' => $url
					);
					return $this->respNews($response);
				break;
				case 1:
					//现场投票
					include(MODULE_ROOT.'/inc/redisDB.php');
					$vote_Redis_reply="j_act_vote_".$_W['uniacid']."_".$aid;
					$vote_Redis_item="j_act_vote_item".$_W['uniacid']."_".$aid;
					$vote_Redis_list="j_act_vote_list".$_W['uniacid']."_".$aid;
					
					$redis=new redisDB();
					$voteGamge=$redis->get($vote_Redis_reply);
					if(!$voteGamge)return $this->respText("暂无投票活动".$voteGamge->id);
					if($voteGamge->status!=1)return $this->respText("投票活动终止了哦");
					if(TIMESTAMP < $voteGamge->starttime)return $this->respText("投票活动还没有开始哦");
					if(TIMESTAMP > $voteGamge->endtime)return $this->respText("投票活动已经结束了哦");
					$votekey=intval(str_replace($repeatWord,"",$keyword));
					if(!$votekey)return $this->respText("投票格式错误，格式应该是：".$repeatWord."数字。数字为您要投票的对象");
					$list_item=$redis->getrange($vote_Redis_item);
					$ary_item=array();
					foreach($list_item as $row){
						$row2=json_decode($row,true);
						$ary_item[$row2['votekey']]=$row2['id'];
					}
					if(!isset($ary_item[$votekey]))return $this->respText("投票对象错了哦。看清楚了再投票哦");
					
					$fansinfo=$this->_getFansInfo($openid);
					$data=array(
						'openid'=>$openid,
						'nickname'=>$fansinfo['nickname'],
						'avatar'=>$fansinfo['headimgurl'],
						'votekey'=>$votekey,
					);
					$user_key="j_act_voter_".$_W['uniacid']."_".$aid."_".$openid;
					$keys=$redis->get($user_key);
					if($keys){
						$hasVote=count($keys);
						$votetime=$voteGamge->votetime ? $voteGamge->votetime : 1;
						if($hasVote>=$votetime)return $this->respText("您已经过票了哦");
						array_push($keys,$votekey);
					}else{
						$keys=array($votekey);
					}
					$redis->set($user_key,$keys);
					$redis->push($vote_Redis_list,$data);
					$backword=$voteGamge->msg ? $voteGamge->msg :"投票成功！感谢您的参与！";
					return $this->respText($backword);
				break;
				case 2:
					//现场问答
					include(MODULE_ROOT.'/inc/redisDB.php');
					$ask_Redis_reply="j_act_ask_".$_W['uniacid']."_".$aid;
					$ask_Redis_list="j_act_ask_list".$_W['uniacid']."_".$aid;
					$ask_Redis_asker="j_act_asker_".$_W['uniacid']."_".$aid."_*";
					$ask_Redis_keyid="j_act_ask_id_".$_W['uniacid']."_".$aid;
					
					$redis=new redisDB();
					if(!$redis->exists($ask_Redis_reply))return $this->respText("暂无现场问答活动");
					$askGamge=$redis->get($ask_Redis_reply);
					if($askGamge->status!=1)return $this->respText("现场问答活动停止了哦");
					$askkey=$keyword;
					//过滤
					
					//---
					$fansinfo=$this->_getFansInfo($openid);
					$data=array(
						'openid'=>$openid,
						'nickname'=>$fansinfo['nickname'],
						'avatar'=>$fansinfo['headimgurl'],
						'content'=>$askkey,
					);
					$user_key="j_act_asker_".$_W['uniacid']."_".$aid."_".$openid;
					$keys=array();
					if($redis->exists($user_key)){
						$keys=$redis->get($user_key);
					}
					$hasask=count($keys);
					$asktime=$askGamge->sendnum ? $askGamge->sendnum : 1;
					if($hasask>=$asktime)return $this->respText("每人只能提问".$asktime."次哦。");
					array_push($keys,$askkey);
					$redis->set($user_key,$keys);
					
					if(!$redis->exists($ask_Redis_keyid))$redis->set($ask_Redis_keyid,0);
					$redis->incr($ask_Redis_keyid);
					$data['showid']=$redis->get($ask_Redis_keyid);
					
					$redis->push($ask_Redis_list,$data);
					$backword=$askGamge->answer ? $askGamge->answer :"感谢您宝贵的意见哦！";
					return $this->respText($backword);
				break;
			}
			
		}
	}
	private function _getFansInfo($_openid){
		global $_W;
		if(!$_openid)return false;
		load()->func('communication');
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$acc = WeAccount::create($acid);
		$tokens=$acc->fetch_token();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$tokens."&openid=".$_openid;
		$content = ihttp_get($url);
		if(is_error($content))return false;
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token)) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return false;
		}
		return $token;
	}
}