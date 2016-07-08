<?php

/**
 * 模板消息群发模块微站定义
 *
 * @author zjl
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class zjl_mass_tpl_msgModuleSite extends WeModuleSite {

    function getTplTags($tpl) {
        $returnArray = array();
        $pattern = '/\w+(?=\.DATA)/i';
        preg_match_all($pattern, urldecode($tpl), $returnArray);
        if (empty($returnArray[0])) {
            return false;
        } else {
            return $returnArray[0];
        }
    }

    /**
     * @name sendTplMsg 
     * @param string $toUser
     * @param type $optionId
     */
    function sendTplMsg($toUser, $optionId, $isAjax = false) {
        global $_W;
        //$toUser = 'od8tRt2J8fp2QppgJcgSu2FLbblE'; //测试
        if (empty($toUser)) {
            return false;
        }
        $optionInfo = pdo_fetch("select * from " . tablename("zjl_mass_tpl_msg_options") . " where id = :id", array(':id' => $optionId));

        if (empty($optionInfo)) {
            return false;
        }
        load()->classs('weixin.account');
        $accObj = WeixinAccount::create($optionInfo['acid']);
        $postdata = unserialize($optionInfo['post_data']);

        if (!preg_match("/^(http|https|tel):/", urldecode($optionInfo['url']))) {
            $optionInfo['url'] = $_W['siteroot'] . "/app/" . $optionInfo['url'];
        }
        $tpMsgResult = $accObj->sendTplNotice($toUser, $optionInfo['tpl_id'], $postdata, $optionInfo['url'], $optionInfo['topcolor']);
        if ($isAjax) {
            echo $tpMsgResult;
        }
        return $tpMsgResult;
    }

}
