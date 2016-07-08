<?php
/**
 * 我的订单
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;

$openid = $_W['openid'];
$op = $_GPC['op'];

$member = m('member')->get_member($openid);

if ($member['level'] <= 0){
    message('抱歉您还未购买升级,无法查看【我的红包】',$this->createMobileUrl('index'),'warning');
    exit;
}
if ($op == 'hongbao'){
    $depositId = $_GPC['depositid'];
    $deposits =  m('deposit')->get_deposit($depositId);
    $settings = $this->module['config'];
    if (!empty($deposits)){
        if ($member['level'] > 0){
        if($deposits['state'] != 1){
            if($deposits['openid'] == $_W['openid']){
                if(m('deposit')->update_deposit($depositId,array('state'=>1))){
                    
        $info = array(
	           'mch_billno' => $deposits['depositsn'],
	           'act_name'=>$settings['act_name'],
	           'openid'=>$_W['openid'],
	           'amount'=>$deposits['amount'],
	           'wishing'=> '来自【'.$deposits['from_nickname'].'】的红包');
                    $ResTX = sendTransfer($info,$settings);
                    $ResWeChat = json_decode($ResTX);
                    if($ResWeChat->{'result_code'} == 'SUCCESS'){
                        $response = array('s' => 'ok', 'msg' => '恭喜您,红包领取成功！请查收');
                    
                    }else {
                        m('deposit')->update_deposit($depositId,array('state'=>2));
                        $response = array('s' => 'no', 'msg' => $ResWeChat->{'return_msg'});
                    }
                }else{
                    $response = array('s' => 'no', 'msg' => '抱歉,领取失败,您领取红包存在异常！');
                }
            }else {
                $response = array('s' => 'no', 'msg' => '抱歉,领取失败,您用户信息存在异常！');
            }
            
        }else{
            $response = array('s' => 'ok', 'msg' => '抱歉,领取失败,您的红包已领取！');
        }
        }else {
            $response = array('s' => 'no', 'msg' => '抱歉,领取失败,您还不是VIP！');
        }
    }else {
    $response = array('s' => 'no', 'msg' => '抱歉,领取失败,您的红包记录不存在！');
    }
    echo json_encode($response);
}else {

$mydeposit = m('deposit')->get_depositlist($_W['openid']);

$mydeposit2 = m('deposit')->get_depositlist2($_W['openid']);

include $this->template('myorder');
}