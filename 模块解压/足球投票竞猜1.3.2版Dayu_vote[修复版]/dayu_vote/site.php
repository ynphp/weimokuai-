<?php
defined('IN_IA') or exit('Access Denied');
define('TEMPLATE_PATH', '../../addons/dayu_vote/template/style/');
class dayu_voteModuleSite extends WeModuleSite
{
    private static $COOKIE_DAYS = 7;
    public function getHomeTiles()
    {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, reid FROM " . tablename('dayu_vote') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_vote', array('id' => $row['reid'])));
            }
        }
        return $urls;
    }
    public function doWebQuery()
    {
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['title'] = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb'] = $row['thumb'];
            $r['reid'] = $row['reid'];
            $row['entry'] = $r;
        }
        include $this->template('query');
    }
    public function doWebStaff()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $weid = $_W['uniacid'];
        $reid = intval($_GPC['reid']);
        $activity = pdo_fetch('SELECT reid,title FROM ' . tablename('dayu_vote') . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
        if (empty($activity)) {
            message('投票不存在或已删除', $this->createWebUrl('activity'), 'error');
        }
        if ($op == 'list') {
            $where = ' reid = :reid';
            $params[':reid'] = $reid;
            if (!empty($_GPC['keyword'])) {
                $where .= " AND nickname LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_vote_staff') . ' WHERE ' . $where, $params);
            $lists = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_staff') . ' WHERE ' . $where . ' ORDER BY createtime DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            $pager = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array('nickname' => trim($_GPC['nickname'][$k]), 'openid' => trim($_GPC['openid'][$k]), 'weid' => trim($_GPC['weid'][$k]));
                        pdo_update('dayu_vote_staff', $data, array('reid' => $reid, 'id' => intval($v)));
                    }
                    message('编辑成功', $this->createWebUrl('staff', array('op' => 'list', 'reid' => $reid)), 'success');
                }
            }
            include $this->template('staff');
        } elseif ($op == 'post') {
            if (checksubmit('submit')) {
                if (!empty($_GPC['nickname'])) {
                    foreach ($_GPC['nickname'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v)) {
                            continue;
                        }
                        $data['reid'] = $reid;
                        $data['nickname'] = $v;
                        $data['nickname'] = $_GPC['nickname'][$k];
                        $data['openid'] = $_GPC['openid'][$k];
                        $data['weid'] = $_GPC['weid'][$k];
                        $data['createtime'] = time();
                        pdo_insert('dayu_vote_staff', $data);
                    }
                }
                message('添加客服成功', $this->createWebUrl('staff', array('reid' => $reid, 'op' => 'list')), 'success');
            }
            include $this->template('staff');
        } elseif ($op == 'staffdel') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                pdo_delete('dayu_vote_staff', array('id' => $id));
            }
            message('删除成功.', referer());
        }
    }
    public function doWebVote()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $weid = $_W['uniacid'];
        $reid = intval($_GPC['reid']);
        $activity = pdo_fetch('SELECT reid,title FROM ' . tablename('dayu_vote') . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
        if (empty($activity)) {
            message('投票不存在或已删除', $this->createWebUrl('display'), 'error');
        }
        if ($op == 'list') {
            $where = ' reid = :reid';
            $params[':reid'] = $reid;
            if (!empty($_GPC['keyword'])) {
                $where .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_vote_item') . ' WHERE ' . $where, $params);
            $lists = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_item') . ' WHERE ' . $where . ' ORDER BY createtime DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            $pager = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array('title' => trim($_GPC['title'][$k]), 'displayorder' => intval($_GPC['displayorder'][$k]), 'weid' => $weid);
                        pdo_update('dayu_vote_item', $data, array('reid' => $reid, 'id' => intval($v)));
                    }
                    message('批量修改成功', $this->createWebUrl('vote', array('op' => 'list', 'reid' => $reid)), 'success');
                }
            }
            include $this->template('vote');
        } elseif ($op == 'post') {
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v)) {
                            continue;
                        }
                        $data['reid'] = $reid;
                        $data['title'] = $v;
                        $data['title'] = $_GPC['title'][$k];
                        $data['displayorder'] = intval($_GPC['displayorder'][$k]);
                        $data['weid'] = $weid;
                        if (!empty($_GPC['thumb'])) {
                            $data['thumb'] = $_GPC['thumb'][$k];
                            load()->func('file');
                            file_delete($_GPC['thumb-old'][$k]);
                        }
                        $data['description'] = trim($_GPC['description'][$k]);
                        $data['createtime'] = time();
                        pdo_insert('dayu_vote_item', $data);
                    }
                }
                message('添加投票项目成功', $this->createWebUrl('vote', array('reid' => $reid, 'op' => 'list')), 'success');
            }
            include $this->template('vote');
        } elseif ($op == 'edit') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename('dayu_vote_item') . ' WHERE weid = :weid AND reid = :reid AND id = :id', array(':weid' => $weid, ':reid' => $reid, ':id' => $id));
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v)) {
                            continue;
                        }
                        $data['reid'] = $reid;
                        $data['title'] = $v;
                        $data['title'] = $_GPC['title'][$k];
                        $data['displayorder'] = intval($_GPC['displayorder'][$k]);
                        $data['weid'] = $weid;
                        if (!empty($_GPC['thumb'])) {
                            $data['thumb'] = $_GPC['thumb'][$k];
                            load()->func('file');
                            file_delete($_GPC['thumb-old'][$k]);
                        }
                        $data['description'] = trim($_GPC['description'][$k]);
                        $data['createtime'] = time();
                        pdo_update('dayu_vote_item', $data, array('reid' => $reid, 'id' => $id));
                    }
                }
                message('修改投票项目成功', $this->createWebUrl('vote', array('reid' => $reid, 'op' => 'list')), 'success');
            }
            include $this->template('vote');
        } elseif ($op == 'votedel') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                pdo_delete('dayu_vote_item', array('id' => $id));
            }
            message('删除成功.', referer());
        }
    }
    public function doWebGift()
    {
        global $_W, $_GPC;
        $op = trim($_GPC['op']) ? trim($_GPC['op']) : 'list';
        $weid = $_W['uniacid'];
        $reid = intval($_GPC['reid']);
        $activity = pdo_fetch('SELECT reid,title FROM ' . tablename('dayu_vote') . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
        if (empty($activity)) {
            message('投票不存在或已删除', $this->createWebUrl('display'), 'error');
        }
        if ($op == 'list') {
            $where = ' reid = :reid';
            $params[':reid'] = $reid;
            if (!empty($_GPC['keyword'])) {
                $where .= " AND title LIKE '%{$_GPC['keyword']}%'";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_vote_gift') . ' WHERE ' . $where, $params);
            $lists = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_gift') . ' WHERE ' . $where . ' ORDER BY createtime DESC,id ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, $params, 'id');
            $pager = pagination($total, $pindex, $psize);
            if (checksubmit('submit')) {
                if (!empty($_GPC['ids'])) {
                    foreach ($_GPC['ids'] as $k => $v) {
                        $data = array('title' => trim($_GPC['title'][$k]), 'num' => intval($_GPC['num'][$k]), 'displayorder' => intval($_GPC['displayorder'][$k]), 'weid' => $weid);
                        pdo_update('dayu_vote_gift', $data, array('reid' => $reid, 'id' => intval($v)));
                    }
                    message('批量修改成功', $this->createWebUrl('gift', array('op' => 'list', 'reid' => $reid)), 'success');
                }
            }
            include $this->template('gift');
        } elseif ($op == 'post') {
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v)) {
                            continue;
                        }
                        $data['reid'] = $reid;
                        $data['title'] = $v;
                        $data['title'] = $_GPC['title'][$k];
                        $data['displayorder'] = intval($_GPC['displayorder'][$k]);
                        $data['num'] = intval($_GPC['num'][$k]);
                        $data['weid'] = $weid;
                        if (!empty($_GPC['thumb'][$k])) {
                            $data['thumb'] = $_GPC['thumb'][$k];
                            load()->func('file');
                            file_delete($_GPC['thumb-old'][$k]);
                        }
                        $data['description'] = trim($_GPC['description'][$k]);
                        $data['createtime'] = time();
                        pdo_insert('dayu_vote_gift', $data);
                    }
                }
                message('添加奖品成功', $this->createWebUrl('gift', array('reid' => $reid, 'op' => 'list')), 'success');
            }
            include $this->template('gift');
        } elseif ($op == 'edit') {
            $id = intval($_GPC['id']);
            $item = pdo_fetch('SELECT * FROM ' . tablename('dayu_vote_gift') . ' WHERE weid = :weid AND reid = :reid AND id = :id', array(':weid' => $weid, ':reid' => $reid, ':id' => $id));
            if (checksubmit('submit')) {
                if (!empty($_GPC['title'])) {
                    foreach ($_GPC['title'] as $k => $v) {
                        $v = trim($v);
                        if (empty($v)) {
                            continue;
                        }
                        $data['reid'] = $reid;
                        $data['title'] = $v;
                        $data['title'] = $_GPC['title'][$k];
                        $data['num'] = intval($_GPC['num'][$k]);
                        $data['weid'] = $_GPC['weid'][$k];
                        if (!empty($_GPC['thumb'][$k])) {
                            $data['thumb'] = $_GPC['thumb'][$k];
                            load()->func('file');
                            file_delete($_GPC['thumb-old'][$k]);
                        }
                        $data['description'] = trim($_GPC['description'][$k]);
                        $data['createtime'] = time();
                        pdo_update('dayu_vote_gift', $data, array('reid' => $reid, 'id' => $id));
                    }
                }
                message('修改奖品成功', $this->createWebUrl('gift', array('reid' => $reid, 'op' => 'list')), 'success');
            }
            include $this->template('gift');
        } elseif ($op == 'giftdel') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                pdo_delete('dayu_vote_gift', array('id' => $id));
            }
            message('删除成功.', referer());
        }
    }
    public function doWebEditkf()
    {
        global $_W, $_GPC;
        if ($_GPC['dopost'] == "update") {
            $reid = $_GPC['reid'];
            $nickname = $_GPC['nickname'];
            $openid = $_GPC['openid'];
            if (is_array($reid)) {
                foreach ($reid as $k => $v) {
                    $actid = $v . ",";
                }
            }
            $actid = substr($actid, 0, strlen($actid) - 1);
            $a = pdo_update('dayu_vote_staff', array('reid' => $actid, 'nickname' => $nickname, 'openid' => $openid), array('id' => $_GPC['id']));
            message("更改成功!", referer());
            exit;
        }
        $fff = pdo_fetchall("SELECT reid,title FROM " . tablename('dayu_vote'));
        $config = pdo_fetch("SELECT * from " . tablename('dayu_vote_staff') . " where id=" . $_GPC['id']);
        $fun = explode(',', $config['reid']);
        include $this->template('kf_edit');
    }
    public function doWebDetail()
    {
        global $_W, $_GPC;
        $rerid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('dayu_vote_info') . " WHERE `rerid`=:rerid";
        $params = array();
        $params[':rerid'] = $rerid;
        $row = pdo_fetch($sql, $params);
        if (empty($row)) {
            message('访问非法.');
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $row['reid'];
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params = array();
        $params[':reid'] = $row['reid'];
        $fields = pdo_fetchall($sql, $params);
        $ds = $fids = array();
        foreach ($fields as $f) {
            $ds[$f['refid']]['fid'] = $f['title'];
            $ds[$f['refid']]['type'] = $f['type'];
            $ds[$f['refid']]['refid'] = $f['refid'];
            $fids[] = $f['refid'];
        }
        $record = array();
        $record['status'] = intval($_GPC['status']);
        $record['time'] = TIMESTAMP;
        $record['kfinfo'] = $_GPC['kfinfo'];
        if ($_GPC['status'] == '0') {
            $huifu = '未中奖（' . $_GPC['kfinfo'] . '）';
        } elseif ($_GPC['status'] == '1') {
            $huifu = '恭喜你中奖了（' . $_GPC['kfinfo'] . '）';
        }
        $ytime = date('Y-m-d H:i:s', TIMESTAMP);
        $mfirst = $activity['mfirst'];
        $mfoot = $activity['mfoot'];
        $template = array("touser" => $row['openid'], "template_id" => $activity['m_templateid'], "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_vote', array('weid' => $row['weid'], 'id' => $row['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($mfirst), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($row['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($row['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($_GPC['time']), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($huifu), 'color' => "#FF0000"), 'remark' => array('value' => urlencode("\\n" . $mfoot), 'color' => "#008000")));
        if ($_W['ispost'] && $activity['custom_status'] == 1) {
            load()->model('mc');
            $acc = notice_init();
            if (is_error($acc)) {
                return error(-1, $acc['message']);
            }
            $url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_vote', array('name' => 'dayu_vote', 'weid' => $row['weid'], 'id' => $row['reid']));
            $info = "【您好，投票结果通知】\n\n";
            $info .= "姓名：{$ymember}\n手机：{$ymobile}\n结果：{$huifu}\n\n";
            $info .= "<a href='{$url}'>点击查看详情</a>";
            $custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $row['openid']);
            $acc->sendCustomNotice($custom);
            pdo_update('dayu_vote_info', $record, array('rerid' => $rerid));
            message('修改成功', referer(), 'success');
        }
        if ($_W['ispost'] && $activity['custom_status'] == 0) {
            load()->func('communication');
            $this->send_template_message(urldecode(json_encode($template)));
            pdo_update('dayu_vote_info', $record, array('rerid' => $rerid));
            message('修改成功', referer(), 'success');
        }
        $row['time'] && ($row['time'] = date('Y-m-d H:i:s', $row['time']));
        if (!empty($fields)) {
            $fids = implode(',', $fids);
            $row['fields'] = array();
            $sql = 'SELECT * FROM ' . tablename('dayu_vote_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
            $fdatas = pdo_fetchall($sql, $params);
            foreach ($fdatas as $fd) {
                $row['fields'][$fd['refid']] = $fd['data'];
            }
            foreach ($ds as $value) {
                if ($value['type'] == 'reside') {
                    $row['fields'][$value['refid']] = '';
                    foreach ($fdatas as $fdata) {
                        if ($fdata['refid'] == $value['refid']) {
                            $row['fields'][$value['refid']] .= $fdata['data'];
                        }
                    }
                    break;
                }
            }
        }
        load()->func('tpl');
        include $this->template('detail');
    }
    public function doWebManage()
    {
        global $_W, $_GPC;
        $_accounts = $accounts = uni_accounts();
        load()->model('mc');
        if (empty($accounts) || !is_array($accounts) || count($accounts) == 0) {
            message('请指定公众号');
        }
        if (!isset($_GPC['acid'])) {
            $account = array_shift($_accounts);
            if ($account !== false) {
                $acid = intval($account['acid']);
            }
        } else {
            $acid = intval($_GPC['acid']);
            if (!empty($acid) && !empty($accounts[$acid])) {
                $account = $accounts[$acid];
            }
        }
        reset($accounts);
        $reid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params = array();
        $params[':reid'] = $reid;
        $fields = pdo_fetchall($sql, $params);
        $ds = array();
        foreach ($fields as $f) {
            $ds[$f['refid']] = $f['title'];
        }
        $starttime = empty($_GPC['daterange']['start']) ? strtotime('-1 month') : strtotime($_GPC['daterange']['start']);
        $endtime = empty($_GPC['daterange']['end']) ? TIMESTAMP : strtotime($_GPC['daterange']['end']) + 86399;
        $select = array();
        if (!empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $field) {
                if (isset($ds[$field])) {
                    $select[] = $field;
                }
            }
        }
        $vote = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_item') . ' WHERE weid = :weid AND reid = :reid ORDER BY `displayorder` DESC', array(':weid' => $_W['uniacid'], ':reid' => $reid));
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $where .= 'reid=:reid';
        $params = array();
        $params[':reid'] = $reid;
        if (!empty($_GPC['keywords'])) {
            $where .= ' and (member like :member or mobile like :mobile)';
            $params[':member'] = "%{$_GPC['keywords']}%";
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote_info') . " WHERE {$where} ORDER BY `createtime` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, $params);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_vote_info') . " WHERE {$where}", $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as $index => $row) {
            $list[$index]['user'] = mc_fansinfo($row['openid'], $acid, $_W['uniacid']);
            if ($activity['select'] == 0) {
                $list[$index]['vote'] = $this->get_item($row['reid'], $row['vid']);
            } elseif ($activity['select'] == 1) {
                $list[$index]['options'] = explode(',', $row['checkbox']);
            }
        }
        if (checksubmit('export', 1)) {
            $sql = 'SELECT title FROM ' . tablename('dayu_vote_fields') . " AS f JOIN " . tablename('dayu_vote_info') . " AS r ON f.reid='{$params[':reid']}' GROUP BY title ORDER BY displayorder DESC";
            $tableheader = pdo_fetchall($sql, $params);
            $tablelength = count($tableheader);
            $tableheaders[] = array('title' => '姓名');
            $tableheaders[] = array('title' => '手机');
            $tableheaders[] = array('title' => '提交时间');
            $sql = 'SELECT * FROM ' . tablename('dayu_vote_info') . " WHERE `reid`=:reid AND `createtime` > {$starttime} AND `createtime` < {$endtime} ORDER BY `createtime` DESC";
            $params = array();
            $params[':reid'] = $reid;
            $list = pdo_fetchall($sql, $params);
            if (empty($list)) {
                message('暂时没有投票数据');
            }
            foreach ($list as &$r) {
                $r['fields'] = array();
                $sql = 'SELECT data, refid FROM ' . tablename('dayu_vote_data') . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}' ORDER BY redid";
                $fdatas = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    if (false == array_key_exists($fd['refid'], $r['fields'])) {
                        $r['fields'][$fd['refid']] = $fd['data'];
                    } else {
                        $r['fields'][$fd['refid']] .= '-' . $fd['data'];
                    }
                }
            }
            $data = array();
            foreach ($list as $key => $value) {
                $data[$key]['member'] = $value['member'];
                $data[$key]['mobile'] = $value['mobile'];
                $data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
                if (!empty($value['fields'])) {
                    foreach ($value['fields'] as $field) {
                        if (substr($field, 0, 6) == 'images') {
                            $data[$key][] = str_replace(array("\n", "\r", "\t"), '', $_W['attachurl'] . $field);
                        } else {
                            $data[$key][] = str_replace(array("\n", "\r", "\t"), '', $field);
                        }
                    }
                }
            }
            $html = "﻿";
            foreach ($tableheaders as $value) {
                $html .= $value['title'] . "\t ,";
            }
            foreach ($tableheader as $value) {
                $html .= $value['title'] . "\t ,";
            }
            $html .= "\n";
            foreach ($data as $value) {
                $html .= $value['member'] . "\t ,";
                $html .= $value['mobile'] . "\t ,";
                $html .= $value['createtime'] . "\t ,";
                for ($i = 0; $i < $tablelength; $i++) {
                    $html .= $value[$i] . "\t ,";
                }
                $html .= "\n";
            }
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=全部数据.csv");
            echo $html;
            exit;
        }
        foreach ($list as $key => &$value) {
            if (is_array($value['fields'])) {
                foreach ($value['fields'] as &$v) {
                    $img = '<img src="';
                    if (substr($v, 0, 6) == 'images') {
                        $v = $img . $_W['attachurl'] . $v . '" style="width:50px;height:50px;"/>';
                    }
                }
                unset($v);
            }
        }
        include $this->template('manage');
    }
    public function doWebDisplay()
    {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $reid = intval($_GPC['reid']);
            $switch = intval($_GPC['switch']);
            $sql = 'UPDATE ' . tablename('dayu_vote') . ' SET `status`=:status WHERE `reid`=:reid';
            $params = array();
            $params[':status'] = $switch;
            $params[':reid'] = $reid;
            pdo_query($sql, $params);
            exit;
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid';
        $status = $_GPC['status'];
        if ($status != '') {
            $sql .= " and status=" . intval($status);
        }
        if (!empty($_GPC['keyword'])) {
            $sql .= " AND title LIKE '%{$_GPC['keyword']}%'";
        }
        $ds = pdo_fetchall($sql, array(':weid' => $_W['uniacid']));
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch'] = $item['status'];
            $item['link'] = murl('entry', array('do' => 'dayu_vote', 'id' => $item['reid'], 'm' => 'dayu_vote'), true, true);
        }
        include $this->template('display');
    }
    public function doWebDelete()
    {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        if ($reid > 0) {
            $params = array();
            $params[':reid'] = $reid;
            $sql = 'DELETE FROM ' . tablename('dayu_vote') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_vote_info') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_vote_data') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_vote_staff') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_vote_item') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }
    public function doWebdayu_voteDelete()
    {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (!empty($id)) {
            pdo_delete('dayu_vote_info', array('rerid' => $id));
            pdo_delete('dayu_vote_data', array('rerid' => $id));
        }
        message('操作成功.', referer());
    }
    public function doWebPost()
    {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        $hasData = false;
        if ($reid) {
            $sql = 'SELECT COUNT(*) FROM ' . tablename('dayu_vote_info') . ' WHERE `reid`=' . $reid;
            if (pdo_fetchcolumn($sql) > 0) {
                $hasData = true;
            }
        }
        if (checksubmit()) {
            $record = array();
            $record['title'] = trim($_GPC['activity']);
            $record['weid'] = $_W['uniacid'];
            $record['description'] = trim($_GPC['description']);
            $record['content'] = trim($_GPC['content']);
            $record['giftdec'] = trim($_GPC['giftdec']);
            $record['information'] = trim($_GPC['information']);
            if (!empty($_GPC['thumb'])) {
                $record['thumb'] = $_GPC['thumb'];
                load()->func('file');
                file_delete($_GPC['thumb-old']);
            }
            $record['bg'] = $_GPC['bg'];
            $record['status'] = intval($_GPC['status']);
            $record['custom_status'] = intval($_GPC['custom_status']);
            $record['inhome'] = intval($_GPC['inhome']);
            $record['votenum'] = intval($_GPC['votenum']);
            $record['pretotal'] = intval($_GPC['pretotal']);
            $record['starttime'] = strtotime($_GPC['starttime']);
            $record['endtime'] = strtotime($_GPC['endtime']);
            $record['noticeemail'] = trim($_GPC['noticeemail']);
            $record['k_templateid'] = trim($_GPC['k_templateid']);
            $record['kfirst'] = trim($_GPC['kfirst']);
            $record['kfoot'] = trim($_GPC['kfoot']);
            $record['mfirst'] = trim($_GPC['mfirst']);
            $record['mfoot'] = trim($_GPC['mfoot']);
            $record['kfid'] = trim($_GPC['kfid']);
            $record['m_templateid'] = trim($_GPC['m_templateid']);
            $record['isreplace'] = intval($_GPC['isreplace']);
            $record['mname'] = trim($_GPC['mname']);
            $record['skins'] = trim($_GPC['skins']);
            $record['reward'] = intval($_GPC['reward']);
            $record['select'] = intval($_GPC['select']);
            $record['iscredit'] = intval($_GPC['iscredit']);
            $record['follow'] = intval($_GPC['follow']);
            if (empty($reid)) {
                $record['status'] = 1;
                $record['createtime'] = TIMESTAMP;
                pdo_insert('dayu_vote', $record);
                $reid = pdo_insertid();
                if (!$reid) {
                    message('保存投票失败, 请稍后重试.');
                }
            } else {
                if (pdo_update('dayu_vote', $record, array('reid' => $reid)) === false) {
                    message('保存投票失败, 请稍后重试.');
                }
            }
            if (!$hasData) {
                $sql = 'DELETE FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid';
                $params = array();
                $params[':reid'] = $reid;
                pdo_query($sql, $params);
                foreach ($_GPC['title'] as $k => $v) {
                    $field = array();
                    $field['reid'] = $reid;
                    $field['title'] = trim($v);
                    $field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
                    $field['type'] = $_GPC['type'][$k];
                    $field['essential'] = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
                    $field['bind'] = $_GPC['bind'][$k];
                    $field['value'] = $_GPC['value'][$k];
                    $field['value'] = urldecode($field['value']);
                    $field['description'] = $_GPC['desc'][$k];
                    pdo_insert('dayu_vote_fields', $field);
                }
            }
            message('保存投票成功.', 'refresh');
        }
        $types = array();
        $types['number'] = '数字(number)';
        $types['text'] = '字串(text)';
        $types['textarea'] = '文本(textarea)';
        $types['radio'] = '单选(radio)';
        $types['checkbox'] = '多选(checkbox)';
        $types['select'] = '选择(select)';
        $types['calendar'] = '日历(calendar)';
        $types['email'] = '电子邮件(email)';
        $types['image'] = '上传图片(image)';
        $types['range'] = '时间(range)';
        $types['reside'] = '居住地(reside)';
        $fields = fans_fields();
        if ($reid) {
            $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $params = array();
            $params[':weid'] = $_W['uniacid'];
            $params[':reid'] = $reid;
            $activity = pdo_fetch($sql, $params);
            $activity['starttime'] && ($activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']));
            $activity['endtime'] && ($activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']));
            if ($activity) {
                $sql = 'SELECT * FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
                $params = array();
                $params[':reid'] = $reid;
                $ds = pdo_fetchall($sql, $params);
            }
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $reply = pdo_fetch($sql, $params);
        if (!$reply) {
            $activity = array("mname" => "我的投票", "kfirst" => "有新的客户提交投票", "kfoot" => "啦啦啦，自定义", "mfirst" => "投票结果通知", "mfoot" => "如有疑问，请致电联系我们。", "information" => "感谢您的宝贵一票！", "pretotal" => "1", "votenum" => 1, "endtime" => date('Y-m-d H:i:s', strtotime('+30 day')));
        }
        include $this->template('post');
    }
    public function doWebReward()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $weid = $_W['uniacid'];
        $reid = intval($_GPC['reid']);
        $credit = intval($_GPC['credit']);
        $activity = pdo_fetch('SELECT reid,title FROM ' . tablename('dayu_vote') . ' WHERE weid = :weid AND reid = :reid', array(':weid' => $weid, ':reid' => $reid));
        $item = pdo_fetchall("SELECT * FROM " . tablename('dayu_vote_item') . " WHERE weid = :weid AND reid = :reid ORDER BY displayorder DESC,id DESC", array(':weid' => $weid, ':reid' => $reid));
        $setting = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
        $behavior = $setting['creditbehaviors'];
        $creditnames = $setting['creditnames'];
        if (checksubmit('submit')) {
            $time = date('Y-m-d H:i', time());
            $member = pdo_fetchall("SELECT rerid,openid FROM " . tablename('dayu_vote_info') . " WHERE reid=:reid AND vid=:vid", array(':vid' => $_GPC['item'], ':reid' => $reid));
            if (is_array($member)) {
                foreach ($member as $s) {
                    mc_credit_update(mc_openid2uid($s['openid']), 'credit1', $credit, array(0, $activity['title'] . '-奖励' . $credit . '积分'));
                    $credits = mc_credit_fetch(mc_openid2uid($s['openid']), '*');
                    $template = array("touser" => $s['openid'], "template_id" => "p6B7nd-PGHwN5tsG3LE_nphH5kJYvwmwYkeKsuGrvhs", "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageform', array('name' => 'dayu_form', 'weid' => $row['weid'], 'id' => $row['reid'])), "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode("您的积分账户有新的变动，具体内容如下：\\n"), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($time), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($credit), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($activity['title'] . '-奖励'), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($credits[$behavior['activity']] . "\\n"), 'color' => "#FF0000"), 'remark' => array('value' => urlencode("感谢您的投票"), 'color' => "#008000")));
                    $this->send_template_message(urldecode(json_encode($template)));
                    pdo_update('dayu_vote_info', array('status' => 1), array('rerid' => $s['rerid']));
                }
                pdo_update('dayu_vote', array('reward' => 1), array('reid' => $reid));
                message('积分发放完毕.', $this->createWebUrl('display'));
            }
        }
        include $this->template('reward');
    }
    public function doWebbatchrecord()
    {
        global $_GPC, $_W;
        $reid = intval($_GPC['reid']);
        $reply = pdo_fetch("select reid from " . tablename('dayu_vote') . " where reid = :reid", array(':reid' => $reid));
        if (empty($reply)) {
            $this->showMessage('抱歉，预约主题不存在或是已经被删除！');
        }
        foreach ($_GPC['idArr'] as $k => $rerid) {
            $rerid = intval($rerid);
            pdo_delete('dayu_vote_info', array('rerid' => $rerid, 'reid' => $reid));
            pdo_delete('dayu_vote_data', array('rerid' => $rerid, 'reid' => $reid));
        }
        $this->showMessage('记录批量删除成功！', '', 0);
    }
    public function get_userinfo($weid, $from_user)
    {
        load()->model('mc');
        return mc_fetch($from_user);
    }
    public function doMobiledayu_vote()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $userinfo = $this->get_userinfo($weid, $openid);
        $acc = notice_init();
        if (is_error($acc)) {
            return error(-1, $acc['message']);
        }
        $reid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $weid;
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        if ($activity['follow'] == 1) {
            $this->getFollow();
        }
        $title = $activity['title'];
        $time = date('Y-m-d H:i', time() + 3600);
        if ($activity['status'] != '1') {
            message('当前投票已经停止.');
        }
        if (!$activity) {
            message('非法访问.');
        }
        if ($activity['starttime'] > TIMESTAMP) {
            message('当前投票还未开始！');
        }
        if ($activity['endtime'] < TIMESTAMP) {
            message('当前投票已经结束！');
        }
        $pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_vote_info') . " WHERE reid = :reid AND openid = :openid", array(':reid' => $reid, ':openid' => $openid));
        pdo_update('dayu_vote', array('view' => intval($activity['view']) + 1), array('reid' => $reid));
        $members = pdo_fetchall('SELECT * FROM ' . tablename('mc_members') . ' WHERE uniacid = :weid ORDER BY credit1 DESC, uid DESC LIMIT 20', array(':weid' => $weid));
        foreach ($members as $index => $mv) {
            $members[$index]['avatar'] = !empty($mv['avatar']) ? tomedia($mv['avatar']) : 'resource/images/heading.jpg';
        }
        $item = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_item') . ' WHERE weid = :weid AND reid = :reid ORDER BY displayorder DESC, id DESC', array(':weid' => $weid, ':reid' => $reid));
        $gift = pdo_fetchall('SELECT * FROM ' . tablename('dayu_vote_gift') . ' WHERE weid = :weid AND reid = :reid ORDER BY `displayorder` DESC', array(':weid' => $weid, ':reid' => $reid));
        foreach ($gift as &$value) {
            $value['thumb'] = tomedia($value['thumb']);
        }
        $sql = 'SELECT * FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
        $params = array();
        $params[':reid'] = $reid;
        $ds = pdo_fetchall($sql, $params);
        $initRange = $initCalendar = false;
        $binds = array();
        foreach ($ds as &$r) {
            if ($r['type'] == 'range') {
                $initRange = true;
            }
            if ($r['type'] == 'calendar') {
                $initCalendar = true;
            }
            if ($r['value']) {
                $r['options'] = explode(',', $r['value']);
            }
            if ($r['bind']) {
                $binds[$r['type']] = $r['bind'];
            }
            if ($r['type'] == 'reside') {
                $reside = $r;
            }
        }
        if (checksubmit('submit')) {
            if ($activity['select'] == 0) {
                $num = trim($_GPC['item']) ? trim($_GPC['item']) : message('投票对象不能为空');
            }
            $row = array();
            $row['reid'] = $reid;
            $row['member'] = $_GPC['member'];
            $row['mobile'] = $_GPC['mobile'];
            if ($activity['select'] == 1) {
                $row['checkbox'] = implode(',', $_GPC['item']);
            } else {
                $row['vid'] = $_GPC['item'];
            }
            $row['openid'] = $openid;
            $row['createtime'] = TIMESTAMP;
            if ($activity['select'] == 1) {
                foreach ($_GPC['item'] as $k => $voteid) {
                    $voteid = intval($voteid);
                    $vote = pdo_fetch("SELECT * FROM " . tablename('dayu_vote_item') . " WHERE reid = :reid and id = :id", array(':reid' => $reid, ':id' => $voteid));
                    pdo_update('dayu_vote_item', array('num' => intval($vote['num']) + 1), array('id' => $vote['id']));
                    pdo_update('dayu_vote', array('num' => intval($activity['num']) + 1), array('reid' => $reid));
                }
            } elseif ($activity['select'] == 0) {
                $vote = pdo_fetch("SELECT * FROM " . tablename('dayu_vote_item') . " WHERE reid = :reid and id = :id", array(':reid' => $reid, ':id' => $row['vid']));
                pdo_update('dayu_vote_item', array('num' => intval($vote['num']) + 1), array('id' => $vote['id']));
                pdo_update('dayu_vote', array('num' => intval($activity['num']) + 1), array('reid' => $reid));
            }
            $datas = $fields = $update = array();
            foreach ($ds as $value) {
                $fields[$value['refid']] = $value;
            }
            foreach ($_GPC as $key => $value) {
                if (strexists($key, 'field_')) {
                    $bindFiled = substr(strrchr($key, '_'), 1);
                    if (!empty($bindFiled)) {
                        $update[$bindFiled] = $value;
                    }
                    $refid = intval(str_replace('field_', '', $key));
                    $field = $fields[$refid];
                    if ($refid && $field) {
                        $entry = array();
                        $entry['reid'] = $reid;
                        $entry['rerid'] = 0;
                        $entry['refid'] = $refid;
                        if (in_array($field['type'], array('number', 'text', 'calendar', 'email', 'textarea', 'radio', 'range', 'select', 'image'))) {
                            $entry['data'] = strval($value);
                        }
                        if (in_array($field['type'], array('checkbox'))) {
                            if (!is_array($value)) {
                                continue;
                            }
                            $entry['data'] = implode(';', $value);
                        }
                        $datas[] = $entry;
                    }
                }
            }
            if ($_FILES) {
                load()->func('file');
                foreach ($_FILES as $key => $file) {
                    if (strexists($key, 'field_')) {
                        $refid = intval(str_replace('field_', '', $key));
                        $field = $fields[$refid];
                        if ($refid && $field && $file['name'] && $field['type'] == 'image') {
                            $entry = array();
                            $entry['reid'] = $reid;
                            $entry['rerid'] = 0;
                            $entry['refid'] = $refid;
                            $ret = file_upload($file);
                            if (!$ret['success']) {
                                message('上传图片失败, 请稍后重试.');
                            }
                            $entry['data'] = trim($ret['path']);
                            $datas[] = $entry;
                        }
                    }
                }
            }
            if (!empty($_GPC['reside'])) {
                if (in_array('reside', $binds)) {
                    $update['resideprovince'] = $_GPC['reside']['province'];
                    $update['residecity'] = $_GPC['reside']['city'];
                    $update['residedist'] = $_GPC['reside']['district'];
                }
                foreach ($_GPC['reside'] as $key => $value) {
                    $resideData = array('reid' => $reside['reid']);
                    $resideData['rerid'] = 0;
                    $resideData['refid'] = $reside['refid'];
                    $resideData['data'] = $value;
                    $datas[] = $resideData;
                }
            }
            if ($activity['isreplace']) {
                $upinfo['realname'] = $_GPC['member'];
                $upinfo['mobile'] = $_GPC['mobile'];
                load()->model('mc');
                mc_update($uid, $upinfo);
            }
            if (pdo_insert('dayu_vote_info', $row) != 1) {
                message('保存失败.');
            }
            $rerid = pdo_insertid();
            if (empty($rerid)) {
                message('保存失败.');
            }
            if (!empty($datas)) {
                foreach ($datas as &$r) {
                    $r['rerid'] = $rerid;
                    pdo_insert('dayu_vote_data', $r);
                }
            }
            if (empty($activity['starttime'])) {
                $record = array();
                $record['starttime'] = TIMESTAMP;
                pdo_update('dayu_vote', $record, array('reid' => $reid));
            }
            foreach ($datas as $row) {
                $img = "<img src='{$_W['attachurl']}";
                if (substr($row['data'], 0, 6) == 'images') {
                    $body = $fields[$row['refid']]['title'] . ':' . $img . $row['data'] . " ' width='90';height='120'/>";
                }
                $body .= '<h4>' . $fields[$row['refid']]['title'] . ':' . $row['data'] . '</h4>';
                $bodym .= $fields[$row['refid']]['title'] . ':' . $row['data'] . ',';
            }
            load()->func('communication');
            ihttp_email($activity['noticeemail'], $activity['title'] . '的投票提醒', '<h4>姓名：' . $_GPC['member'] . '</h4><h4>手机：' . $_GPC['mobile'] . '</h4>' . $body);
            $ytime = date('Y-m-d H:i:s', TIMESTAMP);
            if ($activity['select'] == 0) {
                $votes = $vote['title'];
            } elseif ($activity['select'] == 1) {
                $votes = "多选";
            }
            if ($activity['custom_status'] == 0) {
                $staff = pdo_fetchall("SELECT `openid` FROM " . tablename('dayu_vote_staff') . " WHERE reid=" . $row['reid'] . " AND weid=" . $weid);
                if (is_array($staff)) {
                    foreach ($staff as $s) {
                        $template = array("touser" => $s['openid'], "template_id" => $activity['k_templateid'], "topcolor" => "#FF0000", "data" => array('first' => array('value' => urlencode($activity['kfirst']), 'color' => "#743A3A"), 'keyword1' => array('value' => urlencode($_GPC['member']), 'color' => '#000000'), 'keyword2' => array('value' => urlencode($_GPC['mobile']), 'color' => '#000000'), 'keyword3' => array('value' => urlencode($ytime), 'color' => '#000000'), 'keyword4' => array('value' => urlencode($votes), 'color' => "#FF0000"), 'remark' => array('value' => urlencode($activity['kfoot']), 'color' => "#008000")));
                        $this->send_template_message(urldecode(json_encode($template)));
                    }
                }
            } else {
                $staff = pdo_fetchall("SELECT `openid` FROM " . tablename('dayu_vote_staff') . " WHERE reid=" . $row['reid'] . " AND weid=" . $_W['uniacid']);
                if (is_array($staff)) {
                    foreach ($staff as $s) {
                        $info = "【您好，有新的投票】\n\n";
                        $info .= "姓名：{$_GPC['member']}\n手机：{$_GPC['mobile']}\n投票：{$votes}\n\n";
                        $custom = array('msgtype' => 'text', 'text' => array('content' => urlencode($info)), 'touser' => $s['openid']);
                        $acc->sendCustomNotice($custom);
                    }
                }
            }
            message($activity['information'], $this->createMobileUrl('success', array('name' => 'dayu_vote', 'weid' => $row['weid'], 'id' => $row['reid'])));
        }
        foreach ($binds as $key => $value) {
            if ($value == 'reside') {
                unset($binds[$key]);
                $binds[] = 'resideprovince';
                $binds[] = 'residecity';
                $binds[] = 'residedist';
                break;
            }
        }
        if (!empty($openid) && !empty($binds)) {
            $profile = fans_search($openid, $binds);
            if ($profile['gender']) {
                if ($profile['gender'] == '0') {
                    $profile['gender'] = '保密';
                }
                if ($profile['gender'] == '1') {
                    $profile['gender'] = '男';
                }
                if ($profile['gender'] == '2') {
                    $profile['gender'] = '女';
                }
            }
            foreach ($ds as &$r) {
                if ($profile[$r['bind']]) {
                    $r['default'] = $profile[$r['bind']];
                }
            }
        }
        load()->func('tpl');
        $_share['title'] = $activity['title'];
        $_share['content'] = $activity['description'];
        $_share['imgUrl'] = tomedia($activity['thumb']);
        $skins = !empty($activity['skins']) ? $activity['skins'] : 'submit';
        include $this->template($skins);
    }
    public function doMobileSuccess()
    {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        $activity['bg'] = tomedia($activity['bg']);
        $_share['title'] = $activity['title'];
        $_share['content'] = $activity['description'];
        $_share['imgUrl'] = tomedia($activity['thumb']);
        $_share['link'] = $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_vote', array('weid' => $_W['uniacid'], 'id' => $reid));
        include $this->template('success');
    }
    public function doMobileMydayu_vote()
    {
        global $_W, $_GPC;
        require 'fans.mobile.php';
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $reid = intval($_GPC['id']);
        $sql = 'SELECT * FROM ' . tablename('dayu_vote') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params = array();
        $params[':weid'] = $weid;
        $params[':reid'] = $reid;
        $activity = pdo_fetch($sql, $params);
        $list = pdo_fetchall("SELECT * FROM " . tablename('dayu_vote') . " WHERE weid = '{$weid}' and status='1' ORDER BY reid DESC", array(), 'reid');
        if ($operation == 'display') {
            if ($reid) {
                $rows = pdo_fetchall("SELECT * FROM " . tablename('dayu_vote_info') . " WHERE openid = :openid and reid = :reid ORDER BY rerid DESC ", array(':openid' => $openid, ':reid' => $reid));
            } else {
                $rows = pdo_fetchall("SELECT * FROM " . tablename('dayu_vote_info') . " WHERE openid = :openid ORDER BY rerid DESC ", array(':openid' => $openid));
            }
            foreach ($rows as $key => $value) {
                if ($activity['select'] == 0) {
                    $rows[$key]['vote'] = $this->get_item($value['reid'], $value['vid']);
                } elseif ($activity['select'] == 1) {
                    $rows[$key]['options'] = explode(',', $value['checkbox']);
                }
            }
        } elseif ($operation == 'detail') {
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename('dayu_vote_info') . " WHERE openid = :openid AND rerid = :rerid", array(':openid' => $openid, ':rerid' => $id));
            if (empty($row)) {
                message('我的投票不存在或是已经被删除！');
            }
            $row['options'] = explode(',', $row['checkbox']);
            $vote = $this->get_item($row['reid'], $row['vid']);
            $dayu_vote = pdo_fetch("SELECT * FROM " . tablename('dayu_vote') . " WHERE reid = :reid", array(':reid' => $row['reid']));
            $dayu_vote['content'] = htmlspecialchars_decode($dayu_vote['content']);
            $sql = 'SELECT * FROM ' . tablename('dayu_vote_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
            $params = array();
            $params[':reid'] = $row['reid'];
            $fields = pdo_fetchall($sql, $params);
            if (!empty($fields)) {
                $ds = $fids = array();
                foreach ($fields as $f) {
                    $ds[$f['refid']]['fid'] = $f['title'];
                    $ds[$f['refid']]['type'] = $f['type'];
                    $ds[$f['refid']]['refid'] = $f['refid'];
                    $fids[] = $f['refid'];
                }
                $fids = implode(',', $fids);
                $row['fields'] = array();
                $sql = 'SELECT * FROM ' . tablename('dayu_vote_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
                $fdatas = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    $row['fields'][$fd['refid']] = $fd['data'];
                }
                foreach ($ds as $value) {
                    if ($value['type'] == 'reside') {
                        $row['fields'][$value['refid']] = '';
                        foreach ($fdatas as $fdata) {
                            if ($fdata['refid'] == $value['refid']) {
                                $row['fields'][$value['refid']] .= $fdata['data'];
                            }
                        }
                        break;
                    }
                }
            }
        }
        include $this->template('dayu_vote');
    }
    public function send_template_message($data)
    {
        global $_W, $_GPC;
        $atype = 'weixin';
        $account_code = "account_weixin_code";
        load()->classs('weixin.account');
        $access_token = WeAccount::token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $response = ihttp_request($url, $data);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty($result)) {
            return error(-1, "接口调用失败, 原数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }
        return true;
    }
    public function get_item($reid, $itemid)
    {
        return pdo_fetch("SELECT * FROM " . tablename('dayu_vote_item') . " WHERE reid = :reid and id = :id", array(':reid' => $reid, ':id' => $itemid));
    }
    public function doMobileFansUs()
    {
        global $_W, $_GPC;
        $qrcodesrc = tomedia('qrcode_' . $_W['acid'] . '.jpg');
        include $this->template('fans_us');
    }
    public function get_curl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, 1);
    }
    public function post_curl($url, $post = '')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data, 1);
    }
    private function getCode()
    {
        global $_GPC, $_W;
        $appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];
        $level = $_W['account']['level'];
        if ($level == 4) {
            $oauth_openid = "dayu_vote_" . $_W['uniacid'];
            if (empty($_COOKIE[$oauth_openid])) {
                $redirect_uri = url('entry&do=GetToken&m=dayu_vote', '', true);
                $redirect_uri = $_W['siteroot'] . 'app/' . $redirect_uri;
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=snsapi_base&state=0#wechat_redirewct';
                header('Location: ' . $url, true, 301);
            }
        } else {
            return '';
        }
    }
    public function doMobileGetToken()
    {
        global $_GPC, $_W;
        $appid = $_W['account']['key'];
        $secret = $_W['account']['secret'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret . '&code=' . $_GPC['code'] . '&grant_type=authorization_code';
        $data = $this->get_curl($url);
        if (empty($data)) {
            $data = file_get_contents($url);
            $data = json_decode($data, 1);
        }
        $oauth_openid = "dayu_vote_" . $_W['uniacid'];
        setcookie($oauth_openid, $data['openid'], time() + self::$COOKIE_DAYS * 24 * 60 * 60);
        header('Location:' . $this->createMobileUrl('dayu_vote'), true, 301);
    }
    public function getFollow()
    {
        global $_GPC, $_W;
        $p = pdo_fetch("SELECT follow FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :weid AND openid = :openid LIMIT 1", array(":weid" => $this->weid, ":openid" => $_W['openid']));
        if (intval($p['follow']) == 0) {
            header('Location: ' . $this->createMobileUrl('FansUs'), true, 301);
        } else {
            return true;
        }
    }
}
function https_post($url, $datas)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
    curl_setopt($curl, CURLOPT_SSL_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        return 'Errno' . curl_error($curl);
    }
    curl_close($curl);
    return $result;
}
function notice_init()
{
    global $_W;
    $acc = WeAccount::create();
    if (is_null($acc)) {
        return error(-1, '创建公众号操作对象失败');
    }
    return $acc;
}
