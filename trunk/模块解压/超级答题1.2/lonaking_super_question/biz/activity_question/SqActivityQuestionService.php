<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/15
 * Time: 下午2:43
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqActivityQuestionService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_activity_question';
        $this->columns = 'id,uniacid,activity_id,question_id,question_score';
    }

}