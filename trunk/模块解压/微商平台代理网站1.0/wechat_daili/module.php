<?php
defined('IN_IA') or exit('Access Denied');

class Wechat_dailiModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		$dailishopurl = "../addons/wechat_daili/";
		if(checksubmit()) {
			$cfg = array(
			'img' =>$_GPC['img'],
			'eimg' =>$_GPC['eimg'],
			'img1' =>$_GPC['img1'],
			'img2' =>$_GPC['img2'],
			'img3' =>$_GPC['img3'],
			'img4' =>$_GPC['img4'],
			'img5' =>$_GPC['img5'],
			'img6' =>$_GPC['img6'],
			'title' =>$_GPC['title'],
			'desc' =>$_GPC['desc'],
			'desd' =>$_GPC['descd'],
			'uuu' =>$_GPC['uuu'],
			'usina' =>$_GPC['usina'],
			'kefuq1' =>$_GPC['kefuq1'],
			'kefuq2' =>$_GPC['kefuq2'],
			'kefuq3' =>$_GPC['kefuq3'],
			'kefutel' =>$_GPC['kefutel'],
			'kefuad' =>$_GPC['kefuad'],
			'product0' =>$_GPC['product0'],
			'product1' =>$_GPC['product1'],
			'product1s' =>$_GPC['product1s'],
			'product1m' =>$_GPC['product1m'],
			'product2' =>$_GPC['product2'],
			'product2s' =>$_GPC['product2s'],
			'product2m' =>$_GPC['product2m'],
			'product3' =>$_GPC['product3'],
			'product3s' =>$_GPC['product3s'],
			'product3m' =>$_GPC['product3m'],
			'product4' =>$_GPC['product4'],
			'product4s' =>$_GPC['product4s'],
			'product4m' =>$_GPC['product4m'],
			'product5' =>$_GPC['product5'],
			'product5s' =>$_GPC['product5s'],
			'product5m' =>$_GPC['product5m'],
			'product6' =>$_GPC['product6'],
			'product6s' =>$_GPC['product6s'],
			'product6m' =>$_GPC['product6m'],
			'product7' =>$_GPC['product7'],
			'product7s' =>$_GPC['product7s'],
			'product7m' =>$_GPC['product7m'],
			'product8' =>$_GPC['product8'],
			'product8s' =>$_GPC['product8s'],
			'product8m' =>$_GPC['product8m'],
			);
			if($this->saveSettings($cfg)) {
				message('保存成功', 'refresh');
			}
		}
		if(!isset($settings['uuu'])) {
			$settings['uuu'] = 'http://www.taobao.com';
		}
		if(!isset($settings['title'])) {
			$settings['title'] = '微信营销服务平台';
		}
		if(!isset($settings['desc'])) {
			$settings['desc'] = '微信营销，微信营销服务，微信分销，微官网，微预约，微商城，微酒店，微汽车，微点餐，微服务，微房产，全网微信营销第三方服务平台';
		}
		if(!isset($settings['desd'])) {
			$settings['desd'] = '我们公司是最专业的第三方服务公司，立足于某某城市，专业服务七年，好像微信没有七年吧，先这样吧，这里自己填写咯';
		}
		if(!isset($settings['kefuq1'])) {
			$settings['kefuq1'] = '123456789';
		}
		if(!isset($settings['kefuq2'])) {
			$settings['kefuq2'] = '223456789';
		}
		if(!isset($settings['kefuq3'])) {
			$settings['kefuq3'] = '323456789';
		}
		if(!isset($settings['kefutel'])) {
			$settings['kefutel'] = '8008008800';
		}
		if(!isset($settings['kefuad'])) {
			$settings['kefuad'] = '我爱北京天安门，天安门上太阳升';
		}
		if(!isset($settings['kefuad'])) {
			$settings['kefuad'] = '我爱北京天安门，天安门上太阳升';
		}
		if(!isset($settings['product0'])) {
			$settings['product0'] = '微信活动策划、微信粉丝营销、人人分销商城服务提供企业及商家营销整体解决方案';
		}
		if(!isset($settings['product1'])) {
			$settings['product1'] = '餐饮酒店营销版';
		}
		if(!isset($settings['product1s'])) {
			$settings['product1s'] = '订餐、订房，深化餐饮及酒店营销，提供关注用户随时订餐、订座、订房，随时接收处理订单的一体化营销服务……';
		}
		if(!isset($settings['product1m'])) {
			$settings['product1m'] = '￥2800';
		}
		if(!isset($settings['product2'])) {
			$settings['product2'] = '汽车4S店服务版';
		}
		if(!isset($settings['product2s'])) {
			$settings['product2s'] = '丰富的在线预约，强大的会员系统，提供会员日活动，试驾、代驾、维修保养，一步到位！';
		}
		if(!isset($settings['product2m'])) {
			$settings['product2m'] = '￥4800';
		}
		if(!isset($settings['product3'])) {
			$settings['product3'] = '商场营销平台版';
		}
		if(!isset($settings['product3s'])) {
			$settings['product3s'] = '丰富的在线预约，整合商城资源，商铺优惠券，会员系统，从订餐、点餐到各个店铺的优惠应有尽有！';
		}
		if(!isset($settings['product3m'])) {
			$settings['product3m'] = '￥16800';
		}
		if(!isset($settings['product4'])) {
			$settings['product4'] = '品牌分销至尊版';
		}
		if(!isset($settings['product4s'])) {
			$settings['product4s'] = '裂变式分销，营销、销售、粉丝营销一体化产品，结合传统的商城销售模式，更多组件：团购、砍价、降价拍、店中店、品牌签到、限时购、会员分级折扣等，真正达到分销裂变，结合市场的特性，进行全方位的产品销售，从而达到人人客户人人销售这一最终目的裂变营销利器！';
		}
		if(!isset($settings['product4m'])) {
			$settings['product4m'] = '￥29800';
		}
		if(!isset($settings['product5'])) {
			$settings['product5'] = '家居行业品牌版';
		}
		if(!isset($settings['product5s'])) {
			$settings['product5s'] = '专门针对品牌家居行业，多达100多种品牌活动模式，提供全方位的产品品牌推广，结合丰富的异业资源，打造一体化微信营销服务，品牌杂志、品牌活动、品牌推送，一个二维码，销售行天下。公司特价产品，专享服务，托管拓客营销一体化。';
		}
		if(!isset($settings['product5m'])) {
			$settings['product5m'] = '￥9800';
		}
		if(!isset($settings['product6'])) {
			$settings['product6'] = '房产物业会员版';
		}
		if(!isset($settings['product6s'])) {
			$settings['product6s'] = '商品房展示，在线约谈，楼书设计、用户专享服务设计，从房产营销到落地服务，打造全方位的网络化终端服务。';
		}
		if(!isset($settings['product6m'])) {
			$settings['product6m'] = '￥6800';
		}
		if(!isset($settings['product7'])) {
			$settings['product7'] = '行业营销大众版';
		}
		if(!isset($settings['product7s'])) {
			$settings['product7s'] = '针对企业提供的微信微官网、产品展示等服务的产品！包装企业的网络形象，配合网络营销推广达到更好的营销效果！';
		}
		if(!isset($settings['product7m'])) {
			$settings['product7m'] = '￥3800';
		}
		if(!isset($settings['product8'])) {
			$settings['product8'] = '婚庆私人定制版';
		}
		if(!isset($settings['product8s'])) {
			$settings['product8s'] = '专门针对婚庆行业提供一体化定制型服务，独特的微首页，精美的微相册，新人祝福功能等，真正体现私人定制特色。';
		}
		if(!isset($settings['product8m'])) {
			$settings['product8m'] = '￥5800';
		}
		include $this->template('setting');
	}

}
