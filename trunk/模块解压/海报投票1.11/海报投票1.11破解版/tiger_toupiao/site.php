<?php

defined ('IN_IA') or exit ('Access Denied');
class Tiger_toupiaoModuleSite extends WeModuleSite{
    public $table_request = "tiger_toupiao_request";
    public $table_goods = "tiger_toupiao_goods";
    public $table_ad = "tiger_toupiao_ad";
    private static $t_sys_member = 'mc_members';
    public function getmainhuo(){
        $host = $_SERVER['HTTP_HOST'];
        $host = strtolower($host);
        if(strpos($host, '/') !== false){
            $parse = @parse_url($host);
            $host = $parse['host'];
        }
        $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'wang', 'cc', 'me');
        $str = '';
        foreach($topleveldomaindb as $v){
            $str .= ($str ? '|' : '') . $v;
        }
        $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
        if(preg_match("/" . $matchstr . "/ies", $host, $matchs)){
            $domain = $matchs['0'];
        }else{
            $domain = $host;
        }
        return $domain;
    }
    public function doWebMPoster(){
        $this -> getsq();
        global $_W, $_GPC;
        $do = 'mposter';
        if ('delete' == $_GPC['op'] && $_GPC['pid']){
            $rid = pdo_fetchcolumn('select rid from ' . tablename($this -> modulename . "_poster") . " where id='{$_GPC['pid']}'");
            if (pdo_delete($this -> modulename . "_poster", array('id' => $_GPC['pid'])) === false){
                message ('删除海报失败！');
            }else{
                $shares = pdo_fetchall('select id from ' . tablename($this -> modulename . "_share") . " where pid='{$_GPC['pid']}'");
                foreach ($shares as $value){
                    @unlink(str_replace('#sid#', $value['id'], "../addons/junsion_poster/qrcode/mposter#sid#.jpg"));
                }
                pdo_delete('rule', array('id' => $rid));
                pdo_delete('rule_keyword', array('rid' => $rid));
                pdo_delete($this -> modulename . "_share", array('pid' => $_GPC['pid']));
                pdo_delete('qrcode', array('name' => $title, 'uniacid' => $_W['uniacid']));
                message ('删除海报成功！', $this -> createWebUrl ('mposter'));
            }
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        if($_W['account']['level'] == 3){
            $type = 3;
        }elseif($_W['account']['level'] == 4){
            $type = 2;
        }
        $list = pdo_fetchall("select *,(select count(*) from " . tablename($this -> modulename . "_share") . " where pid=p.id) as scan from " . tablename($this -> modulename . "_poster") . " p where weid='{$_W['uniacid']}' and type='{$type}' LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_poster') . " where weid='{$_W['uniacid']}'");
        $pager = pagination($total, $pindex, $psize);
        include $this -> template ('mlist');
    }
    public function doWebMCreate(){
        $this -> getsq();
        global $_W, $_GPC;
        $do = 'mcreate';
        $op = $_GPC ['op'];
        $pid = $_GPC ['pid'];
        $item = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where id='{$pid}'");
        if (checksubmit ()){
            $ques = $_GPC['ques'];
            $answer = $_GPC['answer'];
            $questions = '';
            foreach ($ques as $key => $value){
                if (empty($value)) continue;
                $questions[] = array('question' => $value, 'answer' => $answer[$key]);
            }
            $data = array ('title' => $_GPC ['title'], 'type' => $_GPC ['type'], 'fans_type' => $_GPC ['fans_type'], 'bg' => $_GPC ['bg'], 'data' => htmlspecialchars_decode($_GPC ['data']), 'weid' => $_W ['uniacid'], 'score' => $_GPC ['score'], 'cscore' => $_GPC ['cscore'], 'pscore' => $_GPC ['pscore'], 'scorehb' => $_GPC ['scorehb'], 'cscorehb' => $_GPC ['cscorehb'], 'pscorehb' => $_GPC ['pscorehb'], 'rscore' => $_GPC ['rscore'], 'gid' => $_GPC ['gid'], 'kdtype' => $_GPC ['kdtype'], 'winfo1' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['winfo1']), ENT_QUOTES), 'winfo2' => $_GPC ['winfo2'], 'winfo3' => $_GPC ['winfo3'], 'stitle' => serialize($_GPC ['stitle']), 'sthumb' => serialize($_GPC ['sthumb']), 'sdesc' => serialize($_GPC ['sdesc']), 'rtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['rtips']), ENT_QUOTES), 'ftips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['ftips']), ENT_QUOTES), 'utips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['utips']), ENT_QUOTES), 'utips2' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['utips2']), ENT_QUOTES), 'wtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['wtips']), ENT_QUOTES), 'nostarttips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['nostarttips']), ENT_QUOTES), 'endtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC ['endtips']), ENT_QUOTES), 'starttime' => strtotime($_GPC['starttime']), 'endtime' => strtotime($_GPC['endtime']), 'surl' => serialize($_GPC ['surl']), 'kword' => $_GPC ['kword'], 'credit' => $_GPC ['credit'], 'doneurl' => $_GPC ['doneurl'], 'tztype' => $_GPC ['tztype'], 'slideH' => $_GPC ['slideH'], 'mbcolor' => $_GPC ['mbcolor'], 'mbstyle' => $_GPC ['mbstyle'], 'mbfont' => $_GPC ['mbfont'], 'sliders' => $_GPC ['sliders'], 'mtips' => $_GPC ['mtips'], 'sharetitle' => $_GPC ['sharetitle'], 'sharethumb' => $_GPC ['sharethumb'], 'sharedesc' => $_GPC ['sharedesc'], 'sharegzurl' => $_GPC ['sharegzurl'], 'tzurl' => $_GPC ['tzurl'], 'questions' => serialize($questions), 'createtime' => time(),);
            if ($pid){
                if (pdo_update ($this -> modulename . "_poster", $data, array ('id' => $pid)) === false){
                    message ('更新海报失败！1');
                }else{
                    if (empty($item['rid'])){
                        $this -> createRule($_GPC['kword'], $pid);
                    }elseif ($item['kword'] != $data['kword']){
                        pdo_update('rule_keyword', array('content' => $data['kword']), array('rid' => $item['rid']));
                        pdo_update('qrcode', array('keyword' => $data['kword']), array('name' => $this -> modulename . $pid, 'keyword' => $item['kword']));
                    }
                    message ('更新海报成功！2', $this -> createWebUrl ('mposter'));
                }
            }else{
                $data['rtype'] = $_GPC['rtype'];
                $data ['createtime'] = time ();
                if (pdo_insert ($this -> modulename . "_poster", $data) === false){
                    message ('生成海报失败！3');
                }else{
                    $this -> createRule($_GPC['kword'], pdo_insertid());
                    $this -> createRuletp();
                    message ('生成海报成功！4', $this -> createWebUrl ('mposter'));
                }
            }
        }
        load () -> func ('tpl');
        if ($item){
            $data = json_decode(str_replace('&quot;', "'", $item['data']), true);
            $size = getimagesize(toimage($item['bg']));
            $size = array($size[0] / 2, $size[1] / 2);
            $date = array('start' => date('Y-m-d H:i:s', $item['starttime']), 'end' => date('Y-m-d H:i:s', $item['endtime']));
            $titles = unserialize($item['stitle']);
            $thumbs = unserialize($item['sthumb']);
            $sdesc = unserialize($item['sdesc']);
            $surl = unserialize($item['surl']);
            foreach ($titles as $key => $value){
                if (empty($value)) continue;
                $slist[] = array('stitle' => $value, 'sdesc' => $sdesc[$key], 'sthumb' => $thumbs[$key], 'surl' => $surl[$key]);
            }
        }else $date = array('start' => date('Y-m-d H:i:s', time()), 'end' => date('Y-m-d H:i:s', time() + 7 * 24 * 3600));
        $groups = pdo_fetchall('select * from ' . tablename('mc_groups') . " where uniacid='{$_W['uniacid']}' order by isdefault desc");
        include $this -> template ('mcreate');
    }
    private function createRule($kword, $pid){
        global $_W;
        $rule = array('uniacid' => $_W['uniacid'], 'name' => $kword, 'module' => $this -> modulename, 'status' => 1, 'displayorder' => 254,);
        pdo_insert('rule', $rule);
        unset($rule['name']);
        $rule['type'] = 1;
        $rule['rid'] = pdo_insertid();
        $rule['content'] = $kword;
        pdo_insert('rule_keyword', $rule);
        pdo_update($this -> modulename . "_poster", array('rid' => $rule['rid']), array('id' => $pid));
    }
    private function createRuletp(){
        global $_W;
        $rule = array('uniacid' => $_W['uniacid'], 'name' => '我要投票', 'module' => $this -> modulename, 'status' => 1, 'displayorder' => 254,);
        pdo_insert('rule', $rule);
        unset($rule['name']);
        $rule['type'] = 1;
        $rule['rid'] = pdo_insertid();
        $rule['content'] = '我要投票';
        pdo_insert('rule_keyword', $rule);
    }
    public function doWebDianyuan(){
        $this -> getsq();
        global $_W, $_GPC;
        $do = 'dianyuan';
        include $this -> template ('dianyuan');
    }
    public function doWebDianyuandel(){
        global $_W, $_GPC;
        $del = pdo_delete($this -> modulename . "_dianyuan", array('id' => $_GPC['id']));
        if($del){
            message ('删除成功', $this -> createWebUrl ('dianyuangl'));
        }
    }
    public function doWebDianyuangl(){
        global $_W, $_GPC;
        $do = 'dianyuangl';
        $list = pdo_fetchall("select * from" . tablename($this -> modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' order by id desc");
        include $this -> template ('dianyuangl');
    }
    public function doWebHexiao(){
        $this -> getsq();
        global $_W, $_GPC;
        $do = 'hexiao';
        $list = pdo_fetchall("select * from" . tablename($this -> modulename . "_hexiao") . " where weid='{$_W['uniacid']}' order by id desc");
        include $this -> template ('hexiao');
    }
    public function doMobileHexiao(){
        global $_W, $_GPC;
        $password = $_GPC['password'];
        if($password){
            $clerk = pdo_fetch("select * from" . tablename($this -> modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' and password='{$password}'");
            if($clerk){
                $data = array ('weid' => $_W ['uniacid'], 'dianyanid' => $clerk ['dianyanid'], 'openid' => $_GPC ['openid'], 'nickname' => $_GPC ['nickname'], 'ename' => $clerk ['ename'], 'companyname' => $clerk ['companyname'], 'goodname' => $_GPC ['goodname'], 'goodid' => $_GPC ['goodid'], 'createtime' => time());
                pdo_insert ($this -> modulename . "_hexiao", $data);
                $dataab = array('status' => 'done');
                $id = intval($_GPC ['goodid']);
                if(pdo_update($this -> table_request, $dataab, array('id' => $id))){
                    message('消费成功', $this -> createMobileUrl('request'));
                }else{
                    message('消费失败', $this -> createMobileUrl('request'), 'error');
                }
            }else{
                message('密码填写错误', $this -> createMobileUrl('request'), 'error');
            }
        }else{
            message('请填写消费密码', $this -> createMobileUrl('request'), 'error');
        }
    }
    public function doWebDianyuanadd(){
        global $_W, $_GPC;
        $do = 'dianyuanadd';
        $id = $_GPC['id'];
        $op = $_GPC['op'];
        if($id){
            $clerk = pdo_fetch("select * from" . tablename($this -> modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' and id={$id}");
        }
        if($op == 'adde'){
            $list = pdo_fetchall("select * from" . tablename($this -> modulename . "_dianyuan") . " where password='{$_GPC['password']}'");
            if($list){
                message ('店员密码不能重复', $this -> createWebUrl ('dianyuanadd'), 'error');
            }
            $data = array ('weid' => $_W ['uniacid'], 'openid' => $_GPC ['openid'], 'nickname' => $_GPC ['nickname'], 'ename' => $_GPC ['ename'], 'tel' => $_GPC ['tel'], 'password' => $_GPC ['password'], 'companyname' => $_GPC ['companyname'], 'nickname' => $_GPC ['nickname'], 'createtime' => time());
            if($id){
                if(pdo_update($this -> modulename . "_dianyuan", $data, array ('id' => $id))){
                    message ('编辑成功！', $this -> createWebUrl ('dianyuangl'));
                }else{
                    message ('添加失败！');
                }
            }
            if(pdo_insert ($this -> modulename . "_dianyuan", $data)){
                message ('添加成功！', $this -> createWebUrl ('dianyuangl'));
            }else{
                message ('添加失败！');
            }
        }
        include $this -> template ('dianyuangl');
    }
    public function doWebRecord(){
        $this -> getsq();
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("select * from " . tablename($this -> modulename . "_record") . " where pid='{$pid}' LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        load() -> model('mc');
        foreach ($list as $key => $value){
            $mc = mc_fetch($value['openid']);
            $list[$key]['nickname'] = $mc['nickname'];
            $list[$key]['avatar'] = $mc['avatar'];
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_record') . " where pid='{$pid}'");
        $pager = pagination($total, $pindex, $psize);
        include $this -> template ('record');
    }
    public function doWebShare(){
        $this -> getsq();
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $cid = $_GPC['cid'];
        $pid = $_GPC['pid'];
        $name = $_GPC['name'];
        $uid = $_GPC['uid'];
        $weid = $_W['uniacid'];
        $status = intval($_GPC['status']);
        if (!empty($name)) $where = " and (nickname like '%{$name}%' or openid = '{$name}') ";
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("select id,bbcimg,province,city,createtime,nickname,openid,helpid,count(id) as sum  from " . tablename('tiger_toupiao_share') . " where weid='{$_W['uniacid']}' and openid<>'' and tp=1 and status={$status} {$where} group by helpid order by count(id) desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        foreach ($list as $key => $value){
            if(empty($value['helpid'])){
                continue;
            }
            $tpfans = pdo_fetch('select * from ' . tablename($this -> modulename . "_share") . " where openid='{$value['helpid']}' and weid='{$_W['uniacid']}'");
            $mlist[$key]['id'] = $value['id'];
            $mlist[$key]['nickname'] = $tpfans['nickname'];
            $mlist[$key]['avatar'] = $tpfans['avatar'];
            $mlist[$key]['bbimg'] = $tpfans['bbimg'];
            $mlist[$key]['bbcimg'] = $tpfans['bbcimg'];
            $mlist[$key]['sum'] = $value['sum'];
            $mlist[$key]['openid'] = $tpfans['openid'];
            $mlist[$key]['province'] = $tpfans['province'];
            $mlist[$key]['city'] = $tpfans['city'];
            $mlist[$key]['createtime'] = $tpfans['createtime'];
        }
        $total = pdo_fetchcolumn("SELECT COUNT(nickname) FROM " . tablename($this -> modulename . '_share') . " where weid='{$_W['uniacid']}' and openid<>'' and tp=1 and status={$status} {$where}");
        $pager = pagination($total, $pindex, $psize);
        include $this -> template ('share');
    }
    public function doWebShareaa(){
        $this -> getsq();
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $cid = $_GPC['cid'];
        $pid = $_GPC['pid'];
        $name = $_GPC['name'];
        $uid = $_GPC['uid'];
        $weid = $_W['uniacid'];
        $status = intval($_GPC['status']);
        if (!empty($sid)){
            $where = " and helpid='{$sid}'";
        }elseif (!empty($cid)){
            $c = pdo_fetchall('select openid from ' . tablename($this -> modulename . "_share") . " where weid='{$_W['uniacid']}' and helpid='{$cid}'", array(), 'openid');
            $fid = implode(',', array_keys($c));
            if(!$fid){
                $fid = '999999999';
            }
            $where = " and weid='{$_W['uniacid']}' and helpid in (" . $fid . ")";
        }
        if (!empty($name)) $where .= " and (nickname like '%{$name}%' or openid = '{$name}') ";
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $credit = pdo_fetchcolumn('select credit from ' . tablename($this -> modulename . "_poster") . " where id='{$pid}'");
        $credit = $credit?'credit2':'credit1';
        $list = pdo_fetchall("select *,(select credit1 from " . tablename('mc_members') . " where uid=s.openid ) as surplus,(select followtime from " . tablename('mc_mapping_fans') . " where uid=s.openid and follow='1') as follow from " . tablename($this -> modulename . "_share") . " s where openid<>'' and weid='{$_W['uniacid']}' and status={$status} {$where} order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        load() -> model('mc');
        foreach ($list as $key => $value){
            $mc = mc_fetch($value['openid']);
            $list[$key]['nickname'] = $mc['nickname'];
            $list[$key]['avatar'] = $mc['avatar'];
            if (empty($value['province'])){
                $list[$key]['province'] = $mc['resideprovince'];
                $list[$key]['city'] = $mc['residecity'];
                pdo_update($this -> modulename . "_share", array('province' => $mc['resideprovince'], 'city' => $mc['residecity']), array('id' => $value['id']));
            }
            $c = pdo_fetchall('select openid from ' . tablename($this -> modulename . "_share") . " where weid='{$_W['uniacid']}' and openid<>'' and helpid='{$value['openid']}'", array(), 'openid');
            $list[$key]['l1'] = count($c);
            if ($c){
                $list[$key]['l2'] = pdo_fetchcolumn('select count(id) from ' . tablename($this -> modulename . "_share") . " where weid='{$_W['uniacid']}' and openid<>'' and helpid in (" . implode(',', array_keys($c)) . ")");
            }else $list[$key]['l2'] = 0;
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this -> modulename . '_share') . " where weid='{$_W['uniacid']}' and openid<>'' and status={$status} {$where}");
        $pager = pagination($total, $pindex, $psize);
        $type = pdo_fetchcolumn("select type from " . tablename($this -> modulename . "_poster") . " where weid='{$weid}' ");
        include $this -> template ('share');
    }
    public function postfansorders($fans_id){
        global $_W, $_GPC;
        $dshouhuo = pdo_fetch("SELECT SUM(yongjin) tx  FROM " . tablename('tiger_toupiao_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=1");
        $fans['dsh'] = $dshouhuo['tx'];
        if(empty($fans['dsh'])){
            $fans['dsh'] = '0.00';
        }
        $dshouhuo = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_toupiao_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        if(empty($fans['ysh'])){
            $fans['ysh'] = '0.00';
        }
        return $fans;
    }
    public function postfanscont($uid){
        global $_W, $_GPC;
        $weid = $_W['weid'];
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $fans1 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
        $count1 = count($fans1);
        if(!empty($fans1)){
            $fans2 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $count2 = count($fans2);
            if(empty($count2)){
                $count2 = 0;
            }
        }
        if(!empty($fans2)){
            $fans3 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")", array(), 'openid');
            $count3 = count($fans3);
            if(empty($count3)){
                $count3 = 0;
            }
        }
        $fcont = array('count1' => $count1, 'count2' => $count2, 'count3' => $count3);
        return $fcont;
    }
    public function doWebStatus(){
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $pid = $_GPC['pid'];
        if ($_GPC['status']){
            if (pdo_update($this -> modulename . "_share", array('status' => 0), array('id' => $sid)) === false){
                message('恢复失败！');
            }else message('恢复成功！', $this -> createWebUrl('share', array('pid' => $pid, 'status' => 1)));
        }else{
            if (pdo_update($this -> modulename . "_share", array('status' => 1), array('id' => $sid)) === false){
                message('拉黑失败！');
            }else message('拉黑成功！', $this -> createWebUrl('share', array('pid' => $pid)));
        }
    }
    public function doWebDelete(){
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $pid = $_GPC['pid'];
        pdo_delete($this -> modulename . "_share", array('id' => $sid));
        pdo_update($this -> modulename . "_share", array('helpid' => 0), array('helpid' => $sid));
        message('删除成功！', $this -> createWebUrl('share', array('pid' => $pid, 'status' => $_GPC['status'])));
    }
    public function doMobileScore(){
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $openid = $_W['fans']['from_user'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
            $openid = 'opk4HsyhyQpJvVAUhA6JGhdMSImo';
        }
        $pid = $_GPC['pid'];
        $items = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where id='{$pid}'");
        $name = $items['credit']?'余额':'积分';
        if (empty($items) && $items['type'] != 1) die('扫码送' . $name . '活动已经结束！');
        $sliders = unserialize($items['sliders']);
        $atimes = '';
        foreach ($sliders as $key => $value){
            $atimes[$key] = $value['displayorder'];
        }
        array_multisort($atimes, SORT_NUMERIC, SORT_DESC, $sliders);
        $follow = pdo_fetchcolumn('select follow from ' . tablename("mc_mapping_fans") . " where openid='{$openid}'");
        $record = pdo_fetch('select * from ' . tablename($this -> modulename . "_record") . " where openid='{$openid}' and pid='{$pid}'");
        $items['score3'] = $items['score'];
        if ($items['score2']){
            $items['score1'] = $items['score'] . "—" . $items['score2'] . " ";
            $items['score3'] = intval(mt_rand($items['score'], $items['score2']));
        }
        $cfg = $this -> module['config'];
        include $this -> template ('qrcode');
    }
    public function doMobileTprank(){
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $fans = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $mc = mc_fetch($_W['openid']);
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $tpsum = pdo_fetch("select count(id) as a from " . tablename($this -> modulename . "_share") . " where weid='{$_W['uniacid']}' and helpid='{$mc['uid']}' and tp=1 order by createtime desc limit 20");
        $tpcou = $tpsum['a'];
        $list = pdo_fetchall("select helpid,count(nickname) as sum  from " . tablename('tiger_toupiao_share') . " where weid='{$_W['uniacid']}' and tp=1 group by helpid order by count(nickname) desc");
        foreach ($list as $key => $value){
            if(empty($value['helpid'])){
                continue;
            }
            $tpfans = pdo_fetch('select * from ' . tablename($this -> modulename . "_share") . " where openid='{$value['helpid']}' and weid='{$_W['uniacid']}'");
            $mlist[$key]['nickname'] = $tpfans['nickname'];
            $mlist[$key]['avatar'] = $tpfans['avatar'];
            $mlist[$key]['bbimg'] = $tpfans['bbimg'];
            $mlist[$key]['sum'] = $value['sum'];
        }
        $mbstyle = 'style2';
        include $this -> template ($mbstyle . '/tprank');
    }
    public function doMobileTxranking(){
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit2';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
            if (empty($count2)) $count2 = 0;
        }
        $sumcount = $count;
        $rank = $poster['slideH'];
        if(empty($rank)){
            $rank = 20;
        }
        $cfg = $this -> module['config'];
        $shares = pdo_fetchall("select m.nickname,m.avatar,m.credit2,m.uid from" . tablename('mc_members') . " m inner join " . tablename('mc_mapping_fans') . " f on m.uid=f.uid and f.follow=1 and f.uniacid='{$weid}' order by m.credit2 desc limit {$rank}");
        foreach($shares as $k => $v){
            $txsum = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num', array(':uniacid' => $_W['uniacid'], ':uid' => $shares[$k]['uid'], ':credittype' => 'credit2', ':num' => 0));
            if(empty($txsum['tx'])){
                $shares[$k]['credit3'] = 0;
            }else{
                $shares[$k]['credit3'] = $txsum['tx'] * -1;
            }
        }
        $cfg = $this -> module['config'];
        if($cfg['paihang'] == 1){
            foreach ($shares as $key => $value){
                $nickname[$key] = $value['nickname'];
                $avatar[$key] = $value['avatar'];
                $credit2[$key] = $value['credit2'];
                $uid[$key] = $value['uid'];
                $credit3[$key] = $value['credit3'];
            }
            array_multisort($credit3, SORT_NUMERIC, SORT_DESC, $uid, SORT_STRING, SORT_ASC, $shares);
        }
        $mbstyle = $poster['mbstyle'];
        include $this -> template ('tixian/txranking');
    }
    public function doMobileHbshare(){
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $weid = $_W['uniacid'];
        $cfg = $this -> module['config'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $type = $_GPC['type'];
        $id = $_GPC['id'];
        $img = $_W['siteroot'] . 'addons/tiger_toupiao/qrcode/mposter' . $id . '.jpg';
        $mbstyle = $poster['mbstyle'];
        include $this -> template ($mbstyle . '/hbshare');
    }
    public function doMobileRecords(){
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        $count2 = 0;
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
            if (empty($count2)) $count2 = 0;
        }
        $cfg = $this -> module['config'];
        $sumcount = $count;
        $cfg = $this -> module['config'];
        $records = pdo_fetchall('select * from ' . tablename('mc_credits_record') . " where uid='{$fans['uid']}' and credittype='credit1' order by createtime desc limit 20");
        $mbstyle = $poster['mbstyle'];
        include $this -> template ($mbstyle . '/records');
    }
    public function doMobileTxrecords(){
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit2';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)){
            $count2 = 0;
        }
        $count = count($fans1);
        $sumcount = $count;
        $cfg = $this -> module['config'];
        $records = pdo_fetchall('select * from ' . tablename('mc_credits_record') . " where uid='{$fans['uid']}' and credittype='credit2' order by createtime desc limit 20");
        $mbstyle = $poster['mbstyle'];
        include $this -> template ('tixian/txrecords');
    }
    public function doMobileMFan1(){
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $uid = $_GPC['uid'];
        $level = $_GPC['level'];
        $cfg = $this -> module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)){
            $count2 = 0;
        }
        $sumcount = $count;
        $credittype = 'credit1';
        if ($poster['credit']){
            $credittype = 'credit2';
        }
        $fans1 = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,s.openid,m.createtime from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$uid}' and f.follow=1 order by m.{$credittype} desc");
        $mbstyle = $poster['mbstyle'];
        include $this -> template ($mbstyle . '/mfan1');
    }
    public function doMobileTxmfan1(){
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $uid = $_GPC['uid'];
        $fans['uid'] = $_GPC['uid'];
        $weid = $_W['weid'];
        $level = $_GPC['level'];
        $cfg = $this -> module['config'];
        $op = $_GPC['op'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $fans1 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
        $count1 = count($fans1);
        if(empty($count1)){
            $count1 = 0;
            $count2 = 0;
            $count3 = 0;
        }
        if(!empty($fans1)){
            $fans2 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $count2 = count($fans2);
            if(empty($count2)){
                $count2 = 0;
            }
        }
        if(!empty($fans2)){
            $fans3 = pdo_fetchall("select openid from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")", array(), 'openid');
            $count3 = count($fans3);
            if(empty($count3)){
                $count3 = 0;
            }
        }
        if($op == 1){
            $txrank = pdo_fetchall("select * from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}' order by createtime desc limit 20");
        }elseif($op == 2){
            if(!empty($fans1)){
                $txrank = pdo_fetchall("select * from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ") limit 20");
            }
        }elseif($op == 3){
            if(!empty($fans2)){
                $txrank = pdo_fetchall("select * from " . tablename($this -> modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ") limit 20");
            }
        }
        $mbstyle = $poster['mbstyle'];
        include $this -> template ('tixian/txmfan1');
    }
    public function doMobileMFan2(){
        global $_W, $_GPC;
        $pid = $_GPC['pid'];
        $uid = $_GPC['uid'];
        $weid = $_GPC['i'];
        $level = $_GPC['level'];
        $cfg = $this -> module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)){
            $count2 = 0;
        }
        $sumcount = $count;
        $credittype = 'credit1';
        if ($poster['credit']){
            $credittype = 'credit2';
        }
        $fans1 = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,s.openid from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$uid}' and f.follow=1 order by m.{$credittype} desc");
        $ids = array();
        foreach ($fans1 as $value){
            $ids[] = $value['openid'];
        }
        if ($ids && $level == 1){
            $fans2 = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,m.createtime from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', $ids) . ") and f.follow=1 order by m.{$credittype} desc");
        }
        $mbstyle = $poster['mbstyle'];
        include $this -> template ($mbstyle . '/mfan2');
    }
    public function doWebGoods(){
        $this -> getsq();
        global $_W;
        global $_GPC;
        load () -> func ('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post'){
            $goods_id = intval($_GPC['goods_id']);
            if (!empty($goods_id)){
                $item = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = :goods_id" , array(':goods_id' => $goods_id));
                if (empty($item)){
                    message('抱歉，兑换商品不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['title'])){
                    message('请输入兑换商品名称！');
                }
                if (empty($_GPC['cost'])){
                    message('请输入兑换商品需要消耗的积分数量！');
                }
                if (empty($_GPC['price'])){
                    message('请输入商品实际价值！');
                }
                $cost = intval($_GPC['cost']);
                $price = intval($_GPC['price']);
                $vip_require = intval($_GPC['vip_require']);
                $amount = intval($_GPC['amount']);
                $type = intval($_GPC['type']);
                $per_user_limit = intval($_GPC['per_user_limit']);
                $data = array('weid' => $_W['weid'], 'title' => $_GPC['title'], 'px' => $_GPC['px'], 'shtype' => $_GPC['shtype'], 'logo' => $_GPC['logo'], 'starttime' => strtotime($_GPC ['starttime']), 'endtime' => strtotime($_GPC ['endtime']), 'amount' => $amount, 'cardid' => $_GPC['cardid'], 'per_user_limit' => intval($per_user_limit), 'vip_require' => $vip_require, 'cost' => $cost, 'day_sum' => $_GPC['day_sum'], 'price' => $price, 'type' => $type, 'hot' => $_GPC['hot'], 'hotcolor' => $_GPC['hotcolor'], 'dj_link' => $_GPC['dj_link'], 'content' => $_GPC['content'], 'createtime' => TIMESTAMP,);
                if (!empty($goods_id)){
                    pdo_update($this -> table_goods, $data, array('goods_id' => $goods_id));
                }else{
                    pdo_insert($this -> table_goods, $data);
                }
                message('商品更新成功！', $this -> createWebUrl('goods', array('op' => 'display')), 'success');
            }
        }else if ($operation == 'delete'){
            $goods_id = intval($_GPC['goods_id']);
            $row = pdo_fetch("SELECT goods_id FROM " . tablename($this -> table_goods) . " WHERE goods_id = :goods_id", array(':goods_id' => $goods_id));
            if (empty($row)){
                message('抱歉，商品' . $goods_id . '不存在或是已经被删除！');
            }
            pdo_delete($this -> table_goods, array('goods_id' => $goods_id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'display'){
            if (checksubmit()){
                if (!empty($_GPC['displayorder'])){
                    foreach ($_GPC['displayorder'] as $id => $displayorder){
                        pdo_update($this -> table_goods, array('displayorder' => $displayorder), array('goods_id' => $id));
                    }
                    message('排序更新成功！', referer(), 'success');
                }
            }
            $condition = '';
            $list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}'  ORDER BY px ASC");
        }
        include $this -> template('goods');
    }
    public function doWebAd(){
        $this -> getsq();
        global $_W;
        global $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post'){
            $id = intval($_GPC['id']);
            if (!empty($id)){
                $item = pdo_fetch("SELECT * FROM " . tablename($this -> table_ad) . " WHERE id = :id" , array(':id' => $id));
                if (empty($item)){
                    message('抱歉，广告不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['title'])){
                    message('请输入广告名称！');
                }
                $data = array('weid' => $_W['weid'], 'title' => $_GPC['title'], 'url' => $_GPC['url'], 'pic' => $_GPC['pic'], 'createtime' => TIMESTAMP,);
                if (!empty($id)){
                    pdo_update($this -> table_ad, $data, array('id' => $id));
                }else{
                    pdo_insert($this -> table_ad, $data);
                }
                message('广告更新成功！', $this -> createWebUrl('ad', array('op' => 'display')), 'success');
            }
        }else if ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_ad) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，广告' . $id . '不存在或是已经被删除！');
            }
            pdo_delete($this -> table_ad, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'display'){
            $condition = '';
            $list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}'  ORDER BY id desc");
        }
        include $this -> template('ad');
    }
    public function doWebRequest(){
        $this -> getsq();
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display_new';
        if ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            }else if ($row['status'] != 'done'){
                message('未兑换商品无法删除。请兑换后删除！', referer(), 'error');
            }
            pdo_delete($this -> table_request, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }else if ($operation == 'do_goods'){
            $data = array('status' => 'done');
            $id = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
            if (empty($row)){
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            }
            pdo_update($this -> table_request, $data, array('id' => $id));
            message('审核通过', referer(), 'success');
        }else if ($operation == 'display_new'){
            if (checksubmit('batchsend')){
                foreach ($_GPC['id'] as $id){
                    $data = array('status' => 'done');
                    $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
                    if (empty($row)){
                        continue;
                    }
                    pdo_update($this -> table_request, $data, array('id' => $id));
                }
                message('批量兑换成功!', referer(), 'success');
            }
            $condition = '';
            if (!empty($_GPC['name'])){
                $kw = $_GPC['name'];
                $condition .= "  AND (t1.from_user_realname like '%" . $kw . "%' OR  t1.mobile like '%" . $kw . "%' OR t1.realname like '%" . $kw . "%' OR t1.residedist like '%" . $kw . "%') ";
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $sql = "SELECT t1.*,t2.title FROM " . tablename($this -> table_request) . "as t1 LEFT JOIN " . tablename($this -> table_goods) . " as t2 " . " ON  t2.goods_id=t1.goods_id AND t2.weid=t1.weid AND t2.weid='{$_W['weid']}' WHERE t1.weid = '{$_W['weid']}'  " . $condition . " ORDER BY t1.createtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v){
                $fanskey[$v['from_user']] = 1;
            }
            $total = pdo_fetchcolumn($sql);
            $pager = pagination($total, $pindex, $psize);
            $fans = fans_search(array_keys($fanskey), array('realname', 'mobile', 'residedist', 'alipay'));
            load() -> model('mc');
        }else{
            $sql = "SELECT t1.*, t2.title FROM " . tablename($this -> table_request) . "as t1 LEFT  JOIN " . tablename($this -> table_goods) . " as t2 " . " ON t2.goods_id=t1.goods_id AND t1.weid=t2.weid AND t2.weid = '{$_W['weid']} WHERE t1.weid='{$_W['weid']}'   ORDER BY t1.createtime DESC";
            $list = pdo_fetchall($sql);
            $ar = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v){
                $fanskey[$v['from_user']] = 1;
            }
            $fans = fans_search(array_keys($fanskey), array('realname', 'mobile', 'residedist', 'alipay'));
        }
        include $this -> template('request');
    }
    public function doMobileSharetz(){
        global $_W, $_GPC;
        $weid = intval($_GPC['weid']);
        $uid = intval($_GPC['uid']);
        $reply = pdo_fetch('select * from ' . tablename('tiger_toupiao_poster') . ' where weid=:weid order by id asc limit 1', array(':weid' => $_W['uniacid']));
        $auth = mc_oauth_userinfo();
        $insert = array('weid' => $_W['uniacid'], 'openid' => $auth['openid'], 'from_user' => $_W['fans']['from_user'], 'helpid' => $uid, 'nickname' => $auth['nickname'], 'sex' => $auth['sex'], 'city' => $auth['city'], 'province' => $auth['province'], 'country' => $auth['country'], 'headimgurl' => $auth['headimgurl'], 'unionid' => $auth['unionid'],);
        $from_user = $_W['fans']['from_user'];
        $sql = 'select * from ' . tablename('tiger_toupiao_member') . ' where weid=:weid AND openid=:openid ';
        $where = "  ";
        $fans = pdo_fetch($sql . $where . " order by id asc limit 1 " , array(':weid' => $_W['uniacid'], ':openid' => $auth['openid']));
        if(empty($fans)){
            if(empty($auth)){
                echo '请在微信端打开';
                exit;
            }
            $insert['time'] = time();
            pdo_insert('tiger_toupiao_member', $insert);
            if(intval($reply['tztype']) == 1){
                $settings = $this -> module['config'];
                $ips = $this -> getIp();
                $ip = $this -> GetIpLookup($ips);
                $province = $ip['province'];
                $city = $ip['city'];
                $district = $ip['district'];
                include $this -> template('sharetz');
            }else{
                header("location:" . $reply['tzurl']);
            }
        }else{
            if(intval($reply['tztype']) == 1){
                $settings = $this -> module['config'];
                $ips = $this -> getIp();
                $ip = $this -> GetIpLookup($ips);
                $province = $ip['province'];
                $city = $ip['city'];
                $district = $ip['district'];
                include $this -> template('sharetz');
            }else{
                header("location:" . $reply['tzurl']);
            }
        }
        load() -> model('account');
        $cfg = $this -> module['config'];
    }
    public function doMobileSharefx(){
        global $_W, $_GPC;
        $url = $_GPC['url'];
        $uid = intval($_GPC['uid']);
        $reply = pdo_fetch('select * from ' . tablename('tiger_toupiao_poster') . ' where weid=:weid order by id asc limit 1', array(':weid' => $_W['uniacid']));
        $auth = mc_oauth_userinfo();
        $insert = array('weid' => $_W['uniacid'], 'openid' => $auth['openid'], 'from_user' => $_W['fans']['from_user'], 'helpid' => $uid, 'nickname' => $auth['nickname'], 'sex' => $auth['sex'], 'city' => $auth['city'], 'province' => $auth['province'], 'country' => $auth['country'], 'headimgurl' => $auth['headimgurl'], 'unionid' => $auth['unionid'],);
        $from_user = $_W['fans']['from_user'];
        $sql = 'select * from ' . tablename('tiger_toupiao_member') . ' where weid=:weid AND openid=:openid ';
        $where = "  ";
        $fans = pdo_fetch($sql . $where . " order by id asc limit 1 " , array(':weid' => $_W['uniacid'], ':openid' => $auth['openid']));
        if(empty($fans)){
            if(empty($auth)){
                echo '请在微信端打开';
                exit;
            }
            $insert['time'] = time();
            pdo_insert('tiger_toupiao_member', $insert);
            header("location:" . $url);
        }else{
            header("location:" . $url);
        }
    }
    private function sendtext($txt, $openid){
        global $_W;
        $acid = $_W['account']['acid'];
        if(!$acid){
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account') . " WHERE uniacid=:uniacid ", array(':uniacid' => $_W['uniacid']));
        }
        $acc = WeAccount :: create($acid);
        $data = $acc -> sendCustomNotice(array('touser' => $openid, 'msgtype' => 'text', 'text' => array('content' => urlencode($txt))));
        return $data;
    }
    function GetIpLookup($ip = ''){
        if(empty($ip)){
            $ip = GetIp();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){
            return false;
        }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        return $json;
    }
    public function doMobileDiqu(){
        global $_W, $_GPC;
        $uid = $_GPC['uid'];
        $ip = $this -> getIp();
        $settings = $this -> module['config'];
        $ip = $this -> GetIpLookup($ip);
        $province = $ip['province'];
        $city = $ip['city'];
        $district = $ip['district'];
        include $this -> template('diqu');
    }
    function getIp(){
        $onlineip = '';
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
            $onlineip = getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
            $onlineip = getenv('REMOTE_ADDR');
        }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
    public function doMobileAjxdiqu(){
        global $_W, $_GPC;
        $diqu = $_GPC['city'];
        $province = $_GPC['province'];
        $district = $_GPC['district'];
        $uid = $_GPC['uid'];
        $ddtype = $_GPC['ddtype'];
        $cfg = $this -> module['config'];
        load() -> model('mc');
        $fans = pdo_fetch('select * from ' . tablename('mc_mapping_fans') . ' where uniacid=:uniacid and uid=:uid order by fanid asc limit 1', array(':uniacid' => $_W['uniacid'], ':uid' => $uid));
        $user = mc_fetch($uid);
        $pos = stripos($cfg['city'], $diqu);
        if($ddtype == 1){
            $nzmsg = "抱歉!\n\n核对位置失败，请先开启共享位置功能！";
            $this -> sendtext($nzmsg, $fans['openid']);
            exit;
        }
        if ($pos === false){
            $nzmsg = "抱歉!\n\n本次活动只针对【" . $cfg['city'] . "】微信用户开放\n\n您所在的位置【" . $diqu . "】未开启活动，您不能参与本次活动，感谢您的支持!";
            mc_update($uid, array('resideprovince' => $province, 'residecity' => $diqu, 'residedist' => $district));
        }else{
            mc_update($uid, array('resideprovince' => $province, 'residecity' => $diqu, 'residedist' => $district));
            $nzmsg = '位置核对成功，请点击菜单【生成海报】参加活动!';
        }
        $this -> sendtext($nzmsg, $fans['openid']);
    }
    public function doMobileGoods(){
        global $_W, $_GPC;
        $now = time();
        $weid = $_W['weid'];
        $cfg = $this -> module['config'];
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " WHERE weid = '{$_W['weid']}' and $now < endtime and amount >= 0 order by px ASC");
        $my_goods_list = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . " WHERE  from_user='{$_W['fans']['from_user']}' AND weid = '{$_W['weid']}'");
        $ad = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        load() -> model('account');
        $cfg = $this -> module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $mc = mc_fetch($fans['uid'], array('nickname', 'avatar', 'credit1'));
            $fans['credit1'] = $mc['credit1'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1  and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)){
            $count2 = 0;
        }
        $sumcount = $count;
        if($_W['account']['level'] <> 4){
            $auth = mc_oauth_userinfo();
            $openid = $auth['openid'];
            $from_user = $_W['openid'];
        }else{
            $from_user = $_W['openid'];
            $openid = $_W['openid'];
        }
        $sql = 'select * from ' . tablename('tiger_toupiao_member') . ' where weid=:weid AND from_user=:from_user order by id asc limit 1';
        $member = pdo_fetch($sql, array(':weid' => $_W['weid'], ':from_user' => $from_user));
        if(empty($member)){
            $insert = array('weid' => $_W['uniacid'], 'from_user' => $from_user, 'openid' => $openid, 'helpid' => $uid, 'nickname' => $auth['nickname'], 'sex' => $auth['sex'], 'city' => $auth['city'], 'province' => $auth['province'], 'country' => $auth['country'], 'headimgurl' => $auth['avatar'], 'unionid' => $auth['unionid'], 'time' => time(),);
            $c = pdo_insert('tiger_toupiao_member', $insert);
        }
        $cfg['head'] = 1;
        $poster['mbstyle'] = 'style2';
        $is_follow = true;
        $mbstyle = 'style1';
        include $this -> template('goods/' . $mbstyle . '/goods');
    }
    public function doMobileFillInfo(){
        global $_W, $_GPC;
        checkauth();
        $cfg = $this -> module['config'];
        $memberid = intval($_GPC['memberid']);
        $goods_id = intval($_GPC['goods_id']);
        $fans = fans_search($_W['fans']['from_user']);
        $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
        $ips = $this -> getIp();
        $ip = $this -> GetIpLookup($ips);
        $province = $ip['province'];
        $city = $ip['city'];
        $district = $ip['district'];
        $mbstyle = 'style1';
        include $this -> template('goods/' . $mbstyle . '/fillinfo');
    }
    public function doMobileRequest(){
        global $_W, $_GPC;
        $cfg = $this -> module['config'];
        $ad = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')){
            message('请使用微信浏览器打开！');
            $openid = 'oUvXSsv6hNi7wdmd5uWQUTS4vJTY';
            $fans = pdo_fetch("select * from ims_mc_mapping_fans where openid='{$openid}'");
        }else{
            load() -> model('mc');
            $info = mc_oauth_userinfo();
            $fans = $_W['fans'];
            $mc = mc_fetch($fans['uid'], array('nickname', 'avatar', 'credit1'));
            $fans['credit1'] = $mc['credit1'];
            $fans['avatar'] = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid = $_GPC['pid'];
        $weid = $_GPC['i'];
        $poster = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_poster") . " where weid='{$weid}'");
        $credit = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']){
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans){
            $mc = mc_credit_fetch($fans['uid'], array($credittype));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this -> modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1){
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this -> modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)){
            $count2 = 0;
        }
        $sumcount = $count;
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this -> table_goods) . " as t1," . tablename($this -> table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND from_user='{$_W['fans']['from_user']}' AND t1.weid = '{$_W['weid']}' ORDER BY t2.createtime DESC");
        if(empty($goods_list)){
            $olist = 1;
        }
        $mbstyle = 'style1';
        include $this -> template('goods/' . $mbstyle . '/request');
    }
    public function doWebDhlist(){
        global $_W, $_GPC;
        $name = $_GPC['name'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        if (!empty($name)) $where .= " and (dwnick like '%{$name}%' or dopenid = '{$name}') ";
        $sql = "select * from " . tablename($this -> modulename . "_paylog") . " where uniacid='{$_W['uniacid']}' {$where} order BY dtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $list = pdo_fetchall($sql);
        $total = pdo_fetchcolumn($sql);
        $pager = pagination($total, $pindex, $psize);
        include $this -> template('dhlist');
    }
    public function doWebTxlist(){
        global $_W, $_GPC;
        $name = $_GPC['name'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        if (!empty($name)) $where .= " and (dwnick like '%{$name}%' or dopenid = '{$name}') ";
        $sql = "select * from " . tablename($this -> modulename . "_tixianlog") . " where uniacid='{$_W['uniacid']}' {$where} order BY dtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $list = pdo_fetchall($sql);
        $total = pdo_fetchcolumn($sql);
        $pager = pagination($total, $pindex, $psize);
        include $this -> template('txlist');
    }
    public function doMobileXfrank(){
        global $_W, $_GPC;
        $fans = $_W['fans'];
        $cfg = $this -> module['config'];
        $op = $_GPC['op'];
        if($op == 1){
            $daya = time()-7 * 86400;
            $day = date('Y-m-d H:i:s', $daya);
        }elseif($op == 2){
            $daya = time()-30 * 86400;
            $day = date('Y-m-d H:i:s', $daya);
        }elseif($op == 3){
            $daya = time()-90 * 86400;
            $day = date('Y-m-d H:i:s', $daya);
        }
        $mlist = pdo_fetchall("select picurl,nickname,sum(payment) as payment  from " . tablename('tiger_toupiao_order') . " where weid='{$_W['uniacid']}' and created>'{$day}' group by nickname order by sum(payment) desc");
        $ad = pdo_fetchall("SELECT * FROM " . tablename($this -> table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        include $this -> template('tixian/xfrank');
    }
    public function doMobileTixianpost(){
        global $_W, $_GPC;
        $uid = $_GPC['uid'];
        $fans['uid'] = $_GPC['uid'];
        $weid = $_GPC['weid'];
        $openid = $_GPC['openid'];
        $dhPay = doubleval($_GPC['dhPay']);
        load() -> model('mc');
        load() -> model('account');
        $cfg = $this -> module['config'];
        $fans = mc_fetch($uid, array('credit2', 'uid', 'uniacid'));
        $dshouhuo = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_toupiao_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        $txsum = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num and module=:module', array(':uniacid' => $_W['uniacid'], ':uid' => $fans['uid'], ':credittype' => 'credit2', ':num' => 0, ':module' => 'tiger_toupiao'));
        $fans['tx'] = $txsum['tx'] * -1;
        $fans['ktx'] = $fans['ysh'] - $fans['tx'];
        if(!$_W['isajax'])die(json_encode(array('success' => false, 'msg' => '非法提交,只能通过网站提交')));
        if($dhPay > $fans['ktx']){
            die(json_encode(array('success' => false, 'msg' => "提现金额不能大于当前金额")));
        }elseif($dhPay < $cfg['tx_num']){
            die(json_encode(array('success' => false, 'msg' => "提现金额最低" . $cfg['tx_num'] . "元起")));
        }elseif($dhPay < 1){
            die(json_encode(array('success' => false, 'msg' => "提现金额最低1元起")));
        }elseif($dhPay > 200){
            die(json_encode(array('success' => false, 'msg' => "单次提现金额不能大于200元")));
        }elseif($dhPay < 0){
            die(json_encode(array('success' => false, 'msg' => "请输入正确的金额")));
        }
        $credit2 = pdo_fetch('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark  order by createtime desc limit 1', array(':uniacid' => $weid, ':uid' => $uid, ':credittype' => 'credit2', ':remark' => '余额提现红包'));
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $daytime = mktime(0, 0, 0, $m, $d, $y);
        $daysum = pdo_fetch('select count(uid) t from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark and createtime>:createtime order by createtime desc limit 1', array(':uniacid' => $weid, ':uid' => $uid, ':credittype' => 'credit2', ':remark' => '余额提现红包', ':createtime' => $daytime));
        $day_sum = $daysum['t'];
        $rmbsum = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark and num<:num order by createtime desc limit 1', array(':uniacid' => $weid, ':uid' => $uid, ':credittype' => 'credit2', ':remark' => '余额提现红包', ':num' => 0));
        $rmb_sum = $rmbsum['tx'] * -1;
        $cfg['day_num'];
        $cfg['rmb_num'];
        if(!empty($cfg['day_num'])){
            if(intval($day_sum) >= intval($cfg['day_num'])){
                die(json_encode(array('success' => false, 'msg' => "1天之内只能兑换" . $cfg['day_num'] . "次，明天在来兑换吧！")));
                exit;
            }
        }
        if(!empty($cfg['rmb_num'])){
            if($dhPay >= $cfg['rmb_num']){
                die(json_encode(array('success' => false, 'msg' => "每个粉丝最多只能提现" . $cfg['rmb_num'] . "元")));
                exit;
            }
            if(doubleval($rmb_sum) >= doubleval($cfg['rmb_num'])){
                die(json_encode(array('success' => false, 'msg' => "每个粉丝最多只能提现" . $cfg['rmb_num'] . "元")));
                exit;
            }
        }
        $member = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_member") . " where weid='{$_W['weid']}' and id='{$_GPC['memberid']}' order by id desc");
        load() -> func('logging');
        if(!$cfg['mchid']){
            die(json_encode(array("success" => 4, "msg" => "商家未开启微信支付功能,请联系商家开启后申请兑换")));
        }
        $dtotal_amount = $dhPay * 100;
        if($cfg['txtype'] == 0){
            $msg = $this -> post_txhb($cfg, $member['openid'], $dtotal_amount, '1');
        }elseif($cfg['txtype'] == 1){
            $msg = $this -> post_qyfk($cfg, $member['openid'], $dtotal_amount, '1');
        }
        if($msg['message'] == 'success'){
            mc_credit_update($fans['uid'], 'credit2', - $dhPay, array($fans['uid'], '余额提现红包', 'tiger_toupiao'));
            pdo_insert('tiger_toupiao_tixianlog', array("uniacid" => $_W["uniacid"], "dwnick" => $_W['fans']['nickname'], "dopenid" => $_W['fans']['from_user'], "dtime" => time(), "dcredit" => $dhPay, "dtotal_amount" => $dtotal_amount, "dmch_billno" => $mch_billno, "dissuccess" => $msg['dissuccess'], "dresult" => $msg['message']));
            die(json_encode(array("success" => 1, "msg" => '提现成功,请到微信窗口查收！')));
        }else{
            die(json_encode(array("success" => 4, "msg" => $msg['message'])));
        }
    }
    function post_txhb($cfg, $openid, $dtotal_amount, $desc){
        global $_W;
        if(!empty($desc)){
            $fans = mc_fetch($_W['openid']);
            $dtotal = $dtotal_amount / 100;
            if($dtotal > $fans['credit2']){
                $ret['code'] = -1;
                $ret['dissuccess'] = 0;
                $ret['message'] = '余额不足';
                return $ret;
                exit;
            }
        }
        $root = IA_ROOT . '/attachment/tiger_toupiao/cert/' . $_W['uniacid'] . '/';
        $ret = array();
        $ret['code'] = 0;
        $ret['message'] = "success";
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = random(10) . date('Ymd') . random(3);
        $pars['mch_id'] = $cfg['mchid'];
        $pars['wxappid'] = $cfg['appid'];
        $pars['nick_name'] = $_W['account']['name'];
        $pars['send_name'] = $_W['account']['name'];
        $pars['re_openid'] = $openid;
        $pars['total_amount'] = $dtotal_amount;
        $pars['min_value'] = $dtotal_amount;
        $pars['max_value'] = $dtotal_amount;
        $pars['total_num'] = 1;
        $pars['wishing'] = '提现红包成功!';
        $pars['client_ip'] = $cfg['client_ip'];
        $pars['act_name'] = '兑换红包';
        $pars['remark'] = "来自" . $_W['account']['name'] . "的红包";
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v){
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] = $root . 'apiclient_key.pem';
        load() -> func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)){
            $procResult = $resp["message"];
            $ret['code'] = -1;
            $ret['message'] = $procResult;
            return $ret;
        }else{
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom -> loadXML($xml)){
                $xpath = new DOMXPath($dom);
                $code = $xpath -> evaluate('string(//xml/return_code)');
                $result = $xpath -> evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success'){
                    $ret['code'] = 0;
                    $ret['dissuccess'] = 1;
                    $ret['message'] = "success";
                    return $ret;
                }else{
                    $error = $xpath -> evaluate('string(//xml/err_code_des)');
                    $ret['code'] = -2;
                    $ret['dissuccess'] = 0;
                    $ret['message'] = $error;
                    return $ret;
                }
            }else{
                $ret['code'] = -3;
                $ret['dissuccess'] = 0;
                $ret['message'] = "3error3";
                return $ret;
            }
        }
    }
    public function post_qyfk($cfg, $openid, $amount, $desc){
        global $_W;
        if(!empty($desc)){
            $fans = mc_fetch($_W['openid']);
            $dtotal = $amount / 100;
            if($dtotal > $fans['credit2']){
                $ret['code'] = -1;
                $ret['dissuccess'] = 0;
                $ret['message'] = '余额不足';
                return $ret;
                exit;
            }
        }
        $root = IA_ROOT . '/attachment/tiger_toupiao/cert/' . $_W['uniacid'] . '/';
        $ret = array();
        $ret['code'] = 0;
        $ret['message'] = "success";
        $ret['amount'] = $amount;
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();
        $pars['mch_appid'] = $cfg['appid'];
        $pars['mchid'] = $cfg['mchid'];
        $pars['nonce_str'] = random(32);
        $pars['partner_trade_no'] = random(10) . date('Ymd') . random(3);
        $pars['openid'] = $openid;
        $pars['check_name'] = "NO_CHECK";
        $pars['amount'] = $amount;
        $pars['desc'] = "来自" . $_W['account']['name'] . "的提现";
        $pars['spbill_create_ip'] = $cfg['client_ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v){
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY'] = $root . 'apiclient_key.pem';
        load() -> func('communication');
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)){
            $procResult = $resp['message'];
            $ret['code'] = -1;
            $ret['dissuccess'] = 0;
            $ret['message'] = "-1:" . $procResult;
            return $ret;
        }else{
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom -> loadXML($xml)){
                $xpath = new DOMXPath($dom);
                $code = $xpath -> evaluate('string(//xml/return_code)');
                $result = $xpath -> evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($result) == 'success'){
                    $ret['code'] = 0;
                    $ret['dissuccess'] = 1;
                    $ret['message'] = "success";
                    return $ret;
                }else{
                    $error = $xpath -> evaluate('string(//xml/err_code_des)');
                    $ret['code'] = -2;
                    $ret['dissuccess'] = 0;
                    $ret['message'] = "-2:" . $error;
                    return $ret;
                }
            }else{
                $ret['code'] = -3;
                $ret['dissuccess'] = 0;
                $ret['message'] = "error response";
                return $ret;
            }
        }
    }
    public function doMobileGoodpost(){
        global $_W, $_GPC;
        if(!$_W['isajax'])die(json_encode(array('success' => false, 'msg' => '非法提交,只能通过网站提交')));
        $goods_id = intval($_GPC['goods_id']);
        $type = intval($_GPC['typea']);
        if (!empty($_GPC['goods_id'])){
            $fans = fans_search($_W['fans']['from_user'], array('realname', 'mobile', 'residedist', 'alipay', 'credit1', 'credit2', 'vip', 'uniacid', 'uid'));
            $share = pdo_fetch("SELECT * FROM " . tablename('tiger_jifenbao_share') . " WHERE weid = :weid and openid=:openid", array(':weid' => $_W['uniacid'], ':openid' => $fans['uid']));
            if($share['status'] == 1){
                die(json_encode(array('success' => false, 'msg' => "您的帐号怀疑有作弊嫌疑已被系统拉黑，如没有作弊请联系管理帮您解除操作！")));
            }
            $goods_info = pdo_fetch("SELECT * FROM " . tablename($this -> table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $daysum = mktime(0, 0, 0, $m, $d, $y);
            $goods_request = pdo_fetch("SELECT count(*) sn FROM " . tablename($this -> table_request) . " WHERE goods_id = $goods_id AND createtime>" . $daysum . " and weid = '{$_W['weid']}' and from_user = '{$_W['fans']['from_user']}'");
            if(!empty($goods_info['day_sum'])){
                if($goods_request['sn'] >= $goods_info['day_sum']){
                    die(json_encode(array('success' => false, 'msg' => "每个用户1天只能兑换" . $goods_info['day_sum'] . "次,\n明天在来兑换吧！")));
                }
            }
            if ($goods_info['amount'] <= 0){
                die(json_encode(array('success' => false, 'msg' => "商品已经兑空，请重新选择商品！")));
            }
            if (intval($goods_info['vip_require']) > $fans['vip']){
                die(json_encode(array('success' => false, 'msg' => "您的VIP级别不够，无法参与本项兑换，试试其它的吧。")));
            }
            $min_idle_time = empty($goods_info['min_idle_time']) ? 0 : $goods_info['min_idle_time'] * 60;
            $replicated = pdo_fetch("SELECT * FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' AND " . TIMESTAMP . " - createtime < {$min_idle_time}");
            if (!empty($replicated)){
                $last_time = date('H:i:s', $replicated['createtime']);
                die(json_encode(array('success' => false, 'msg' => "{$goods_info['min_idle_time']}分钟内不能重复兑换相同物品")));
            }
            if ($goods_info['per_user_limit'] > 0){
                $goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename($this -> table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}'");
                $cfg = $this -> module['config'];
                if(!empty($cfg['towurl'])){
                    if ($goods_limit['per_user_limit'] >= 1){
                        die(json_encode(array('success' => 8, 'towurl' => $cfg['towurl'], 'msg' => "每个用户只可以兑换一次红包 联系客服获取更多兑换机会!")));
                    }
                }
                if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']){
                    die(json_encode(array('success' => false, 'msg' => "本商品每个用户最多可兑换" . $goods_info['per_user_limit'] . "件,试试兑换其他奖品吧！")));
                }
            }
            if ($fans['credit1'] < $goods_info['cost']){
                die(json_encode(array('success' => false, 'msg' => "积分不足, 请重新选择商品")));
            }
            if (true){
                $data = array('amount' => $goods_info['amount'] - 1);
                pdo_update($this -> table_goods, $data, array('weid' => $_W['weid'], 'goods_id' => $goods_id));
                $data = array('realname' => ("" == $fans['realname'])?$_GPC['realname']:$_W['fans']['nickname'], 'mobile' => ("" == $fans['mobile'])?$_GPC['mobile']:$fans['mobile'], 'residedist' => ("" == $fans['residedist'])?$_GPC['residedist']:$fans['residedist'], 'alipay' => ("" == $fans['alipay'])?$_GPC['alipay']:$fans['alipay'],);
                fans_update($_W['fans']['from_user'], $data);
                $data = array('weid' => $_W['weid'], 'from_user' => $_W['fans']['from_user'], 'from_user_realname' => $_W['fans']['nickname'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'residedist' => $_GPC['residedist'], 'alipay' => $_GPC['alipay'], 'note' => $_GPC['note'], 'goods_id' => $goods_id, 'price' => $goods_info['price'], 'cost' => $goods_info['cost'], 'createtime' => TIMESTAMP);
                if ($goods_info['cost'] > $fans['credit1']){
                    die(json_encode(array('success' => false, 'msg' => "系统出现未知错误，请重试或与管理员联系")));
                }
                $kjfabc = $data['cost'];
                $kjfabc1 = $data['price'] * 100;
                if($type == 7){
                    if(empty($goods_info['cardid'])){
                    }else{
                        if($type == 7){
                            $data['status'] = 'done';
                        }
                        pdo_insert($this -> table_request, $data);
                        mc_credit_update($fans['uid'], 'credit1', - $kjfabc, array($fans['uid'], '礼品兑换:' . $goods_info['title']));
                        $cardinfo = $this -> sendcardpost($_W['openid'], $goods_info['cardid']);
                        die(json_encode(array('success' => true, 'msg' => "领取成功，请到公众号界面领取!")));
                    }
                    exit;
                }
                if($type == 5 || $type == 8){
                    if(($goods_info['price'] * 100) < 100){
                        die(json_encode(array("success" => 4, "msg" => "最少1元起兑换")));
                    }
                    if(($goods_info['price'] * 100) > 20000){
                        die(json_encode(array("success" => 4, "msg" => "单次最多不能超过200元红包")));
                    }
                    load() -> model('mc');
                    load() -> func('logging');
                    load() -> model('account');
                    $cfg = $this -> module['config'];
                    $member = pdo_fetch ('select * from ' . tablename ($this -> modulename . "_member") . " where weid='{$_W['weid']}' and id='{$_GPC['memberid']}' order by id desc");
                    $set = pdo_fetch('select * from ' . tablename('tiger_jifenbao_set') . ' where weid=:weid order BY id DESC LIMIT 1', array(':weid' => $_W['weid']));
                    if(($goods_info['price'] * 100) > ($set['hbsum'] * 100)){
                        if(!empty($set['hbtext'])){
                            die(json_encode(array("success" => 4, "msg" => $set['hbtext'])));
                        }
                    }
                    if(!$cfg['mchid']){
                        die(json_encode(array("success" => 4, "msg" => "商家未开启微信支付功能,请联系商家开启后申请兑换")));
                    }
                    if($_W['account']['level'] == 4){
                        $member['openid'] = $_W['openid'];
                    }
                    $dtotal_amount = $kjfabc * 1;
                    $msgs = '兑换成功，我们会在24小时之内给你审核发红包的哦，请耐心等待！';
                    if($goods_info['shtype'] == 1){
                        if($cfg['txtype'] == 0){
                            $msg = $this -> post_txhb($cfg, $member['openid'], $kjfabc1, $desc);
                        }elseif($cfg['txtype'] == 1){
                            $msg = $this -> post_qyfk($cfg, $member['openid'], $kjfabc1, $desc);
                        }
                        $msgs = '兑换成功,请到微信窗口查收！';
                        $data['status'] = 'done';
                        if($msg['message'] == 'success'){
                            pdo_insert($this -> table_request, $data);
                            mc_credit_update($fans['uid'], 'credit1', - $kjfabc, array($fans['uid'], '兑换:' . $goods_info['title']));
                            $dhdata = array("uniacid" => $_W["uniacid"], "dwnick" => $_W['fans']['nickname'], "dopenid" => $member['openid'], "dtime" => time(), "dcredit" => $kjfabc, "dtotal_amount" => $kjfabc1, "dmch_billno" => $mch_billno, "dissuccess" => $msg['dissuccess'], "dresult" => $msg['message']);
                            pdo_insert($this -> modulename . "_paylog", $dhdata);
                            die(json_encode(array("success" => 1, "msg" => $msgs)));
                        }else{
                            die(json_encode(array("success" => 4, "msg" => $msg['message'])));
                        }
                    }else{
                        $msgs = '亲！我们会在24小时之内给你审核发红包的哦，请耐心等待！';
                        $data['openid'] = $member['openid'];
                        pdo_insert($this -> table_request, $data);
                        mc_credit_update($fans['uid'], 'credit1', - $kjfabc, array($fans['uid'], '兑换:' . $goods_info['title']));
                        die(json_encode(array("success" => 1, "msg" => $msgs)));
                    }
                    exit;
                }
                if($type == 4){
                    $data['status'] = 'done';
                }
                pdo_insert($this -> table_request, $data);
                mc_credit_update($fans['uid'], 'credit1', - $kjfabc, array($fans['uid'], '礼品兑换:' . $goods_info['title']));
                die(json_encode(array('success' => true, 'msg' => "扣除您{$goods_info['cost']}积分。")));
            }
        }else{
            message('请选择要兑换的商品！', $this -> createMobileUrl('goods', array('weid' => $_W['weid'])), 'error');
        }
    }
    public function doMobileDoneExchange(){
        global $_W, $_GPC;
        $data = array('status' => 'done');
        $id = intval($_GPC['id']);
        $row = pdo_fetch("SELECT id FROM " . tablename($this -> table_request) . " WHERE id = :id", array(':id' => $id));
        if (empty($row)){
            message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
        }
        pdo_update($this -> table_request, $data, array('id' => $id));
        message('兑换成功！！', referer(), 'success');
    }
    public function getCredit(){
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array('credit1'));
        return "<span  class='label label-success'>{$fans['credit1']}分</span>";
    }
    public function getCredit2(){
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array('credit2'));
        return "<span  class='label label-success'>{$fans['credit2']}元</span>";
    }
    public function doWebDownloade(){
        include "downloade.php";
    }
    private function getAccountLevel(){
        global $_W;
        load() -> classs('weixin.account');
        $accObj = WeixinAccount :: create($_W['uniacid']);
        $account = $accObj -> account;
        return $account['level'];
    }
    private function getAccessToken(){
        global $_W;
        load() -> model('account');
        $acid = $_W['acid'];
        if (empty($acid)){
            $acid = $_W['uniacid'];
        }
        $account = WeAccount :: create($acid);
        $token = $account -> getAccessToken();
        return $token;
    }
    public function doWebQingkong(){
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $pid = $_GPC['pid'];
        if ($weid){
            $shares = pdo_fetchall('select id from ' . tablename($this -> modulename . "_share") . " where weid='{$weid}'");
            foreach ($shares as $value){
                @unlink("../addons/tiger_toupiao/qrcode/yzposter{$value['id']}.jpg");
            }
            pdo_delete('qrcode', array('uniacid' => $weid));
            pdo_delete('qrcode_stat', array('uniacid' => $weid));
            message('清空成功！！', referer(), 'success');
        }
    }
    public function doMobileDuibagoods(){
        global $_W, $_GPC;
        include 'duiba.php';
        $cfg = $this -> module['config'];
        if(empty($cfg['AppKey'])){
            exit;
        }
        load() -> model('mc');
        $uid = mc_openid2uid($_W['openid']);
        $credit = mc_credit_fetch($uid);
        $crdeidt = strval(intval($credit['credit1']));
        $url = buildCreditAutoLoginRequest($cfg['AppKey'], $cfg['appSecret'], $uid, $crdeidt);
        header('location: ' . $url);
    }
    public function doMobileDuibaxf(){
        global $_W, $_GPC;
        include 'duiba.php';
        load() -> model('mc');
        $cfg = $this -> module['config'];
        if(empty($cfg['AppKey'])){
            exit;
        }
        $request_array = array('uid' => $_GPC['uid'], 'orderNum' => $_GPC['orderNum'], 'credits' => $_GPC['credits'], 'params' => $_GPC['params'], 'type' => $_GPC['type'], 'ip' => $_GPC['ip'], 'sign' => $_GPC['sign'], 'timestamp' => $_GPC['timestamp'], 'waitAudit' => $_GPC['waitAudit'], 'actualPrice' => $_GPC['actualPrice'], 'description' => $_GPC['description'], 'paramsTest4' => $_GPC['paramsTest4'], 'facePrice' => $_GPC['facePrice'], 'appKey' => $_GPC['appKey']);
        $res = parseCreditConsume($cfg['AppKey'], $cfg['appSecret'], $request_array);
        if(!empty($request_array['uid'])){
            $mc = mc_fetch($_GPC['uid'], array('nickname', 'credit1'));
            $request_array['weid'] = $_W['uniacid'];
            $request_array['nickname'] = $mc['nickname'];
            $request_array['openid'] = $_W['openid'];
            $result = pdo_insert($this -> modulename . "_dbrecord", $request_array);
            mc_credit_update($_GPC['uid'], 'credit1', - $_GPC['credits'], array($_GPC['uid'], '兑吧订单号:' . $_GPC['orderNum']));
        }
        $mc = mc_fetch($_GPC['uid'], array('nickname', 'credit1'));
        $credit1 = strval(intval($mc['credit1']));
        $msg = array('status' => 'ok', 'bizId' => $_GPC['orderNum'], 'credits' => $credit1);
        file_put_contents(IA_ROOT . "/addons/tiger_jifenbao/log.txt", "\n old:" . json_encode($res), FILE_APPEND);
        return json_encode($msg);
        exit;
    }
    public function doMobileDuibatz(){
        global $_W, $_GPC;
        include 'duiba.php';
        $cfg = $this -> module['config'];
        if(empty($cfg['AppKey'])){
            exit;
        }
        $request_array = array('appKey' => $_GPC['appKey'], 'timestamp' => $_GPC['timestamp'], 'success' => $_GPC['success'], 'errorMessage' => $_GPC['errorMessage'], 'orderNum' => $_GPC['orderNum'], 'sign' => $_GPC['sign']);
        if($request_array['success'] <> 'true'){
            $list = pdo_fetch('select * from ' . tablename('tiger_jifenbao_dbrecord') . ' where weid=:weid and ordernum=:ordernum order by id asc limit 1', array(':weid' => $_W['uniacid'], ':ordernum' => $_GPC['orderNum']));
            mc_credit_update($list['uid'], 'credit1', $list['credits'], array($list['uid'], '兑吧失败退回订单号:' . $_GPC['orderNum']));
        }
        $res = parseCreditNotify($cfg['AppKey'], $cfg['appSecret'], $request_array);
        return 'ok';
    }
    public function doMobileDe(){
        $ret = $this -> getunionid();
        echo '<pre>';
        $auth = @json_decode($ret['content'], true);
        $unionid = $auth['unionid'];
        echo $unionid;
        print_r($auth);
    }
    public function getunionid(){
        global $_W;
        $access_token = $this -> getAccessToken();
        $openid = $_W['openid'];
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        return $ret = ihttp_get($url);
    }
    public function doMobiledd(){
        $cfg = $this -> module['config'];
        $appId = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client = new KdtApiClient($appId, $appSecret);
        $method = 'kdt.trades.sold.get';
        $params = array();
        $json = $client -> post($method, $params);
        echo '<pre>';
        print_r($json);
    }
    public function doMobileCard(){
        global $_W;
        $this -> sendcardpost($_W['openid'], 'pozm3txI6W-Fcxndth6AlSONkZqE');
    }
    public function sendcardpost($openid, $cardid){
        global $_W;
        $getticket = $this -> getticket();
        $createNonceStr = $this -> createNonceStr();
        $signature = $this -> signature($getticket, $createNonceStr);
        $account = WeAccount :: create();
        $card_ext = array('openid' => $openid, 'timestamp' => strval(TIMESTAMP), 'signature' => $signature,);
        $custom = array('touser' => $_W['openid'], 'msgtype' => 'wxcard', 'wxcard' => array('card_id' => $cardid, 'card_ext' => $card_ext),);
        $account -> sendCustomNotice($custom);
    }
    public function doMobileCardd(){
        $data11 = array('action_name' => "QR_CARD", 'expire_seconds' => 1800, 'action_info' => array('card' => array('card_id' => "pozm3txI6W-Fcxndth6AlSONkZqE", 'is_unique_code' => false, 'outer_id' => 100),),);
        $result = $this -> create_card_qrcode($data11);
        echo '<pre>';
        print_r($result);
        echo "<img src='{$result['show_qrcode_url']}'>";
    }
    public function create_card_qrcode($data){
        $access_token = $this -> getAccessToken();
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=" . $access_token;
        $res = $this -> http_web_request($url, json_encode($data));
        return json_decode($res, true);
    }
    protected function http_web_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    public function getticket(){
        global $_W;
        $data = pdo_fetch("SELECT * FROM " . tablename($this -> modulename . "_ticket") . " WHERE weid = '{$_W['weid']}'");
        if(empty($data)){
            $access_token = $this -> getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
            $json = ihttp_get($url);
            $res = @json_decode($json['content'], true);
            if(empty($ticket)){
                $kjdata = array('weid' => $_W['uniacid'], 'ticket' => $res['ticket'], 'createtime' => TIMESTAMP + 7000,);
                pdo_insert($this -> modulename . "_ticket", $kjdata);
            }
            Return $res['ticket'];
        }else{
            if($data['createtime'] < time()){
                $access_token = $this -> getAccessToken();
                $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
                $json = ihttp_get($url);
                $res = @json_decode($json['content'], true);
                if(empty($ticket)){
                    $kjdata = array('ticket' => $ticket, 'createtime' => TIMESTAMP + 7000,);
                    pdo_update($this -> modulename . "_ticket", $kjdata, array('weid' => $_W['uniacid']));
                }
                Return $res['ticket'];
            }else{
                Return $data['ticket'];
            }
        }
    }
    private function createNonceStr($length = 16){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++){
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    public function signature($api_ticket, $nonce_str){
        $obj['api_ticket'] = $api_ticket;
        $obj['timestamp'] = TIMESTAMP;
        $obj['nonce_str'] = $nonce_str;
        $signature = $this -> get_card_sign($obj);
        Return $signature;
    }
    public function get_card_sign($bizObj){
        asort($bizObj);
        $buff = "";
        foreach ($bizObj as $k => $v){
            $buff .= $v;
        }
        return sha1($buff);
    }
}

?>