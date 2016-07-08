<?php 
   defined('IN_IA') or exit('Access Denied');
   $result=$this->mobile_from_find_student();
   $on_school    =  $this->module['config']['on_school'][$_SESSION['school_id']];
   $begin_course =  $this->module['config']['begin_course'][$_SESSION['school_id']];
   $school_uniacid = " and ".$this->where_uniacid_school;
   $list           =pdo_fetchall("select * from {$table_pe}lianhu_cookbook  where 1=1 
                                  {$school_uniacid}  order by cookbook_week asc ");
   $display_list   =array(1,2,3,4,5,6,7);
   if($_GPC['submit']){
       $breakfast_arr = $_GPC['breakfast'];
       $lunch_arr     = $_GPC['lunch'];
       $dinner_arr    = $_GPC['dinner'];
       if($breakfast_arr)
            $arr=$breakfast_arr;
       elseif($lunch_arr) 
            $arr= $lunch_arr;
       elseif($dinner_arr)
            $arr= $dinner_arr;
       else
           message("没有传入食谱数据，更新失败",$this->createWebUrl('cookbook'),'error');
         
         pdo_delete('lianhu_cookbook',array('school_id'=>$_SESSION['school_id'],'uniacid'=>$_W['uniacid']) );
         $in['uniacid']   =$_W['uniacid'];
         $in['school_id'] =$_SESSION['school_id'];
         $in['add_time']  =time();     
         foreach ($arr as $key => $value) {
               $in['cookbooK_breakfast'] =$breakfast_arr[$key];
               $in['cookbook_lunch']     =$lunch_arr[$key];
               $in['cookbook_dinner']    =$dinner_arr[$key];
               $in['cookbook_week']      =$key;
               pdo_insert("lianhu_cookbook",$in);
           } 
      message("更新成功",$this->createWebUrl('cookbook'),'success');
   }   
   
   
