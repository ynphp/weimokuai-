<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doWebProduct extends Superman_creditmallModuleSite {
    public function __construct() {
        parent::__construct(true);
    }

    public function exec() {
        global $_W, $_GPC;
        $title = '商品管理';
        $eid = intval($_GPC['eid']);
        $act = in_array($_GPC['act'], array('display', 'post', 'delete', 'setattr', 'virtual'))?$_GPC['act']:'display';
        $product_types = superman_product_type();

        if ($act == 'display') {
            //更新排序
            if(checksubmit('submit')) {
                $displayorder = $_GPC['displayorder'];
                if ($displayorder) {
                    foreach ($displayorder as $id=>$val) {
                        pdo_update('superman_creditmall_product', array('displayorder' => $val), array('id' => $id));
                    }
                    message('操作成功！', referer(), 'success');
                }
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;

            $filter['uniacid'] = $_W['uniacid'];
            if (trim($_GPC['title']) != ''){
                $filter['title'] = trim($_GPC['title']);
            }
            if ($_GPC['type'] > 0) {
                $filter['type'] = intval($_GPC['type']);
            }
            $sort = $_GPC['sort'];
            $orderby = isset($_GPC['orderby'])&&$_GPC['orderby']=='ASC'?'ASC':'DESC';
            $order_by = '';
            if (in_array($sort, array('total', 'sales'))) {
                $order_by = ' ORDER BY '.$sort.' '.$orderby;
                $orderby = $orderby=='ASC'?'DESC':'ASC';
            }
            $list = superman_product_fetchall($filter,$order_by,$start,$pagesize);
            $total = superman_product_count($filter);
            $pager = pagination($total, $pindex, $pagesize);
            if ($list) {
                foreach($list as &$p){
                    superman_product_set($p);
                    $p['prices'] = '';      //价格格式化
                    $p['credit_type'] = superman_credit_type($p['credit_type']);
                    if ($p['price'] && $p['credit']) {
                        $p['prices'] = $p['price'].'元+'.$p['credit'].$p['credit_type'];
                    } else if ($p['price']) {
                        $p['prices'] = $p['price'].'元';
                    } else if ($p['credit']) {
                        $p['prices'] = $p['credit'].$p['credit_type'];
                    } else {
                        $p['prices'] = '免费';
                    }
                }
                unset($p);
            }
            //print_r($list);
        } else if ($act == 'post') {
            $minus_total = superman_product_minus_total();
            $credit_type = superman_credit_type();
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            $dispatch = superman_dispatch_fetchall($filter);
            if (!$dispatch) {
                $dispatch = superman_dispatch_init($_W['uniacid']);
            }
            $id = intval($_GPC['id']);
            $item = superman_product_fetch($id);
            if ($item) {
                superman_product_set($item);
                //虚拟物品自动更新库存
                if (superman_is_virtual($item)) {
                    $filter = array(
                        'status' => 0,
                        'product_id' => $item['id']
                    );
                    $item['virtual_total'] = superman_virtual_count($filter);
                    pdo_update('superman_creditmall_product', array('total' => $item['virtual_total']), array('id' => $item['id']));
                }
            } else {
                $item['isvirtual'] = 0;
                $item['isshow'] = 1;
                $item['ishome'] = 1;
                $item['ishot'] = 1;
                $item['minus_total'] = 1;
                $item['order_buy_num'] = 0;
                $item['today_limit'] = 0;
                $item['max_buy_num'] = 0;
                $item['activity_time'] = array(
                    'start' => date('Y-m-d H:i:s'),
                    'end' => date('Y-m-d H:i:s', strtotime('+1 months')),
                );
                $item['share_credit_type'] = 'credit1';
                $item['share_credit'] = 0;
            }
            //获取会员组
            $filter = array(
                'uniacid' => $_W['uniacid'],
            );
            $groups = superman_mc_groups_fetchall($filter, '', 0, -1);
            //print_r($groups);

            if (checksubmit('submit')) {
                $activity_time = $_GPC['activity_time'];
                $cover = superman_fix_path($_GPC['cover']);
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'title' => $_GPC['title'],
                    'market_price' => $_GPC['market_price'],
                    'price' => $_GPC['price'],
                    'credit_type' => $_GPC['credit_type'],
                    'credit' => $_GPC['credit'],
                    'total' => intval($_GPC['total']),
                    'sales' => intval($_GPC['sales']),
                    'start_time' => !isset($_GPC['activity_time_limit'])?strtotime($activity_time['start']):0,
                    'end_time' => !isset($_GPC['activity_time_limit'])?strtotime($activity_time['end']):0,
                    'cover' => $cover,
                    'view_count' => intval($_GPC['view_count']),
                    'share_count' => intval($_GPC['share_count']),
                    'comment_count' => intval($_GPC['comment_count']),
                    'description' => $_GPC['description'],
                    'joined_total' => $_GPC['joined_total'],
                    'displayorder' => $_GPC['displayorder'],
                    'dateline' => TIMESTAMP,
                    'minus_total' => $_GPC['minus_total'],
                    'isshow' => $_GPC['isshow'],
                    'ishome' => $_GPC['ishome'],
                    'ishot' => $_GPC['ishot'],
                    'isvirtual' => $_GPC['isvirtual'] == 'on'?1:0,
                    'extend' => isset($_GPC['extend'])?iserializer($_GPC['extend']):'',
                    'order_buy_num' => $_GPC['order_buy_num'],
                    'today_limit' => $_GPC['today_limit'],
                    'max_buy_num' => $_GPC['max_buy_num'],
                    'dispatch_id' => $_GPC['dispatch_id'],
                    'share_credit_type' => $_GPC['share_credit_type'],
                    'share_credit' => $_GPC['share_credit'],
                    'groupid' => $_GPC['groupid'],
                );
                if (superman_is_redpack($data['type']) && superman_is_virtual($data)) {
                    message('微信红包不可选虚拟商品类型', referer(), 'error');
                }
                //print_r($data);die;
                $album = $_GPC['album'];
                if (is_array($album) && !empty($album)){
                    $data['album'] = serialize($album);
                }

                if (empty($id)){
                    $data['type'] = intval($_GPC['type']);
                    if (superman_is_virtual($data)) {
                        unset($data['total']);
                    }
                    if (superman_is_redpack($_GPC['type']) && $cover == '') {
                        $data['cover'] = './addons/superman_creditmall/template/mobile/images/redpack_bg.jpg';
                    }
                    pdo_insert('superman_creditmall_product', $data);
                    $new_id = pdo_insertid();

                    //如果是虚拟物品
                    if ($new_id && superman_is_virtual($data) && $_GPC['virtual_keys']) {
                        $virtual_keys =  trim($_GPC['virtual_keys']);
                        if (empty($virtual_keys)) {
                            message('无添加数据', referer());
                        }
                        $virtual_keys = explode("\n", $virtual_keys);
                        $_data = array(
                            'uniacid' => $_W['uniacid'],
                            'product_id' => $new_id,
                            'dateline' => TIMESTAMP,
                        );
                        //虚拟数据入库
                        foreach ($virtual_keys as $key => $item) {
                            if ($item == '') {
                                unset($virtual_keys['key']);
                                continue;
                            }
                            $_data['key'] = $item;
                            pdo_insert('superman_creditmall_virtual_stuff', $_data);
                        }
                        unset($_data);
                        //更新库存
                        pdo_update('superman_creditmall_product', array('total' => count($virtual_keys)), array('id' => $new_id));
                    }
                } else {
                    $item = superman_product_fetch($id);
                    //修复红包默认封面图片
                    if (superman_is_redpack($item['type'])) {
                        if (stripos($data['cover'], 'addons/') !== false && substr($data['cover'], 0, 2) != './') {
                            $data['cover'] = './'.$data['cover'];
                        }
                        if ($data['cover'] == '') {
                            $data['cover'] = './addons/superman_creditmall/template/mobile/images/redpack_bg.jpg';
                        }
                    }
                    unset($data['type']);   //商品类型不能修改
                    unset($data['dateline']);
                    unset($data['isvirtual']);//虚拟商品类型不可修改
                    if (superman_is_virtual($item)) {
                        unset($data['total']);
                    }
                    pdo_update('superman_creditmall_product', $data, array('id' => $id));
                }
                message('操作成功！', $this->createWebUrl('product'), 'success');
            }
        } else if ($act == 'delete') {
            $id = intval($_GPC['id']);
            $item = superman_product_fetch($id);
            if (empty($item)) {
                message('商品不存在或是已被删除！', referer(), 'error');
            }
            if (!empty($item['album'])) {
                $arr = unserialize($item['album']);
                if ($arr) {
                    foreach ($arr as $v) {
                        file_delete($v);
                    }
                }
            }
            if ($item['cover']) {
                file_delete($item['cover']);
            }
            pdo_delete('superman_creditmall_product', array('id' => $id));
            message('操作成功！', referer(), 'success');
        } else if ($act == 'setattr') {
            $id = intval($_GPC['id']);
            if (!$id) {
                echo '非法请求！';
                exit;
            }
            $field = $_GPC['field'];
            $value = $_GPC['value'];
            if (!in_array($field, array('isshow'))) {
                echo '非法请求！';
                exit;
            }
            $data = array(
                $field => $value==1?0:1
            );
            $condition = array(
                'id' => $id,
            );
            pdo_update('superman_creditmall_product', $data, $condition);
            echo 'success';
            exit;
        } else if ($act == 'virtual') {
            $product_id = intval($_GPC['product_id']);
            if ($product_id > 0) {
                $product = superman_product_fetch($product_id);
                if (empty($product)) {
                    message('商品不存在或已删除', $this->createWebUrl('product'), 'error');
                }
            }

            if ($_GPC['delete'] == 1 && $_GPC['id'] != '') {
                pdo_delete('superman_creditmall_virtual_stuff', array('id'=> $_GPC['id']));
                message('删除成功', referer(), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 20;
            $start = ($pindex - 1) * $pagesize;

            $filter = array(
                'product_id' => $product_id
            );
            $list = superman_virtual_fetchall($filter, '', $start, $pagesize);
            if ($list) {
                foreach ($list as &$item) {
                    superman_virtual_set($item);
                }
                unset($item);
            }

            $total = superman_virtual_count($filter);
            $pager = pagination($total, $pindex, $pagesize);
            if (checksubmit('submit')) {
                $virtual_keys =  trim($_GPC['virtual_keys']);
                if (empty($virtual_keys)) {
                    message('无添加数据', referer());
                }
                $virtual_keys = explode("\n", $virtual_keys);
                $_data = array(
                    'uniacid' => $_W['uniacid'],
                    'product_id' => $product_id,
                    'dateline' => TIMESTAMP,
                );

                foreach ($virtual_keys as $key => $item) {
                    if ($item == '') {
                        unset($virtual_keys['key']);
                        continue;
                    }
                    $_data['key'] = $item;
                    pdo_insert('superman_creditmall_virtual_stuff', $_data);
                }
                $filter = array(
                    'product_id' => $product_id,
                    'status' => 0
                );
                $count = superman_virtual_count($filter);
                pdo_update('superman_creditmall_product', array('total' => $count), array('id' => $product_id));
                message('添加成功', referer(), 'success');
            }

        }
        include $this->template('web/product');
    }
}

$obj = new Superman_creditmall_doWebProduct;
$obj->exec();
