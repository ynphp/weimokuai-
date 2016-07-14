<?php
/**
 * 猜涨跌模块
 * @author 微赞
 */
defined('IN_IA') or exit('Access Denied');

class kang_goldModuleSite extends WeModuleSite
{

    public function gethome()
    {
        global $_W;
        $articles = pdo_fetchall("select id,rid, title from " . tablename('kang_gold_reply') . " where uniacid = '{$_W['uniacid']}'");
        if (!empty($articles)) {
            foreach ($articles as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));
            }
            return $urls;
        }
    }

    public function doMobileShare()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $from_user = $_W['fans']['from_user'];
        $fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        }
        //print_r($_W);
        //exit;
        if (empty($from_user)) {
            //没有取得OPENID设置时间为OPENID保存分享记录
            $from_user = '无OPENID' . TIMESTAMP;
        }
        if ($from_user != $fromuser) {
            $sharenumtype = pdo_fetchcolumn("select sharenumtype from " . tablename('kang_gold_share') . " where uniacid = :uniacid and acid = :acid and rid = :rid order by `id` desc", array(':uniacid' => $uniacid, ':acid' => $acid, ':rid' => $rid));
            if ($sharenumtype == 0) {
                $sharedata = pdo_fetch("select id from " . tablename('kang_gold_data') . " where rid = '" . $rid . "' and from_user = '" . $from_user . "' and uniacid = '" . $uniacid . "'  limit 1");
            } else {
                $sharedata = pdo_fetch("select id from " . tablename('kang_gold_data') . " where rid = '" . $rid . "' and from_user = '" . $from_user . "' and fromuser = '" . $fromuser . "' and uniacid = '" . $uniacid . "'  limit 1");
            }
            //记录分享
            $insert = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'from_user' => $from_user,
                'fromuser' => $fromuser,
                'visitorsip' => CLIENT_IP,
                'visitorstime' => TIMESTAMP,
                'viewnum' => 1
            );
            if (empty($sharedata)) {
                pdo_insert('kang_gold_data', $insert);
            }
            //记录分享
        }
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('index', array('rid' => $rid)) . "");
        exit();
    }

    function get_share($uniacid, $rid, $fromuser, $title)
    {
        if (!empty($rid)) {
            //虚拟人数据配置
            $now = time();
            $reply = pdo_fetch("select xuninum_time,xuninumtime,xuninum,xuninuminitial,xuninumending from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            if ($now - $reply['xuninum_time'] > $reply['xuninumtime']) {
                pdo_update('kang_gold_reply', array('xuninum_time' => $now, 'xuninum' => $reply['xuninum'] + mt_rand($reply['xuninuminitial'], $reply['xuninumending'])), array('rid' => $rid));
            }
            //虚拟人数据配置
            $total = pdo_fetchcolumn("select xuninum+fansnum as total from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        }
        if (!empty($fromuser)) {
            $realname = pdo_fetchcolumn("select realname from " . tablename('kang_gold_fans') . " where uniacid= :uniacid and rid= :rid and from_user= :fromuser", array(':uniacid' => $uniacid, ':rid' => $rid, ':fromuser' => $fromuser));
        }
        $gifttitle = pdo_fetchcolumn("select description from " . tablename('kang_gold_award') . " where uniacid='" . $uniacid . "' and rid = '" . $rid . "' and from_user='" . $fromuser . "' and status>0 and prize>0 order by prize asc");
        $str = array('#参与人数#' => $total, '#参与人#' => $realname, '#奖品#' => $gifttitle);
        $result = strtr($title, $str);
        return $result;
    }

    public function doMobileindex()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
        $uniacid = $_W['uniacid'];
        $acid = $_W['acid'];
        $running = true;
        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        if (empty($acid)) {
            $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        }
        $share = pdo_fetch("select * from " . tablename('kang_gold_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid, ':acid' => $acid));


        /*--- 首页广告显示控制 begin ---*/
        if ($reply['homepictime'] > 0) {
            if (empty($_COOKIE['kang_gold_homepictime']) || $_COOKIE["kang_gold_homepictime"] <= time()) {
                setcookie("kang_gold_homepictime", mktime(23, 59, 59, date('d'), date('m'), date('Y')), mktime(23, 59, 59, date('d'), date('m'), date('Y')));
                include $this->template('homepictime');
                exit;
            }
        }
        /*--- 首页广告显示控制 end ---*/


        //获得关键词
        $keyword = pdo_fetch("select content from " . tablename('rule_keyword') . " where rid=:rid and type=1", array(":rid" => $rid));
        $reply['keyword'] = $keyword['content'];
        //获得关键词
        if (empty($from_user)) {

            //TODO WKL 现在要求是，没关注的可以抽奖，中奖率为0，再提示关注公众号


            /*
            //301跳转
            if (!empty($share['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['share_url'] . "");
                exit();
            }
            */

            //message('抱歉，参数错误！','', 'error');
            $isshare = 1;
            $running = false;
            $msg = '请先关注公共号。';
        } else {
            //查询是否为关注用户
            $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $from_user, ":uniacid" => $uniacid));
            if ($follow == 0) {


                //TODO WKL 现在要求是，没关注的可以抽奖，中奖率为0，再提示关注公众号
                /*
			    if (!empty($share['share_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['share_url'] . "");
                    exit();
                }
                */

                //message('抱歉，参数错误！','', 'error');
                $isshare = 1;
                $running = false;
                $msg = '请先关注公共号。';
            }

            //获得用户资料
            $profile = mc_fetch($fansID, array('avatar', 'nickname', 'realname', 'mobile', 'qq', 'email', 'address', 'gender', 'telephone', 'idcard', 'company', 'occupation', 'position'));
            $fans = pdo_fetch("select * from " . tablename('kang_gold_fans') . " where rid = '" . $rid . "' and fansID='" . $fansID . "' and from_user='" . $from_user . "'");
            if ($fans == false && $running == true) {
                $insert = array(
                    'rid' => $rid,
                    'uniacid' => $uniacid,
                    'fansID' => $fansID,
                    'from_user' => $from_user,
                    'avatar' => $profile['avatar'],
                    'nickname' => $profile['nickname'],
                    'todaynum' => 0,
                    'totalnum' => 0,
                    'awardnum' => 0,
                    'createtime' => time(),
                );
                $temp = pdo_insert('kang_gold_fans', $insert);
                $fans['id'] = pdo_insertid();
                $fans = pdo_fetch("select * from " . tablename('kang_gold_fans') . " where rid = '" . $rid . "' and fansID='" . $fansID . "' and from_user='" . $from_user . "'");
                if ($temp == false) {
                    message('抱歉，刚才操作数据失败！', '', 'error');
                }
                //增加人数，和浏览次数
                pdo_update('kang_gold_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
            } else {
                //增加浏览次数
                pdo_update('kang_gold_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
            }


            //查询是否需要弹出填写兑奖资料
            if ($fans['awardnum']) {
                //自动读取会员信息存入FANS表中
                $isfansh = 180;
                $ziduan = array('realname', 'mobile', 'qq', 'email', 'address', 'gender', 'telephone', 'idcard', 'company', 'occupation', 'position');
                foreach ($ziduan as $ziduans) {
                    if ($reply['is' . $ziduans]) {
                        if (!empty($profile[$ziduans]) && empty($fans[$ziduans])) {
                            pdo_update('kang_gold_fans', array($ziduans => $profile[$ziduans]), array('id' => $fans['id']));
                        } else {
                            if (empty($fans[$ziduans])) {
                                $$ziduans = true;
                            }
                        }
                        $isfansh += 38;
                    }
                }
                if ($fans['zhongjiang'] && ($realname || $mobile || $qq || $email || $address || $gender || $telephone || $idcard || $company || $occupation || $position)) {

                    // 中奖后，如果存在必填字段为空，就弹出填写兑奖资料
                    //TODO WKL 先去掉此功能中奖后
//                    $isfans = true;
                    $isfans = false;

                    $isfansh += 50;
                } else {
                    $isfansh = 180;
                }
                //自动读取会员信息存入FANS表中
            }

            //查询是否需要弹出填写兑奖资料
            //查询是活动定义的次数还是商户赠送次数
            if ($reply['opportunity'] == 1) {
                //商家赠送机会

                if (empty($profile['mobile'])) {
                    message('还没有注册成为会员，无法进入抽奖', url('entry//member', array('m' => 'stonefish_member', 'url' => url('entry//index', array('m' => 'kang_gold', 'rid' => $rid)))), 'error');
                    exit();
                }
                $doings = pdo_fetch("select awardcount,districtid,status from " . tablename('kang_branch_doings') . " where rid = " . $rid . " and mobile='" . $profile['mobile'] . "' and uniacid='" . $uniacid . "'");
                if (!empty($doings)) {
                    if ($doings['status'] < 2) {
                        $running = false;
                        $msg = '抱歉，您的抽奖资格正在审核中';
                    } else {
                        if ($doings['awardcount'] == 0) {
                            $running = false;
                            $msg = '抱歉，您的抽奖次数已用完了或没有获得抽奖次数';
                        } else {
                            $reply['number_times'] = $doings['awardcount'];
                        }
                    }
                    //查询网点资料
                    $business = pdo_fetch("select * from " . tablename('kang_branch_business') . " where id=" . $doings['districtid'] . "");
                    //更新网点记录到会员中心表
                    pdo_update('mc_members', array('districtid' => $doings['districtid']), array('uid' => $fansID));
                } else {
                    $running = false;
                    $msg = '抱歉，您的还未获得抽奖资格';
                }
            } elseif ($reply['opportunity'] == 2) {
                /*--- 抽奖次数选项:积分购买次数 ---*/

                $creditnames = array();
                $unisettings = uni_setting($uniacid, array('creditnames'));
                foreach ($unisettings['creditnames'] as $key => $credit) {
                    if ($reply['credit_type'] == $key) {
                        $creditnames = $credit['title'];
                        break;
                    }
                }
                //积分购买机会
                $credit = mc_credit_fetch($fansID, array($reply['credit_type']));
                $credit_times = intval($credit[$reply['credit_type']] / $reply['credit_times']);
                if ($reply['number_times']) {
                    if ($reply['number_times'] >= $credit_times) {
                        $reply['number_times'] = $credit_times;
                    }
                } else {
                    $reply['number_times'] = $credit_times;
                }
            }

            /*--- 查询分享赠送次数 begin ---*/
            if ($share['sharenum'] > 0) {
                if ($share['sharetype']) {
                    $sharenum = pdo_fetchcolumn("select count(id) as dd from " . tablename('kang_gold_data') . " where uniacid='" . $uniacid . "' and rid= '" . $rid . "' and fromuser='" . $from_user . "'");
                    if ($share['sharenumtype'] == 2 && $reply['number_times'] > 0 && $sharenum > 0) {
                        $reply['number_times'] = $reply['number_times'] + $share['sharenum'];
                    } elseif ($reply['number_times'] > 0) {
                        $reply['number_times'] = $reply['number_times'] + ($share['sharenum'] * $sharenum);
                    }
                } elseif ($fans['sharenum'] > 0 && $reply['number_times'] > 0) {
                    $reply['number_times'] = $reply['number_times'] + $share['sharenum'];
                }
            }
            /*--- 查询分享赠送次数 end ---*/


            //中奖名单
            if ($reply['awardnum']) {
                $awardlist = pdo_fetchall("select * from " . tablename('kang_gold_award') . " where uniacid='" . $uniacid . "' and rid = '" . $rid . "' and status>=1 group by fansID order by id desc limit " . $reply['awardnum']);
                foreach ($awardlist as $awardlists) {
                    $awardname = pdo_fetchcolumn("select realname from " . tablename('kang_gold_fans') . " where uniacid='" . $uniacid . "' and rid = '" . $rid . "' and fansID='" . $awardlists['fansID'] . "'");
                    $awardz = pdo_fetchall("select * from " . tablename('kang_gold_award') . " where uniacid='" . $uniacid . "' and rid = '" . $rid . "' and fansID='" . $awardlists['fansID'] . "' and status>=1 group by name order by id desc");
                    $awardx = '';
                    foreach ($awardz as $awards) {
                        $prizex = pdo_fetchcolumn("select COUNT(*) from " . tablename('kang_gold_award') . " where uniacid='" . $uniacid . "' and rid = '" . $rid . "' and fansID='" . $awardlists['fansID'] . "' and status>=1 and name='" . $awards['name'] . "'");
                        if ($prizex == 1) {
                            $awardx .= $awards['name'] . '&nbsp;&nbsp;';
                        } else {
                            $awardx .= $awards['name'] . ' X' . $prizex . '&nbsp;&nbsp;';
                        }
                    }
                    $awardname = empty($awardname) ? '匿名' : $awardname;
                    $award_list .= $awardname . '抽中了：' . $awardx . '&nbsp;&nbsp;';
                }
            }

            //积分购买机会重新定义
            if ($reply['opportunity'] == 2) {
                $reply['number_times'] += $fans['totalnum'];
            }

            //判断用户抽奖次数
            $nowtime = mktime(0, 0, 0);
            if ($fans['last_time'] < $nowtime) {
                // 每天重置已抽奖数
                $fans['todaynum'] = 0;

                //TODO 每天重置分享数
                $fans['sharenum'] = 0;
            }
            //判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
            if ($running && $reply['starttime'] > time()) {
                $running = false;
                $msg = '活动还没有开始呢！';
            }
            //判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
            if ($running && $reply['endtime'] < time()) {
                $running = false;
                $msg = '活动已经结束了，下次再来吧！';
            }
            //判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
            if ($running && $fans['totalnum'] >= $reply['number_times'] && $reply['number_times'] > 0) {
                $running = false;
                $msg = '您已经超过抽奖总限制次数，无法抽奖了!';
            }
            //判断当日是否超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
            /*
            if ($running && $fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
                $running = false;
                $msg = '您已经超过今天的抽奖次数，明天再来吧!';
            }
            */
            //TODO WKL
            if ($running && $fans['todaynum'] >= ($reply['most_num_times'] + $fans['sharenum']) && $reply['most_num_times'] > 0) {
                $running = false;
                $msg = '您已经超过今天的抽奖次数，明天再来吧!' .
                    "todaynum:" . $fans['todaynum'] . " = " . $reply['most_num_times'] . " + " . $fans['sharenum'];;
            }
        }

        if ($reply['turntable']) {
            $prize0_3 = pdo_fetchall("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` asc  limit 4", array(':rid' => $rid));
            $prize6_9 = pdo_fetchall("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` desc  limit 2,4", array(':rid' => $rid));
            $prize4 = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` asc  limit 4,1", array(':rid' => $rid));
            $prize5 = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` asc  limit 5,1", array(':rid' => $rid));
            $prize10 = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` asc  limit 10,1", array(':rid' => $rid));
            $prize11 = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where rid = :rid order by `id` asc  limit 11,1", array(':rid' => $rid));
        }
        //查询中奖总数以及可中奖总数 是否还有奖品
        $prizenum = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . " where uniacid='" . $uniacid . "' and rid= '" . $rid . "' and status>0");
        if ($prizenum >= $reply['total_num']) {
            //已没有奖品可发放了
            $running = false;
            $msg = '所有奖品都发放完了，无法抽奖了，下次早点来哟!';
        }
        //查询中奖总数以及可中奖总数 是否还有奖品
        if ($reply['most_num_times'] > 0 && $reply['number_times'] > 0) {
            $detail = '本次活动共可以转' . $reply['number_times'] . '次，每天可以转 ' . intval($reply['most_num_times']) . ' 次! 你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次 ，今天转了<span id="count">' . intval($fans['todaynum']) . '</span> 次.';

            $Tcount = $reply['most_num_times'];
            $Lcount = $reply['most_num_times'] - $fans['todaynum'];
        } elseif ($reply['most_num_times'] > 0) {
            // 不限总抽奖次数
            $detail = '本次活动每天可以转 ' . $reply['most_num_times'] . ' 次!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次 ，今天转了<span id="count">' . intval($fans['todaynum']) . '</span> 次.';
            $Tcount = $reply['most_num_times'];
            $Lcount = $reply['most_num_times'] - $fans['todaynum'];
        } elseif ($reply['number_times'] > 0) {
            $detail = '本次活动共可以转' . $reply['number_times'] . '次!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次。';
            $Tcount = $reply['number_times'];
            $Lcount = $reply['number_times'] - $fans['totalnum'];
        } else {
            $detail = '您很幸运，本次活动没有任何限制，您可以随意转!你共已经转了 <span id="totalcount">' . intval($fans['totalnum']) . '</span> 次。';
            $Tcount = 99999;
            $Lcount = 99999;
        }
        if (empty($reply['sn_rename'])) {
            $reply['sn_rename'] = 'SN码';
        }
        if (empty($reply['repeat_lottery_reply'])) {
            $reply['repeat_lottery_reply'] = '亲，继续努力哦！';
        }
        if (empty($fans['todaynum'])) {
            $fans['todaynum'] = 0;
        }
        if (empty($fans['totalnum'])) {
            $fans['totalnum'] = 0;
        }
        if (empty($fans['sharenum'])) {
            $fans['sharenum'] = 0;
        }
        //兑奖参数重命名
        $isfansname = explode(',', $reply['isfansname']);
        //兑奖参数重命名
        //分享信息
        $sharelink = $_W['siteroot'] . 'app/' . $this->createMobileUrl('share', array('rid' => $rid, 'from_user' => $page_from_user));
        $sharetitle = empty($share['share_title']) ? '欢迎参加猜涨跌活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加猜涨跌抽奖活动，祝您好运哦！！' : str_replace("\r\n", " ", $share['share_desc']);
        $sharetitle = $this->get_share($uniacid, $rid, $from_user, $sharetitle);
        $sharedesc = $this->get_share($uniacid, $rid, $from_user, $sharedesc);
        if (!empty($share['share_imgurl'])) {
            $shareimg = toimage($share['share_imgurl']);
        } else {
            $shareimg = toimage($reply['start_picurl']);
        }
        if (!$reply['turntable']) {
            include $this->template('index');
        } else {
            include $this->template('squares');
        }
    }

    public function doMobileawardinfo()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $from_user = $_W['fans']['from_user'];
        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = pdo_fetch("select title,adpic,adpicurl,award_info,turntable,show_num from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        //奖品
        $prize = pdo_fetchall("select prizetype,prizename,prizetotal,prizepic from " . tablename('kang_gold_prize') . " where rid = :rid and turntable=:turntable order by `id` asc", array(':rid' => $rid, ':turntable' => $reply['turntable']));
        include $this->template('awardinfo');
    }

    public function doMobileGift()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $reply = pdo_fetch("select title,adpic,adpicurl,opportunity,ticket_information from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        //获得关键词
        $keyword = pdo_fetch("select content from " . tablename('rule_keyword') . " where rid=:rid and type=1", array(":rid" => $rid));
        $reply['keyword'] = $keyword['content'];
        //获得关键词
        //已兑奖项
        $award = pdo_fetchall("select name,description from " . tablename('kang_gold_award') . " where uniacid='" . $_W['uniacid'] . "' and rid = '" . $rid . "' and fansID='" . $fansID . "' and status=2 group by name order by id desc");
        foreach ($award as $mid => $awards) {
            $award[$mid]['num'] = pdo_fetchcolumn("select COUNT(*) from " . tablename('kang_gold_award') . " where uniacid='" . $_W['uniacid'] . "' and rid = '" . $rid . "' and fansID='" . $fansID . "' and status=2 and name='" . $awards['name'] . "'");
        }
        //未兑奖项
        $awardw = pdo_fetchall("select name,description from " . tablename('kang_gold_award') . " where uniacid='" . $_W['uniacid'] . "' and rid = '" . $rid . "' and fansID='" . $fansID . "' and status=1 group by name order by id desc");
        foreach ($awardw as $mid => $awardws) {
            $awardw[$mid]['num'] = pdo_fetchcolumn("select COUNT(*) from " . tablename('kang_gold_award') . " where uniacid='" . $_W['uniacid'] . "' and rid = '" . $rid . "' and fansID='" . $fansID . "' and status=1 and name='" . $awardws['name'] . "'");
        }
        include $this->template('gift');
    }

    public function doMobileRule()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $from_user = $_W['fans']['from_user'];
        $page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
        if (empty($rid)) {
            message('抱歉，参数错误！', '', 'error');
        }
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        }
        $share = pdo_fetch("select share_txt,share_url from " . tablename('kang_gold_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid, ':acid' => $acid));
        $reply = pdo_fetch("select title,adpic,adpicurl from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if ($reply == false) {
            message('抱歉，活动已经结束，下次再来吧！', '', 'error');
        }
        //查询是否为关注用户
        $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $from_user, ":uniacid" => $uniacid));
        include $this->template('rule');
    }

    function Get_rand($proArr)
    {
        $result = '';
        //概率数组的总概率精度   
        $proSum = array_sum($proArr);
        //概率数组循环   
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    /**
     * 抽奖
     */
    public function doMobileget_award()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        //开始抽奖咯
        $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if ($reply == false) {
            $this->message(array("success" => 2, "msg" => '规则出错！...'), "");
        }

        if ($reply['isshow'] != 1) {
            //活动已经暂停,请稍后...
            $this->message(array("success" => 2, "msg" => '活动暂停，请稍后...'), "");
        }

        if ($reply['starttime'] > time()) {
            $this->message(array("success" => 2, "msg" => '活动还没有开始呢，请等待...'), "");
        }

        if ($reply['endtime'] < time()) {
            $this->message(array("success" => 2, "msg" => '活动已经结束了，下次再来吧！'), "");
        }

        if (empty($_W['fans'])) {
            $this->message(array("success" => 2, "msg" => '请先关注公共账号再来参与活动！详情请查看规则！'), "");
        }

        //先判断有没有资格领取
        $fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
        //判断是否为关注用户
        $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $from_user, ":uniacid" => $uniacid));
        if ($follow == 0) {
//			$this->message(array("success"=>2, "msg"=>'请先关注公共账号再来参与活动！详情请查看规则!'),"");


            //TODO WKL 未关注的中奖率为0
            /*
            $data = array(
                'msg' => '未关注',
                'success' => 2,
//                'height' => 180,
            );
            $this->message($data);
            */

            /*
           //301跳转
           if (!empty($share['share_url'])) {
               header("HTTP/1.1 301 Moved Permanently");
               header("Location: " . $share['share_url'] . "");
               exit();
           }
           */


            $acid = $_W['acid'];
            if (empty($acid)) {
                $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            }
            $share11 = pdo_fetch("select * from " . tablename('kang_gold_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid, ':acid' => $acid));

            $data = array(
                'name' => '未关注',
                'award' => '',
                'sn' => '',
                'success' => 2,
                'credit_now' => $share11['share_url'],
                'prizetype' => null,
                'isfans' => 0,
                'msg' => '未关注'
            );
            $this->message($data);
            exit;

        }


        //判断是否为关注用户
        //是否已关联用户，如果中能中奖一次，判断是否已中奖
        $fans = pdo_fetch("select * from " . tablename('kang_gold_fans') . " where rid = " . $rid . " and fansID=" . $fansID . " and from_user='" . $from_user . "'");
        if ($fans == false) {
            //不存在false的情况，如果是false，则表明是非法
            //$this->message();
            $fans = array(
                'rid' => $rid,
                'uniacid' => $uniacid,
                'fansID' => $fansID,
                'from_user' => $from_user,
                'todaynum' => 0,
                'totalnum' => 0,
                'awardnum' => 0,
                'createtime' => time(),
            );
            pdo_insert('kang_gold_fans', $fans);
            $fans['id'] = pdo_insertid();
            //增加人数，和浏览次数
            pdo_update('kang_gold_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
        } else {
            if ($fans['awardnum'] >= $reply['award_times'] && $reply['award_times'] != 0) {
                $this->message(array("success" => 2, "msg" => '您已中过大奖了，本活动仅限中奖' . $reply['award_times'] . '次，谢谢！'), "");
            }

            //TODO WKL 已经砸过1次，没有分享到朋友圈，点进去后提示分享
            if(intval($fans['todaynum']) == 1 && intval($fans['sharenum']) == 0){
//                if(intval($fans['todaynum']) == 1 && intval($fans['sharenum']) == 0){
                $data = array(
                    'name' => '还没有分享【'.$fans['todaynum'].'】',
                    'award' => '还没有分享',
                    'sn' => "",
                    'success' => 2,
                    'credit_now' => "",
//                            'prizetype' => null,
                    'prizetype' => 'noshare【'.$fans['todaynum'].'】',
                    'isfans' =>0,
                    'msg' => '还没有分享'
                );
                $this->message($data);
            }


            //增加浏览次数
            pdo_update('kang_gold_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
        }
        //获得用户资料
        $profile = mc_fetch($_W['member']['uid'], array('avatar', 'nickname', 'realname', 'mobile', 'qq', 'email', 'address', 'gender', 'telephone', 'idcard', 'company', 'occupation', 'position'));
        //查询是活动定义的次数还是商户赠送次数
        if ($reply['opportunity'] == 1) {
            if (empty($profile['mobile'])) {
                $this->message(array("success" => 2, "msg" => '您没有注册成为会员，不能抽奖!'), "");
            }
            $doings = pdo_fetch("select * from " . tablename('kang_branch_doings') . " where rid = " . $rid . " and mobile='" . $profile['mobile'] . "' and uniacid='" . $uniacid . "'");
            if (!empty($doings)) {
                if ($doings['status'] < 2) {
                    $this->message(array("success" => 2, "msg" => '抱歉，您的抽奖资格正在审核中!'), "");
                } else {
                    if ($doings['awardcount'] == 0) {
                        $this->message(array("success" => 2, "msg" => '抱歉，您的抽奖次数已用完了或没有获得抽奖次数!'), "");
                    } else {
                        $reply['number_times'] = $doings['awardcount'];
                    }
                }
            } else {
                $this->message(array("success" => 2, "msg" => '抱歉，您还没有获取抽奖资格，不能抽奖!'), "");
            }
        } elseif ($reply['opportunity'] == 2) {
            load()->model('account');
            $unisettings = uni_setting($uniacid, array('creditnames'));
            foreach ($unisettings['creditnames'] as $key => $credits) {
                if ($reply['credit_type'] == $key) {
                    $creditnames = $credits['title'];
                    break;
                }
            }
            $credit = mc_credit_fetch($fansID, array($reply['credit_type']));
            $credit_times = intval($credit[$reply['credit_type']] / $reply['credit_times']);
            if ($credit_times < 1) {
                $this->message(array("success" => 2, "msg" => '抱歉，您没有' . $creditnames . '兑换抽奖次数了，不能再抽奖!'), "");
            }
            if ($reply['number_times']) {
                if ($reply['number_times'] >= $credit_times) {
                    $reply['number_times'] = $credit_times;
                }
            } else {
                $reply['number_times'] = $credit_times;
            }
            $reply['number_times'] += $fans['totalnum'];
        }
        //查询是活动定义的次数还是商户赠送次数
        //查询分享赠送次数
        if ($share['sharenum'] > 0) {
            if ($share['sharetype']) {
                $sharenum = pdo_fetchcolumn("select count(id) as dd from " . tablename('kang_gold_data') . " where uniacid='" . $uniacid . "' and rid= '" . $rid . "' and fromuser='" . $from_user . "'");
                if ($share['sharenumtype'] == 2 && $reply['number_times'] > 0 && $sharenum > 0) {
                    $reply['number_times'] = $reply['number_times'] + $share['sharenum'];
                } elseif ($reply['number_times'] > 0) {
                    $reply['number_times'] = $reply['number_times'] + ($share['sharenum'] * $sharenum);
                }
            } elseif ($fans['sharenum'] > 0 && $reply['number_times'] > 0) {
                $reply['number_times'] = $reply['number_times'] + $share['sharenum'];
            }
        }

        /*--- 查询分享赠送次数 begin ---*/
        //更新当日次数
        $nowtime = mktime(0, 0, 0);
        if ($fans['last_time'] < $nowtime) {
            $fans['todaynum'] = 0;
            $fans['sharenum'] = 0;
        }

        //判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
        if ($fans['totalnum'] >= $reply['number_times'] && $reply['number_times'] > 0) {
            // $this->message('', '超过抽奖总限制次数');
            $this->message(array("success" => 2, "msg" => '您超过抽奖总次数了，不能抽奖了!'), "");
        }

        //判断当日是否超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
        /*
        if ($fans['todaynum'] >= $reply['most_num_times'] && $reply['most_num_times'] > 0) {
            //$this->message('', '超过当日限制次数');
             $this->message(array("success"=>2, "msg"=>'您超过当日抽奖次数了，不能抽奖了!'),"");
        }
        */
        //TODO WKL 修复分享后增加抽奖次数
        //TODO WKL 下一步增加选项：分享有效次数，比如每天分享只有一次有效
        if ($fans['todaynum'] >= ($reply['most_num_times'] + $fans['sharenum']) && $reply['most_num_times'] > 0) {
            //$this->message('', '超过当日限制次数');
//            $this->message(array("success" => 2, "msg" => '您超过当日抽奖次数了，不能抽奖了!'), "");

            $this->message(array("success" => 2, "msg" => '超过当日限制次数'), "");

        }
        /*--- 查询分享赠送次数 end ---*/


        //所有奖品
        $gift = pdo_fetchall("select * from " . tablename('kang_gold_prize') . " where rid = :rid and uniacid=:uniacid and turntable=:turntable order by Rand()", array(':rid' => $rid, ':uniacid' => $uniacid, ':turntable' => $reply['turntable']));
        //计算礼物中的最小概率
        $rate = 1;
        foreach ($gift as $giftxiao) {
            if ($giftxiao['probalilty'] < 1 && $giftxiao['probalilty'] > 0 && $giftxiao['prizetotal'] - $giftxiao['prizedraw'] >= 1) {
                $temp = explode('.', $giftxiao['probalilty']);
                $temp = pow(10, strlen($temp[1]));
                $rate = $temp < $rate ? $rate : $temp;
            }
        }
        //计算礼物中的最小概率
        $prize_arr = array();
        foreach ($gift as $row) {
            if ($row['prizetotal'] - $row['prizedraw'] >= 1 and floatval($row['prizepro']) > 0) {
                $item = array(
                    'id' => $row['id'],
                    'prize' => $row['prizetype'],
                    'v' => $row['prizepro'] * $rate,
                );
                $prize_arr[] = $item;
                $isgift = true;
            }
            $zprizepro += $row['prizepro'];
        }
        //所有奖品
        if ($isgift && ($fans['awardnum'] < $reply['award_times'] || $reply['award_times'] == 0)) {
            $last_time = strtotime(date("Y-m-d", mktime(0, 0, 0)));
            //当天抽奖次数
            pdo_update('kang_gold_fans', array('todaynum' => $fans['todaynum'] + 1, 'last_time' => $last_time), array('id' => $fans['id']));
            //总抽奖次数
            pdo_update('kang_gold_fans', array('totalnum' => $fans['totalnum'] + 1), array('id' => $fans['id']));
            //商家赠送增加使用次数
            if ($reply['opportunity'] == 1) {
                pdo_update('kang_branch_doings', array('usecount' => $doings['usecount'] + 1, 'usetime' => time()), array('id' => $doings['id']));
            } elseif ($reply['opportunity'] == 2) {
                mc_credit_update($fansID, $reply['credit_type'], -$reply['credit_times'], array($fansID, '兑换幸运猜涨跌活动消耗：' . $reply['credit_times'] . '个' . $creditnames));
                $credit_now = $credit[$reply['credit_type']] - $reply['credit_times'];
            }
            //传统猜涨跌增加未中奖奖项
            if (!empty($reply['lostDeg']) && $reply['turntable'] == 0 && (100 - $zprizepro) > 0) {
                $item = array(
                    'id' => 0,
                    'prize' => '好可惜！没有中奖',
                    'v' => (100 - $zprizepro) * $rate,
                );
                $prize_arr[] = $item;
            }
            //开始抽奖咯
            foreach ($prize_arr as $key => $val) {
                $arr[$val['id']] = $val['v'];
            }
            $prizetype = $this->get_rand($arr); //根据概率获取奖项id

            // 中奖
            if ($prizetype > 0) {

                $sn = random(16); // 兑奖码 //TODO WKL 做成指定规则的兑奖码
                $status = 1; // 兑奖状态
                $consumetime = '';
                //TODO WKL 增加实物奖品标记
                $isphysical = 1; // 实物奖品标记

                //查询奖品排名以及名称和类型
                $k = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_prize') . " where uniacid='" . $uniacid . "' and rid= '" . $rid . "' and id<='" . $prizetype . "' order by `id` asc");
                $awardinfo = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where  id='" . $prizetype . "'");
                if (!empty($awardinfo['prizepic'])) {
                    $awardinfo['prizepic'] = toimage($awardinfo['prizepic']);
                }
                // 更新中奖数量
                pdo_update('kang_gold_prize', array('prizedraw' => $awardinfo['prizedraw'] + 1), array('id' => $prizetype));

                // 虚拟奖品处理：自动兑奖
                //TODO WKL 虚拟奖品处理方式
                if ($awardinfo['credit_type'] != 'physical' && $awardinfo['credit_type'] != 'spaceprize') {
                    $unisetting_s = uni_setting($uniacid, array('creditnames'));
                    foreach ($unisetting_s['creditnames'] as $key => $credit) {
                        if ($awardinfo['credit_type'] == $key) {
                            $credit_names = $credit['title'];
                            break;
                        }
                    }
                    //添加积分到粉丝数据库
                    mc_credit_update($fansID, $awardinfo['credit_type'], $awardinfo['credit'], array($fansID, '幸运猜涨跌活动抽中' . $awardinfo['credit'] . '个' . $credit_names));
                    //添加积分到粉丝数据库
                    if ($reply['credit_type'] == $awardinfo['credit_type']) {
                        $credit_now += $awardinfo['credit'];
                    }
                    $status = 2;
                    $consumetime = time();
                    $isphysical = 0;
                }

                if ($awardinfo['credit_type'] != 'spaceprize') {

                    //TODO WKL 用户点击【领取奖品】输入信息时，
                    //TODO WKL 要把【中奖记录】和【领取信息】关联

                    //中奖记录保存
                    $insert = array(
                        'uniacid' => $uniacid,
                        'rid' => $rid,
                        'fansID' => $fansID,
                        'from_user' => $from_user,
                        'name' => $awardinfo['prizetype'],
                        'description' => $awardinfo['prizename'],
                        'prizetype' => $prizetype,
                        'prize' => $k,
                        'award_sn' => $sn,
                        'createtime' => time(),
                        'consumetime' => $consumetime,
                        'status' => $status,
                    );
                    $temp = pdo_insert('kang_gold_award', $insert);
                    $branchawardid = pdo_insertid();//取id商家赠送时保存
                    //中奖记录保存
                    //保存中奖人信息到fans中
                    pdo_update('kang_gold_fans', array('awardnum' => $fans['awardnum'] + 1, 'zhongjiang' => 1), array('id' => $fans['id']));
                    //保存中奖人信息到fans中
                }

                //自动读取会员信息存入FANS表中
                $isfansh = 180;
                $ziduan = array('realname', 'mobile', 'qq', 'email', 'address', 'gender', 'telephone', 'idcard', 'company', 'occupation', 'position');
                foreach ($ziduan as $ziduans) {
                    if ($reply['is' . $ziduans]) {
                        if (!empty($profile[$ziduans]) && empty($fans[$ziduans])) {
                            pdo_update('kang_gold_fans', array($ziduans => $profile[$ziduans]), array('id' => $fans['id']));
                        } else {
                            if (empty($fans[$ziduans])) {
                                $$ziduans = true;
                            }
                        }
                        $isfansh += 36;
                    }
                }
                if ($realname || $mobile || $qq || $email || $address || $gender || $telephone || $idcard || $company || $occupation || $position) {
                    $isfans = true;
                    $isfansh += 56;
                } else {
                    $isfansh = 180;
                }

                //自动读取会员信息存入FANS表中
                //商家赠送添加使用记录
                if ($reply['opportunity'] == 1) {
                    $content = '中奖SN:' . $sn . ';' . $awardinfo['prizetype'] . '[' . $awardinfo['prizename'] . ']';
                    $insert = array(
                        'uniacid' => $uniacid,
                        'rid' => $rid,
                        'module' => 'kang_gold',
                        'mobile' => $doings['mobile'],
                        'content' => $content,
                        'prizeid' => $branchawardid,
                        'createtime' => time()
                    );
                    pdo_insert('kang_branch_doingslist', $insert);
                }
                //商家赠送添加使用记录
                $data = array(
                    'award' => $awardinfo,       // 奖品信息
                    'sn' => $sn,                 // 兑奖码
                    'success' => 1,              // 正常抽奖
                    'height' => $isfansh,        // 粉丝兑奖窗口高度
                    'credit_now' => $credit_now, // 赠送积分
                    'prizetype' => $k,           // 奖项
                    'isfans' => $isfans,         // 粉丝标识
                    'isphysical' => $isphysical, // 实物奖品标识
                    'msg' == '中奖了'
                );
            }

            // 未中奖
            if ($awardinfo['credit_type'] == 'spaceprize' || $prizetype <= 0) {
                //未中奖赠送积分
                //是否赠送积分
                if ($reply['credit1'] > 0 || $reply['credit2'] > 0) {
                    load()->model('account');
                    $unisettings = uni_setting($uniacid, array('creditnames'));
                    foreach ($unisettings['creditnames'] as $key => $credit) {
                        if ($reply['credittype'] == $key) {
                            $creditnames = $credit['title'];
                            break;
                        }
                    }
                    $credit = mt_rand($reply['credit1'], $reply['credit2']);
                    if ($reply['credit_type'] == $reply['credittype']) {
                        $credit_now = $credit_now + $credit;
                    }
                    //添加积分到粉丝数据库
                    load()->model('mc');
                    mc_credit_update($fansID, $reply['credittype'], $credit, array($fansID, '幸运猜涨跌活动赠送' . $credit . '个' . $creditnames));
                    //添加积分到粉丝数据库
                }

                if ($reply['turntable'] == 0) {

                    // 猜涨跌

                    //TODO WKL 没有幸运奖，送余额的方式
                    /*
					$data = array(
               	        'name' => '幸运送'.$creditnames,
                	    'award' => $creditnames,
                	    'sn' => $credit,
                	    'success' => 1,
					    'credit_now' => $credit_now,
                	    'prizetype' =>0,
					    'isfans' =>0,
           	        );
					$this->message($data);
                    */

                    if($fans['todaynum'] == 0 || $fans['todaynum'] == '0'){
                        $data = array(
                            'name' => '第一次抽奖不中',
                            'award' => '再接再厉',
                            'sn' => $credit,
                            'success' => 1,
                            'credit_now' => $credit_now,
//                            'prizetype' => null,
                            'prizetype' => 'first',
                            'isfans' =>0,
                            'msg' => '第一次抽奖不中'
                        );
                        $this->message($data);
                    }else if($fans['todaynum'] == 1 || $fans['todaynum'] == '1'){
                        $data = array(
                            'name' => '第二次抽奖不中',
                            'award' => '再接再厉',
                            'sn' => $credit,
                            'success' => 1,
                            'credit_now' => $credit_now,
//                            'prizetype' => null,
                            'prizetype' => 'second',
                            'isfans' =>0,
                            'msg' => '第二次抽奖不中'
                        );
                        $this->message($data);
                    }else{
                        $data = array(
                            'name' => "不中奖",
                            'award' => '再接再厉',
                            'sn' => $credit,
                            'success' => 1,
                            'credit_now' => $credit_now,
//                            'prizetype' => null,
                            'prizetype' => 'fail',
                            'isfans' =>0,
                            'msg' => '多次不中奖'
                        );
                        $this->message($data);
                    }
                } elseif ($awardinfo['credit_type'] == 'spaceprize') {
                    $data['isfans'] = 0;
                    $data['height'] = 180;
                    $data['sn'] = $credit . '个' . $creditnames;
                    $data['credit_now'] = $credit_now;
                }
            }

            $this->message($data);
        } else {
            //没有奖品或没有资格的提示
            $data = array(
                'msg' => '没有奖品了，好遗憾，下次早点来吧！',
                'success' => 2,
                'height' => 180,
            );
            $this->message($data);
        }
        $this->message();
    }

    /**
     * 分享设置
     */
    public function doMobileGet_share()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
        //判断是否为关注用户
        $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(":openid" => $from_user, ":uniacid" => $uniacid));
        if ($follow == 0) {
            $data = array(
                'msg' => '您还没有关注公众号[' . $_W['account']['childname'] . ']，即使分享成功了！' . $_W['account']['childname'] . '没有办法为您赠送抽奖机会！请先关注公众号[' . $_W['account']['childname'] . ']再来分享，谢谢！',
                'success' => 2,
            );
        }
        //判断是否为关注用户
        $fans = pdo_fetch("select * from " . tablename('kang_gold_fans') . " where rid = " . $rid . " and from_user='" . $from_user . "'");
        if ($fans == false) {
            //数据出错
            $data = array(
                'msg' => '您还没有参与本活动，即使分享成功了！[' . $_W['account']['childname'] . ']也没有办法为您赠送抽奖机会！请先参与些活动再来分享，谢谢！',
                'success' => 2,
            );
        } else {


            // 判断是否超出【每人每天分享有效次数】
            $acid = $_W['acid'];
            if (empty($acid)) {
                $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            }
            $share = pdo_fetch("select * from " . tablename('kang_gold_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid, ':acid' => $acid));

            // 分享次数不限
            if($share["most_share_times"] == 0){
                //保存分享次数
                pdo_update('kang_gold_fans', array('sharenum' => $fans['sharenum'] + 1, 'sharetime' => time()), array('id' => $fans['id']));
                $data = array(
                    'msg' => '分享次数保存成功！',
                    'success' => 1,
                );
            }else{

                // 判断分享有效次数
                if($fans['sharenum'] < ($share["most_share_times"] )){

                    //保存分享次数
                    pdo_update('kang_gold_fans', array('sharenum' => $fans['sharenum'] + 1, 'sharetime' => time()), array('id' => $fans['id']));
                    $data = array(
                        'msg' => '分享次数保存成功！',
                        'success' => 1,
                    );
                }else{

                    // 超过分享有效次数
                    $data = array(
                        'msg' => '今天不能增加抽奖机会了',
                        'success' => 2,
                    );
                }
            }

            /*
            //保存分享次数
            pdo_update('kang_gold_fans', array('sharenum' => $fans['sharenum'] + 1, 'sharetime' => time()), array('id' => $fans['id']));
            $data = array(
                'msg' => '分享次数保存成功！',
                'success' => 1,
            );
            */

        }
        $this->message($data);
    }

    /**
     * 保存中奖信息
     */
    public function doMobilesettel()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fansID = $_W['member']['uid'];
        $from_user = $_W['fans']['from_user'];
        $fans = pdo_fetch("select id from " . tablename('kang_gold_fans') . " where rid = " . $rid . " and fansID=" . $fansID . " and from_user='" . $from_user . "'");
        if ($fans == false) {
            $data = array(
                'success' => 0,
                'msg' => '保存数据错误！',
            );
        } else {
            //查询规则保存哪些数据
            $updata = array();
            $reply = pdo_fetch("select isfans,isrealname,ismobile,isqq,isemail,isaddress,isgender,istelephone,isidcard,iscompany,isoccupation,isposition from " . tablename('kang_gold_reply') . " where rid = :rid", array(':rid' => $rid));
            if ($reply['isrealname']) {
                $updata['realname'] = $_GPC['realname'];
            }
            if ($reply['ismobile']) {
                $updata['mobile'] = $_GPC['mobile'];
            }
            if ($reply['isqq']) {
                $updata['qq'] = $_GPC['qq'];
            }
            if ($reply['isemail']) {
                $updata['email'] = $_GPC['email'];
            }
            if ($reply['isaddress']) {
                $updata['address'] = $_GPC['address'];
            }
            if ($reply['isgender']) {
                $updata['gender'] = $_GPC['gender'];
            }
            if ($reply['istelephone']) {
                $updata['telephone'] = $_GPC['telephone'];
            }
            if ($reply['isidcard']) {
                $updata['idcard'] = $_GPC['idcard'];
            }
            if ($reply['iscompany']) {
                $updata['company'] = $_GPC['company'];
            }
            if ($reply['isoccupation']) {
                $updata['occupation'] = $_GPC['occupation'];
            }
            if ($reply['isposition']) {
                $updata['position'] = $_GPC['position'];
            }
            $temp = pdo_update('kang_gold_fans', $updata, array('rid' => $rid, 'fansID' => $fansID));

            if ($temp === false) {
                $data = array(
                    'success' => 0,
                    'msg' => '保存数据错误！',
                );
            } else {
                if ($reply['isfans']) {
                    load()->model('mc');
                    mc_update($fansID, $updata);
                }
/*
                $awardnum = pdo_fetchcolumn("select COUNT(*) from " . tablename('kang_gold_award') . " where uniacid='" . $_W['uniacid'] . "' and rid = '" . $rid . "' and fansID='" . $fansID . "'  '");
                // 中奖超过1次
                $isfirst = intval($awardnum) > 1 ? 1 : 0;
*/
                $isfirst = 1;

                $data = array(
                    'success' => 1,
                    'msg' => '成功提交数据',
                    'isfirst' => $isfirst
                );
            }
        }
        echo json_encode($data);
    }

    public function doMobileduijiang()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $fansID = $_GPC['fansID'];
        $bid = $_GPC['bid'];
        $password = $_GPC['password'];
        $fans = pdo_fetch("select id,mobile from " . tablename('kang_gold_fans') . " where rid = " . $rid . " and fansID=" . $fansID . "");
        if ($fans == false) {
            $data = array(
                'success' => 0,
                'msg' => '兑奖数据错误！',
            );
        } else {
            //查询密码是否正确
            if (!empty($bid)) {
                $business = pdo_fetch("select id from " . tablename('kang_branch_business') . " where uniacid = '" . $_W['uniacid'] . "' and password = '" . $password . "' and id='" . $bid . "'");
            } else {
                $business = pdo_fetch("select id from " . tablename('kang_branch_business') . " where uniacid = '" . $_W['uniacid'] . "' and password = '" . $password . "'");
            }
            if (!empty($business)) {
                $temp = pdo_update('kang_gold_award', array('status' => 2), array('rid' => $rid, 'fansID' => $fansID, 'uniacid' => $_W['uniacid']));
                if ($temp === false) {
                    $data = array(
                        'success' => 0,
                        'msg' => '兑奖数据保存错误！',
                    );
                } else {
                    pdo_update('kang_gold_fans', array('zhongjiang' => 2), array('id' => $fans['id']));
                    $data = array(
                        'success' => 1,
                        'msg' => '兑奖成功！',
                    );
                    //添加兑奖记录
                    $content = '兑奖成功';
                    $insert = array(
                        'uniacid' => $_W['uniacid'],
                        'rid' => $rid,
                        'module' => 'kang_gold',
                        'fansID' => $fansID,
                        'mobile' => $fans['mobile'],
                        'bid' => $business['id'],
                        'content' => $content,
                        'createtime' => time()
                    );
                    pdo_insert('kang_branch_duijiang', $insert);
                    //添加兑奖记录
                }
            } else {
                $data = array(
                    'success' => 0,
                    'msg' => '密码不正确，请重新输入',
                );
            }
        }
        echo json_encode($data);
    }

    //json
    public function message($_data = '', $_msg = '')
    {
        if (!empty($_data['success']) && $_data['success'] != 2) {
            $this->setfans();
        }
        if (empty($_data)) {
            $_data = array(
                'name' => "谢谢参与",
                'success' => 0,
            );
        }
        if (!empty($_msg)) {
            //$_data['error']='invalid';
            $_data['msg'] = $_msg;
        }
        die(json_encode($_data));
    }

    public function setfans()
    {
        global $_GPC, $_W;
        //增加fans次数
        //记录用户信息
        $id = intval($_GPC['id']);
        $fansID = $_W['fans']['id'];
        if (empty($fansID) || empty($id))
            return;
        $fans = pdo_fetch("select * from " . tablename('kang_gold_fans') . " where rid = " . $id . " and fansID=" . $fansID . "");
        $nowtime = mktime(0, 0, 0);
        if ($fans['last_time'] < $nowtime) {
            $fans['todaynum'] = 0;
        }
        $update = array(
            'todaynum' => $fans['todaynum'] + 1,
            'totalnum' => $fans['totalnum'] + 1,
            'last_time' => time(),
        );
        pdo_update('kang_gold_fans', $update, array('id' => $fans['id']));
    }

    public function doWebManage()
    {
        global $_GPC, $_W;
        //查询是否有商户网点权限
        //$modules = uni_modules($enabledOnly = true);
        //$modules_arr = array();
        //$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        //if(in_array('kang_branch',$modules_arr)){
        $kang_branch = true;
        //}
        //查询是否有商户网点权限
        load()->model('reply');
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid and `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'kang_gold';

        if (!empty($_GPC['keyword'])) {
            $sql .= ' and `name` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keyword'] = reply_keywords_search($condition);
                $bigwheel = pdo_fetch("select fansnum, viewnum,starttime,endtime,isshow from " . tablename('kang_gold_reply') . " where rid = :rid ", array(':rid' => $item['id']));
                $item['fansnum'] = $bigwheel['fansnum'];
                $item['viewnum'] = $bigwheel['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $bigwheel['starttime']);
                $endtime = $bigwheel['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($bigwheel['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($bigwheel['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $bigwheel['isshow'];
            }
        }
        include $this->template('manage');
    }

    public function doWebFanslist()
    {
        global $_GPC, $_W;
        $rid = $_GPC['rid'];
        //查询是否有商户网点权限
        //$modules = uni_modules($enabledOnly = true);
        //$modules_arr = array();
        //$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        //if(in_array('kang_branch',$modules_arr)){
        $kang_branch = true;
        //}
        //查询是否有商户网点权限
        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
        if (!empty($_GPC['realname'])) {
            $where .= ' and realname=:realname';
            $params[':realname'] = $_GPC['realname'];
        }
        if (!empty($_GPC['mobile'])) {
            $where .= ' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
        //导出标题以及参数设置
        if ($_GPC['status'] == '') {
            $statustitle = '全部';
        }
        if ($_GPC['status'] == 1) {
            $statustitle = '已中奖';
            $where .= ' and zhongjiang>=1';
        }
        if ($_GPC['status'] == 2) {
            $statustitle = '已兑换';
            $where .= ' and zhongjiang=2';
        }
        if ($_GPC['status'] == 3) {
            $statustitle = '未兑换';
            $where .= ' and zhongjiang=1';
        }
        if ($_GPC['status'] == 4) {
            $statustitle = '未中奖';
            $where .= ' and zhongjiang=0';
        }
        if ($_GPC['status'] == 5) {
            $statustitle = '虚拟奖';
            $where .= ' and xuni=1';
        }
        //导出标题以及参数设置
        $total = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('kang_gold_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
        //中奖情况
        foreach ($list as &$lists) {
            $lists['awardinfo'] = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . "  where rid = :rid and fansID=:fansID", array(':rid' => $rid, ':fansID' => $lists['fansID']));
            $lists['share_num'] = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_data') . "  where rid = :rid and fromuser=:fromuser", array(':rid' => $rid, ':fromuser' => $lists['from_user']));
        }
        //中奖情况
        //一些参数的显示
        $num1 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num2 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang>0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num3 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num4 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=2", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num5 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid and zhongjiang=0", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $num6 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_fans') . "  where rid = :rid and uniacid=:uniacid and xuni=1", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        //一些参数的显示
        include $this->template('fanslist');
    }

    public function doWebBranch()
    {
        global $_GPC, $_W;
        //查询是否有商户网点权限
        //$modules = uni_modules($enabledOnly = true);
        //$modules_arr = array();
        //$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        //if(in_array('kang_branch',$modules_arr)){
        $kang_branch = true;
        //}
        //查询是否有商户网点权限
        $rid = $_GPC['rid'];
        //选择商家
        $district = pdo_fetchall("select * from " . tablename('kang_branch_district') . " where uniacid = '{$_W['uniacid']}' order by orderid desc, id desc", array(), 'id');
        $items = pdo_fetchall("select id,title,districtid from " . tablename('kang_branch_business') . " where uniacid = '{$_W['uniacid']}' order by id desc", array(), 'id');
        if (!empty($items)) {
            $business = '';
            foreach ($items as $cid => $cate) {
                $business[$cate['districtid']][$cate['id']] = array($cate['id'], $cate['title']);
            }
        }
        //选择商家
        $params = array(':module' => 'kang_gold', ':rid' => $rid, ':uniacid' => $_W['uniacid']);
        if (!empty($_GPC['mobile'])) {
            $where .= ' and mobile=:mobile';
            $params[':mobile'] = $_GPC['mobile'];
        }
        if (!empty($_GPC['districtid'])) {
            $where .= ' and districtid=:districtid';
            $params[':districtid'] = $_GPC['districtid'];
        } elseif (!empty($_GPC['pcate'])) {
            $districts = pdo_fetchall("select id from " . tablename('kang_branch_business') . "  where districtid=:districtid and  uniacid=:uniacid order by id desc", array('districtid' => $_GPC['pcate'], 'uniacid' => $_W['uniacid']), 'districtid');
            $districtid = '';
            foreach ($districts as $districtss) {
                $districtid .= $districtss['id'] . ',';
            }
            $districtid = substr($districtid, 0, strlen($districtid) - 1);
            $where .= ' and districtid in(:districtid)';
            $params[':districtid'] = $districtid;
        }
        $total = pdo_fetchcolumn("select count(id) from " . tablename('kang_branch_doings') . "  where module=:module and rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('kang_branch_doings') . " where module=:module and rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
        //查询商家
        foreach ($list as &$lists) {
            $lists['shangjia'] = pdo_fetchcolumn("select title from " . tablename('kang_branch_business') . "  where id = :id", array(':id' => $lists['districtid']));
        }
        //查询商家
        include $this->template('branch');
    }

    public function doWebSetcheck()
    {

        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);

        if (in_array($type, array('status'))) {
            $data = ($data == 2 ? '1' : '2');
            pdo_update("kang_branch_doings", array("status" => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            die(json_encode(array("result" => 1, "data" => $data)));
        }
        die(json_encode(array("result" => 0)));

    }

    public function doWebImporting()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $rid = intval($_GPC['rid']);
            //选择商家
            $district = pdo_fetchall("select * from " . tablename('kang_branch_district') . " where uniacid = '{$_W['uniacid']}' order by orderid desc, id desc", array(), 'id');
            $items = pdo_fetchall("select id,title,districtid from " . tablename('kang_branch_business') . " where uniacid = '{$_W['uniacid']}' order by id desc", array(), 'id');
            if (!empty($items)) {
                $business = '';
                foreach ($items as $cid => $cate) {
                    $business[$cate['districtid']][$cate['id']] = array($cate['id'], $cate['title']);
                }
            }
            //选择商家
            include $this->template('importing');
            exit();
        }

    }

    public function doWebImportingsave()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $districtid = intval($_GPC['districtid']);
        if (!$rid) {
            message('系统出错', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
            exit;
        }
        if (empty($_FILES["inputExcel"]["tmp_name"])) {
            message('系统出错', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
            exit;
        }
        $inputFileName = '../addons/kang_gold/template/moban/excel/' . $_FILES["inputExcel"]["name"];
        if (file_exists($inputFileName)) {
            unlink($inputFileName);    //如果服务器上存在同名文件，则删除
        }
        move_uploaded_file($_FILES["inputExcel"]["tmp_name"], $inputFileName);
        require_once '../framework/library/phpexcel/PHPExcel.php';
        require_once '../framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once '../framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
        //设置php服务器可用内存，上传较大文件时可能会用到
        ini_set('memory_limit', '1024M');
        $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();           //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();

        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexfromString($highestColumn);//总列数

        $headtitle = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            $strs = array();
            //注意highestColumnIndex的列数索引从0开始
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $strs[$col] = $objWorksheet->getCellByColumnandRow($col, $row)->getValue();
            }
            //查询是否规定了区域商点
            if (!empty($districtid)) {
                $strs[2] = $districtid;
            }
            //查询是否规定了区域商点
            //插入数据
            $chongfu = pdo_fetch("select id from " . tablename('kang_branch_doings') . " where mobile =:mobile and uniacid=:uniacid and districtid=:districtid", array(':mobile' => $strs[0], ':uniacid' => $_W['uniacid'], ':districtid' => $strs[2]));
            $data = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'module' => 'kang_gold',
                'mobile' => $strs[0],
                'awardcount' => $strs[1],
                'districtid' => $strs[2],
                'status' => 2,
                'createtime' => time()
            );
            if (!empty($chongfu)) {
                pdo_update('kang_branch_doings', $data, array('id' => $chongfu['id']));
            } else {
                pdo_insert('kang_branch_doings', $data);
            }
        }
        unlink($inputFileName); //删除上传的excel文件
        message('导入抽奖次数成功', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')));
        exit;
    }

    public function doWebEditbranch()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            $data = pdo_fetch("select * from " . tablename('kang_branch_doings') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            include $this->template('editbranch');
            exit();
        }

    }

    public function doWebEditbranchsave()
    {
        global $_GPC, $_W;
        $uid = intval($_GPC['uid']);
        $rid = intval($_GPC['rid']);
        $usecount = intval($_GPC['usecount']);
        $awardcount = intval($_GPC['awardcount']);
        $status = intval($_GPC['status']);
        if ($usecount > $awardcount) {
            message('修改后的次数少于已使用的次数', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
        if (!$rid) {
            message('系统出错', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
        if ($uid) {
            //抽奖次数
            pdo_update('kang_branch_doings', array('awardcount' => $awardcount, 'status' => $status), array('id' => $uid));
            message('修改抽奖次数成功', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')));
        } else {
            message('未找到指定用户', url('site/entry/branch', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
    }

    public function doWebAddaward()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            //规则
            $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            //粉丝数据
            $data = pdo_fetch("select id, fansID, realname, mobile, uniacid  from " . tablename('kang_gold_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            //奖品数据
            $awardlist = pdo_fetchall("select * from " . tablename('kang_gold_prize') . ' where rid = :rid and uniacid = :uniacid and turntable=:turntable order by id asc', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':turntable' => $reply['turntable']));
            //判断是否还有奖品
            foreach ($awardlist as &$lists) {
                if ($lists['prizetotal'] > $lists['prizedraw']) {
                    $award = true;
                    break; //直接跳出不再继续循环
                }
            }
            include $this->template('xuniaward');
            exit();
        }

    }

    public function doWebAwardfrom()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            //粉丝数据
            $data = pdo_fetch("select id, fansID, realname, mobile, uniacid  from " . tablename('kang_gold_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            $list = pdo_fetchall("select * from " . tablename('kang_gold_award') . "  where rid = :rid and uniacid=:uniacid and fansID=:fansID order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':fansID' => $data['fansID']));
            include $this->template('awardfrom');
            exit();
        }
    }

    public function doWebUserinfo()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            $fansID = intval($_GPC['fansID']);
            //兑奖资料
            $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            $isfansname = explode(',', $reply['isfansname']);
            //粉丝数据
            if ($fansID) {
                $data = pdo_fetch("select * from " . tablename('kang_gold_fans') . ' where fansID = :fansID and uniacid = :uniacid and rid = :rid', array(':uniacid' => $_W['uniacid'], ':fansID' => $fansID, ':rid' => $rid));
            } else {
                $data = pdo_fetch("select * from " . tablename('kang_gold_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            }

            include $this->template('userinfo');
            exit();
        }
    }

    public function doWebSharelist()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            //粉丝数据
            $data = pdo_fetch("select id, fansID, realname, mobile, from_user  from " . tablename('kang_gold_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            $share = pdo_fetchall("select * from " . tablename('kang_gold_data') . "  where rid = :rid and uniacid=:uniacid and fromuser=:fromuser order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':fromuser' => $data['from_user']));
            include $this->template('sharelist');
            exit();
        }
    }

    public function doWebUseinfo()
    {
        global $_GPC, $_W;
        if ($_W['isajax']) {
            $uid = intval($_GPC['uid']);
            $rid = intval($_GPC['rid']);
            //粉丝数据
            $data = pdo_fetch("select id, districtid, mobile, awardcount, usecount  from " . tablename('kang_branch_doings') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            //商家信息
            $data['shangjiang'] = pdo_fetchcolumn("select title from " . tablename('kang_branch_business') . "  where id = :id", array(':id' => $data['districtid']));
            $list = pdo_fetchall("select * from " . tablename('kang_branch_doingslist') . "  where rid = :rid and uniacid=:uniacid and mobile=:mobile order by id desc ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':mobile' => $data['mobile']));
            include $this->template('useinfo');
            exit();
        }

    }

    public function doWebAddawardsave()
    {
        global $_GPC, $_W;
        $uid = intval($_GPC['uid']);
        $rid = intval($_GPC['rid']);
        $awardid = intval($_GPC['awardid']);
        if (!$awardid) {
            message('必需选择奖品才能生效', url('site/entry/fanslist', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
        if (!$rid) {
            message('系统出错', url('site/entry/fanslist', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
        if ($uid) {
            //规则
            $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            //粉丝数据
            $data = pdo_fetch("select id, fansID, from_user, awardnum  from " . tablename('kang_gold_fans') . ' where id = :id and uniacid = :uniacid', array(':uniacid' => $_W['uniacid'], ':id' => $uid));
            //添加中奖记录
            $sn = random(16);
            $award = pdo_fetch("select * from " . tablename('kang_gold_prize') . "  where id=:id", array(':id' => $awardid));
            pdo_update('kang_gold_prize', array('prizedraw' => $award['prizedraw'] + 1), array('id' => $awardid));
            $k = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_prize') . " where uniacid='" . $_W['uniacid'] . "' and rid= '" . $rid . "' and id<='" . $awardid . "' order by `id` asc");
            //保存sn到award中
            $insert = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'fansID' => $data['fansID'],
                'from_user' => $data['from_user'],
                'name' => $award['prizetype'],
                'description' => $award['prizename'],
                'prizetype' => $awardid,
                'prize' => $k,
                'award_sn' => $sn,
                'createtime' => time(),
                'status' => 1,
                'xuni' => 1
            );
            if ($award['credit_type'] != 'physical') {
                $insert['consumetime'] = time();
                $insert['status'] = 2;
            }
            $temp = pdo_insert('kang_gold_award', $insert);
            //保存中奖人信息到fans中
            pdo_update('kang_gold_fans', array('awardnum' => $data['awardnum'] + 1, 'zhongjiang' => 1, 'xuni' => 1), array('id' => $data['id']));
            message('添加虚拟中奖成功', url('site/entry/fanslist', array('rid' => $rid, 'm' => 'kang_gold')));
        } else {
            message('未找到指定用户', url('site/entry/fanslist', array('rid' => $rid, 'm' => 'kang_gold')), 'error');
        }
    }

    public function doWebDelete()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
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
        message('活动删除成功！', referer(), 'success');
    }

    public function doWebDeleteAll()
    {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
                $this->webmessage('抱歉，要修改的规则不存在或是已经被删除！');
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
        $this->webmessage('选择中的活动删除成功！', '', 0);
    }

    public function doWebDeletefans()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select id, fansnum from " . tablename('kang_gold_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
            $fans = pdo_fetch("select fansID from " . tablename('kang_gold_fans') . " where id = :id", array(':id' => $id));
            if (empty($fans)) {
                $this->webmessage('抱歉，选中的粉丝数据不存在！');
            }
            //删除粉丝中奖记录
            $fansaward = pdo_fetchall("select id,prizetype from " . tablename('kang_gold_award') . " where rid = :rid and uniacid=:uniacid and fansID=:fansID", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $fans['fansID']));
            foreach ($fansaward as $fansawards) {
                $prize = pdo_fetch("select id,prizedraw from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $fansawards['prizetype']));
                pdo_update('kang_gold_prize', array('prizedraw' => $prize['prizedraw'] - 1), array('id' => $fansawards['prizetype']));
                //查询奖品是否为虚拟积分，如果是则扣除相应的积分

                //查询奖品是否为虚拟积分，如果是则扣除相应的积分
                pdo_delete('kang_gold_award', array('id' => $fansawards['id']));
            }
            //删除粉丝中奖记录
            //删除粉丝参与记录
            pdo_delete('kang_gold_fans', array('id' => $id));
            //删除粉丝参与记录
            //减少参与记录
            $reply['fansnum'] = $reply['fansnum'] - 1;
            pdo_update('kang_gold_reply', array('fansnum' => $reply['fansnum']), array('id' => $reply['id']));
            //减少参与记录
        }
        $this->webmessage('粉丝记录删除成功！', '', 0);
    }

    public function doWebDeleteaward()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select id, fansnum from " . tablename('kang_gold_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
            //删除奖品第一步先恢复到奖池中
            $award = pdo_fetch("select id,prizetype,fansID from " . tablename('kang_gold_award') . " where id = :id", array(':id' => $id));
            $prize = pdo_fetch("select id,prizedraw from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $award['prizetype']));
            pdo_update('kang_gold_prize', array('prizedraw' => $prize['prizedraw'] - 1), array('id' => $award['prizetype']));
            //删除奖品第一步先恢复到奖池中
            //查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
            $fansaward = pdo_fetch("select id from " . tablename('kang_gold_award') . " where rid = :rid and fansID = :fansID and uniacid=:uniacid and id!=:id", array(':rid' => $rid, ':fansID' => $award['fansID'], ':uniacid' => $_W['uniacid'], ':id' => $id));
            if (empty($fansaward)) {
                pdo_update('kang_gold_fans', array('zhongjiang' => 0), array('fansID' => $award['fansID'], 'uniacid' => $_W['uniacid'], 'rid' => $rid));
            } else {
                $awardnum = pdo_fetchcolumn("select count(*) from " . tablename('kang_gold_award') . " where rid = :rid and uniacid=:uniacid and fansID=:fansID and id!=:id", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $award['fansID'], ':id' => $id));
                pdo_update('kang_gold_fans', array('awardnum' => $awardnum, 'zhongjiang' => 1), array('fansID' => $award['fansID'], 'uniacid' => $_W['uniacid'], 'rid' => $rid));
            }
            //查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
            //查询奖品是否为虚拟积分，如果是则扣除相应的积分

            //查询奖品是否为虚拟积分，如果是则扣除相应的积分
            //删除粉丝中奖记录
            pdo_delete('kang_gold_award', array('id' => $id));
            //删除粉丝中奖记录
        }
        $this->webmessage('粉丝中奖记录删除成功！', '', 0);
    }

    public function doWebDeletebranch()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $reply = pdo_fetch("select id, fansnum from " . tablename('kang_gold_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($reply)) {
            $this->webmessage('抱歉，要修改的活动不存在或是已经被删除！');
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if ($id == 0)
                continue;
            //删除使用记录
            $doings = pdo_fetch("select * from " . tablename('kang_branch_doings') . " where id = :id", array(':id' => $id));
            $doingslist = pdo_fetchall("select * from " . tablename('kang_branch_doingslist') . " where rid = :rid and uniacid=:uniacid and module=:module and mobile=:mobile", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':module' => $doings['module'], ':mobile' => $doings['mobile']));
            foreach ($doingslist as $doingslists) {
                //删除中奖记录
                //删除奖品第一步先恢复到奖池中
                $award = pdo_fetch("select id,prizetype,fansID from " . tablename('kang_gold_award') . " where id = :id", array(':id' => $doingslists['prizeid']));
                $prize = pdo_fetch("select id,prizedraw from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $award['prizetype']));
                pdo_update('kang_gold_prize', array('prizedraw' => $prize['prizedraw'] - 1), array('id' => $award['prizetype']));
                //删除奖品第一步先恢复到奖池中
                //查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
                $fansaward = pdo_fetch("select id from " . tablename('kang_gold_award') . " where rid = :rid and fansID = :fansID and uniacid=:uniacid and id!=:id", array(':rid' => $rid, ':fansID' => $award['fansID'], ':uniacid' => $_W['uniacid'], ':id' => $doingslists['prizeid']));
                if (empty($fansaward)) {
                    pdo_update('kang_gold_fans', array('zhongjiang' => 0), array('fansID' => $award['fansID'], 'uniacid' => $_W['uniacid'], 'rid' => $rid));
                } else {
                    $awardnum = pdo_fetchcolumn("select count(*) from " . tablename('kang_gold_award') . " where rid = :rid and uniacid=:uniacid and fansID=:fansID and id!=:id", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $award['fansID'], ':id' => $doingslists['prizeid']));
                    pdo_update('kang_gold_fans', array('awardnum' => $awardnum, 'zhongjiang' => 1), array('fansID' => $award['fansID'], 'uniacid' => $_W['uniacid'], 'rid' => $rid));
                }
                //查询粉丝是否还有中奖记录，没有则需要改变粉丝状态
                //查询奖品是否为虚拟积分，如果是则扣除相应的积分

                //查询奖品是否为虚拟积分，如果是则扣除相应的积分
                //删除粉丝中奖记录
                pdo_delete('kang_gold_award', array('id' => $doingslists['prizeid']));
                //删除粉丝中奖记录
                //删除中奖记录
                pdo_delete('kang_branch_doingslist', array('id' => $doingslists['id']));
            }
            //删除使用记录
            //删除赠送记录
            pdo_delete('kang_branch_doings', array('id' => $id));
            //删除赠送记录
        }
        $this->webmessage('商家赠送记录删除成功！', '', 0);
    }

    public function doWebAwardlist()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        //查询是否有商户网点权限
        //$modules = uni_modules($enabledOnly = true);
        //$modules_arr = array();
        //$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        //if(in_array('kang_branch',$modules_arr)){
        $kang_branch = true;
        //}
        //查询是否有商户网点权限
        //所有奖品类别
        $reply = pdo_fetch("select turntable from " . tablename('kang_gold_reply') . " where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        $award = pdo_fetchall("select * from " . tablename('kang_gold_prize') . " where rid = :rid and uniacid=:uniacid and turntable=:turntable order by `id` asc", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':turntable' => $reply['turntable']));
        foreach ($award as $k => $awards) {
            $award[$k]['num'] = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . " where rid = :rid and uniacid=:uniacid and prizetype='" . $awards['id'] . "'", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        }
        //所有奖品类别
        //导出标题
        if ($_GPC['status'] == 0) {
            $statustitle = '被取消' . $_GPC['award'];
        }
        if ($_GPC['status'] == 1) {
            $statustitle = '未兑换' . $_GPC['award'];
        }
        if ($_GPC['status'] == 2) {
            $statustitle = '已兑换' . $_GPC['award'];
        }
        if ($_GPC['status'] == '') {
            $statustitle = '全部' . $_GPC['award'];
        }
        //导出标题
        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $where = '';
        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
        if (isset($_GPC['status'])) {
            $where .= ' and a.status=:status';
            $params[':status'] = $_GPC['status'];
        }
        if (!empty($_GPC['award'])) {
            $where .= ' and a.name=:name';
            $params[':name'] = $_GPC['award'];
        }
        if (!empty($_GPC['keywords'])) {
            if (strlen($_GPC['keywords']) == 11 && is_numeric($_GPC['keywords'])) {
                $members = pdo_fetchall("select uid from " . tablename('mc_members') . " where mobile = :mobile", array(':mobile' => $_GPC['keywords']), 'uid');
                if (!empty($members)) {
                    $fans = pdo_fetchall("select openid from " . tablename('mc_mapping_fans') . " where uid in ('" . implode("','", array_keys($members)) . "')", array(), 'openid');
                    if (!empty($fans)) {
                        $where .= " and a.from_user IN ('" . implode("','", array_keys($fans)) . "')";
                    }
                }
            } else {
                $where .= ' and (a.award_sn like :keywords)';
                $params[':keywords'] = "%{$_GPC['keywords']}%";
            }
        }
        $total = pdo_fetchcolumn("select count(a.id) from " . tablename('kang_gold_award') . " a where a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select a.* from " . tablename('kang_gold_award') . " a where a.rid = :rid and a.uniacid=:uniacid  " . $where . " order by a.id desc " . $limit, $params);
        //中奖资料
        foreach ($list as &$lists) {
            $lists['realname'] = pdo_fetchcolumn("select realname from " . tablename('kang_gold_fans') . " where fansID = :fansID", array(':fansID' => $lists['fansID']));
            $lists['mobile'] = pdo_fetchcolumn("select mobile from " . tablename('kang_gold_fans') . " where fansID = :fansID", array(':fansID' => $lists['fansID']));
            $lists['credit_type'] = pdo_fetchcolumn("select credit_type from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $lists['prizetype']));
        }
        //中奖资料
        //一些参数的显示
        $num1 = pdo_fetchcolumn("select total_num from " . tablename('kang_gold_reply') . " where rid = :rid", array(':rid' => $rid));
        $num2 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . " where rid = :rid and status=1", array(':rid' => $rid));
        $num3 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . " where rid = :rid and status=2", array(':rid' => $rid));
        $num4 = pdo_fetchcolumn("select count(id) from " . tablename('kang_gold_award') . " where rid = :rid and status=0", array(':rid' => $rid));
        //一些参数的显示
        include $this->template('awardlist');
    }

    public function doWebDownload()
    {
        require_once 'download.php';
    }

    public function doWebSetshow()
    {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('kang_gold_reply', array('isshow' => $isshow), array('rid' => $rid));
        message('状态设置成功！', referer(), 'success');
    }

    public function doWebSetstatus()
    {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $rid = intval($_GPC['rid']);
        $status = intval($_GPC['status']);
        $reply = pdo_fetch("select * from " . tablename('kang_gold_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        if (empty($id)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $p = array('status' => $status);
        if ($status == 2) {
            $p['consumetime'] = TIMESTAMP;
        }
        if ($status == 3) {
            $p['consumetime'] = '';
            $p['status'] = 1;
        }
        $temp = pdo_update('kang_gold_award', $p, array('id' => $id));
        if ($temp == false) {
            message('抱歉，刚才操作数据失败！', '', 'error');
        } else {
            //修改用户状态
            if ($status >= 1) {
                $fansID = pdo_fetchcolumn("select fansID from " . tablename('kang_gold_award') . " where id=:id", array(':id' => $id));
                $statusis = pdo_fetchcolumn("select status from " . tablename('kang_gold_award') . " where fansID=:fansID and status>0 order by `status` asc", array(':fansID' => $fansID));
                pdo_update('kang_gold_fans', array('zhongjiang' => $statusis), array('fansID' => $fansID));
            }
            //恢复奖品数量到奖池
            if ($status == 0) {
                $name = pdo_fetchcolumn("select prizetype from " . tablename('kang_gold_award') . " where id=:id", array(':id' => $id));
                $prize = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $name));
                pdo_update('kang_gold_prize', array('prizedraw' => $prize['prizedraw'] - 1), array('id' => $prize['id']));
                $awardfans = pdo_fetch("select awardnum,id from " . tablename('kang_gold_fans') . " where rid=:rid and uniacid=:uniacid and fansID=:fansID", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $prize['fansID']));
                pdo_update('kang_gold_fans', array('awardnum' => $awardfans['awardnum'] - 1), array('id' => $awardfans['id']));
            }
            //恢复奖品数量到奖池
            //从奖池减少奖品
            if ($status == 1) {
                $name = pdo_fetchcolumn("select prizetype from " . tablename('kang_gold_award') . " where id=:id", array(':id' => $id));
                $prize = pdo_fetch("select * from " . tablename('kang_gold_prize') . " where id = :id", array(':id' => $name));
                if ($prize['prizetotal'] - $prize['prizedraw'] >= 1) {
                    pdo_update('kang_gold_prize', array('prizedraw' => $prize['prizedraw'] + 1), array('id' => $prize['id']));
                    $awardfans = pdo_fetch("select awardnum,id from " . tablename('kang_gold_fans') . " where rid=:rid and uniacid=:uniacid and fansID=:fansID", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':fansID' => $prize['fansID']));
                    pdo_update('kang_gold_fans', array('awardnum' => $awardfans['awardnum'] + 1), array('id' => $awardfans['id']));
                } else {
                    pdo_update('kang_gold_award', array('status' => 0), array('id' => $id));
                    message('已没有奖品可恢复了', $this->createWebUrl('awardlist', array('rid' => $_GPC['rid'])), 'error');
                }
            }
            //从奖池减少奖品
            message('状态设置成功！', $this->createWebUrl('awardlist', array('rid' => $_GPC['rid'])), 'success');
        }
    }

    public function webmessage($error, $url = '', $errno = -1)
    {
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
