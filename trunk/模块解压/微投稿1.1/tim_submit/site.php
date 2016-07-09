<?php
/**
 * Timfan design模块处理程序
 *
 * @author Tim Fan
 * QQ:1026073477
 * @url http://i-fanr.com/
 */
defined('IN_IA') or exit('Access Denied');

class Tim_submitModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		include $this->template('index');
	}
	public function doMobileSubmit() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$today_date = date("Y-m-d");
		$item = pdo_fetch("SELECT * FROM ".tablename('tim_submit_set')." WHERE uniacid=:uniacid and today = :today", array(':today' => $today_date, 'uniacid' => $uniacid));
		if(empty($item)) { 
			echo "<script>alert('还未创建今日投稿');</script>";
		}else{ 

			include $this->template('index');
		}
		
	}

	public function doMobileCreate() {
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$today_date = date("Y-m-d");
		$item = pdo_fetch("SELECT * FROM ".tablename('tim_submit_set')." WHERE uniacid=:uniacid and today = :today", array(':today' => $today_date, 'uniacid' => $uniacid));
		$sid = $item['sid'];
		$name = $_GPC['name'];
		$tel = $_GPC['tel'];
		$title = $_GPC['title'];
		$content = $_GPC['content'];
		$count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tim_submit_article')." WHERE sid=:sid and name=:name",array(':sid' => $sid, ':name' => $name));
		$user = pdo_fetch("SELECT * FROM ".tablename('tim_submit_user')." WHERE uniacid=:uniacid and name=:name",array(':uniacid' => $uniacid, ':name' => $name));
		

		if(intval($count) >= intval($item['article_num'])){ 
			echo "<script>alert('您今天已经提交上限了');</script>";
			include $this->template('index');
		}else{ 
			if(empty($user)){ 
				$users = array(
					'uniacid'=>$uniacid,
					'name' =>$name,
					'tel'=>$tel,
					'total_cent' =>$item['cent']
				);
				pdo_insert('tim_submit_user', $users);
			}else{ 
				$datas = array(
					'total_cent' =>$user['total_cent']+$item['cent']
				);
				pdo_update('tim_submit_user', $datas, array('uid' => $user['uid']));
			}
			$data = array(
				'sid'=>$sid,
				'uniacid'=>$uniacid,
				'name' =>$name,
				'tel'=>$tel,
				'title' =>$title,
				'content' => $content,
				'adate' => $today_date
			);
			pdo_insert('tim_submit_article', $data);
			echo "<script>alert('提交成功!恭喜获得".$item['cent']."积分');</script>";
			include $this->template('index');
		}

		
	}

	public function doWebCent() { 
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		if('del' == $op){//删除
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$sqls = "delete from  ".tablename('tim_submit_user')."  where uid in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$uid = intval($_GPC['uid']);
			$row = pdo_fetch("SELECT uid FROM ".tablename('tim_submit_user')." WHERE uid = :uid", array(':uid' => $uid));
			if (empty($row)) {
				//dump($_GPC);
				message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('cent', array('op' => 'display')), 'error');
			}
			pdo_delete('tim_submit_user', array('uid' => $uid));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
		$pindex = max(1, intval($_GPC['page']));
		$psize =20;//每页显示	
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " WHERE uniacid=$uniacid and  AND name LIKE '%".$_GPC['keyword']."%'  ";
		}
		$list = pdo_fetchall("SELECT *  FROM ".tablename('tim_submit_user') ." $condition  ORDER BY uid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tim_submit_user')." $condition" );
		$pager = pagination($total, $pindex, $psize);
		include $this->template('user');
		}
	}

	public function doWebList() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->func('tpl');
		if('post' == $op){//添加或修改
			$aid = intval($_GPC['aid']);
			if(!empty($aid)){
			$item = pdo_fetch("SELECT * FROM ".tablename('tim_submit_article')." where aid=$aid and uniacid=$uniacid");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			if(checksubmit('submit')){
				empty ($_GPC['name'])?message('亲,标题不能为空'):$name=$_GPC['name'];
			$tel =$_GPC['tel'];
			$title =$_GPC['title'];
			$content =$_GPC['content'];
			$sid = $item['sid'];
				$data = array(
					'aid'=>$aid,
					'uniacid'=>$uniacid,
					'name' =>$name,
					'tel'=>$tel,
					'sid'=>$sid,
					'title' =>$title,
					'content' => $content
					
				);
				
				if(empty($aid)){
						pdo_insert('tim_submit_article', $data);//添加数据
						message('数据添加成功！', $this->createWebUrl('list', array('op' => 'display')), 'success');
				}else{
						pdo_update('tim_submit_article', $data, array('aid' => $aid));
						message('数据更新成功！', $this->createWebUrl('list', array('op' => 'display')), 'success');
				}
				
			}else{
				include $this->template('list');
			}
			
		}else if('del' == $op){//删除
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$sqls = "delete from  ".tablename('tim_submit_article')."  where aid in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$aid = intval($_GPC['aid']);
			$row = pdo_fetch("SELECT aid FROM ".tablename('tim_submit_article')." WHERE aid = :aid", array(':aid' => $aid));
			if (empty($row)) {
				//dump($_GPC);
				message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('list', array('op' => 'display')), 'error');
			}
			pdo_delete('tim_submit_article', array('aid' => $aid));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " WHERE uniacid=$uniacid and  AND name LIKE '%".$_GPC['keyword']."%'  ";
			}
			
			$list = pdo_fetchall("SELECT *  FROM ".tablename('tim_submit_article') ." $condition  ORDER BY aid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tim_submit_article')." $condition" );
			$pager = pagination($total, $pindex, $psize);
			include $this->template('list');
		}
		
	}

	public function doWebToday() {
		global $_W,$_GPC;
		$uniacid =$_W['uniacid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		load()->func('tpl');
		if('post' == $op){//添加或修改
			$sid = intval($_GPC['sid']);
			if(!empty($sid)){
			$item = pdo_fetch("SELECT * FROM ".tablename('tim_submit_set')." where sid=$sid and uniacid=$uniacid");
			empty($item)?message('亲,数据不存在！', '', 'error'):"";	
			}
			if(checksubmit('submit')){
				$cent = $_GPC['cent'];
				$article_num = $_GPC['article_num'];
				$today = $_GPC['today'];
				$info = $_GPC['info'];
				$share_content = $_GPC['share_content'];
				$share_title = $_GPC['share_title'];
				$share_icon = $_GPC['share_icon'];
				$data = array(
					'uniacid' => $uniacid,
					'cent' => $cent,
					'article_num' => $article_num,
					'today' => $today,
					'info' => $info,
					'share_content' => $share_content, 
					'share_title' => $share_title, 
					'share_icon' => $share_icon
				);
				
				if(empty($sid)){
						pdo_insert('tim_submit_set', $data);//添加数据
						message('数据添加成功！', $this->createWebUrl('today', array('op' => 'display')), 'success');
				}else{
						pdo_update('tim_submit_set', $data, array('sid' => $sid));
						message('数据更新成功！', $this->createWebUrl('today', array('op' => 'display')), 'success');
				}
				
			}else{
				include $this->template('today');
			}
			
		}else if('del' == $op){//删除
			if(isset($_GPC['delete'])){
				$ids = implode(",",$_GPC['delete']);
				$sqls = "delete from  ".tablename('tim_submit_set')."  where sid in(".$ids.")"; 
				pdo_query($sqls);
				message('删除成功！', referer(), 'success');
			}
			$sid = intval($_GPC['sid']);
			$row = pdo_fetch("SELECT sid FROM ".tablename('tim_submit_set')." WHERE sid = :sid", array(':sid' => $sid));
			if (empty($row)) {
				//dump($_GPC);
				message('抱歉，数据不存在或是已经被删除！', $this->createWebUrl('list', array('op' => 'display')), 'error');
			}
			pdo_delete('tim_submit_set', array('sid' => $sid));
			message('删除成功！', referer(), 'success');
			
		}else if('display' == $op){//显示
			$pindex = max(1, intval($_GPC['page']));
			$psize =20;//每页显示
			
				$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " WHERE uniacid=$uniacid and  AND name LIKE '%".$_GPC['keyword']."%'  ";
			}
			
			$list = pdo_fetchall("SELECT *  FROM ".tablename('tim_submit_set') ." $condition  ORDER BY sid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);//分页
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tim_submit_set')." $condition" );
			$pager = pagination($total, $pindex, $psize);
			include $this->template('today');
		}

	}
	
	
}
