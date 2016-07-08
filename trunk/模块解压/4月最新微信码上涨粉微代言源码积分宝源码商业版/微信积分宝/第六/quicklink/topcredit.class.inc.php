<?php
 class TopCredit{
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_member = 'mc_members';

    public function updateCache($weid, $list){
        $ret = pdo_query('REPLACE INTO ' . tablename(self :: $t_cache) . ' (weid, createtime, cache) VALUES (:weid, :ct, :cache)', array(':weid' => $weid, ':ct' => time(), ':cache' => serialize($list)));
        return $ret;
    }
    public function getCached($weid){
        $cache = pdo_fetch('SELECT cache FROM ' . tablename(self :: $t_cache) . ' WHERE weid=:weid', array(':weid' => $weid));
        if (!empty($cache)) $result = unserialize($cache['cache']);
        return $result;
    }
    public function get($weid, $limit = 20){
        $mylist = pdo_fetchall('SELECT * FROM ' . tablename(self :: $t_sys_fans) . ' a LEFT JOIN ' . tablename(self :: $t_sys_member) . ' b ON a.uid=b.uid ' . ' WHERE a.uniacid=:uniacid AND follow=1 ORDER BY credit1 DESC LIMIT ' . $limit, array(':uniacid' => $weid));
        return $mylist;
    }
}
