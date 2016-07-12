<?php

defined('IN_IA') or exit('Access Denied');
class Ewei_voteModuleSite extends WeModuleSite {
    public $tablename = 'vote_reply';
    public $tablefans = 'vote_fans';

    public function getItemTiles() {
        global $_W;
        $articles = pdo_fetchall("SELECT id,rid, title FROM " . tablename('vote_reply') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($articles)) {
            foreach ($articles as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));
            }
            return $urls;
        }
    }

    public function doMobileindex() {
        global $_GPC, $_W;
        $rid = $_GPC['id'];
        $weid = $_W['uniacid'];
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $from_user = $_W['fans']['from_user'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
        if ($reply == false) {
            message('活动已经取消了！', '', 'error');
        }
        $nowtime = time();
        $endtime = $reply['endtime'] + 86399;
        if ($reply['status'] == 0) {
            message('投票已经暂停！', '', 'error');
        }
        if ($reply['votelimit'] == 1) {
            if ($reply['votenum'] >= $reply['votetotal']) {
                message('投票人数已满！', '', 'error');
            }
        } else {
            if ($reply['starttime'] > $nowtime) {
                message('投票未开始！', '', 'error');
            } elseif ($endtime < $nowtime) {
                message('投票已经结束！', '', 'error');
            }
        }

        if ( (empty($_W['fans'])) && ($_GPC['share'] == 1) ) {
            //301跳转
            if (!empty($reply['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $reply['share_url'] . "");
                exit();
            }
            $isshare = 1;
            $running = false;
            $msg = '请先关注公共号。';
        } else {
            $isshare = 0;
        }
        $limits = "";
        if ($reply['votelimit'] == 1) {
            $limits = "参数人数 " . $reply['votenum'] . " /  允许总数 " . $reply['votetotal'];
        } else {
            $limits = "投票期限: " . date('Y-m-d H:i', $reply['starttime']) . " 至 " . date('Y-m-d H:i', $endtime);
        }
        $selects = "";
        if ($reply['votetype'] == 0) {
            $selects = "最多选择一项";
        } else {
            $selects = "可以选择多项";
        }
        //判断有没有投票过
        $votetimes = pdo_fetch("SELECT count(*) as cnt FROM " . tablename('vote_fans') . "where rid=" . $rid . " and from_user='" . $_W['fans']['from_user'] . "'");
        $votetimes = $votetimes['cnt'];
        $isvote = $votetimes > 0;
        $list = pdo_fetchall("SELECT * FROM " . tablename('vote_option') . " WHERE rid = :rid ORDER by `id` ASC", array(':rid' => $rid));
        $sumnum = pdo_fetchcolumn("SELECT sum(vote_num) FROM " . tablename('vote_option') . " WHERE rid = :rid ", array(':rid' => $rid));
        foreach ($list as &$r) {
            if ($sumnum == 0) {
                $r['percent'] = 0;
            } else {
                $r['percent'] = floor($r['vote_num']  / $sumnum * 100);
            }
        }
        unset($r);
        //判断粉丝是否要继续投票
        $can = true;
        if($reply['votetimes']>0){
            if($votetimes >= $reply['votetimes']){
                $can = false;
            }
        }
        $canvotetimes = intval($reply['votetimes'] - $votetimes);

        //分享信息
        $sharelink = empty($reply['share_url']) ? ($_W['siteroot'] .'app' . ltrim($this->createMobileUrl('index', array('id' => $rid, 'name' => 'vote', 'share' => 1)),'.') ): $reply['share_url'];
        $sharetitle = empty($reply['share_title']) ? '欢迎参加投票活动' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，欢迎参加投票活动！' : $reply['share_desc'];
        $shareimg = tomedia($reply['thumb']);
        if($can) {
            pdo_fetch("UPDATE " . tablename('vote_reply') . " SET viewnum = (viewnum + 1) WHERE rid = :rid AND weid = :weid", array(':rid' => $rid, ':weid' => $weid));
            include $this->template('vote-content');
        } else {
            include $this->template('vote-end');
        }

    }

    public function doMobileSubmit() {
        global $_GPC, $_W;
        //判断用户是否存在
        $rid = $_GPC['id'];
        $from_user =$_W['fans']['from_user'];

        if (empty($rid)) {
            die("参数错误!");
        }
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        if (!$reply) {
            die("参数错误!");
        }

        if ($reply['status'] == 0) {
            die("投票已经暂停!");
        }

        $nowtime = time();
        $endtime = $reply['endtime'] + 86399;

        if ($reply['votelimit'] == 1) {
            if ($reply['votenum'] >= $reply['votetotal']) {
                die("投票人数已满!");
            }
        } else {
            if ($reply['starttime'] > $nowtime) {
                die("投票未开始!");
            } elseif ($endtime < $nowtime) {

                die("投票已经结束!");
            } else {
//                if ($reply['status'] == 1) {
//
//                } else {
//                    die("投票已经暂停!");
//                }
            }
        }
        //print_r($reply);exit;

        //判断用户投票次数
        $vc =  pdo_fetch("select count(*) as cnt from ".tablename('vote_fans')." where from_user=:from_user and rid=:rid",array(":from_user"=>$from_user,":rid"=>$rid));
        if($reply['votetimes']>0 && $vc['cnt']>=$reply['votetimes']) {
            //今天已经投票过了
            die('您已经超过投票次数了!');

        } else {

            $ids = $_GPC['ids'];
            if(empty($ids)){
                die("参数错误!");
            }
            //粉丝投票次数
            pdo_insert('vote_fans', array('from_user'=>$from_user,'rid'=>$rid, 'votes' => $ids,'votetime'=>time()));
            //参与人数
            pdo_update('vote_reply', array('votenum' => ($reply['votenum'] + 1)), array('rid' =>$rid));
            //投票记录
            $item_ids = explode(",",$ids);
            foreach($item_ids as $item_id){
                //查找投票项是否存在
                $vote = pdo_fetch("SELECT * FROM " . tablename('vote_option') . " WHERE rid = :rid and id=" . $item_id . " ORDER by `id` ASC", array(':rid' => $rid));
                if($vote){
                    pdo_update('vote_option', array('vote_num' => ($vote['vote_num'] + 1)), array('id' =>$item_id));
                }
            }
            die('');
        }

    }

    public function doMobileresult() {
        global $_GPC, $_W;

        $rid = $_GPC['id'];
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $from_user = $_W['fans']['from_user'];
        $reply = pdo_fetch("SELECT * FROM " . tablename('vote_reply') . " WHERE `rid`=:rid LIMIT 1", array(':rid' => $rid));
        if ($reply == false) {
            message('活动已经取消了！', '', 'error');
        }

        $limits = "";
        if ($reply['votelimit'] == 1) {
            $limits = "参数人数 " . $reply['votenum'] . " /  允许总数 " . $reply['votetotal'];
        } else {
            $endtime = $reply['endtime'] + 86399;
            $limits = "投票期限: " . date('Y-m-d H:i', $reply['starttime']) . " 至 " . date('Y-m-d H:i', $endtime);
        }
        $selects = "";
        if ($reply['votetype'] == 0) {
            $selects = "最多选择一项";
        } else {
            $selects = "可以选择多项";
        }
        //判断有没有投票过
        $votetimes = pdo_fetchcolumn("SELECT count(*) as cnt FROM " . tablename('vote_fans') . "where rid=" . $rid . " and from_user='" . authcode(base64_decode($_GPC['from_user']), 'DECODE') . "'");

        $list = pdo_fetchall("SELECT * FROM " . tablename('vote_option') . " WHERE rid = :rid ORDER by `id` ASC", array(':rid' => $rid));
        $sumnum = pdo_fetch("SELECT sum(vote_num) FROM " . tablename('vote_option') . " WHERE rid = :rid ", array(':rid' => $rid));
        $sumnum = $sumnum["sum(vote_num)"];

        foreach ($list as &$r) {


            if ($sumnum == 0) {
                $r['percent'] = 0;
            } else {
                $r['percent'] = floor($r['vote_num'] * 100 / $sumnum);
            }
        }
        unset($r);
        //分享信息
        $sharelink = empty($reply['share_url']) ? ($_W['siteroot'] .'app' . ltrim($this->createMobileUrl('index', array('id' => $rid, 'name' => 'vote', 'share' => 1)),'.') ): $reply['share_url'];
        $sharetitle = empty($reply['share_title']) ? '欢迎参加投票活动' : $reply['share_title'];
        $sharedesc = empty($reply['share_desc']) ? '亲，欢迎参加投票活动！' : $reply['share_desc'];
        //$shareimg = $_W['siteroot'] . trim($reply['start_picurl'], '/');
        $shareimg = tomedia($reply['thumb']);

        include $this->template('vote-end');
    }



    public function doWebitem(){
        global $_GPC;
        $o = array(
            "id"=>  random(32),
            "type"=>$_GPC['type']
        );
        include $this->template('item');
    }
    public function doWebManage() {
        global $_GPC, $_W;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :weid AND `module` = :module";
        $params = array();
        $params[':weid'] = $_W['weid'];
        $params[':module'] = 'ewei_vote';

        if (isset($_GPC['keywords'])) {
            $sql .= ' AND `name` LIKE :keywords';
            $params[':keywords'] = "%{$_GPC['keywords']}%";
        }
        load()->model('reply');
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keywords'] = reply_keywords_search($condition);
                $vote = pdo_fetch("SELECT title,votenum,votetimes,votelimit,votetotal,viewnum,starttime,endtime,status FROM " . tablename('vote_reply') . " WHERE rid = :rid ", array(':rid' => $item['id']));
                $item['title'] = $vote['title'];
                $item['votenum'] = $vote['votenum'];
                $item['votetimes'] = $vote['votetimes'];
                $item['viewnum'] = $vote['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $vote['starttime']);
                $endtime = $vote['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);

                $limits = "";
                if ($vote['votelimit'] == 1) {
                    $limits = "允许投票 " . $vote['votetotal'] . " 人";
                } else {
                    $limits = "投票期限: " . date('Y-m-d H:i', $vote['starttime']) . " 至 " . date('Y-m-d H:i', $endtime);
                }
                $item['limits'] = $limits;

                $nowtime = time();
                if($item['votelimit']==1){
                    if ($item['votetotal']>0 && $item['votenum']>=$item['votetotal']) {
                        $item['status'] = '<span class="label">已结束</span>';
                        $item['show'] = 0;
                    } else  {
                        $item['status'] = '<span class="label label-succes">已开始</span>';
                        $item['show'] = 2;
                    }
                }else {
                    if ($vote['starttime'] > $nowtime) {
                        $item['status'] = '<span class="label label-default">未开始</span>';
                        $item['show'] = 1;
                    } elseif (($vote['endtime'] + 86399) < $nowtime) {
                        $item['status'] = '<span class="label label-danger">已结束</span>';
                        $item['show'] = 0;
                    } else {
                        if ($vote['status'] == 1) {
                            $item['status'] = '<span class="label label-success">已开始</span>';
                            $item['show'] = 2;
                        } else {
                            $item['status'] = '<span class="label label-default">已暂停</span>';
                            $item['show'] = 1;
                        }
                    }
                } }
        }
        include $this->template('manage');
    }

    public function doWebdelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['weid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }


        message('规则操作成功！', referer(), 'success');
    }

    public function doWebdeleteAll() {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid != 0) {
                $rule = pdo_fetch("SELECT id, module FROM " . tablename('rule') . " WHERE id = :id and uniacid=:weid", array(':id' => $rid, ':weid' => $_W['weid']));
                if (empty($rule)) {
                    $this->web_message('抱歉，要修改的规则不存在或是已经被删除！');
                }
                if (pdo_delete('rule', array('id' => $rid))) {
                    pdo_delete('rule_keyword', array('rid' => $rid));
                    //删除统计相关数据
                    pdo_delete('stat_rule', array('rid' => $rid));
                    pdo_delete('stat_keyword', array('rid' => $rid));
                    //调用模块中的删除
                    $module = WeUtility::createModule($rule['module']);
                    if (method_exists($module, 'ruleDeleted')) {
                        $module->ruleDeleted($rid);
                    }
                }
            }
        }
        $this->web_message('规则操作成功！', '', 0);
    }

    public function doWebstatus($rid = 0) {
        global $_GPC;
        $rid = $_GPC['rid'];
        $insert = array(
            'status' => $_GPC['status']
        );
        pdo_update($this->tablename, $insert, array('rid' => $rid));
        message('模块操作成功！', referer(), 'success');
    }

    public function doWebresult() {
        global $_GPC;
        $rid = $_GPC['id'];
        $list = pdo_fetchall("SELECT * FROM " . tablename('vote_option') . " WHERE rid = :rid ORDER by `id` asc", array(':rid' => $rid));
        foreach ($list as $v) {
            echo '<b>'.$v['title'] . '</b><br>选票:<span style="color:#ff6600;font-weight:bold">' . $v['vote_num'] . '</span><br>';
        }
    }

    //投票记录
    public function doWebvotelist() {
        global $_W, $_GPC;
        checklogin();
        checkaccount();
        $rid = $_GPC['id'];
        $pindex = max(1, $_GPC['page']);
        $psize = 15;
        $list = pdo_fetchall("select from_user,votes,votetime from " . tablename('vote_fans') . " where rid={$rid} order by votetime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        foreach($list as &$r)
        {
            $votes = "";
            $options = pdo_fetchall("select title from ".tablename('vote_option')." where id in (".$r['votes'].")");
            foreach($options as $o){
                $votes.=mb_substr($o['title'],0,10,"utf-8")."<br/>";
            }
            $r['votes'] = $votes;
        }
        unset($r);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('vote_fans') . " where rid={$rid} order by votetime desc");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('list');
    }


    public function web_message($error, $url = '', $errno = -1) {
        $data = array();
        $data['errno'] = $errno;
        if (!empty($url)) {
            $data['url'] = $url;
        }
        $data['error'] = $error;
        echo json_encode($data);
        exit;
    }

}
