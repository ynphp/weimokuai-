<?php
/**
 * 微信钱包红包, 商户红包
 *
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/microb_redpack');

class Microb_RedpackModuleSite extends WeModuleSite {
    
    protected function auth() {
        global $_W;
        session_start();
        $openid = $_SESSION['__:proxy:openid'];
        require_once MB_ROOT . '/source/Fans.class.php';
        $f = new Fans();
        if(!empty($openid)) {
            $exists = $f->getOne($openid, true);
            if(!empty($exists)) {
                return $exists;
            }
        }
        
        $api = $this->module['config']['api'];
        if(empty($api)) {
            message('系统还未开放');
        }
        $callback = $_W['siteroot'] . 'app' . substr(substr($this->createMobileUrl('auth'), 1), 0, -39);
        $callback = urlencode($callback);
        $state = $_SERVER['REQUEST_URI'];
        $stateKey = substr(md5($state), 0, 8);
        $_SESSION['__:proxy:forward'] = array($stateKey, $state);
        $forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$api['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$stateKey}#wechat_redirect";
        header('Location: ' . $forward);
        exit;
    }
    
    public function doMobileAuth() {
        global $_GPC, $_W;
        session_start();
        $api = $this->module['config']['api'];
        if(empty($api)) {
            message('系统还未开放');
        }
        $code = $_GPC['code'];
        load()->func('communication');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$api['appid']}&secret={$api['secret']}&code={$code}&grant_type=authorization_code";
        $resp = ihttp_get($url);
        if(is_error($resp)) {
            message('系统错误, 详情: ' . $resp['message']);
        }
        $auth = @json_decode($resp['content'], true);
        if(is_array($auth) && !empty($auth['openid'])) {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$auth['access_token']}&openid={$auth['openid']}&lang=zh_CN";
            $resp = ihttp_get($url);
            if(is_error($resp)) {
                message('系统错误');
            }
            $info = @json_decode($resp['content'], true);
            if(is_array($info) && !empty($info['openid'])) {
                $user = array();
                $user['uniacid']         = $_W['uniacid'];
                $user['openid']          = $info['openid'];
                $user['unionid']         = $info['unionid'];
                $user['nickname']        = $info['nickname'];
                $user['gender']          = $info['sex'];
                $user['city']            = $info['city'];
                $user['state']           = $info['province'];
                $user['avatar']          = $info['headimgurl'];
                $user['country']         = $info['country'];
                if(!empty($user['avatar'])) {
                    $user['avatar'] = rtrim($user['avatar'], '0');
                    $user['avatar'] .= '132';
                }
                
                require_once MB_ROOT . '/source/Fans.class.php';
                $f = new Fans();
                $f->save($user);
                
                $_SESSION['__:proxy:openid'] = $user['openid'];
                $forward = $_SESSION['__:proxy:forward'][1];
                unset($_SESSION['__:proxy:forward']);
                header('Location: ' . $forward);
                exit();
            }
        }
        message('系统错误');
    }
    
    protected function getActivity($verify = true, $verifyParams = array()) {
        $activity = $this->module['config']['activity'];
        if(empty($activity)) {
            $activity = array();
            $activity['type'] = 'direct';
            $activity['fee']['downline'] = '1';
            $activity['fee']['upline'] = '1';
            $activity['limit'] = '1';
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
        if($verify) {
            if(empty($activity) || empty($activity['title'])) {
                return error(-1, '活动还没有设置好, 请咨询客服');
            }
            if($activity['time']['start'] > TIMESTAMP || $activity['time']['end'] < TIMESTAMP) {
                return error(-2, '活动还未开始, 敬请期待');
            }
            if(!empty($activity['state'])) {
                if($activity['state'] != $verifyParams['user']['state']) {
                    return error(-3, '您不在本次活动范围内, 不能参与活动哦');
                }
                if(!empty($activity['city'])) {
                    if($activity['city'] != $verifyParams['user']['city']) {
                        return error(-3, '您不在本次活动范围内, 不能参与活动哦');
                    }
                }
            }
        }
        return $activity;
    }
    
    public function doWebActivity() {
        //session_start();
        //$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
        //debug
        global $_W, $_GPC;
        if($_W['ispost']) {
            $input = array_elements(array('title', 'provider', 'wish', 'remark', 'fee', 'type', 'state', 'city', 'helps', 'label', 'time', 'banner', 'rules', 'guide', 'logo', 'image', 'stitle', 'content'), $_GPC);
            $input['time']['start'] = strtotime($input['time']['start'] . ':00');
            $input['time']['end'] = strtotime($input['time']['end'] . ':59');
            $setting = $this->module['config'];
            $setting['activity'] = $input;
            if($this->saveSettings($setting)) {
                message('保存活动选项成功', 'refresh');
            }
        }
        $activity = $this->getActivity(false);
        $activity['time']['start'] = date('Y-m-d H:i', $activity['time']['start']);
        $activity['time']['end'] = date('Y-m-d H:i', $activity['time']['end']);
        load()->func('tpl');
        include $this->template('activity');
    }
    
    public function doWebApi() {
        global $_W, $_GPC;
        if(checksubmit()) {
            load()->func('file');
            mkdirs(MB_ROOT . '/cert');
            $r = true;
            if(!empty($_GPC['cert'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/microb_redpack/cert/ 目录可写');
            }
            $input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip'), $_GPC);
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
        include $this->template('setting');
    }
    
    public function doWebRecords() {
        global $_W, $_GPC;
        $filters = array();
        if(!empty($_GPC['nickname'])) {
            $filters['nickname'] = $_GPC['nickname'];
        }
        if(!empty($_GPC['status'])) {
            $filters['status'] = $_GPC['status'];
        }
        
        $pindex = intval($_GPC['page']);
        $pindex = max($pindex, 1);
        $psize = 15;
        $total = 0;
        
        require_once MB_ROOT . '/source/Fans.class.php';
        $f = new Fans();
        $ds = $f->getAll($filters, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);
        include $this->template('records');
    }
    
    public function doWebSend() {
        global $_W, $_GPC;
        require_once MB_ROOT . '/source/Fans.class.php';
        $f = new Fans();

        $uid = intval($_GPC['uid']);
        $user = $f->getOne($uid);
        if(empty($user)) {
            exit('错误的访问');
        }
        $activity = $this->getActivity(true, array('user' => $user));
        if(is_error($activity)) {
            message($activity['message']);
        }
        $ret = $this->send($user);
        if(is_error($ret)) {
            exit($ret['message']);
        } else {
            exit('success');
        }
    }
    
    public function doWebEntry() {
        global $_W;
        $url = $_W['siteroot'].'app/' . substr($this->createMobileUrl('get'), 2);
        include $this->template('entry');
    }
    
    public function doWebManual() {
        header('Location: http://bbs.we7.cc/thread-6754-1-1.html');
    }
    
    public function doWebQr() {
        global $_GPC;
        $raw = @base64_decode($_GPC['raw']);
        if(!empty($raw)) {
            include MB_ROOT . '/source/phpqrcode.php';
            QRcode::png($raw, false, QR_ECLEVEL_Q, 4);
        }
    }

    protected function send($user) {
        global $_W;
        $activity = $this->getActivity(true, array('user' => $user));
        if(is_error($activity)) {
            message($activity['message']);
        }
        $api = $this->module['config']['api'];
        if(empty($api)) {
            return error(-2, '系统还未开放');
        }

        require_once MB_ROOT . '/source/Shared.class.php';
        $s = new Shared();

        $got = $s->getOneRecord($user['uid']);
        if(!empty($got)) {
            if($got['status'] == 'success') {
                return error(-3, '您已经成功领取本次活动的红包了');
            } else {
            }
        } else {
            $rec = array();
            $rec['uid'] = $user['uid'];
            $rec['type'] = $activity['type'];

            $rec['helps'] = $s->helpsCount($user['uid']);
            $fee = rand($activity['fee']['downline'] * 100, $activity['fee']['upline'] * 100);
            $rec['fee'] = sprintf('%.2f', $fee / 100);
            $rec['snapshoot'] = serialize($activity);
            $rec['dateline'] = TIMESTAMP;
            $rec['status'] = 'created';
            $ret = $s->createRecord($rec);
            if(is_error($ret)) {
                return error(-3, '领取红包失败. 请稍后重试, 或者联系我们的客服人员');
            } else {
                $got = array();
                $got['id'] = $ret;
                $got['fee'] = $rec['fee'];
            }
        }
        
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        load()->func('communication');
        $pars = array();
        $pars['nonce_str'] = random(32);
        $pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $got['id']);
        $pars['mch_id'] = $api['mchid'];
        $pars['wxappid'] = $api['appid'];
        $pars['nick_name'] = $activity['provider'];
        $pars['send_name'] = $activity['provider'];
        $pars['re_openid'] = $user['openid'];
        $pars['total_amount'] = floatval($got['fee']) * 100;
        $pars['min_value'] = $pars['total_amount'];
        $pars['max_value'] = $pars['total_amount'];
        $pars['total_num'] = 1;
        $pars['wishing'] = $activity['wish'];
        $pars['client_ip'] = $api['ip'];
        $pars['act_name'] = $activity['title'];
        $pars['remark'] = $activity['remark'];
        $pars['logo_imgurl'] = tomedia($activity['image']);
        $pars['share_content'] = $activity['content'];
        $pars['share_imgurl'] = tomedia($activity['image']);
        
        $pars['share_url'] = $_W['siteroot'].'app/' . substr(substr($this->createMobileUrl('entry', array('owner'=>$user['uid'])), 2), 0, -39);
        
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$api['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'];
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'];
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'];

        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)) {
            $setting = $this->module['config'];
            $setting['api']['error'] = $resp['message'];
            $this->saveSettings($setting);
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $setting = $this->module['config'];
                    $setting['api']['error'] = $error;
                    $this->saveSettings($setting);
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
        }

        if(is_error($procResult)) {
            $s->touchRecord($user['uid'], $procResult['message']);
        } else {
            $s->touchRecord($user['uid']);
        }
        return $procResult;
    }
}
