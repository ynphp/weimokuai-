<?php
defined('IN_IA') or exit('Access Denied');
$teacher_info   =$this->teacher_mobile_qx();
$_W['uid'] 	    =$teacher_info['fanid'];
$result         =pdo_fetchall("select * from {$table_pe}lianhu_class where  status=1 and teacher_id=:tid",array(':tid'=>$teacher_info['teacher_id']) );

if(empty($result)){ echo json_encode(array('msg'=>'您不是班主任'));exit(); }
foreach ($result as $key => $value) {
	$class_id_arr[$key]=$value['class_id'];
}
$class_id_str  =implode(',', $class_id_arr);
$student_list  =pdo_fetchall("select * from {$table_pe}lianhu_student where class_id in({$class_id_str}) and status=1");
$acid=pdo_fetchcolumn("select acid from ".tablename('account')." where uniacid=:uid",array(":uid"=>$_W['uniacid']));
load()->classs('weixin.account');
$accObj= WeixinAccount::create($acid);
$i=0;
$que_num=FALSE;
foreach ($student_list as $key => $value) {
	#遍历and发送
	$openids=$this->returnEfficeOpenid($value,3);
        $first=$value['student_name'].'的家长您好，请您注意，学校放学了';
        $key2 = $teacher_info['teacher_realname'];
        $key4 = "放学通知";
        $remark="祝您愉快！";
        $mu_info =$this->decodeMsg1($first,$key2,$key4 ,$remark );
         foreach ($openids as $key => $v) {
              if($v)  $que_num=$this->insertMsgQueueMu($v,$mu_info['data'],$mu_info['mu_id'],false,$que_num);
	   }    							
}
// message('添加成功，跳转往发送页面，请勿关闭',$this->createMobileUrl('sendToMsg',array('que_num'=>$que_num)),'success');
header("Location:".$this->createMobileUrl('sendToMsg',array('que_num'=>$que_num)));
exit();