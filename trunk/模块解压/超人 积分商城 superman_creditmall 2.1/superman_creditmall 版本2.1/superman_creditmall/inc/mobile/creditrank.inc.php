<?php
/**
 * 【超人】积分商城模块定义
 *
 * @author 超人
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
class Superman_creditmall_doMobileCreditrank extends Superman_creditmallModuleSite {
    public function exec() {
        global $_W, $_GPC, $do;
        $_share = $this->_share;
        $title = '积分商城';
        $act = in_array($_GPC['act'], array('display'))?$_GPC['act']:'display';
        if ($act == 'display') {
            $header_title = '积分排行榜';
            $all_credit_types = superman_credit_type();
            $credit_type = array_key_exists($_GPC['credit_type'], $all_credit_types)?$_GPC['credit_type']:'credit1';
            $credit_title = $all_credit_types[$credit_type]['title'];
            if (is_array($this->module['config']['base']['creditrank_type']) && $this->module['config']['base']['creditrank_type']) {
                foreach ($this->module['config']['base']['creditrank_type'] as $type) {
                    $credit_type_selected[] = array(
                        'type' => $type,
                        'title' => $all_credit_types[$type]['title'],
                    );
                }
                unset($item);
            } else {
                $credit_type_selected[] = array(
                    'type' => $credit_type,
                    'title' => $all_credit_types[$credit_type]['title'],
                );
            }
            //print_r($credit_type_selected);

            //积分排行榜
            $row = $this->module['config']['base']['rank_num'] > 0 ? $this->module['config']['base']['rank_num'] : 10;
            $sql = "SELECT `uid`,`nickname`,`$credit_type`,`avatar` FROM ".tablename('mc_members')." WHERE `uniacid`=:uniacid ORDER BY `$credit_type` DESC,uid ASC LIMIT $row";
            $param = array(
                ':uniacid' => $_W['uniacid'],
            );
            $list = pdo_fetchall($sql, $param);
            if ($list) {
                foreach ($list as $k=>&$item) {
                    $item['index'] = $k + 1;
                    $item[$credit_type] = superman_format_price($item[$credit_type]);
                }
                unset($item);
            }

            if ($_W['member']['uid']) { //logined
                //总积分
                $result = mc_credit_fetch($_W['member']['uid']);
                $all_credit = $result&&isset($result[$credit_type])?$result[$credit_type]:0;

                //月积分
                $sql = 'SELECT SUM(`num`) FROM '.tablename('mc_credits_record').' WHERE `uid`=:uid AND `credittype`=:credittype AND `createtime`>=:createtime';
                $params = array(
                    ':uid' => $_W['member']['uid'],
                    ':createtime' => strtotime(date('Y-m-1')),
                    ':credittype' => $credit_type,
                );
                $result = pdo_fetchcolumn($sql, $params);
                $month_credit = $result ? $result : 0;

                //周积分
                $sql = 'SELECT SUM(`num`) FROM '.tablename('mc_credits_record').' WHERE `uid`=:uid AND `credittype`=:credittype AND `createtime`>=:createtime';
                $params[':createtime'] = strtotime('last Monday');
                $result = pdo_fetchcolumn($sql, $params);
                $week_credit = $result ? $result : 0;

                //排名
                $sql = 'SELECT COUNT(*) FROM '.tablename('mc_members').' WHERE uniacid=:uniacid AND '.$credit_type.'>:all_credit';
                $param = array(
                    ':uniacid' => $_W['uniacid'],
                    ':all_credit' => $all_credit,
                );
                $result = pdo_fetchcolumn($sql,$param);
                $my_rank = $result ? $result : 0;
                $sql = 'SELECT COUNT(*) FROM '.tablename('mc_members').' WHERE uniacid=:uniacid AND uid<=:uid AND '.$credit_type.'=:all_credit';
                $param[':uid'] = $_W['member']['uid'];
                $result = pdo_fetchcolumn($sql,$param);
                $result = $result ? $result : 0;
                $my_rank += $result;

                $week_credit = superman_format_price($week_credit);         //去尾
                $month_credit = superman_format_price($month_credit);
                $all_credit = superman_format_price($all_credit);
            } else {    //no login
                $week_credit = 0;
                $month_credit = 0;
                $all_credit = 0;
                $my_rank = 0;
            }

            //ad list
            $filter = array(
                'uniacid' => $_W['uniacid'],
                'isshow' => 1,
                'time' => TIMESTAMP,
                'position_id' => 3,
            );
            $adlist = superman_ad_fetchall_posid($filter);
            //print_r($adlist);
        }
        include $this->template('creditrank');
    }
}

$obj = new Superman_creditmall_doMobileCreditrank;
$obj->exec();
