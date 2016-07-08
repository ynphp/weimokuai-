<?php 
defined('IN_IA') or exit('Access Denied');
$hash_add_str='asdas;#sdf';
$this->teacher_mobile_qx();
$signPackage=$this->getSignPackage();

$student_re=pdo_fetch("select * from {$table_pe}lianhu_student where student_id=:id",array(':id'=>$_GPC['sid']));
$hash_str=sha1(md5($student_re['class_id'].$student_re['grade_id'].$student_re['xuhao'].$hash_add_str));

if($hash_str!=$_GPC['hash'])
    message("学生代码不正确，非法二维码",$this->createMobileUrl('teacenter'),'error');
$url=$this->createMobileUrl('studentlLive',array('hash'=>$hash_str,'sid'=>$_GPC['sid'],'live_in'=>'live'));
$live_in=true;
if(!$_GPC['live_in']){
    $live_in=false;
}
$type='live_in=out';
if($_GPC['live_in']=='in'){
    $url=$this->createMobileUrl('studentIn',array('hash'=>$hash_str,'sid'=>$_GPC['sid'],'live_in'=>'in'));
    header('Location:'.$url."&send".$_GPC['send']);
    exit();
}
if($_GPC['send']==1){
    #放学操作
    $acid=pdo_fetchcolumn("select acid from ".tablename('account')." where uniacid={$_W['uniacid']}");
    load()->classs('weixin.account');
    $accObj= WeixinAccount::create($acid);
    
    $in['school_id']=$_SESSION['school_id'];
    $in['uniacid']=$_W['uniacid'];
    $in['teacher_id']=$_SESSION['teacher_id'];
    $in['addtime']=TIMESTAMP;
    $in['student_id']=$student_re['student_id'];
    pdo_insert('lianhu_student_live',$in);
    
    $openids=$this->returnEfficeOpenid($student_re,3);
    foreach ($openids as $key => $value) {
        $first=$student_re['student_name'].'的家长您好，您的孩子离校啦！';
        $key2 = $teacher_info['teacher_realname'];
        $key4 = "您的孩子离校啦！";
        $remark="祝您愉快！";
        $mu_info =$this->decodeMsg1($first,$key2,$key4 ,$remark );
        $accObj->sendTplNotice($value,$mu_info['mu_id'],$mu_info['data'] );
    }
    $have_send=1;
}