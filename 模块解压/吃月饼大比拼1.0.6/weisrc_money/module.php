<?php
/**
 * 吃月饼大比拼
 *
 * 作者:迷失卍国度
 *
 * qq : 15595755
 */
defined('IN_IA') or exit('Access Denied');
include "model.php";

class weisrc_moneyModule extends WeModule {

    public $tablename = 'weisrc_money_reply';
    public $tableaward = 'weisrc_money_award';

    public function fieldsFormDisplay($rid = 0) {
        global $_W;
        load()->func('tpl');
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $prize = pdo_fetchall("SELECT * FROM " . tablename($this->tableaward) . " WHERE rid = :rid ORDER BY `id` asc", array(':rid' => $rid));
        }
        $time = date('Y-m-d H:i', TIMESTAMP + 3600*24);
        if (!$reply) {
            $now = TIMESTAMP;
            $reply = array(
                "title" => "吃月饼大比拼",
                "start_picurl" => "../addons/weisrc_money/template/mobile/money/images/game.jpg",
                "description" => "吃月饼大比拼",
                "rule" => '1、中秋节吃月饼比拼，滑动你的手指吃月饼。<br />
					2、在规定时间内滑动越快分数越高。<br />
					3、结果按分数排名。<br />
					<br />
					此活动仅作中秋节演示。',
                "award" => '<p>1、 手机号码为兑奖重要凭证，填写应当真实有效，如若有误，作废处理。</p>
							<p>2、 优惠券使用规则参照商家实际制定。</p>
							<p>3、 本活动最终解释权归xxxx所有。</p>',
                "awardtip" => "注:活动时间截止{$time},活动结束后依次按排行榜名次发奖",
                "starttime" => $now,
                "endtime" => strtotime(date("Y-m-d H:i", $now + 7 * 24 * 3600)),
                "end_picurl" => "../addons/weisrc_money/template/mobile/money/images/game.jpg",
                "share_image" => "../addons/weisrc_money/icon.jpg",
                "end_theme" => "活动结束了",
                "end_instruction" => "活动已经结束了",
                "bg" => "../addons/weisrc_money/template/mobile/money/images/bg.jpg",
                "btn_start" => "../addons/weisrc_money/template/mobile/bridge/image/btn_start.png",
                "game_page_bg" => "../addons/weisrc_money/template/mobile/bridge/image/game_page_bg.jpg",
                "result_page_bg" => "../addons/weisrc_money/template/mobile/bridge/image/result_page_bg.jpg",
                "game_tile" => "../addons/weisrc_money/template/mobile/bridge/image/game_tile.png",
                "number_times" => 10,
                "gametime" => 20,
                "gamelevel" => 60,
                "tips1text" => '吃',
                "tips2text" => '能吃',
                "tips3text" => '吃月饼',
                "signtext" => '个',
                "Gameovertext" => '吃货进化的机会已用完啦！',
                "showusernum" => 15,
                "cover" => "../addons/weisrc_money/template/mobile/boat/App_Content/Game/Boats/style/images/cover.jpg",
                "ad" => "../addons/weisrc_money/template/mobile/bridge/image/bottom_ads.jpg",
                "adurl" => "#",
                "most_num_times" => 3,
                "daysharenum" => 1,
                "mode" => 0,
                "sharelotterynum" =>1,
                'copyright' => '',
                'isneedfollow' => 1,
                'copyrighturl' => '',
                "share_title" => "中秋月饼吃到爽，谁更快快快！",
                "share_desc" => "吃月饼最快的那个是你吗？吃货与饕餮的终极对决，敢不敢来挑战~",
            );
        }

        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        global $_GPC, $_W;
        load()->func('tpl');
        $id = intval($_GPC['reply_id']);

        $insert = array(
            'rid' => $rid,
            'weid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'content' => trim($_GPC['content']),
            'description' => trim($_GPC['description']),
            'rule' => trim($_GPC['rule']),
            'award' => trim($_GPC['award']),
            'end_theme' => $_GPC['end_theme'],
            'end_instruction' => $_GPC['end_instruction'],
            'number_times' => intval($_GPC['number_times']),
            'most_num_times' => intval($_GPC['most_num_times']),
            'daysharenum' => intval($_GPC['daysharenum']),
            'starttime' => strtotime($_GPC['datelimit']['start']),
            'endtime' => strtotime($_GPC['datelimit']['end']),
            'dateline' => TIMESTAMP,
            'copyright' => $_GPC['copyright'],
            'copyrighturl' => $_GPC['copyrighturl'],
            "gametime" => intval($_GPC['gametime']),
            "gamelevel" => intval($_GPC['gamelevel']),
            "number_times" => intval($_GPC['number_times']),
            "showusernum" => intval($_GPC['showusernum']),
            "most_num_times" => intval($_GPC['most_num_times']),
            "daysharenum" => intval($_GPC['daysharenum']),
            "mode" => intval($_GPC['mode']),
            "isneedfollow" => intval($_GPC['isneedfollow']),
            "sharelotterynum" => intval($_GPC['sharelotterynum']),
            'Gameovertext' => $_GPC['Gameovertext'],
            'tips1text' => $_GPC['tips1text'],
            'tips2text' => $_GPC['tips2text'],
            'tips3text' => $_GPC['tips3text'],
            'signtext' => $_GPC['signtext'],
            'share_title' => $_GPC['share_title'],
            'share_desc' => $_GPC['share_desc'],
            'share_url' => $_GPC['share_url'],
            'follow_url' => $_GPC['follow_url'],
            'ad' => $_GPC['ad'],
            'adurl' => $_GPC['adurl'],
            'awardtip' => $_GPC['awardtip'],
        );

        if ($insert['number_times'] == 0) {
            message('每人最多游戏次数不能为0');
        }

        if ($insert['most_num_times'] == 0) {
            message('每人每天游戏次数不能为0');
        }

        if (!empty($_GPC['start_picurl'])) {
            $insert['start_picurl'] = $_GPC['start_picurl'];
        }

        if (!empty($_GPC['end_picurl'])) {
            $insert['end_picurl'] = $_GPC['end_picurl'];
        }

        if (!empty($_GPC['share_image'])) {
            $insert['share_image'] = $_GPC['share_image'];
        }

        if (!empty($_GPC['cover'])) {
            $insert['cover'] = $_GPC['cover'];
        }

        if (!empty($_GPC['bg'])) {
            $insert['bg'] = $_GPC['bg'];
        }

        if (!empty($_GPC['btn_start'])) {
            $insert['btn_start'] = $_GPC['btn_start'];
        }

        if (!empty($_GPC['game_page_bg'])) {
            $insert['game_page_bg'] = $_GPC['game_page_bg'];
        }

        if (!empty($_GPC['result_page_bg'])) {
            $insert['result_page_bg'] = $_GPC['result_page_bg'];
        }

        if (!empty($_GPC['game_tile'])) {
            $insert['game_tile'] = $_GPC['game_tile'];
        }

        if (empty($id)) {
            if ($insert['starttime'] <= time()) {
                $insert['status'] = 1;
            } else {
                $insert['status'] = 0;
            }
            $id = pdo_insert($this->tablename, $insert);
        } else {
            unset($insert['dateline']);
            pdo_update($this->tablename, $insert, array('id' => $id));
        }
        return true;
    }

    public function ruleDeleted($rid) {
        pdo_delete('weisrc_money_reply', array('rid' => $rid));
        pdo_delete('weisrc_money_fans', array('rid' => $rid));
        pdo_delete('weisrc_money_record', array('rid' => $rid));
    }

    public function settingsDisplay($settings) {
        global $_GPC, $_W;
    }
}
