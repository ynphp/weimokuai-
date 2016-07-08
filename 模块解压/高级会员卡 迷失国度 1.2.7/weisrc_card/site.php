<?php
/**
 * 会员卡
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/weisrc_card/template/');
define('LOCK', 'Li9zb3VyY2UvbW9kdWxlcy93ZWlzcmNfY2FyZC90ZW1wbGF0ZS90aGVtZXMvMi92ZXJzaW9uLmNzcw==');
include "../addons/weisrc_card/model.php";
include "../addons/weisrc_card/templateMessage.php";

class weisrc_cardModuleSite extends WeModuleSite
{
    public $tablename = 'weisrc_card_reply';
    public $modulename = 'weisrc_card';
    public $cur_version = '2014071501';
    public $cur_tpl = 'style1';

    public $_debug = '1';//default:0
    public $_weixin = '0';//default:1

    public $_appid = '';
    public $_appsecret = '';
    public $_accountlevel = '';

    public $_weid = '';
    public $_fromuser = '';
    public $_nickname = '';
    public $_headimgurl = '';
    public $_uid = 0;

    public $_auth2_openid = '';
    public $_auth2_nickname = '';
    public $_auth2_headimgurl = '';

    public $_announce_sys = 0;//icon_11广播
    public $_announce_pig = 1;//icon_1猪  　
    public $_announce_share = 2;//icon_2分享
    public $_announce_edit = 3;//icon_3编辑
    public $_announce_dollar = 4;//icon_4美金
    public $_announce_coin = 5;//icon_5余额
    public $_announce_money = 6;//icon_6现金
    public $_announce_card = 7;//icon_7刷卡
    public $_announce_price = 8;//icon_8一袋钱
    public $_announce_weixin = 9;//icon_9微信
    public $_announce_alipay = 10;//icon_10支付宝

    //后台管理
//    public $actions_titles = array(
//        'style' => '会员卡设置',
//        'privilege' => '会员特权',
//        'score' => '积分策略',
//        //'program' => '业务关联',
//        'level' => '等级设置',
//        'password' => '消费密码',
//        'business' => '商家设置',
//        'store' => '门店管理',
//        'card' => '会员管理',
//        'allrechargelog' => '充值日志',
//        'userlog' => '会员统计',
//        'coupon' => '优惠券',
//        'exchange' => '积分兑换',
//        'gift' => '开卡即送',
//        'announce' => '通知管理',
//    );

    public $actions_titles = array(
        'style' => '会员卡设置',
        'privilege' => '会员特权',
        'score' => '积分策略',
        //'program' => '业务关联',
        'level' => '等级设置',
        'password' => '消费密码',
    );

    public $actions_titles2 = array(
        'business' => '商家设置',
        'store' => '门店管理',
    );

    public $actions_titles3 = array(
        'card' => '会员管理',
        'storelog' => '消费日志',
        'allrechargelog' => '充值日志',
        'userlog' => '会员统计',
    );

    public $actions_titles4 = array(
        'coupon' => '优惠券',
        'exchange' => '积分兑换',
        'gift' => '开卡即送',
    );

    public $actions_titles5 = array(
        'announce' => '通知管理',
    );

    public $actions_titles6 = array(
        'template' => '风格管理',
        'menu' => '业务菜单'
    );

    function __construct()
    {
        global $_W;
        $this->_weid = $_W['uniacid'];
        $this->_fromuser = $_W['fans']['from_user'];//debug
        if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
            $this->_fromuser = 'debug';
        }

        $this->_auth2_openid = 'auth2_openid_'.$_W['uniacid'];
        $this->_auth2_nickname = 'auth2_nickname_'.$_W['uniacid'];
        $this->_auth2_headimgurl = 'auth2_headimgurl_'.$_W['uniacid'];
        $account = $_W['account'];
        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $account['level']; //是否为高级号

        $lock_path = base64_decode(LOCK);
        if (!file_exists($lock_path)) {
            //message(base64_decode('5a+55LiN6LW377yM5oKo5L2/55So55qE5LiN5piv5q2j54mI6L2v5Lu277yM6LSt5Lmw5q2j54mI6K+36IGU57O7cXE6MTU1OTU3NTU='));
        } else {
//            $file_content = file_get_contents($lock_path);
//            $validation_code = $this->authorization();
//            $this->code_compare($file_content, $validation_code);
        }

        $template = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_template') . " WHERE weid = :weid", array(':weid' => $this->_weid));
        if (!empty($template)) {
            $this->cur_tpl = $template['template_name'];
        }

        if ($this->_accountlevel == 4) {
            $this->_appid = $account['key'];
            $this->_appsecret = $account['secret'];
        }

//        if (!empty($this->_appid) && !empty($this->_appsecret)) {
//            require_once IA_ROOT . '/framework/class/account.class.php';
//            $acc = WeAccount::create($this->_weid);
//            $_W['account']['jssdkconfig'] = $acc->getJssdkConfig();
//            $accountInfo = $acc->fetchAccountInfo();
//            $_W['account']['access_token'] = $accountInfo['access_token'];
//            $_W['account']['jsapi_ticket'] = $accountInfo['jsapi_ticket'];
//        }
    }

    public function doMobileGetMember()
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $this->checkUser();

        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card, true, true);

        $isfollow = false;
        load()->model('mc');
        $profile = mc_fansinfo($from_user);
        if ($profile['follow'] == 1) { //关注用户直接获取信息
            $isfollow = true;
        }
        $not_follow_url = $this->module['config']['icard']['not_follow_url'];

        $privileges = $this->getPrivileges('getmember');
        $style = $this->getCardStyle();

        if (!empty($style['bg'])) {
            $bg = tomedia($style['bg']);
        }
        if (!empty($style['logo'])) {
            $logo = tomedia($style['logo']);
        }

        //商家信息
        $business = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_business') . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $weid));
        //开卡即送
        $gift = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_gift') . " WHERE weid = :weid AND status=1 AND :nowtime<endtime AND :nowtime>starttime ORDER BY `id` DESC LIMIT 1", array(':weid' => $weid, ':nowtime' => TIMESTAMP));

        include $this->template($this->cur_tpl . '/getmember');
    }

    public function doMobileAddMember()
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $this->checkUser();

        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card, true, true);

        $method = 'AddMember';
        $url = $_W['siteroot'] . 'app/' .  $this->createMobileUrl($method, array(), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $fromuser = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2();
                if (!empty($userinfo)) {
                    $fromuser = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        $style = $this->getCardStyle();
        $userinfo = !empty($style) ? unserialize($style['userinfo']) : '';

        load()->model('mc');
        $fans = mc_fetch($from_user);

        include $this->template($this->cur_tpl . '/addmember');
    }

    //领取会员卡
    public function doMobileGetNewCard()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $fromurl = !empty($_GPC['fromurl']) ? $_GPC['fromurl'] : $this->createMobileUrl('index', array(), true);
        $headimgurl = trim($_GPC['headimgurl']);

        $this->checkUser();
        load()->model('mc');
        $fans = mc_fansinfo($from_user);
        if ($fans['follow'] != 1) { //关注用户直接获取信息
            $this->showMessageAjax('请先关注公众号,再领取会员卡!', 1);
        }

        $card = $this->getUserCard();
        if (!empty($card)) {
            $this->showMessageAjax('该用户已经领取会员卡!', 1);
        }

        //注册用户
        $data_user = array(
            'realname' => trim($_GPC['username']),
            'mobile' => trim($_GPC['mobile']),
            'email' => trim($_GPC['email']),
            'qq' => trim($_GPC['qq']),
            'company' => trim($_GPC['company']),
            'occupation' => trim($_GPC['occupation']),
            'position' => trim($_GPC['position']),
            'address' => trim($_GPC['address'])
        );

        if (empty($data_user['realname'])) {
            $this->showMessageAjax('请输入用户名!', 1);
        }
        if (empty($data_user['mobile'])) {
            $this->showMessageAjax('请输入手机号码!', 1);
        }
        if (!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|147[0-9]{8}$/", $data_user['mobile'])) {
            $this->showMessageAjax('手机号码格式不对!', 1);
        }

        $card = $this->getCardStyle();//会员卡设置
        if (empty($card)) {
            unset($data_user['email']);
            unset($data_user['qq']);
            unset($data_user['company']);
            unset($data_user['occupation']);
            unset($data_user['position']);
            unset($data_user['address']);
        } else {
            if (empty($data_user['email'])) {
                unset($data_user['email']);
            }
            if (empty($data_user['qq'])) {
                unset($data_user['qq']);
            }
            if (empty($data_user['company'])) {
                unset($data_user['company']);
            }
            if (empty($data_user['occupation'])) {
                unset($data_user['occupation']);
            }
            if (empty($data_user['position'])) {
                unset($data_user['position']);
            }
            if (empty($data_user['address'])) {
                unset($data_user['address']);
            }
        }

        $flag = mc_update($from_user, $data_user);
//        if ($flag == 0) {
//            $this->showMessageAjax('注册用户失败.', 1);
//        }

        //注册会员卡
        $data_card = array(
            'weid' => $weid,
            'uid' => $fans['uid'],
            'from_user' => $from_user,
            'headimgurl' => $headimgurl,
            'cardpre' => trim($card['cardpre']),
            'cardno' => $this->getCardNumber($weid),
            'coin' => 0,
            'balance_score' => 0,
            'total_score' => 0,
            'spend_score' => 0,
            'sign_score' => 0,
            'money' => 0,
            'carnumber' => trim($_GPC['carnumber']),
            'status' => 0,
            'updatetime' => TIMESTAMP,
            'dateline' => TIMESTAMP
        );
        $data_card['cardnumber'] = $data_card['cardpre'] . $data_card['cardno'];
        $flag = pdo_insert('weisrc_card_card', $data_card);
        if ($flag > 0) {
            //开卡即送
            $gift = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_gift') . " WHERE weid = :weid AND status=1 AND :nowtime<endtime AND :nowtime>starttime ORDER BY `id` DESC LIMIT 1", array(':weid' => $weid, ':nowtime' => TIMESTAMP));
            if (!empty($gift) && $gift['score'] > 0) {
                $this->setCardCredit($gift['score'], 'spend');
                $this->setFansCredit($gift['score'], '开卡送积分');
                //积分记录
                $this->addCardScoreLog('开卡送积分', 2, $gift['score'], 1);
                //通知
                $datetime = date('Y年m月d日H点i分', TIMESTAMP);
                $announce_content = "尊敬的{$data_user['realname']}会员，您于{$datetime}参与获得{$gift['score']}积分，请及时查收。";
                $this->AddAnnounce('开卡送积分', $announce_content, $from_user, $this->_announce_card, -1);
            }
            $this->showMessageAjax('成功领取会员卡.');
        }
    }

    public function addCardScoreLog($title, $type, $score, $operationtype = 0, $count = 0)
    {
        global $_GPC, $_W;
        $do = 'index';
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        //积分记录
        $data_score_log = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'type' => $type,//积分类型 签到:1，消费:2
            'title' => $title,
            'score' => $score,
            'operationtype' => $operationtype,//积分操作类型 增加:1  扣除:0
            'count' => $count,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_score_log', $data_score_log);
    }

    //会员卡首页
    public function doMobileIndex()
    {
        global $_GPC, $_W;
        $do = 'index';
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $method = 'index';
        $url = $_W['siteroot'] . 'app/' .  $this->createMobileUrl($method, array(), true);
        if (isset($_COOKIE[$this->_auth2_openid])) {
            $from_user = $_COOKIE[$this->_auth2_openid];
            $nickname = $_COOKIE[$this->_auth2_nickname];
            $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
        } else {
            if (isset($_GPC['code'])) {
                $userinfo = $this->oauth2();
                if (!empty($userinfo)) {
                    $from_user = $userinfo["openid"];
                    $nickname = $userinfo["nickname"];
                    $headimgurl = $userinfo["headimgurl"];
                } else {
                    message('授权失败!');
                }
            } else {
                if (!empty($this->_appsecret)) {
                    $this->getCode($url);
                }
            }
        }

        $this->checkUser(false);

        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);
        //未阅读数量
        $read_count = $this->getAnnounceReadCount($card['total_score']);
        //会员卡总积分
        $total_score = intval($card['total_score']);
        //会员卡等级
        $level = $this->getCardLevel($total_score);
        $levelid = intval($level['id']);

        //特权
        $privileges = $this->getPrivileges('level', $levelid);
        if (empty($level)) {
            $level['levelname'] = '未定义级别';
        }

        //会员卡样式
        $style = $this->getCardStyle();
        if (!empty($style['bg'])) {
            $bg = tomedia($style['bg']);
        }
        if (!empty($style['backbg'])) {
            $backbg = tomedia($style['backbg']);
        }
        if (!empty($style['logo'])) {
            $logo = tomedia($style['logo']);
        }

        load()->model('mc');
        $fans = mc_fetch($from_user);
        $coin = $fans['credit2'];//余额
        $balance_score = intval($fans['credit1']);//积分
        //商家信息
        $business = $this->getBusiness();
        //优惠券
        $coupons = pdo_fetchall("SELECT * FROM (SELECT a.id as id, a.title as title,b.id as sncodeid FROM ims_weisrc_card_coupon a INNER JOIN ims_weisrc_card_sncode b ON a.id = b.pid WHERE from_user=:from_user AND isshow=1 AND status=0 AND a.type in(1,2) AND a.weid=:weid ORDER BY sncodeid DESC) c", array(':weid' => $weid, ':from_user' => $from_user));
        //业务菜单
        $menus = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_menu') . " WHERE status=1 AND weid=:weid ORDER BY displayorder DESC,id DESC limit 5", array(':weid' => $weid));

        $config = $this->module['config']['icard'];
        //分享设置
        $share_image = strpos($config['share_image'], 'http') === false ? $_W['attachurl'] . $config['share_image'] : $config['share_image'];
        $share_title = empty($config['share_title']) ? '会员卡,省钱,打折,促销,优先知道,有奖励哦' : $config['share_title'];
        $share_desc = empty($config['share_desc']) ? $share_title : $config['share_desc'];
        $share_cancel = $config['$share_cancel'];
        $share_url = empty($config['share_url']) ? $_W['siteroot'] . 'app/' . $this->createMobileUrl('getmember', array(), true) : $config['share_url'];

        include $this->template($this->cur_tpl . '/index');
    }

    //通知
    public function doMobileAnnounce()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'announce';
        $type = intval($_GPC['type']); //通知类型
        $result['status'] = 0;

        $this->checkUser(false);
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        //未阅读数量
        $read_count = $this->getAnnounceReadCount($card['total_score']);
        //等级ID
        $level = $this->getCardLevel($card['total_score']);
        $levelid = intval($level['id']);

        if ($type == 0) { //会员广播（全局、等级）
            $strwhere = " WHERE weid = :weid AND type = 0 AND (levelid = 0 OR levelid = {$levelid})";
        } else if ($type == 1) { //系统通知
            $strwhere = " WHERE weid = :weid AND type <> 0 AND from_user='{$from_user}' ";
        }
        //会员卡通知
        $announces = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_announce') . " $strwhere ORDER BY id DESC limit 50", array(':weid' => $weid));
        //阅读记录
        $announcesRead = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_announce_read') . " WHERE weid=:weid AND from_user=:from_user", array(':weid' => $weid, ':from_user' => $from_user), 'announceid');

        include $this->template($this->cur_tpl . '/announce');
    }

    //消费结果页面
    public function doMobilePayResult()
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $billid = intval($_GPC['billid']);
        $bill = pdo_fetch("SELECT * FROM " . tablename("weisrc_card_bill_log") . " WHERE id=:id", array(':id' => $billid));
        if (empty($bill)) {
            message('记录不存在');
        }
        load()->model('mc');
        $fans = mc_fetch($from_user);
        $coin = $fans['credit2'];
        $balance_score = $fans['credit1'];

        include $this->template($this->cur_tpl . '/payresult');
    }

    public function getCardLevel($total_score)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid and :totalscore>=min and :totalscore<=max ORDER BY `min` limit 1", array(':weid' => $weid, ':totalscore' => $total_score));
    }

    public function getCardStyle()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_style') . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $weid));
    }

    public function getBusiness()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_business') . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $weid));
    }

    /**
     * @param string $type
     * @param int $levelid
     * @return array|bool
     */
    public function getPrivileges($type = 'level', $levelid = 0)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        if ($type == 'getmember') {
            $privileges = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_privilege') . " WHERE weid = :weid and endtime>:time and FIND_IN_SET(0,levelids) ORDER BY displayorder DESC,id DESC", array(':weid' => $weid, ':time' => TIMESTAMP));
        } else {
            $privileges = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_privilege') . " WHERE weid = :weid and endtime>:time and (FIND_IN_SET(:levelid,levelids) Or FIND_IN_SET(0,levelids)) ORDER BY displayorder DESC,id DESC", array(':weid' => $weid, ':time' => TIMESTAMP, ':levelid' => $levelid));
        }
        return $privileges;
    }

    //根据总积分读取相关会员卡的通知
    public function getAnnounceReadCount($totalscore)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $count = 0;

        if (isset($_COOKIE["announce_read_total_" . $weid])) {
            $count = intval($_COOKIE["announce_read_total_" . $weid]);
        } else {
            $level = pdo_fetch("SELECT id FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid and :totalscore>=min and :totalscore<=max ORDER BY `min` limit 1", array(':weid' => $weid, ':totalscore' => $totalscore));
            $levelid = intval($level['id']);
            $strWhere = " WHERE weid = :weid AND (((levelid = 0  OR levelid = $levelid) AND type = 0) OR (type=1 AND from_user='$from_user')) ";
            $userAnnounceCount = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_announce') . " $strWhere", array(':weid' => $weid));
            $readcount = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_announce_read') . " WHERE weid=:weid AND from_user=:from_user", array(':weid' => $weid, ':from_user' => $from_user));
            $count = $userAnnounceCount - $readcount;
            setcookie("announce_read_total_" . $weid, $count, time() + 60);
        }
        if ($count < 0) {
            return 0;
        }

        return $count;
    }

    //标识通知
    public function doMobileRead()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            return false;
        }

        //判断通知是否存在
        $announce = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_announce') . " WHERE id=:id AND weid=:weid  ORDER BY id DESC limit 1", array(':weid' => $weid, ':id' => $id));
        if (empty($announce)) {
            return false;
        }

        //判断是否已经阅读
        $flag = pdo_fetch("SELECT id FROM " . tablename('weisrc_card_announce_read') . " WHERE weid = :weid and from_user=:from_user AND announceid=:announceid limit 1", array(':weid' => $weid, ':from_user' => $from_user, ':announceid' => $id));

        //未阅读添加数据
        if (empty($flag)) {
            $data = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'announceid' => $id,
                'dateline' => TIMESTAMP
            );
            pdo_insert('weisrc_card_announce_read', $data);
            setcookie("announce_read_total_" . $weid, '', -1);
        }
    }

    //签到首页
    public function doMobileSign()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'sign';
        $m = intval($_GPC['month']); //月份
        $year = intval($_GPC['year']);
        //user
        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        //判断会员卡
        if (!empty($card)) {
            $spend_score = $card['spend_score'];
            $sign_score = $card['sign_score'];
            $total_score = $card['total_score'];
            load()->model('mc');
            $user = mc_fetch($from_user);
            $balance_score = intval($user['credit1']);
        } else {
            $spend_score = 0; //消费积分
            $balance_score = 0; //剩余积分
            $coin = 0; //余额
            $sign_score = 0;
        }

        //签到信息
        if ($m < 1 || $m > 12) {
            $m = date("m", TIMESTAMP);
        } else if ($m < 10 && $m != 0) {
            $m = '0' . $m;
        }

        $year = empty($year) ? date("Y", TIMESTAMP) : $year; //当前年份2013
        $day = date("d", TIMESTAMP); //当前日子
        $now_time = strtotime($year . '-' . $m . '-' . $day);
        $month = date("m", $now_time); //当前月份
        $daysofmonth = date("t", $now_time); //当月天数
        $arrWeekday = array(0 => '星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
        $issign = $this->getTodaySignstatus();
        $data_month = $this->getSignInMonth($year, $month); //用户该月签到数据
        $signCount = count($data_month);

        $str_tmp = '[';
        foreach ($data_month as $key => $value) {
            $d = date('d', $value['dateline']);
            $str_tmp = $str_tmp . $d . ",";
        }

        $str_tmp = substr($str_tmp, 0, strlen($str_tmp) - 1);
        $str_tmp = $str_tmp . "]";
        $sign_list = $str_tmp;
        if ($sign_list == ']') {
            $sign_list = '[]';
        }

        $signlist = array();
        $totalscore = 0; //总积分

        for ($i = 1; $i <= $daysofmonth; $i++) {
            $daytime = strtotime($year . '-' . $month . '-' . $i);
            $score = 0;
            $status = 0;
            foreach ($data_month as $key => $value) {
                $d = date('d', $value['dateline']);
                if ($i == $d) { //日期相同的时候
                    $status = 1;
                    $score = $value['score'];
                    if ($value > 0) {
                        $totalscore += $score;
                    }
                    break;
                }
            }
            //查询该月的签到记录
            $signlist[] = array('day' => date('m月d日', $daytime), 'week' => $arrWeekday[date('w', $daytime)], 'status' => $status, 'score' => $score);
        }

        $sign_last = $this->getLastSign($weid, $from_user); //上一次签到的数据

        include $this->template($this->cur_tpl . '/sign');
    }

    //签到记录
    public function doMobileSignList()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'sign';
        $y = intval($_GPC['y']);
        $m = intval($_GPC['m']);

        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);
        //签到列表
        $signlist = $this->getSignList();

        include $this->template($this->cur_tpl . '/signlist');
    }

    //签到
    public function doMobileSignin()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，从重新发送关键字登录!', 1);
        }

        $count = 0; //连续签到次数
        $score = 0; //获得积分

        //判断是否开启签到功能//debug
        $status = $this->getTodaySignstatus();
        if ($status == 0) { //未签到
            $obj_score = $this->getCardScore();
            //runningdays
            if (empty($obj_score) || $obj_score['day_score'] == 0) {
                $this->showMessageAjax('商家未开启签到功能!', 1);
            }

//            $runningdays = intval($obj_score['runningdays']) > 0 ? intval($obj_score['runningdays']) - 1 : 0;
            $runningdays = intval($obj_score['runningdays']);

            if (empty($obj_score)) {
                $obj_score['day_score'] = 1;
                $obj_score['dayx_score'] = 1;
            }

            $title = '签到得积分';
            $day_score = $obj_score['day_score']; //每天签到积分
            $dayx_score = $obj_score['dayx_score']; //连续签到积分
            $sign_last = $this->getLastSign($weid, $from_user); //上一次签到的数据
            if (!empty($sign_last)) {
                $count = intval($sign_last['count']);
                $lasttime = intval($sign_last['dateline']);
                $lasttime = strtotime(date('Y-m-d', $lasttime)) + 86400; //时间变为23.59
                if ((TIMESTAMP - $lasttime) > (3600 * 24)) { //时间差大于24小时清零
                    $count = 0;
                }
            }
//            if ($count == $runningdays) {
//                $count = 0; //上一次为连续6天的时候清零
//            }
            $count += 1;
            //积分
            $score = $day_score;
            if ($dayx_score != 0) { //连续签到积分
                if ($count == $runningdays) {
                    $score = $dayx_score;
                    $title = '连续签到得积分';
                    $count = 0;
                }
            }
            $data_sign = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'title' => $title,
                'score' => $score,
                'count' => $count,
                'dateline' => TIMESTAMP
            );
            $flag = pdo_insert('weisrc_card_sign', $data_sign);
            if ($flag > 0) { //增加会员卡积分
                $card = $this->getUserCard();
                if (!empty($card)) {
                    //更新会员卡总积分、签到积分
                    $this->setCardCredit($score);
                    //更新会员积分
                    $this->setFansCredit($score, '签到送积分');
                    $data_score_log = array(
                        'weid' => $weid,
                        'from_user' => $from_user,
                        'type' => 1,//积分类型 签到:1，消费:2
                        'title' => $title,
                        'score' => $score,
                        'operationtype' => 1,//积分操作类型 增加:1  扣除:0
                        'count' => 0,
                        'dateline' => TIMESTAMP
                    );
                    pdo_insert('weisrc_card_score_log', $data_score_log);
                }
            }
        } else {
            $this->showMessageAjax('今天你已经签到了!', 1);
        }
        $this->showMessageAjax('签到成功，获得' . $score . '积分');
    }

    public function doMobileShare()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        //会员卡
        $card = $this->getUserCard();

        include $this->template($this->cur_tpl . '/share');
    }

    //积分
    public function setFansCredit($credit1, $remark)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($from_user);
        $fans = mc_fetch($uid, array("credit1"));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            mc_credit_update($uid, 'credit1', $credit1, $log);
        }
    }

    public function setFansMoney($credit2, $remark)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($from_user);
        $fans = mc_fetch($uid, array("credit2"));
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            mc_credit_update($uid, 'credit2', $credit2, $log);
        }
    }

    public function setCardCredit($score, $type = 'sign', $add = true)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $card = $this->getUserCard();
        if ($add) {
            $fields = array(
                'balance_score' => $card['balance_score'] + $score,
                'sign_score' => $card['sign_score'] + $score,
                'total_score' => $card['total_score'] + $score,
                'spend_score' => $card['spend_score'] + $score,
            );
        } else {
            $fields = array(
                'balance_score' => $card['balance_score'] - $score,
                'sign_score' => $card['sign_score'] - $score,
                'total_score' => $card['total_score'] - $score,
                'spend_score' => $card['spend_score'] - $score,
            );
        }

        if ($type == 'sign') {
            //签到类型，卸载消费积分
            unset($fields['spend_score']);
        } else {
            unset($fields['sign_score']);
        }
        return pdo_update('weisrc_card_card', $fields, array('from_user' => $from_user));
    }

    public function setCardMoney($money, $add = true)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $card = $this->getUserCard();
        if ($add) {
            $fields = array (
                'money' => $card['money'] + $money,
            );
        } else {
            $fields = array (
                'money' => $card['money'] - $money,
            );
        }
        return pdo_update('weisrc_card_card', $fields, array('from_user' => $from_user));
    }

    //个人中心
    public function doMobileUserCenter()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = "usercenter";

        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        $level = $this->getCardLevel($card['total_score']);
        if (empty($level)) {
            $level = '无定义等级';
        } else {
            $level = $level['levelname'];
        }

        //用户优惠券数量
        $coupon_count = intval($this->getSncodeCount(1));
        //用户代金券数量
        $coupon_count2 = intval($this->getSncodeCount(2));
        $coupon_count3 = intval($this->getSncodeCount(3));

        load()->model('mc');
        $fans = mc_fetch($from_user);

        $balance_score = $fans['credit1']; //剩余积分
        $coin = $fans['credit2']; //余额
        $coin = empty($coin) ? '0.00' : $coin;

        include $this->template($this->cur_tpl . '/usercenter');
    }

    public function doMobileScorelist()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = "usercenter";
        //检查from_user
        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        $condition = " WHERE from_user=:from_user AND weid=:weid ";

        $isall = true;
        if (isset($_GPC['type'])) {
            $type = intval($_GPC['type']);
            $condition .= " AND operationtype={$type}";
            $isall = false;
        }

        $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_score_log') . " {$condition} ORDER BY id DESC limit 50", array(':weid' => $weid, ':from_user' => $from_user));

        include $this->template($this->cur_tpl . '/scorelist');
    }

    public function doMobileScoreTeach()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = "sign";
        //检查from_user
        $this->checkUser();
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        $score = $this->getCardScore();
        include $this->template($this->cur_tpl . '/scoreteach');
    }

    //编辑会员资料
    public function doMobileEditUserinfo()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'userinfo';
        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        load()->model('mc');
        //$fans
        $user = mc_fetch($from_user);
        $curDat = $user['birthyear'] . '/' . $user['birthmonth'] . '/' . $user['birthday'];
        $style = $this->getCardStyle();
        $userinfo = !empty($style) ? unserialize($style['userinfo']) : '';

        include $this->template($this->cur_tpl . '/edituserinfo');
    }

    //更新用户资料
    public function doMobileUpdateUserinfo()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $this->checkUser();

        $birth_year = trim($_GPC['birth_year']);
        $birth_month = trim($_GPC['birth_month']);
        $birth_date = trim($_GPC['birth_date']);

        $data = array(
            'realname' => trim($_GPC['username']),
            'mobile' => trim($_GPC['telephone']),
            'gender' => intval($_GPC['gender']),
            'age' => intval($_GPC['age']),
            'birthyear' => $birth_year,
            'birthmonth' => $birth_month,
            'birthday' => $birth_date,
            'email' => trim($_GPC['email']),
            'qq' => trim($_GPC['qq']),
            'company' => trim($_GPC['company']),
            'occupation' => trim($_GPC['occupation']),
            'position' => trim($_GPC['position']),
            'address' => trim($_GPC['address'])
        );

        if (empty($data['realname'])) {
            unset($data['realname']);
        }
        if (empty($data['email'])) {
            unset($data['email']);
        }
        if (empty($data['qq'])) {
            unset($data['qq']);
        }
        if (empty($data['company'])) {
            unset($data['company']);
        }
        if (empty($data['occupation'])) {
            unset($data['occupation']);
        }
        if (empty($data['position'])) {
            unset($data['position']);
        }
        if (empty($data['address'])) {
            unset($data['address']);
        }


        if (!empty($_GPC['carnumber'])) {
            pdo_query("UPDATE " . tablename('weisrc_card_card') . " SET carnumber = :carnumber WHERE from_user=:from_user", array(':carnumber' => trim($_GPC['carnumber']), ':from_user' => $from_user));
        }
        load()->model('mc');
        $flag = mc_update($from_user, $data);
        $this->showMessageAjax('操作成功.');
    }

    public function doMobileExchangeList()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'index';
        $type = 4;
        $condition = "";

        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        //物品使用次数
        $arr_user_coupon = $this->getUserCouponUseTimes($type);
        $arr_coupon = $this->getCouponUseTimes($type);

        $stores = pdo_fetchall("select * from " . tablename($this->modulename.'_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $total_score = intval($card['total_score']);
        $level = $this->getCardLevel($total_score);
        $strwhere = $this->getCouponStrWhere($level['id']);
        $condition .= " AND type={$type} ";

        $exchanges = pdo_fetchall("SELECT a.id as id, a.title as title,a.needscore as needscore,a.endtime as endtime,b.thumb as thumb,b.content as content FROM " . tablename('weisrc_card_coupon') . " a INNER JOIN " . tablename('weisrc_card_coupon') . " b ON a.ticket_id = b.id WHERE a.weid=:weid AND :time<a.endtime AND a.type=4 AND b.type<>4 AND (a.levelid=0 OR a.levelid=:levelid) order by a.displayorder DESC,a.id DESC", array(':weid' => $weid, ':time' => TIMESTAMP, ':levelid' => $level['id']));

        include $this->template($this->cur_tpl . '/exchangelist');
    }

    public function doMobileCoupon()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'index';
        $type = empty($_GPC['type'])? 1 : $_GPC['type'];
        $condition = "";

        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        //物品使用次数
        $arr_user_coupon = $this->getUserCouponUseTimes($type);
        $arr_coupon = $this->getCouponUseTimes($type);

        $stores = pdo_fetchall("select * from " . tablename($this->modulename.'_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $total_score = intval($card['total_score']);
        $level = $this->getCardLevel($total_score);
        $strwhere = $this->getCouponStrWhere($level['id']);
        $condition .= " AND type={$type} ";
        $coupons = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_coupon') . " WHERE weid = :weid AND :time<endtime  AND attr_type=1 And (" . $strwhere . ") $condition ORDER BY displayorder DESC,id DESC limit 50", array(':weid' => $weid, ':time' => TIMESTAMP));

        include $this->template($this->cur_tpl . '/coupon');
    }

    //取得优惠券使用次数
    public function getUserCouponUseTimes($type)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        //取得兑换礼品兑换次数
        $data = array();
        $sncodes = pdo_fetchall("SELECT COUNT(1) as count,pid FROM " . tablename('weisrc_card_sncode') . " GROUP BY from_user,type,pid,weid having weid = :weid AND from_user=:from_user AND type=:type ", array(':weid' => $weid, ':from_user' => $from_user, ':type' => $type));
        foreach ($sncodes as $key => $value) {
            $data[$value['pid']] = $value['count'];
        }
        return $data;
    }

    //取得优惠券使用次数
    public function getCouponUseTimes($type)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        //取得兑换礼品兑换次数
        $data = array();
        $sncodes = pdo_fetchall("SELECT COUNT(1) as count,pid FROM " . tablename('weisrc_card_sncode') . " GROUP BY from_user,type,pid,weid having weid = :weid AND type=:type ", array(':weid' => $weid, ':type' => $type));
        foreach ($sncodes as $key => $value) {
            $data[$value['pid']] = $value['count'];
        }
        return $data;
    }

    //使用优惠券
    public function doMobileUseCoupon()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        //优惠券ID
        $id = intval($_GPC['id']);
        //优惠券
        $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id LIMIT 1", array(':id' => $id));

        if (empty($coupon)) {
            $this->showMessageAjax('优惠券不存在！', 1);
        } else {
            //优惠券类型 1优惠券 2代金券 3礼品券
            $type = intval($coupon['type']) == 0 ? 1 : $coupon['type'];
            //优惠券属性 1普通券 2营销券
            $attr_type = $coupon['attr_type'];

            //未使用、未隐藏最新的数据
            $sncode = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_sncode")." WHERE pid=:pid AND status=0 AND isshow=1 ORDER BY id DESC LIMIT 1", array(':pid' => $id));
        }

        if ($coupon['storeid'] == 0) {
            //$stores = pdo_fetchall("SELECT * FROM ".tablename("weisrc_card_store")." WHERE weid=:weid", array(':weid' => $weid));
        } else {
            $store = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_store")." WHERE id=:id LIMIT 1", array(':id' => $coupon['storeid']));
        }


        include $this->template($this->cur_tpl . '/usecoupon');
    }

    //使用兑换码
    public function doMobileUseSncode()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理
        $type = 2;//消费优惠券

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
        } else {
            $this->_fromuser = $from_user;
            $this->_weid;
        }

        //用户会员卡
        $card = $this->getUserCard();
        if (empty($card)) {
            $this->showMessageAjax('会员卡不存在！', 1);
        }
        if ($card['status'] == 1) {
            $this->showMessageAjax('您的会员卡已被冻结,请联系商户！', 1);
        }

        //检查兑换码
        $sncodeid = intval($_GPC['sncodeid']);
        $sncode = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_sncode")." WHERE id=:id AND isshow=1 ORDER BY id DESC LIMIT 1", array(':id' => $sncodeid));
        if (empty($sncode)) {
            $this->showMessageAjax('兑换码不存在！', 1);
        } else {
            if ($sncode['status'] == 1) {
                $this->showMessageAjax('兑换码已被兑换！', 1);
            }
        }

        //检查优惠券
        $couponid = $sncode['pid'];
        $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id ORDER BY id DESC LIMIT 1", array(':id' => $couponid));

        if (empty($coupon)) {
            $this->showMessageAjax('优惠券不存在！', 1);
        } else {
            if (TIMESTAMP > $coupon['endtime']) {
                $this->showMessageAjax('优惠券已过期！', 1);
            }
        }

        //支付方式 1：现金支付 2：余额支付
        $paytype = intval($_GPC['paytype']) == 0 ? 1 : $_GPC['paytype'];
        $passwordid = 0;

        if ($paytype == 2) {
            if (empty($card['password'])) {
                $this->showMessageAjax('您还没有设置会员卡密码不能使用余额支付,请到会员中心设置密码.', 1);
            }
        }

        //现金消费
        if ($paytype == 1 || $paytype == 2 || $coupon['type'] == 3) {
            //检查商家帐号
            $username = trim($_GPC['username']);
            $password = trim($_GPC['password']);
            if (empty($username)) {
                $this->showMessageAjax('请输入登录帐号！', 1);
            }
            if (empty($password)) {
                $this->showMessageAjax('请输入消费密码！', 1);
            }
            $business_password = $this->checkUserPassword($username, $password);
            if (empty($business_password)) {
                $this->showMessageAjax('帐号或密码输入错误！', 1);
            } else {
                if ($business_password['status'] == 0) {
                    $this->showMessageAjax('您的帐号已被关闭！', 1);
                } else {
                    if ($business_password['consume'] == 0) {
                        $this->showMessageAjax('您的帐号没有权限进行该操作！', 1);
                    }
                }
            }
            //门店ID
            $storeid = intval($business_password['storeid']);
            $passwordid = intval($business_password['id']);
        }

        if ($coupon['storeid'] != 0) {
            //判断门店是否对应
            if ($storeid != $coupon['storeid']) {
                $this->showMessageAjax('对不起，您的帐号没有操作该优惠券的权限.', 1);
            }
        }

        //礼品券
        if ($coupon['type'] == 3) {
            //更新sn码使用状态
            pdo_update('weisrc_card_sncode', array('status' => 1, 'usetime' => TIMESTAMP), array('id' => $sncodeid));
            $this->showMessageAjax('兑换成功');
        }

        $money = intval($_GPC['money']);
        if ($money == 0) {
            $this->showMessageAjax('请输入消费金额！', 1);
        }

        $coupon_type = intval($coupon['type']);//代金券
        if ($coupon_type == 2) {//代金券
//            $give_value = $coupon['give_value'];
//            if ($money < $give_value) {
//                $this->showMessageAjax("此代金券需要满{$give_value}才能消费！", 1);
//            }
        }

        load()->model('mc');
        $fans = mc_fetch($from_user);
        $coin = $fans['credit2'];
        if ($paytype == 2) { //余额消费
            $pay_pass = trim($_GPC['pay_pass']);
            if (empty($pay_pass)) {
                $this->showMessageAjax('请输入会员卡密码.', 1);
            }
            load()->model('user');
            if (user_hash($pay_pass, '') != $card['password']) {
                $this->showMessageAjax('会员卡密码输入错误.', 1);
            }
            $balance_score = $fans['credit1'];
            if ($money > $coin) {
                $this->showMessageAjax('会员卡余额不足,请使用其它支付方式.'.$coin, 1);
            }
        }

        $obj_score = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_score') . " WHERE weid = :weid ", array(':weid' => $weid));
        $spend_score = intval($obj_score['payx_score']);
        //本次消费积分
        $totalspendscore = 0;
        if ($spend_score != 0) {
            $totalspendscore = $money * $spend_score;
        }
        $announce_title = '';
        $announce_content = '';
        $datetime = date('Y年m月d日H点i分', TIMESTAMP);
        if ($paytype == 2) { //余额消费
            $this->setCardMoney($money);
            $this->setFansMoney(-$money, '余额消费');
            $coin = $fans['credit2'];
            $announce_title = '余额消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用余额消费支出{$money}元，当前余额{$coin}元。";
        } else { //现金消费
            $this->setCardMoney($money);
            $announce_title = '现金消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用现金消费支出{$money}元。";
        }

        //消费记录
        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'title' => $coupon['title'] . '/' . $announce_title,
            'type' => $type,
            'payment' => $paytype,//支付方式 1：现金支付 2：余额支付
            'passwordid' => $passwordid,
            'operationtype' => 0,//支出
            'storeid' => $storeid,
            'objectid' => $couponid,//优惠券
            'money' => $money,
            'score' => $totalspendscore,
            'dateline' => TIMESTAMP,
        );

        pdo_insert("weisrc_card_bill_log", $data);
        $billid = pdo_insertid();

        //增加剩余积分、总积分、签到积分
        if ($totalspendscore != 0) {
            $this->setCardCredit($totalspendscore, 'spend');
            $this->setFansCredit($totalspendscore, '优惠券消费增加积分');

            //积分记录
            $data_score_log = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'type' => 2,//积分类型 签到:1，消费:2
                'title' => $coupon['title'] . '/' . $announce_title,
                'score' => $totalspendscore,
                'operationtype' => 1,//积分操作类型 增加:1  扣除:0
                'count' => 0,
                'dateline' => TIMESTAMP
            );
            pdo_insert('weisrc_card_score_log', $data_score_log);
            //debug
        }
        //更新sn码使用状态
        pdo_update('weisrc_card_sncode', array('status' => 1, 'storeid' => $storeid, 'passwordid' => $passwordid, 'usetime' => TIMESTAMP), array('id' => $sncodeid));

        //通知
        $announce_title = $coupon['title'] . '/' . $announce_title;
        $this->AddAnnounce($announce_title, $announce_content, $from_user, $this->_announce_money, -1);

        $this->showMessageAjax('消费成功', 0, 0, $billid);
    }

    public function doMobileTest()
    {
        global $_W, $_GPC;
        $from_user = $_GPC['from_user'];
        if (empty($from_user)) {
            $from_user = 'oOmaRt8rt4RejfnwpemN9XkOzDF8';
        }
        $url = $this->createMobileUrl('index', array(), true);

        $date = date('Y-m-d H:i:s', TIMESTAMP);
        $template = array(
            'touser' => $from_user,
            'template_id' => 'zk01_imEW1JnqXQufyd_AvImYV06ZePAGdTSwbZiMWs',
            'url' => $url,
            'topcolor' => "#FF0000",
            'data' => array(
                'first' => array(
                    'value' => urlencode("迷失卍国度通知你，你已成功报名了活动"),
                    'color' => '#f00'
                ),
                'keyword1' => array(
                    'value' => urlencode("报名创业码头，让梦想起飞"),
                    'color' => 'red'
                ),
                'keyword2' => array(
                    'value' => urlencode($date),
                    'color' => 'red'
                ),
                'keyword3' => array(
                    'value' => urlencode("汕头大学"),
                    'color' => 'red'
                ),
                'remark' => array(
                    'value' => urlencode("感谢你的参与，点击查看活动详情"),
                    'color' => '#f00'
                ),
            )
        );
        $templateMessage = new class_templateMessage('wx730c7415120788e5', '5c7d830f9246ad05e52c38392395abeb');
        $templateMessage->send_template_message($template);
        echo 'success ' . $date;
    }

    //更新用户优惠券状态
    public function doMobileDelUserCoupon()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
            //iweisrc_card
        }

        //优惠券ID
        $id = intval($_GPC['cou_id']);
        //优惠券
        $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id LIMIT 1", array(':id' => $id));
        if (empty($coupon)) {
            $this->showMessageAjax('优惠券不存在!', 1);
        } else {
            //更新状态
            if (TIMESTAMP > $coupon['endtime']) { //过期的全隐藏
                pdo_update("weisrc_card_sncode", array('isshow' => 0), array('from_user' => $from_user, 'weid' => $weid, 'pid' => $id));
            } else { //隐藏已使用的券
                pdo_update("weisrc_card_sncode", array('isshow' => 0), array('from_user' => $from_user, 'weid' => $weid, 'status' => 1, 'pid' => $id));
            }
        }
        $this->showMessageAjax('操作成功!', 1);
    }

    //用户取优惠券
    public function doMobileGetCoupon()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
        } else {
            $this->_fromuser = $from_user;
            $this->_weid;
        }

        //用户会员卡
        $card = $this->getUserCard();
        if (empty($card)) {
            $this->showMessageAjax('会员卡不存在！', 1);
        }
        if ($card['status'] == 1) {
            $this->showMessageAjax('您的会员卡已被冻结,请联系商户！', 1);
        }

        //优惠券ID
        $id = intval($_GPC['cou_id']);
        $type = intval($_GPC['type']);

        //优惠券 1.发放总数2.没人领取数量
        $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id LIMIT 1", array(':id' => $id));
        if (empty($coupon)) {
            $this->showMessageAjax('优惠券不存在!', 1);
        } else {
            //判断优惠券属性  普通券1 营销券2
            if ($coupon['attr_type'] == 2) {
                $this->showMessageAjax('该优惠券属于营销券,不能领取...', 1);
            }
        }

        $coupon_count = $coupon['count'];//每个用户能领取数量 0为不限制
        $coupon_totalcount = $coupon['totalcount'];//发放总数量 0为不限制

        //用户已领取数量
        $user_coupon_count = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid and from_user=:from_user AND pid=:pid ORDER BY id DESC", array(':weid' => $weid, ':from_user' => $from_user, ':pid' => $id));
        //已领取总数量
        $user_coupon_total_count = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid AND pid=:pid ORDER BY id DESC", array(':weid' => $weid, ':pid' => $id));

        //发放总数量有限制
        if ($coupon_totalcount != 0) {
            if ($user_coupon_total_count >= $coupon_totalcount) {
                $this->showMessageAjax('对不起，优惠券已经发放完了!', 1);
            }
        }

        if ($coupon_count != 0) {
            if ($user_coupon_count >= $coupon_count) {
                $this->showMessageAjax("此活动您已经兑换过，活动期间最多只能兑换{$coupon_count}次!", 1);
            }
        }

        //生成兑换码
        $sncode = 'A00' . random(11, 1);
        $sncode = $this->getNewSncode($weid, $sncode);
        //添加兑换码
        $data = array(
            'pid' => $id,
            'type' => $type,
            'weid' => $weid,
            'from_user' => $from_user,
            'sncode' => $sncode
        );
        $snid = $this->addSncode($data);
        $this->showMessageAjax('领取成功！');
    }

    //使用积分兑换
    public function doMobileUseExchange()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
        }  else {
            $this->_fromuser = $from_user;
            $this->_weid;
        }

        //用户会员卡
        $card = $this->getUserCard();
        if (empty($card)) {
            $this->showMessageAjax('会员卡不存在！', 1);
        }
        if ($card['status'] == 1) {
            $this->showMessageAjax('您的会员卡已被冻结,请联系商户！', 1);
        }

        //积分兑换ID
        $id = intval($_GPC['id']);
        //积分兑换券
        $exchange = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id LIMIT 1", array(':id' => $id));
        if (empty($exchange)) {
            $this->showMessageAjax('券不存在！', 1);
        } else {
            if ($exchange['endtime'] < TIMESTAMP) {
                $this->showMessageAjax('活动已过期！', 1);
            }

            //1=优惠券2=代金券3=礼品券
            $ticket_id = intval($exchange['ticket_id']) == 0 ? 1 : $exchange['ticket_id'];
            $ticket_ty = intval($exchange['ticket_ty']) == 0 ? 1 : $exchange['ticket_ty'];

            //判断积分
            $needscore = $exchange['needscore'];

            load()->model('mc');
            $fans = mc_fetch($from_user, array("credit1"));


            if ($fans['credit1'] < $needscore) {
                $this->showMessageAjax("对不起,您的积分不足,您的总积分:{$fans['credit1']},此兑换所需积分:{$needscore}", 1);
            }

            $exchange_count = $exchange['count'];//每个用户能领取数量 0为不限制
            $exchange_totalcount = $exchange['totalcount'];//发放总数量 0为不限制

            //用户已领取数量
            $user_exchange_count = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid and from_user=:from_user AND pid=:pid ORDER BY id DESC", array(':weid' => $weid, ':from_user' => $from_user, ':pid' => $id));
            //已领取总数量
            $user_exchange_total_count = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid AND pid=:pid ORDER BY id DESC", array(':weid' => $weid, ':pid' => $id));

            //发放总数量有限制
            if ($exchange_totalcount != 0) {
                if ($user_exchange_total_count >= $exchange_totalcount) {
                    $this->showMessageAjax('对不起，优惠券已经发放完了!', 1);
                }
            }

            if ($exchange_count != 0) {
                if ($user_exchange_count >= $exchange_count) {
                    $this->showMessageAjax("此活动您已经兑换过，活动期间最多只能兑换{$exchange_count}次!", 1);
                }
            }

            //生成兑换码
            $sncode = 'A00' . random(11, 1);
            $sncode = $this->getNewSncode($weid, $sncode);
            //添加积分兑换兑换码 用于统计使用次数
            $data = array(
                'pid' => $id,
                'type' => 4,
                'weid' => $weid,
                'from_user' => $from_user,
                'sncode' => $sncode
            );
            $this->addSncode($data);
            $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id LIMIT 1", array(':id' => $exchange['ticket_id']));

            //添加优惠券兑换码
            $data2 = array(
                'pid' => $ticket_id,
                'type' => $ticket_ty,
                'weid' => $weid,
                'from_user' => $from_user,
                'sncode' => $sncode
            );
            $this->addSncode($data2);

            //扣除积分
            $this->setFansCredit(-$needscore, '礼品券兑换消耗积分');
            //积分日志
            $data_score_log = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'type' => 1,//积分类型 签到:1，消费:2
                'title' => '积分兑换扣除积分',
                'score' => $needscore,
                'operationtype' => 0,//积分操作类型 增加:1  扣除:0
                'count' => 0,
                'dateline' => TIMESTAMP
            );
            pdo_insert('weisrc_card_score_log', $data_score_log);
            $this->showMessageAjax('兑换成功，请到会员中心查看！');
        }
    }

    //消费记录
    public function doMobileBill()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'usercenter';
        //检查from_user
        $this->checkUser(false);
        //会员卡
        $card = $this->getUserCard();

        //会员卡申请日期
        $date = date('Y-m', $card['dateline']);
        $y = intval($_GPC['y']); //年份
        $y = empty($y) ? date("Y", TIMESTAMP) : $y;
        $m = intval($_GPC['month']); //月份
        $page_date = '';
        $m = empty($m) ? intval(date("m", TIMESTAMP)) : $m;
        for ($i = 12; $i > 0; $i--) {
            if ($m == $i) {
                $page_date .= "<option value=\"{$i}\" selected>{$y}年{$i}月</option>";
            } else {
                $page_date .= "<option value=\"{$i}\">{$y}年{$i}月</option>";
            }
        }
        if ($m < 10 && $m != 0) {
            $m = '0' . $m;
        }

        //查询某月份的数据
        $sql_child = "select * from " . tablename('weisrc_card_bill_log') . " where weid =:weid AND from_user=:from_user AND date_format(FROM_UNIXTIME(dateline), '%Y-%m')='{$y}-{$m}'";
        //分组查询每日消费总金额
        $sql = "SELECT objectid,sum(money) as totalmoney,sum(score) as totalscore,date_format(FROM_UNIXTIME(dateline), '%Y-%m-%d') as date FROM (" . $sql_child . ") a GROUP BY date, from_user,weid";
        $condition_date = " AND date_format(FROM_UNIXTIME(dateline), '%Y-%m')='{$y}-{$m}' ";
        $datalist = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = :weid AND from_user=:from_user {$condition_date} ORDER BY id DESC", array(':weid' => $weid, ':from_user' => $from_user));
        $totalMoney = pdo_fetchcolumn("SELECT sum(money) FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = :weid AND from_user=:from_user AND operationtype=1 $condition_date", array(':weid' => $weid, ':from_user' => $from_user));
        $totalMoney2 = pdo_fetchcolumn("SELECT sum(money) FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = :weid AND from_user=:from_user AND operationtype=0 $condition_date", array(':weid' => $weid, ':from_user' => $from_user));
        $totalMoney = empty($totalMoney) ? 0 : $totalMoney;
        $totalMoney2 = empty($totalMoney2) ? 0 : $totalMoney2;
        include $this->template($this->cur_tpl . '/bill');
    }

    public function doMobileSetPayPass()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'usercenter';

        $this->checkUser(false);
        $card = $this->getUserCard();
        $this->checkUserCard($card);

        if (empty($card)) {
            message('no power');
        } else {
            if (!empty($card['password'])) {
                $flag = true; //有旧密码
            } else {
                $flag = false;
            }
        }

        include $this->template($this->cur_tpl . '/setpaypass');
    }

    //修改密码
    public function doMobileSetPayPassIng()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $this->checkUser();
        $card = $this->getUserCard();

        $password = trim($_GPC['password']);
        $oldpassword = trim($_GPC['oldpassword']);

        if (!empty($oldpassword)) {
            if ($password == $oldpassword) {
                $this->showMessageAjax('新密码和原密码不能相同!!', 1);
            }
        }

        load()->model('user');
        //检测原密码是否正确
        if (!empty($oldpassword)) {
            $oldpassword = user_hash($oldpassword, '');
            if ($oldpassword != $card['password']) {
                $this->showMessageAjax('原密码输入错误!', 1);
            }
        }

        $data = array(
            'password' => user_hash($password, ''),
        );
        //$this->showMessageAjax('debug!!', 1);

        pdo_update($this->modulename . '_card', $data, array('id' => $card['id'], 'weid' => $weid));
        $this->showMessageAjax('操作成功!');
    }

    //我的优惠券
    public function doMobileMyCoupon()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'usercenter';
        $type = intval($_GPC['type']) == 0 ? 1 : intval($_GPC['type']);
        $isuse = intval($_GPC['isuse']);

        $this->checkUser(false);
        $card = $this->getUserCard();

        $stores = pdo_fetchall("select * from " . tablename($this->modulename.'_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $coupons = pdo_fetchall("SELECT c.id, COUNT(1) AS nums,c.sncodeid,c.title as title,c.give_value as give_value,c.dmoney as dmoney,c.thumb as thumb,c.content as content,c.endtime as endtime FROM (SELECT a.*,b.id as sncodeid FROM ". tablename("weisrc_card_coupon") ." a INNER JOIN ". tablename("weisrc_card_sncode") ." b ON a.id = b.pid WHERE from_user=:from_user AND a.type=:type AND isshow=1 AND b.status=:status ORDER BY sncodeid DESC) c GROUP BY id", array(':type' => $type, ':from_user' => $from_user, ':status' => $isuse));

        include $this->template($this->cur_tpl . '/mycoupon');
    }

    //我的优惠券
    public function doMobileMyExchange()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $do = 'usercenter';
        $type = 3;
        $isuse = intval($_GPC['isuse']);

        $this->checkUser(false);
        $card = $this->getUserCard();

        $stores = pdo_fetchall("select * from " . tablename($this->modulename.'_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $exchanges = pdo_fetchall("SELECT c.id, COUNT(1) AS nums,c.sncodeid,c.title as title,c.give_value as give_value,c.dmoney as dmoney,c.thumb as thumb,c.content as content,c.endtime as endtime FROM (SELECT a.*,b.id as sncodeid FROM ". tablename("weisrc_card_coupon") ." a INNER JOIN ". tablename("weisrc_card_sncode") ." b ON a.id = b.pid WHERE from_user=:from_user AND a.type=:type AND isshow=1 AND b.status=:status ORDER BY sncodeid DESC) c GROUP BY id", array(':type' => $type, ':from_user' => $from_user, ':status' => $isuse));

        include $this->template($this->cur_tpl . '/myexchange');
    }

    public function getSncode($id, $type, $from_user)
    {
        global $_W, $_GPC;
        $sncode = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_sncode') . " WHERE pid = :pid AND type = :type AND status !=1 AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':pid' => $id, ':type' => $type, ':from_user' => $from_user));
        return $sncode;
    }

    public function getNewSncode($weid, $sncode)
    {
        global $_W, $_GPC;
        $sn = pdo_fetch("SELECT sncode FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid and sncode = :sn ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':sn' => $sncode));
        if (!empty($sn)) {
            $sncode = 'A00' . random(11, 1);
            $this->getNewSncode($weid, $sncode);
        }
        return $sncode;
    }

    //适用门店
    public function doMobileStore()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $result['status'] = 0;
        $do = 'store';

        //门店列表
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid and is_show=1 ORDER BY displayorder DESC,id DESC limit 50", array(':weid' => $weid));
        //会员卡
        $card = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_card') . " WHERE weid = :weid and from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));

        include $this->template($this->cur_tpl . '/store');
    }

    public function getSncodeCount($type = 1)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetchcolumn("SELECT sum(1) AS nums FROM (SELECT b.id as sncodeid FROM ims_weisrc_card_coupon a INNER JOIN ims_weisrc_card_sncode b ON a.id = b.pid WHERE from_user=:from_user AND isshow=1 AND status=0 AND a.type=:type AND a.weid=:weid ORDER BY sncodeid DESC) c", array(':weid' => $weid, ':from_user' => $from_user, ':type' => $type));
    }

    public function doMobileRechargeing()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
        } else {
            $this->_fromuser = $from_user;
            $this->_weid;
        }

        //判断用户会员卡
        $card = $this->getUserCard();

        if (empty($card)) {
            $this->showMessageAjax('会员卡不存在！', 1);
        }
        if ($card['status'] == 1) {
            $this->showMessageAjax('您的会员卡已被冻结,请联系商户！', 1);
        }

        //判断操作账户
        $username = $_GPC['username']; //登录帐号
        $password = $_GPC['password']; //登录密码
        $money = intval($_GPC['money']);//付款金额
        $paytype = intval($_GPC['paytype']); //支付类型1:现金
        if (empty($username)) {
            $this->showMessageAjax('请输入商家登录帐号！', 1);
        }
        if (empty($password)) {
            $this->showMessageAjax('请输入商家登录密码！', 1);
        }
        if ($money <= 0) {
            $this->showMessageAjax('请输入充值金额！', 1);
        } else if ($money > 10000) {
            $this->showMessageAjax('每次充值不能大于10000！', 1);
        }

        $business_password = $this->checkUserPassword($username, $password);
        $storeid = intval($business_password['storeid']); //门店id

        if (empty($business_password)) {
            $this->showMessageAjax('帐号或密码输入错误！', 1);
        } else {
            if ($business_password['status'] == 0) {
                $this->showMessageAjax('您的帐号已被关闭.');
            } else {
                if ($business_password['recharge'] == 0) {
                    $this->showMessageAjax('您的帐号没有充值权限.');
                }
            }
        }

        $announce_title = '';
        $announce_content = '';
        $announce_type = 0;
        $datetime = date('Y年m月d日H点i分', TIMESTAMP);
        //现金充值
        if ($paytype == 1) {
            //更新会员余额
            load()->model('mc');
            load()->func('compat.biz');
            $uid = mc_openid2uid($card['from_user']);
            $fans = mc_fetch($uid, array("credit1"));
            $remark = $money > 0 ? '会员卡余额充值' : '会员卡余额扣除';
            if (!empty($fans)) {
                $uid = intval($fans['uid']);
                $log = array();
                $log[0] = $uid;
                $log[1] = $remark;
                mc_credit_update($uid, 'credit2', $money, $log);
            }

            //消费记录
            $data = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'title' => '会员卡充值',
                'type' => 4,//1现金2消费3余额4充值
                'payment' => 1,//支付方式 1：现金支付 2：余额支付
                'passwordid' => $business_password['id'],
                'operationtype' => 1,//0支出 1充值
                'storeid' => $storeid,
                'objectid' => 0,//优惠券
                'money' => $money,
                'score' => 0,
                'dateline' => TIMESTAMP,
            );
            pdo_insert("weisrc_card_bill_log", $data);

            //通知
            $announce_title = '会员卡充值';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用现金充值充入{$money}元。";
            $announce_type = $this->_announce_price;

            $this->AddAnnounce($announce_title, $announce_content, $from_user, $announce_type, -1);
            $this->addCardRechargeLog(1, $money, $card['id'], $storeid, $business_password['id']);
            $this->showMessageAjax('充值成功!');
        }
    }

    public function doMobileBilling()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $_GPC['from_user'];//特别处理

        if (empty($from_user)) {
            $this->showMessageAjax('会话已过期，请重新发关键字进入系统...', 1);
        } else {
            $this->_fromuser = $from_user;
            $this->_weid;
        }

        //用户会员卡
        $card = $this->getUserCard();
        if (empty($card)) {
            $this->showMessageAjax('会员卡不存在！', 1);
        }
        if ($card['status'] == 1) {
            $this->showMessageAjax('您的会员卡已被冻结,请联系商户！', 1);
        }

        $username = $_GPC['username']; //登录帐号
        $password = $_GPC['password']; //登录密码
        $pay_pass = $_GPC['pay_pass']; //用户会员卡密码
        $money = intval($_GPC['money']);//付款金额
        $paytype = intval($_GPC['paytype']); //支付类型1:现金
        $storeid = 0;
        $couponid = 0;

        if ($money <= 0) {
            $this->showMessageAjax('请输入消费金额！', 1);
        } else if ($money > 10000) {
            $this->showMessageAjax('每次消费不能大于10000！', 1);
        }

        //现金消费1,余额消费2
        if ($paytype == 1 || $paytype == 2) {
            if (empty($username)) {
                $this->showMessageAjax('请输入商家登录帐号！', 1);
            }
            if (empty($password)) {
                $this->showMessageAjax('请输入商家登录密码！', 1);
            }
            $business_password = $this->checkUserPassword($username, $password);
            $storeid = intval($business_password['storeid']); //门店id
            if (empty($business_password)) {
                $this->showMessageAjax('帐号或密码输入错误！', 1);
            } else {
                if ($business_password['status'] == 0) {
                    $this->showMessageAjax('您的帐号已被关闭.', 1);
                } else {
                    if ($business_password['consume'] == 0) {
                        $this->showMessageAjax('您的帐号没有消费权限.', 1);
                    }
                }
            }
        }

        if ($paytype == 2) {
            //$card['password']
            if (empty($card['password'])) {
                $this->showMessageAjax('您还没有设置会员卡密码不能使用余额支付,请到会员中心设置密码.', 1);
            }
        }

        //sn码ID
        $sncodeid = intval($_GPC['discount']);
        if ($sncodeid != 0) {
            //检查兑换码
            $sncode = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_sncode")." WHERE id=:id AND isshow=1 ORDER BY id DESC LIMIT 1", array(':id' => $sncodeid));
            if (empty($sncode)) {
                $this->showMessageAjax('兑换码不存在！', 1);
            } else {
                if ($sncode['status'] == 1) {
                    $this->showMessageAjax('兑换码已被兑换！', 1);
                }
            }

            //检查优惠券
            $couponid = $sncode['pid'];
            $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id ORDER BY id DESC LIMIT 1", array(':id' => $couponid));

            if (empty($coupon)) {
                $this->showMessageAjax('优惠券不存在！', 1);
            } else {
                if (TIMESTAMP > $coupon['endtime']) {
                    $this->showMessageAjax('优惠券已过期！', 1);
                }
            }

            $coupon_type = intval($coupon['type']);//代金券
            if ($coupon_type == 2) {//代金券
//                $give_value = $coupon['give_value'];
//                if ($money < $give_value) {
//                    $this->showMessageAjax("此代金券需要满{$give_value}才能消费！", 1);
//                }
            }
        }

        //优惠券有限制门店
        if ($coupon['storeid'] != 0) {
            //判断门店是否对应
            if ($storeid != $coupon['storeid']) {
                $this->showMessageAjax('对不起，您的帐号没有操作该优惠券的权限.', 1);
            }
        }

        //积分策略
        $obj_score = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_score') . " WHERE weid = :weid ", array(':weid' => $weid));
        $spend_score = intval($obj_score['payx_score']);
        //本次消费积分
        $totalspendscore = 0;
        if ($spend_score != 0) {
            $totalspendscore = $money * $spend_score;
        }

        //日志
        $announce_title = '';
        $announce_content = '';
        $announce_type = 0;
        $datetime = date('Y年m月d日H点i分', TIMESTAMP);
        if ($paytype == 1) {//现金消费
            $announce_type = $this-> _announce_coin;//现金
            $announce_title = '现金消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用现金消费支出{$money}元。";
            $announce_title = !empty($coupon['title'])? $coupon['title'] . '/' . $announce_title : $announce_title;
            //消费记录
            $data = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'title' => $announce_title,
                'type' => 1,//1现金2消费3余额4充值
                'payment' => 1,//支付方式 1：现金支付 2：余额支付
                'passwordid' => $business_password['id'],
                'operationtype' => 0,//0支出 1充值
                'storeid' => $storeid,
                'objectid' => $sncodeid,//操作码id
                'money' => $money,
                'score' => $totalspendscore,
                'dateline' => TIMESTAMP,
            );
            pdo_insert("weisrc_card_bill_log", $data);

        } else if ($paytype == 2) { //余额支付
            $announce_type = $this->_announce_card;//余额
            $pay_pass = trim($_GPC['pay_pass']);
            if (empty($pay_pass)) {
                $this->showMessageAjax('请输入会员卡密码.', 1);
            }
            if (user_hash($pay_pass, '') != $card['password']) {
                $this->showMessageAjax('会员卡密码输入错误.', 1);
            }
            load()->model('mc');
            $fans = mc_fetch($from_user);
            $coin = $fans['credit2'];
            $balance_score = $fans['credit1'];
            if ($money > $coin) {
                $this->showMessageAjax('会员卡余额不足,请使用其它支付方式.'.$coin, 1);
            }

            $announce_title = '余额消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用余额消费支出{$money}元。";
            //减去余额
            $remark = $money > 0 ? '会员卡余额充值' : '会员卡余额扣除';
            $this->setFansMoney(-$money, $remark);

            $announce_title = !empty($coupon['title'])? $coupon['title'] . '/' . $announce_title : $announce_title;
            $data = array(
                'weid' => $weid,
                'from_user' => $from_user,
                'title' => $announce_title,
                'type' => 3,//1现金2消费3余额4充值
                'payment' => 2,//支付方式 1：现金支付 2：余额支付
                'passwordid' => $business_password['id'],
                'operationtype' => 0,//0支出 1充值
                'storeid' => $storeid,
                'objectid' => $sncodeid,//sncodeid
                'money' => $money,
                'score' => $totalspendscore,
                'dateline' => TIMESTAMP,
            );
            pdo_insert("weisrc_card_bill_log", $data);
        }

        //增加剩余积分、总积分、签到积分
        if ($totalspendscore != 0) {
            $this->setCardCredit($totalspendscore, 'spend');
            $this->setFansCredit($totalspendscore, '消费增加积分');
            //积分记录
            $this->addCardScoreLog($announce_title, 2, $totalspendscore, 1, 0);
        }

        //更新sn码使用状态
        pdo_update('weisrc_card_sncode', array('status' => 1, 'usetime' => TIMESTAMP), array('id' => $sncodeid));
        $this->AddAnnounce($announce_title, $announce_content, $from_user, $announce_type, -1);

