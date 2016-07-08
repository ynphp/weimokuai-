<?php
/**
 * 捷讯活动平台模块处理程序
 *
 * @author 捷讯设计
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class J_moneyModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$openid = $this->message['from'];
		$rid = $this->rule;
		$reStr="";
		$firstAttend=pdo_fetch("SELECT * FROM ".tablename('j_money_marketing')." WHERE weid='{$_W['uniacid']}' and `condition`=4 and status=1 order by displayorder asc ,id desc limit 1");
		$cfg = $this->module['config'];
		if($firstAttend){
			$isget=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_money_reward')." WHERE weid=:a and marketid=:c and openid=:b",array(':a'=>$_W['uniacid'],':b'=>$openid,':c'=>$firstAttend['id']));
			if(!$isget){
				$flag=true;
				if($firstAttend['num']){
					
					$sql="SELECT count(*) FROM ".tablename('j_money_reward')." WHERE weid=:a and marketid=:b ";
					if(!$firstAttend['isallnum']){
						$sql="SELECT count(*) FROM ".tablename('j_money_reward')." WHERE weid=:a and marketid=:b and createtime>".strtotime(date("Y-m-d")." 00:00:00")." and createtime<".strtotime(date("Y-m-d")." 23:59:59")." ";
					}
					$allnum=pdo_fetchcolumn($sql,array(':a'=>$_W['uniacid'],':b'=>$firstAttend['id']));
					$flag=$firstAttend['num']>$allnum ? true : false;
				}
				
				if($flag){
					
					switch($firstAttend['favorabletype']){
						case 3:
							
							
							if(strpos($firstAttend['favorable'],"|#卡券#|")){
								$temp=str_replace("[|#卡券#|","",$firstAttend['favorable']);
								$temp=str_replace("]","",$temp);
								$favorAry=strpos($temp,",") ? explode(",",$temp) : array($temp);
								shuffle($favorAry);
								$cardkey=$favorAry[0];
								$wxcard=json_decode($cfg['wxcard'],true);
								
								if($wxcard[$cardkey]){
									$data=array(
										'marketid'=>$firstAttend['id'],
										'favorabletype'=>3,
										'weid'=>$_W['uniacid'],
										"openid"=>$openid,
										'favorable'=>$firstAttend['favorable'],
										'condition'=>$firstAttend['condition'],
										'reward'=>$wxcard[$cardkey],
										'status'=>0,
										'gettype'=>1,
										"createtime"=>TIMESTAMP,
										'log'=>'获得卡券',
									);
									pdo_insert("j_money_reward",$data);
								}
							}
						break;
						case 4:
							if(strpos($firstAttend['favorable'],"|#抽奖#|")){
								$temp=str_replace("[|#抽奖#|","",$firstAttend['favorable']);
								$temp=str_replace("]","",$temp);
								$favorAry=intval($temp);
								if($favorAry){
									$data=array(
										'marketid'=>$firstAttend['id'],
										'favorabletype'=>4,
										'weid'=>$_W['uniacid'],
										"openid"=>$openid,
										'favorable'=>$firstAttend['favorable'],
										'condition'=>$firstAttend['condition'],
										'reward'=>$favorAry,
										'status'=>1,
										'gettype'=>1,
										"createtime"=>TIMESTAMP,
										"endtime"=>TIMESTAMP,
										'log'=>'获得'.$favorAry.'次抽奖机会',
									);
									pdo_insert("j_money_reward",$data);
									$insert=array(
										'weid'=>$_W['uniacid'],
										'gid'=>$firstAttend['gid'],
										'from_user'=>$openid,
									);
									for($i=0;$i<$favorAry;$i++){
										pdo_insert("j_money_lottery",$insert);
									}
								}
							}
						break;
					}
				}
			}
		}
		$flag=false;
		$list=pdo_fetchall("SELECT * FROM ".tablename('j_money_reward')." WHERE openid=:a and status=0 and gettype=1 limit 5",array(':a'=>$openid));
		if($list){
			$flag=true;
			foreach($list as $row){
				switch($row['favorabletype']){
					case 2:
						//获得红包
						$reStr="恭喜您，获得红包一个";
						$this->_sendpack($openid,$row,$cfg);
						$this->_sendText($openid,$reStr);
					break;
					case 3:
						//活动卡券
						$reStr="恭喜您，获得卡券一张";
						pdo_update("j_money_reward",array("status"=>1,"endtime"=>TIMESTAMP),array("id"=>$row['id']));
						$this->sendCard($openid,$row['reward']);
						$this->_sendText($openid,$reStr);
					break;
				}
			}
		}
		
		$count=pdo_fetchcolumn("SELECT count(*) FROM ".tablename('j_money_lottery')." WHERE from_user=:a and `createtime`=0",array(':a'=>$openid));
		if($count){
			$flag=true;
			$gid=pdo_fetchcolumn("SELECT gid FROM ".tablename('j_money_lottery')." WHERE from_user=:a and `createtime`=0 group by gid order by gid desc limit 1",array(':a'=>$openid));
			$game=pdo_fetch("SELECT * FROM ".tablename('j_money_lottery')." WHERE id=:a ",array(':a'=>$gid));
			if(!$game || !$game['status'])goto end;
			if($game['starttime']>TIMESTAMP || $game['endtime']<TIMESTAMP)goto end;
			
			$reStr="您还有抽奖机会没有使用哦\n<a href='".$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&id=".$gid."&do=game&m=j_money'>点击进入抽奖</a>";
			$this->_sendText($openid,$reStr);
		}
		end:
		/**/
		if($flag){
			return $this->respText("再次感谢您关注我们");
		}
	}
	//发送信息
	public function _sendText($openid,$txt){
		global $_W;
		$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'text','text'=>array('content'=>urlencode($txt))));
		return $data;
	}
	//----
	/*
	* 发送红包
	*/
	public function _sendpack($openid,$item=array(),$cfg=array()){
		global $_W;
		$fee=$item['reward'];
		if($fee<100)return false;
 		$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
		$pars['nonce_str'] =random(32);
		$pars['mch_billno'] =$cfg['mchid'].date('YmdHis').rand(1000, 9999);
		$pars['mch_id']=$cfg['pay_mchid'];
		$pars['wxappid'] =$cfg['appid'];
		$pars['send_name'] =$cfg['pay_name'];
		$pars['re_openid'] =$openid;
		$pars['total_amount'] =$fee;
		$pars['total_num'] =1;
		$pars['wishing'] ="微信支付，便捷生活";
		$pars['client_ip'] =$cfg['pay_ip'];
		$pars['act_name'] =$cfg['pay_name'];
		$pars['remark'] =$cfg['pay_name'];
        ksort($pars, SORT_STRING);
        $string1='';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key=".$cfg['pay_signkey'];
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = IA_ROOT.'/attachment/j_money/cert_2/'.$_W['uniacid'].'/'.$cfg['rootca'];
        $extras['CURLOPT_SSLCERT'] =IA_ROOT.'/attachment/j_money/cert_2/'.$_W['uniacid'].'/'.$cfg['apiclient_cert'];
        $extras['CURLOPT_SSLKEY'] =IA_ROOT.'/attachment/j_money/cert_2/'.$_W['uniacid'].'/'.$cfg['apiclient_key'];
		$procResult = null;
		load()->func('communication');
        $resp = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult = $resp;
        } else {
			$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if ($dom->loadXML($xml)){
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult =  array('errno'=>0,'error'=>'success');
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = array('errno'=>-2,'error'=>$error);
                }
            } else {
				$procResult = array('errno'=>-1,'error'=>'未知错误');				
            }
        }
		$rec = array();
		$rec['log'] = $error;
		$rec['completed']=$rec['status']=$procResult['errno']!=0 ? $procResult['errno'] :1;
		if($rec['completed']==1)$rec['endtime']=TIMESTAMP;
        pdo_update('j_money_reward',$rec,array('id'=>$item['id']));
		return $procResult;
	}
	/*
	* 卡券
	*/
	public function sendCard($openid,$cardid){
		global $_W;
		if(strlen($cardid)<5)return false;
		$acid=$_W['account']['acid'];
		if(!$acid){
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
		}
		$acc = WeAccount::create($acid);
		$ticket=$this->getCard();
		$pars['nonce_str'] =random(32);
		$pars['code'] ="";
		$pars['openid'] =$openid;
		$pars['timestamp'] =TIMESTAMP;
		$pars['signature'] ="";
		//timestamp,nonce_str,code,ticket,card_id
        $string1=$pars['timestamp'].$pars['nonce_str'].$pars['code'].$ticket.$cardid;
		$pars['signature']=sha1($string1);
		$sendata=array(
			"card_id"=>$cardid,
			"card_ext"=>array(
				"code"=>$pars['code'],
				"openid"=>$pars['openid'],
				"timestamp"=>$pars['timestamp'],
				"signature"=>$pars['signature'],
			)
		);
		$data = $acc->sendCustomNotice(array('touser'=>$openid,'msgtype'=>'wxcard','wxcard'=>$sendata));
		return $data;
	}
	public function getCard(){
		global $_W;
		load()->func('cache');
		$wxcard=cache_load("wxcard");
		if(!$wxcard || $wxcard['extime']<TIMESTAMP){
			load()->func('communication');
			$acid=pdo_fetchcolumn("SELECT acid FROM ".tablename('account')." WHERE uniacid=:uniacid ",array(':uniacid'=>$_W['uniacid']));
			$acc = WeAccount::create($acid);
			$tokens=$acc->fetch_token();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$tokens."&type=wx_card";
			$content = ihttp_get($url);
			if(is_error($content))return false;
			$token = @json_decode($content['content'], true);
			if(empty($token) || !is_array($token)) {
				$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
				$errorinfo = @json_decode($errorinfo, true);
				return false;
			}
			$record = array();
			$record['ticket'] = $token['ticket'];
			$record['expire'] = TIMESTAMP + $token['expires_in'];
			cache_write("wxcard",array("ticket"=>$record['ticket'],"expire"=>$record['expire']));
			return $record['ticket'];
		}
		return $wxcard['ticket'];
	}
}