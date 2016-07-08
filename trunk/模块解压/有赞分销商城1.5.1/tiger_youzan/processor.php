<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/tiger_youzan/lib/KdtApiClient.php';
class Tiger_youzanModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {
        global $_W;
        load()->model('mc');
        $fans_id = $this->getfansid($this->message['from']);
        if (empty($fans_id)) {
            $this->postText($this->message['from'], '商家未配置有赞API参数!');
        }
        $rid    = $this->rule;
        $poster = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_poster') . " WHERE rid = :rid", array(
            ':rid' => $rid
        ));
        $fans   = mc_fetch($this->message['from']);
        if (empty($fans['nickname']) || empty($fans['avatar'])) {
            $openid       = $this->message['from'];
            $ACCESS_TOKEN = $this->getAccessToken();
            $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
            load()->func('communication');
            $json             = ihttp_get($url);
            $userInfo         = @json_decode($json['content'], true);
            $fans['nickname'] = $userInfo['nickname'];
            $fans['avatar']   = $userInfo['headimgurl'];
            $fans['province'] = $userInfo['province'];
            $fans['city']     = $userInfo['city'];
            mc_update($this->message['from'], array(
                'nickname' => $mc['nickname'],
                'avatar' => $mc['avatar']
            ));
        }
        if (!empty($poster)) {
            if ($poster['starttime'] > time()) {
                return $this->postText($this->message['from'], $poster['nostarttips']);
            } elseif ($poster['endtime'] < time()) {
                return $this->postText($this->message['from'], $poster['endtips']);
            }
        }
        if ($this->message['content'] == '肯定好友') {
            $cfg = $this->module['config'];
            if ($cfg['locationtype'] == 1 || $cfg['locationtype'] == 2 || $cfg['locationtype'] == 0) {
                $user = mc_fetch($this->message['from']);
                $city = $user['residecity'];
                $pos  = stripos($cfg['city'], $city);
                if ($pos === false) {
                    $dqurl = "<a href='" . $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('diqu', array(
                        'uid' => $fans['uid']
                    ))) . "'>点击这里</a>";
                    $dqmsg = "次活动只针对【" . $cfg['city'] . "】微信用户开放\n\n当前地区为【" . $city . "】\n\n如果你是该地区的用户，" . $dqurl . "验证\n\n如果不处于此地区，暂时不能参与活动，感谢您的支持！";
                    $this->postText($this->message['from'], $dqmsg);
                    exit;
                } else {
                }
            }
            $from_user = $this->message['content'];
            $credit1   = pdo_fetch('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $fans['uid'],
                ':credittype' => 'credit1',
                ':remark' => '关注送积分'
            ));
            if ($_W['account']['level'] == 4) {
                if ($poster['kdtype'] == 0) {
                    $this->postText($this->message['from'], "商家未开启该功能！");
                    exit;
                }
                if (empty($credit1) || empty($credit1)) {
                    $share = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and openid=:openid", array(
                        ':weid' => $_W['uniacid'],
                        ':openid' => $fans['uid']
                    ));
                    if ($poster['score'] > 0 || $poster['scorehb'] > 0) {
                        $info1 = str_replace('#昵称#', $fans['nickname'], $poster['ftips']);
                        $info1 = str_replace('#积分#', $poster['score'], $info1);
                        $info1 = str_replace('#元#', $poster['scorehb'], $info1);
                        if ($poster['score']) {
                            mc_credit_update($share['openid'], 'credit1', $poster['score'], array(
                                $share['openid'],
                                '关注送积分'
                            ));
                        }
                        if ($poster['scorehb']) {
                            mc_credit_update($share['openid'], 'credit2', $poster['scorehb'], array(
                                $share['openid'],
                                '关注送余额'
                            ));
                        }
                        $this->postText($this->message['from'], $info1);
                    }
                    if ($share['helpid'] == 0) {
                        exit;
                    }
                    $hmember = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and openid=:openid", array(
                        ':weid' => $_W['uniacid'],
                        ':openid' => $share['helpid']
                    ));
                    if ($poster['cscore'] > 0 || $poster['cscorehb'] > 0) {
                        if ($hmember['status'] == 1) {
                            exit;
                        }
                        $info2 = str_replace('#昵称#', $fans['nickname'], $poster['utips']);
                        $info2 = str_replace('#积分#', $poster['cscore'], $info2);
                        $info2 = str_replace('#元#', $poster['cscorehb'], $info2);
                        if ($poster['cscore']) {
                            mc_credit_update($hmember['openid'], 'credit1', $poster['cscore'], array(
                                $hmember['openid'],
                                '2级推广奖励'
                            ));
                        }
                        if ($poster['cscorehb']) {
                            mc_credit_update($hmember['openid'], 'credit2', $poster['cscorehb'], array(
                                $hmember['openid'],
                                '2级推广奖励'
                            ));
                        }
                        $this->postText($hmember['from_user'], $info2);
                    }
                    if ($poster['pscore'] > 0 || $poster['pscorehb'] > 0) {
                        $fmember = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and openid=:openid", array(
                            ':weid' => $_W['uniacid'],
                            ':openid' => $hmember['helpid']
                        ));
                        if ($fmember['status'] == 1) {
                            exit;
                        }
                        if ($fmember) {
                            $info3 = str_replace('#昵称#', $fans['nickname'], $poster['utips2']);
                            $info3 = str_replace('#积分#', $poster['cscore'], $info3);
                            $info3 = str_replace('#元#', $poster['cscorehb'], $info3);
                            if ($poster['pscore']) {
                                mc_credit_update($fmember['openid'], 'credit1', $poster['pscore'], array(
                                    $hmember['openid'],
                                    '3级推广奖励'
                                ));
                            }
                            if ($poster['pscore']) {
                                mc_credit_update($fmember['openid'], 'credit2', $poster['pscore'], array(
                                    $hmember['openid'],
                                    '3级推广奖励'
                                ));
                            }
                            $this->postText($fmember['from_user'], $info3);
                        }
                    }
                    exit;
                } else {
                    $kdmsg = '尊敬的粉丝：\n\n您已经领取过积分了，不能重复领取，快去生成海报赚取积分吧！';
                    $this->postText($this->message['from'], $kdmsg);
                    exit;
                }
            }
            if (empty($credit1) || empty($credit1)) {
                $urljq = "<a href='" . $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('kending', array(
                    'uid' => $fans['uid']
                ))) . "'>点击这里</a>";
                $kdmsg = "尊敬的粉丝：\n需要点击领取奖励才能获得奖励哦\n" . $urljq . "领取";
            } else {
                $kdmsg = '尊敬的粉丝：\n\n您已经领取过积分了，不能重复领取，快去生成海报赚取积分吧！';
            }
            $this->postText($this->message['from'], $kdmsg);
            exit;
        }
        if ($this->message['msgtype'] == 'event') {
            $scene_id = str_replace('qrscene_', '', $this->message['eventkey']);
            $fans     = mc_fetch($this->message['from']);
            if (empty($fans['nickname']) || empty($fans['avatar'])) {
                $openid       = $this->message['from'];
                $ACCESS_TOKEN = $this->getAccessToken();
                $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$openid}&lang=zh_CN";
                load()->func('communication');
                $json             = ihttp_get($url);
                $userInfo         = @json_decode($json['content'], true);
                $fans['nickname'] = $userInfo['nickname'];
                $fans['avatar']   = $userInfo['headimgurl'];
                $fans['province'] = $userInfo['province'];
                $fans['city']     = $userInfo['city'];
                mc_update($this->message['from'], array(
                    'nickname' => $mc['nickname'],
                    'avatar' => $mc['avatar']
                ));
            }
            if ($this->message['event'] == 'subscribe') {
                $hmember = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and sceneid=:sceneid", array(
                    ':weid' => $_W['uniacid'],
                    ':sceneid' => $scene_id
                ));
                $member  = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and from_user=:from_user", array(
                    ':weid' => $_W['uniacid'],
                    ':from_user' => $this->message['from']
                ));
                $poster  = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where id='{$hmember['pid']}'");
                if (empty($member)) {
                    pdo_insert($this->modulename . "_share", array(
                        'openid' => $fans['uid'],
                        'fans_id' => $fans_id,
                        'nickname' => $fans['nickname'],
                        'avatar' => $fans['avatar'],
                        'pid' => $poster['id'],
                        'createtime' => time(),
                        'helpid' => $hmember['openid'],
                        'weid' => $_W['uniacid'],
                        'fans_type' => $poster['fans_type'],
                        'score' => $poster['score'],
                        'cscore' => $poster['cscore'],
                        'pscore' => $poster['pscore'],
                        'from_user' => $this->message['from'],
                        'follow' => 1
                    ));
                    $share['id'] = pdo_insertid();
                    $share       = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where id='{$share['id']}'");
                    if ($poster['kdtype'] == 1) {
                        if (!empty($hmember['from_user'])) {
                            $mcsj  = mc_fetch($hmember['from_user']);
                            $msgsj = "您已通过「" . $mcsj['nickname'] . "」，成功关注，点击下方\n\n「菜单-领取奖励」\n\n为好友加分";
                        } else {
                            $msgsj = '您需要点击「领取奖励」才能得到积分哦!';
                        }
                        $this->postText($this->message['from'], $msgsj);
                        exit;
                    }
                    if ($poster['score'] > 0 || $poster['scorehb'] > 0) {
                        $info1 = str_replace('#昵称#', $fans['nickname'], $poster['ftips']);
                        $info1 = str_replace('#积分#', $poster['score'], $info1);
                        $info1 = str_replace('#元#', $poster['scorehb'], $info1);
                        if ($poster['score']) {
                            mc_credit_update($share['openid'], 'credit1', $poster['score'], array(
                                $share['openid'],
                                '关注送积分'
                            ));
                        }
                        if ($poster['scorehb']) {
                            mc_credit_update($share['openid'], 'credit2', $poster['scorehb'], array(
                                $share['openid'],
                                '关注送余额',
                                'tiger_youzan'
                            ));
                        }
                        $this->insertorder($fans, $fans_id, $poster['scorehb']);
                        $this->postText($this->message['from'], $info1);
                    }
                    if ($poster['cscore'] > 0 || $poster['cscorehb'] > 0) {
                        if ($hmember['status'] == 1) {
                            exit;
                        }
                        $info2 = str_replace('#昵称#', $fans['nickname'], $poster['utips']);
                        $info2 = str_replace('#积分#', $poster['cscore'], $info2);
                        $info2 = str_replace('#元#', $poster['cscorehb'], $info2);
                        if ($poster['cscore']) {
                            mc_credit_update($hmember['openid'], 'credit1', $poster['cscore'], array(
                                $hmember['openid'],
                                '2级推广奖励'
                            ));
                        }
                        $this->postText($hmember['from_user'], $info2);
                    }
                    if ($poster['pscore'] > 0 || $poster['pscorehb'] > 0) {
                        $fmember = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and openid=:openid", array(
                            ':weid' => $_W['uniacid'],
                            ':openid' => $hmember['helpid']
                        ));
                        if ($fmember['status'] == 1) {
                            exit;
                        }
                        if ($fmember) {
                            $info3 = str_replace('#昵称#', $fans['nickname'], $poster['utips2']);
                            $info3 = str_replace('#积分#', $poster['pscore'], $info3);
                            $info3 = str_replace('#元#', $poster['pscorehb'], $info3);
                            if ($poster['pscore']) {
                                mc_credit_update($fmember['openid'], 'credit1', $poster['pscore'], array(
                                    $hmember['openid'],
                                    '3级推广奖励'
                                ));
                            }
                            $this->postText($fmember['from_user'], $info3);
                        }
                    }
                } else {
                    $this->postText($this->message['from'], '亲，您已经是粉丝了，快去生成海报赚取奖励吧--有赞');
                }
                return $this->PostNews($poster, $fans['nickname']);
            }
            if ($this->message['event'] == 'SCAN') {
                $cfg = $this->module['config'];
                if ($cfg['hztype'] <> '') {
                    $jflx = $cfg['hztype'];
                } else {
                    $jflx = "积分";
                }
                $msg1 = $fans['nickname'] . "你已经是【" . $_W['account']['name'] . "】的粉丝了，不用再扫了哦。\n\n你当前有" . $fans['credit1'] . "" . $jflx . "";
                $this->postText($this->message['from'], $msg1);
                return $this->PostNews($poster, $fans['nickname']);
            }
        }
        if ($this->message['msgtype'] == 'text' || $this->message['event'] == 'CLICK') {
            $cfg = $this->module['config'];
            if ($cfg['locationtype'] == 1 || $cfg['locationtype'] == 2 || $cfg['locationtype'] == 0) {
                $user = mc_fetch($this->message['from']);
                $city = $user['residecity'];
                $pos  = stripos($cfg['city'], $city);
                if ($pos === false) {
                    $dqurl = "<a href='" . $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('diqu', array(
                        'uid' => $fans['uid']
                    ))) . "'>点击这里</a>";
                    $dqmsg = "本次活动只针对【" . $cfg['city'] . "】微信用户开放\n\n当前地区为【" . $city . "】\n\n如果你是该地区的用户，" . $dqurl . "验证\n\n如果不处于此地区，暂时不能参与活动，感谢您的支持！";
                    $this->postText($this->message['from'], $dqmsg);
                    exit;
                }
            }
            $img      = $this->createPoster($fans, $poster, $fans_id);
            $media_id = $this->uploadImage($img);
            if ($poster['winfo1']) {
                $info = str_replace('#时间#', date('Y-m-d H:i', time() + 30 * 24 * 3600), $poster['winfo1']);
                $this->postText($this->message['from'], $info);
            }
            if ($poster['winfo2']) {
                $hbshare = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where openid='{$fans['uid']}' ");
                $msg2    = $poster['winfo2'];
                if ($poster['rtype'] && $poster['type'] == 2);
                sleep(1);
                $this->postText($this->message['from'], $msg2);
            }
            $this->sendImage($this->message['from'], $media_id);
            exit;
        }
    }
    public function insertorder($fans, $fans_id, $money)
    {
        global $_W;
        $time = date('Y-m-d H:i:s', TIMESTAMP);
        $data = array(
            'weid' => $_W['uniacid'],
            'openid' => $this->message['from'],
            'fans_id' => $fans_id,
            'nickname' => $fans['nickname'],
            'picurl' => $fans['avatar'],
            'tid' => 'GZ' . date('Ymd', time()) . time(),
            'num' => 1,
            'num_iid' => 11111111,
            'price' => $money,
            'pic_path' => '',
            'pic_thumb_path' => '',
            'title' => '扫码关注奖励',
            'type' => '',
            'buyer_type' => '',
            'buyer_nick' => '',
            'trade_memo' => '',
            'receiver_city' => '',
            'receiver_district' => '',
            'receiver_name' => '',
            'receiver_address' => '',
            'receiver_mobile' => '',
            'feedback' => '',
            'status' => 'TRADE_BUYER_SIGNED',
            'total_fee' => $money,
            'payment' => $money,
            'created' => $time,
            'update_time' => $time,
            'pay_type' => 'WEIXIN',
            'cengji' => 0,
            'isjs' => 2,
            'bili' => 100,
            'yongjin' => $money
        );
        $ms   = pdo_insert($this->modulename . "_order", $data);
        return $ms;
    }
    public function sendImage($openid, $media_id)
    {
        $data = array(
            "touser" => $openid,
            "msgtype" => "image",
            "image" => array(
                "media_id" => $media_id
            )
        );
        $ret  = $this->postRes($this->getAccessToken(), json_encode($data));
        return $ret;
    }
    private function uploadImage($img)
    {
        $url  = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $this->getAccessToken() . "&type=image";
        $post = array(
            'media' => '@' . $img
        );
        load()->func('communication');
        $ret     = ihttp_request($url, $post);
        $content = @json_decode($ret['content'], true);
        return $content['media_id'];
    }
    private $sceneid = 0;
    private $Qrcode = "/addons/tiger_youzan/qrcode/yzposter#sid#.jpg";
    private function createPoster($fans, $poster, $fans_id)
    {
        global $_W;
        $bg    = $poster['bg'];
        $pid   = $poster['id'];
        $share = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where openid='{$fans['uid']}' limit 1");
        if (empty($share)) {
            pdo_insert($this->modulename . "_share", array(
                'openid' => $fans['uid'],
                'fans_id' => $fans_id,
                'nickname' => $fans['nickname'],
                'avatar' => $fans['avatar'],
                'pid' => $poster['id'],
                'createtime' => time(),
                'parentid' => 0,
                'weid' => $_W['uniacid'],
                'fans_type' => $poster['fans_type'],
                'score' => $poster['score'],
                'cscore' => $poster['cscore'],
                'pscore' => $poster['pscore'],
                'from_user' => $this->message['from'],
                'follow' => 1
            ));
            $share['id'] = pdo_insertid();
            $share       = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where id='{$share['id']}'");
        }
        $qrcode = str_replace('#sid#', $share['id'], IA_ROOT . $this->Qrcode);
        $data   = json_decode(str_replace('&quot;', "'", $poster['data']), true);
        include 'func.php';
        set_time_limit(0);
        @ini_set('memory_limit', '256M');
        $size   = getimagesize(tomedia($bg));
        $target = imagecreatetruecolor($size[0], $size[1]);
        $bg     = imagecreates(tomedia($bg));
        imagecopy($target, $bg, 0, 0, 0, 0, $size[0], $size[1]);
        imagedestroy($bg);
        foreach ($data as $value) {
            $value = trimPx($value);
            if ($value['type'] == 'qr') {
                if ($poster['type'] == 2) {
                    $url = $this->getQR($fans, $poster, $share['id'], $fans_id);
                } elseif ($poster['type'] == 3) {
                    $url = $_W['siteroot'] . str_replace('./', 'app/', $this->createMobileurl('sharetz', array(
                        'weid' => $_W['uniacid'],
                        'uid' => $fans['uid']
                    )));
                }
                if (!empty($url)) {
                    $img = IA_ROOT . "/temp_qrcode.png";
                    include 'phpqrcode.php';
                    $errorCorrectionLevel = "L";
                    $matrixPointSize      = "4";
                    QRcode::png($url, $img, $errorCorrectionLevel, $matrixPointSize, 2);
                    mergeImage($target, $img, array(
                        'left' => $value['left'],
                        'top' => $value['top'],
                        'width' => $value['width'],
                        'height' => $value['height']
                    ));
                    @unlink($img);
                }
            } elseif ($value['type'] == 'img') {
                $img = saveImage($fans['avatar']);
                mergeImage($target, $img, array(
                    'left' => $value['left'],
                    'top' => $value['top'],
                    'width' => $value['width'],
                    'height' => $value['height']
                ));
                @unlink($img);
            } elseif ($value['type'] == 'name')
                mergeText($this->modulename, $target, $fans['nickname'], array(
                    'size' => $value['size'],
                    'color' => $value['color'],
                    'left' => $value['left'],
                    'top' => $value['top']
                ), $poster);
        }
        imagejpeg($target, $qrcode);
        imagedestroy($target);
        return $qrcode;
    }
    function getfansid($openid)
    {
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.users.weixin.follower.get';
        $params    =array('weixin_openid' => $openid);
        $json      = $client->post($method, $params);
        $fans_id   = $json['response']['user']['user_id'];
        return $fans_id;
    }
    private function getQR($fans, $poster, $sid, $fans_id)
    {
        global $_W;
        $pid = $poster['id'];
        if (empty($this->sceneid)) {
            $share = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where id='{$sid}'");
            if (!empty($share['url'])) {
                $out = false;
                if ($poster['rtype']) {
                    $qrcode = pdo_fetch('select * from ' . tablename('qrcode') . " where uniacid='{$_W['uniacid']}' and qrcid='{$share['sceneid']}' " . " and name='{$poster['title']}' and ticket='{$share['ticketid']}' and url='{$share['url']}'");
                    if ($qrcode['createtime'] + $qrcode['expire'] < time()) {
                        pdo_delete('qrcode', array(
                            'id' => $qrcode['id']
                        ));
                        $out = true;
                    }
                }
                if (!$out) {
                    $this->sceneid = $share['sceneid'];
                    return $share['url'];
                }
            }
            $this->sceneid = pdo_fetchcolumn('select sceneid from ' . tablename($this->modulename . "_share") . " where weid='{$_W['uniacid']}' order by sceneid desc limit 1");
            if (empty($this->sceneid))
                $this->sceneid = 3000001;
            else
                $this->sceneid++;
            $barcode['action_info']['scene']['scene_id'] = $this->sceneid;
            load()->model('account');
            $acid                      = pdo_fetchcolumn('select acid from ' . tablename('account') . " where uniacid={$_W['uniacid']}");
            $uniacccount               = WeAccount::create($acid);
            $time                      = 0;
            $barcode['action_name']    = 'QR_SCENE';
            $barcode['expire_seconds'] = 30 * 24 * 3600;
            $res                       = $uniacccount->barCodeCreateDisposable($barcode);
            $time                      = $barcode['expire_seconds'];
            $rid                       = $this->rule;
            $sql                       = "SELECT * FROM " . tablename('rule_keyword') . " WHERE `rid`=:rid LIMIT 1";
            $row                       = pdo_fetch($sql, array(
                ':rid' => $rid
            ));
            pdo_insert('qrcode', array(
                'uniacid' => $_W['uniacid'],
                'acid' => $acid,
                'qrcid' => $this->sceneid,
                'name' => $poster['title'],
                'keyword' => $row['content'],
                'model' => 1,
                'ticket' => $res['ticket'],
                'expire' => $time,
                'createtime' => time(),
                'status' => 1,
                'url' => $res['url']
            ));
            pdo_update($this->modulename . "_share", array(
                'sceneid' => $this->sceneid,
                'ticketid' => $res['ticket'],
                'url' => $res['url'],
                'nickname' => $fans['nickname'],
                'fans_id' => $fans_id,
                'avatar' => $fans['avatar']
            ), array(
                'id' => $sid
            ));
            return $res['url'];
        }
    }
    public function postText($openid, $text)
    {
        $post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
        $ret  = $this->postRes($this->getAccessToken(), $post);
        return $ret;
    }
    private function postRes($access_token, $data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
        load()->func('communication');
        $ret     = ihttp_request($url, $data);
        $content = @json_decode($ret['content'], true);
        return $content['errcode'];
    }
    private function PostNews($poster, $name)
    {
        $stitle = unserialize($poster['stitle']);
        if (!empty($stitle)) {
            $thumbs = unserialize($poster['sthumb']);
            $sdesc  = unserialize($poster['sdesc']);
            $surl   = unserialize($poster['surl']);
            foreach ($stitle as $key => $value) {
                if (empty($value))
                    continue;
                $response[] = array(
                    'title' => str_replace('#昵称#', $name, $value),
                    'description' => $sdesc[$key],
                    'picurl' => tomedia($thumbs[$key]),
                    'url' => $this->buildSiteUrl($surl[$key])
                );
            }
            if ($response)
                return $this->respNews($response);
        }
        return '';
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