<?php
global $_W,$_GPC;
$this->checkMobile();
require_once(MODULE_ROOT.'/module/Order.class.php');
require_once(MODULE_ROOT.'/module/Activity.class.php');
load()->model('mc');
//参数验证
$op = $_GPC['op'];
$id = $_GPC['id'];
$isdo = $_GPC['isdo'];
if(empty($op) || empty($id)){
    message('参数错误','','error');
    exit();
}
//订单验证
$order = new Order();
$check = $order->checkOrder($id,$op);
if(!$check){
    message('票据错误','','error');
    exit();
}
if(!empty($check['scanown'])){
    $faninfo = mc_fansinfo($check['scanown']);
    $check['checkname'] = $faninfo['nickname'];
}
//加入购票人信息
$buyopenid = $check['openid'];
$buyuid = mc_openid2uid($buyopenid);
$buyinfo = mc_fetch($buyuid,array('groupid','nickname','mobile','realname','email','avatar'));
//验证权限
$act = new Activity();
$actdata = $act->getOne($check['actid']);
if(!$actdata){
    message('活动数据错误','','error');
    exit();
}
$grouparr = unserialize($actdata['groups']);
$uid = $_W['member']['uid'];
$meminfo = mc_fetch($uid,array('groupid','nickname','mobile','realname','email','avatar'));
$groupid = $meminfo['groupid'];
$meminfo['avatar'] = tomedia($meminfo['avatar']);
if(!in_array($groupid,$grouparr)){
    message('你没有验票权限','','error');
    exit();
}
//验票
if($isdo==1){
    $chres = $order->checkOrder($id,$op,2);
    if($chres){
        $update = array();
        $update['status'] = 3;
        $update['scantime'] = TIMESTAMP;
        $update['scanown'] = $_W['fans']['from_user'];
        $res = $order->modify($id,$update);
        if($res){
            if (!empty($this->module['config']['template'])) {
					$good = $actdata['proname'];
					$nums = '1';
					$data = array (
						'first' => array('value' => '您的票据已核销通知'),
						'keyword1' => array('value' => $good),
						'keyword2' => array('value' => $nums),
						'keyword3' => array('value' => date('Y-m-d H:i',strtotime('now')))
					);
					$acc = WeAccount::create($_W['acid']);
					$acc->sendTplNotice($check['openid'],$this->module['config']['templateidhx'],$data);
				}
            message('票据已核销成功','refresh','success');
        }else{
            message('票据核销失败','refresh','error');
        }
    }
}
//展示票据信息
include $this->template('master');



