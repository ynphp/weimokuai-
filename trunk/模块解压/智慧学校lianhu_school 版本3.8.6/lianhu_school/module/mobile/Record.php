<?php 
    defined('IN_IA') or exit('Access Denied');
	$student_info =$this->mobile_from_find_student();
	$class_name   =$student_info['class_name'];
    $ji_lv_class  =$this->jiLvClass();
	$class_id     =$_GPC['op'];
    $info =$this->jiLvClassInfo($class_id);
    $list =$this->jiLvClass($class_id);
    
    $jilv_list =$this->get_info($class_id,$student_info['student_id']);