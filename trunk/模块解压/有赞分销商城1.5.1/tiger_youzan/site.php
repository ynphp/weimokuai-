<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/tiger_youzan/lib/payutil/WxPayMicropayHelper.php';
require_once IA_ROOT . '/addons/tiger_youzan/lib/payutil/WxPay.Micropay.config.php';
require_once IA_ROOT . '/addons/tiger_youzan/lib/KdtApiClient.php';
class Tiger_youzanModuleSite extends WeModuleSite
{
    public $table_request = "tiger_youzan_request";
    public $table_goods = "tiger_youzan_goods";
    public $table_ad = "tiger_youzan_ad";
    private static $t_sys_member = 'mc_members';
    public function doWebYzgoods()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            $upitem = pdo_fetch('select * from ' . tablename($this->modulename . "_yzgoods") . " where id='{$_GPC['goods_id']}' order by px desc");
            if (checksubmit('submit')) {
                $id     = intval($_GPC['goods_id']);
                $updata = array(
                    'zg' => $_GPC['zg'],
                    'px' => $_GPC['px'],
                    'tj' => $_GPC['tj'],
                    'level1' => $_GPC['level1'],
                    'level2' => $_GPC['level2'],
                    'level3' => $_GPC['level3'],
                    'createtime' => time()
                );
                if (pdo_update($this->modulename . "_yzgoods", $updata, array(
                    'id' => $id
                )) === false) {
                    message('更新失败');
                } else {
                    message('更新成功!');
                }
            }
        } else if ($operation == 'delete') {
            $goods_id = intval($_GPC['goods_id']);
            $row      = pdo_fetch('select * from ' . tablename($this->modulename . "_yzgoods") . " where id='{$goods_id}'");
            if (empty($row)) {
                message('抱歉，商品' . $goods_id . '不存在或是已经被删除！');
            }
            pdo_delete($this->modulename . "_yzgoods", array(
                'id' => $goods_id
            ));
            message('删除成功！', referer(), 'success');
        }
        $set     = pdo_fetch('select * from ' . tablename($this->modulename . "_set") . " where weid='{$_W['weid']}'");
        $yzgoods = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . "_yzgoods") . " WHERE weid = '{$_W['weid']}'  ORDER BY px ASC");
        include $this->template('yzgoods');
    }
    public function doWebYztongbu()
    {
        global $_W, $_GPC;
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        if (empty($appId)) {
            message('请先填写有赞API');
        }
        $client  = new KdtApiClient($appId, $appSecret);
        $method  = 'kdt.items.onsale.get';
        $json    = $client->post($method);
        $yzgoods = $json['response']['items'];
        if (empty($yzgoods)) {
            message('请先到有赞商城添加商品');
        }
        foreach ($yzgoods as $v) {
            $data = array(
                'num_iid' => $v['num_iid'],
                'weid' => $_W['uniacid'],
                'px' => 0,
                'title' => $v['title'],
                'pic_url' => $v['pic_url'],
                'pic_thumb_url' => $v['pic_thumb_url'],
                'detail_url' => $v['detail_url'],
                'createtime' => time()
            );
            $item = pdo_fetch('select * from ' . tablename($this->modulename . "_yzgoods") . " where num_iid='{$v['num_iid']}'");
            if (empty($item)) {
                pdo_insert($this->modulename . "_yzgoods", $data);
            } else {
                pdo_update($this->modulename . "_yzgoods", $data, array(
                    'num_iid' => $v['num_iid']
                ));
            }
        }
        message('同步成功', $this->createWebUrl('yzgoods'));
    }
    public function doWebSet()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $set  = pdo_fetch('select * from ' . tablename($this->modulename . "_set") . " where weid='{$weid}'");
        if (empty($set)) {
            if (checksubmit('submit')) {
                $indata = array(
                    'weid' => $_W['uniacid'],
                    'z1' => $_GPC['z1'],
                    'z2' => $_GPC['z2'],
                    'z3' => $_GPC['z3'],
                    'p1' => $_GPC['p1'],
                    'g1' => $_GPC['g1'],
                    'j1' => $_GPC['j1'],
                    'p2' => $_GPC['p2'],
                    'g2' => $_GPC['g2'],
                    'j2' => $_GPC['j2'],
                    'p3' => $_GPC['p3'],
                    'g3' => $_GPC['g3'],
                    'j3' => $_GPC['j3']
                );
                $result = pdo_insert($this->modulename . "_set", $indata);
                if (empty($result)) {
                    message('添加失败');
                } else {
                    message('添加成功!');
                }
            }
        } else {
            if (checksubmit('submit')) {
                $id     = intval($_GPC['id']);
                $updata = array(
                    'z1' => $_GPC['z1'],
                    'z2' => $_GPC['z2'],
                    'z3' => $_GPC['z3'],
                    'p1' => $_GPC['p1'],
                    'g1' => $_GPC['g1'],
                    'j1' => $_GPC['j1'],
                    'p2' => $_GPC['p2'],
                    'g2' => $_GPC['g2'],
                    'j2' => $_GPC['j2'],
                    'p3' => $_GPC['p3'],
                    'g3' => $_GPC['g3'],
                    'j3' => $_GPC['j3']
                );
                if (pdo_update($this->modulename . "_set", $updata, array(
                    'id' => $id
                )) === false) {
                    message('更新失败');
                } else {
                    message('更新成功!');
                }
            }
        }
        include $this->template('set');
    }
    public function doWebYzorder()
    {
        global $_W, $_GPC;
        $weid   = $_W['uniacid'];
        $cfg    = $this->module['config'];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 12;
        $order  = pdo_fetchall('select * from ' . tablename($this->modulename . "_order") . " where weid='{$_W['uniacid']}' order by id desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_order') . " where weid='{$_W['uniacid']}'");
        $pager  = pagination($total, $pindex, $psize);
        include $this->template('yzorder');
    }
    public function doWebYzuserid()
    {
        $this->userid($openid);
    }
    public function userid($openid)
    {
        global $_W;
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.items.onsale.get';
        $params    = array(
            'weixin_openid' => $openid
        );
        $json      = $client->post($method, $params);
        return $json;
    }
    public function doWebMPoster()
    {
        global $_W, $_GPC;
        $do = 'mposter';
        if ('delete' == $_GPC['op'] && $_GPC['pid']) {
            $rid = pdo_fetchcolumn('select rid from ' . tablename($this->modulename . "_poster") . " where id='{$_GPC['pid']}'");
            if (pdo_delete($this->modulename . "_poster", array(
                'id' => $_GPC['pid']
            )) === false) {
                message('删除海报失败！');
            } else {
                $shares = pdo_fetchall('select id from ' . tablename($this->modulename . "_share") . " where pid='{$_GPC['pid']}'");
                foreach ($shares as $value) {
                    @unlink(str_replace('#sid#', $value['id'], "../addons/junsion_poster/qrcode/mposter#sid#.jpg"));
                }
                pdo_delete('rule', array(
                    'id' => $rid
                ));
                pdo_delete('rule_keyword', array(
                    'rid' => $rid
                ));
                pdo_delete($this->modulename . "_share", array(
                    'pid' => $_GPC['pid']
                ));
                pdo_delete('qrcode', array(
                    'name' => $title,
                    'uniacid' => $_W['uniacid']
                ));
                message('删除海报成功！', $this->createWebUrl('mposter'));
            }
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $list   = pdo_fetchall("select *,(select count(*) from " . tablename($this->modulename . "_share") . " where pid=p.id) as scan from " . tablename($this->modulename . "_poster") . " p where weid='{$_W['uniacid']}' and type=2 LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_poster') . " where weid='{$_W['uniacid']}'");
        $pager  = pagination($total, $pindex, $psize);
        include $this->template('mlist');
    }
    public function doWebMCreate()
    {
        global $_W, $_GPC;
        $do   = 'mcreate';
        $op   = $_GPC['op'];
        $pid  = $_GPC['pid'];
        $item = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where id='{$pid}'");
        if (checksubmit()) {
            $ques      = $_GPC['ques'];
            $answer    = $_GPC['answer'];
            $questions = '';
            foreach ($ques as $key => $value) {
                if (empty($value))
                    continue;
                $questions[] = array(
                    'question' => $value,
                    'answer' => $answer[$key]
                );
            }
            $data = array(
                'title' => $_GPC['title'],
                'type' => 2,
                'fans_type' => $_GPC['fans_type'],
                'bg' => $_GPC['bg'],
                'data' => htmlspecialchars_decode($_GPC['data']),
                'weid' => $_W['uniacid'],
                'score' => $_GPC['score'],
                'cscore' => $_GPC['cscore'],
                'pscore' => $_GPC['pscore'],
                'scorehb' => $_GPC['scorehb'],
                'cscorehb' => $_GPC['cscorehb'],
                'pscorehb' => $_GPC['pscorehb'],
                'rscore' => $_GPC['rscore'],
                'gid' => $_GPC['gid'],
                'kdtype' => $_GPC['kdtype'],
                'winfo1' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['winfo1']), ENT_QUOTES),
                'winfo2' => $_GPC['winfo2'],
                'winfo3' => $_GPC['winfo3'],
                'stitle' => serialize($_GPC['stitle']),
                'sthumb' => serialize($_GPC['sthumb']),
                'sdesc' => serialize($_GPC['sdesc']),
                'rtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['rtips']), ENT_QUOTES),
                'ftips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['ftips']), ENT_QUOTES),
                'utips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['utips']), ENT_QUOTES),
                'utips2' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['utips2']), ENT_QUOTES),
                'wtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['wtips']), ENT_QUOTES),
                'nostarttips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['nostarttips']), ENT_QUOTES),
                'endtips' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['endtips']), ENT_QUOTES),
                'starttime' => strtotime($_GPC['starttime']),
                'endtime' => strtotime($_GPC['endtime']),
                'surl' => serialize($_GPC['surl']),
                'kword' => $_GPC['kword'],
                'credit' => $_GPC['credit'],
                'doneurl' => $_GPC['doneurl'],
                'tztype' => $_GPC['tztype'],
                'slideH' => $_GPC['slideH'],
                'mbcolor' => $_GPC['mbcolor'],
                'mbstyle' => $_GPC['mbstyle'],
                'mbfont' => $_GPC['mbfont'],
                'sliders' => $_GPC['sliders'],
                'mtips' => $_GPC['mtips'],
                'sharetitle' => $_GPC['sharetitle'],
                'sharethumb' => $_GPC['sharethumb'],
                'sharedesc' => $_GPC['sharedesc'],
                'sharegzurl' => $_GPC['sharegzurl'],
                'tzurl' => $_GPC['tzurl'],
                'questions' => serialize($questions),
                'createtime' => time()
            );
            if ($pid) {
                if (pdo_update($this->modulename . "_poster", $data, array(
                    'id' => $pid
                )) === false) {
                    message('更新海报失败！1');
                } else {
                    if (empty($item['rid'])) {
                        $this->createRule($_GPC['kword'], $pid);
                    } elseif ($item['kword'] != $data['kword']) {
                        pdo_update('rule_keyword', array(
                            'content' => $data['kword']
                        ), array(
                            'rid' => $item['rid']
                        ));
                        pdo_update('qrcode', array(
                            'keyword' => $data['kword']
                        ), array(
                            'name' => $this->modulename . $pid,
                            'keyword' => $item['kword']
                        ));
                    }
                    message('更新海报成功！2', $this->createWebUrl('mposter'));
                }
            } else {
                $data['rtype']      = $_GPC['rtype'];
                $data['createtime'] = time();
                if (pdo_insert($this->modulename . "_poster", $data) === false) {
                    message('生成海报失败！3');
                } else {
                    $this->createRule($_GPC['kword'], pdo_insertid());
                    message('生成海报成功！4', $this->createWebUrl('mposter'));
                }
            }
        }
        load()->func('tpl');
        if ($item) {
            $data   = json_decode(str_replace('&quot;', "'", $item['data']), true);
            $size   = getimagesize(toimage($item['bg']));
            $size   = array(
                $size[0] / 2,
                $size[1] / 2
            );
            $date   = array(
                'start' => date('Y-m-d H:i:s', $item['starttime']),
                'end' => date('Y-m-d H:i:s', $item['endtime'])
            );
            $titles = unserialize($item['stitle']);
            $thumbs = unserialize($item['sthumb']);
            $sdesc  = unserialize($item['sdesc']);
            $surl   = unserialize($item['surl']);
            foreach ($titles as $key => $value) {
                if (empty($value))
                    continue;
                $slist[] = array(
                    'stitle' => $value,
                    'sdesc' => $sdesc[$key],
                    'sthumb' => $thumbs[$key],
                    'surl' => $surl[$key]
                );
            }
        } else
            $date = array(
                'start' => date('Y-m-d H:i:s', time()),
                'end' => date('Y-m-d H:i:s', time() + 7 * 24 * 3600)
            );
        $groups = pdo_fetchall('select * from ' . tablename('mc_groups') . " where uniacid='{$_W['uniacid']}' order by isdefault desc");
        include $this->template('mcreate');
    }
    private function createRule($kword, $pid)
    {
        global $_W;
        $rule = array(
            'uniacid' => $_W['uniacid'],
            'name' => $kword,
            'module' => $this->modulename,
            'status' => 1,
            'displayorder' => 254
        );
        pdo_insert('rule', $rule);
        unset($rule['name']);
        $rule['type']    = 1;
        $rule['rid']     = pdo_insertid();
        $rule['content'] = $kword;
        pdo_insert('rule_keyword', $rule);
        pdo_update($this->modulename . "_poster", array(
            'rid' => $rule['rid']
        ), array(
            'id' => $pid
        ));
    }
    public function doWebDianyuan()
    {
        global $_W, $_GPC;
        $do = 'dianyuan';
        include $this->template('dianyuan');
    }
    public function doWebDianyuandel()
    {
        global $_W, $_GPC;
        $del = pdo_delete($this->modulename . "_dianyuan", array(
            'id' => $_GPC['id']
        ));
        if ($del) {
            message('删除成功', $this->createWebUrl('dianyuangl'));
        }
    }
    public function doWebDianyuangl()
    {
        global $_W, $_GPC;
        $do   = 'dianyuangl';
        $list = pdo_fetchall("select * from" . tablename($this->modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' order by id desc");
        include $this->template('dianyuangl');
    }
    public function doWebHexiao()
    {
        global $_W, $_GPC;
        $do   = 'hexiao';
        $list = pdo_fetchall("select * from" . tablename($this->modulename . "_hexiao") . " where weid='{$_W['uniacid']}' order by id desc");
        include $this->template('hexiao');
    }
    public function doMobileHexiao()
    {
        global $_W, $_GPC;
        $password = $_GPC['password'];
        if ($password) {
            $clerk = pdo_fetch("select * from" . tablename($this->modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' and password='{$password}'");
            if ($clerk) {
                $data = array(
                    'weid' => $_W['uniacid'],
                    'dianyanid' => $clerk['dianyanid'],
                    'openid' => $_GPC['openid'],
                    'nickname' => $_GPC['nickname'],
                    'ename' => $clerk['ename'],
                    'companyname' => $clerk['companyname'],
                    'goodname' => $_GPC['goodname'],
                    'goodid' => $_GPC['goodid'],
                    'createtime' => time()
                );
                pdo_insert($this->modulename . "_hexiao", $data);
                $dataab = array(
                    'status' => 'done'
                );
                $id     = intval($_GPC['goodid']);
                if (pdo_update($this->table_request, $dataab, array(
                    'id' => $id
                ))) {
                    message('消费成功', $this->createMobileUrl('request'));
                } else {
                    message('消费失败', $this->createMobileUrl('request'), 'error');
                }
            } else {
                message('密码填写错误', $this->createMobileUrl('request'), 'error');
            }
        } else {
            message('请填写消费密码', $this->createMobileUrl('request'), 'error');
        }
    }
    public function doWebDianyuanadd()
    {
        global $_W, $_GPC;
        $do = 'dianyuanadd';
        $id = $_GPC['id'];
        $op = $_GPC['op'];
        if ($id) {
            $clerk = pdo_fetch("select * from" . tablename($this->modulename . "_dianyuan") . " where weid='{$_W['uniacid']}' and id={$id}");
        }
        if ($op == 'adde') {
            $list = pdo_fetchall("select * from" . tablename($this->modulename . "_dianyuan") . " where password='{$_GPC['password']}'");
            if ($list) {
                message('店员密码不能重复', $this->createWebUrl('dianyuanadd'), 'error');
            }
            $data = array(
                'weid' => $_W['uniacid'],
                'openid' => $_GPC['openid'],
                'nickname' => $_GPC['nickname'],
                'ename' => $_GPC['ename'],
                'tel' => $_GPC['tel'],
                'password' => $_GPC['password'],
                'companyname' => $_GPC['companyname'],
                'nickname' => $_GPC['nickname'],
                'createtime' => time()
            );
            if ($id) {
                if (pdo_update($this->modulename . "_dianyuan", $data, array(
                    'id' => $id
                ))) {
                    message('编辑成功！', $this->createWebUrl('dianyuangl'));
                } else {
                    message('添加失败！');
                }
            }
            if (pdo_insert($this->modulename . "_dianyuan", $data)) {
                message('添加成功！', $this->createWebUrl('dianyuangl'));
            } else {
                message('添加失败！');
            }
        }
        include $this->template('dianyuangl');
    }
    public function doWebRecord()
    {
        global $_W, $_GPC;
        $pid    = $_GPC['pid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        $list   = pdo_fetchall("select * from " . tablename($this->modulename . "_record") . " where pid='{$pid}' LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
        load()->model('mc');
        foreach ($list as $key => $value) {
            $mc                     = mc_fetch($value['openid']);
            $list[$key]['nickname'] = $mc['nickname'];
            $list[$key]['avatar']   = $mc['avatar'];
        }
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_record') . " where pid='{$pid}'");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('record');
    }
    public function doWebShare()
    {
        global $_W, $_GPC;
        load()->model('mc');
        $weid   = $_W['weid'];
        $uid    = $_GPC['uid'];
        $op     = $_GPC['op'];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;
        $fans1  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
        $count1 = count($fans1);
        if (empty($count1)) {
            $count1 = 0;
            $count2 = 0;
            $count3 = 0;
        }
        if (!empty($fans1)) {
            $fans2  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $count2 = count($fans2);
            if (empty($count2)) {
                $count2 = 0;
            }
        }
        if (!empty($fans2)) {
            $fans3  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")", array(), 'openid');
            $count3 = count($fans3);
            if (empty($count3)) {
                $count3 = 0;
            }
        }
        if ($_GPC['name']) {
            $name  = $_GPC['name'];
            $where = " and (nickname like '%{$name}%' or fans_id = '{$name}')";
        }
        if ($op == 1) {
            $list  = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}' order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_share') . " where weid='{$weid}' and helpid='{$uid}'");
        } elseif ($op == 2) {
            $fans1 = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
            $list  = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ") LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_share') . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")");
        } elseif ($op == 3) {
            $fans1 = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}' order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'openid');
            $fans2 = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $list  = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ") LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_share') . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")");
        } else {
            $list  = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' {$where} order by createtime desc LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_share') . " where weid='{$weid}'");
        }
        $mlist = array();
        foreach ($list as $k => $v) {
            $cont = $this->postfanscont($v['openid']);
            $fans = $this->postfans($v['fans_id']);
            if (!empty($v['helpid'])) {
                $mc = mc_fansinfo($v['helpid']);
            }
            $jf                       = mc_credit_fetch($v['openid']);
            $jq                       = $this->postfansorders($v['fans_id']);
            $mlist[$k]['fans_id']     = $v['fans_id'];
            $mlist[$k]['uid']         = $v['openid'];
            $mlist[$k]['helpid']      = $v['helpid'];
            $mlist[$k]['dj']          = $v['fans_type'];
            $mlist[$k]['avatar']      = $fans['avatar'];
            $mlist[$k]['openid']      = $fans['openid'];
            $mlist[$k]['nickname']    = $fans['nickname'];
            $mlist[$k]['follow_time'] = $fans['follow_time'];
            $mlist[$k]['createtime']  = $v['createtime'];
            $mlist[$k]['province']    = $fans['province'];
            $mlist[$k]['city']        = $fans['city'];
            $mlist[$k]['tjrname']     = $mc['nickname'];
            $mlist[$k]['sex']         = $fans['sex'];
            $mlist[$k]['lv1']         = $cont['count1'];
            $mlist[$k]['lv2']         = $cont['count2'];
            $mlist[$k]['lv3']         = $cont['count3'];
            $mlist[$k]['jf']          = $jf['credit1'];
            $mlist[$k]['dsh']         = $jq['dsh'];
            $mlist[$k]['ysh']         = $jq['ysh'];
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template('share');
    }
    public function postfansorders($fans_id)
    {
        global $_W, $_GPC;
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) tx  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=1");
        $fans['dsh'] = $dshouhuo['tx'];
        if (empty($fans['dsh'])) {
            $fans['dsh'] = '0.00';
        }
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        if (empty($fans['ysh'])) {
            $fans['ysh'] = '0.00';
        }
        return $fans;
    }
    public function postfanscont($uid)
    {
        global $_W, $_GPC;
        $weid   = $_W['weid'];
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $fans1  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
        $count1 = count($fans1);
        if (!empty($fans1)) {
            $fans2  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $count2 = count($fans2);
            if (empty($count2)) {
                $count2 = 0;
            }
        }
        if (!empty($fans2)) {
            $fans3  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")", array(), 'openid');
            $count3 = count($fans3);
            if (empty($count3)) {
                $count3 = 0;
            }
        }
        $fcont = array(
            'count1' => $count1,
            'count2' => $count2,
            'count3' => $count3
        );
        return $fcont;
    }
    public function doWebStatus()
    {
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $pid = $_GPC['pid'];
        if ($_GPC['status']) {
            if (pdo_update($this->modulename . "_share", array(
                'status' => 0
            ), array(
                'id' => $sid
            )) === false) {
                message('恢复失败！');
            } else
                message('恢复成功！', $this->createWebUrl('share', array(
                    'pid' => $pid,
                    'status' => 1
                )));
        } else {
            if (pdo_update($this->modulename . "_share", array(
                'status' => 1
            ), array(
                'id' => $sid
            )) === false) {
                message('拉黑失败！');
            } else
                message('拉黑成功！', $this->createWebUrl('share', array(
                    'pid' => $pid
                )));
        }
    }
    public function doWebDelete()
    {
        global $_W, $_GPC;
        $sid = $_GPC['sid'];
        $pid = $_GPC['pid'];
        pdo_delete($this->modulename . "_share", array(
            'id' => $sid
        ));
        pdo_update($this->modulename . "_share", array(
            'helpid' => 0
        ), array(
            'helpid' => $sid
        ));
        message('删除成功！', $this->createWebUrl('share', array(
            'pid' => $pid,
            'status' => $_GPC['status']
        )));
    }
    public function doMobileScore()
    {
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $openid    = $_W['fans']['from_user'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
            $openid = 'opk4HsyhyQpJvVAUhA6JGhdMSImo';
        }
        $pid   = $_GPC['pid'];
        $items = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where id='{$pid}'");
        $name  = $items['credit'] ? '余额' : '积分';
        if (empty($items) && $items['type'] != 1)
            exit('扫码送' . $name . '活动已经结束！');
        $sliders = unserialize($items['sliders']);
        $atimes  = '';
        foreach ($sliders as $key => $value) {
            $atimes[$key] = $value['displayorder'];
        }
        array_multisort($atimes, SORT_NUMERIC, SORT_DESC, $sliders);
        $follow          = pdo_fetchcolumn('select follow from ' . tablename("mc_mapping_fans") . " where openid='{$openid}'");
        $record          = pdo_fetch('select * from ' . tablename($this->modulename . "_record") . " where openid='{$openid}' and pid='{$pid}'");
        $items['score3'] = $items['score'];
        if ($items['score2']) {
            $items['score1'] = $items['score'] . "—" . $items['score2'] . " ";
            $items['score3'] = intval(mt_rand($items['score'], $items['score2']));
        }
        $cfg = $this->module['config'];
        include $this->template('qrcode');
    }
    public function doMobileAjaxrank()
    {
        global $_W, $_GPC;
        $weid   = $_GPC['weid'];
        $last   = $_GPC['last'];
        $amount = $_GPC['amount'];
        $shares = pdo_fetchall("select m.nickname,m.avatar,m.credit1 FROM " . tablename('mc_members') . " m LEFT JOIN " . tablename('mc_mapping_fans') . " f ON m.uid=f.uid where f.follow=1 and f.uniacid='{$weid}' and m.credit1<>0 order by credit1 desc limit $last,$amount");
        echo json_encode($shares);
    }
    public function doMobileRanking()
    {
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
            if (empty($count2))
                $count2 = 0;
        }
        $sumcount = $count;
        $rank     = $poster['slideH'];
        if (empty($rank)) {
            $rank = 20;
        }
        $cfg    = $this->module['config'];
        $shares = pdo_fetchall("select m.nickname,m.avatar,m.credit1,m.uid from" . tablename('mc_members') . " m inner join " . tablename('mc_mapping_fans') . " f on m.uid=f.uid and f.follow=1 and f.uniacid='{$weid}' order by m.credit1 desc limit {$rank}");
        foreach ($shares as $k => $v) {
            $txsum = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $shares[$k]['uid'],
                ':credittype' => 'credit1',
                ':num' => 0
            ));
            if (empty($txsum['tx'])) {
                $shares[$k]['credit3'] = 0;
            } else {
                $shares[$k]['credit3'] = $txsum['tx'] * -1;
            }
        }
        $cfg = $this->module['config'];
        if ($cfg['paihang'] == 1) {
            foreach ($shares as $key => $value) {
                $nickname[$key] = $value['nickname'];
                $avatar[$key]   = $value['avatar'];
                $credit2[$key]  = $value['credit2'];
                $uid[$key]      = $value['uid'];
                $credit3[$key]  = $value['credit3'];
            }
            array_multisort($credit3, SORT_NUMERIC, SORT_DESC, $uid, SORT_STRING, SORT_ASC, $shares);
        }
        $mbstyle = $poster['mbstyle'];
        include $this->template($mbstyle . '/ranking');
    }
    public function doMobileTxranking()
    {
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit2';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
            if (empty($count2))
                $count2 = 0;
        }
        $sumcount = $count;
        $rank     = $poster['slideH'];
        if (empty($rank)) {
            $rank = 20;
        }
        $cfg    = $this->module['config'];
        $shares = pdo_fetchall("select m.nickname,m.avatar,m.credit2,m.uid from" . tablename('mc_members') . " m inner join " . tablename('mc_mapping_fans') . " f on m.uid=f.uid and f.follow=1 and f.uniacid='{$weid}' order by m.credit2 desc limit {$rank}");
        foreach ($shares as $k => $v) {
            $txsum = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $shares[$k]['uid'],
                ':credittype' => 'credit2',
                ':num' => 0
            ));
            if (empty($txsum['tx'])) {
                $shares[$k]['credit3'] = 0;
            } else {
                $shares[$k]['credit3'] = $txsum['tx'] * -1;
            }
        }
        $cfg = $this->module['config'];
        if ($cfg['paihang'] == 1) {
            foreach ($shares as $key => $value) {
                $nickname[$key] = $value['nickname'];
                $avatar[$key]   = $value['avatar'];
                $credit2[$key]  = $value['credit2'];
                $uid[$key]      = $value['uid'];
                $credit3[$key]  = $value['credit3'];
            }
            array_multisort($credit3, SORT_NUMERIC, SORT_DESC, $uid, SORT_STRING, SORT_ASC, $shares);
        }
        $mbstyle = $poster['mbstyle'];
        include $this->template('tixian/txranking');
    }
    public function doMobileHbshare()
    {
        global $_W, $_GPC;
        $pid     = $_GPC['pid'];
        $weid    = $_W['uniacid'];
        $cfg     = $this->module['config'];
        $poster  = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $type    = $_GPC['type'];
        $id      = $_GPC['id'];
        $img     = $_W['siteroot'] . 'addons/tiger_youzan/qrcode/mposter' . $id . '.jpg';
        $mbstyle = $poster['mbstyle'];
        include $this->template($mbstyle . '/hbshare');
    }
    public function doMobileRecords()
    {
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1  = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count  = count($fans1);
        $count2 = 0;
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
            if (empty($count2))
                $count2 = 0;
        }
        $cfg      = $this->module['config'];
        $sumcount = $count;
        $cfg      = $this->module['config'];
        $records  = pdo_fetchall('select * from ' . tablename('mc_credits_record') . " where uid='{$fans['uid']}' and credittype='credit1' order by createtime desc limit 20");
        $mbstyle  = $poster['mbstyle'];
        include $this->template($mbstyle . '/records');
    }
    public function doMobileTxrecords()
    {
        global $_W, $_GPC;
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit2';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s left join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)) {
            $count2 = 0;
        }
        $count    = count($fans1);
        $sumcount = $count;
        $cfg      = $this->module['config'];
        $records  = pdo_fetchall('select * from ' . tablename('mc_credits_record') . " where uid='{$fans['uid']}' and credittype='credit2' order by createtime desc limit 20");
        $mbstyle  = $poster['mbstyle'];
        include $this->template('tixian/txrecords');
    }
    public function doMobileMFan1()
    {
        global $_W, $_GPC;
        $pid       = $_GPC['pid'];
        $uid       = $_GPC['uid'];
        $level     = $_GPC['level'];
        $cfg       = $this->module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)) {
            $count2 = 0;
        }
        $sumcount   = $count;
        $credittype = 'credit1';
        if ($poster['credit']) {
            $credittype = 'credit2';
        }
        $fans1   = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,s.openid,m.createtime from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$uid}' and f.follow=1 order by m.{$credittype} desc");
        $mbstyle = $poster['mbstyle'];
        include $this->template($mbstyle . '/mfan1');
    }
    public function doMobileTxmfan1()
    {
        global $_W, $_GPC;
        $pid         = $_GPC['pid'];
        $uid         = $_GPC['uid'];
        $fans['uid'] = $_GPC['uid'];
        $weid        = $_W['weid'];
        $level       = $_GPC['level'];
        $cfg         = $this->module['config'];
        $op          = $_GPC['op'];
        $userAgent   = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        }
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $fans1  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}'", array(), 'openid');
        $count1 = count($fans1);
        if (empty($count1)) {
            $count1 = 0;
            $count2 = 0;
            $count3 = 0;
        }
        if (!empty($fans1)) {
            $fans2  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ")", array(), 'openid');
            $count2 = count($fans2);
            if (empty($count2)) {
                $count2 = 0;
            }
        }
        if (!empty($fans2)) {
            $fans3  = pdo_fetchall("select openid from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ")", array(), 'openid');
            $count3 = count($fans3);
            if (empty($count3)) {
                $count3 = 0;
            }
        }
        if ($op == 1) {
            $txrank = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid='{$uid}' order by createtime desc limit 20");
        } elseif ($op == 2) {
            if (!empty($fans1)) {
                $txrank = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans1)) . ") limit 20");
            }
        } elseif ($op == 3) {
            if (!empty($fans2)) {
                $txrank = pdo_fetchall("select * from " . tablename($this->modulename . "_share") . " where weid='{$weid}' and helpid in (" . implode(',', array_keys($fans2)) . ") limit 20");
            }
        }
        $mbstyle = $poster['mbstyle'];
        include $this->template('tixian/txmfan1');
    }
    public function doMobileMFan2()
    {
        global $_W, $_GPC;
        $pid       = $_GPC['pid'];
        $uid       = $_GPC['uid'];
        $weid      = $_GPC['i'];
        $level     = $_GPC['level'];
        $cfg       = $this->module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)) {
            $count2 = 0;
        }
        $sumcount   = $count;
        $credittype = 'credit1';
        if ($poster['credit']) {
            $credittype = 'credit2';
        }
        $fans1 = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,s.openid from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$uid}' and f.follow=1 order by m.{$credittype} desc");
        $ids   = array();
        foreach ($fans1 as $value) {
            $ids[] = $value['openid'];
        }
        if ($ids && $level == 1) {
            $fans2 = pdo_fetchall("select m.{$credittype} as credit,m.nickname,m.avatar,m.createtime from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_members') . " m on s.openid=m.uid join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', $ids) . ") and f.follow=1 order by m.{$credittype} desc");
        }
        $mbstyle = $poster['mbstyle'];
        include $this->template($mbstyle . '/mfan2');
    }
    public function doWebGoods()
    {
        global $_W;
        global $_GPC;
        load()->func('tpl');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            $goods_id = intval($_GPC['goods_id']);
            if (!empty($goods_id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE goods_id = :goods_id", array(
                    ':goods_id' => $goods_id
                ));
                if (empty($item)) {
                    message('抱歉，兑换商品不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('请输入兑换商品名称！');
                }
                if (empty($_GPC['cost'])) {
                    message('请输入兑换商品需要消耗的积分数量！');
                }
                if (empty($_GPC['price'])) {
                    message('请输入商品实际价值！');
                }
                $cost           = intval($_GPC['cost']);
                $price          = intval($_GPC['price']);
                $vip_require    = intval($_GPC['vip_require']);
                $amount         = intval($_GPC['amount']);
                $type           = intval($_GPC['type']);
                $per_user_limit = intval($_GPC['per_user_limit']);
                $data           = array(
                    'weid' => $_W['weid'],
                    'title' => $_GPC['title'],
                    'px' => $_GPC['px'],
                    'logo' => $_GPC['logo'],
                    'starttime' => strtotime($_GPC['starttime']),
                    'endtime' => strtotime($_GPC['endtime']),
                    'amount' => $amount,
                    'per_user_limit' => intval($per_user_limit),
                    'vip_require' => $vip_require,
                    'cost' => $cost,
                    'day_sum' => $_GPC['day_sum'],
                    'price' => $price,
                    'type' => $type,
                    'hot' => $_GPC['hot'],
                    'hotcolor' => $_GPC['hotcolor'],
                    'dj_link' => $_GPC['dj_link'],
                    'content' => $_GPC['content'],
                    'createtime' => TIMESTAMP
                );
                if (!empty($goods_id)) {
                    pdo_update($this->table_goods, $data, array(
                        'goods_id' => $goods_id
                    ));
                } else {
                    pdo_insert($this->table_goods, $data);
                }
                message('商品更新成功！', $this->createWebUrl('goods', array(
                    'op' => 'display'
                )), 'success');
            }
        } else if ($operation == 'delete') {
            $goods_id = intval($_GPC['goods_id']);
            $row      = pdo_fetch("SELECT goods_id FROM " . tablename($this->table_goods) . " WHERE goods_id = :goods_id", array(
                ':goods_id' => $goods_id
            ));
            if (empty($row)) {
                message('抱歉，商品' . $goods_id . '不存在或是已经被删除！');
            }
            pdo_delete($this->table_goods, array(
                'goods_id' => $goods_id
            ));
            message('删除成功！', referer(), 'success');
        } else if ($operation == 'display') {
            if (checksubmit()) {
                if (!empty($_GPC['displayorder'])) {
                    foreach ($_GPC['displayorder'] as $id => $displayorder) {
                        pdo_update($this->table_goods, array(
                            'displayorder' => $displayorder
                        ), array(
                            'goods_id' => $id
                        ));
                    }
                    message('排序更新成功！', referer(), 'success');
                }
            }
            $condition = '';
            $list      = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$_W['weid']}'  ORDER BY px ASC");
        }
        include $this->template('goods');
    }
    public function doWebAd()
    {
        global $_W;
        global $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'post') {
            $id = intval($_GPC['id']);
            if (!empty($id)) {
                $item = pdo_fetch("SELECT * FROM " . tablename($this->table_ad) . " WHERE id = :id", array(
                    ':id' => $id
                ));
                if (empty($item)) {
                    message('抱歉，广告不存在或是已经删除！', '', 'error');
                }
            }
            if (checksubmit('submit')) {
                if (empty($_GPC['title'])) {
                    message('请输入广告名称！');
                }
                $data = array(
                    'weid' => $_W['weid'],
                    'title' => $_GPC['title'],
                    'url' => $_GPC['url'],
                    'pic' => $_GPC['pic'],
                    'createtime' => TIMESTAMP
                );
                if (!empty($id)) {
                    pdo_update($this->table_ad, $data, array(
                        'id' => $id
                    ));
                } else {
                    pdo_insert($this->table_ad, $data);
                }
                message('广告更新成功！', $this->createWebUrl('ad', array(
                    'op' => 'display'
                )), 'success');
            }
        } else if ($operation == 'delete') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT id FROM " . tablename($this->table_ad) . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，广告' . $id . '不存在或是已经被删除！');
            }
            pdo_delete($this->table_ad, array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        } else if ($operation == 'display') {
            $condition = '';
            $list      = pdo_fetchall("SELECT * FROM " . tablename($this->table_ad) . " WHERE weid = '{$_W['weid']}'  ORDER BY id desc");
        }
        include $this->template('ad');
    }
    public function doWebRequest()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display_new';
        if ($operation == 'delete') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename($this->table_request) . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            } else if ($row['status'] != 'done') {
                message('未兑换商品无法删除。请兑换后删除！', referer(), 'error');
            }
            pdo_delete($this->table_request, array(
                'id' => $id
            ));
            message('删除成功！', referer(), 'success');
        } else if ($operation == 'do_goods') {
            $data = array(
                'status' => 'done'
            );
            $id   = intval($_GPC['id']);
            $row  = pdo_fetch("SELECT id FROM " . tablename($this->table_request) . " WHERE id = :id", array(
                ':id' => $id
            ));
            if (empty($row)) {
                message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
            }
            pdo_update($this->table_request, $data, array(
                'id' => $id
            ));
            message('审核通过', referer(), 'success');
        } else if ($operation == 'display_new') {
            if (checksubmit('batchsend')) {
                foreach ($_GPC['id'] as $id) {
                    $data = array(
                        'status' => 'done'
                    );
                    $row  = pdo_fetch("SELECT id FROM " . tablename($this->table_request) . " WHERE id = :id", array(
                        ':id' => $id
                    ));
                    if (empty($row)) {
                        continue;
                    }
                    pdo_update($this->table_request, $data, array(
                        'id' => $id
                    ));
                }
                message('批量兑换成功!', referer(), 'success');
            }
            $condition = '';
            if (!empty($_GPC['name'])) {
                $kw = $_GPC['name'];
                $condition .= "  AND (t1.from_user_realname like '%" . $kw . "%' OR  t1.mobile like '%" . $kw . "%' OR t1.realname like '%" . $kw . "%' OR t1.residedist like '%" . $kw . "%') ";
            }
            $pindex  = max(1, intval($_GPC['page']));
            $psize   = 20;
            $sql     = "SELECT t1.*,t2.title FROM " . tablename($this->table_request) . "as t1 LEFT JOIN " . tablename($this->table_goods) . " as t2 " . " ON  t2.goods_id=t1.goods_id AND t2.weid=t1.weid AND t2.weid='{$_W['weid']}' WHERE t1.weid = '{$_W['weid']}'  " . $condition . " ORDER BY t1.createtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
            $list    = pdo_fetchall($sql);
            $ar      = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v) {
                $fanskey[$v['from_user']] = 1;
            }
            $total = pdo_fetchcolumn($sql);
            $pager = pagination($total, $pindex, $psize);
            $fans  = fans_search(array_keys($fanskey), array(
                'realname',
                'mobile',
                'residedist',
                'alipay'
            ));
            load()->model('mc');
        } else {
            $sql     = "SELECT t1.*, t2.title FROM " . tablename($this->table_request) . "as t1 LEFT  JOIN " . tablename($this->table_goods) . " as t2 " . " ON t2.goods_id=t1.goods_id AND t1.weid=t2.weid AND t2.weid = '{$_W['weid']} WHERE t1.weid='{$_W['weid']}'   ORDER BY t1.createtime DESC";
            $list    = pdo_fetchall($sql);
            $ar      = pdo_fetchall($sql, array());
            $fanskey = array();
            foreach ($ar as $v) {
                $fanskey[$v['from_user']] = 1;
            }
            $fans = fans_search(array_keys($fanskey), array(
                'realname',
                'mobile',
                'residedist',
                'alipay'
            ));
        }
        include $this->template('request');
    }
    public function doMobileOauth()
    {
        global $_W, $_GPC;
        $code = $_GPC['code'];
        load()->func('communication');
        $weid  = intval($_GPC['weid']);
        $uid   = intval($_GPC['uid']);
        $do    = $_GPC['dw'];
        $reply = pdo_fetch('select * from ' . tablename('tiger_youzan_poster') . ' where weid=:weid order by id asc limit 1', array(
            ':weid' => $weid
        ));
        load()->model('account');
        $cfg = $this->module['config'];
        if (!empty($code)) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $cfg['appid'] . "&secret=" . $cfg['secret'] . "&code={$code}&grant_type=authorization_code";
            $ret = ihttp_get($url);
            if (!is_error($ret)) {
                $auth = @json_decode($ret['content'], true);
                if (is_array($auth) && !empty($auth['openid'])) {
                    $url       = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $auth['access_token'] . '&openid=' . $auth['openid'] . '&lang=zh_CN';
                    $ret       = ihttp_get($url);
                    $auth      = @json_decode($ret['content'], true);
                    $insert    = array(
                        'weid' => $_W['uniacid'],
                        'openid' => $auth['openid'],
                        'helpid' => $uid,
                        'nickname' => $auth['nickname'],
                        'sex' => $auth['sex'],
                        'city' => $auth['city'],
                        'province' => $auth['province'],
                        'country' => $auth['country'],
                        'headimgurl' => $auth['headimgurl'],
                        'unionid' => $auth['unionid']
                    );
                    $from_user = $_W['fans']['from_user'];
                    isetcookie('tiger_youzan_openid' . $weid, $auth['openid'], 1 * 86400);
                    $sql   = 'select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid AND openid=:openid ';
                    $where = "  ";
                    $fans  = pdo_fetch($sql . $where . " order by id asc limit 1 ", array(
                        ':weid' => $weid,
                        ':openid' => $auth['openid']
                    ));
                    if (empty($fans)) {
                        $insert['from_user'] = $from_user;
                        $insert['time']      = time();
                        if ($_W['account']['key'] == $reply['appid'])
                            $insert['from_user'] = $auth['openid'];
                        pdo_insert('tiger_youzan_member', $insert);
                    }
                    if ($do == 'Goods') {
                        $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Goods&m=tiger_youzan&openid=" . $auth['openid'] . "&wxref=mp.weixin.qq.com#wechat_redirect";
                    }
                    if ($do == 'tixian') {
                        $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Tixian&m=tiger_youzan&openid=" . $auth['openid'] . "&wxref=mp.weixin.qq.com#wechat_redirect";
                    }
                    if ($do == 'sharetz') {
                        $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Sharetz&uid=" . $uid . "&m=tiger_youzan&wxref=mp.weixin.qq.com#wechat_redirect";
                    }
                    header('location:' . $forward);
                    exit;
                } else {
                    exit('微信授权失败');
                }
            } else {
                exit('微信授权失败');
            }
        } else {
            if ($do == 'Goods') {
                $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Goods&m=tiger_youzan&wxref=mp.weixin.qq.com#wechat_redirect";
            }
            if ($do == 'tixian') {
                $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Tixian&m=tiger_youzan&wxref=mp.weixin.qq.com#wechat_redirect";
            }
            if ($do == 'sharetz') {
                $forward = $_W['siteroot'] . "app/index.php?i=" . $_W['uniacid'] . "&c=entry&do=Sharetz&uid=" . $uid . "&m=tiger_youzan&wxref=mp.weixin.qq.com#wechat_redirect";
            }
            header('location: ' . $forward);
            exit;
        }
    }
    public function doMobileSharetz()
    {
        global $_W, $_GPC;
        $weid  = intval($_GPC['weid']);
        $uid   = intval($_GPC['uid']);
        $reply = pdo_fetch('select * from ' . tablename('tiger_youzan_poster') . ' where weid=:weid order by id asc limit 1', array(
            ':weid' => $_W['uniacid']
        ));
        load()->model('account');
        $cfg = $this->module['config'];
        if (empty($_GPC['tiger_youzan_openid' . $weid])) {
            $callback = urlencode($_W['siteroot'] . 'app' . str_replace("./", "/", $this->createMobileurl('oauth', array(
                'weid' => $weid,
                'uid' => $uid,
                'dw' => 'sharetz'
            ))));
            $forward  = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $cfg['appid'] . "&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            header('location:' . $forward);
            exit();
        } else {
            $openid = $_GPC['tiger_youzan_openid' . $weid];
            if (intval($reply['tztype']) == 1) {
                $settings = $this->module['config'];
                $ips      = $this->getIp();
                $ip       = $this->GetIpLookup($ips);
                $province = $ip['province'];
                $city     = $ip['city'];
                $district = $ip['district'];
                include $this->template('sharetz');
            } else {
                header('location:' . $reply['tzurl']);
            }
        }
    }
    public function doMobileOauthkd()
    {
        global $_W, $_GPC;
        $code = $_GPC['code'];
        $weid = $_GPC['weid'];
        load()->model('account');
        $cfg = $this->module['config'];
        if (!empty($code)) {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $cfg['appid'] . "&secret=" . $cfg['secret'] . "&code={$code}&grant_type=authorization_code";
            $ret = ihttp_get($url);
            if (!is_error($ret)) {
                $auth = @json_decode($ret['content'], true);
                if (is_array($auth) && !empty($auth['openid'])) {
                    $url  = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $auth['access_token'] . '&openid=' . $auth['openid'] . '&lang=zh_CN';
                    $ret  = ihttp_get($url);
                    $auth = @json_decode($ret['content'], true);
                    isetcookie('tiger_youzan_openid' . $weid, $auth['openid'], 1 * 86400);
                    $forward = $this->createMobileurl('kending', array(
                        'weid' => $_GPC['weid'],
                        'uid' => $_GPC['uid']
                    ));
                    header('location:' . $forward);
                    exit;
                } else {
                    exit('微信授权失败');
                }
            } else {
                exit('微信授权失败');
            }
        } else {
            $forward = $this->createMobileurl('kending', array(
                'weid' => $_GPC['weid'],
                'uid' => $_GPC['uid']
            ));
            header('location: ' . $forward);
            exit;
        }
    }
    public function doMobileKending()
    {
        global $_W, $_GPC;
        $weid = $_W['uniacid'];
        $uid  = $_GPC['uid'];
        load()->model('mc');
        load()->model('account');
        $cfg = $this->module['config'];
        if (empty($_GPC['tiger_youzan_openid' . $weid])) {
            $callback = urlencode($_W['siteroot'] . 'app' . str_replace("./", "/", $this->createMobileurl('oauthkd', array(
                'weid' => $weid,
                'uid' => $uid
            ))));
            $forward  = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $cfg['appid'] . "&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            header('location:' . $forward);
            exit();
        } else {
            $openid = $_GPC['tiger_youzan_openid' . $weid];
        }
        $fans   = pdo_fetch('select * from ' . tablename('mc_mapping_fans') . ' where uniacid=:uniacid and uid=:uid order by fanid desc limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $_GPC['uid']
        ));
        $member = pdo_fetch('select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid and openid=:openid order by id desc limit 1', array(
            ':weid' => $_W['uniacid'],
            ':openid' => $openid
        ));
        if (!empty($member)) {
            $data = array(
                'from_user' => $fans['openid']
            );
            pdo_update('tiger_youzan_member', $data, array(
                'weid' => $weid,
                'openid' => $openid
            ));
            $share = pdo_fetch('select * from ' . tablename('tiger_youzan_share') . ' where weid=:weid and from_user=:from_user order by id asc limit 1', array(
                ':weid' => $_W['uniacid'],
                ':from_user' => $fans['openid']
            ));
            if (!empty($share)) {
                $data = array(
                    'jqfrom_user' => $openid,
                    'nickname' => $member['nickname'],
                    'avatar' => $member['headimgurl']
                );
                pdo_update('tiger_youzan_share', $data, array(
                    'weid' => $weid,
                    'from_user' => $fans['openid']
                ));
                $this->sendtext('亲，您已经领取过奖励了，不能重复领取，快去生成海报赚取奖励吧！', $fans['openid']);
                include $this->template('kending');
                exit;
            } else {
                if (empty($fans['uid'])) {
                    include $this->template('kending');
                    exit;
                }
                pdo_insert($this->modulename . "_share", array(
                    'openid' => $fans['uid'],
                    'nickname' => $member['nickname'],
                    'avatar' => $member['headimgurl'],
                    'createtime' => time(),
                    'parentid' => $member['helpid'],
                    'helpid' => $member['helpid'],
                    'weid' => $_W['uniacid'],
                    'from_user' => $fans['openid'],
                    'jqfrom_user' => $openid,
                    'follow' => 1
                ));
            }
            $credit1 = pdo_fetch('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $fans['uid'],
                ':credittype' => 'credit1',
                ':remark' => '关注送积分'
            ));
            $credit2 = pdo_fetch('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $fans['uid'],
                ':credittype' => 'credit2',
                ':remark' => '关注送余额'
            ));
            if (empty($credit1) || empty($credit1)) {
                $share  = pdo_fetch('select * from ' . tablename('tiger_youzan_share') . ' where weid=:weid and from_user=:from_user order by id asc limit 1', array(
                    ':weid' => $_W['uniacid'],
                    ':from_user' => $fans['openid']
                ));
                $poster = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_poster') . " WHERE weid = :weid", array(
                    ':weid' => $_W['uniacid']
                ));
                if ($poster['score'] > 0 || $poster['scorehb'] > 0) {
                    $info1 = str_replace('#昵称#', $share['nickname'], $poster['ftips']);
                    $info1 = str_replace('#积分#', $poster['score'], $info1);
                    $info1 = str_replace('#元#', $poster['scorehb'], $info1);
                    if (!empty($poster['score'])) {
                        mc_credit_update($share['openid'], 'credit1', $poster['score'], array(
                            $share['openid'],
                            '关注送积分'
                        ));
                    }
                    if (!empty($poster['scorehb'])) {
                        mc_credit_update($share['openid'], 'credit2', $poster['scorehb'], array(
                            $share['openid'],
                            '关注送余额'
                        ));
                    }
                    $this->sendtext($info1, $fans['openid']);
                    if ($share['helpid'] > 0) {
                        if ($poster['cscore'] > 0 || $poster['cscorehb'] > 0) {
                            $hmember = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where openid='{$share['helpid']}'");
                            if ($hmember['status'] == 1) {
                                include $this->template('kending');
                                exit;
                            }
                            $info2 = str_replace('#昵称#', $share['nickname'], $poster['utips']);
                            $info2 = str_replace('#积分#', $poster['cscore'], $info2);
                            $info2 = str_replace('#元#', $poster['cscorehb'], $info2);
                            if (!empty($poster['cscore'])) {
                                mc_credit_update($hmember['openid'], 'credit1', $poster['cscore'], array(
                                    $hmember['openid'],
                                    '2级推广奖励'
                                ));
                            }
                            if (!empty($poster['cscorehb'])) {
                                mc_credit_update($hmember['openid'], 'credit2', $poster['cscorehb'], array(
                                    $hmember['openid'],
                                    '2级推广奖励'
                                ));
                            }
                            $this->sendtext($info2, $hmember['from_user']);
                        }
                        if ($poster['pscore'] > 0 || $poster['pscorehb'] > 0) {
                            $fmember = pdo_fetch("SELECT * FROM " . tablename('tiger_youzan_share') . " WHERE weid = :weid and openid=:openid", array(
                                ':weid' => $_W['uniacid'],
                                ':openid' => $hmember['helpid']
                            ));
                            if ($fmember['status'] == 1) {
                                include $this->template('kending');
                                exit;
                            }
                            $info3 = str_replace('#昵称#', $share['nickname'], $poster['utips2']);
                            $info3 = str_replace('#积分#', $poster['pscore'], $info3);
                            $info3 = str_replace('#元#', $poster['pscorehb'], $info3);
                            if ($poster['pscore']) {
                                mc_credit_update($fmember['openid'], 'credit1', $poster['pscore'], array(
                                    $hmember['openid'],
                                    '3级推广奖励'
                                ));
                            }
                            if ($poster['pscorehb']) {
                                mc_credit_update($fmember['openid'], 'credit2', $poster['pscorehb'], array(
                                    $hmember['openid'],
                                    '3级推广奖励'
                                ));
                            }
                            $this->sendtext($info3, $fmember['from_user']);
                        }
                    }
                }
                include $this->template('kending');
                exit;
            } else {
                $this->sendtext('尊敬的粉丝：\n\n您已经领取过奖励了，不能重复领取，快去生成海报赚取奖励吧！', $fans['openid']);
                include $this->template('kending');
                exit;
            }
        }
        $this->sendtext('尊敬的粉丝：\n\n您已经领取过奖励了，不能重复领取，快去生成海报赚取奖励吧！', $fans['openid']);
        include $this->template('kending');
    }
    private function sendtext($txt, $openid)
    {
        global $_W;
        $acid = $_W['account']['acid'];
        if (!$acid) {
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account') . " WHERE uniacid=:uniacid ", array(
                ':uniacid' => $_W['uniacid']
            ));
        }
        $acc  = WeAccount::create($acid);
        $data = $acc->sendCustomNotice(array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array(
                'content' => urlencode($txt)
            )
        ));
        return $data;
    }
    function GetIpLookup($ip = '')
    {
        if (empty($ip)) {
            $ip = GetIp();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if (empty($res)) {
            return false;
        }
        $jsonMatches = array();
        preg_match('#\\{.+?\\}#', $res, $jsonMatches);
        if (!isset($jsonMatches[0])) {
            return false;
        }
        $json = json_decode($jsonMatches[0], true);
        if (isset($json['ret']) && $json['ret'] == 1) {
            $json['ip'] = $ip;
            unset($json['ret']);
        } else {
            return false;
        }
        return $json;
    }
    public function doMobileDiqu()
    {
        global $_W, $_GPC;
        $uid      = $_GPC['uid'];
        $ip       = $this->getIp();
        $settings = $this->module['config'];
        $ip       = $this->GetIpLookup($ip);
        $province = $ip['province'];
        $city     = $ip['city'];
        $district = $ip['district'];
        include $this->template('diqu');
    }
    function getIp()
    {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
    public function doMobileAjxdiqu()
    {
        global $_W, $_GPC;
        $diqu     = $_GPC['city'];
        $province = $_GPC['province'];
        $district = $_GPC['district'];
        $uid      = $_GPC['uid'];
        $ddtype   = $_GPC['ddtype'];
        $cfg      = $this->module['config'];
        load()->model('mc');
        $fans = pdo_fetch('select * from ' . tablename('mc_mapping_fans') . ' where uniacid=:uniacid and uid=:uid order by fanid asc limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $uid
        ));
        $user = mc_fetch($uid);
        $pos  = stripos($cfg['city'], $diqu);
        if ($ddtype == 1) {
            $nzmsg = "抱歉!\n\n核对位置失败，请先开启共享位置功能！";
            $this->sendtext($nzmsg, $fans['openid']);
            exit;
        }
        if ($pos === false) {
            $nzmsg = "抱歉!\n\n本次活动只针对【" . $cfg['city'] . "】微信用户开放\n\n您所在的位置【" . $diqu . "】未开启活动，您不能参与本次活动，感谢您的支持!";
            mc_update($uid, array(
                'resideprovince' => $province,
                'residecity' => $diqu,
                'residedist' => $district
            ));
        } else {
            mc_update($uid, array(
                'resideprovince' => $province,
                'residecity' => $diqu,
                'residedist' => $district
            ));
            $nzmsg = '位置核对成功，请点击菜单【生成海报】参加活动!';
        }
        $this->sendtext($nzmsg, $fans['openid']);
    }
    public function doMobileGoods()
    {
        global $_W, $_GPC;
        $now           = time();
        $weid          = $_W['weid'];
        $cfg           = $this->module['config'];
        $goods_list    = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$_W['weid']}' and $now < endtime and amount >= 0 order by px ASC");
        $my_goods_list = pdo_fetch("SELECT * FROM " . tablename($this->table_request) . " WHERE  from_user='{$_W['fans']['from_user']}' AND weid = '{$_W['weid']}'");
        $ad            = pdo_fetchall("SELECT * FROM " . tablename($this->table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        load()->model('account');
        $cfg       = $this->module['config'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
            $openid = 'oUvXSsv6hNi7wdmd5uWQUTS4vJTY';
            $fans   = pdo_fetch("select * from ims_mc_mapping_fans where openid='{$openid}'");
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $mc               = mc_fetch($fans['uid'], array(
                'nickname',
                'avatar',
                'credit1'
            ));
            $fans['credit1']  = $mc['credit1'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1  and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)) {
            $count2 = 0;
        }
        $sumcount  = $count;
        $from_user = $_W['openid'];
        $sql       = 'select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid AND from_user=:from_user order by id asc limit 1';
        $member    = pdo_fetch($sql, array(
            ':weid' => $_W['weid'],
            ':from_user' => $from_user
        ));
        if (empty($member)) {
            $insert = array(
                'weid' => $_W['uniacid'],
                'from_user' => $from_user,
                'openid' => $fans['openid'],
                'helpid' => $uid,
                'nickname' => $fans['nickname'],
                'sex' => $fans['tag']['sex'],
                'city' => $fans['tag']['city'],
                'province' => $fans['tag']['province'],
                'country' => $fans['tag']['country'],
                'headimgurl' => $fans['avatar'],
                'unionid' => $fans['unionid'],
                'time' => time()
            );
            pdo_insert('tiger_youzan_member', $insert);
        }
        $is_follow = true;
        $mbstyle   = 'style1';
        include $this->template('goods/' . $mbstyle . '/goods');
    }
    public function doMobileFillInfo()
    {
        global $_W, $_GPC;
        checkauth();
        $cfg        = $this->module['config'];
        $memberid   = intval($_GPC['memberid']);
        $goods_id   = intval($_GPC['goods_id']);
        $fans       = fans_search($_W['fans']['from_user']);
        $goods_info = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
        $ips        = $this->getIp();
        $ip         = $this->GetIpLookup($ips);
        $province   = $ip['province'];
        $city       = $ip['city'];
        $district   = $ip['district'];
        $mbstyle    = 'style1';
        include $this->template('goods/' . $mbstyle . '/fillinfo');
    }
    public function doMobileRequest()
    {
        global $_W, $_GPC;
        $cfg       = $this->module['config'];
        $ad        = pdo_fetchall("SELECT * FROM " . tablename($this->table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
            $openid = 'oUvXSsv6hNi7wdmd5uWQUTS4vJTY';
            $fans   = pdo_fetch("select * from ims_mc_mapping_fans where openid='{$openid}'");
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $mc               = mc_fetch($fans['uid'], array(
                'nickname',
                'avatar',
                'credit1'
            ));
            $fans['credit1']  = $mc['credit1'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
        }
        $pid        = $_GPC['pid'];
        $weid       = $_GPC['i'];
        $poster     = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $credit     = 0;
        $creditname = '积分';
        $credittype = 'credit1';
        if ($poster['credit']) {
            $creditname = '余额';
            $credittype = 'credit2';
        }
        if ($fans) {
            $mc     = mc_credit_fetch($fans['uid'], array(
                $credittype
            ));
            $credit = $mc[$credittype];
        }
        $fans1 = pdo_fetchall("select s.openid from " . tablename($this->modulename . "_share") . " s join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid='{$fans['uid']}' and f.follow=1 and s.openid<>''", array(), 'openid');
        $count = count($fans1);
        if ($fans1) {
            $count2 = pdo_fetchcolumn("select count(*) from " . tablename($this->modulename . "_share") . " s  join " . tablename('mc_mapping_fans') . " f on s.openid=f.uid where s.weid='{$weid}' and s.helpid in (" . implode(',', array_keys($fans1)) . ") and f.follow=1");
        }
        if (empty($count2)) {
            $count2 = 0;
        }
        $sumcount   = $count;
        $goods_list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " as t1," . tablename($this->table_request) . "as t2 WHERE t1.goods_id=t2.goods_id AND from_user='{$_W['fans']['from_user']}' AND t1.weid = '{$_W['weid']}' ORDER BY t2.createtime DESC");
        if (empty($goods_list)) {
            $olist = 1;
        }
        $mbstyle = 'style1';
        include $this->template('goods/' . $mbstyle . '/request');
    }
    public function doWebDhlist()
    {
        global $_W, $_GPC;
        $name   = $_GPC['name'];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        if (!empty($name))
            $where .= " and (dwnick like '%{$name}%' or dopenid = '{$name}') ";
        $sql   = "select * from " . tablename($this->modulename . "_paylog") . " where uniacid='{$_W['uniacid']}' {$where} order BY dtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $list  = pdo_fetchall($sql);
        $total = pdo_fetchcolumn($sql);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('dhlist');
    }
    public function doWebTxlist()
    {
        global $_W, $_GPC;
        $name   = $_GPC['name'];
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;
        if (!empty($name))
            $where .= " and (dwnick like '%{$name}%' or dopenid = '{$name}') ";
        $sql   = "select * from " . tablename($this->modulename . "_tixianlog") . " where uniacid='{$_W['uniacid']}' {$where} order BY dtime DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
        $list  = pdo_fetchall($sql);
        $total = pdo_fetchcolumn($sql);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('txlist');
    }
    public function tborder()
    {
        global $_W;
        $weid      = $_W['uniacid'];
        $cfg       = $this->module['config'];
        $poster    = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}' and type=2");
        $start     = date("Y-m-d H:i:s", $poster['starttime']);
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.trades.sold.get';
        $params    = array(
            'start_created' => $start
        );
        $json      = $client->post($method, $params);
        $yzorder   = $json['response']['trades'];
        $set       = pdo_fetch('select * from ' . tablename($this->modulename . "_set") . " where weid='{$_W['weid']}'");
        foreach ($yzorder as $v) {
            $goods  = pdo_fetch('select * from ' . tablename($this->modulename . "_yzgoods") . " where num_iid='{$v['num_iid']}'");
            $member = $this->postmember($v['fans_info']['fans_id']);
            $this->postjl($v, $goods, $member, $set);
        }
        return '';
    }
    private function postjl($v, $goods, $member, $set)
    {
        global $_W;
        $cfg       = $this->module['config'];
        $goodstime = strtotime($v['created']);
        $day_num   = $goodstime + $cfg['day_one'] * 86400;
        $day       = time();
        if ($v['status'] == 'TRADE_BUYER_SIGNED' && $day > $day_num) {
            $isstatus = 2;
        } else {
            $isstatus = 1;
        }
        if (empty($member['fans_type'])) {
            $member['fans_type'] = 1;
        }
        if ($member['fans_type'] == 1) {
            $bili    = $set['z1'] * $goods['zg'] / 100;
            $yongjin = $v['payment'] * $bili / 100;
        } else if ($member['fans_type'] == 2) {
            $bili    = $set['z2'] * $goods['zg'] / 100;
            $yongjin = $v['payment'] * $bili / 100;
        } else if ($member['fans_type'] == 3) {
            $bili    = $set['z3'] * $goods['zg'] / 100;
            $yongjin = $v['payment'] * $bili / 100;
        }
        $mdata = array(
            'fans_id' => $v['fans_info']['fans_id'],
            'nickname' => $v['fans_info']['fans_nickname'],
            'nickname' => $v['fans_info']['avatar'],
            'cengji' => 0,
            'isjs' => $isstatus,
            'bili' => $bili,
            'yongjin' => $yongjin
        );
        if ($v['status'] == 'WAIT_SELLER_SEND_GOODS' || $v['status'] == 'WAIT_BUYER_CONFIRM_GOODS' || $v['status'] == 'TRADE_BUYER_SIGNED') {
            $this->inorder($v, $mdata, $v['fans_info']['fans_id'], $v['fans_info']['fans_nickname']);
        }
        if (!empty($member['helpid'])) {
            $helpid1 = $member['helpid'];
            $member1 = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where weid='{$_W['weid']}' and openid='{$helpid1}'");
            if ($member1['fans_type'] == 1) {
                $bili    = $set['p1'] * $goods['level1'] / 100;
                $yongjin = $v['payment'] * $bili / 100;
            } else if ($member['fans_type'] == 2) {
                $bili    = $set['g1'] * $goods['level1'] / 100;
                $yongjin = $v['payment'] * $bili / 100;
            } else if ($member['fans_type'] == 3) {
                $bili    = $set['j1'] * $goods['level1'] / 100;
                $yongjin = $v['payment'] * $bili / 100;
            }
            $mdata1 = array(
                'fans_id' => $member1['fans_id'],
                'nickname' => $member1['nickname'],
                'cengji' => 1,
                'isjs' => $isstatus,
                'bili' => $bili,
                'yongjin' => $yongjin
            );
            if ($v['status'] == 'WAIT_SELLER_SEND_GOODS' || $v['status'] == 'WAIT_BUYER_CONFIRM_GOODS' || $v['status'] == 'TRADE_BUYER_SIGNED') {
                $this->inorder($v, $mdata1, $member1['fans_id'], $v['fans_info']['fans_nickname']);
            }
            if (!empty($member1['helpid'])) {
                $helpid2 = $member1['helpid'];
                $member2 = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where weid='{$_W['weid']}' and openid='{$helpid2}'");
                if ($member2['fans_type'] == 1) {
                    $bili    = $set['p2'] * $goods['level2'] / 100;
                    $yongjin = $v['payment'] * $bili / 100;
                } else if ($member['fans_type'] == 2) {
                    $bili    = $set['g2'] * $goods['level2'] / 100;
                    $yongjin = $v['payment'] * $bili / 100;
                } else if ($member['fans_type'] == 3) {
                    $bili    = $set['j2'] * $goods['level2'] / 100;
                    $yongjin = $v['payment'] * $bili / 100;
                }
                $mdata2 = array(
                    'fans_id' => $member2['fans_id'],
                    'nickname' => $member2['nickname'],
                    'cengji' => 2,
                    'isjs' => $isstatus,
                    'bili' => $bili,
                    'yongjin' => $yongjin
                );
                if ($v['status'] == 'WAIT_SELLER_SEND_GOODS' || $v['status'] == 'WAIT_BUYER_CONFIRM_GOODS' || $v['status'] == 'TRADE_BUYER_SIGNED') {
                    $this->inorder($v, $mdata2, $member2['fans_id'], $v['fans_info']['fans_nickname']);
                }
                if (!empty($member2['helpid'])) {
                    $helpid3 = $member2['helpid'];
                    $member3 = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where weid='{$_W['weid']}' and openid='{$helpid3}'");
                    if ($member3['fans_type'] == 1) {
                        $bili    = $set['p3'] * $goods['level3'] / 100;
                        $yongjin = $v['payment'] * $bili / 100;
                    } else if ($member['fans_type'] == 2) {
                        $bili    = $set['g3'] * $goods['level3'] / 100;
                        $yongjin = $v['payment'] * $bili / 100;
                    } else if ($member['fans_type'] == 3) {
                        $bili    = $set['j3'] * $goods['level3'] / 100;
                        $yongjin = $v['payment'] * $bili / 100;
                    }
                    $mdata3 = array(
                        'fans_id' => $member3['fans_id'],
                        'nickname' => $member3['nickname'],
                        'cengji' => 3,
                        'isjs' => $isstatus,
                        'bili' => $bili,
                        'yongjin' => $yongjin
                    );
                    if ($v['status'] == 'WAIT_SELLER_SEND_GOODS' || $v['status'] == 'WAIT_BUYER_CONFIRM_GOODS' || $v['status'] == 'TRADE_BUYER_SIGNED') {
                        $this->inorder($v, $mdata3, $member3['fans_id'], $v['fans_info']['fans_nickname']);
                    }
                }
            }
        }
        return '';
    }
    private function inorder($v, $mdata, $fans_id, $nickname)
    {
        global $_W;
        $fans     = $this->postfans($mdata['fans_id']);
        $data     = array(
            'weid' => $_W['uniacid'],
            'openid' => $fans['openid'],
            'fans_id' => $mdata['fans_id'],
            'nickname' => $fans['nickname'],
            'picurl' => $fans['avatar'],
            'tid' => $v['tid'],
            'num' => $v['num'],
            'num_iid' => $v['num_iid'],
            'price' => $v['price'],
            'pic_path' => $v['pic_path'],
            'pic_thumb_path' => $v['pic_thumb_path'],
            'title' => $v['title'],
            'type' => $v['type'],
            'buyer_type' => $v['buyer_type'],
            'buyer_nick' => $v['buyer_nick'],
            'trade_memo' => $v['trade_memo'],
            'receiver_city' => $v['receiver_city'],
            'receiver_district' => $v['receiver_district'],
            'receiver_name' => $v['receiver_name'],
            'receiver_address' => $v['receiver_address'],
            'receiver_mobile' => $v['receiver_mobile'],
            'feedback' => $v['feedback'],
            'status' => $v['status'],
            'total_fee' => $v['total_fee'],
            'payment' => $v['payment'],
            'created' => $v['created'],
            'update_time' => $v['update_time'],
            'pay_type' => $v['pay_type'],
            'cengji' => $mdata['cengji'],
            'isjs' => $mdata['isjs']
        );
        $item     = pdo_fetch('select * from ' . tablename($this->modulename . "_order") . " where tid='{$v['tid']}' and fans_id='{$fans_id}'");
        $yzopenid = $this->postopenid($mdata['fans_id']);
        load()->model('mc');
        $fans = mc_fetch($yzopenid);
        $cfg  = $this->module['config'];
        if (empty($item)) {
            $data['isjs']    = $mdata['isjs'];
            $data['bili']    = $mdata['bili'];
            $data['yongjin'] = $mdata['yongjin'];
            $ms              = pdo_insert($this->modulename . "_order", $data);
            $yzopenid        = $this->postopenid($mdata['fans_id']);
            if ($mdata['cengji'] == 0) {
                $msg = "尊敬的【" . $nickname . "】您已经支付成功!\n实付金额：" . $v['payment'] . "元\n自购奖励:" . $mdata['yongjin'] . "\n支付时间:" . $v['update_time'] . "\n";
                mc_credit_update($fans['uid'], 'credit2', $mdata['yongjin'], array(
                    $fans['uid'],
                    '余额提现红包',
                    'tiger_youzan'
                ));
            } elseif ($mdata['cengji'] == 1) {
                $msg = "你的" . $cfg['tdname1'] . "【" . $nickname . "】支付成功！\n实付金额：" . $v['payment'] . "元\n推广奖励:" . $mdata['yongjin'] . "\n支付时间:" . $v['update_time'] . "\n";
                mc_credit_update($fans['uid'], 'credit2', $mdata['yongjin'], array(
                    $fans['uid'],
                    '余额提现红包',
                    'tiger_youzan'
                ));
            } elseif ($mdata['cengji'] == 2) {
                $msg = "你的" . $cfg['tdname2'] . "【" . $nickname . "】支付成功！\n实付金额：" . $v['payment'] . "元\n推广奖励:" . $mdata['yongjin'] . "\n支付时间:" . $v['update_time'] . "\n";
                mc_credit_update($fans['uid'], 'credit2', $mdata['yongjin'], array(
                    $fans['uid'],
                    '余额提现红包',
                    'tiger_youzan'
                ));
            } elseif ($mdata['cengji'] == 3) {
                $msg = "你的" . $cfg['tdname3'] . "【" . $nickname . "】支付成功！\n实付金额：" . $v['payment'] . "元\n推广奖励:" . $mdata['yongjin'] . "\n支付时间:" . $v['update_time'] . "\n";
                mc_credit_update($fans['uid'], 'credit2', $mdata['yongjin'], array(
                    $fans['uid'],
                    '余额提现红包',
                    'tiger_youzan'
                ));
            }
            $this->sendtext($msg, $yzopenid);
        } else {
            if ($item['isjs'] == 1) {
                $data['isjs'] = $mdata['isjs'];
            }
            $ms = pdo_update($this->modulename . "_order", $data, array(
                'tid' => $v['tid'],
                'fans_id' => $fans_id
            ));
        }
        return '';
    }
    private function postmember($fans_id)
    {
        global $_W;
        $set = pdo_fetch('select * from ' . tablename($this->modulename . "_set") . " where weid='{$_W['weid']}'");
        return $member = pdo_fetch('select * from ' . tablename($this->modulename . "_share") . " where weid='{$_W['weid']}' and fans_id='{$fans_id}'");
    }
    public function postopenid($fans_id)
    {
        global $_W;
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.users.weixin.follower.get';
        $params    = array(
            'user_id' => $fans_id
        );
        $json      = $client->post($method, $params);
        return $json['response']['user']['weixin_openid'];
    }
    public function postname($fans_id)
    {
        global $_W;
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.users.weixin.follower.get';
        $params    = array(
            'user_id' => $fans_id
        );
        $json      = $client->post($method, $params);
        return $json['response']['user']['nick'];
    }
    public function postfans($fans_id)
    {
        global $_W;
        $cfg                 = $this->module['config'];
        $appId               = $cfg['yzappid'];
        $appSecret           = $cfg['yzappsecert'];
        $client              = new KdtApiClient($appId, $appSecret);
        $method              = 'kdt.users.weixin.follower.get';
        $params              = array(
            'user_id' => $fans_id
        );
        $json                = $client->post($method, $params);
        $fans['avatar']      = $json['response']['user']['avatar'];
        $fans['openid']      = $json['response']['user']['weixin_openid'];
        $fans['nickname']    = $json['response']['user']['nick'];
        $fans['follow_time'] = $json['response']['user']['follow_time'];
        $fans['province']    = $json['response']['user']['province'];
        $fans['city']        = $json['response']['user']['city'];
        $fans['sex']         = $json['response']['user']['sex'];
        $fans['union_id']    = $json['response']['user']['union_id'];
        return $fans;
    }
    public function doMobileTjproduct()
    {
        global $_W, $_GPC;
        $fans    = $_W['fans'];
        $fans_id = $_GPC['fans_id'];
        $fans    = $this->postfans($fans_id);
        $cfg     = $this->module['config'];
        $cfg     = str_replace('#昵称#', $fans['nickname'], $cfg);
        $mlist   = pdo_fetchall("SELECT * FROM " . tablename('tiger_youzan_yzgoods') . " WHERE weid = '{$_W['weid']}' and tj=1 order by px asc limit 20");
        $ad      = pdo_fetchall("SELECT * FROM " . tablename($this->table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        include $this->template('tixian/tjproduct');
    }
    public function doMobileXfrank()
    {
        global $_W, $_GPC;
        $fans = $_W['fans'];
        $cfg  = $this->module['config'];
        $op   = $_GPC['op'];
        if ($op == 1) {
            $daya = time() - 7 * 86400;
            $day  = date('Y-m-d H:i:s', $daya);
        } elseif ($op == 2) {
            $daya = time() - 30 * 86400;
            $day  = date('Y-m-d H:i:s', $daya);
        } elseif ($op == 3) {
            $daya = time() - 90 * 86400;
            $day  = date('Y-m-d H:i:s', $daya);
        }
        $mlist = pdo_fetchall("select picurl,nickname,sum(payment) as payment  from " . tablename('tiger_youzan_order') . "where created>'" . $day . "' group by nickname order by sum(payment) desc");
        $ad    = pdo_fetchall("SELECT * FROM " . tablename($this->table_ad) . " WHERE weid = '{$_W['weid']}' order by id desc");
        include $this->template('tixian/xfrank');
    }
    public function doMobileOrders()
    {
        global $_W, $_GPC;
        $weid            = $_W['weid'];
        $openid          = $_W['openid'];
        $cfg             = $this->module['config'];
        $fans_id         = $this->getfansid($openid);
        $fans            = $_W['fans'];
        $mc              = mc_fetch($fans['uid'], array(
            'nickname',
            'avatar',
            'credit1',
            'credit2',
            'uid',
            'uniacid'
        ));
        $fans['credit1'] = $mc['credit1'];
        if (empty($fans['credit1'])) {
            $fans['credit1'] = '0.00';
        }
        $op = $_GPC['op'];
        if ($op == 'wsh') {
            $wher  = ' and isjs=1';
            $mlist = pdo_fetchall("SELECT * FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' " . $wher . " order by created desc limit 20");
        } elseif ($op == 'ysh') {
            $wher  = ' and isjs=2';
            $mlist = pdo_fetchall("SELECT * FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' " . $wher . " order by created desc limit 20");
        } elseif ($op == 'yzc') {
            $mlist = pdo_fetchall('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num and module=:module  limit 20', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $fans['uid'],
                ':credittype' => 'credit2',
                ':num' => 0,
                ':module' => 'tiger_youzan'
            ));
        } elseif ($op == 'myjf') {
            $mlist = pdo_fetchall('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype ', array(
                ':uniacid' => $_W['uniacid'],
                ':uid' => $fans['uid'],
                ':credittype' => 'credit1'
            ));
        }
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) tx  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=1");
        $fans['dsh'] = $dshouhuo['tx'];
        if (empty($fans['dsh'])) {
            $fans['dsh'] = '0.00';
        }
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        if (empty($fans['ysh'])) {
            $fans['ysh'] = '0.00';
        }
        $txsum      = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num and module=:module', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $fans['uid'],
            ':credittype' => 'credit2',
            ':num' => 0,
            ':module' => 'tiger_youzan'
        ));
        $fans['tx'] = $txsum['tx'] * -1;
        if (empty($fans['tx'])) {
            $fans['tx'] = '0.00';
        }
        $fans['ktx'] = $fans['ysh'] - $fans['tx'];
        if (empty($fans['ktx'])) {
            $fans['ktx'] = '0.00';
        }
        $fans['fans_id'] = $fans_id;
        include $this->template('tixian/orders');
    }
    public function doMobileTongbu()
    {
        global $_W, $_GPC;
        $abc = $this->tborder();
        include $this->template('tixian/tongbu');
    }
    public function doMobileTixian()
    {
        global $_W, $_GPC;
        $weid      = $_W['weid'];
        $openid    = $_W['openid'];
        $cfg       = $this->module['config'];
        $poster    = pdo_fetch('select * from ' . tablename($this->modulename . "_poster") . " where weid='{$weid}'");
        $abc       = $this->tborder();
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        if (!strpos($userAgent, 'MicroMessenger')) {
            message('请使用微信浏览器打开！');
        } else {
            load()->model('mc');
            $info             = mc_oauth_userinfo();
            $fans             = $_W['fans'];
            $mc               = mc_fetch($fans['uid'], array(
                'nickname',
                'avatar',
                'credit1',
                'credit2',
                'uid',
                'uniacid'
            ));
            $fans['credit1']  = $mc['credit1'];
            $fans['credit2']  = $mc['credit2'];
            $fans['avatar']   = $fans['tag']['avatar'];
            $fans['nickname'] = $fans['tag']['nickname'];
            $fans['uid']      = $mc['uid'];
            $fans['uniacid']  = $mc['uniacid'];
        }
        $fans_id     = $this->getfansid($openid);
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) tx  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=1");
        $fans['dsh'] = $dshouhuo['tx'];
        if (empty($fans['dsh'])) {
            $fans['dsh'] = '0.00';
        }
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        if (empty($fans['ysh'])) {
            $fans['ysh'] = '0.00';
        }
        $txsum      = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num and module=:module', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $fans['uid'],
            ':credittype' => 'credit2',
            ':num' => 0,
            ':module' => 'tiger_youzan'
        ));
        $fans['tx'] = $txsum['tx'] * -1;
        if (empty($fans['tx'])) {
            $fans['tx'] = '0.00';
        }
        $fans['ktx'] = $fans['ysh'] - $fans['tx'];
        if (empty($fans['ktx'])) {
            $fans['ktx'] = '0.00';
        }
        $sql    = 'select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid AND openid=:openid order by id asc limit 1';
        $member = pdo_fetch($sql, array(
            ':weid' => $_W['weid'],
            ':openid' => $openid
        ));
        include $this->template('tixian/tixian');
    }
    function getfansid($openid)
    {
        $cfg       = $this->module['config'];
        $appId     = $cfg['yzappid'];
        $appSecret = $cfg['yzappsecert'];
        $client    = new KdtApiClient($appId, $appSecret);
        $method    = 'kdt.users.weixin.follower.get';
        $params    = array(
            'weixin_openid' => $openid
        );
        $json      = $client->post($method, $params);
        $fans_id   = $json['response']['user']['user_id'];
        return $fans_id;
    }
    public function doMobileTixianpost()
    {
        global $_W, $_GPC;
        $uid         = $_GPC['uid'];
        $fans['uid'] = $_GPC['uid'];
        $weid        = $_GPC['weid'];
        $openid      = $_GPC['openid'];
        $dhPay       = doubleval($_GPC['dhPay']);
        load()->model('mc');
        load()->model('account');
        $cfg         = $this->module['config'];
        $fans        = mc_fetch($uid, array(
            'credit2',
            'uid',
            'uniacid'
        ));
        $fans_id     = $this->getfansid($openid);
        $dshouhuo    = pdo_fetch("SELECT SUM(yongjin) ysh  FROM " . tablename('tiger_youzan_order') . " WHERE weid = '{$_W['weid']}' and fans_id='{$fans_id}' and isjs=2");
        $fans['ysh'] = $dshouhuo['ysh'];
        $txsum       = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and num<:num and module=:module', array(
            ':uniacid' => $_W['uniacid'],
            ':uid' => $fans['uid'],
            ':credittype' => 'credit2',
            ':num' => 0,
            ':module' => 'tiger_youzan'
        ));
        $fans['tx']  = $txsum['tx'] * -1;
        $fans['ktx'] = $fans['ysh'] - $fans['tx'];
        if (!$_W['isajax'])
            exit(json_encode(array(
                'success' => false,
                'msg' => '非法提交,只能通过网站提交'
            )));
        if ($dhPay > $fans['ktx']) {
            exit(json_encode(array(
                'success' => false,
                'msg' => "提现金额不能大于当前金额"
            )));
        } elseif ($dhPay < $cfg['tx_num']) {
            exit(json_encode(array(
                'success' => false,
                'msg' => "提现金额最低" . $cfg['tx_num'] . "元起"
            )));
        } elseif ($dhPay < 1) {
            exit(json_encode(array(
                'success' => false,
                'msg' => "提现金额最低1元起"
            )));
        } elseif ($dhPay > 200) {
            exit(json_encode(array(
                'success' => false,
                'msg' => "单次提现金额不能大于200元"
            )));
        } elseif ($dhPay < 0) {
            exit(json_encode(array(
                'success' => false,
                'msg' => "请输入正确的金额"
            )));
        }
        $credit2 = pdo_fetch('select * from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark  order by createtime desc limit 1', array(
            ':uniacid' => $weid,
            ':uid' => $uid,
            ':credittype' => 'credit2',
            ':remark' => '余额提现红包'
        ));
        $y       = date("Y");
        $m       = date("m");
        $d       = date("d");
        $daytime = mktime(0, 0, 0, $m, $d, $y);
        $daysum  = pdo_fetch('select count(uid) t from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark and createtime>:createtime order by createtime desc limit 1', array(
            ':uniacid' => $weid,
            ':uid' => $uid,
            ':credittype' => 'credit2',
            ':remark' => '余额提现红包',
            ':createtime' => $daytime
        ));
        $day_sum = $daysum['t'];
        $rmbsum  = pdo_fetch('select SUM(num) tx from ' . tablename('mc_credits_record') . ' where uniacid=:uniacid and uid=:uid and credittype=:credittype and remark=:remark and num<:num order by createtime desc limit 1', array(
            ':uniacid' => $weid,
            ':uid' => $uid,
            ':credittype' => 'credit2',
            ':remark' => '余额提现红包',
            ':num' => 0
        ));
        $rmb_sum = $rmbsum['tx'] * -1;
        $cfg['day_num'];
        $cfg['rmb_num'];
        if (!empty($cfg['day_num'])) {
            if (intval($day_sum) >= intval($cfg['day_num'])) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "1天之内只能兑换" . $cfg['day_num'] . "次，明天在来兑换吧！"
                )));
                exit;
            }
        }
        if (!empty($cfg['rmb_num'])) {
            if ($dhPay >= $cfg['rmb_num']) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "每个粉丝最多只能提现" . $cfg['rmb_num'] . "元"
                )));
                exit;
            }
            if (doubleval($rmb_sum) >= doubleval($cfg['rmb_num'])) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "每个粉丝最多只能提现" . $cfg['rmb_num'] . "元"
                )));
                exit;
            }
        }
        $member = pdo_fetch('select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid and id=:id order BY id DESC LIMIT 1', array(
            ':weid' => $weid,
            'id' => $_GPC['memberid']
        ));
        load()->func('logging');
        if (!$cfg['mchid']) {
            exit(json_encode(array(
                "success" => 4,
                "msg" => "商家未开启微信支付功能,请联系商家开启后申请兑换"
            )));
        }
        $dtotal_amount = $dhPay * 100;
        if ($cfg['txtype'] == 0) {
            $msg = $this->post_txhb($cfg, $_W['fans']['from_user'], $dtotal_amount, $desc);
        } elseif ($cfg['txtype'] == 1) {
            $msg = $this->post_qyfk($cfg, $_W['fans']['from_user'], $dtotal_amount, $desc);
        }
        if ($msg['message'] == 'success') {
            mc_credit_update($fans['uid'], 'credit2', -$dhPay, array(
                $fans['uid'],
                '余额提现红包',
                'tiger_youzan'
            ));
            pdo_insert('tiger_youzan_tixianlog', array(
                'uniacid' => $_W["uniacid"],
                "dwnick" => $_W['fans']['nickname'],
                "dopenid" => $_W['fans']['from_user'],
                "dtime" => time(),
                "dcredit" => $dhPay,
                "dtotal_amount" => $dtotal_amount,
                "dmch_billno" => $mch_billno,
                "dissuccess" => $dissuccess,
                "dresult" => $dresult
            ));
            exit(json_encode(array(
                'success' => 1,
                'msg' => '提现成功,请到微信窗口查收！'
            )));
        } else {
            exit(json_encode(array(
                'success' => 4,
                'msg' => $msg['message']
            )));
        }
    }
    function post_txhb($cfg, $openid, $dtotal_amount, $desc)
    {
        global $_W;
        $root                 = IA_ROOT . '/attachment/tiger_youzan/cert/' . $_W['uniacid'] . '/';
        $ret                  = array();
        $ret['code']          = 0;
        $ret['message']       = "success";
        $url                  = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        $pars                 = array();
        $pars['nonce_str']    = random(32);
        $pars['mch_billno']   = random(10) . date('Ymd') . random(3);
        $pars['mch_id']       = $cfg['mchid'];
        $pars['wxappid']      = $cfg['appid'];
        $pars['nick_name']    = $_W['account']['name'];
        $pars['send_name']    = $_W['account']['name'];
        $pars['re_openid']    = $openid;
        $pars['total_amount'] = $dtotal_amount;
        $pars['min_value']    = $dtotal_amount;
        $pars['max_value']    = $dtotal_amount;
        $pars['total_num']    = 1;
        $pars['wishing']      = '提现红包成功!';
        $pars['client_ip']    = $cfg['client_ip'];
        $pars['act_name']     = '兑换红包';
        $pars['remark']       = "来自" . $_W['account']['name'] . "的红包";
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY']  = $root . 'apiclient_key.pem';
        load()->func('communication');
        $procResult = null;
        $resp       = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult     = $resp["message"];
            $ret['code']    = -1;
            $ret['message'] = $procResult;
            return $ret;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath  = new DOMXPath($dom);
                $code   = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']    = 0;
                    $ret['message'] = "success";
                    return $ret;
                } else {
                    $error          = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']    = -2;
                    $ret['message'] = $error;
                    return $ret;
                }
            } else {
                $ret['code']    = -3;
                $ret['message'] = "3error3";
                return $ret;
            }
        }
    }
    public function post_qyfk($cfg, $openid, $amount, $desc)
    {
        global $_W;
        $root                     = IA_ROOT . '/attachment/tiger_youzan/cert/' . $_W['uniacid'] . '/';
        $ret                      = array();
        $ret['code']              = 0;
        $ret['message']           = "success";
        $ret['amount']            = $amount;
        $url                      = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars                     = array();
        $pars['mch_appid']        = $cfg['appid'];
        $pars['mchid']            = $cfg['mchid'];
        $pars['nonce_str']        = random(32);
        $pars['partner_trade_no'] = random(10) . date('Ymd') . random(3);
        $pars['openid']           = $openid;
        $pars['check_name']       = "NO_CHECK";
        $pars['amount']           = $amount;
        $pars['desc']             = "来自" . $_W['account']['name'] . "的提现";
        $pars['spbill_create_ip'] = $cfg['client_ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach ($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$cfg['apikey']}";
        $pars['sign']              = strtoupper(md5($string1));
        $xml                       = array2xml($pars);
        $extras                    = array();
        $extras['CURLOPT_CAINFO']  = $root . 'rootca.pem';
        $extras['CURLOPT_SSLCERT'] = $root . 'apiclient_cert.pem';
        $extras['CURLOPT_SSLKEY']  = $root . 'apiclient_key.pem';
        load()->func('communication');
        $procResult = null;
        $resp       = ihttp_request($url, $xml, $extras);
        if (is_error($resp)) {
            $procResult     = $resp['message'];
            $ret['code']    = -1;
            $ret['message'] = "-1:" . $procResult;
            return $ret;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if ($dom->loadXML($xml)) {
                $xpath  = new DOMXPath($dom);
                $code   = $xpath->evaluate('string(//xml/return_code)');
                $result = $xpath->evaluate('string(//xml/result_code)');
                if (strtolower($code) == 'success' && strtolower($result) == 'success') {
                    $ret['code']    = 0;
                    $ret['message'] = "success";
                    return $ret;
                } else {
                    $error          = $xpath->evaluate('string(//xml/err_code_des)');
                    $ret['code']    = -2;
                    $ret['message'] = "-2:" . $error;
                    return $ret;
                }
            } else {
                $ret['code']    = -3;
                $ret['message'] = "error response";
                return $ret;
            }
        }
    }
    public function doMobileGoodpost()
    {
        global $_W, $_GPC;
        if (!$_W['isajax'])
            exit(json_encode(array(
                'success' => false,
                'msg' => '非法提交,只能通过网站提交'
            )));
        checkauth();
        $goods_id = intval($_GPC['goods_id']);
        $type     = intval($_GPC['typea']);
        if (!empty($_GPC['goods_id'])) {
            $fans          = fans_search($_W['fans']['from_user'], array(
                'realname',
                'mobile',
                'residedist',
                'alipay',
                'credit1',
                'credit2',
                'vip',
                'uniacid'
            ));
            $goods_info    = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE goods_id = $goods_id AND weid = '{$_W['weid']}'");
            $y             = date("Y");
            $m             = date("m");
            $d             = date("d");
            $daysum        = mktime(0, 0, 0, $m, $d, $y);
            $goods_request = pdo_fetch("SELECT count(*) sn FROM " . tablename($this->table_request) . " WHERE goods_id = $goods_id AND createtime>" . $daysum . " and weid = '{$_W['weid']}' and from_user = '{$_W['fans']['from_user']}'");
            if (!empty($goods_info['day_sum'])) {
                if ($goods_request['sn'] >= $goods_info['day_sum']) {
                    exit(json_encode(array(
                        'success' => false,
                        'msg' => "每个用户1天只能兑换" . $goods_info['day_sum'] . "次,\n明天在来兑换吧！"
                    )));
                }
            }
            if ($goods_info['amount'] <= 0) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "商品已经兑空，请重新选择商品！"
                )));
            }
            if (intval($goods_info['vip_require']) > $fans['vip']) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "您的VIP级别不够，无法参与本项兑换，试试其它的吧。"
                )));
            }
            $min_idle_time = empty($goods_info['min_idle_time']) ? 0 : $goods_info['min_idle_time'] * 60;
            $replicated    = pdo_fetch("SELECT * FROM " . tablename($this->table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}' AND " . TIMESTAMP . " - createtime < {$min_idle_time}");
            if (!empty($replicated)) {
                $last_time = date('H:i:s', $replicated['createtime']);
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "{$goods_info['min_idle_time']}分钟内不能重复兑换相同物品"
                )));
            }
            if ($goods_info['per_user_limit'] > 0) {
                $goods_limit = pdo_fetch("SELECT count(*) as per_user_limit FROM " . tablename($this->table_request) . "  WHERE goods_id = $goods_id AND weid = '{$_W['weid']}' AND from_user = '{$_W['fans']['from_user']}'");
                if ($goods_limit['per_user_limit'] >= $goods_info['per_user_limit']) {
                    exit(json_encode(array(
                        'success' => false,
                        'msg' => "本商品每个用户最多可兑换" . $goods_info['per_user_limit'] . "件,试试兑换其他奖品吧！"
                    )));
                }
            }
            if ($fans['credit1'] < $goods_info['cost']) {
                exit(json_encode(array(
                    'success' => false,
                    'msg' => "积分不足, 请重新选择商品"
                )));
            }
            if (true) {
                $data = array(
                    'amount' => $goods_info['amount'] - 1
                );
                pdo_update($this->table_goods, $data, array(
                    'weid' => $_W['weid'],
                    'goods_id' => $goods_id
                ));
                $data = array(
                    'realname' => ("" == $fans['realname']) ? $_GPC['realname'] : $_W['fans']['nickname'],
                    'mobile' => ("" == $fans['mobile']) ? $_GPC['mobile'] : $fans['mobile'],
                    'residedist' => ("" == $fans['residedist']) ? $_GPC['residedist'] : $fans['residedist'],
                    'alipay' => ("" == $fans['alipay']) ? $_GPC['alipay'] : $fans['alipay']
                );
                fans_update($_W['fans']['from_user'], $data);
                $data = array(
                    'weid' => $_W['weid'],
                    'from_user' => $_W['fans']['from_user'],
                    'from_user_realname' => $_W['fans']['nickname'],
                    'realname' => $_GPC['realname'],
                    'mobile' => $_GPC['mobile'],
                    'residedist' => $_GPC['residedist'],
                    'alipay' => $_GPC['alipay'],
                    'note' => $_GPC['note'],
                    'goods_id' => $goods_id,
                    'price' => $goods_info['price'],
                    'cost' => $goods_info['cost'],
                    'createtime' => TIMESTAMP
                );
                if ($goods_info['cost'] > $fans['credit1']) {
                    exit(json_encode(array(
                        'success' => false,
                        'msg' => "系统出现未知错误，请重试或与管理员联系"
                    )));
                }
                $kjfabc  = $data['cost'];
                $kjfabc1 = $data['price'] * 100;
                if ($type == 5) {
                    if (($goods_info['price'] * 100) < 100) {
                        exit(json_encode(array(
                            "success" => 4,
                            "msg" => "最少1元起兑换"
                        )));
                    }
                    if (($goods_info['price'] * 100) > 20000) {
                        exit(json_encode(array(
                            "success" => 4,
                            "msg" => "单次最多不能超过200元红包"
                        )));
                    }
                    load()->model('mc');
                    load()->func('logging');
                    load()->model('account');
                    $cfg    = $this->module['config'];
                    $member = pdo_fetch('select * from ' . tablename('tiger_youzan_member') . ' where weid=:weid and id=:id order BY id DESC LIMIT 1', array(
                        ':weid' => $_W['weid'],
                        'id' => $_GPC['memberid']
                    ));
                    if (!$cfg['mchid']) {
                        exit(json_encode(array(
                            "success" => 4,
                            "msg" => "商家未开启微信支付功能,请联系商家开启后申请兑换"
                        )));
                    }
                    $dtotal_amount = $kjfabc * 1;
                    if ($cfg['txtype'] == 0) {
                        $msg = $this->post_txhb($cfg, $_W['fans']['from_user'], $kjfabc1, $desc);
                    } elseif ($cfg['txtype'] == 1) {
                        $msg = $this->post_qyfk($cfg, $_W['fans']['from_user'], $kjfabc1, $desc);
                    }
                    if ($msg['message'] == 'success') {
                        $data['status'] = 'done';
                        pdo_insert($this->table_request, $data);
                        mc_credit_update($fans['uid'], 'credit1', -$kjfabc, array(
                            $fans['uid'],
                            '兑换:' . $goods_info['title']
                        ));
                        pdo_insert('tiger_youzan_paylog', array(
                            'uniacid' => $_W["uniacid"],
                            "dwnick" => $_W['fans']['nickname'],
                            "dopenid" => $_W['fans']['from_user'],
                            "dtime" => time(),
                            "dcredit" => $kjfabc,
                            "dtotal_amount" => $kjfabc1,
                            "dmch_billno" => $mch_billno,
                            "dissuccess" => $dissuccess,
                            "dresult" => $dresult
                        ));
                        exit(json_encode(array(
                            'success' => 1,
                            'msg' => '兑换成功,请到微信窗口查收！'
                        )));
                    } else {
                        exit(json_encode(array(
                            'success' => 4,
                            'msg' => $msg['message']
                        )));
                    }
                    exit;
                }
                if ($type == 4) {
                    $data['status'] = 'done';
                }
                pdo_insert($this->table_request, $data);
                mc_credit_update($fans['uid'], 'credit1', -$kjfabc, array(
                    $fans['uid'],
                    '礼品兑换:' . $goods_info['title']
                ));
                exit(json_encode(array(
                    'success' => true,
                    'msg' => "扣除您{$goods_info['cost']}积分。"
                )));
            }
        } else {
            message('请选择要兑换的商品！', $this->createMobileUrl('goods', array(
                'weid' => $_W['weid']
            )), 'error');
        }
    }
    public function doMobileDoneExchange()
    {
        global $_W, $_GPC;
        $data = array(
            'status' => 'done'
        );
        $id   = intval($_GPC['id']);
        $row  = pdo_fetch("SELECT id FROM " . tablename($this->table_request) . " WHERE id = :id", array(
            ':id' => $id
        ));
        if (empty($row)) {
            message('抱歉，编号为' . $id . '的兑换请求不存在或是已经被删除！');
        }
        pdo_update($this->table_request, $data, array(
            'id' => $id
        ));
        message('兑换成功！！', referer(), 'success');
    }
    public function getCredit()
    {
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array(
            'credit1'
        ));
        return "<span  class='label label-success'>{$fans['credit1']}分</span>";
    }
    public function getCredit2()
    {
        global $_W;
        $fans = fans_search($_W['fans']['from_user'], array(
            'credit2'
        ));
        return "<span  class='label label-success'>{$fans['credit2']}元</span>";
    }
    public function doWebDownloade()
    {
        include "downloade.php";
    }
    private function getAccountLevel()
    {
        global $_W;
        load()->classs('weixin.account');
        $accObj  = WeixinAccount::create($_W['uniacid']);
        $account = $accObj->account;
        return $account['level'];
    }
    private function getAccessToken()
    {
        global $_W;
        load()->model('account');
        $acid = $_W['acid'];
        if (empty($acid)) {
            $acid = $_W['uniacid'];
        }
        $account = WeAccount::create($acid);
        $token   = $account->getAccessToken();
        return $token;
    }
    public function doMobileDe()
    {
        $ret = $this->getunionid();
        echo '<pre>';
        $auth    = @json_decode($ret['content'], true);
        $unionid = $auth['unionid'];
        echo $unionid;
        print_r($auth);
    }
    public function getunionid()
    {
        global $_W;
        $access_token = $this->getAccessToken();
        $openid       = $_W['openid'];
        $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        return $ret = ihttp_get($url);
    }
}