<?php
/**
 * 微表白模块微站定义
 *
 * @author 晓锋
 * @url http://www.qfinfo.cn
 */
defined('IN_IA') or exit('Access Denied');

class Xiaofeng_cexpressModuleSite extends WeModuleSite {


	//后台校园表白
	public function doWebCexpress(){
		global $_W,$_GPC;
		
		$list = pdo_fetchall("SELECT * FROM".tablename('weischool_cexpress')."WHERE uniacid='{$_W['uniacid']}'");
		if ($_GPC['op'] == 'delete') {
			pdo_delete("weischool_cexpress",array('id'=>$_GPC['id']));
			message('删除成功',referer(),'success');
		}elseif ($_GPC['op'] == 'verify') {
			pdo_query("UPDATE ".tablename('weischool_cexpress')."SET cexpress_status='{$_GPC['cexpress_status']}' WHERE id='{$_GPC['id']}'");
			message('审核完成',referer(),'success');
		}
		include $this->template('cexpress');

	}
	//微信端校园表白
		public function doMobileIndex(){
			global $_GPC,$_W;
			
			//$this->wechat();
			$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
			$id = intval($_GPC['id']);
			if ($op == 'display') {
			    if (!empty($this->module['config']['cexpress_status'])) {
					$condition1 ="AND cexpress_status='1'";
				}
				$condition = '';
				//$params = array();
				if ($_GPC['keyword']) {
					$condition .= " AND receiver LIKE '%{$_GPC['keyword']}%'";
					// $params[':keyword'] = "%{$_GPC['keyword']}%";
				}
				$list = pdo_fetchall("SELECT * FROM".tablename('weischool_cexpress')."WHERE uniacid='{$_W['uniacid']}' $condition $condition1 limit 0,10");
				$count = count($list);
				include $this->template('cexpress_index');
			}elseif ($op == 'add') {
				$data = array(
						'uniacid' => $_W['uniacid'],
						'openid' => $_W['fans']['from_user'],
						'receiver' => $_GPC['receiver'],
						'sender' => $_GPC['sender'],
						'content' => $_GPC['content'],
						'createtime' => TIMESTAMP,
						'cexpress_status' => 0
					); 
				if (checksubmit('submit')) {
					if ($id) {
						# code...
					}else{
						pdo_insert('weischool_cexpress',$data);
						message('表白成功',$this->createMobileUrl('index',array('op'=>'display')));
					}
				}
				include $this->template('cexpress_add');
			}
		}

		//微信端校园表白翻页
		public function doMobileCexpressPage(){
			global $_W,$_GPC;
			
			//$this->wechat();
			$currentPage = $_GPC["page"] ;//当前页         
			$pageSize = $_GPC['pagesize'];//每页显示的记录数
		    $first = ($currentPage-1)*$pageSize;//每页记录的起始值
		    $list = pdo_fetchall("SELECT * FROM".tablename('weischool_cexpress')."WHERE uniacid='{$_W['uniacid']}' limit {$first},{$pageSize}");
		    foreach ($list as $key => $value) {
		    	$html.=" <article class='underline'>
			    
							<h2><span style='color:#78c1e5'>TO：&nbsp;".$value['receiver']."</span></h2>
							<p>".$value['content']."</p>
				            <div class='date'><span>FROM：".$value['sender']."</span></div>
			            
			        	</article>

		    			";
		    }
		    return json_encode($html);
		}
}