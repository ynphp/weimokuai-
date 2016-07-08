<?php
defined('IN_IA') or exit('Access Denied');
define('JX_ROOT', str_replace("\\", '/', dirname(__FILE__)));
class Xhbdz_WintersModuleSite extends WeModuleSite
{
    public function __construct()
    {
        global $_W;
        m('common')->api();
        if (empty($_W['user'])) {
            $userinfo = mc_oauth_userinfo();
            $openid   = $userinfo['openid'];
            if (empty($openid)) {
                die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请在微信客户端打开链接</h4></div></div></div>
                    </body>
                </html>");
            }
            $avatar   = $userinfo['avatar'];
            $nickname = $userinfo['nickname'];
            $profile  = pdo_fetch("SELECT * FROM " . tablename('xhbdz_member') . " WHERE uniacid ='{$_W['uniacid']}' and openid = '$openid'");
            $data     = array(
                'uniacid' => $_W['uniacid'],
                'openid' => $openid,
                'nickname' => $nickname,
                'avatar' => $avatar
            );
            if (empty($profile)) {
                $data['add_time'] = time();
                pdo_insert('xhbdz_member', $data);
            } else {
                pdo_update('xhbdz_member', $data, array(
                    'uid' => $profile['uid']
                ));
            }
        }
    }
    public function doMobileIndex()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePaygoods()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileMygoods()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileCenter()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileMyOrder()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobileMydeposit()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function doMobilePay()
    {
        $this->__mobile(__FUNCTION__);
    }
    public function payResult($params)
    {
        if ($params['result'] == 'success') {
            global $_W;
            $uniacid = $_W['uniacid'];
            $openid  = $_W['openid'];
            $order   = m('order')->get_orders(0, $params['tid']);
            if (empty($order)) {
                message('发生异常！！', $this->createMobileUrl('center'), 'success');
                exit();
            }
            if ($order['paystate'] == 0) {
                $goods = m('goods')->get_goods($order['gid']);
                $oid   = $order['id'];
                pdo_query('UPDATE  ' . tablename('xhbdz_goods') . " SET `stock` =`stock`-1 WHERE `uniacid` = " . $uniacid . ' AND `id` = ' . $order['gid']);
                $member = m('member')->get_member($openid);
                $uid    = $member['uid'];
                if (!empty($member['parent1'])) {
                    $parent1 = m('member')->get_member(0, $member['parent1']);
                }
                if (!empty($member['parent2'])) {
                    $parent2 = m('member')->get_member(0, $member['parent2']);
                }
                if (!empty($member['parent3'])) {
                    $parent3 = m('member')->get_member(0, $member['parent3']);
                }
                if (!empty($member['parent4'])) {
                    $parent4 = m('member')->get_member(0, $member['parent4']);
                }
                if (!empty($member['parent5'])) {
                    $parent5 = m('member')->get_member(0, $member['parent5']);
                }
                if (!empty($member['parent6'])) {
                    $parent6 = m('member')->get_member(0, $member['parent6']);
                }
                if (!empty($member['parent7'])) {
                    $parent7 = m('member')->get_member(0, $member['parent7']);
                }
                if (!empty($member['parent8'])) {
                    $parent8 = m('member')->get_member(0, $member['parent8']);
                }
                if (!empty($member['parent9'])) {
                    $parent9 = m('member')->get_member(0, $member['parent9']);
                }
                $uniaccount            = 'uniaccount:' . $_W['uniacid'];
                $data['appid']         = $_W['cache'][$uniaccount]['key'];
                $data['openid']        = $openid;
                $data['state']         = 0;
                $data['from_openid']   = $openid;
                $data['from_nickname'] = $member['nickname'];
                $data['from_avatar']   = $member['avatar'];
                $settings              = $this->module['config'];
                if (!empty($parent1)) {
                    $Hbcount            = m('deposit')->get_depositBS(1, $parent1['openid']);
                    $data['biaoshi']    = 1;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent1'];
                    $data['openid']     = $parent1['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent1'];
                    $hongbao            = $settings['hongbao1'] == 0 ? 99999 : $settings['hongbao1'];
                    if ($Hbcount <= $hongbao) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent1['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                    } else {
                        if ($member['level'] <= 1) {
                            m('message')->sendCustomNotice($data['openid'], '非常抱歉【' . $parent1['nickname'] . '】由于您的等级原因无法获得〖' . $member['nickname'] . '〗红包');
                        } else {
                            m('member')->update_memAm($data['openid'], array(
                                'shouyi' => $amount
                            ));
                            m('deposit')->add_depositlog($data);
                            m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent1['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                        }
                    }
                }
                if (!empty($parent2)) {
                    $Hbcount            = m('deposit')->get_depositBS(2, $parent2['openid']);
                    $data['biaoshi']    = 2;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent2'];
                    $data['openid']     = $parent2['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent2'];
                    $hongbao            = $settings['hongbao2'] == 0 ? 99999 : $settings['hongbao2'];
                    if ($Hbcount <= $hongbao) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent2['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                    } else {
                        if ($member['level'] < 2) {
                            m('message')->sendCustomNotice($data['openid'], '非常抱歉【' . $parent2['nickname'] . '】由于您的等级原因无法获得〖' . $member['nickname'] . '〗红包');
                        } else {
                            m('member')->update_memAm($data['openid'], array(
                                'shouyi' => $amount
                            ));
                            m('deposit')->add_depositlog($data);
                            m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent2['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                        }
                    }
                }
                if (!empty($parent3)) {
                    $Hbcount            = m('deposit')->get_depositBS(3, $parent3['openid']);
                    $data['biaoshi']    = 3;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent3'];
                    $data['openid']     = $parent3['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent3'];
                    $hongbao            = $settings['hongbao3'] == 0 ? 99999 : $settings['hongbao3'];
                    if ($Hbcount <= $hongbao) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent3['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                    } else {
                        if ($member['level'] <= 3) {
                            m('message')->sendCustomNotice($data['openid'], '非常抱歉【' . $parent3['nickname'] . '】由于您的等级原因无法获得〖' . $member['nickname'] . '〗红包');
                        } else {
                            m('member')->update_memAm($data['openid'], array(
                                'shouyi' => $amount
                            ));
                            m('deposit')->add_depositlog($data);
                            m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent3['nickname'] . '】您已获得〖' . $member['nickname'] . '〗红包');
                        }
                    }
                }
                if (!empty($parent4)) {
                    $data['biaoshi']    = 4;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent4'];
                    $data['openid']     = $parent4['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent4'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent4['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if (!empty($parent5)) {
                    $data['biaoshi']    = 5;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent5'];
                    $data['openid']     = $parent5['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent5'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent5['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if (!empty($parent6)) {
                    $data['biaoshi']    = 6;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent6'];
                    $data['openid']     = $parent6['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent6'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent6['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if (!empty($parent7)) {
                    $data['biaoshi']    = 7;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent7'];
                    $data['openid']     = $parent7['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent7'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent7['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if (!empty($parent8)) {
                    $data['biaoshi']    = 8;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent8'];
                    $data['openid']     = $parent8['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent8'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent8['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if (!empty($parent9)) {
                    $data['biaoshi']    = 9;
                    $data['depositsn']  = date('YmdHis', time()) . mt_rand(1000, 9999);
                    $data['amount']     = $settings['parent9'];
                    $data['openid']     = $parent9['openid'];
                    $data['createtime'] = time();
                    $amount             = '+' . $settings['parent9'];
                    if ($member['level'] == 3) {
                        m('member')->update_memAm($data['openid'], array(
                            'shouyi' => $amount
                        ));
                        m('deposit')->add_depositlog($data);
                        m('message')->sendCustomNotice($data['openid'], '恭喜您【' . $parent9['nickname'] . '】您已获得〖' . $member['nickname'] . '〗分红红包');
                    }
                }
                if ($settings['link1'] == $order['gid']) {
                    $level = 1;
                } elseif ($settings['link2'] == $order['gid']) {
                    $level = 2;
                } elseif ($settings['link3'] == $order['gid']) {
                    $level = 3;
                } else {
                    $level = 0;
                }
                m('member')->update_member($uid, array(
                    'level' => $level
                ));
                m('order')->up_order($oid, array(
                    'paystate' => '1',
                    'paytime' => time()
                ));
                message('购买成功！', $this->createMobileUrl('center'), 'success');
            } else {
                message('购买失败这个订单已经支付！', $this->createMobileUrl('center'), 'error');
            }
            message('支付处理异常！', $this->createMobileUrl('center'), 'error');
        }
    }
    public function doWebMember()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebOrder()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebGoods()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebDeposit()
    {
        $this->__web(__FUNCTION__);
    }
    public function __web($f_name)
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        load()->func('tpl');
        require_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function __mobile($f_name)
    {
        global $_W, $_GPC;
        $uniacid    = $_W['uniacid'];
        $share_data = $this->module['config'];
        $to_url     = $_W['siteroot'] . 'app/' . $this->createMobileUrl('attention', array());
        require_once 'mobile/' . strtolower(substr($f_name, 8)) . '.php';
    }
}
function m($name)
{
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = JX_ROOT . '/mod/' . $name . '.mod.php';
    if (!is_file($model)) {
        die(' Model ' . $name . ' Not Found!');
    }
    require_once $model;
    $class_name      = $name;
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}
function sendTransfer($info, $settings)
{
    define('hbdz_WINTERS_PATH', IA_ROOT . '/addons/hbdz_winters');
    define('hbdz_WINTERS_CERT', hbdz_WINTERS_PATH . '/cert');
    define('CERT', hbdz_WINTERS_CERT . '/apiclient_cert.pem');
    define('KEY', hbdz_WINTERS_CERT . '/apiclient_key.pem');
    define('CA', hbdz_WINTERS_CERT . '/rootca.pem');
    require_once hbdz_WINTERS_PATH . '/WxPay.pub.config.php';
    require_once hbdz_WINTERS_PATH . '/sendWallet_app.php';
    $mch_billno = $info['mch_billno'];
    $act_name   = $info['act_name'];
    $openid     = $info['openid'];
    $amount     = $info['amount'] * 100;
    $wishing    = $info['wishing'];
    $options    = array(
        'appid' => $settings['appid'],
        'machid' => $settings['machid'],
        'key' => $settings['key'],
        'appsecret' => $settings['appsecret']
    );
    new WxPayConf_pub($options);
    $sendWallet_app = new sendWallet();
    $SendTransfers  = $sendWallet_app;
    $SendTransfers->set_wxappid($settings['appid']);
    $SendTransfers->set_mchid($settings['machid']);
    $SendTransfers->set_key($settings['key']);
    $SendTransfers->set_nonce_str(walletWeixinUtil::getNonceStr());
    $SendTransfers->set_mch_billno($mch_billno);
    $SendTransfers->set_send_name($settings['act_name']);
    $SendTransfers->set_re_openid($openid);
    $SendTransfers->set_check_name('NO_CHECK');
    $SendTransfers->set_re_user_name('evcehiack');
    $SendTransfers->set_total_amount($amount);
    $SendTransfers->set_wishing($wishing);
    $SendTransfers->set_act_name($act_name);
    $SendTransfers->set_remark($wishing);
    $SendTransfers->set_total_num(1);
    $SendTransfers->set_logo_imgurl('https://wx.gtimg.com/mch/img/ico-logo.png');
    $SendTransfers->set_client_ip(walletWeixinUtil::getRealIp());
    $getNewData = $SendTransfers->getSendRedpackXml($SendTransfers);
    $data       = walletWeixinUtil::curl_post_ssl($getNewData['api_url'], $getNewData['xml_data']);
    $res        = @simplexml_load_string($data, NULL, LIBXML_NOCDATA);
    if (!empty($res)) {
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(array(
            'return_code' => 'FAIL',
            'return_msg' => 'transfers_接口出错',
            'return_ext' => array()
        ), JSON_UNESCAPED_UNICODE);
    }
}
