<?php
defined('IN_IA') or exit('Access Denied');
$teacher_info=$this->teacher_mobile_qx();

$tea_result=pdo_fetch("select tea.* ,users.username,users.uid from {$table_pe}lianhu_teacher tea left join ".tablename('users')." users on  users.uid=tea.fanid where tea.teacher_id=:tid",array(":tid"=>$teacher_info['teacher_id']));
    if(!$tea_result) 
        $admin_name='管理员';
    else
        $admin_name=$tea_result['teacher_realname'];
        
    $_W['uid']=$teacher_info['fanid'];
    $model=$_GPC['model'] ? $_GPC['model'] :"class";
    if($model=='class')
        $result=$this->teacher_standard('no');
    else
        $result=$this->student_standard();		
    if($_GPC['submit_weixin'] || $_GPC['submit_kf'] ){
                if(!$_GPC['content']){message('请填写内容','','error');}
                $have=$_POST['have'];
                if(!$have) message('请选择用户或者群组','','error');	
                foreach ($have as $key => $value) {
                    $have[$key]=intval($value);
                }		
                if($model=='class'){
                    $class_id_str=implode(',', $have);
                    $student_list=pdo_fetchall("select * from {$table_pe}lianhu_student where class_id in({$class_id_str}) and status=1 and  (fanid !='' or fanid1!='' or fanid2!='' ) ");				
                }
                if($model=='student'){
                    $student_id_str=implode(',', $have);
                    $student_list=pdo_fetchall("select * from {$table_pe}lianhu_student where student_id in({$student_id_str}) and status=1 and (fanid !='' or fanid1!='' or fanid2!='' ) ");	
                }
                $que_num=false;
                if($_GPC['submit_weixin']=='提交'){
                    foreach ($student_list as $key => $value) {
                        #遍历and发送
                        if($_GPC['title'])
                        {
                            $title=$_GPC['title'];
                            $title=str_ireplace('[学生姓名]',$value['student_name'],$title);
                        }   
                        else 
                            $title=$value['student_name'].'的家长您好，您有一个学校通知，请查看';
                                                
                        $openids=$this->returnEfficeOpenid($value,3);
                        $first= $title;
                        $key2 = $admin_name;
                        $key4 = $_GPC['content'];
                        $remark=$_GPC['remark'];
                        $mu_info =$this->decodeMsg($first,$key2,$key4 ,$remark );     
                                            
                        foreach ($openids as $key => $v) {
                                if($v)
                                $que_num=$this->insertMsgQueueMu($v,$mu_info['data'],$mu_info['mu_id'],$_GPC['url'],$que_num);
                       }         
                    }
                }
                if($_GPC['submit_kf']=='客服消息'){
                        $content_kf  =$_GPC['content_kf'];
                        $title_kf    =$_GPC['title_kf'];
                        foreach ($student_list as $key => $value) {
                            #遍历and发送
                            $openids=$this->returnEfficeOpenid($value,3);
                            foreach ($openids as $key => $v) {
                                if($v){
                                    $data=array('title'=>$title_kf,'url'=>'','content'=>$content_kf);
                                    $que_num=$this->insertMsgQueueKe($v,$data,$que_num);
                                }
                            }                   
                        }                
                }
        message('添加成功，跳转往发送页面，请勿关闭',$this->createMobileUrl('sendToMsg',array('que_num'=>$que_num)),'success');
  }	