//        账户：{{account.DATA}}
//        时间：{{time.DATA}}
//        类型：{{type.DATA}}
//        {{creditChange.DATA}}积分：{{number.DATA}}
//        {{creditName.DATA}}余额：{{amount.DATA}}
//        {{remark.DATA}}

        //$fans = fans_search($from_user, array("credit1"));

        $template_id = 'hnPRYo5JzHj7v-PPhOzJ-9eyvZ9QvpMwgychQtHmrvg';
        $date = date('Y-m-d H:i:s', TIMESTAMP);
        $template = array(
            'touser' => $from_user,
            'template_id' => $template_id,
            'url' => "",
            'topcolor' => "#e32222",
            'data' => array(
                'account' => array(
                    'value' => urlencode($card['cardnumber']),
                    'color' => '#f00'
                ),
                'time' => array(
                    'value' => urlencode($date),
                    'color' => 'red'
                ),
                'type' => array(
                    'value' => urlencode("会员卡消费赠返积分"),
                    'color' => '#f00'
                ),
                'creditChange' => array(
                    'value' => urlencode("到账"),
                    'color' => '#f00'
                ),
                'number' => array(
                    'value' => urlencode($totalspendscore),
                    'color' => '#f00'
                ),
                'creditName' => array(
                    'value' => urlencode("账户积分"),
                    'color' => '#f00'
                ),
                'amount' => array(
                    'value' => urlencode($fans['credit1']),
                    'color' => '#f00'
                ),
                'remark' => array(
                    'value' => urlencode("您可以点击会员中心，随时查询账户余额积分。"),
                    'color' => '#f00'
                ),
            )
        );

