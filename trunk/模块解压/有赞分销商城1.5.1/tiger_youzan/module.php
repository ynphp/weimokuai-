<?php
defined('IN_IA') or exit('Access Denied');
define('OB_ROOT', IA_ROOT . '/attachment/tiger_youzan');
class Tiger_youzanModule extends WeModule
{
    public function settingsDisplay($settings)
    {
        global $_GPC, $_W;
        load()->func('tpl');
        if (checksubmit()) {
            load()->func('file');
            mkdirs(OB_ROOT . '/cert/' . $_W['uniacid']);
            $r = true;
            if (!empty($_GPC['cert'])) {
                $ret = file_put_contents(OB_ROOT . '/cert/' . $_W['uniacid'] . '/apiclient_cert.pem', trim($_GPC['cert']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['key'])) {
                $ret = file_put_contents(OB_ROOT . '/cert/' . $_W['uniacid'] . '/apiclient_key.pem', trim($_GPC['key']));
                $r   = $r && $ret;
            }
            if (!empty($_GPC['ca'])) {
                $ret = file_put_contents(OB_ROOT . '/cert/' . $_W['uniacid'] . '/rootca.pem', trim($_GPC['ca']));
                $r   = $r && $ret;
            }
            if (!$r) {
                message('证书保存失败, 请保证 /attachment/tiger_youzan/cert/ 目录可写');
            }
            $cfg = array(
                'tiger_youzan_fansnum' => $_GPC['tiger_youzan_fansnum'],
                'tiger_youzan_usr' => $_GPC['tiger_youzan_usr'],
                'nbfchangemoney' => $_GPC['nbfchangemoney'],
                'nbfhelpgeturl' => $_GPC['nbfhelpgeturl'],
                'nbfwxpaypath' => $arr_json,
                'mchid' => $_GPC['mchid'],
                'apikey' => $_GPC['apikey'],
                'appid' => $_GPC['appid'],
                'txtype' => $_GPC['txtype'],
                'secret' => $_GPC['secret'],
                'client_ip' => $_GPC['client_ip'],
                'szurl' => $_GPC['szurl'],
                'szcolor' => $_GPC['szcolor'],
                'rmb_num' => $_GPC['rmb_num'],
                'tdname1' => $_GPC['tdname1'],
                'tdname2' => $_GPC['tdname2'],
                'tdname3' => $_GPC['tdname3'],
                'yzurl' => $_GPC['yzurl'],
                'tgurl' => $_GPC['tgurl'],
                'tx_num' => $_GPC['tx_num'],
                'desc' => $_GPC['desc'],
                'day_num' => $_GPC['day_num'],
                'day_one' => $_GPC['day_one'],
                'hztype' => $_GPC['hztype'],
                'copyright' => $_GPC['copyright'],
                'txinfo' => htmlspecialchars_decode(str_replace('&quot;', '&#039;', $_GPC['txinfo']), ENT_QUOTES),
                'locationtype' => $_GPC['locationtype'],
                'jiequan' => $_GPC['jiequan'],
                'paihang' => $_GPC['paihang'],
                'head' => $_GPC['head'],
                'yzappid' => $_GPC['yzappid'],
                'yzappsecert' => $_GPC['yzappsecert'],
                'city' => $_GPC['city']
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
        include $this->template('settings');
    }
}