<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class SqActivityService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = "lonaking_super_question";//question_bg_pic,end_page_bg_pic
        $this->table_name = 'lonaking_super_question_activity';//activity_type 1:普通答题，单人模式  2:普通答题，求助模式  3 团队答题，现场模式
        $this->columns = 'id,uniacid,activity_num,name,activity_type,pic,start_time,end_time,play_times,play_limit,limit_type,help_limit,virtual_times,share_times,question_count,score,copyright,rule,score_rule,analyse_message,ad_end_page,bg_pic,theme_pic,logo_pic,current,share_logo,create_time,update_time';
    }

    public function activitySave($activityDto){

    }

    /**
     * check the current acitivty
     * @param $currentId
     * @throws Exception
     */
    public function checkCurrentAcitivity($currentId){
        $activity = $this->selectById($currentId);
        if($activity['current'] == false){
            // check the old activity gone
            $this->updateColumnByWhere('current',0," AND current=true");
            // set new activity currently
            $this->updateColumn('current',1,$currentId);
        }
    }

}