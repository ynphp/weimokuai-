<?php 
defined('IN_IA') or exit('Access Denied');
$result=$this->mobile_from_find_student();
$msg_count=count($this->web_msg(true));
    $ji_lv_class=$this->jiLvClass();
    for ($i=0; $i <3 ; $i++) { 
    		if($ji_lv_class[$i])
            {
                $out[$i]['list']  =$this->get_info($ji_lv_class[$i]['class_id'],$result['student_id']);
                $out[$i]['name']  =$ji_lv_class[$i]['class_name'];            
                $out[$i]['count'] =count($out[$i]['list']);
                $out[$i]['id']    =$ji_lv_class[$i]['class_id'];
            }
    }
    
    $need_money=$this->MoneyGive();