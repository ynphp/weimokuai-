<?php

defined('IN_IA') or exit('Access Denied');
define('M_PATH', IA_ROOT . '/addons/wkf_yaoyiyao_money_point');
class Wkf_yaoyiyao_money_pointModuleSite extends WeModuleSite {
    public $dbwinner = "yaoyiyao_money_point_winner";   //获取纪录
    public $dbrecord = "yaoyiyao_money_point_record";   //领取纪录
    public $dbshare = "yaoyiyao_money_point_share";//分享纪录
    public $starttime = "";
    public $endtime = "";


    public function doWebApi() {
        global $_W, $_GPC;
        if(checksubmit()) {
            load()->func('file');
            mkdirs(M_PATH . '/cert');
            $r = true;
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(M_PATH . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(M_PATH . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(M_PATH . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/czt_subscribe_redpack/cert/ 目录可写');
            }
            $input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip'), $_GPC);
            $input['appid'] = trim($input['appid']);
            $input['secret'] = trim($input['secret']);
            $input['mchid'] = trim($input['mchid']);
            $input['password'] = trim($input['password']);
            $input['ip'] = trim($input['ip']);
            $setting = $this->module['config'];
            $setting['api'] = $input;
            if($this->saveSettings($setting)) {
                message('保存参数成功', 'refresh');
            }
        }
        $config = $this->module['config']['api'];
        if(empty($config['ip'])) {
            $config['ip'] = $_SERVER['SERVER_ADDR'];
        }
        include $this->template('api');
    }
    public function doWebActivity() {
        global $_W, $_GPC;
        if($_W['ispost']) {
            $input = array_elements(array('title','daynumber','sharenumber','rule','pointnum','pointlimit','attention','winnernum', 'provider', 'wish', 'remark', 'fee', 'time', 'image', 'stitle', 'content'), $_GPC);;
            $input['time']['start'] = strtotime($input['time']['start'] . ':00');
            $input['time']['end'] = strtotime($input['time']['end'] . ':59');
            $setting = $this->module['config'];
            $setting['activity'] = $input;
            if($this->saveSettings($setting)) {
                message('保存红包设置成功', 'refresh');
            }
        }

        $activity = $this->module['config']['activity'];
        if(empty($activity)) {
            $activity = array();
            $activity['fee']['downline'][0] = '0';
            $activity['fee']['upline'][0] = '1';
            $activity['fee']['probability'][0] = '100';
            $activity['fee']['model'][0] = 2;
        }
        if(!is_array($activity['fee'])) {
            $fee = $activity['fee'];
            $activity['fee'] = array();
            $activity['fee']['downline'] = $fee;
            $activity['fee']['upline'] = $fee;
        }
        if(!is_array($activity['time'])) {
            $activity['time'] = array(
                'start' => TIMESTAMP,
                'end'   => TIMESTAMP + 3600 * 24
            );
        }
        $activity['time']['start'] = date('Y-m-d H:i', $activity['time']['start']);
        $activity['time']['end'] = date('Y-m-d H:i', $activity['time']['end']);
        $activity['rule'] = htmlspecialchars_decode($activity['rule']);
        load()->func('tpl');
        include $this->template('activity');
    }


    //中奖纪录
    public function doWebWinner(){
        global $_W,$_GPC;
        $paward = max(1, intval($_GPC['page']));    //分页
        $psize = 20;
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->dbrecord).' WHERE wid='.$_W['uniacid']);
        $list = pdo_fetchall("SELECT * FROM ".tablename($this->dbrecord) ." WHERE wid = ".$_W['uniacid']." ORDER BY success_t DESC  LIMIT ".($paward - 1) * $psize.','.$psize);
        $pager = pagination($total, $paward, $psize);
        include $this->template("winner");
    }


