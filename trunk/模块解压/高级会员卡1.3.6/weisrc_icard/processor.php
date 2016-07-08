<?php
/**
 * 微信会员卡
 * 作者:迷微赞科技
 * qq: 800083075
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');

class weisrc_icardModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('weisrc_icard_reply') . " WHERE `rid`=:rid LIMIT 1";
        $row = pdo_fetch($sql, array(':rid' => $rid));
        $weid = $row['weid'];
        $from_user = $this->message['from'];
        $card = pdo_fetch("SELECT id FROM " . tablename('weisrc_icard_card') . " WHERE `from_user`='" . $from_user . "' AND `weid`=" . $weid . " LIMIT 1");

        $content = !empty($card) ? htmlspecialchars_decode($row['description']) : htmlspecialchars_decode($row['description_not']);

        return $this->respNews(array(
            'Title' => !empty($card) ? $row['title'] : $row['title_not'],
            'Description' => $content,
            'PicUrl' => !empty($card) ? $_W['attachurl'] . $row['picture'] : $_W['attachurl'] . $row['picture_not'],
            'Url' => $_W['siteroot'] . create_url('mobile/module', array('do' => 'index', 'name' => 'weisrc_icard', 'weid' => $weid, 'from_user' => base64_encode(authcode($from_user, 'ENCODE'))))
        ));
        //return $response;
    }
}
