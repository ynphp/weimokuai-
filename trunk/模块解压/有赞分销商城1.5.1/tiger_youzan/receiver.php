<?php
defined('IN_IA') or exit('Access Denied');
class Tiger_youzanModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        global $_W, $_GPC;
        load()->func('logging');
        $cfg = $this->module["config"];
        load()->model('mc');
        $mc     = mc_fetch($this->message['from']);
        $openid = $this->message['from'];
        $debug  = false;
        if ($debug) {
            logging_run("接收到的参数:" . var_export($this->message, true), 'info', date('Ymd'));
        }
        if ($this->message['event'] == "subscribe") {
            if (empty($mc['nickname']) || empty($mc['avatar'])) {
                $openid       = $this->message['from'];
                $ACCESS_TOKEN = $this->getAccessToken();
                $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
                load()->func('communication');
                $json           = ihttp_get($url);
                $userInfo       = @json_decode($json['content'], true);
                $mc['nickname'] = $userInfo['nickname'];
                $mc['avatar']   = $userInfo['headimgurl'];
                $mc['province'] = $userInfo['province'];
                $mc['city']     = $userInfo['city'];
                $mc['country']  = $userInfo['country'];
            }
            $status1 = 1;
            pdo_update('tiger_youzan_share', array(
                'follow' => $status1
            ), array(
                'openid' => $mc['uid'],
                'weid' => $mc['uniacid']
            ));
            $fansusr = mc_fetch($this->message['from']);
            if ($fansusr['gender'] == 1) {
                $fansusr['gender'] = '帅哥';
            } elseif ($fansusr['gender'] == 2) {
                $fansusr['gender'] = '美女';
            }
            $fanssum      = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid and follow=1 ", array(
                ':uniacid' => $fansusr['uniacid']
            ));
            $account_name = $_W['account']['name'];
            $zfs          = $cfg["tiger_youzan_fansnum"] + $fanssum;
            $url          = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('kending', array(
                'uid' => $mc['uid']
            )));
            $msg          = htmlspecialchars_decode($cfg["tiger_youzan_usr"]);
            $msg          = str_replace('#昵称#', $mc['nickname'], $msg);
            $msg          = str_replace('#积分#', $mc['credit1'], $msg);
            $msg          = str_replace('#余额#', $mc['credit2'], $msg);
            $msg          = str_replace('#假粉丝数#', $zfs, $msg);
            $msg          = str_replace('#性别#', $fansusr['gender'], $msg);
            $msg          = str_replace('#国家#', $mc['country'], $msg);
            $msg          = str_replace('#省#', $mc['province'], $msg);
            $msg          = str_replace('#市#', $mc['city'], $msg);
            $msg          = str_replace('#领取积分#', $url, $msg);
            $msg          = str_replace('#公众号名称#', $account_name, $msg);
            $account      = WeAccount::create();
            $custom       = array(
                'msgtype' => 'text',
                'text' => array(
                    'content' => urlencode($msg)
                ),
                'touser' => $this->message['from']
            );
            $account->sendCustomNotice($custom);
        }
        if ($this->message['msgtype'] == 'event') {
            if ($this->message['event'] == 'unsubscribe') {
                $mc     = mc_fetch($this->message['from']);
                $shares = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where openid='{$mc['uid']}'");
                $reply  = pdo_fetch('select * from ' . tablename('tiger_youzan_poster') . ' where id=:id order by id asc limit 1', array(
                    ':id' => $shares['pid']
                ));
                if ($reply['rscore'] == 0) {
                    exit;
                }
                $cscore3   = $reply['cscore'];
                $pscore3   = $reply['pscore'];
                $cscorehb3 = $reply['cscorehb'];
                $pscorehb3 = $reply['pscorehb'];
                $m_fans    = pdo_fetch('select * from ' . tablename('tiger_youzan_share') . ' where weid=:weid AND from_user=:from_user order by id asc limit 1', array(
                    ':weid' => $mc['uniacid'],
                    ':from_user' => $this->message['from']
                ));
                if ($m_fans['helpid'] && empty($m_fans['hasdel'])) {
                    $s_fans = pdo_fetch('select * from ' . tablename('tiger_youzan_share') . ' where weid=:weid AND openid=:openid order by id asc limit 1', array(
                        ':weid' => $mc['uniacid'],
                        ':openid' => $m_fans['helpid']
                    ));
                    if ($s_fans['follow'] == 0) {
                        exit;
                    }
                    if ($cscore3) {
                        mc_credit_update($s_fans['openid'], 'credit1', -$cscore3, array(
                            $s_fans['openid'],
                            '2级取消关注'
                        ));
                    }
                    if ($cscorehb3) {
                        mc_credit_update($s_fans['openid'], 'credit2', -$cscorehb3, array(
                            $s_fans['openid'],
                            '2级取消关注'
                        ));
                    }
                    $rtips = str_replace('#昵称#', $mc['nickname'], $reply['rtips']);
                    $rtips = str_replace('#积分#', $cscore3, $rtips);
                    $rtips = str_replace('#元#', $cscorehb3, $rtips);
                    $this->sendText($s_fans['from_user'], $rtips);
                    if ($s_fans['helpid']) {
                        $p_fans = pdo_fetch('select * from ' . tablename('tiger_youzan_share') . ' where weid=:weid AND openid=:openid order by id asc limit 1', array(
                            ':weid' => $mc['uniacid'],
                            ':openid' => $s_fans['helpid']
                        ));
                        if ($p_fans['follow'] == 0) {
                            exit;
                        }
                        if ($pscore3) {
                            mc_credit_update($p_fans['openid'], 'credit1', -$pscore3, array(
                                $s_fans['openid'],
                                '3级取消关注'
                            ));
                        }
                        if ($pscorehb3) {
                            mc_credit_update($p_fans['openid'], 'credit2', -$pscorehb3, array(
                                $s_fans['openid'],
                                '3级取消关注'
                            ));
                        }
                        $rtips3 = str_replace('#昵称#', $mc['nickname'], $reply['rtips']);
                        $rtips3 = str_replace('#积分#', $pscore3, $rtips3);
                        $rtips3 = str_replace('#元#', $pscorehb3, $rtips3);
                        $this->sendText($p_fans['from_user'], $rtips3);
                    }
                }
                $status = 0;
                pdo_update('tiger_youzan_share', array(
                    'follow' => $status,
                    'hasdel' => 1
                ), array(
                    'openid' => $mc['uid'],
                    'weid' => $mc['uniacid']
                ));
            }
        }
    }
    function formot_content($content = '', $fansusr)
    {
        global $_W;
        if (empty($content)) {
            return $content;
        }
        load()->model('mc');
        $fansusr = mc_fetch($this->message['from']);
        $replace = array(
            'a' => Array(
                'replace' => '#昵称#',
                'name' => 'nickname'
            ),
            'b' => Array(
                'replace' => '#积分#',
                'name' => 'credit1'
            ),
            'c' => Array(
                'replace' => '#余额#',
                'name' => 'credit2'
            ),
            'd' => Array(
                'replace' => '#国家#',
                'name' => 'nationality'
            ),
            'e' => Array(
                'replace' => '#省#',
                'name' => 'resideprovince'
            ),
            'f' => Array(
                'replace' => '#市#',
                'name' => 'residecity'
            )
        );
        foreach ($replace as $re) {
            $content = str_replace($re['replace'], $fansusr[$re['name']], $content);
        }
        return $content;
    }
    private function Uid2Openid($uid)
    {
        return pdo_fetchcolumn('select openid from ' . tablename('mc_mapping_fans') . " where uid='{$uid}'");
    }
    public function sendText($openid, $text)
    {
        $post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
        $ret  = $this->sendRes($this->getAccessToken(), $post);
        return $ret;
    }
    private function sendRes($access_token, $data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        load()->func('communication');
        $ret     = ihttp_request($url, $data);
        $content = @json_decode($ret['content'], true);
        return $content['errcode'];
    }
    private function getAccessToken()
    {
        global $_W;
        load()->model('account');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account = WeAccount::create($acid);
        $token   = $account->getAccessToken();
        return $token;
    }
}