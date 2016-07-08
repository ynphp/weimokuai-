<?php
/**
 * 个人中心
 * 
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W,$_GPC;
$openid = $_W['openid'];
$op = $_GPC['op'];

$member = m('member')->get_member($openid);

$memFans = m('member')->get_fanslist($member['uid']);


if (!empty($member['parent4'])){
    $direct = m('member')->get_member(0,$member['parent1']);
}

$settings = $this->module['config'];

$isvip = $settings['name'.$member['level']];


//普卡
$parent1 = m('member')->get_parents('parent1',$member['uid']);
//银卡
$parent2 = m('member')->get_parents('parent2',$member['uid']);
//金卡
$parent3 = m('member')->get_parents('parent3',$member['uid']);

$parent1Num = $parent2Num = $parent3Num = 0;
if (!empty($parent1)){
$parent1Num = count($parent1);
}
if (!empty($parent2)){
$parent2Num = count($parent2);
}
if (!empty($parent3)){
$parent3Num = count($parent3);
}

$sex = $_W['fans']['tag']['sex']==1?'class="am-icon-male"':'class="am-icon-female" style="color:pink"';
if ($op == 'edit'){
    
    $data = array(
    'truename' => $_GPC['truename'],
    'wechat' => $_GPC['wechat'],
    'mobile' => $_GPC['mobile']
    );
    $response = array('s' => 'ok', 'msg' => '抱歉,个人资料修改失败!');
    if (empty($data['truename'])) {
        $response = array('s' => 'no', 'msg' => '请输入您的真实姓名！');
    }
    elseif (empty($data['wechat'])) {
        $response = array('s' => 'no', 'msg' => '请输入您的微信号码');
    }
    elseif (empty($data['mobile'])) {
        $response = array('s' => 'no', 'msg' => '请输入您的手机号码');
    }else{
     if(m('member')->update_member($member['uid'],$data)){
    $response = array('s' => 'ok', 'msg' => '个人资料修改成功!');
        }
}
    echo json_encode($response);
    
    
    
    
}else {
include $this->template('center');
}