<?php
/**
 * 会员卡模块
 * 作者:迷失卍国度
 * qq : 15595755
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');
define('LOCK', 'Li9zb3VyY2UvbW9kdWxlcy93ZWlzcmNfY2FyZC90ZW1wbGF0ZS90aGVtZXMvMi92ZXJzaW9uLmNzcw==');
include "../addons/weisrc_card/model.php";

class weisrc_cardModule extends WeModule
{
    public $name = 'weisrc_card';
    public $title = '';
    public $ability = '';
    public $tablename = 'weisrc_card_reply';
    public $modulename = 'weisrc_card'; //模块标识
    public $action = 'style'; //方法

    function __construct()
    {
        global $_W;
        $this->_fromuser = $_W['fans']['from_user'];//debug
        $this->_weid = $_W['uniacid'];

        $this->_appid = '';
        $this->_appsecret = '';
        $this->_accountlevel = $_W['account']['level']; //是否为高级号

        $lock_path = base64_decode(LOCK);
        if (!file_exists($lock_path)) {
            //message(base64_decode('5a+55LiN6LW377yM5oKo5L2/55So55qE5LiN5piv5q2j54mI6L2v5Lu277yM6LSt5Lmw5q2j54mI6K+36IGU57O7cXE6MTU1OTU3NTU='));
        } else {
            $file_content = file_get_contents($lock_path);
            $validation_code = $this->authorization();
            //$this->code_compare($file_content, $validation_code);
        }
    }

    public function fieldsFormSubmit($rid = 0)
    {
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
        $data = array(
            'rid' => $rid,
            'weid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'title_not' => $_GPC['title_not'],
            'picture' => $_GPC['picture'],
            'picture_not' => $_GPC['picture_not'],
            'description' => $_GPC['description'],
            'description_not' => $_GPC['description_not'],
            'dateline' => TIMESTAMP
        );

        if (empty($id)) {
            pdo_insert($this->tablename, $data);
        } else {
            //会员图片
            if (!empty($_GPC['picture'])) {
                file_delete($_GPC['picture_old']);
            } else {
                unset($data['picture']);
            }
            //非会员图片
            if (!empty($_GPC['picture_not'])) {
                file_delete($_GPC['picture_not_old']);
            } else {
                unset($data['picture_not']);
            }
            unset($data['dateline']);
            pdo_update($this->tablename, $data, array('id' => $id));
        }
    }

    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->tablename) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $starttime = intval($reply['starttime']) == 0 ? TIMESTAMP : $reply['starttime'];
            $endtime = intval($reply['endtime']) == 0 ? TIMESTAMP + 86400 * 10 : $reply['endtime'];
        } else {
            $starttime = TIMESTAMP;
            $endtime = TIMESTAMP + 86400 * 10;
        }
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0)
    {
        return true;
    }

    //删除规则
    public function ruleDeleted($rid = 0)
    {
        global $_W;
        $replies = pdo_fetchall("SELECT id FROM " . tablename($this->tablename) . " WHERE rid = '$rid'");
        $deleteid = array();
        if (!empty($replies)) {
            foreach ($replies as $index => $row) {
                //file_delete($row['thumb']);
                $deleteid[] = $row['id'];
            }
        }
        pdo_delete($this->tablename, "id IN ('" . implode("','", $deleteid) . "')");
        return true;
    }

    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if(checksubmit()) {
            $cfg = $settings;
            $cfg['icard']['follow_url'] = trim($_GPC['follow_url']);
            $cfg['icard']['share_title'] = trim($_GPC['share_title']);
            $cfg['icard']['share_image'] = trim($_GPC['share_image']);
            $cfg['icard']['share_cancel'] = trim($_GPC['share_cancel']);
            $cfg['icard']['share_desc'] = trim($_GPC['share_desc']);
            $cfg['icard']['share_url'] = trim($_GPC['share_url']);
            $cfg['icard']['not_follow_url'] = trim($_GPC['not_follow_url']);
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        //serialize();
        //unserialize();
        include $this->template('setting');
    }
}