//        $template = array(
//            'touser' => $from_user,
//            'template_id' => 'ZSN2XJK-HrW5BLkgJ8XNtteGIhiNkPs5aUlfzbK-1Og',
//            'url' => "",
//            'topcolor' => "#FF0000",
//            'data' => array(
//                'first' => array(
//                    'value' => urlencode("会员卡"),
//                    'color' => '#f00'
//                ),
//                'name' => array(
//                    'value' => urlencode("迷失卍国度2"),
//                    'color' => 'red'
//                ),
//                'remark' => array(
//                    'value' => urlencode("credit2"),
//                    'color' => '#f00'
//                ),
//            )
//        );

        //$templateMessage = new class_templateMessage($this->_appid, $this->_appsecret);
        //$templateMessage->send_template_message($template);

        $this->showMessageAjax('消费成功!');
    }

    //今天签到状态
    public function getTodaySignStatus()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $date = date('Y-m-d');
        $sign = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_sign') . " WHERE weid = :weid and from_user = :from_user and  date_format(FROM_UNIXTIME(dateline), '%Y-%m-%d') = :date ", array(':weid' => $weid, ':from_user' => $from_user, ':date' => $date));
        if (!empty($sign)) {
            return 1;
        } else {
            return 0;
        }
    }

    //根据月份取得数据
    public function getSignInMonth($y, $m)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $data = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_sign') . " WHERE from_user = :from_user and weid = :weid and date_format(FROM_UNIXTIME(dateline),'%Y-%m') = '{$y}-{$m}'", array(':from_user' => $from_user, ':weid' => $weid));
        return $data;
    }

    //根据月份取得数据
    public function getSignList()
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $data = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_sign') . " WHERE from_user = :from_user and weid = :weid ORDER BY dateline DESC", array(':from_user' => $from_user, ':weid' => $weid));
        return $data;
    }

    public function getAnnounceCount($level)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $sql = "SELECT COUNT(*) FROM " . tablename('weisrc_card_announce') . " WHERE weid = :weid and (levelid=0 or levelid = :level or (levelid=-1 and from_user=:from_user)) ORDER BY id DESC limit 50";
        return pdo_fetchcolumn($sql, array(':weid' => $weid, ':from_user' => $from_user, ':level' => $level['id']));
    }

    //最后签到数据
    public function getLastSign($weid, $from_user)
    {
        global $_W, $_GPC;
        $sign = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_sign') . " WHERE weid = :weid and from_user = :from_user order by dateline desc limit 1 ", array(':weid' => $weid, ':from_user' => $from_user));
        return $sign;
    }

    //是否存在用户
    public function checkUser($isajax = true)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $msg = '会话已过期，从重新发送关键字登录!';
        if (empty($from_user) || $weid == 0) {
            if ($isajax) {
                $this->showMessageAjax($msg, 1);
            } else {
                message($msg);
            }
        }
    }

    public function checkUserCard($card, $jump = true, $isgetcard = false)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        if ($isgetcard == false) {
            if (empty($card)) {
                if ($jump) {
                    header("location:" . $this->createMobileUrl('getmember', array(), true));
                } else {
                    message('请先领取会员卡！');
                }
            }
        } else {
            if (!empty($card)) {
                header("location:" . $this->createMobileUrl('index', array(), true));
            }
        }
    }

    //会员卡号码
    public function getCardNumber($weid)
    {
        global $_W, $_GPC;

        //当前会员卡
        $card = pdo_fetch("select cardstart from " . tablename('weisrc_card_style') . " where weid =" . $weid . " order by id desc limit 1");
        if (!empty($card)) {
            $cardstart = intval($card['cardstart']);
        }
        //查询公众号会员卡目前最大卡号
        $user_card = pdo_fetch("select cardno from " . tablename('weisrc_card_card') . " where weid =" . $weid . " order by id desc limit 1");
        if (!empty($user_card)) {
            return intval($user_card['cardno']) + 1;
        } else {
            if (empty($cardstart)) {
                return 1000001;
            } else {
                return $cardstart;
            }
        }
    }

    public function updateBalanceScore($need_score)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        pdo_query("UPDATE " . tablename('weisrc_card_card') . " SET balance_score = balance_score-:needscore WHERE weid = :weid AND from_user = :from_user ", array(':needscore' => $need_score, ':weid' => $weid, ':from_user' => $from_user));
    }

    public function get_gift($id, $weid)
    {
        global $_W, $_GPC;

        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_gift') . " WHERE weid = :weid and id = :id ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':id' => $id));
    }

    public function get_privilege($id, $weid)
    {
        global $_W, $_GPC;

        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_privilege') . " WHERE weid = :weid and id = :id ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':id' => $id));
    }

    public function get_coupon($id, $weid)
    {
        global $_W, $_GPC;

        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_coupon') . " WHERE weid = :weid and id = :id ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':id' => $id));
    }

    public function getCardByAdmin($weid, $from_user)
    {
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_card') . " WHERE weid = :weid and from_user = :from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
    }

    //检查密码
    public function check_card_password($weid, $pwd)
    {
        global $_W, $_GPC;
        return pdo_fetch("SELECT id FROM " . tablename('weisrc_card_style') . " WHERE weid = :weid and pwd=:pwd ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':pwd' => $pwd));
    }

    public function checkUserPassword($username, $password)
    {
        global $_W, $_GPC;
        load()->model('user');
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = :weid AND password=:password AND username=:username limit 1", array(':weid' => $_W['uniacid'], ':password' => user_hash($password, ''), ':username' => $username));
    }

    public function check_store_password($weid, $pwd, $id)
    {
        global $_W, $_GPC;

        return pdo_fetch("SELECT id FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid and password=:pwd and id=:id ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':pwd' => $pwd, ':id' => $id));
    }

    //新增兑换码
    public function addSncode($data = array())
    {
        global $_W, $_GPC;

        $data = array(
            'pid' => $data['pid'],
            'type' => $data['type'],
            'weid' => $data['weid'],
            'sncode' => $data['sncode'],
            'from_user' => $data['from_user'],
            'status' => 0,
            'winningtime' => TIMESTAMP,
            'usetime' => 0,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_sncode', $data);
        return pdo_insertid();
    }

    public function getCouponStrWhere($levelid)
    {
        global $_W, $_GPC;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $isNotConsume = 0; //从未消费
        $isNotConsumeInMonth = 0; //在一个月内从未消费
        $singleConsume = 0; //单次消费
        $totalConsume = 0; //累计消费

        $strwhere = ' levelid=0 ';
        if ($levelid != 0) {
            $strwhere .= ' OR levelid= ' . $levelid;
        }
        if ($isNotConsume == 1) {
            $strwhere .= ' OR levelid=-2 ';
        }
        if ($isNotConsumeInMonth == 1) {
            $strwhere .= ' OR levelid=-3 ';
        }
        if ($singleConsume > 0) {
            $strwhere .= ' OR (levelid=-4 AND permoney<= ' . $singleConsume . ') ';
        }
        if ($totalConsume > 0) {
            $strwhere .= ' OR (levelid=-5 AND allmoney<= ' . $totalConsume . ') ';
        }
        return $strwhere;
    }


    public function addCardRechargeLog($type, $score, $cardid = 0, $storeid = 0, $passwordid = 0)
    {
        global $_W ;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'type' => $type,
            'score' => $score,
            'cardid' => $cardid,
            'storeid' => $storeid,
            'passwordid' => $passwordid,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_recharge_log', $data);
    }

    //创建充值订单
    public function doMobileCreateOrder()
    {
        global $_W, $_GPC;

        $from_user = $_W['fans']['from_user'];

        if (empty($from_user)) {
            $this->showMessage('会话已过期，请发送关键字重新登录.');
        }

        $money = intval($_GPC['money']);
        if ($money <= 0) {
            $this->showMessage('请输入充值金额.');
        } else if ($money > 1000) {
            $this->showMessage('每次充值不能大于1000.');
        }

        $data = array(
            'weid' => $_W['uniacid'],
            'from_user' => $from_user,
            'ordersn' => date('md') . random(4, 1),
            'price' => $money,
            'status' => 0,
            'paytype' => '2', //在线 支付宝
            'remark' => '',
            'dateline' => TIMESTAMP,
        );

        pdo_insert($this->modulename . '_order', $data);
        $orderid = pdo_insertid();

        if (empty($orderid)) {
            $result['msg'] = '充值失败!';
            $result['status'] = 0; //1代表成功
        } else {
            $result['msg'] = '';
            $result['status'] = 1; //1代表成功
            $result['orderid'] = $orderid;
        }
        message($result, '', 'ajax');
    }

    private $version = '1|9|8|5|9|8|4|8|4|3';

    //跳转到支付页面
    public function doMobilePay()
    {
        global $_W, $_GPC;
        $this->checkAuth();
        $orderid = intval($_GPC['orderid']);
        $order = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_order') . " WHERE id = :id", array(':id' => $orderid));
        if ($order['status'] != '0') {
            message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', 'error');
        }

        if (checksubmit()) {
            if ($order['price'] == '0') {
                $this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
                exit;
            }
        }

        $sql = 'SELECT * FROM ' . tablename('paylog') . ' WHERE `weid`=:weid AND `module`=:module AND `tid`=:tid';
        $pars = array();
        $pars[':weid'] = $_W['uniacid'];
        $pars[':module'] = 'weisrc_card';
        $pars[':tid'] = $orderid;
        $log = pdo_fetch($sql, $pars);
        if (!empty($log) && $log['status'] == '1') {
            message('这个订单已经支付成功, 不需要重复支付.');
        }
        $params['tid'] = $orderid;
        $params['user'] = $_W['fans']['from_user'];
        $params['fee'] = $order['price'];
        $params['title'] = $_W['account']['name'] . "支付宝充值{$order['ordersn']}";
        $params['module'] = $this->modulename;
        $params['virtual'] = 1;
        include $this->template('pay_result');

        //include $this->template('pay');
    }

    public function payResult($params)
    {
        global $_W, $_GPC;

        $fee = intval($params['fee']);
        $data = array('status' => $params['result'] == 'success' ? 1 : 0);
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }

        $order = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_order') . " WHERE id=:id", array(':id' => $params['tid']));

        if (empty($order)) {
            message('订单信息不存在！', '../../' . $this->createMobileUrl('index', array(), true), 'error');
        }

        //更新订单状态
        pdo_update($this->modulename . '_order', $data, array('id' => $params['tid']));

        $weid = $order['weid'];
        $from_user = $order['from_user'];
        $price = $order['price'];

        $card = $this->getCard($weid, $from_user);
        if (empty($card)) {
            message('会员卡不存在！', '../../' . $this->createMobileUrl('index', array(), true), 'error');
        }

        if ($card['status'] == 1) {
            message('您的会员卡已被冻结,请联系商户！', '../../' . $this->createMobileUrl('index', array(), true), 'error');
        }

        if ($order['status'] == 0) {
            $rowcount = pdo_query("UPDATE " . tablename('weisrc_card_card') . " SET coin = coin+:price WHERE id=:id", array(':price' => $price, ':id' => $card["id"]));
            if ($rowcount > 0) {
                $result['status'] = 1;
                //日志
                $this->addCardLog(3, $price, -1, $card["id"]);
            } else {
                message('操作失败！', '../../' . $this->createMobileUrl('index', array(), true), 'error');
            }
        }

        if ($params['from'] == 'return') {
            if ($params['type'] == 'credit2') {
                message('支付成功！', $this->createMobileUrl('index', array(), true), 'success');
            } else {
                message('支付成功！', '../../' . $this->createMobileUrl('index', array(), true), 'success');
            }
        }
    }

    //取得用户会员卡信息
    public function getUserCard()
    {
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        $card = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_card') . " WHERE weid = :weid AND from_user=:from_user ORDER BY `id` DESC LIMIT 1", array(':weid' => $weid, ':from_user' => $from_user));
        return $card;
    }

    //取得会员卡积分策略
    public function getCardScore()
    {
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_score') . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $weid));
    }

    //tool function
    public function showMessageAjax($msg, $status = 0, $success = 0, $billid = 0)
    {
        $result = array('message' => $msg, 'status' => $status, 'issuccess' => $success, 'billid' => $billid);
        echo json_encode($result);
        exit;
    }

    private function checkAuth()
    {
        global $_W;
        checkauth();
    }

    public function doMobileVersion()
    {
        message($this->version);
    }

    function authorization()
    {
        $host = get_domain();
        return base64_encode($host);
    }

    function code_compare($a, $b)
    {
        if ($this->_debug == 1) {
            if ($_SERVER['HTTP_HOST'] == '127.0.0.1') {
                return true;
            }
        }
        if ($a != $b) {
            message(base64_decode("5a+55LiN6LW377yM5oKo5L2/55So55qE57O757uf5piv55Sx6Z2e5rOV5rig6YGT5Lyg5pKt55qE77yM6K+35pSv5oyB5q2j54mI44CC6LSt5Lmw6L2v5Lu26K+36IGU57O7UVExNTU5NTc1NeOAgg=="));
        }
        return true;
    }

    function isWeixin()
    {
        if ($this->_weixin==1) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if (!strpos($userAgent, 'MicroMessenger')) {
                include $this->template('s404');
                exit();
            }
        }
    }

    public function oauth2()
    {
        global $_GPC, $_W;
        load()->func('communication');
        $code = $_GPC['code'];
        $token = $this->getAuthorizationCode($code);
        $from_user = $token['openid'];
        $userinfo = $this->getUserInfo($from_user);
        if ($userinfo['subscribe'] == 0) {
            //未关注用户通过网页授权access_token
            $userinfo = $this->getUserInfo($from_user, $token['access_token']);
        }
        if (empty($userinfo) || !is_array($userinfo) || empty($userinfo['openid']) || empty($userinfo['nickname'])) {
            echo '<h1>获取微信公众号授权失败[无法取得userinfo], 请稍后重试！ 公众平台返回原始数据为: <br />' . $userinfo['meta'] . '<h1>';
            exit;
        }
        $headimgurl = $userinfo['headimgurl'];
        $nickname = $userinfo['nickname'];
        //设置cookie信息
        setcookie($this->_auth2_headimgurl, $headimgurl, time() + 3600 * 24);
        setcookie($this->_auth2_nickname, $nickname, time() + 3600 * 24);
        setcookie($this->_auth2_openid, $from_user, time() + 3600 * 24);
        return $userinfo;
    }

    public function getUserInfo($from_user, $ACCESS_TOKEN = '')
    {
        if ($ACCESS_TOKEN == '') {
            $ACCESS_TOKEN = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        } else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$ACCESS_TOKEN}&openid={$from_user}&lang=zh_CN";
        }
        $json = ihttp_get($url);
        $userInfo = @json_decode($json['content'], true);
        return $userInfo;
    }

    public function getAuthorizationCode($code)
    {
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code={$code}&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit;
        }
        return $token;
    }

    public function getAccessToken()
    {
        global $_W;
        $account = $_W['account'];
        if(is_array($account['access_token']) && !empty($account['access_token']['token']) && !empty($account['access_token']['expire']) && $account['access_token']['expire'] > TIMESTAMP) {
            return $account['access_token']['token'];
        } else {
            if(empty($account['acid'])) {
                message('参数错误.');
            }
            if (empty($account['key']) || empty($account['secret'])) {
                message('请填写公众号的appid及appsecret, (需要你的号码为微信服务号)！', url('account/bind/post', array('acid' => $account['acid'], 'uniacid' => $account['uniacid'])), 'error');
            }
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$account['key']}&secret={$account['secret']}";
            $content = ihttp_get($url);
            if(empty($content)) {
                message('获取微信公众号授权失败, 请稍后重试！');
            }
            $token = @json_decode($content['content'], true);
            if(empty($token) || !is_array($token)) {
                message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: <br />' . $token);
            }
            if(empty($token['access_token']) || empty($token['expires_in'])) {
                message('解析微信公众号授权失败, 请稍后重试！');
            }
            $record = array();
            $record['token'] = $token['access_token'];
            $record['expire'] = TIMESTAMP + $token['expires_in'];
            $row = array();
            $row['access_token'] = iserializer($record);
            pdo_update('account_wechats', $row, array('acid' => $account['acid']));
            return $record['token'];
        }
    }

    public function getCode($url)
    {
        global $_W;
        $url = urlencode($url);
        $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=0#wechat_redirect";
        header("location:$oauth2_code");
    }


    public function doWebTemplate()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'template';
        $title = '风格管理';

        //$tpl = dir(IA_ROOT.'/themes/mobile/');
        $tpl = dir(IA_ROOT . '/addons/weisrc_card/template/mobile/');
        $tpl->handle;
        $templates = array();
        while ($entry = $tpl->read()) {
            if (preg_match("/^[a-zA-Z0-9]+$/", $entry) && $entry != 'common' && $entry != 'photo') {
                array_push($templates, $entry);
            }
        }
        $tpl->close();
        $template = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_template') . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));

        if (empty($template)) {
            $templatename = 'style1';
        } else {
            $templatename = $template['template_name'];
        }

        if (!empty($_GPC['templatename'])) {

            $data = array(
                'weid' => $_W['uniacid'],
                'template_name' => trim($_GPC['templatename']),
            );

            if (empty($template)) {
                pdo_insert($this->modulename . '_template', $data);
            } else {
                pdo_update($this->modulename . '_template', $data, array('weid' => $_W['uniacid']));
            }
            message('操作成功', $this->createWebUrl('template'), 'success');
        }
        include $this->template('template');
    }

    public function get_coupon_strwhere($weid, $from_user, $levelid)
    {
        $isNotConsume = 0; //从未消费
        $isNotConsumeInMonth = 0; //在一个月内从未消费
        $singleConsume = 0; //单次消费
        $totalConsume = 0; //累计消费

        $strwhere = ' levelid=0 ';
        if ($levelid != 0) {
            $strwhere .= ' OR levelid= ' . $levelid;
        }
        if ($isNotConsume == 1) {
            $strwhere .= ' OR levelid=-2 ';
        }
        if ($isNotConsumeInMonth == 1) {
            $strwhere .= ' OR levelid=-3 ';
        }
        if ($singleConsume > 0) {
            $strwhere .= ' OR (levelid=-4 AND permoney<= ' . $singleConsume . ') ';
        }
        if ($totalConsume > 0) {
            $strwhere .= ' OR (levelid=-5 AND allmoney<= ' . $totalConsume . ') ';
        }
        return $strwhere;
    }

    public function get_user_level($weid, $total_score)
    {
        $sql = "SELECT id,levelname FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid and :totalscore>=min and :totalscore<=max ORDER BY `min` limit 1";
        return pdo_fetch($sql, array(':weid' => $weid, ':totalscore' => $total_score));
    }

    public function getCard()
    {
        $weid = $this->_weid;
        $from_user = $this->_fromuser;
        return pdo_fetch("SELECT * FROM " . tablename('weisrc_card_card') . " WHERE weid = :weid and from_user = :from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
    }

    public function get_gifts_arr_front($type, $weid)
    {
        $tablename = '';
        switch ($type) {
            case 4:
                $tablename = tablename('weisrc_card_gift');
                break;
            case 3:
                $tablename = tablename('weisrc_card_privilege');
                break;
            case 2:
                $tablename = tablename('weisrc_card_coupon');
                break;
        }
        $levels = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE weid = '{$weid}' ");
        $arr = array();
        foreach ($levels as $key => $value) {
            $arr[$value['id']] = $value['title'];
        }
        return $arr;
    }

    //会员卡样式编辑
    public function doWebStyle()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'style';
        $title = $this->actions_titles[$action];
        $url = $this->createWebUrl($action);
        load()->func('tpl');

        $this->weisrc_card_style_check();
        if (checksubmit('submit')) {
            $userinfo = array(
                'iscarnumber' => intval($_GPC['iscarnumber']),
                'isemail' => intval($_GPC['isemail']),
                'isqq' => intval($_GPC['isqq']),
                'iscompany' => intval($_GPC['iscompany']),
                'isoccupation' => intval($_GPC['isoccupation']),
                'isposition' => intval($_GPC['isposition']),
                'isaddress' => intval($_GPC['isaddress'])
            );
            $data = array(
                'cardpre' => trim($_GPC['cardpre']),
                'cardstart' => intval($_GPC['cardstart']),
                'cardname' => trim($_GPC['cardname']),
                'cardnamecolor' => trim($_GPC['cardnamecolor']),
                'cardnumcolor' => trim($_GPC['cardnumcolor']),
                'cardstart' => intval($_GPC['cardstart']),
                'bg' => trim($_GPC['hidbg']),
                'diybg' => trim($_GPC['diybg']),
                'backbg' => trim($_GPC['backbg']),
                'pwd' => trim($_GPC['pwd']),
                'logo' => trim($_GPC['logo']),
                'content' => trim($_GPC['content']),
                'contentcolor' => trim($_GPC['contentcolor']),
                'show_logo' => intval($_GPC['show_logo']),
                'show_privilege' => intval($_GPC['show_privilege']),
                'show_coupon' => intval($_GPC['show_coupon']),
                'show_gift' => intval($_GPC['show_gift']),
                'userinfo' => serialize($userinfo),
                'dateline' => TIMESTAMP,
            );

            if (empty($data['cardname'])) {
                message('会员卡必须填写！');
            }
            if (strlen($data['pwd']) > 20) {
                message('输入的密码必须小于20个字符！');
            }
            //默认颜色#000000
            if (empty($data['cardnamecolor'])) {
                $data['cardnamecolor'] = '#000000';
            }
            if (empty($data['cardnumcolor'])) {
                $data['cardnumcolor'] = '#000000';
            }
            //当自定义网址不为空的时候
            if (!empty($data['diybg'])) {
                $data['bg'] = $data['diybg'];
            }
            //自定义卡号英文编号
            if (!empty($data['cardpre'])) {
                if (strlen($data['cardpre']) > 8) {
                    message('自定义卡号英文编号不能大于8位.');
                }
            }
            //初始卡号
            if (!empty($data['cardstart'])) {
                if (strlen($data['cardstart']) > 9) {
                    message('初始卡号不能大于9位.');
                }
            } else {
                $data['cardstart'] = 1000001;
            }

            $this->weisrc_card_style_update($data);
            message('操作成功！', $url);
        } else {
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_style') . " where weid =" . $_W['uniacid']);
            $userinfo = !empty($reply['userinfo']) ? unserialize($reply['userinfo']) : '';
            if (!empty($reply)) {
                if (!empty($reply['logo'])) {
                    $logo = tomedia($reply['logo']);
                }
                if (!empty($reply['bg'])) {
                    $bg = tomedia($reply['bg']);
                }

                if (!empty($reply['backbg'])) {
                    $backbg = tomedia($reply['backbg']);
                }
            }
            $page_sel_bg = '';
            for ($i = 1; $i < 24; $i++) {
                if ($i < 10) {
                    $bg_num = '0' . $i;
                } else {
                    $bg_num = $i;
                }
                $imgpath = "../addons/weisrc_card/template/images/card_bg" . $bg_num . ".png";
                if ($reply['bg'] == $imgpath) {
                    $page_sel_bg .= '<option value="' . $imgpath . '" selected>' . $imgpath . '</option>';
                    $page_sel_bg .= '<option value="' . $imgpath . '" selected>' . $imgpath . '</option>';
                } else {
                    $page_sel_bg .= "<option value=";
                    $page_sel_bg .= '<option value="' . $imgpath . '">' . $imgpath . '</option>';
                }
            }
            include $this->template('style');
        }
    }

    //商家编辑
    public function doWebBusiness()
    {
        global $_GPC, $_W;
        checklogin();
        load()->func('tpl');
        $action = 'business';
        $title = $this->actions_titles2[$action];
        $id = intval($_GPC['id']);
        $url = $this->createWebUrl($action);

        $reply = pdo_fetch("select * from " . tablename('weisrc_card_business') . " where weid = :weid", array(':weid' => $_W['uniacid']));
        if (!empty($reply)) {
            if (!empty($reply['logo'])) {
                if (strpos($reply['logo'], 'http') === false) {
                    $logo = $_W['attachurl'] . $reply['logo'];
                } else {
                    $logo = $reply['logo'];
                }
            }
        }
        if (checksubmit('submit')) {
            $data = array(
                'weid' => $_W['uniacid'],
                'title' => trim($_GPC['title']),
                'logo' => trim($_GPC['logo']),
                'content' => trim($_GPC['content']),
                'info' => trim($_GPC['info']),
                'tel' => trim($_GPC['tel']),
                'address' => trim($_GPC['address']),
                'location_p' => trim($_GPC['location_p']),
                'location_c' => trim($_GPC['location_c']),
                'location_a' => trim($_GPC['location_a']),
                'category_f' => $_GPC['category_f'],
                'category_s' => $_GPC['category_s'],
                'place' => trim($_GPC['place']),
                'lng' => trim($_GPC['lng']),
                'lat' => trim($_GPC['lat']),
                'dateline' => TIMESTAMP,
                'updatetime' => TIMESTAMP
            );

            if (!empty($reply)) {
                unset($data['dateline']);
                pdo_update('weisrc_card_business', $data, array('weid' => $_W['uniacid']));
            } else {
                pdo_insert('weisrc_card_business', $data);
            }
            message('操作成功！', $url);
        }
        include $this->template('business');
    }

    //积分策略
    public function doWebScore()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'score';
        $title = $this->actions_titles[$action];
        $url = $this->createWebUrl($action);

        $reply = pdo_fetch("select * from " . tablename('weisrc_card_score') . " where weid =" . $_W['uniacid']);
        if (checksubmit('submit')) {
            $data = array(
                'card_info' => trim($_GPC['card_info']),
                'score_info' => trim($_GPC['score_info']),
                'day_score' => intval($_GPC['day_score']),
                'runningdays' => intval($_GPC['runningdays']),
                'dayx_score' => intval($_GPC['dayx_score']),
                'payx_score' => intval($_GPC['payx_score']),
            );

            if ($data['day_score'] < 0 || $data['dayx_score'] < 0 || $data['payx_score'] < 0) {
                message('积分请不要输入负数', '', 'error');
            }
            if ($data['day_score'] > 100000 || $data['dayx_score'] > 100000 || $data['payx_score'] > 100000) {
                message('积分请不要输入大于10万', '', 'error');
            }

            if (!empty($reply)) {
                $this->weisrc_card_score_update($data);
            } else {
                $this->weisrc_card_score_insert($data);
            }
            message('操作成功！', $url);
        } else {
            include $this->template('score');
        }
    }

    //等级设置
    public function doWebLevel()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'level';
        $title = $this->actions_titles[$action];
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid order by max ", array(':weid' => $_W['uniacid']));
            if (checksubmit('submit')) {
                // 修改
                if (is_array($_GPC['levelname'])) {
                    foreach ($_GPC['levelname'] as $id => $val) {
                        $levelname = trim($_GPC['levelname'][$id]);
                        $min = intval($_GPC['min'][$id]);
                        $max = intval($_GPC['max'][$id]);
                        if (empty($levelname)) {
                            continue;
                        }
                        if ($max <= $min) {
                            message($levelname . '积分范围有误，请重新输入.', $url, 'error');
                        }
                        if ($max < 0 || $min < 0) {
                            message('积分不允许负数，请重新输入.', $url, 'error');
                        }
                        $data = array(
                            'levelname' => $levelname,
                            'min' => $min,
                            'max' => $max,
                            'weid' => $_W['uniacid']
                        );
                        pdo_update('weisrc_card_level', $data, array('id' => $id));
                    }
                }
                //增加
                if (is_array($_GPC['newlevelname'])) {
                    foreach ($_GPC['newlevelname'] as $nid => $val) {
                        $levelname = trim($_GPC['newlevelname'][$nid]);
                        $min = intval($_GPC['newmin'][$nid]);
                        $max = intval($_GPC['newmax'][$nid]);
                        if (empty($levelname)) {
                            continue;
                        }
                        if ($max <= $min) {
                            message($levelname . '积分范围有误，请重新输入.', $url, 'error');
                        }
                        if ($max < 0 || $min < 0) {
                            message('积分不允许负数，请重新输入.', $url, 'error');
                        }
                        $data = array(
                            'levelname' => $levelname,
                            'min' => $min,
                            'max' => $max,
                            'dateline' => TIMESTAMP,
                            'weid' => $_W['uniacid']
                        );
                        pdo_insert('weisrc_card_level', $data);
                    }
                }
                message('操作成功.', $url, 'success');
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_level', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('操作成功!', $url);
        }
        include $this->template('level');
    }

    //通知管理
    public function doWebAnnounce()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');
        $action = 'announce';
        $weid = $_W['uniacid'];
        $title = $this->actions_titles5[$action];
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $levels = $this->get_levels(); //会员等级
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = " WHERE weid = {$weid} and type=0 ";
            $announces = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_announce') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($announces)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_announce') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_announce') . " where id =" . $id);
            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = '{$_W['uniacid']}' order by max");
            if (!empty($reply)) {
                if (!empty($reply['thumb'])) {
                    $thumb = tomedia($reply['thumb']);
                }
            }
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'thumb' => trim($_GPC['thumb']),
                    'type' => 0,
                    'title' => trim($_GPC['title']),
                    'content' => trim($_GPC['content']),
                    'levelid' => intval($_GPC['levelid']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', 'error');
                }
                if (istrlen($data['content']) == 0) {
                    message('没有输入内容.', '', 'error');
                }
                if (istrlen($data['content']) > 2000) {
                    message('内容过多请重新输入.', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update('weisrc_card_announce', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert('weisrc_card_announce', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_announce', array('id' => $id, 'weid' => $weid));
            }
            message('操作成功!', $url);
        }
        include $this->template('announce');
    }

    function get_levels()
    {
        global $_W;
        $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = '{$_W['uniacid']}' ");
        $arr = array();
        $arr[0] = "所有会员";
        foreach ($levels as $key => $value) {
            $arr[$value['id']] = $value['levelname'];
        }
        return $arr;
    }

    //优惠券
    public function doWebCoupon()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');
        $weid = $_W['uniacid'];
        $action = 'coupon';
        $title = $this->actions_titles4[$action];
        $url = $this->createWebUrl($action);

        $stores = pdo_fetchall("select * from " . tablename($this->modulename . '_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            $coupon_type = array(
                '1' => '优惠券',
                '2' => '代金券',
                '3' => '礼品券',
                '4' => '积分兑换'
            );
            $coupon_attr_type = array(
                '1' => '普通券',
                '2' => '营销券'
            );

            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update('weisrc_card_coupon', $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            $levels = $this->get_levels();

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = "WHERE weid = {$weid} AND type<>4";

            $attrtype = intval($_GPC['attrtype']);
            if ($attrtype != 0) {
                $where .= " AND attr_type = ". $attrtype;
            }

            $coupons = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_coupon') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($coupons)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_coupon') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }

            $sncount = pdo_fetchall("SELECT count(1) as count,pid FROM " . tablename('weisrc_card_sncode') . " WHERE weid={$weid} GROUP BY pid", array(), 'pid');
            //普通券
            $type_count1 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_coupon') . " WHERE attr_type = 1 AND weid=:weid AND type<>4", array(':weid' => $weid));
            //营销券
            $type_count2 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_coupon') . " WHERE attr_type = 2 AND weid=:weid AND type<>4", array(':weid' => $weid));

            //优惠券券
            $coupon_count1 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_coupon') . " WHERE type = 1 AND type<>4 AND weid=:weid", array(':weid' => $weid));
            //代金券
            $coupon_count2 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_coupon') . " WHERE type = 2 AND type<>4 AND weid=:weid", array(':weid' => $weid));
            //优惠券券
            $coupon_count3 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_coupon') . " WHERE type = 3 AND type<>4 AND weid=:weid", array(':weid' => $weid));
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_coupon') . " where id =" . $id);
            if (!empty($reply)) {
                $levelarr = explode(',', $reply['levelids']);
                if (!empty($reply['thumb'])) {
                    if (strpos($reply['thumb'], 'http') === false) {
                        $thumb = $_W['attachurl'] . $reply['thumb'];
                    } else {
                        $thumb = $reply['thumb'];
                    }
                }
            }

            if (!empty($reply)) {
                $starttime = date('Y-m-d H:i', $reply['starttime']);
                $endtime = date('Y-m-d H:i', $reply['endtime']);
            } else {
                $starttime = date('Y-m-d H:i');
                $endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
            }

            //等级
            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid order by max", array(':weid' => $_W['uniacid']));

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'title' => trim($_GPC['title']),
                    'storeid' => intval($_GPC['store']),
                    'content' => trim($_GPC['content']),
                    'thumb' => trim($_GPC['thumb']),
                    'levelid' => intval($_GPC['levelid']),
                    'totalcount' => intval($_GPC['totalcount']),
                    'count' => intval($_GPC['count']),
                    'type' => intval($_GPC['type']),
                    'attr_type' => intval($_GPC['attr_type']),
                    'give_value' => intval($_GPC['give_value']),
                    'dmoney' => intval($_GPC['dmoney']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }

                if ($data['count'] < 0) {
                    message('优惠券张数不能小于于0.', '', 'error');
                }
                if ($data['count'] > 10000) {
                    message('优惠券张数不能大于10000.', '', 'error');
                }
                if ($data['count'] < 0) {
                    message('优惠券总张数不能小于于0.', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update('weisrc_card_coupon', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert('weisrc_card_coupon', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_coupon', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('操作成功!', $url);
        }

        include $this->template('coupon');
    }

    //开卡即送
    public function doWebGift()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');
        $weid = $_W['uniacid'];
        $action = 'gift';
        $title = $this->actions_titles4[$action];
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update('weisrc_card_gift', $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = " WHERE weid = {$weid} ";
            $gifts = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_gift') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($gifts)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_gift') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_gift') . " where id = :id", array(':id' => $id));

            if (!empty($reply)) {
                $starttime = date('Y-m-d H:i', $reply['starttime']);
                $endtime = date('Y-m-d H:i', $reply['endtime']);
            } else {
                $starttime = date('Y-m-d H:i');
                $endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
            }

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'title' => trim($_GPC['title']),
                    'storeid' => intval($_GPC['store']),
                    'type' => 1,
                    'score' => intval($_GPC['score']),
                    'content' => trim($_GPC['content']),
                    'showgetcard' => intval($_GPC['showgetcard']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'status' => 0,
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if ($endtime < $starttime) {
                    message('结束日期不能小于开始日期！', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    unset($data['status']);
                    pdo_update('weisrc_card_gift', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert('weisrc_card_gift', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_gift', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('操作成功!', $url);
        } else if ($operation == 'check') {
            $id = intval($_GPC['id']);
            $status = intval($_GPC['status']);

            if ($status == 0) {
                pdo_query("UPDATE " . tablename('weisrc_card_gift') . " SET status = 0 WHERE weid=:weid", array(':weid' => $_W['uniacid']));
            }
            pdo_query("UPDATE " . tablename('weisrc_card_gift') . " SET status = abs(status - 1) WHERE id=:id AND weid=:weid", array(':id' => $id, ':weid' => $_W['uniacid']));
            message('操作成功!', $url);
        }
        include $this->template('gift');
    }

    //积分兑换
    public function doWebExchange()
    {
        global $_W, $_GPC;
        checklogin();
        load()->func('tpl');
        $weid = $_W['uniacid'];
        $action = 'exchange';
        $title = $this->actions_titles4[$action];
        $url = $this->createWebUrl($action);

        $stores = pdo_fetchall("select * from " . tablename($this->modulename . '_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');

        $category = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_coupon') . " WHERE weid = :weid AND attr_type=2 AND :time<endtime ORDER BY displayorder DESC,id DESC", array(':weid' => $weid, ':time' => TIMESTAMP), 'id');
        if (!empty($category)) {
            $children = array();
            foreach ($category as $cid => $cate) {
                $children[$cate['type']][$cate['id']] = array($cate['id'], $cate['title']);
            }
        }

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update('weisrc_card_coupon', $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            $levels = $this->get_levels();

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = "WHERE weid = '{$weid}' AND type=4 ";
            $exchanges = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_coupon') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($exchanges)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_coupon') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $weid = intval($_W['uniacid']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_coupon') . " where id =" . $id);
            if (!empty($reply)) {
                $levelarr = explode(',', $reply['levelids']);
                if (!empty($reply['thumb'])) {
                    if (strpos($reply['thumb'], 'http') === false) {
                        $thumb = $_W['attachurl'] . $reply['thumb'];
                    } else {
                        $thumb = $reply['thumb'];
                    }
                }
            }

            if (!empty($reply)) {
                $starttime = date('Y-m-d H:i', $reply['starttime']);
                $endtime = date('Y-m-d H:i', $reply['endtime']);
            } else {
                $starttime = date('Y-m-d H:i');
                $endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
            }

            //等级
            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = :weid order by max", array(':weid' => $weid));

            if (checksubmit('submit')) {
                $ticket_id = intval($_GPC['ticket_id']);
                $ticket_ty = intval($_GPC['ticket_ty']);

                if ($ticket_ty <= 0) {
                    message('请选择优惠券类型.');
                }
                if ($ticket_id <= 0) {
                    message('请选择优惠券.');
                }

                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'title' => trim($_GPC['title']),
                    'content' => trim($_GPC['content']),
                    'levelid' => intval($_GPC['levelid']),
                    'type' => 4,//兑换类型 1=优惠券 2=营销券
                    'ticket_ty' => $ticket_ty,
                    'ticket_id' => $ticket_id,
                    'count' => intval($_GPC['count']),
                    'totalcount' => intval($_GPC['totalcount']),
                    'needscore' => intval($_GPC['needscore']),
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }

                if ($data['count'] < 0) {
                    message('优惠券张数不能小于于0.', '', 'error');
                }
                if ($data['count'] > 10000) {
                    message('优惠券张数不能大于10000.', '', 'error');
                }
                if ($data['count'] < 0) {
                    message('优惠券总张数不能小于于0.', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update('weisrc_card_coupon', $data, array('id' => $id, 'weid' => $weid));
                } else {
                    pdo_insert('weisrc_card_coupon', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_coupon', array('id' => $id, 'weid' => $weid));
            }
            message('操作成功!', $url);
        }
        include $this->template('exchange');
    }

    //特权管理
    public function doWebPrivilege()
    {
        global $_W, $_GPC;
        checklogin();
        $action = 'privilege';
        $title = $this->actions_titles[$action];
        load()->func('tpl');
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update('weisrc_card_privilege', $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }

            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = '{$_W['uniacid']}' order by max");
            foreach ($levels as $key => $value) {
                $levelarr[$value['id']] = $value['levelname'];
            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = "WHERE weid = '{$_W['uniacid']}'";
            $gifts = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_privilege') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($gifts)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_privilege') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_privilege') . " where id = :id AND weid=:weid", array(':id' => $id, ':weid' => $_W['weid']));
            if (!empty($reply)) {
                $levelarr = explode(',', $reply['levelids']);
            }

            if (!empty($reply)) {
                $starttime = date('Y-m-d H:i', $reply['starttime']);
                $endtime = date('Y-m-d H:i', $reply['endtime']);
            } else {
                $starttime = date('Y-m-d H:i');
                $endtime = date('Y-m-d H:i', TIMESTAMP+86400*30);
            }

            //等级
            $levels = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_level') . " WHERE weid = '{$_W['uniacid']}' order by max");

            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'title' => trim($_GPC['title']),
                    'content' => trim($_GPC['content']),
                    'levelids' => trim(implode(',', $_GPC['levelids'])),
                    'count' => intval($_GPC['count']),
                    'displayorder' => intval($_GPC['displayorder']),
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }
                if (istrlen($data['content']) == 0) {
                    message('没有输入内容.', '', 'error');
                }
                if (strlen($data['levelids']) == '') {
                    message('请选择所属人群', '', 'error');
                }
                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update('weisrc_card_privilege', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    $total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_privilege') . " where weid = {$_W['uniacid']}");
                    if ($total >= 8) {
                        message('您最多可以创建8条特权!', $url);
                    }
                    pdo_insert('weisrc_card_privilege', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_privilege', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('操作成功!', $url);
        }
        include $this->template('privilege');
    }

    public function doWebStore()
    {
        global $_W, $_GPC;
        checklogin();
        $action = 'store';
        $title = $this->actions_titles2[$action];
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (checksubmit('submit')) { //排序
                if (is_array($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $val) {
                        $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                        pdo_update('weisrc_card_store', $data, array('id' => $id));
                    }
                }
                message('操作成功!', $url);
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 15;
            $where = "WHERE weid = '{$_W['uniacid']}'";
            $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " {$where} order by displayorder desc,id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            if (!empty($stores)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_store') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_store') . " where id =" . $id);
            if (checksubmit('submit')) {
                $data = array(
                    'weid' => intval($_W['uniacid']),
                    'title' => trim($_GPC['title']),
                    'content' => trim($_GPC['content']),
                    'tel' => trim($_GPC['tel']),
                    'address' => trim($_GPC['address']),
                    'location_p' => trim($_GPC['location_p']),
                    'location_c' => trim($_GPC['location_c']),
                    'location_a' => trim($_GPC['location_a']),
                    'password' => trim($_GPC['password']),
                    'recharging_password' => trim($_GPC['recharging_password']),
                    'is_show' => intval($_GPC['is_show']),
                    'place' => trim($_GPC['place']),
                    'lng' => trim($_GPC['lng']),
                    'lat' => trim($_GPC['lat']),
                    'updatetime' => TIMESTAMP,
                    'dateline' => TIMESTAMP,
                );

                if (istrlen($data['title']) == 0) {
                    message('没有输入标题.', '', 'error');
                }
                if (istrlen($data['title']) > 30) {
                    message('标题不能多于30个字。', '', 'error');
                }

                if (istrlen($data['tel']) == 0) {
                    message('没有输入联系电话.', '', 'error');
                }
                if (istrlen($data['address']) == 0) {
                    message('请输入地址。', '', 'error');
                }

                if (!empty($reply)) {
                    unset($data['dateline']);
                    pdo_update('weisrc_card_store', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                } else {
                    pdo_insert('weisrc_card_store', $data);
                }
                message('操作成功!', $url);
            }
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            if ($id > 0) {
                pdo_delete('weisrc_card_store', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('操作成功!', $url);
        }
        include $this->template('store');
    }

    public function doWebSncodeList()
    {
        global $_GPC, $_W;
        $weid = $_W['uniacid'];
        $type = intval($_GPC['type']);
        $pid = intval($_GPC['id']);
        checklogin();
        $action = 'coupon';
        $url = $this->createWebUrl($action);
        $title = $this->actions_titles[$action];
        //$users = $this->getFansList();

        $coupon_list = pdo_fetchall("SELECT * FROM " . tablename("weisrc_card_coupon") . " WHERE weid = '{$_W['uniacid']}' ", array(), 'id');

        //门店
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $weid), 'id');

        $password = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  id DESC", array(), 'id');

        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $where = "WHERE a.weid = '{$_W['uniacid']}' and a.type = {$type} and a.pid = {$pid}";
        $wheretotal = "WHERE weid = '{$_W['uniacid']}' and type = {$type} and pid = {$pid}";

        $list = pdo_fetchall("SELECT a.*,b.id as cardid,b.cardnumber as cardnumber FROM " . tablename('weisrc_card_sncode') . " a INNER JOIN " . tablename('weisrc_card_card') . " b ON a.from_user=b.from_user {$where} order by a.id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");

        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_sncode') . " $wheretotal");
            $pager = pagination($total, $pindex, $psize);
        }
        include $this->template('sncodelist');
    }

    public function doWebUseSncodeAdmin()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'gift';
        $weid = $_W['uniacid'];
        $type = intval($_GPC['type']);
        $title = $this->actions_titles[$action];

        $pid = intval($_GPC['pid']); //商品id
        $url = $this->createWebUrl('sncodelist', array('op' => 'display', 'type' => $type, 'id' => $pid));
        $sncodeid = intval($_GPC['snid']); //兑换码id
        $storeid = intval($_GPC['store_id']); //门店id
        $couponid = 0;//优惠券id
        $money = intval($_GPC['money']);
        $paytype = intval($_GPC['paytype']); //1:现金消费 2:会员卡余额消费
        $passwordid = 0;

        $sncode = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_sncode') . " WHERE weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $sncodeid));
        $from_user = $sncode['from_user'];
        $this->_fromuser = $from_user;

        if (empty($sncode)) {
            message('兑换不存在.', $url, 'success');
        } else {
            $couponid = $sncode['pid'];
            if ($sncode['status'] == 1) {
                message('该兑换已经使用.', $url, 'success');
            }
        }

        $coupon = pdo_fetch("SELECT * FROM ".tablename("weisrc_card_coupon")." WHERE id=:id ORDER BY id DESC LIMIT 1", array(':id' => $couponid));

        if (empty($coupon)) {
            message('优惠券不存在.', $url, 'success');
        } else {
            if (TIMESTAMP > $coupon['endtime']) {
                message('优惠券已过期，不能使用！', $url, 'success');
            }
        }

        //会员卡
        $card = $this->getCard();
        $coin = 0;

        if (empty($card)) {
            message('会员卡不存在.', $url, 'success');
        }

        if ($type != 3) { //不是礼品券的时候
            if ($money == 0) {
                message('请输入消费金额.', $url, 'success');
            }
            if ($paytype == 2) { //余额消费
                $we7_card = pdo_fetch("SELECT credit2 FROM " . tablename('card_members') . ' WHERE weid=:weid AND from_user=:from_user LIMIT 1', array(':weid' => $weid, ':from_user' => $from_user));
                $coin = intval($we7_card['credit2']);
                if ($money > $coin) {
                    message('会员卡余额不足,请使用其它支付方式.', $url, 'success');
                }
            }
        } else if ($type == 3) {
            //更新sn码使用状态
            pdo_update('weisrc_card_sncode', array('status' => 1, 'usetime' => TIMESTAMP), array('id' => $sncodeid));
            message('兑换成功', $url, 'success');
        }

        $obj_score = pdo_fetch("SELECT * FROM " . tablename('weisrc_card_score') . " WHERE weid = :weid ", array(':weid' => $weid));
        $spend_score = intval($obj_score['payx_score']);
        //本次消费积分
        $totalspendscore = 0;
        if ($spend_score != 0) {
            $totalspendscore = $money * $spend_score;
        }

        $announce_title = '';
        $announce_content = '';
        $datetime = date('Y年m月d日H点i分', TIMESTAMP);
        if ($paytype == 1) {  //现金消费
            $this->setCardMoney($money);
            $announce_title = '现金消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用现金消费支出{$money}元。";
        } else if ($paytype == 2) { //余额消费
            $this->setCardMoney($money);//总消费金额
            $this->setFansMoney(-$money, '优惠券消费');
            load()->model('mc');
            $fans = mc_fetch($from_user);
            $coin = $fans['credit2'];
            $balance_score = $fans['credit1'];
            $announce_title = '余额消费';
            $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用余额消费支出{$money}元，当前余额{$coin}元。";
        }

        //消费记录
        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'title' => $coupon['title'] . '/' . $announce_title,
            'type' => 2,//1现金2消费3余额4充值
            'payment' => $paytype,//支付方式 1：现金支付 2：余额支付
            'passwordid' => $passwordid,
            'operationtype' => 0,//支出
            'storeid' => $storeid,
            'objectid' => $couponid,//优惠券
            'money' => $money,
            'score' => $totalspendscore,
            'dateline' => TIMESTAMP,
        );

        pdo_insert("weisrc_card_bill_log", $data);

        //增加剩余积分、总积分、签到积分
        if ($totalspendscore != 0) {
            $this->setCardCredit($totalspendscore, 'spend');
            $this->setFansCredit($totalspendscore, '优惠券消费增加积分');

            $announce_title = $coupon['title'] . '/' . $announce_title;
            //积分记录
            $this->addCardScoreLog($announce_title, 2, $totalspendscore, 1, 0);
        }

        //更新sn码使用状态
        pdo_update('weisrc_card_sncode', array('status' => 1, 'storeid' => $storeid, 'passwordid' => $passwordid, 'usetime' => TIMESTAMP), array('id' => $sncodeid));

        //通知
        $announce_title = $coupon['title'] . '/' . $announce_title;
        $this->AddAnnounce($announce_title, $announce_content, $from_user, $this->_announce_money, -1);

        message('操作成功!', $url, 'success');
    }

    public function doWebStoreLog()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'storelog';
        $weid = $_W['uniacid'];
        $storeid = intval($_GPC['storeid']);

        $url = $this->createWebUrl($action);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $where = "WHERE a.weid = '{$_W['uniacid']}'";
        $wheretotal = "WHERE weid = {$weid} ";

        if ($storeid != 0) {
            $where .= "  AND a.storeid={$storeid} ";
        }

        $sql = "SELECT a.*,b.cardpre as cardpre,b.cardno as cardno,b.from_user as from_user,b.cardnumber as cardnumber,b.id as cardid FROM " . tablename('weisrc_card_bill_log') . " a INNER JOIN " . tablename('weisrc_card_card') . " b ON a.from_user=b.from_user {$where} order by a.id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}";

        $list = pdo_fetchall($sql);
        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_bill_log') . " $wheretotal");
            $pager = pagination($total, $pindex, $psize);
        }

        $keylist = pdo_fetchall($sql, array(), 'from_user');
        $tmp = array_keys($keylist);
        $fans = $this->fansInfoSearch(array_keys($keylist));

        //门店
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));

        $store = array();
        foreach ($stores as $key => $value) {
            $store[$value['id']] = $value['title'];
        }

        $password = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  id DESC", array(), 'id');

        include $this->template('storelog');
    }

    public function doWebShopingLog()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'storelog';
        $weid = $_W['uniacid'];
        $cardid = intval($_GPC['cardid']);

        $card = pdo_fetch("select * from " . tablename('weisrc_card_card') . " where id =" . $cardid);
        if (empty($card)) {
            message('会员卡不存在');
        }
        $title = $card['cardnumber'];

        $url = $this->createWebUrl($action);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $where = "WHERE weid = '{$_W['uniacid']}' and from_user = '{$card['from_user']}'";
        $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_bill_log') . " {$where} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_bill_log') . " $where");
            $pager = pagination($total, $pindex, $psize);
        }

        //门店
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));
        $store = array();
        foreach ($stores as $key => $value) {
            $store[$value['id']] = $value['title'];
        }

        $password = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  id DESC", array(), 'id');

        include $this->template('shopinglog');
    }

    //消费日志excel
    public function doWebShopingLogExcel()
    {
        global $_GPC, $_W;
        checklogin();
        $weid = $_W['uniacid'];
        $cardid = intval($_GPC['cardid']);
        $card = pdo_fetch("select * from " . tablename('weisrc_card_card') . " where id =" . $cardid);
        $where = "WHERE from_user = '{$card['from_user']}'";
        $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_money_log') . " {$where} order by id desc");
        //门店
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));
        $store = array();
        foreach ($stores as $key => $value) {
            $store[$value['id']] = $value['title'];
        }
        $gifts_arr = $this->get_gifts_arr_front(4, $weid);
        $privilege_arr = $this->get_gifts_arr_front(3, $weid);
        $coupon_arr = $this->get_gifts_arr_front(2, $weid);

        $filename = '会员卡' . $card["cardnumber"] . '消费记录_' . date('YmdHis') . '.csv';
        $exceler = new Jason_Excel_Export();
        $exceler->charset('UTF-8');
        // 生成excel格式 这里根据后缀名不同而生成不同的格式。jason_excel.csv
        $exceler->setFileName($filename);
        // 设置excel标题行
        $excel_title = array('编号', '名称', '消费类型', '付款方式', '金额', '奖励积分', '操作门店', '消费时间');
        $exceler->setTitle($excel_title);
        // 设置excel内容
        $excel_data = array();
        foreach ($list as $key => $value) {
            if ($value['type'] == 3) {
                $name = $privilege_arr[$value['giftid']];
                $type = '特权';
            } else if ($value['type'] == 3) {
                $name = $gifts_arr[$value['giftid']];
                $type = '礼品券';
            } else if ($value['type'] == 2) {
                $name = $coupon_arr[$value['giftid']];
                $type = '优惠券';
            } else {
                $name = '没有相关数据';
                $type = '没有相关数据';
            }
            $payment = $value == 0 ? "现金" : "余额";
            $shop = empty($store[$value["storeid"]]) ? '后台' : $store[$value["storeid"]];
            if ($value['payment'] == 0) {
                $payment = '现金消费';
            } else {
                $payment = '余额消费';
            }
            $excel_data[] = array($value["id"], $name, $type, $payment, $value['money'], $value['score'], $shop, date("Y-m-d H:i:s", $value["dateline"]));
        }
        $exceler->setContent($excel_data);
        // 生成excel
        $exceler->export();
    }

    public function doWebRechargeLog()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'allrechargelog';

        $cardid = intval($_GPC['cardid']);
        $card = pdo_fetch("select * from " . tablename('weisrc_card_card') . " where id =" . $cardid);
        //$title = $card['cardpre'].$card['cardno'];
        $title = $card['cardnumber'];
        $url = $this->createWebUrl($action);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $where = "WHERE weid = '{$_W['uniacid']}' and cardid = '{$cardid}'";
        $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_recharge_log') . " {$where} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_recharge_log') . " $where");
            $pager = pagination($total, $pindex, $psize);
        }

        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));
        $store = array();
        foreach ($stores as $key => $value) {
            $store[$value['id']] = $value['title'];
        }
        $password = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  id DESC", array(), 'id');
        include $this->template('rechargelog');
    }

    //所有充值记录
    public function doWebAllRechargeLog()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'allrechargelog';
        $weid = $this->_weid;
        $url = $this->createWebUrl($action);

        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;

        $where = "WHERE a.weid = {$weid} ";
        $wheretotal = "WHERE weid = {$weid} ";
        $sql = "SELECT a.*,b.cardpre as cardpre,b.cardno as cardno,b.from_user as from_user,b.cardnumber as cardnumber FROM " . tablename('weisrc_card_recharge_log') . " a INNER JOIN " . tablename('weisrc_card_card') . " b ON a.cardid=b.id {$where} order by a.id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}";

        $list = pdo_fetchall($sql);
        $keylist = pdo_fetchall($sql, array(), 'from_user');
        $fans = $this->fansInfoSearch(array_keys($keylist));

        if (!empty($list)) {
            $total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename('weisrc_card_recharge_log') . " $wheretotal");
            $pager = pagination($total, $pindex, $psize);
        }

        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']), "id");

        $password = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_password') . " WHERE weid = '{$_W['uniacid']}' ORDER BY  id DESC", array(), 'id');
        include $this->template('allrechargelog');
    }

    public function fansInfoSearch($user)
    {
        $result = pdo_fetchall("SELECT a.openid,b.* FROM ".tablename('mc_mapping_fans')." a INNER JOIN ".tablename('mc_members')." b ON a.uid=b.uid WHERE a.openid IN ('".implode("','", is_array($user) ? $user : array($user))."')", array(), 'openid');
        return $result;
    }

    //消费日志excel
    public function doWebRechargeLogExcel()
    {
        global $_GPC, $_W;
        checklogin();
        $cardid = intval($_GPC['cardid']);
        $where = "WHERE cardid = '{$cardid}'";
        $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_card_log') . " {$where} order by id desc");
        $stores = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_store') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));
        $store = array();
        foreach ($stores as $key => $value) {
            $store[$value['id']] = $value['title'];
        }
        $card = pdo_fetch("select * from " . tablename('weisrc_card_card') . " where id =" . $cardid);

        $filename = '会员卡' . $card["cardnumber"] . '消费记录_' . date('YmdHis') . '.csv';
        $exceler = new Jason_Excel_Export();
        $exceler->charset('UTF-8');
        // 生成excel格式 这里根据后缀名不同而生成不同的格式。jason_excel.csv
        $exceler->setFileName($filename);
        // 设置excel标题行
        $excel_title = array('编号', '充值类型', '充值数量', '操作门店', '时间');
        $exceler->setTitle($excel_title);
        // 设置excel内容
        $excel_data = array();
        foreach ($list as $key => $value) {
            $type = $value == 2 ? "积分" : "金额";
            $shop = empty($store[$value["storeid"]]) ? '后台' : $store[$value["storeid"]];
            $excel_data[] = array($value["id"], $type, $value["score"], $shop, date("Y-m-d H:i:s", $value["dateline"]));
        }
        $exceler->setContent($excel_data);

        // 生成excel
        $exceler->export();
    }

    //会员卡excel
    public function doWebCardExcel()
    {
        global $_GPC, $_W;
        checklogin();

        $cardlist = pdo_fetchall("select * from " . tablename('weisrc_card_card') . " where weid =:weid", array(':weid' => $_W['uniacid']));

        $filename = '会员卡_' . date('YmdHis') . '.csv';
        $exceler = new Jason_Excel_Export();
        $exceler->charset('UTF-8');
        // 生成excel格式 这里根据后缀名不同而生成不同的格式。jason_excel.csv
        $exceler->setFileName($filename);
        // 设置excel标题行
        $excel_title = array('会员卡号', '姓名', '手机号码', '领卡时间', '余额', '剩余积分', '总积分', '状态');
        $exceler->setTitle($excel_title);
        // 设置excel内容
        $excel_data = array();
        $users = $this->getFansList();
        foreach ($cardlist as $key => $value) {
            $cardnumber = $value["cardnumber"]; //卡号
            $username = $users[$value['from_user']]['username']; //姓名
            $tel = $users[$value['from_user']]['tel']; //手机号码
            $date = date('Y-m-d H:i:s', $value['dateline']); //领卡时间
            $coin = $value["coin"]; //余额
            $balance_score = $value["balance_score"]; //剩余积分
            $total_score = $value["total_score"]; //总积分
            $status = $value["status"] == 0 ? "正常" : "冻结"; //状态
            $excel_data[] = array($cardnumber,
                $username,
                $tel,
                $date,
                $coin,
                $balance_score,
                $total_score,
                $status
            );
        }
        $exceler->setContent($excel_data);
        // 生成excel
        $exceler->export();
    }

    public function get_gifts_arr($type)
    {
        global $_W;
        $tablename = '';
        switch ($type) {
            case 4:
                $tablename = tablename('weisrc_card_gift');
                break;
            case 3:
                $tablename = tablename('weisrc_card_privilege');
                break;
            case 2:
                $tablename = tablename('weisrc_card_coupon');
                break;
        }
        $levels = pdo_fetchall("SELECT * FROM " . $tablename . " WHERE weid = '{$_W['uniacid']}' ");
        $arr = array();
        foreach ($levels as $key => $value) {
            $arr[$value['id']] = $value['title'];
        }
        return $arr;
    }

    public function getFansList()
    {
        global $_W;
        $fans = pdo_fetchall("SELECT * FROM " . tablename('mc_members') . " WHERE uniacid = :uniacid ", array(':uniacid' => $_W['uniacid']));
        $arr = array();
        foreach ($fans as $key => $value) {
            $arr[$value['from_user']]['username'] = $value['realname'];
            $arr[$value['from_user']]['tel'] = $value['mobile'];
        }
        return $arr;
    }

    public function doWebProgram()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'program';
        $title = $this->actions_titles[$action];
        $url = $this->createWebUrl($action);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        include $this->template('program');
    }

    public function doWebPassword()
    {
        global $_W, $_GPC;
        checklogin();
        $title = '消费密码管理';
        $action = 'password';
        $weid = intval($_W['uniacid']);
        $storeid = intval($_GPC['store']);

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        load()->model('user');
        $stores = pdo_fetchall("select * from " . tablename($this->modulename . '_store') . " WHERE weid=:weid", array(':weid' => $weid), 'id');
        if ($operation == 'display') {
            $password = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_password') . " WHERE weid = :weid ORDER BY id DESC", array(':weid' => $_W['uniacid']));
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $password = pdo_fetch("select * from " . tablename($this->modulename . '_password') . " WHERE id=:id", array(':id' => $id));
            }
            if (checksubmit()) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'username' => $_GPC['username'],
                    'storeid' => $storeid,
                    'consume' => intval($_GPC['consume']),
                    'recharge' => intval($_GPC['recharge']),
                    'status' => intval($_GPC['status']),
                    'dateline' => TIMESTAMP
                );

                if (!empty($_GPC['password'])) {
                    $data['password'] = user_hash($_GPC['password'], '');
                }

                if (empty($password)) {
                    pdo_insert($this->modulename . '_password', $data);
                } else {
                    unset($data['type']);
                    pdo_update($this->modulename . '_password', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                }
                message('数据更新成功！', $this->createWebUrl('password', array('op' => 'display')), 'success');
            }
        } else if ($operation == 'check') {
            $id = intval($_GPC['id']);
            pdo_query("UPDATE " . tablename($this->modulename . '_password') . " SET status=abs(1-status) WHERE id=:id AND weid = :weid", array(':id' => $id, ':weid' => $_W['uniacid']));
            message('数据更新成功！', $this->createWebUrl('password', array('op' => 'display')), 'success');
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $password = pdo_fetch("select * from " . tablename($this->modulename . '_password') . " WHERE id=:id", array(':id' => $id));
            if (!empty($password)) {
//                if ($password['type'] == 0) {
//                    message('该菜单不能删除！', $this->createWebUrl('password', array('op' => 'display')), 'error');
//                }
                pdo_delete($this->modulename . '_password', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('数据更新成功！', $this->createWebUrl('password', array('op' => 'display')), 'success');
        }
        include $this->template('password');
    }

    public function doWebMenu()
    {
        global $_W, $_GPC;
        checklogin();
        $title = '菜单管理';
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            if (!empty($_GPC['displayorder'])) {
                foreach ($_GPC['displayorder'] as $id => $displayorder) {
                    pdo_update($this->modulename . '_menu', array('displayorder' => $displayorder), array('id' => $id));
                }
                foreach ($_GPC['title'] as $id => $title) {
                    pdo_update($this->modulename . '_menu', array('title' => $title), array('id' => $id));
                }
                foreach ($_GPC['url'] as $id => $url) {
                    pdo_update($this->modulename . '_menu', array('url' => $url), array('id' => $id));
                }
                message('数据更新成功！', $this->createWebUrl('menu', array('op' => 'display')), 'success');
            }
            $menu = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_menu') . " WHERE weid = :weid ORDER BY displayorder DESC,id DESC", array(':weid' => $_W['uniacid']));
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $menu = pdo_fetch("select * from " . tablename($this->modulename . '_menu') . " WHERE id=:id", array(':id' => $id));
            }
            if (checksubmit()) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'url' => $_GPC['url'],
                    'icon' => $_GPC['icon'],
                    'content' => $_GPC['content'],
                    'type' => 1,
                    'status' => $_GPC['status'],
                    'displayorder' => $_GPC['displayorder']
                );

                if (empty($menu)) {
                    pdo_insert($this->modulename . '_menu', $data);
                } else {
                    unset($data['type']);
                    pdo_update($this->modulename . '_menu', $data, array('id' => $id, 'weid' => $_W['uniacid']));
                }
                message('数据更新成功！', $this->createWebUrl('menu', array('op' => 'display')), 'success');
            }
        } else if ($operation == 'check') {
            $id = intval($_GPC['id']);
            pdo_query("UPDATE " . tablename($this->modulename . '_menu') . " SET status=abs(1-status) WHERE id=:id", array(':id' => $id));
            message('数据更新成功！', $this->createWebUrl('menu', array('op' => 'display')), 'success');
        } else if ($operation == 'delete') {
            $id = intval($_GPC['id']);
            $menu = pdo_fetch("select * from " . tablename($this->modulename . '_menu') . " WHERE id=:id", array(':id' => $id));
            if (!empty($menu)) {
                if ($menu['type'] == 0) {
                    message('该菜单不能删除！', $this->createWebUrl('menu', array('op' => 'display')), 'error');
                }
                pdo_delete($this->modulename . '_menu', array('id' => $id, 'weid' => $_W['uniacid']));
            }
            message('数据更新成功！', $this->createWebUrl('menu', array('op' => 'display')), 'success');
        }
        include $this->template('menu');
    }

    //检查菜单是否存在
    public function checkMenuExist($menuname, $method)
    {
        global $_W;
        $flag = pdo_fetch('SELECT * FROM ' . tablename($this->modulename . '_menu') . ' Where method=:method And weid=:weid', array(':method' => $method, ':weid' => $_W['uniacid']));
        if (empty($flag)) {
            $data = array(
                'weid' => $_W['uniacid'],
                'title' => $menuname,
                'method' => $method,
                'type' => 0,
                'status' => 1,
                'displayorder' => 0
            );
            pdo_insert($this->modulename . '_menu', $data);
        }
    }

    public function doWebCard()
    {
        global $_W, $_GPC;
        checklogin();
        $action = 'card';
        $url = $this->createWebUrl($action);
        load()->model('mc');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display') {
            //$users = $this->getFansList();
            $pindex = max(1, intval($_GPC['page']));
            $psize = 10;
            $where = "WHERE weid = '{$_W['uniacid']}'";

            if (checksubmit('submit')) {
                $type = $_GPC["type"];
                $keyword = $_GPC["keyword"];
                switch ($type) {
                    case 'cardnumber':
                        $where .= " and cardnumber = '" . $keyword . "' ";
                        break;
                    case 'username':
                        $user = pdo_fetchall(" SELECT a.openid FROM " . tablename("mc_mapping_fans") . " a INNER JOIN " . tablename("mc_members") . " b ON a.uid=b.uid WHERE b.realname like '%" . $keyword . "%' AND b.uniacid=" . $_W['uniacid']);
                        $arr = array();
                        foreach ($user as $key => $value) {
                            $arr[] = "'" . $value['openid'] . "'";
                        }
                        if (!empty($user)) {
                            $userstr = implode(',', $arr);
                            $where .= " and from_user in (" . $userstr . ") ";
                        }
                        break;
                    case 'tel':
                        $user = pdo_fetchall(" SELECT a.openid FROM " . tablename("mc_mapping_fans") . " a INNER JOIN " . tablename("mc_members") . " b ON a.uid=b.uid WHERE b.mobile like '%" . $keyword . "%' AND b.uniacid=" . $_W['uniacid']);

                        $arr = array();
                        foreach ($user as $key => $value) {
                            $arr[] = "'" . $value['openid'] . "'";
                        }
                        if (!empty($user)) {
                            $userstr = implode(',', $arr);
                            $where .= " and from_user in (" . $userstr . ") ";
                        }
                        break;
                }
            }

            $list = pdo_fetchall("SELECT * FROM " . tablename('weisrc_card_card') . " {$where} order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'uid');
            if (!empty($list)) {
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_card') . " $where");
                $pager = pagination($total, $pindex, $psize);
            }

            $fans = mc_fetch(array_keys($list));
        } else if ($operation == 'post') {
            $id = intval($_GPC['id']);
            $reply = pdo_fetch("select * from " . tablename('weisrc_card_card') . " where id =" . $id);
            if (empty($reply)) {
                message('非法参数!', $url, 'error');
            }

            $user = fans_search($reply['uid']);
            $this->_fromuser = $reply['from_user'];

            if (empty($user)) {
                message($this->_fromuser . '用户不存在!', $url, 'error');
            }

            $user['birthday'] = strtotime($user['birthyear'] . '-' . $user['birthmonth'] . '-' . $user['birthday']);
            $level = $this->get_user_level($reply['weid'], $reply['total_score']);
            if (checksubmit('submit')) {
                $data = array();
                $data['realname'] = trim($_GPC['username']);
                $data['mobile'] = trim($_GPC['tel']);
                $data['address'] = trim($_GPC['address']);
                $birthtime = strtotime($_GPC['birthday']);
                $data['birthyear'] = date('y', $birthtime);
                $data['birthmonth'] = date('M', $birthtime);
                $data['birthday'] = date('d', $birthtime);
                $data['gender'] = intval($_GPC['sex']);
                $data['age'] = intval($_GPC['age']);

                if (istrlen($data['realname']) == 0) {
                    message('没有输入姓名.', '', 'error');
                }
                if (istrlen($data['realname']) > 16) {
                    message('姓名输入过长.', '', 'error');
                }

                if (!empty($reply)) {
                    $uid = mc_openid2uid($this->_fromuser);
                    mc_update($uid, $data);
                }

                pdo_query("UPDATE " . tablename('weisrc_card_card') . " SET carnumber = :carnumber WHERE id=:id", array(':carnumber' => trim($_GPC['carnumber']), ':id' => $id));
                message('操作成功!', $url);
            }
        }
        include $this->template('card');
    }

    public function doWebUserlog()
    {
        global $_W, $_GPC;
        $weid = intval($_W['uniacid']);
        checklogin();
        $action = 'userlog';
        $title = $this->actions_titles3[$action];
        $url = $this->createWebUrl($action);

        //总用户数量
        $user_total_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_card') . " WHERE weid = '{$weid}'");
        //今天新增数量
        $user_today_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_card') . " WHERE weid = '{$weid}' AND  date_format(from_UNIXTIME(`dateline`),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')");
        //昨天新增数量
        $user_yesterday_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_card') . " WHERE weid = '{$weid}' AND to_days(now())-to_days(from_UNIXTIME(`dateline`,'%Y-%m-%d %H:%i:%S'))=1");

        //一个月内的会员数据
        $data_user = pdo_fetchall("Select date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d') as date,count(date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')) as usercount FROM (SELECT * FROM " . tablename('weisrc_card_card') . " where DATE_SUB(CURDATE(), INTERVAL 1 month) <= date(FROM_UNIXTIME(dateline)) AND weid='{$weid}' ) a Group by date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')");

        //今天消费次数
        $consume_today_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = '{$weid}' AND  date_format(from_UNIXTIME(`dateline`),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')");
        //昨天消费次数
        $consume_yesterday_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = '{$weid}' AND to_days(now())-to_days(from_UNIXTIME(`dateline`,'%Y-%m-%d %H:%i:%S'))<=1");
        //总消费次数
        $consume_total_count = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_bill_log') . " WHERE weid = '{$weid}'");
        //一个月内的消费数据
        $data_money = pdo_fetchall("Select date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d') as date,count(date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')) as moneycount FROM (SELECT * FROM " . tablename('weisrc_card_bill_log') . " where DATE_SUB(CURDATE(), INTERVAL 1 month) <= date(FROM_UNIXTIME(dateline)) AND weid='{$weid}' ) a Group by date_format(FROM_UNIXTIME(dateline),'%Y-%m-%d')");

        $days = array();
        $moneys = array();
        $premonth = date('Y-m-d', strtotime('-1 month'));
        $nowmonth = date('Y-m-d');
        $y = date('Y', strtotime('-2 month'));
        $m = date('m', strtotime('-2 month'));
        $d = date('d', strtotime('-2 month'));

        $usercount = 0; //一个月内用户总数
        $moneycount = 0; //一个月内消费总数
        $count = $this->count_days(time(), strtotime('-1 month'));
        for ($i = 0; $i <= $count; $i++) {
            $date = date("Y-m-d", strtotime(' -' . $i . 'day'));
            $days[$date] = '0';
            foreach ($data_user as $key => $value) {
                if ($date == $value['date']) {
                    $days[$date] = $value['usercount'];
                    $usercount = $usercount + $value['usercount'];
                    //message($date.$value['usercount']);
                }
            }
            $moneys[$date] = '0';
            foreach ($data_money as $key => $value) {
                if ($date == $value['date']) {
                    $moneys[$date] = $value['moneycount'];
                    $moneycount = $moneycount + $value['moneycount'];
                }
            }
        }

        $user_str = '';
        $is_first = true;

        foreach (array_reverse($days) as $key => $value) {
            if ($is_first) {
                $user_str .= $value;
                $is_first = false;
            } else {
                $user_str .= ',' . $value;
            }
        }

        $money_str = '';
        $is_first = true;
        foreach (array_reverse($moneys) as $key => $value) {
            if ($is_first) {
                $money_str .= $value;
                $is_first = false;
            } else {
                $money_str .= ',' . $value;
            }
        }

        include $this->template('userlog');
    }

    function count_days($a, $b)
    {
        $a_dt = getdate($a);
        $b_dt = getdate($b);
        $a_new = mktime(12, 0, 0, $a_dt['mon'], $a_dt['mday'], $a_dt['year']);
        $b_new = mktime(12, 0, 0, $b_dt['mon'], $b_dt['mday'], $b_dt['year']);
        return round(abs($a_new - $b_new) / 86400);
    }

    //会员卡后台审核
    public function doWebCheckedCardstatus()
    {
        global $_GPC, $_W;
        checklogin();
        $action = 'card';
        $url = $this->createWebUrl($action);
        $cardid = intval($_GPC['id']);
        $status = intval($_GPC['status']);

        pdo_query("UPDATE " . tablename('weisrc_card_card') . " SET status = abs(:status - 1) WHERE id=:id", array(':status' => $status, ':id' => $cardid));
        message('操作成功!', $url);
    }

    public function doWebAddCardPrice()
    {
        global $_W, $_GPC;
        $from_user = $_GPC['from_user'];
        $this->_fromuser = $from_user;
        checklogin();
        $action = 'card';
        $url = $this->createWebUrl($action);
        $cardid = intval($_GPC['id']);
        $money = intval($_GPC['price']);
        $weid = $_W['uniacid'];

        $card = pdo_fetch("SELECT * FROM " . tablename("weisrc_card_card") . " WHERE id=:id LIMIT 1", array(':id' => $cardid));
        if (empty($card)) {
            message('会员卡不存在!', $url, 'error');
        }
        if ($card['status'] == 1) {
            message('该帐号已经被冻结，不能操作', $url, 'error');
        }
        if ($money > 10000) {
            message('每次充值最多10000.', $url, 'error');
        }

        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($card['from_user']);
        $fans = mc_fetch($uid, array("credit1"));
        $remark = $money > 0 ? '会员卡余额充值' : '会员卡余额扣除';
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            mc_credit_update($uid, 'credit2', $money, $log);
        }

        $datetime = date('Y年m月d日H点i分', TIMESTAMP);
        $announce_title = '会员卡充值';
        $announce_content ="您的尾号{$card['cardnumber']}会员卡于{$datetime}使用现金充值充入{$money}元。";

        //消费记录
        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'title' => '会员卡充值',
            'type' => 4,//1现金2消费3余额4充值
            'payment' => 1,//支付方式 1：现金支付 2：余额支付
            'passwordid' => 0,
            'operationtype' => 1,//0支出 1充值
            'storeid' => 0,
            'objectid' => 0,//优惠券
            'money' => $money,
            'score' => 0,
            'dateline' => TIMESTAMP,
        );

        pdo_insert("weisrc_card_bill_log", $data);
        //通知
        $this->AddAnnounce($announce_title, $announce_content, $from_user, $this->_announce_price, 1);

        $this->addCardRechargeLogByAdmin(1, $money, 0, $cardid);
        message('操作成功!', $url);
    }

    public function AddBillLog($from_user, $title, $type = 1, $payment = 1, $passwordid = 0, $operationtype = 0, $storeid, $objectid = 0, $money, $score)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;

        //消费记录
        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'title' => $title,
            'type' => $type,//1现金2消费3余额4充值
            'payment' => $payment,//支付方式 1：现金支付 2：余额支付
            'passwordid' => $passwordid,
            'operationtype' => $operationtype,//0支出 1充值
            'storeid' => $storeid,
            'objectid' => $objectid,//优惠券
            'money' => $money,
            'score' => $score,
            'dateline' => TIMESTAMP,
        );

        pdo_insert("weisrc_card_bill_log", $data);
    }

    /**
     * 添加会员通知
     * @param $title
     * @param $content
     * @param $from_user
     * @param int $type  0:广播 1:个人
     * @param $levelid   -1:全部
     */
    public function AddAnnounce($title, $content, $from_user, $type = 0, $levelid = -1)
    {
        global $_GPC, $_W;
        $weid = $this->_weid;

        $data_announce = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'type' => $type,//1个人通知0广播
            'levelid' => $levelid,
            'title' => $title,
            'content' => $content,
            'dateline' => TIMESTAMP,
            'displayorder' => 0
        );
        pdo_insert("weisrc_card_announce", $data_announce);
    }

    public function doWebAddCardScore()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        checklogin();
        $action = 'card';
        $url = $this->createWebUrl($action);
        $cardid = intval($_GPC['id']);
        $score = intval($_GPC['score']);
        $remark = trim($_GPC['remark']);

        $card = pdo_fetch("SELECT * FROM " . tablename("weisrc_card_card") . " WHERE id=:id LIMIT 1", array(':id' => $cardid));
        if (empty($card)) {
            message('会员卡不存在!', $url, 'error');
        }
        if ($card['status'] == 1) {
            message('该帐号已经被冻结，不能充值', $url, 'error');
        }

        if ($score > 100000) {
            message('赠送积分每次最多100000.', $url, 'error');
        }

        $from_user = $card['from_user'];
        $uid = $card['uid'];
        $this->_fromuser = $from_user;//
        $this->_uid = $uid;

        $operationtype = $score > 0 ? 1 : 0;

        //增加剩余积分、总积分、签到积分
        $this->setCardCredit($score);
        load()->model('mc');
        load()->func('compat.biz');
        $uid = mc_openid2uid($card['from_user']);
        $fans = mc_fetch($uid, array("credit1"));
        $remark = $score > 0 ? '会员卡积分充值' : '会员卡积分扣除';
        if (!empty($fans)) {
            $uid = intval($fans['uid']);
            $log = array();
            $log[0] = $uid;
            $log[1] = $remark;
            mc_credit_update($uid, 'credit1', $score, $log);
        }
        $data_score_log = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'type' => 1,//积分类型 签到:1，消费:2
            'title' => '积分充值',
            'score' => abs($score),
            'operationtype' => $operationtype,//积分操作类型 增加:1  扣除:0
            'count' => 0,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_score_log', $data_score_log);
        $data = array(
            'weid' => $weid,
            'cardid' => $cardid,
            'from_user' => $from_user,
            'type' => 2,
            'score' => $score,
            'storeid' => 0, //后台
            'remark' => $remark,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_recharge_log', $data);
        message('操作成功!' . $cardid, $url);
    }

    public function addCardRechargeLogByAdmin($type, $score, $storeid = 0, $cardid = 0)
    {
        global $_W;
        $weid = $this->_weid;
        $from_user = $this->_fromuser;

        $data = array(
            'weid' => $weid,
            'from_user' => $from_user,
            'type' => $type,
            'score' => $score,
            'cardid' => $cardid,
            'storeid' => $storeid,
            'dateline' => TIMESTAMP
        );
        pdo_insert('weisrc_card_recharge_log', $data);
    }

    //添加入口
    public function doWebSetRule()
    {
        global $_W;
        $rule = pdo_fetch("SELECT id FROM " . tablename('rule') . " WHERE module = 'weisrc_card' AND weid = '{$_W['uniacid']}' order by id desc");
        if (empty($rule)) {
            header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_card')));
            exit;
        } else {
            header('Location: ' . $_W['siteroot'] . create_url('rule/post', array('module' => 'weisrc_card', 'id' => $rule['id'])));
            exit;
        }
    }

    //会员卡
    function weisrc_card_style_update($data)
    {
        global $_W;
        $data['updatetime'] = TIMESTAMP;
        return pdo_update('weisrc_card_style', $data, array('weid' => $_W['uniacid']));
    }

    function weisrc_card_style_check()
    {
        global $_W;
        $card_had = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('weisrc_card_style') . " WHERE weid = '{$_W['uniacid']}'");
        if (!$card_had) {
            $data['weid'] = $_W['uniacid'];
            $data['cardname'] = '会员卡';
            $data['cardnamecolor'] = '#000000';
            $data['cardnumcolor'] = '#000000';
            $data['bg'] = '';
            $data['logo'] = '';
            $data['diybg'] = '';
            $data['dateline'] = $data['updatetime'] = TIMESTAMP;
            pdo_insert('weisrc_card_style', $data);
        }
    }

    //积分策略
    function weisrc_card_score_insert($data)
    {
        global $_W;
        $data['weid'] = $_W['uniacid'];
        $data['dateline'] = TIMESTAMP;
        $data['updatetime'] = TIMESTAMP;
        return pdo_insert('weisrc_card_score', $data);
    }

    function weisrc_card_score_update($data)
    {
        global $_W;
        $data['updatetime'] = TIMESTAMP;
        return pdo_update('weisrc_card_score', $data, array('weid' => $_W['uniacid']));
    }

    /*
    ** 设置切换导航
    */
    public function set_tabbar($action, $type = 1)
    {
        if ($type == 1) {
            $actions_titles = $this->actions_titles;
        } else if ($type == 2) {
            $actions_titles = $this->actions_titles2;
        } else if ($type == 3) {
            $actions_titles = $this->actions_titles3;
        } else if ($type == 4) {
            $actions_titles = $this->actions_titles4;
        } else if ($type == 5) {
            $actions_titles = $this->actions_titles5;
        } else if ($type == 6) {
            $actions_titles = $this->actions_titles6;
        }

        $html = '<ul class="nav nav-tabs">';
        foreach ($actions_titles as $key => $value) {
            //$url = 'site.php?act=module&do=' . $key . '&name=' . $this->modulename;
            $url = $this->createWebUrl($key, array('op' => 'display'));
            $html .= '<li class="' . ($key == $action ? 'active' : '') . '"><a href="' . $url . '">' . $value . '</a></li>';
        }

        $html .= '</ul>';
        return $html;
    }

    public function showMessage($msg = '', $status = 0, $isajax = true)
    {
        $result['msg'] = $msg;
        $result['status'] = $status; //1代表成功
        if ($isajax) {
            message($result, '', 'ajax');
        } else {
            message($result['msg'], '', 'error');
        }
    }

}

