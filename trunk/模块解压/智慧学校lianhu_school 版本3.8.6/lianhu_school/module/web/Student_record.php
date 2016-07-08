<?php 
defined('IN_IA') or exit('Access Denied');
#ac=work,jinbu,weak
$model=$_GPC['model'] ? $_GPC['model'] :"grade";
$result=$this->student_standard();
$uid=$_W['uid'];
$this->teacher_qx('no');
$school_uniacid=" and  ".$this->where_uniacid_school;
#作业管理
if($ac=='work'|| $ac=='list'){
		$class_list=pdo_fetchall("select * from {$table_pe}lianhu_jilv_class where class_status=1 ");	
		if($model=='someone'){
			if($_GPC['submit']){
				$in['teacher_id']=$_W['uid'];
				$in['student_id']=$result['student_id'];
				$in['class_id']=$result['class_id'];
				$in['grade_id']=$result['grade_id'];
				$in['word']=$_GPC['word'];
				$in['img']=$_GPC['img'];
				$in['content']=$_GPC['content'];
				$in['addtime']=TIMESTAMP;
				$in['jilv_class']=$_GPC['jilv_class'];
				$in['course_name']=$this->find_teacher_by_uid($_W['uid'],'',true);
				$in['uniacid']=$_W['uniacid'];
				$in['school_id']=$_SESSION['school_id'];
                #调用七牛处理
                $img=$this->upToQiniu($_GPC['img']);
                if($img)
                     $in['img']= $img;
				pdo_insert('lianhu_work',$in);
				$id=pdo_insertid();
				$url=$_W['siteroot'].$this->createMobileUrl("line_article",array('wid'=>$id));
				$this->send_record_msg($result['student_id'],'记录',false,$url,false);
				message('新增作业记录成功','refresh','success');
			}
			$list=pdo_fetchall("select work.*,tea.teacher_realname,jilv.class_name
				 from {$table_pe}lianhu_work work 
				  left join  {$table_pe}lianhu_teacher tea on tea.teacher_id=work.teacher_id 
				  left join  {$table_pe}lianhu_jilv_class jilv on jilv.class_id=work.jilv_class 
				  where work.student_id=:id order by work_id desc ",array(':id'=>$result['student_id']));
		}
}
#进步管理
if($ac=='class'){
	    $this->teacher_qx();
		$list=pdo_fetchall("select * from {$table_pe}lianhu_jilv_class where 1=1 ");	
		if($op=='edit')
			$result=pdo_fetch("select * from {$table_pe}lianhu_jilv_class where class_id=:cid",array(':cid'=>$_GPC['class_id']) );
	 
	    if($_GPC['submit']){
				if(!$_GPC['class_name'])
					message('分类名必须填写','refresh','error');
				if($_GPC['class_id']){
					$in['class_name']   =$_GPC['class_name'];
					$in['class_status'] =$_GPC['class_status'];
					pdo_update('lianhu_jilv_class',$in,array('class_id'=>$_GPC['class_id']) );				
					message('编辑成功','refresh','success');
				}else{
					$in['class_name']   =$_GPC['class_name'];
					$in['class_status'] =$_GPC['class_status'];
					$in['add_time']    =TIMESTAMP;
					pdo_insert('lianhu_jilv_class',$in);
					message('新增成功','refresh','success');
				}
	    }
		
}
