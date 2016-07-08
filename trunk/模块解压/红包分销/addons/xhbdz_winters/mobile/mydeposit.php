<?php
/**
 * 提现申请
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;

$uniacid = $_W['uniacid'];//公众号ID
//APPid
$appid = $_W['cache']["uniaccount:$uniacid"]['key'];
$op = $_GPC['op'];
$openid = $_W['openid'];

$member = m('member')->get_member($openid);

if ($op == 'edit') {
    $data = array(
        'depositsn' =>date('YmdHis',time()).mt_rand(1000,9999),
        'wechat'=>$_GPC['wechat'],
        'truename'=>$_GPC['truename'],
        'mobile'=>$_GPC['mobile'],
        'amount'=>$_GPC['amount'],
        'createtime'=>time()  
        );
    
    $settings = $this->module['config'];

    if(!empty($data)){
        if($member['amount'] >= $data['amount']){
                $data['appid'] = $appid;
                $data['openid'] = $_W['openid'];
                if(m('member')->update_memAm(0,array('amount'=>'-'.$data['amount']),$openid)){
                    $info = array(
                        'mch_billno' => $data['depositsn'],
                        'act_name'=>$settings['act_name'],
                        'openid'=>$openid,
                        'amount'=>$data['amount'],
                        'wishing'=> $settings['wishing']);
                    
                    $ResTX = sendTransfer($info);
                    $ResWeChat = json_decode($ResTX);
                    if($ResWeChat->{'result_code'} == 'SUCCESS'){
                        $data['state'] = 1;
                        m('deposit')->add_depositlog($data);
                        message('红包发送成功！！',$this->createMobileUrl('myorder'),'success');
                    }else {
                        m('deposit')->add_depositlog($data);
                        message('提现申请成功！',$this->createMobileUrl('myorder'),'success');
                    }

                }else {
                    message('扣款失败,请重新发起提现!',$this->createMobileUrl('mydeposit'),'warning');
                }
                
        }else {
            message('抱歉,您最大可发送红包'.$member['amount'].'元',$this->createMobileUrl('mydeposit'),'warning');
        }
        
    }

}

include $this->template('mydeposit');
