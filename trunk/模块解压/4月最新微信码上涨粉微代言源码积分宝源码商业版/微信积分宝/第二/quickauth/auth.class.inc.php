<?php
 class Auth{
    private static $t_module = 'modules';
    private static $common_msg = "请联系管理员授权。如果是本模块代码升级后弹出本消息，请打开[参数设置]菜单, 自助点击同步授权即可自动重新获得授权。如果您更换了域名，请联系作者绑定新域名，然后重新同步授权。<br>";
    private static $author_msg = "";
    public static $ERR_SERVER_NO_RESPONSE = 10001;
    public static $ERR_INVALID_AUTH = 10002;
    public static $ERR_NO_AUTH = 10003;
    public static $ERR_UNKNOWN = 10004;
    public static $ERR_CONN = 10005;
    public static $SUCCESS_AUTH = 0;
    //private static $AUTH_COUNT = 3;
    public function checkXAuth($op, $modulename, $version){
        $total = $this -> getInstalledCount($modulename);
        /* if ($total > self :: $AUTH_COUNT){
            $msg = "微信账号超过授权个数，如需扩展，请购买更高级版本。";
            message($msg, '', 'error');
            exit(0);
        } */
        if (0 == strcasecmp($op, 'auth')){
            list($ret, $msg) = $this -> checkRemoteAuth($modulename, $version);
            if (0 == $ret){
                $url = wurl('home/welcome/ext', array('m' => $modulename));
                message($msg, referer(), 'success');
            }else{
                global $_W;
                $re_auth_url = wurl('site/entry/auth', array('m' => $modulename, 'weid' => $_W['weid'], 'op' => 'Auth'));
                $re_auth_str = "<br><H1><a style='color:red' href='$re_auth_url'>点击这里自助授权</a></H1><br>";
                message($msg . "<br>错误码:{$ret}" . "<br><br>" . $re_auth_str . $author_msg, '', 'error');
            }
        }else{
            list($ret, $msg) = $this -> checkLocalAuth($modulename, $version);
            if (0 != $ret){
                global $_W;
                $re_auth_url = create_url('site/entry/auth', array('m' => $modulename, 'weid' => $_W['weid'], 'op' => 'Auth'));
                $re_auth_str = "<br><H1><a style='color:red' href='$re_auth_url'>点击这里自助授权</a></H1><br>";
                message($msg . "<br>错误码:{$ret}" . "<br><br>" . $re_auth_str . $author_msg, '', 'error');
            }
        }
    }
    private function checkRemoteAuth(){
        return array(self :: $SUCCESS_AUTH, '更新授权成功');
    }
    private function checkLocalAuth(){
        return array(self :: $SUCCESS_AUTH, "授权成功");
    }
    private function uni_modules($uniacid, $enabledOnly = true){
        global $_W;
        load() -> model('account');
        $account = uni_fetch($uniacid);
        $groupid = $account['groupid'];
        if (empty($groupid)){
            $modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " WHERE issystem = 1 ORDER BY issystem DESC, mid ASC", array(), 'name');
        }elseif ($groupid == '-1'){
            $modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " ORDER BY issystem DESC, mid ASC", array(), 'name');
        }else{
            $wechatgroup = pdo_fetch("SELECT `modules` FROM " . tablename('uni_group') . " WHERE id = :id", array(':id' => $groupid));
            $ms = '';
            if (!empty($wechatgroup['modules'])){
                $wechatgroup['modules'] = iunserializer($wechatgroup['modules']);
                $ms = implode("','", $wechatgroup['modules']);
                $ms = " OR `name` IN ('{$ms}')";
            }
            $modules = pdo_fetchall("SELECT * FROM " . tablename('modules') . " WHERE issystem = 1{$ms} ORDER BY issystem DESC, mid ASC", array(), 'name');
        }
        foreach($modules as $k => $v){
            if($v['issolution'] && $v['target'] != $_W['uniacid']){
                unset($modules[$k]);
            }
        }
        if (!empty($modules)){
            $ms = implode("','", array_keys($modules));
            $ms = "'{$ms}'";
            $mymodules = pdo_fetchall("SELECT `module`, `enabled`, `settings` FROM " . tablename('uni_account_modules') . " WHERE uniacid = '{$_W['uniacid']}' AND `module` IN ({$ms}) ORDER BY enabled DESC", array(), 'module');
        }
        if (!empty($mymodules)){
            foreach ($mymodules as $name => $row){
                if ($enabledOnly && !$modules[$name]['issystem']){
                    if ($row['enabled'] == 0 || empty($modules[$name])){
                        unset($modules[$name]);
                        continue;
                    }
                }
                if(!empty($row['settings'])){
                    $modules[$name]['config'] = iunserializer($row['settings']);
                }
                $modules[$name]['enabled'] = $row['enabled'];
            }
        }
        foreach ($modules as $name => & $row){
            if ($row['issystem'] == 1){
                $row['enabled'] = 1;
            }elseif (!isset($row['enabled'])){
                $row['enabled'] = 1;
            }
            if(empty($row['config'])){
                $row['config'] = array();
            }
            if(!empty($row['subscribes'])){
                $row['subscribes'] = iunserializer($row['subscribes']);
            }
            if(!empty($row['handles'])){
                $row['handles'] = iunserializer($row['handles']);
            }
            unset($modules[$name]['description']);
        }
        return $modules;
    }
    private function getInstalledCount($modulename){
        global $_W;
        $cnt = 0;
        $uniaccounts = pdo_fetchall("SELECT * FROM " . tablename('uni_account'));
        foreach ($uniaccounts as $acc){
            $modules = $this -> uni_modules($acc['uniacid']);
            if (isset($modules[$modulename])){
                $cnt++;
            }
            unset($modules);
        }
        unset($uniaccounts);
        return $cnt;
    }
}
