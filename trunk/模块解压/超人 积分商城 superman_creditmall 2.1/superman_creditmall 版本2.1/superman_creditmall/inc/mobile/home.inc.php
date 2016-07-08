<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileHome extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $do = $do?$do:'home';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '首页';
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'ishome' => 1,
            );
            $product_types = superman_product_type(0, true);
            $list = array();
            //print_r($product_types);
            foreach ($product_types as $type) {
                $filter['type'] = $type['id'];
                $result = superman_product_fetchall($filter, '', 0, -1);
                if ($result) {
                    foreach ($result as &$item) {
                        superman_product_set($item);
                    }
                    unset($item);
                    $list[$type['id']] = $result;
                }
            }
            //print_r($list);

            //ad list
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'time' => TIMESTAMP,
                'position_id' => 1,
            );
            $adlist = superman_ad_fetchall_posid($filter);
            //print_r($adlist);

        }
        include $this->template('home');
    }
}

$obj = new Superman_creditmall_doMobileHome;
$obj->exec();
