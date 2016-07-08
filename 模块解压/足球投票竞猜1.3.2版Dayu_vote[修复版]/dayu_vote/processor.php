<?php

/**
 * 微投票
 *
 * @author dayu
 * @url Weizan
 */
defined('IN_IA') or exit('Access Denied');

class dayu_voteModuleProcessor extends WeModuleProcessor {

    public function respond() {
        global $_W;
        $rid = $this->rule;
        if ($rid) {
            $reply = pdo_fetch("SELECT * FROM " . tablename('dayu_vote_reply') . " WHERE rid = :rid", array(':rid' => $rid));
            if ($reply) {
                $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
                $activity = pdo_fetch($sql, array(':weid' => $_W['uniacid'], ':reid' => $reply['reid']));
                $news = array();
                $news[] = array(
                    'title' => $activity['title'],
                    'description' => strip_tags($activity['description']),
                    'picurl' => tomedia($activity['thumb']),
                    'url' => $this->createMobileUrl('dayu_vote',array('id' => $activity['reid'], 'weid' => $_W['uniacid']))
                );
                return $this->respNews($news);
            }
        }
        return null;
    }

}