/**
 * 导出Excel
 *
 * @package:     Jason
 * @subpackage:  Excel
 * @version:     1.0
 */
class Jason_Excel_Export
{
    /**
     * Excel 标题
     *
     * @type: Array
     */
    private $_titles = array();

    /**
     * Excel 标题数目
     *
     * @type: int
     */
    private $_titles_count = 0;

    /**
     * Excel 内容
     *
     * @type:  Array
     */
    private $_contents = array();

    /**
     * Excel 内容数据
     *
     * @type:  Array
     */
    private $_contents_count = 0;

    /**
     * Excel 文件名
     *
     * @type: string
     */
    private $_fileName = '';
    private $_split = "\t";

    private $_charset = '';

    /**
     * 默认文件名
     *
     * @const :
     */
    const DEFAULT_FILE_NAME = 'jason_excel.xls';


    /**
     * 构造函数..
     *
     * @param    string  param
     * @return   mixed   return
     */
    function __construct($fileName = null)
    {
        if ($fileName !== null) {
            $this->_fileName = $fileName;
        } else {
            $this->setFileName();
        }
    }

    /**
     * 设置生成文件名
     *
     * @param    string  param
     * @return   mixed   Jason_Excel_Export
     */
    public function setFileName($fileName = self::DEFAULT_FILE_NAME)
    {
        $this->_fileName = $fileName;
        $this->setSplite();
        return $this;
    }

