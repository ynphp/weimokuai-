<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:631872807
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_XS_VOICE", "mon_xsvoice");
define("MON_XS_VOICE_RES", "../addons/" . MON_XS_VOICE . "/");
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/monUtil.class.php";

class Mon_XSVoiceModule extends WeModule
{

    public $weid;

    public function __construct()
    {
        global $_W;
        $this->weid = IMS_VERSION < 0.6 ? $_W['weid'] : $_W['uniacid'];
    }


    public function fieldsFormDisplay($rid = 0)
    {
        global $_W;

        if (!empty($rid)) {
            $reply = DBUtil::findUnique(DBUtil::$TABLE_VOICE, array(":rid" => $rid));

            $reply['starttime'] = date("Y-m-d  H:i", $reply['starttime']);
            $reply['endtime'] = date("Y-m-d  H:i", $reply['endtime']);

        }


        load()->func('tpl');


        include $this->template('form');


    }

    public function fieldsFormValidate($rid = 0)
    {
        global $_GPC, $_W;


        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        global $_GPC, $_W;
        $vid = $_GPC['vid'];

        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'starttime' => strtotime($_GPC['starttime']),
            'endtime' => strtotime($_GPC['endtime']),

            'follow_url' => $_GPC['follow_url'],
            'copyright' => $_GPC['copyright'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'title_img' => $_GPC['title_img'],
            'crp_img' => $_GPC['crp_img'],
            'img1' => $_GPC['img1'],
            'img2' => $_GPC['img2'],
            'img3' => $_GPC['img3'],
            'img4' => $_GPC['img4'],
            'follow_msg' => $_GPC['follow_msg'],
            'follow_btn' => $_GPC['follow_btn'],
            'createtime' => TIMESTAMP,
            'index_bgcolor' => $_GPC['index_bgcolor'],
            'style_bgcolor' => $_GPC['style_bgcolor'],
            'voice_target' => $_GPC['voice_target'],
            'rank_title' => $_GPC['rank_title'],
            'intro' => htmlspecialchars_decode($_GPC['intro'])
        );

        if (empty($vid)) {

           DBUtil::create(DBUtil::$TABLE_VOICE,$data);

        } else {
            DBUtil::updateById(DBUtil::$TABLE_VOICE,$data,$vid);
        }

        return true;
    }

    public function ruleDeleted($rid)
    {
        $voice = DBUtil::findUnique(DBUtil::$TABLE_VOICE, array(":rid" => $rid));
        pdo_delete(DBUtil::$TABLE_VOICE_USER, array("kid" => $voice['id']));


    }


}