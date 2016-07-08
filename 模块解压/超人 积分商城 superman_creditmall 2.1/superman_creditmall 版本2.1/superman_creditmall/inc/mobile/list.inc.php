<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileList extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display', 'share'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $uid = $_W['member']['uid'];

            $typeid = intval($_GPC['type'])?$_GPC['type']:1;
//            $credit_type = isset($_GPC['credit_type'])?$_GPC['credit_type']:'credit1';        //取出筛选条件

            $product_types = superman_product_type();
            $header_title = isset($product_types[$typeid])?$product_types[$typeid]['title']:'商品列表';
            $header_title = superman_is_redpack($typeid)?'微信红包':$header_title;
            $credit_types = superman_credit_type();

            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
//                'credit_type' => $credit_type,
                'type' => $typeid,
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
            );

            $orderby = ' ORDER BY ';
            if (isset($_GPC['sort']) && in_array($_GPC['sort'],array('default','sales','comment','creditdown','creditup','canbuy'))) {
                $sort = $_GPC['sort'];
                if ($sort == 'sales') {
                    $orderby .= ' `sales` DESC,`dateline` DESC';
                } else if ($sort == 'comment') {
                    $orderby .= ' `comment_count` DESC,`dateline` DESC';
                } else if ($sort == 'creditdown') {
                    $orderby .= ' `credit` DESC,`dateline` DESC';
                } else if ($sort == 'creditup') {
                    $orderby .= ' `credit` ASC,`dateline` DESC';
//                } else if ($sort == 'canbuy') {
//                    checkauth();
//                    superman_mycredit($_W['member']['uid']);0
//                    $orderby .= ' `displayorder` DESC,`dateline` DESC';
                } else {
                    $orderby .= ' `displayorder` DESC,`dateline` DESC';
                }
            } else {
                $orderby .= ' `displayorder` DESC,`dateline` DESC';
            }
            $list = superman_product_fetchall($filter, $orderby, $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }

            //print_r($list);
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }

            if (superman_is_redpack($_GPC['type'])) {
                include $this->template('list-redpack');
            } else {
                include $this->template('list');
            }
            exit;
        } else if ($act == 'share') {
            $header_title = '商品列表';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'share_credit' => 0,
                'in_type' => array(1,2,3,4,7),
            );
            $orderby = '';
            $list = superman_product_fetchall($filter, $orderby, $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_product_set($item);
                }
                unset($item);
            }

            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }

            include $this->template('list-share');
            exit;
        }
    }
}

$obj = new Superman_creditmall_doMobileList;
$obj->exec();
