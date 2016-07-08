<?php
defined('IN_IA') or exit('Access Denied');
class Lianhu_schoolModule extends WeModule
{
    public function fieldsFormDisplay($rid = 0)
    {
    }
    public function fieldsFormValidate($rid = 0)
    {
        return '';
    }
    public function fieldsFormSubmit($rid)
    {
    }
    public function ruleDeleted($rid)
    {
    }
    public function settingsDisplay($settings)
    {
        @session_start();
        global $_GPC, $_W;
        //if (!$_SESSION['school_id']) {
         //   message("检测到您没有选择学校", '', 'error');
       // }
        $config = $settings;
        if (checksubmit()) {
            $config['on_school'][$_SESSION['school_id']]    = $_GPC['on_school'];
            $config['begin_course'][$_SESSION['school_id']] = $_GPC['begin_course'];
            $config['am_much'][$_SESSION['school_id']]      = $_GPC['am_much'];
            $config['pm_much'][$_SESSION['school_id']]      = $_GPC['pm_much'];
            $config['ye_much'][$_SESSION['school_id']]      = $_GPC['ye_much'];
            $config['sms_set'][$_SESSION['school_id']]      = $_GPC['sms_set'];
            $config['school_url'][$_SESSION['school_id']]   = $_GPC['school_url'];
            $config['line_type'][$_SESSION['school_id']]    = $_GPC['line_type'];
            $config['appointment'][$_SESSION['school_id']]  = $_GPC['appointment'];
            $cfg                                            = array(
                'msg' => $_GPC['msg'],
                'msg1' => $_GPC['msg1'],
                'msg01' => $_GPC['msg01'],
                'msg11' => $_GPC['msg11'],
                'version' => $_GPC['version'],
                'mylovekid' => $_GPC['mylovekid'],
                'family_set' => $_GPC['family_set'],
                'on_school' => $config['on_school'],
                'begin_course' => $config['begin_course'],
                'am_much' => $config['am_much'],
                'pm_much' => $config['pm_much'],
                'ye_much' => $config['ye_much'],
                'sms_set' => $config['sms_set'],
                'school_url' => $config['school_url'],
                'line_type' => $config['line_type'],
                'appointment' => $config['appointment'],
                'qiniu' => $_GPC['qiniu'],
                'qiniu_url' => $_GPC['qiniu_url'],
                'qiniu_pipeline' => $_GPC['qiniu_pipeline'],
                'qiniu_AccessKey' => $_GPC['qiniu_AccessKey'],
                'qiniu_SecretKey' => $_GPC['qiniu_SecretKey'],
                'qiniu_bucket' => $_GPC['qiniu_bucket'],
                'jia_passport' => $_GPC['jia_passport'],
                'jia_password' => $_GPC['jia_password'],
                'admin_openid' => $_GPC['admin_openid'],
                'pay_do' => $_GPC['pay_do'],
                'pay_uniacid' => $_GPC['pay_uniacid']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        load()->func('tpl');
        $uniacid_list = pdo_fetchall("select * from " . tablename('account_wechats') . "  where 1=1");
        include $this->template('setting');
    }
}