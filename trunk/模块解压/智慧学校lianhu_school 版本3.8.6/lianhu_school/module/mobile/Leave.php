<?php 
defined('IN_IA') or exit('Access Denied');

$result=$this->mobile_from_find_student();
if($op=='list'){
  if($_GPC['time_date_begin']){
      $time_begin   = strtotime($_GPC['time_date_begin']);
      if(!$_GPC['time_date_end'])
           $time_end=$time_begin+3600*24;
      else 
           $time_end=strtotime($_GPC['time_date_end']);
      if($time_end<$time_begin)
        message("请假的结束时间小于开始时间",$this->createMobileUrl('leave',array('op'=>'get')),'success');
      $in['member_uid']     =$uid;
      $in['student_id']     =$result['student_id'];
      $in['class_id']       =$result['class_id'];
      $in['teacher_id']     =$result['teacher_id'];
      $in['leave_reason']   =$_GPC['leave_reason'];
      $in['time_date_begin']=$time_begin;
      $in['time_date_end']  =$time_end;
      $in['add_time']=time();
      pdo_insert('lianhu_leave',$in);
      message("提交成功！",$this->createMobileUrl('leave',array('op'=>'get')),'success');
  }
}
if($op=='get'){
    $list=pdo_fetchall("select * from {$table_pe}lianhu_leave where student_id=:sid order by leave_id desc",array(":sid"=>$result['student_id']));
}
        