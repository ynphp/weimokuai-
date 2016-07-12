<?php
defined('IN_IA') or exit('Access Denied');
class bm_qrsignModuleSite extends WeModuleSite
{
    public $weid;
    public function __construct()
    {
        global $_W;
        $this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
    }
    public function doWebRecord()
    {
        global $_GPC, $_W;
        checklogin();
        load()->func('tpl');
        $rid       = intval($_GPC['id']);
        $condition = '';
        if (!empty($_GPC['username'])) {
            $condition .= " AND username like '%{$_GPC['username']}%' ";
        }
        if (!empty($_GPC['sign_time'])) {
            $condition .= " AND sign_time = '%{$_GPC['username']}%' ";
        }
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = TIMESTAMP;
        }
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']) + 86399;
            $condition .= " AND sign_time >= '{$starttime}' AND sign_time <= '{$endtime}' ";
        }
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 20;
        $list        = pdo_fetchall("SELECT * FROM " . tablename('bm_qrsign_record') . " WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total       = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_record') . " WHERE rid = '$rid' ");
        $pager       = pagination($total, $pindex, $psize);
        $memberlist  = pdo_fetchall("SELECT distinct fromuser FROM " . tablename('bm_qrsign_record') . "  WHERE rid = '$rid' ");
        $membertotal = count($memberlist);
        include $this->template('record');
    }
    public function doWebWinner()
    {
        global $_GPC, $_W;
        checklogin();
        $rid       = intval($_GPC['id']);
        $condition = '';
        if (!empty($_GPC['username'])) {
            $condition .= " AND username like '%{$_GPC['username']}%' ";
        }
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 20;
        $list        = pdo_fetchall("SELECT * FROM " . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total       = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' ");
        $pager       = pagination($total, $pindex, $psize);
        $memberlist  = pdo_fetchall("SELECT distinct from_user FROM " . tablename('bm_qrsign_winner') . "  WHERE rid = '$rid' ");
        $membertotal = count($memberlist);
        include $this->template('winner');
    }
    public function doWebPlay()
    {
        global $_GPC, $_W;
        checklogin();
        $rid   = intval($_GPC['id']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if ($reply['qrtype'] == 0) {
            $list = pdo_fetchall("SELECT id,username as content,fromuser as from_user,'text' as type,sign_time as createtime,username as nickname,avatar FROM " . tablename('bm_qrsign_record') . " WHERE rid = '$rid' and play_status=0 ORDER BY id DESC");
        } else {
            $list = pdo_fetchall("SELECT id,username as content,fromuser as from_user,'text' as type,dateline as createtime,username as nickname,avatar FROM " . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' and play_status=0 and status=1 ORDER BY id DESC");
        }
        include $this->template('play');
    }
    public function doWebAjaxsubmit()
    {
        global $_GPC, $_W;
        $rid    = intval($_GPC['rid']);
        $reply  = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $result = $reply['play_status'];
        echo $result;
    }
    public function doWebAddwinner()
    {
        global $_GPC, $_W;
        checklogin();
        $rid   = intval($_GPC['id']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if ($reply['qrtype'] == 0) {
            $message = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_record') . " WHERE id = :id LIMIT 1", array(
                ':id' => intval($_GPC['mid'])
            ));
        } else {
            $message = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_payed') . " WHERE id = :id LIMIT 1", array(
                ':id' => intval($_GPC['mid'])
            ));
        }
        if (empty($message)) {
            message('抱歉，参数不正确！', '', 'error');
        }
        $data = array(
            'rid' => $message['rid'],
            'from_user' => $message['fromuser'],
            'dateline' => TIMESTAMP,
            'username' => $message['username'],
            'avatar' => $message['avatar'],
            'status' => 0
        );
        pdo_insert('bm_qrsign_winner', $data);
        if ($reply['qrtype'] == 0) {
            pdo_update('bm_qrsign_record', array(
                'play_status' => 1,
                'play_time' => TIMESTAMP
            ), array(
                'id' => intval($_GPC['mid'])
            ));
        } else {
            pdo_update('bm_qrsign_payed', array(
                'play_status' => 1,
                'play_time' => TIMESTAMP
            ), array(
                'id' => intval($_GPC['mid'])
            ));
        }
        $url          = $reply['urly'];
        $template     = array(
            'touser' => $message['fromuser'],
            'template_id' => $reply['templateid'],
            'url' => $url,
            'topcolor' => "#7B68EE",
            'data' => array(
                'first' => array(
                    'value' => urlencode('恭喜您在' . $_W['account']['name'] . '的大屏幕活动中得奖！'),
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => urlencode($reply['awaremethod']),
                    'color' => "#FF0000"
                ),
                'keyword2' => array(
                    'value' => urlencode($reply['awaretime']),
                    'color' => "#0000FF"
                ),
                'remark' => array(
                    'value' => urlencode("感谢您的参与！"),
                    'color' => "#008000"
                )
            )
        );
        $sql          = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
        $row          = pdo_fetch($sql, array(
            ':acid' => $_W['account']['uniacid']
        ));
        $appid        = $row['key'];
        $appsecret    = $row['secret'];
        $url          = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret;
        $res          = $this->http_request($url);
        $result       = json_decode($res, true);
        $access_token = $result["access_token"];
        $lasttime     = time();
        $x            = $this->send_template_message(urldecode(json_encode($template)), $access_token);
        $result       = array(
            'errcode' => $x['errcode'],
            'errmsg' => $x['errmsg'],
            'msgid' => $x['msgid']
        );
        message('', '', 'success');
    }
    private function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    private function send_template_message($data, $access_token)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $res = $this->http_request($url, $data);
        return json_decode($res, true);
    }
    public function doWebGetaware()
    {
        global $_GPC, $_W;
        checklogin();
        $rid       = intval($_GPC['rid']);
        $condition = '';
        if (!empty($_GPC['username'])) {
            $condition .= " AND username like '%{$_GPC['username']}%' ";
        }
        if (!empty($_GPC['id'])) {
            $id = intval($_GPC['id']);
            pdo_update('bm_qrsign_winner', array(
                'status' => intval($_GPC['status'])
            ), array(
                'id' => $id
            ));
        }
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 20;
        $list        = pdo_fetchall("SELECT * FROM " . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total       = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' ");
        $pager       = pagination($total, $pindex, $psize);
        $memberlist  = pdo_fetchall("SELECT distinct from_user FROM " . tablename('bm_qrsign_winner') . "  WHERE rid = '$rid' ");
        $membertotal = count($memberlist);
        include $this->template('winner');
    }
    public function doWebPayed()
    {
        global $_GPC, $_W;
        checklogin();
        $rid       = intval($_GPC['id']);
        $condition = '';
        if (!empty($_GPC['username'])) {
            $condition .= " AND username like '%{$_GPC['username']}%' ";
        }
        $pindex       = max(1, intval($_GPC['page']));
        $psize        = 20;
        $list         = pdo_fetchall("SELECT * FROM " . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total        = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' ");
        $totalsuccess = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' and status=1");
        $pager        = pagination($total, $pindex, $psize);
        include $this->template('payed');
    }
    public function doMobileSign()
    {
        global $_W, $_GPC;
        $rid   = trim($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if (time() > strtotime($reply['endtime'])) {
            if (empty($reply['memo2'])) {
                $msg = '对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
            } else {
                $msg = $reply['memo2'];
            }
            message($msg, $reply['url2'], 'success');
        }
        if (time() < strtotime($reply['starttime'])) {
            if (empty($reply['memo1'])) {
                $msg = '对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
            } else {
                $msg = $reply['memo1'];
            }
            message($msg, $reply['url1'], 'success');
        }
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        if ($reply['pictype'] == 1) {
            if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)) {
                header("Location: " . $reply['urlx']);
                exit;
            }
        }
        $from_user = $_W['fans']['openid'];
        $rec       = pdo_fetch("select * from " . tablename('bm_qrsign_record') . " where rid= " . $rid . " and fromuser= '{$from_user}' order by sign_time desc");
        if (!empty($rec)) {
            $Date_1       = date("Y-m-d", time());
            $Date_2       = date("Y-m-d", $rec['sign_time']);
            $Date_List_a1 = explode("-", $Date_1);
            $Date_List_a2 = explode("-", $Date_2);
            $d1           = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);
            $d2           = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);
            $Days         = round(($d1 - $d2) / 3600 / 24);
            if ($Days == 0) {
                $msg = '感谢您的参与，每个人每天只可以签到一次哦！！！';
                message($msg, $reply['urly'], 'success');
            }
        }
        $insert = array(
            'rid' => $rid,
            'fromuser' => $from_user,
            'username' => $_W['fans']['nickname'],
            'avatar' => $_W['fans']['tag']['avatar'],
            'sign_time' => $_W['timestamp'],
            'credit' => $reply['n']
        );
        pdo_insert('bm_qrsign_record', $insert);
        $user       = fans_search($from_user);
        $sql_member = "SELECT a.uid FROM " . tablename('mc_mapping_fans') . " a inner join " . tablename('mc_members') . " b on a.uid=b.uid WHERE a.openid='{$from_user}'";
        $uid        = pdo_fetchcolumn($sql_member);
        mc_credit_update($uid, 'credit1', intval($reply['n']), array(
            0 => 'system',
            1 => '扫码签到送积分'
        ));
        $user = fans_search($from_user);
        $msg  = '恭喜签到成功，您已获得奖励积分' . $reply['n'] . '分，您目前的总积分为' . $user['credit1'] . '分！';
        message($msg, $reply['urly'], 'success');
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        $rid   = trim($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if (time() > strtotime($reply['endtime'])) {
            if (empty($reply['memo2'])) {
                $msg = '对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
            } else {
                $msg = $reply['memo2'];
            }
            message($msg, $reply['url2'], 'success');
        }
        if (time() < strtotime($reply['starttime'])) {
            if (empty($reply['memo1'])) {
                $msg = '对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
            } else {
                $msg = $reply['memo1'];
            }
            message($msg, $reply['url1'], 'success');
        }
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        if ($reply['pictype'] == 1) {
            if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)) {
                header("Location: " . $reply['urlx']);
                exit;
            }
        }
        $op        = trim($_GPC['op']);
        $qrmoney   = $_GPC['qrmoney'];
        $from_user = $_W['fans']['openid'];
        $qrtype    = $reply['qrtype'];
        if ($op == 'post') {
            if ($qrmoney < 0.01) {
                message('支付金额错误，请重新录入！', $this->createMobileUrl('show', array(
                    'rid' => $rid,
                    'from_user' => $from_user
                )), 'error');
            }
            $data = array(
                'rid' => $rid,
                'dateline' => TIMESTAMP,
                'clientOrderId' => TIMESTAMP,
                'qrmoney' => $qrmoney,
                'status' => 0,
                'fromuser' => $from_user,
                'username' => $_W['fans']['nickname'],
                'avatar' => $_W['fans']['tag']['avatar'],
                'credit' => $reply['n']
            );
            pdo_insert('bm_qrsign_payed', $data);
            $params = array(
                'tid' => $data['clientOrderId'],
                'ordersn' => $data['clientOrderId'],
                'title' => '扫码支付',
                'fee' => $data['qrmoney'],
                'user' => $from_user
            );
            $this->pay($params);
            exit;
        }
        if ($reply['qrmoney'] <= 0) {
            message('支付错误, 金额小于0');
        }
        if (!empty($reply['logo'])) {
            $qrpicurl = $_W['attachurl'] . $reply['logo'];
        } else {
            $qrpicurl = $_W['attachurl'] . $reply['qrcode'];
        }
        if ($reply['qrinput'] == 1) {
            include $this->template('show');
        } else {
            message('正在提交订单，请稍候', $this->createmobileurl('pay_exec', array(
                'rid' => $rid,
                'from_user' => $from_user
            )), 'success');
        }
    }
    public function doMobilePay_exec()
    {
        global $_W, $_GPC;
        $rid       = trim($_GPC['rid']);
        $from_user = trim($_GPC['from_user']);
        $reply     = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        $data      = array(
            'rid' => $rid,
            'dateline' => TIMESTAMP,
            'clientOrderId' => TIMESTAMP,
            'qrmoney' => $reply['qrmoney'],
            'status' => 0,
            'fromuser' => $from_user,
            'username' => $_W['fans']['nickname'],
            'avatar' => $_W['fans']['tag']['avatar'],
            'credit' => $reply['n']
        );
        pdo_insert('bm_qrsign_payed', $data);
        $params = array(
            'tid' => $data['clientOrderId'],
            'ordersn' => $data['clientOrderId'],
            'title' => '扫码支付',
            'fee' => $data['qrmoney'],
            'user' => $from_user
        );
        $this->pay($params);
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        $payed = pdo_fetch("select * from " . tablename('bm_qrsign_payed') . " where clientOrderId = '{$params['tid']}'");
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $payed['rid']
        ));
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            if (empty($payed['paytime']) || $payed['status'] <> 1) {
                $url          = $reply['urly'];
                $template     = array(
                    'touser' => $reply['openid'],
                    'template_id' => $reply['templateid1'],
                    'url' => $url,
                    'topcolor' => "#7B68EE",
                    'data' => array(
                        'first' => array(
                            'value' => urlencode($_W['account']['name'] . '有客户完成扫码支付！'),
                            'color' => "#743A3A"
                        ),
                        'keyword1' => array(
                            'value' => urlencode($payed['clientOrderId']),
                            'color' => "#FF0000"
                        ),
                        'keyword2' => array(
                            'value' => urlencode(date('Y-m-d H:i:s', time())),
                            'color' => "#0000FF"
                        ),
                        'keyword3' => array(
                            'value' => urlencode($payed['realmoney']),
                            'color' => "#0000FF"
                        ),
                        'remark' => array(
                            'value' => urlencode("客户Openid：" . $payed['fromuser']),
                            'color' => "#008000"
                        )
                    )
                );
                $sql          = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
                $row          = pdo_fetch($sql, array(
                    ':acid' => $_W['account']['uniacid']
                ));
                $appid        = $row['key'];
                $appsecret    = $row['secret'];
                $url          = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret;
                $res          = $this->http_request($url);
                $result       = json_decode($res, true);
                $access_token = $result["access_token"];
                $lasttime     = time();
                $x            = $this->send_template_message(urldecode(json_encode($template)), $access_token);
            }
            pdo_update('bm_qrsign_payed', array(
                'status' => 1,
                'paytime' => TIMESTAMP
            ), array(
                'clientOrderId' => $params['tid']
            ));
        }
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                if (empty($payed['paytime']) || $payed['status'] <> 1) {
                    $url          = $reply['urly'];
                    $template     = array(
                        'touser' => $reply['openid'],
                        'template_id' => $reply['templateid1'],
                        'url' => $url,
                        'topcolor' => "#7B68EE",
                        'data' => array(
                            'first' => array(
                                'value' => urlencode($_W['account']['name'] . '有客户完成扫码支付！'),
                                'color' => "#743A3A"
                            ),
                            'keyword1' => array(
                                'value' => urlencode($payed['clientOrderId']),
                                'color' => "#FF0000"
                            ),
                            'keyword2' => array(
                                'value' => urlencode(date('Y-m-d H:i:s', time())),
                                'color' => "#0000FF"
                            ),
                            'keyword3' => array(
                                'value' => urlencode($payed['realmoney']),
                                'color' => "#0000FF"
                            ),
                            'remark' => array(
                                'value' => urlencode("客户Openid：" . $payed['fromuser']),
                                'color' => "#008000"
                            )
                        )
                    );
                    $sql          = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
                    $row          = pdo_fetch($sql, array(
                        ':acid' => $_W['account']['uniacid']
                    ));
                    $appid        = $row['key'];
                    $appsecret    = $row['secret'];
                    $url          = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret;
                    $res          = $this->http_request($url);
                    $result       = json_decode($res, true);
                    $access_token = $result["access_token"];
                    $lasttime     = time();
                    $x            = $this->send_template_message(urldecode(json_encode($template)), $access_token);
                }
                pdo_update('bm_qrsign_payed', array(
                    'status' => 1,
                    'paytime' => TIMESTAMP
                ), array(
                    'clientOrderId' => $params['tid']
                ));
            } else {
                message($reply['qrerrormemo'], $reply['qrerrorurl'], 'success');
            }
        }
        message($reply['memo'], $reply['urly'], 'success');
    }
    public function doMobileShow()
    {
        global $_W, $_GPC;
        $rid   = trim($_GPC['rid']);
        $reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(
            ':rid' => $rid
        ));
        if (time() > strtotime($reply['endtime'])) {
            if (empty($reply['memo2'])) {
                $msg = '对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
            } else {
                $msg = $reply['memo2'];
            }
            message($msg, $reply['url2'], 'success');
        }
        if (time() < strtotime($reply['starttime'])) {
            if (empty($reply['memo1'])) {
                $msg = '对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
            } else {
                $msg = $reply['memo1'];
            }
            message($msg, $reply['url1'], 'success');
        }
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        if ($reply['pictype'] == 1) {
            if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)) {
                header("Location: " . $reply['urlx']);
                exit;
            }
        }
        $op        = trim($_GPC['op']);
        $qrmoney   = $_GPC['qrmoney'];
        $from_user = $_W['fans']['openid'];
        $qrpicurl  = $_W['attachurl'] . $reply['qrcode'];
        if ($op == 'post') {
            if ($qrmoney < 0.01) {
                message('支付金额错误，请重新录入！', $this->createMobileUrl('show', array(
                    'rid' => $rid,
                    'from_user' => $from_user
                )), 'error');
            }
            $data = array(
                'rid' => $rid,
                'dateline' => TIMESTAMP,
                'clientOrderId' => TIMESTAMP,
                'qrmoney' => $qrmoney,
                'status' => 0,
                'fromuser' => $from_user,
                'username' => $_W['fans']['nickname'],
                'avatar' => $_W['fans']['tag']['avatar'],
                'credit' => $reply['n']
            );
            pdo_insert('bm_qrsign_payed', $data);
            $params = array(
                'tid' => $data['clientOrderId'],
                'ordersn' => $data['clientOrderId'],
                'title' => '扫码支付',
                'fee' => $data['qrmoney'],
                'user' => $from_user
            );
            $this->pay($params);
            exit;
        } else {
            if ($op == 'sign') {
                $rec = pdo_fetch("select * from " . tablename('bm_qrsign_record') . " where rid= " . $rid . " and fromuser= '{$from_user}' order by sign_time desc");
                if (!empty($rec)) {
                    $Date_1       = date("Y-m-d", time());
                    $Date_2       = date("Y-m-d", $rec['sign_time']);
                    $Date_List_a1 = explode("-", $Date_1);
                    $Date_List_a2 = explode("-", $Date_2);
                    $d1           = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);
                    $d2           = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);
                    $Days         = round(($d1 - $d2) / 3600 / 24);
                    if ($Days == 0) {
                        $msg = '感谢您的参与，每个人每天只可以签到一次哦！！！';
                        message($msg, $reply['urly'], 'success');
                    }
                }
                $insert = array(
                    'rid' => $rid,
                    'fromuser' => $from_user,
                    'username' => $_W['fans']['nickname'],
                    'avatar' => $_W['fans']['tag']['avatar'],
                    'sign_time' => $_W['timestamp'],
                    'credit' => $reply['n']
                );
                pdo_insert('bm_qrsign_record', $insert);
                $user       = fans_search($from_user);
                $sql_member = "SELECT a.uid FROM " . tablename('mc_mapping_fans') . " a inner join " . tablename('mc_members') . " b on a.uid=b.uid WHERE a.openid='{$from_user}'";
                $uid        = pdo_fetchcolumn($sql_member);
                mc_credit_update($uid, 'credit1', intval($reply['n']), array(
                    0 => 'system',
                    1 => '扫码签到送积分'
                ));
                $user = fans_search($from_user);
                $msg  = '恭喜签到成功，您已获得奖励积分' . $reply['n'] . '分，您目前的总积分为' . $user['credit1'] . '分！';
                message($msg, $reply['urly'], 'success');
            }
        }
        include $this->template('show');
    }
}
?>