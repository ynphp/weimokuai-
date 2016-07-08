<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileDetail extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = array();
        $title =  '积分商城';
        $act = in_array($_GPC['act'], array('display', 'view', 'share'))?$_GPC['act']:'display';
        $id = intval($_GPC['id']);
        if ($act == 'display') {
            $header_title = '商品详情';
            $pindex = max(1, intval($_GPC['page']));
            $pagesize = 10;
            $start = ($pindex - 1) * $pagesize;
            if (!$id) {
                message('非法请求！', referer(), 'warning');
            }
            $product = superman_product_fetch($id);
            if (!$product) {
                message('数据不存在或已删除！', referer(), 'error');
            }
            superman_product_set($product);

            //分享赚积分
            if (isset($_GPC['fromuid']) && $product['share_credit'] > 0 && $_W['container'] == 'wechat') {
                $this->update_share($_GPC['fromuid'], $product);
            }

            if ($_W['member']['uid']) {     //当前会员积分
                $mycredit = superman_mycredit($_W['member']['uid'], $product['credit_type'], true);
            }

            $sql = '';
            if ($product['type'] == 2) {
                $sql = 'SELECT a.nickname,a.avatar,b.dateline,b.credit,b.credit_type FROM '.tablename('mc_members').' AS a,'.tablename('superman_creditmall_product_log').' AS b WHERE a.uid=b.uid AND b.status=:status AND b.product_id=:id ORDER BY b.id DESC LIMIT '.$start.','.$pagesize;
            } else {
                $sql = 'SELECT a.nickname,a.avatar,b.dateline,b.credit,b.credit_type FROM '.tablename('mc_members').' AS a,'.tablename('superman_creditmall_order').' AS b WHERE b.product_id=:id AND a.uid=b.uid AND b.status>=:status ORDER BY b.id DESC LIMIT '.$start.','.$pagesize;
            }

            $filter = array(
                ':id' => $id,
                ':status' => 1,
            );
            $list = pdo_fetchall($sql,$filter);

            if ($list) {
                foreach ($list as &$order) {
                    superman_order_set($order);
                    $order['nickname'] = empty($order['nickname'])?'***':superman_hide_nickname($order['nickname']);
                    $order['avatar'] = tomedia($order['avatar']);
                }
                unset($order);
            }
            //print_r($list);
            if ($_W['isajax'] && $_GPC['load'] == 'infinite') {
                die(json_encode($list));
            }
            $_share = array(
                'title' => $product['title'],
                'desc' => cutstr(strip_tags(htmlspecialchars_decode($product['description'])), 60),
                'link' => $_W['siteroot'].'app/'.$this->createMobileUrl('detail', array('id' => $id, 'fromuid' => $_W['member']['uid'])),
                'imgUrl' => $product['cover'],
            );

            if (superman_is_redpack($product['type'])) {
                include $this->template('detail-redpack');
            } else {
                include $this->template('detail');
            }
        } else if ($act == 'view') {
            if ($_W['container'] == 'wechat') {
                $key = '_product_view_'.$id;
                $value = 'yes';
                if (!isset($_GPC[$key]) || $_GPC[$key] != $value) {
                    $ret = superman_product_update_count($id, 'view_count');
                    if ($ret) {
                        $expire_time = strtotime(date('Y-m-d 23:59:59')) - TIMESTAMP;
                        $expire_time = $expire_time>=3600?$expire_time:3600;
                        isetcookie($key, $value, $expire_time);
                    }
                    superman_stat_update_count(date('Ymd'), 'product_views');
                }
            }
            exit();
        } else if ($act == 'share') {
            if ($_W['container'] == 'wechat') {
                superman_product_update_count($id, 'share_count');
                superman_stat_update_count(date('Ymd'), 'product_shares');
            }
            exit();
        }
    }

    //更新分享数据
    private function update_share($fromuid, $product) {
        global $_W, $_GPC;
        $share_key = '_'.md5('_product_share_'.$fromuid.'_'.$product['id'].'superman_creditmall_share');
        $share_value = 'yes';
        if (isset($_GPC[$share_key]) && $share_value == 'yes') {
            return;
        }
        $friend_uid = 0;
        $member = mc_fetch($fromuid, array('nickname'));
        if ($member) {
            //获取任务数据
            $task = superman_task_fetch_name('superman_creditmall_task5', $_W['uniacid']);
            if (!$task) {
                return;
            }
            superman_task_set($task);

            //检查是否领取任务
            $mytask = superman_mytask_fetch_uid($fromuid, $task['id']);
            if (!$mytask) {
                return;
            }

            //每天领取分享积分上限
            if (isset($task['extend']['credit_limit']) && $task['extend']['credit_limit'] > 0) {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'starttime' => strtotime(date('Y-m-d 0:0:0', TIMESTAMP)),
                    'endtime' => strtotime(date('Y-m-d 23:59:59', TIMESTAMP)),
                );
                $credit_total = superman_prodcut_share_sum($filter);
                if ($credit_total > 0 && $credit_total + $product['share_credit'] >= $task['extend']['credit_limit']) {
                    return;
                }
            }
            if ($_W['member']['uid']) { //会员
                $friend_uid = $_W['member']['uid'];
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'product_id' => $product['id'],
                    'friend_uid' => $friend_uid,
                );
                $list = superman_product_share_fetchall($filter, '', 0, 1);
                if ($list) {   //存在分享记录
                    return;
                }
            } else {
                $filter = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => $fromuid,
                    'product_id' => $product['id'],
                    'ip' => $_W['clientip'],
                );
                $list = superman_product_share_fetchall($filter, '', 0, 1);
                if ($list && count($list) >= 3) {   //存在分享记录
                    return;
                }
            }
            //记录分享数据
            $data = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $fromuid,
                'product_id' => $product['id'],
                'friend_uid' => $friend_uid,
                'ip' => $_W['clientip'],
                'credit_type' => $product['share_credit_type'],
                'credit' => $product['share_credit'],
                'dateline' => TIMESTAMP,
            );
            $new_id = superman_product_share_insert($data);
            if ($new_id) {
                //增加积分
                $log = array(
                    $friend_uid,    //记录积分来源
                    "分享商品(id={$product['id']})奖励积分",
                    'superman_creditmall',
                );
                $ret = mc_credit_update($fromuid, $product['share_credit_type'], $product['share_credit'], $log);
                if (is_error($ret)) {
                    WeUtility::logging('fatal', '更新积分失败, result='.var_export($ret, true));
                    return;
                }

                //记录cookie
                $expire_time = 30*365*86400;
                isetcookie($share_key, $share_value, $expire_time);
            }
        }
    }
}

$obj = new Superman_creditmall_doMobileDetail;
$obj->exec();
