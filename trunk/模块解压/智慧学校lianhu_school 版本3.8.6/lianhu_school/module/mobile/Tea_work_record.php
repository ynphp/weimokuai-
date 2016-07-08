<?php
defined('IN_IA') or exit('Access Denied');
$teacher_info=$this->teacher_mobile_qx();
$_W['uid']=$teacher_info['fanid'];
$class_list=pdo_fetchall("select * from {$table_pe}lianhu_jilv_class where class_status=1 ");	
		$model=$_GPC['model'] ? $_GPC['model'] :"class";
		if($model=='class')
			$result=$this->student_standard('no');	
		else 
			$result=$this->student_standard();	
		if($model=='someone'){
			if($_GPC['submit']){
		if(!strstr($_GPC['img_value'],'images') && $_GPC['img_value'])
				$in['img']          = $this->getWechatMedia($_POST['img_value'],1,true); 
        if($_POST['voice_value'])
				$in['voice']        = $this->getWechatMedia($_POST['voice_value'],2,false);
                
                $in['teacher_id']   = $_W['uid'];
				$in['student_id']   = $result['student_id'];
				$in['class_id']     = $result['class_id'];
				$in['grade_id']     = $result['grade_id'];
				$in['word']         = $_GPC['word'];
				$in['content']      = $_GPC['content'];
				$in['addtime']      = TIMESTAMP;
				$in['jilv_class']   = $_GPC['jilv_class'];
				$in['course_name']  = $this->find_teacher_by_uid($_W['uid'],'',true);
				$in['uniacid']      = $_W['uniacid'];
				$in['school_id']    = $_SESSION['school_id'];
				pdo_insert('lianhu_work',$in);
				$in_id=pdo_insertid();
                $this->send_record_msg($result['student_id'],'记录','', $_W['siteroot'].$this->createMobileUrl('line_article',array('wid'=>$row['work_id'])) );
				message('新增记录成功','refresh','success');
			}
			$list=pdo_fetchall("select work.*,tea.teacher_realname,jilv.class_name
			 from {$table_pe}lianhu_work work 
			 left join  {$table_pe}lianhu_teacher tea on tea.teacher_id=work.teacher_id 
			 left join  {$table_pe}lianhu_jilv_class jilv on jilv.class_id=work.jilv_class 
			 where work.student_id=:id order by work_id desc ",array(':id'=>$result['student_id']));
}