    public function doMobileList(){
        global $_W,$_GPC;
       $openId = $_W['openid'];
        $is_attention = 1;
        if(empty($openId)){
            echo "获取用户信息失败";exit;
        }

        $activity = $this->module['config']['activity'];
        $_share = array(
            'title'=> $activity['stitle'],
            'link'=> $_W['siteroot'].$this->createMobileUrl('list'),
            'imgUrl'=>tomedia($activity['image']),
            'content'=> $activity['content'],
       );
        if(empty($_W['fans']['follow'])){//验证是否关注
            $is_attention = 0;
        }
        $shareUser = pdo_fetch("SELECT * FROM ".tablename($this->dbshare)." WHERE openid='".$openId."'");
        if(empty($shareUser)){
            $insert = array(
                'wid'=>$_W['uniacid'],
                'openid'=>$openId,
                'sharenum'=>0
            );
            pdo_insert($this->dbshare,$insert);
        }

        include $this->template('index');
    }
    /*活动规则*/
    public function doMobileRule(){
        global $_W,$_GPC;
        $activity = $this->module['config']['activity'];
        $_share = array(
            'title'=> $activity['stitle'],
            'link'=> $_W['siteroot'].$this->createMobileUrl('list'),
            'imgUrl'=>tomedia($activity['image']),
            'content'=> $activity['content'],
        );
        include $this->template('rule');
    }
    /*领取现金红包*/
    public function doMobileGetaward(){
        global $_W,$_GPC;
        $openId = $_W['openid'];
        if(empty($openId)){
            echo "获取用户信息失败";exit;
        }
        $activity = $this->module['config']['activity'];
        $_share = array(
            'title'=> $activity['stitle'],
            'link'=> $_W['siteroot'].$this->createMobileUrl('list'),
            'imgUrl'=>tomedia($activity['image']),
            'content'=> $activity['content'],
        );
        $list = pdo_fetchall("SELECT * FROM ".tablename($this->dbwinner)." WHERE openid='".$openId."' AND wid=".$_W['uniacid']." AND is_get=0");
        $allPrice = 0;
        $allPoint = 0;
        foreach($list as $item){
            if($item['model'] == 1){
                $allPrice += $item['price'];
            }else{
                $allPoint += $item['price'];
            }
        }

        if(checksubmit('submit')){
            if($allPrice<1 && $allPoint<1){
                echo "<script>alert('领取失败,金额不足!')</script>";
            }else{
                $allPoint2 = floor($allPoint);
                $allPrice2 = floor($allPrice);
                $result2 = $this->yiy_sendpoint($allPoint2);
                if($allPrice2>=1){
                    $result = $this->yiy_send($openId,($allPrice2*100));

                }else{

                    $result = true;
                }
                if($result2){
                    pdo_update($this->dbwinner,array('is_get'=>1),array('openid'=>$openId,'wid'=>$_W['uniacid'],'is_get'=>0,'model'=>2));
                    $insert2 = array(
                        'wid'=>$_W['uniacid'],
                        'openid'=>$_W['openid'],
                        'time'=>time(),
                        'is_get'=>0,
                        'price'=>$allPoint-$allPoint2,
                        'model'=>2,
                        'isshare'=>0,
                    );
                    pdo_insert($this->dbwinner,$insert2);
                }
                if($result == 1){
                    pdo_update($this->dbwinner,array('is_get'=>1),array('openid'=>$openId,'wid'=>$_W['uniacid'],'is_get'=>0,'model'=>1));
                    $insert = array(
                        'wid'=>$_W['uniacid'],
                        'openid'=>$_W['openid'],
                        'time'=>time(),
                        'is_get'=>0,
                        'price'=>$allPrice-$allPrice2,
                        'model'=>1,
                        'isshare'=>0,
                    );
                    pdo_insert($this->dbwinner,$insert);

                    echo "<script>alert('领取成功!')</script>";
                }else{
                    echo "<script>alert('{$result}')</script>";
                }
            }

            $list = pdo_fetchall("SELECT * FROM ".tablename($this->dbwinner)." WHERE openid='".$openId."' AND wid=".$_W['uniacid']." AND is_get=0");
            $allPrice = 0;
            $allPoint = 0;
            foreach($list as $item){
                if($item['model'] == 1){
                    $allPrice += $item['price'];
                }else{
                    $allPoint += $item['price'];
                }
            }
        }
        include $this->template('getaward');
    }

