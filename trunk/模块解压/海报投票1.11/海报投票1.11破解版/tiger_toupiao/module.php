<?php
/**
 * 积分宝模块微站定义
 *
 * @author 老虎
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('OB_ROOT', IA_ROOT . '/attachment/tiger_toupiao');
class Tiger_toupiaoModule extends WeModule {
	
	public function settingsDisplay($settings) {
		global $_GPC,$_W;
        //echo '<pre>';
           // print_r($settings);
           // exit;
        
        load ()->func ( 'tpl' );       
		if (checksubmit()) {
            
             load()->func('file');
             mkdirs(OB_ROOT . '/cert/'.$_W['uniacid']);
             $r=true;
    
            if(!empty($_GPC['cert'])) { 
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem', trim($_GPC['cert']));
                $r = $r && $ret;               
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem', trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(OB_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem', trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /attachment/tiger_toupiao/cert/ 目录可写');
            }
            //echo '<pre>';
            //print_r($_GPC);
           // exit;
			$cfg = array(
                'tiger_toupiao_fansnum'=>$_GPC['tiger_toupiao_fansnum'],
				'tiger_toupiao_usr' => $_GPC['tiger_toupiao_usr'],
                'tiger_toupiao_usr' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['tiger_toupiao_usr']),ENT_QUOTES),
                'nbfchangemoney' => $_GPC['nbfchangemoney'],
				'nbfhelpgeturl'=>$_GPC['nbfhelpgeturl'],
				'nbfwxpaypath'=>$arr_json,
                'mchid'=>$_GPC['mchid'],
                'apikey'=>$_GPC['apikey'],
                'appid'=>$_GPC['appid'],
                'txtype'=>$_GPC['txtype'],
                'secret'=>$_GPC['secret'],
                'client_ip'=>$_GPC['client_ip'],
                'szurl' => $_GPC ['szurl'],
                'szcolor' => $_GPC ['szcolor'],
                'rmb_num' => $_GPC ['rmb_num'],
                'tdname1' => $_GPC ['tdname1'],
                'tdname2' => $_GPC ['tdname2'],
                'tdname3' => $_GPC ['tdname3'],
                'fwlocation'=>$_GPC ['fwlocation'],
                'yzurl' => $_GPC ['yzurl'],
                'tgurl' => $_GPC ['tgurl'], 
                'tx_num' => $_GPC ['tx_num'],                
                'desc'=>$_GPC ['desc'],
                'day_num' => $_GPC ['day_num'],
                'day_one' => $_GPC ['day_one'],
                'hztype' => $_GPC ['hztype'],
                'copyright' => $_GPC ['copyright'],
                'txinfo' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['txinfo']),ENT_QUOTES),
                'info0' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['info0']),ENT_QUOTES),
                'info1' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['info1']),ENT_QUOTES),
                'info2' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['info2']),ENT_QUOTES),
                'info3' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['info3']),ENT_QUOTES),
                'qxinfo' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['qxinfo']),ENT_QUOTES),
                'cfinfo' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['cfinfo']),ENT_QUOTES),
                'fxxzinfo1' => htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['fxxzinfo1']),ENT_QUOTES),//未满提示
                'locationtype'=>$_GPC['locationtype'],
                'jiequan'=>$_GPC['jiequan'],
                'paihang'=>$_GPC['paihang'],
                'yymsg'=>$_GPC['yymsg'],
                'head'=>$_GPC['head'],
                'yzappid'=>$_GPC['yzappid'],
                'hbsctime'=>$_GPC['hbsctime'],
                'hbcsmsg'=>htmlspecialchars_decode(str_replace('&quot;','&#039;',$_GPC ['hbcsmsg']),ENT_QUOTES),
                'AppKey'=>$_GPC['AppKey'],
                'appSecret'=>$_GPC['appSecret'], 
                'tptype'=>$_GPC['tptype'], 
                'scorename'=>$_GPC['scorename'],//积分名称自定义
                'rscore'=>$_GPC['rscore'],//取消关注扣除1或-1
                'qr_score'=>$_GPC['qr_score'],//是否开启扫码奖励 0 1
                'qr_score0'=>$_GPC['qr_score0'],//首次扫码奖励
                'qr_score1'=>$_GPC['qr_score1'],//一级推广奖励
                'qr_score2'=>$_GPC['qr_score2'],//二级推广奖励
                'qr_score3'=>$_GPC['qr_score3'],//三级推广奖励
                'dd_score'=>$_GPC['dd_score'],//是否开启订单奖励 0 1
                'dd_score0'=>$_GPC['dd_score0'],//自购奖励
                'dd_score1'=>$_GPC['dd_score1'],//一级推广奖励
                'dd_score2'=>$_GPC['dd_score2'],//二级推广奖励
                'dd_score3'=>$_GPC['dd_score3'],//三级推广奖励
                'yrscore'=>$_GPC['yrscore'],//取消是否扣除 1 或 -1为扣除
                'yj_score'=>$_GPC['yj_score'],//是否开启佣金
                'yj_score0'=>$_GPC['yj_score0'],//自购佣金
                'yj_score1'=>$_GPC['yj_score1'],//一级佣金奖励
                'yj_score2'=>$_GPC['yj_score2'],//二级佣金奖励
                'yj_score3'=>$_GPC['yj_score3'],//三级佣金奖励
                'fans_type'=>$_GPC['fans_type'],//关注粉丝等级 
                'fxxz'=>$_GPC['fxxz'],//分销限制类型
                'fxxzinfo'=>$_GPC['fxxzinfo'],   //分销限制金额或是数字             
                'yzappsecert'=>$_GPC['yzappsecert'],
                'city'=>$_GPC['city']
			);
			if ($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		include $this->template('settings');
	}
}