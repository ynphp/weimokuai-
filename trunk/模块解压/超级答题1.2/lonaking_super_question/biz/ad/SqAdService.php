<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/10/17
 * Time: 上午9:03
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqAdService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";
        $this->table_name = 'lonaking_super_question_ad';
        $this->columns = 'id,uniacid,title,image,url,type,delay,create_time,update_time';
    }

}