<?php 
defined('IN_IA') or exit('Access Denied');
$teacher_info=$this->teacher_mobile_qx();
if($ac=='list'){
  $list   =pdo_fetchall("select * from {$table_pe}lianhu_leave where teacher_id=:tid and leave_status=1 order by add_time desc ",array(":tid"=>$teacher_info['teacher_id']) );
}
if($ac=='edit'){
    $where[":id"]=$_GPC['id'];
    $result=pdo_fetch("select * from {$table_pe}lianhu_leave where leave_id=:id ",$where);
    if($_GPC['teacher_text']){
       $up['teacher_text'] =$_GPC['teacher_text'];
       if($_GPC['submit']=='准许')
            $up['leave_status'] =2;
       else 
            $up['leave_status'] =3;
       
       pdo_update('lianhu_leave',$up,array('leave_id'=>$_GPC['id']));
      $this->sendLeaveMsg($_GPC['id']);
      message("处理成功",$this->createMobileUrl('teacenter'),'success');
    }
}
