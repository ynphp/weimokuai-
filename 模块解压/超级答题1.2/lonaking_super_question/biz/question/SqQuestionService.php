<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqQuestionService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_question';
        $this->columns = 'id,uniacid,question_num,title,pic,bg_pic,option_a,option_b,option_c,option_d,option_e,right_answer,score,de_score,ad_id,create_time,update_time';
    }

}