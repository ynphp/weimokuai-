<?php 
defined('IN_IA') or exit('Access Denied');
		$result=$this->mobile_from_find_student();
		for ($i=0; $i <100 ; $i++) { 
			$loop[$i]=1;
		}
		
		$old_result=pdo_fetch("select * from {$table_pe}lianhu_syllabus where class_id=:cid order by addtime desc ",array(':cid'=>$result['class_id']));
		$data=unserialize($old_result['content']);
		if($old_result['url'])
			header("Location:".$old_result['url']);
        $time_result=pdo_fetch("select * from {$table_pe}lianhu_set where keyword='course_time' order by set_id  desc ");
        $time_result['content']=unserialize($time_result['content']);
        $time_result['begin_time']=$time_result['content']['begin_time'];
        $time_result['end_time']=$time_result['content']['end_time'];
        $begin_course =$this->module['config']['begin_course'][$_SESSION['school_id']];
                   
        
        