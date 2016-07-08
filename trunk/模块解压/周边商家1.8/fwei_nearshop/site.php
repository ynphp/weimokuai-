<?php
/**
 * 周边商店模块微站定义
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_nearshopModuleSite extends WeModuleSite {

	public function doMobileList() {
		//这个操作被定义用来呈现 功能封面
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$sql = 'SELECT * FROM '.tablename('fwei_nearshop')." WHERE weid='{$weid}' ORDER BY id ASC";
		$list = pdo_fetchall($sql);
		$config = $this->module['config'];
		include $this->template('list');
	}

	public function doMobileDetail(){
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$id = intval( $_GPC['id'] );
		$item = pdo_fetch('SELECT * FROM '.tablename('fwei_nearshop')." WHERE weid = '{$weid}' AND id='{$id}' LIMIT 1");
		if( empty($item) ){
			message('参数错误');
		}
		$config = $this->module['config'];
		include $this->template('detail');
	}
	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		load()->func('tpl');
		global $_GPC,  $_W;
		$weid=$_W["uniacid"];
		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$where =" AND title like '%{$_GPC['keys']}%'";
		
		$list = pdo_fetchall( 'SELECT * FROM '.tablename('fwei_nearshop').' WHERE weid = '.$weid.$where.' ORDER BY id DESC  LIMIT '. ($pindex -1) * $psize . ',' .$psize );
		$total = pdo_fetchcolumn( 'SELECT COUNT(*) FROM '.tablename('fwei_nearshop').' WHERE weid = '.$weid . $where );
		$pager = pagination($total, $pindex, $psize);

		include $this->template('list');
	}
	public function doWebCreate() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_GPC,  $_W;
		
		$weid = $_W["uniacid"];
		$id = intval( $_GPC['id'] );
		
		if (checksubmit('submit')) {
			$insert_data = array(
					'weid'	=>	$weid,
					'title' => $_GPC['title'],
					'thumb' => $_GPC['thumb'],
					'content' => $_GPC['content'],
					'phone' => $_GPC['phone'],
					'qq' => $_GPC['qq'],
					'province' => $_GPC['dis']['province'],
					'city' =>$_GPC['dis']['city'],
					'dist' => $_GPC['dis']['district'],
					'address' => $_GPC['address'],
					'lng' => $_GPC['baidumap']['lng'],
					'lat' => $_GPC['baidumap']['lat'],
					'industry1' => $_GPC['industry']['parent'],
					'industry2' => $_GPC['industry']['child'],
					'createtime'	=>	time(),
					'outlink'	=>	$_GPC['outlink'],
			);
			//转换百度地图经纬度为腾讯地图经纬度
			if( $insert_data['lng'] && $insert_data['lat'] ){
				load()->func('communication');
				$res = ihttp_get( "http://apic.map.qq.com/translate/?type=3&points={$insert_data['lng']},{$insert_data['lat']}&output=jsonp&pf=jsapi" );
				if( $res['code'] == 200 && $res['content'] ){
					$res = json_decode($res['content'], true);
					$insert_data['soso_lng'] = $res['detail']['points'][0]['lng'];
					$insert_data['soso_lat'] = $res['detail']['points'][0]['lat'];
				}
			}
			if( $id ){
				pdo_update('fwei_nearshop', $insert_data, array('id' => $id, 'weid'=>$weid));
			} else {
				pdo_insert('fwei_nearshop', $insert_data);
			}
			message('商家设置成功！', $this->createWebUrl('list'), 'success');
		}
		$sql = 'SELECT * FROM ' . tablename('fwei_nearshop') . " WHERE `id` = :id AND `weid`= :weid";
		$item = pdo_fetch($sql, array(':id' => $id, ':weid'=>$weid));
		$reside = array();
		if( $item ){
			$reside['province'] = $item['province'];
			$reside['city'] = $item['city'];
			$reside['district'] = $item['dist'];
		}
		load()->func('tpl');
		include $this->template('create');
	}

	public function doWebDelete(){
		global $_GPC,  $_W;
		
		$weid = $_W["uniacid"];
		$id = intval( $_GPC['id'] );
		pdo_delete('fwei_nearshop', array('id'=>$id, 'weid'=>$weid));
		message('操作成功！', $this->createWebUrl('list'), 'success');
	}

}