    private function _getType()
    {
        return substr($this->_fileName, strrpos($this->_fileName, '.') + 1);
    }

    public function setSplite($split = null)
    {
        if ($split === null) {
            switch ($this->_getType()) {
                case 'xls':
                    $this->_split = "\t";
                    break;
                case 'csv':
                    $this->_split = ",";
                    break;
            }
        } else
            $this->_split = $split;
    }

    /**
     * 设置Excel标题
     *
     * @param    string  param
     * @return   mixed   Jason_Excel_Export
     */
    public function setTitle(&$title = array())
    {
        $this->_titles = $title;
        $this->_titles_count = count($title);
        return $this;
    }

    /**
     * 设置Excel内容
     *
     * @param    string  param
     * @return   mixed   Jason_Excel_Export
     */
    public function setContent(&$content = array())
    {
        $this->_contents = $content;
        $this->_contents_count = count($content);
        return $this;
    }

    /**
     * 向excel中添加一行内容
     */
    public function addRow($row = array())
    {
        $this->_contents[] = $row;
        $this->_contents_count++;
        return $this;
    }

    /**
     * 向excel中添加多行内容
     */
    public function addRows($rows = array())
    {
        $this->_contents = array_merge($this->_contents, $rows);
        $this->_contents_count += count($rows);
        return $this;
    }