    public function doMobileAjaxlottery(){
        global $_W,$_GPC;
        $isshare = 0;
        $isend = 0;
        $activity = $this->module['config']['activity'];
        $this->starttime = date('Y-m-d H:i', $activity['time']['start']);
        $this->endtime = date('Y-m-d H:i', $activity['time']['end']);
        //查看用户积分
      /*  $member = pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$_W['member']['uid']);*/

        $result = array();
        $openId = $_W['openid'];
        if(empty($openId)){
            die(json_encode(array('code'=>-1)));
        }
        //活动是否结束
        if(time()<strtotime($this->starttime)){//获取还没开始
            die(json_encode(array('code'=>-2)));
        }
        if(time()>strtotime($this->endtime)){//活动已经结束
            die(json_encode(array('code'=>-3)));
        }

        $share= pdo_fetch("SELECT * FROM ".tablename($this->dbshare)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."'");
        //每天的抽奖次数
        $daylotteryCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND time BETWEEN '".strtotime(date("Y-m-d 00:00:00"))."' AND '".strtotime(date("Y-m-d 23:59:59"))."' AND isshare=0");
        $pointlotteryCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND time BETWEEN '".strtotime(date("Y-m-d 00:00:00"))."' AND '".strtotime(date("Y-m-d 23:59:59"))."' AND isshare=2");
        //分享次数
        $sharelotteryCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND time BETWEEN '".strtotime(date("Y-m-d 00:00:00"))."' AND '".strtotime(date("Y-m-d 23:59:59"))."' AND isshare=1");
        if($daylotteryCount<$activity['daynumber']){//使用本身的抽奖次数

        }else if($sharelotteryCount<$activity['sharenumber'] && $share['sharenum']>0) {//使用分享抽奖
            $isshare = 1;
            pdo_update($this->dbshare,array('sharenum'=>($share['sharenum']-1)),array('id'=>$share['id']));
        }else if($pointlotteryCount<$activity['pointlimit'] && $share['pointnum']>0){//积分抽奖
            $isshare = 2;
            pdo_update($this->dbshare,array('pointnum'=>($share['pointnum']-1)),array('id'=>$share['id']));
        }else{
            if($sharelotteryCount<$activity['sharenumber'] || $pointlotteryCount<$activity['pointlimit']){
                die(json_encode(array('code'=>-4)));
            }else{
                die(json_encode(array('code'=>-5)));
            }

        }

        /*查看用户中奖次数*/
        $winnerCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND price>0 AND time BETWEEN '".strtotime($this->starttime)."' AND '".strtotime($this->endtime)."'");
        if($winnerCount>=$activity['winnernum']){
            $randData = 0;
        }else{
            $model = 0;
            /*开始抽奖*/
            $probability = $activity['fee']['probability'];
            $result_lottery = -1;//中奖结果
            $total_probability = 0;
            $award_arr = array();
            foreach($probability as $pindex => $pvalue){
                $total_probability += $pvalue;
                $award_arr[$pindex] = $total_probability;
            }
            $result_probability = rand(0,100);
            foreach($award_arr as $aindex => $avalue){
                if($result_probability <= $award_arr[$aindex]){
                    $result_lottery = $aindex;
                    break;
                }
            }
            if($result_lottery != -1){
                $startPrice = $activity['fee']['downline'][$result_lottery]*10;
                $endPrice = $activity['fee']['upline'][$result_lottery]*10;
                $model = $activity['fee']['model'][$result_lottery];
                $randData = number_format(rand($startPrice,$endPrice)/10,2,'.','');
            }else{
                $randData = 0;
            }
        }


        //验证是否已经纪录用户
        //记录奖品数据
        $nowtime = time();
        $insert = array(
            'wid'=>$_W['uniacid'],
            'openid'=>$openId,
           'time'=>$nowtime,
            'is_get'=>0,
            'price'=>$randData,
            'model'=>$model,
            'isshare'=>$isshare,
        );

        $inresult = pdo_insert($this->dbwinner,$insert);
        $inId = pdo_fetchcolumn("SELECT id FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND time='".$nowtime."'");
        $result['uid'] = $inId;
        $result['code'] = 1;
        $result['price'] = $randData;
        $result['model'] = $model;
        die(json_encode($result));
    }
    //积分兑换摇一摇
    public function doMobileAjax_point_yiy(){
        global $_W;
        $activity = $this->module['config']['activity'];
        $member = pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$_W['member']['uid']);
        $pointlotteryCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$_W['openid']."' AND time BETWEEN '".strtotime(date("Y-m-d 00:00:00"))."' AND '".strtotime(date("Y-m-d 23:59:59"))."' AND isshare=2");
        if($pointlotteryCount>=$activity['pointlimit']){//积分兑换用完了
            die(json_encode(array('code'=>-1)));
        }
        if($member['credit1'] < $activity['pointnum']){//积分不够
            die(json_encode(array('code'=>-2)));
        }
        $point = pdo_fetch("SELECT * FROM ".tablename($this->dbshare)." WHERE wid=".$_W['uniacid']." AND openid='".$_W['openid']."'");
        pdo_update($this->dbshare,array('pointnum'=>$point['pointnum']+1),array('id'=>$point['id']));
        mc_credit_update($_W['member']['uid'],'credit1',-$activity['pointnum'],array($_W['member']['uid'],'用积分参与摇一摇'));
        die(json_encode(array('code'=>1,'pointnumber'=>($activity['pointlimit']-$pointlotteryCount-1))));
    }
    //发送积分
    protected  function yiy_sendpoint($point){
        global $_W;
        if($point>0){
           /* $member = pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid=".$_W['member']['uid']);
            pdo_update('mc_members',array('credit1'=>($member['credit1']+$point)),array('uid'=>$_W['member']['uid']));*/
            mc_credit_update($_W['member']['uid'],'credit1',$point,array($_W['member']['uid'],'参与摇一摇活动获得积分'));
            $insert = array(
                'wid'=>$_W['uniacid'],
                'openid'=>$_W['openid'],
                'nickname'=>$_W['fans']['nickname'],
                'price'=>$point,
                'model'=>2,
                'status' => 1,
                'success_t' => time(),
            );
            pdo_insert($this->dbrecord, $insert);
            return true;
        }else{
            return false;
        }
    }
    protected function yiy_send($openid,$price) {
        global $_W;
        $uniacid = $_W['uniacid'];
        $api = $this->module['config']['api'];
        $activity = $this->module['config']['activity'];
        $insert = array(
            'wid'=>$_W['uniacid'],
            'openid'=>$openid,
            'nickname'=>$_W['fans']['nickname'],
            'price'=>$price/100,
            'model'=>1,
            'success_t'=>time(),
        );
        pdo_insert($this->dbrecord,$insert);
        $record_id = pdo_insertid();
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $record_id);
        $pars['mch_id'] = $api['mchid'];
        $pars['wxappid'] = $api['appid'];
        $pars['nick_name'] =  $activity['provider'];
        $pars['send_name'] = $activity['provider'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $price;
        $pars['min_value'] = $price;
        $pars['max_value'] = $price;
        $pars['total_num'] = 1;
        $pars['wishing'] = $activity['wish'];
        $pars['client_ip'] = $api['ip'];
        $pars['act_name'] = $activity['title'];
        $pars['remark'] = $activity['remark'];
        $pars['logo_imgurl'] = tomedia($activity['image']);
        $pars['share_content'] =  $activity['content'];
        $pars['share_imgurl'] = tomedia($activity['image']);
        $pars['share_url'] = $_W['siteroot'].$this->createMobileUrl('list');

        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);

        $extras = array();
        $extras['CURLOPT_CAINFO'] = M_PATH . '/cert/rootca.pem.' . $uniacid;
        $extras['CURLOPT_SSLCERT'] = M_PATH . '/cert/apiclient_cert.pem.' . $uniacid;
        $extras['CURLOPT_SSLKEY'] = M_PATH . '/cert/apiclient_key.pem.' . $uniacid;

        load()->func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new \DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new \DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }
        if(is_error($procResult)) {
            $filters = array();
            $filters['wid'] = $uniacid;
            $filters['id'] = $record_id;
            $rec = array();
            $rec['log'] = $procResult['message'];
            pdo_update($this->dbrecord, $rec, $filters);
            return $procResult['message'];
        } else {
            $filters = array();
            $filters['wid'] = $uniacid;
            $filters['id'] = $record_id;
            $rec = array();
            $rec['status'] = 1;
            $rec['success_t'] = time();
            pdo_update($this->dbrecord, $rec, $filters);
            return 1;
        }
    }
    /*分享增加摇一摇次数*/
    public function  doMobileAjaxaddyiy(){
        global $_W,$_GPC;
        $openId = $_W['openid'];
        $activity = $this->module['config']['activity'];
        $sharelotteryCount = pdo_fetchcolumn("SELECT count(*) FROM ".tablename($this->dbwinner)." WHERE wid=".$_W['uniacid']." AND openid='".$openId."' AND time BETWEEN '".strtotime(date("Y-m-d 00:00:00"))."' AND '".strtotime(date("Y-m-d 23:59:59"))."' AND isshare=1");
        $shareNum = pdo_fetchcolumn("SELECT sharenum FROM ".tablename($this->dbshare)." WHERE openid='".$openId."'");
        if($sharelotteryCount>=$activity['sharenumber']){
            die(json_encode(array('code'=>-1)));
        }
        pdo_update($this->dbshare,array('sharenum'=>($shareNum+1)),array('openid'=>$openId));
        die(json_encode(array('code'=>1,'sharenumber'=>($activity['sharenumber']-$sharelotteryCount-1))));
    }

}