<?php
    defined('IN_IA') or exit('Access Denied');
    $teacher_info =$this->teacher_mobile_qx();
    $list         =$this->getTeacherClass($teacher_info['teacher_id'],true);
    