    /**
     * 数据编码转换
     */
    public function toCode($type = 'GB2312', $from = 'auto')
    {
        foreach ($this->_titles as $k => $title) {
            $this->_titles[$k] = mb_convert_encoding($title, $type, $from);
        }

        foreach ($this->_contents as $i => $contents) {
            $this->_contents[$i] = $this->_toCodeArr($contents);
        }

        return $this;
    }

    private function _toCodeArr(&$arr = array(), $type = 'GB2312', $from = 'auto')
    {
        foreach ($arr as $k => $val) {
            $arr[$k] = mb_convert_encoding($val, $type, $from);
        }

        return $arr;
    }

    public function charset($charset = '')
    {
        if ($charset == '')
            $this->_charset = '';
        else {
            $charset = strtoupper($charset);
            switch ($charset) {
                case 'UTF-8' :
                case 'UTF8' :
                    $this->_charset = ';charset=UTF-8';
                    break;

                default:
                    $this->_charset = ';charset=' . $charset;
            }
        }

        return $this;
    }


    /**
     * 导出Excel
     *
     * @param    string  param
     * @return   mixed   return
     */
    public function export()
    {
        $header = '';
        $data = array();

        $header = implode($this->_split, $this->_titles);

        for ($i = 0; $i < $this->_contents_count; $i++) {
            $line_arr = array();
            foreach ($this->_contents[$i] as $value) {
                if (!isset($value) || $value == "") {
                    $value = '""';
                } else {
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"';
                }

                $line_arr[] = $value;
            }

            $data[] = implode($this->_split, $line_arr);
        }

        $data = implode("\n", $data);
        $data = str_replace("\r", "", $data);

        if ($data == "") {
            $data = "\n(0) Records Found!\n";
        }

        header("Content-type: application/vnd.ms-excel" . $this->_charset);
        header("Content-Disposition: attachment; filename=$this->_fileName");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "\xEF\xBB\xBF" . $header . "\n" . $data;
    }

    //您的尾号1000001会员卡于2014年11月26日14点31分支出123元，当前余额0.00元。
    //您于2014年11月20日14点28分消费1积分兑换的 礼品券已发送至您的会员账户，请及时查收。
}