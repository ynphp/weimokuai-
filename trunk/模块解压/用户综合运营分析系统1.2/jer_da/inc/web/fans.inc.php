<?php

global $_GPC, $_W;
include_once (IA_ROOT.'/addons/jer_da/function/statistics.func.php');

$op = $_GPC['op'] ? $_GPC['op'] : 'trend';

if($op == 'trend'){

    if($_GPC['is']){
        $starttime =  strtotime($_GPC['time']['start']);
        $endtime =  strtotime($_GPC['time']['end']) + 86399;
        $day_num = round(($endtime - $starttime) / 3600 / 24);
    }else{
        $day_num = !empty($_GPC['day_num']) ? $_GPC['day_num'] : 14;
        $starttime = strtotime("-{$day_num} day");
        $endtime = time();
    }

    //关注时间
    $follow_fans_data = follow_fans($starttime,$endtime);

    //取关时间
    $unfollow_fans_data = unfollow_fans($starttime,$endtime);

    //开始时间前的累积人数
    $all_start_fans_data = all_start_fans($starttime);

    //生成日期序列
    $endtime_data = date('Y-m-d', $endtime);
    for($i = $day_num; $i >= 0; $i--){
        $key = date('Y-m-d', strtotime($endtime_data . '-' . $i . 'day'));
        $days[] = $key;
        $follow_fans[$key] = 0;
        $unfollow_fans[$key] = 0;
    }

    //格式化关注粉丝数
    foreach($follow_fans_data as $data) {
        $key1 = date('Y-m-d', $data['followtime']);
        if(in_array($key1, $days)) {
            $follow_fans[$key1]++;
        }
    }

    //格式化取关粉丝数
    foreach($unfollow_fans_data as $data) {
        $key1 = date('Y-m-d', $data['unfollowtime']);
        if(in_array($key1, $days)) {
            $unfollow_fans[$key1]++;
        }
    }

    //计算净增长粉丝数
    foreach($follow_fans as $k => $data) {
        $nav_fans[$k] = $follow_fans[$k] - $unfollow_fans[$k];
    }

    //计算累积粉丝数
    foreach($nav_fans as $k => $data) {
        $all_start_fans_data = $all_start_fans_data + $data;
        $all_fans[$k] = $all_start_fans_data;
    }


    //计算期间付费营销的用户数
    $event_list = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_event') . ' WHERE starttime BETWEEN  '.$starttime.' AND '.$endtime);
    $event_sum_money = 0;
    $event_num = 0;
    foreach($event_list as $key => $value){
        $event_sum_money += $value['money'];
        $f = follow_fans($value['starttime'], $value['endtime']);
        $n = unfollow_fans($value['starttime'], $value['endtime']);
        $event_num += count($f) - count($n);

        $d_n = round(($value['endtime'] - $value['starttime']) / 3600 / 24);
        $event_speed_list[] = round(($d_n * 24) / (count($f) - count($n)), 2);
    }
    //增长速率
    $event_speed = round(array_sum($event_speed_list) / count($event_speed_list), 2);

    //时间成本
    $time_costs = round(($day_num * 24) / array_sum($nav_fans), 2);
    //金钱成本
    $money_costs = round($event_sum_money / array_sum($nav_fans), 2);

    //计算所有营销费用
    if($event_sum_money > 0){
        $event_all_money_arr = pdo_fetch('SELECT SUM(money) AS money FROM ' . tablename('jer_da_event'));
        $event_money_acc = round($event_sum_money / $event_all_money_arr['money'] * 100, 2);
    }

    $fans_data_json = array(
        'date' => json_encode($days),
        'day_num' => $day_num,
        'follow_fans' => json_encode(array_values($follow_fans)),
        'unfollow_fans' => json_encode(array_values($unfollow_fans)),
        'nav_fans' => json_encode(array_values($nav_fans)),
        'all_fans' => json_encode(array_values($all_fans))
    );

    include $this->template('web/fans_trend');
}elseif($op == 'event'){
    $ac = trim($_GPC['ac']) ? trim($_GPC['ac']) : 'list';
    $id = intval($_GPC['id']);

    if($ac == 'list'){
        $starttime = empty($_GPC['time']['start']) ? strtotime('-30 days') : strtotime($_GPC['time']['start']);
        $endtime = empty($_GPC['time']['end']) ? TIMESTAMP + 86399 : strtotime($_GPC['time']['end']) + 86399;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $condition = " e.weid = :weid";
        $paras = array(':weid' => $_W['uniacid']);

        if($_GPC['is']){
            $eventname = trim($_GPC['eventname']);
            $condition .= " AND e.name LIKE '%{$eventname}%'";
            $condition .= ' AND ((e.starttime >= '.$starttime.' AND e.starttime <= '.$endtime.'))';
        }

        $sql = 'SELECT COUNT(*) FROM ' . tablename('jer_da_event') . ' AS e WHERE ' . $condition;
        $total = pdo_fetchcolumn($sql, $paras);

        if ($total > 0) {
            if ($_GPC['export'] != 'export') {
                $limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            }

            $sql = 'SELECT e.*,ec.name AS catename FROM ' . tablename('jer_da_event') . ' AS e LEFT JOIN '.tablename('jer_da_event_category').' AS ec ON e.cid = ec.id WHERE ' . $condition . ' ORDER BY e.id DESC ' . $limit;
            $list = pdo_fetchall($sql,$paras);
            $pager = pagination($total, $pindex, $psize);
        }

        include $this->template('web/fans_event_list');
    }elseif($ac == 'post'){

        if ($id > 0) {
            $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_event') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));
            $starttime = empty($_GPC['starttime']) ? $theone['starttime'] : strtotime($_GPC['starttime']);
            $endtime = empty($_GPC['endtime']) ? $theone['endtime'] : strtotime($_GPC['endtime']);
        }else{
            $starttime = strtotime('-30 days');
            $endtime = TIMESTAMP + 86399;
        }

        $event_category = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_event_category') . " WHERE  weid = :weid ", array(':weid' => $_W['uniacid']));

        if (checksubmit('submit')) {
            $name = trim($_GPC['name']) ? trim($_GPC['name']) : message('请填写事件名称！');
            $cid = $_GPC['cid'] ? $_GPC['cid'] : message('请选择事件分类！');

            $insert = array(
                'weid' => $_W['uniacid'],
                'cid' => $cid,
                'name' => $name,
                'content' => $_GPC['content'],
                'money' => $_GPC['money'],
                'starttime' => $starttime,
                'endtime' => $endtime,
                'createtime' => TIMESTAMP
            );

            if (empty($id)) {
                pdo_insert('jer_da_event', $insert);
                !pdo_insertid() ? message('保存数据失败, 请联系管理员检查.', 'error') : '';
            } else {
                unset($insert['createtime']);
                if (pdo_update('jer_da_event', $insert, array('id' => $id)) === false) {
                    message('更新数据失败, 请联系管理员检查.', 'error');
                }
            }
            message('更新数据成功！', $this->createWebUrl('fans', array('op' => 'event', 'ac' => 'list')), 'success');
        }

        include $this->template('web/fans_event_post');
    }elseif($ac == 'analysis'){

        $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_event') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));

        //分类
        $event_category = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_event_category') . " WHERE  weid = :weid ", array(':weid' => $_W['uniacid']));

        //关注时间
        $follow_fans_data = follow_fans($theone['starttime'],$theone['endtime']);

        //取关时间
        $unfollow_fans_data = unfollow_fans($theone['starttime'],$theone['endtime']);

        //开始时间前的累积人数
        $all_start_fans_data = all_start_fans($theone['starttime']);

        $slr_info = slr_info($theone['starttime'], $theone['endtime'], $follow_fans_data, $unfollow_fans_data, $all_start_fans_data);

        //关注时刻计算
        foreach($follow_fans_data as $key => $data){
            $follow_hour[date('H', $data['followtime'])]+= 1;
        }
        ksort($follow_hour);
        $follow_hour_name = array_keys($follow_hour);
        $follow_hour_value = array_values($follow_hour);

        //关注数,取消关注数,净增用户数
        $follow_fans_count = count($follow_fans_data);
        $unfollow_fans_count = count($unfollow_fans_data);
        $nav_fans_count = $follow_fans_count - $unfollow_fans_count;

        //时间成本
        $time_costs = round(($slr_info['day_num'] * 24) / $nav_fans_count, 2);
        //金钱成本
        $money_costs = round($theone['money'] / $nav_fans_count, 2);

        $fans_data_json = array(
            'date' => json_encode($slr_info['date']),
            'day_num' => $slr_info['day_num'],
            'follow_fans' => json_encode(array_values($slr_info['follow_fans'])),
            'unfollow_fans' => json_encode(array_values($slr_info['unfollow_fans'])),
            'nav_fans' => json_encode(array_values($slr_info['nav_fans'])),
            'bl_predicted' => json_encode(array_values($slr_info['bl_predicted'])),
            'bl_x' => json_encode(array_values($slr_info['bl_x'])),
            'follow_hour_name' => json_encode(array_values($follow_hour_name)),
            'follow_hour_value' => json_encode(array_values($follow_hour_value)),
            'follow_fans_count' => json_encode($follow_fans_count),
            'unfollow_fans_count' => json_encode($unfollow_fans_count),
            'nav_fans_count' => json_encode($nav_fans_count)
        );

        include $this->template('web/fans_event_analysis');
    }elseif($ac == 'del'){

        $temp = pdo_delete("jer_da_event", array("weid" => $_W['uniacid'], 'id' => $id));
        if ($temp == false) {
            message('抱歉，删除数据失败！', '', 'error');
        } else {
            pdo_delete("jer_da_event", array("weid" => $_W['uniacid'], 'id' => $id));
            message('删除数据成功！', $this->createWebUrl('fans', array('op' => 'event', 'ac' => 'list')), 'success');
        }
    }

}elseif($op == 'event_category'){
    //事件分类
    $ac = trim($_GPC['ac']) ? trim($_GPC['ac']) : 'list';

    if($ac == 'list'){
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;

        $list = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_event_category') . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . "," . $psize);

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('jer_da_event_category'));
        $pager = pagination($total, $pindex, $psize);

        include $this->template('web/fans_event_category_list');
    }elseif($ac == 'post') {
        $id = intval($_GPC['id']);

        if ($id > 0) {
            $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_event_category') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));
        }

        if (checksubmit('submit')) {
            $name = trim($_GPC['name']) ? trim($_GPC['name']) : message('分类名称！');
            $insert = array(
                'weid' => $_W['uniacid'],
                'name' => $name,
                'createtime' => TIMESTAMP
            );
            if (empty($id)) {
                pdo_insert('jer_da_event_category', $insert);
                !pdo_insertid() ? message('保存数据失败, 请联系管理员检查.', 'error') : '';
            } else {
                if (pdo_update('jer_da_event_category', $insert, array('id' => $id)) === false) {
                    message('更新数据失败, 请联系管理员检查.', 'error');
                }
            }
            message('更新数据成功！', $this->createWebUrl('fans', array('op' => 'event_category', 'ac' => 'list')), 'success');
        }
        include $this->template('web/fans_event_category_post');
    }elseif($ac == 'del'){
        $id = intval($_GPC['id']);

        $temp = pdo_delete("jer_da_event_category", array("weid" => $_W['uniacid'], 'id' => $id));
        if ($temp == false) {
            message('抱歉，删除数据失败！', '', 'error');
        } else {
            pdo_delete("jer_da_event_category", array("weid" => $_W['uniacid'], 'id' => $id));
            message('删除数据成功！', $this->createWebUrl('fans', array('op' => 'event_category', 'ac' => 'list')), 'success');
        }
    }
}elseif($op == 'getEvent'){
    $cid = $_GPC['cid'];
    $tid = $_GPC['tid'];

    //获取事件列表
    $event_list = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_event') . ' WHERE cid = '.$cid.' AND NOT id = '.$tid.' ORDER BY id DESC ');
    exit(json_encode(array('event_list' => $event_list)));
}elseif($op == 'addCompared'){
    $id = $_GPC['id'];

    $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_event') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));

    //关注时间
    $follow_fans_data = follow_fans($theone['starttime'],$theone['endtime']);

    //取关时间
    $unfollow_fans_data = unfollow_fans($theone['starttime'],$theone['endtime']);

    //开始时间前的累积人数
    $all_start_fans_data = all_start_fans($theone['starttime']);

    $slr_info = slr_info($theone['starttime'], $theone['endtime'], $follow_fans_data, $unfollow_fans_data, $all_start_fans_data);

    $fans_data_json = array(
        'id' => $theone['id'],
        'name' => $theone['name'],
        'date' => $slr_info['date'],
        'day_num' =>$slr_info['day_num'],
        'follow_fans' => array_values($slr_info['follow_fans']),
        'unfollow_fans' => array_values($slr_info['unfollow_fans']),
        'nav_fans' => array_values($slr_info['nav_fans']),
        'bl_predicted' => array_values($slr_info['bl_predicted']),
        'bl_x' => array_values($slr_info['bl_x']),
    );

    exit(json_encode($fans_data_json));
}


