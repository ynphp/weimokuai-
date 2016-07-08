<?php
global $_W,$_GPC;
$cj_table = 'meepo_paipaile_cj';
if($_W['isajax']){
	$actid = intval($_GPC['actid']);
	$rid = intval($_GPC['rid']);
	$zjuser = $_GPC['zjuser'];
	if(is_array($zjuser) && !empty($zjuser)){
			$data = array();
			$data['weid'] = $_W['uniacid'];
			$data['actid'] = $actid;
			$data['createtime'] = time();
			$data['rid'] = $rid;
			if($_W['account']['level'] > 2){
			   load()->classs('weixin.account');
				 $accObj= WeixinAccount::create($_W['account']['acid']);
				 $access_token = $accObj->fetch_token();
			}
			foreach($zjuser as $key=>$row){
					$data['openid'] = $row;
					pdo_insert($cj_table,$data);
					if(!empty($access_token)){
					 //$num = $key + 1;
					 $content = '恭喜恭喜、您中等奖了！';
					 sendmessage($access_token,$row,$content);
					}
			}
	}
}
function sendmessage($access_token,$touser,$content){		       
										$data = '{
										"touser":"'.$touser.'",
										"msgtype":"text",
										"text":
										{
										"content":"'.$content.'"
										}
										}';
							  load()->func('communication');
							  $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
							  ihttp_post($url, $data);
					
   }