<?php

defined('IN_IA') or exit('Access Denied');
define("MON_XS_VOICE", "mon_xsvoice");
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/monUtil.class.php";
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/value.class.php";

class Mon_XSVoiceModuleProcessor extends WeModuleProcessor
{


    public function respond()
    {
        $rid = $this->rule;


        $voice = pdo_fetch("select * from " . tablename(DBUtil::$TABLE_VOICE) . " where rid=:rid", array(":rid" => $rid));


        if (!empty($voice)) {

            if (TIMESTAMP < $voice['starttime']) {
                return $this->respText("寻声活动还未开始!");
            }
            $news = array();
            $news [] = array('title' => $voice['new_title'], 'description' => $voice['new_content'], 'picurl' => MonUtil::getpicurl($voice ['new_icon']), 'url' => $this->createMobileUrl('index', array('vid'=>$voice['id']), true));
            return $this->respNews($news);
        } else {

            return $this->respText("寻声动删除或不存在!");

        }

        return null;


    }


}
