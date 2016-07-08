<?php

global $_GPC, $_W;
include_once (IA_ROOT.'/addons/jer_da/function/statistics.func.php');

$op = $_GPC['op'] ? $_GPC['op'] : 'list';

if($op == 'list'){
    $pindex = max(1, intval($_GPC['page']));
    $psize  = 20;
    $condition = " m.weid = :weid";
    $paras = array(':weid' => $_W['uniacid']);

    if($_GPC['is']){
        $modulename = trim($_GPC['modulename']);
        $condition .= " AND m.name LIKE '%{$modulename}%'";
    }

    $sql = 'SELECT COUNT(*) FROM ' . tablename('jer_da_module') . ' AS m WHERE ' . $condition;
    $total = pdo_fetchcolumn($sql, $paras);

    if ($total > 0) {
        if ($_GPC['export'] != 'export') {
            $limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        }

        $sql = 'SELECT m.* FROM ' . tablename('jer_da_module') . ' AS m WHERE ' . $condition . ' ORDER BY m.id DESC ' . $limit;
        $list = pdo_fetchall($sql,$paras);
        $pager = pagination($total, $pindex, $psize);
    }

    include $this->template('web/module_list');
}elseif($op == 'post'){
    $id = intval($_GPC['id']);

    if ($id > 0) {
        $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_module') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));
    }

    if (checksubmit('submit')) {
        $name = trim($_GPC['name']) ? trim($_GPC['name']) : message('请填写模块名称！');

        $insert = array(
            'weid' => $_W['uniacid'],
            'name' => $name,
            'createtime' => TIMESTAMP
        );

        if (empty($id)) {
            pdo_insert('jer_da_module', $insert);
            !pdo_insertid() ? message('保存数据失败, 请联系管理员检查.', 'error') : '';
        } else {
            unset($insert['createtime']);
            if (pdo_update('jer_da_module', $insert, array('id' => $id)) === false) {
                message('更新数据失败, 请联系管理员检查.', 'error');
            }
        }
        message('更新数据成功！', $this->createWebUrl('module', array('op' => 'list')), 'success');
    }

    include $this->template('web/module_post');
}elseif($op == 'table'){
    $ac = trim($_GPC['ac']) ? trim($_GPC['ac']) : 'list';
    $mid = intval($_GPC['mid']);

    $module_data = pdo_fetch('SELECT * FROM ' . tablename('jer_da_module') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $mid));

    if($ac == 'list'){
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $condition = " mt.weid = :weid AND mid = ".$mid;
        $paras = array(':weid' => $_W['uniacid']);

        $sql = 'SELECT COUNT(*) FROM ' . tablename('jer_da_module_table') . ' AS mt WHERE ' . $condition;
        $total = pdo_fetchcolumn($sql, $paras);

        if ($total > 0) {
            if ($_GPC['export'] != 'export') {
                $limit = ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
            }

            $sql = 'SELECT mt.* FROM ' . tablename('jer_da_module_table') . ' AS mt WHERE ' . $condition . ' ORDER BY mt.id DESC ' . $limit;
            $list = pdo_fetchall($sql,$paras);
            $pager = pagination($total, $pindex, $psize);
        }


        include $this->template('web/module_table_list');
    }elseif($ac == 'post'){
        $id = intval($_GPC['id']);

        if ($id > 0) {
            $theone = pdo_fetch('SELECT * FROM ' . tablename('jer_da_module_table') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $id));

            //获取表字段
            $database = $_W['config']['db']['database'];
            $field_list = pdo_fetchall("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$theone[tablename]' AND table_schema = '$database'");
            foreach($field_list as $key => $value){
                $field_list_arr[$key] = array_values(array_values($value));
            }
        }

        if (checksubmit('submit')) {
            $name = trim($_GPC['name']) ? trim($_GPC['name']) : message('请填写模块表名称！');
            $insert = array(
                'weid' => $_W['uniacid'],
                'mid' => $_GPC['mid'],
                'name' => $name,
                'tablename' => $_GPC['tablename'],
                'field' => $_GPC['field'],
                'timefield' => $_GPC['timefield'],
                'uniacidfield' => $_GPC['uniacidfield'],
                'status' => $_GPC['status'],
                'createtime' => TIMESTAMP
            );

            if (empty($id)) {
                pdo_insert('jer_da_module_table', $insert);
                !pdo_insertid() ? message('保存数据失败, 请联系管理员检查.', 'error') : '';
            } else {
                unset($insert['createtime']);
                if (pdo_update('jer_da_module_table', $insert, array('id' => $id)) === false) {
                    message('更新数据失败, 请联系管理员检查.', 'error');
                }
            }
            message('更新数据成功！', $this->createWebUrl('module', array('op' => 'table', 'ac' => 'list', 'mid' => $mid)), 'success');
        }

        //获取数据库所有表
        $table_list = pdo_fetchall("show tables");

        foreach($table_list as $key => $value){
            $table_list_arr[$key] = array_values(array_values($value));
        }

        include $this->template('web/module_table_post');
    }elseif($ac == 'getField'){
        $table_name = $_GPC['table_name'];

        //获取表字段
        $database = $_W['config']['db']['database'];
        $field_list = pdo_fetchall("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$table_name' AND table_schema = '$database'");
        foreach($field_list as $key => $value){
            $field_list_arr[$key] = array_values(array_values($value));
        }
        exit(json_encode($field_list_arr));
    }
}elseif($op == 'analysis'){
    $mid = intval($_GPC['mid']);
    if ($mid > 0) {
        if($_GPC['is']){
            $starttime =  strtotime($_GPC['time']['start']);
            $endtime =  strtotime($_GPC['time']['end']) + 86399;
            $day_num = round(($endtime - $starttime) / 3600 / 24) - 1;
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

        $fans_data_json = array(
            'date' => json_encode($days),
            'day_num' => $day_num,
            'follow_fans' => json_encode(array_values($follow_fans)),
            'unfollow_fans' => json_encode(array_values($unfollow_fans)),
            'nav_fans' => json_encode(array_values($nav_fans)),
            'all_fans' => json_encode(array_values($all_fans))
        );

        //模块信息
        $module_table = pdo_fetch('SELECT * FROM ' . tablename('jer_da_module') . " WHERE  weid = :weid  AND id = :id", array(':weid' => $_W['uniacid'], ':id' => $mid));
        $module_table_list = pdo_fetchall('SELECT * FROM ' . tablename('jer_da_module_table') . ' WHERE status = 1 AND mid ='.$mid);

        foreach($module_table_list as $key => $value){
            $module_table_data_seq = module_table_seq($value, $starttime, $endtime);
            $module_table_data_grand = module_table_grand($value, $module_table_data_seq, $starttime);
            $module_table_data_hour = module_table_hour($value, $starttime, $endtime);
            $fans_data_json['module_table_name'][$value['id']] = json_encode($value['name']);
            $fans_data_json['module_table_data_seq'][$value['id']] = json_encode(array_values($module_table_data_seq));
            $fans_data_json['module_table_data_grand'][$value['id']] = json_encode(array_values($module_table_data_grand));
            $fans_data_json['module_table_data_hour'][$value['id']]['key'] = json_encode($module_table_data_hour['key']);
            $fans_data_json['module_table_data_hour'][$value['id']]['value'] = json_encode($module_table_data_hour['value']);
        }

    }else{
        message('找不到指定的模块, 请联系管理员检查.', 'error');
    }

    include $this->template('web/module_analysis');
}
