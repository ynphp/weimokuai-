<?php
/**
 *
 *
 * @author  codeMonkey
 * qq:mitusky QQ：229364369
 * @url
 */
defined('IN_IA') or exit('Access Denied');

define("MON_MAGAZINE", "mon_magazine");
define("MON_MAGAZINE_RES", "../addons/" . MON_MAGAZINE . "/");
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/dbutil.class.php";
require_once IA_ROOT . "/addons/" . MON_MAGAZINE . "/monUtil.class.php";

class Mon_MagazineModule extends WeModule
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
            $reply = DBUtil::findUnique(DBUtil::$TABLE_MAGAZINE, array(":rid" => $rid));
            $mag_pages=pdo_fetchall("select * from ".tablename(DBUtil::$TABLE_MAGAZINE_PAGE)." where mid=:mid order by page asc ",array(":mid"=>$reply['id']));
        }

        load()->func('tpl');
        MonUtil::deleteInstall();
        include $this->template('form');


    }

    public function fieldsFormValidate($rid = 0)
    {
        global $_GPC, $_W;


        return '';
    }

    public function fieldsFormSubmit($rid)
    {
        global $_GPC;
        $mid = $_GPC['mid'];
        $data = array(
            'rid' => $rid,
            'weid' => $this->weid,
            'title' => $_GPC['title'],
            'cover_img' => $_GPC['cover_img'],
            'zx_tel' => $_GPC['zx_tel'],
            'btn_icons' => $_GPC['btn_icons'],
            'btn1_name' => $_GPC['btn1_name'],
            'btn1_url' => $_GPC['btn1_url'],
            'btn2_name' => $_GPC['btn2_name'],
            'btn2_url' => $_GPC['btn2_url'],
            'new_title' => $_GPC['new_title'],
            'new_icon' => $_GPC['new_icon'],
            'new_content' => $_GPC['new_content'],
            'share_title' => $_GPC['share_title'],
            'share_icon' => $_GPC['share_icon'],
            'share_content' => $_GPC['share_content'],
            'share_Layer' => $_GPC['share_Layer'],
            'updatetime' => TIMESTAMP
        );

        if (empty($mid)) {
            $data['createtime'] = TIMESTAMP;
            DBUtil::create(DBUtil::$TABLE_MAGAZINE, $data);
            $mid = pdo_insertid();
        } else {
            DBUtil::updateById(DBUtil::$TABLE_MAGAZINE, $data, $mid);
        }

        //目录
        $pids = array();
        $page_ids = $_GPC['page_ids'];
        $pages = $_GPC['pages'];
        $pnames = $_GPC['pnames'];
        $purls = $_GPC['purls'];

        if (is_array($page_ids)) {
            foreach ($page_ids as $key => $value) {
                $value = intval($value);
                $d = array(
                    "mid" => $mid,
                    "pname" => $pnames[$key],
                    "page" => $pages[$key],
                    "purl" => $purls[$key],
                    "createtime"=>TIMESTAMP
                );

                if (empty($value)) {
                    DBUtil::create(DBUtil::$TABLE_MAGAZINE_PAGE, $d);
                    $pids[] = pdo_insertid();
                } else {
                    DBUtil::updateById(DBUtil::$TABLE_MAGAZINE_PAGE, $d, $value);
                    $pids[] = $value;
                }

            }


            if (count($pids) > 0) {

                pdo_query("delete from " . tablename(DBUtil::$TABLE_MAGAZINE_PAGE) . " where mid='{$mid}' and id not in (" . implode(",", $pids) . ")");

            } else {

                pdo_query("delete from " . tablename(DBUtil::$TABLE_MAGAZINE_PAGE) . " where mid='{$mid}'");

            }

        }

        return true;
    }

    public function ruleDeleted($rid)
    {
        $mag = DBUtil::findUnique(DBUtil::$TABLE_MAGAZINE, array(":rid" => $rid));
        pdo_delete(DBUtil::$TABLE_MAGAZINE_PAGE, array("mid" => $mag['id']));
    }


}