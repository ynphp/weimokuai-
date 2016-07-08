<?php
defined('IN_IA') or exit('Access Denied');
class member
{
    public function get_member($openid = 0, $uid = 0)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist   = pdo_fetch('SELECT * FROM ' . tablename('xhbdz_member') . " WHERE uniacid = $uniacid AND `openid` = '$openid' or `uid` = $uid");
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    public function get_memberAll()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist   = array_reverse(pdo_fetchall('SELECT * FROM ' . tablename('xhbdz_member') . " WHERE uniacid = $uniacid"));
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    public function get_fanslist($uid)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist   = array_reverse(pdo_fetchall('SELECT * FROM ' . tablename('xhbdz_member') . " WHERE uniacid = $uniacid AND `parent1`  = $uid"));
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    public function get_parent($parent, $uid)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist   = pdo_fetch('SELECT COUNT(`uid`) AS `count`  FROM ' . tablename('xhbdz_member') . " WHERE  `uniacid` = $uniacid  AND  `vip` = 1 AND `$parent` = $uid");
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    public function get_parents($parent, $uid)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $exist   = pdo_fetchall("SELECT * FROM " . tablename('xhbdz_member') . " WHERE  `uniacid` = $uniacid  AND `$parent` = $uid");
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
    public function update_member($uid, $data)
    {
        global $_W;
        if (pdo_update(xhbdz_member, $data, array(
            'uid' => $uid
        ))) {
            return true;
        }
        return false;
    }
    public function update_memAm($openid = 0, $data)
    {
        global $_W;
        $shouyi = $data['shouyi'];
        if (pdo_query('UPDATE  ' . tablename('xhbdz_member') . " SET `shouyi` =`shouyi`$shouyi WHERE uniacid = " . $_W['uniacid'] . " AND `openid` = '$openid'")) {
            return true;
        }
        return false;
    }
    public function add_member($data)
    {
        global $_W;
        $data['uniacid'] = $_W['uniacid'];
        if (pdo_insert(xhbdz_member, $data)) {
            return true;
        }
        return false;
    }
    public function get_membercou($openid)
    {
        global $_W;
        $exist            = array();
        $uniacid          = $_W['uniacid'];
        $member           = $this->get_member($openid);
        $memberId         = $member['uid'];
        $exist['direct']  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xhbdz_member') . "WHERE  uniacid = $uniacid AND direct = $memberId");
        $exist['parent1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xhbdz_member') . "WHERE  uniacid = $uniacid AND `vip` = 1 AND `parent1` = $memberId");
        if (!empty($exist)) {
            return $exist;
        }
        return false;
    }
}
?>