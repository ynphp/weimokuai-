<?php 
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);
$in_type    =$this->judePortType();
if(empty($in_type))   
     message('您还未绑定',$this->createMobileUrl('home'),'error');
if($in_type['type'] !='teacher')
{
    $result=$this->mobile_from_find_student();
    $class_id=$result['class_id'];
}
else
    $class_id=$_GPC['class_id'];
#begin
if($_POST['submit']){
    if($_POST['content']){
    #解析图片
    $img_arrs=$_POST['img_value'];
    if($img_arrs){
        foreach ($img_arrs as $key => $value) {
                $img_in[]=$this->getWechatMedia($value); 
        }
    }
        $in['uniacid']      =$_W['uniacid'];
        $in['school_id']    =$_SESSION['school_id'];
        $in['class_id']     =$class_id;
        $in['send_uid']     =$uid;
        $in['send_content'] =$_POST['content'];
        if($img_in)
            $in['send_image']   =serialize($img_in);
        if($this->getSchoolLineStatus()==2 && $in_type['type'] !='teacher')
            $in['send_status']  =2;//审核
        else 
            $in['send_status']  =1;
        $in['add_time']     =time();
        pdo_insert('lianhu_send',$in);
        if($in_type['type'] !='teacher')
               header("Location:".$this->createMobileUrl('line'));
        else 
            header("Location:".$this->createMobileUrl('line',array('class_id'=>$class_id) ));
    }else{
        message("没有分享内容", $this->createMobileUrl('line',array('class_id'=>$class_id)),'error');
    }
}