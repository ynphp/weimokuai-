<?php
defined('IN_IA') or exit('Access Denied');
#��ʦ����ѧ���ҳ�
$signPackage=$this->getSignPackage();
$in_type    =$this->judePortType();
if(empty($in_type)) 
    message('����δ��',$this->createMobileUrl('home'),'error');
if($_GPC['class_id'])
    $class_id=$_GPC['class_id'];
 else 
    $class_id=$in_type['info']['class_id'];   

$class_name= $this->className($class_id);
####�༶Ȧ���
if($this->module['config']['line_type'][$_SESSION['school_id']]){
	$line_type_cfg=explode("||", $this->module['config']['line_type'][$_SESSION['school_id']]);
	foreach ($line_type_cfg as $key => $value) {
		if($value){
			$line_type[]=$value;
		}
	}
	$_W['line_type']=$line_type;
}
$tiao_count  =count($_W['line_type']);
# �����༶Ȧ
if($op=='list')
     $list=$this->getLineList(1,10,$class_id);




