<?php
/**
 * 微专辑
 */

defined('IN_IA') or exit('Access Denied');
function my_quickvideo_display_order_sort($a, $b) {
	if ($a['order'] == $b['order']) {
		return 0;
	}
	return ($a['order'] > $b['order']) ? 1 : -1;
}

class QuickVideoModuleSite extends WeModuleSite {
	public $table_tape = "quickvideo_tape";
	public $table_video = "quickvideo_video";

	function __construct() {	
	}
	
	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename($this->table_tape) . ' WHERE `weid`=:weid AND `title` LIKE :title';
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		include $this->template('query');
	}
	

	public function doWebVideo() {
		global $_W;
		global $_GPC; // 获取query string中的参数
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		// 首次使用时没有试卷，直接进入新建试题界面
		if (empty($_GPC['op']) && $this->isVideoLibraryEmpty()) {
			$operation = 'post';
		}	

		if ($operation == 'post') {
			$video_id = intval($_GPC['video_id']);
			if (!empty($video_id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename($this->table_video)." WHERE video_id =".$video_id);
				if (empty($item)) {
					message('抱歉，视频不存在或是已经删除！', '', 'error');
				}
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('请输入视频名称');
				}
				if (empty($_GPC['url'])) {
					message('请输入视频链接！');
				}
				
				$data = array(
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'author' => $_GPC['author'],
					'cover' => $_GPC['cover'],
					'url' => $_GPC['url'],
					'explain' => $_GPC['explain'],
					'lyrics' => $_GPC['lyrics'],
				);
				if (!empty($video_id)) {
					pdo_update($this->table_video, $data, array('video_id' => $video_id));
				} else {
					pdo_insert($this->table_video, $data);
				}
				message('更新成功！', $this->createWebUrl('video', array('op' => 'display')), 'success');
			}
		}
		else if ($operation == 'delete') {
			$video_id = intval($_GPC['video_id']);
			$row = pdo_fetch("SELECT video_id FROM ".tablename($this->table_video)." WHERE video_id = ".$video_id);
			if (empty($row)) {
				message('抱歉，视频不存在或是已经被删除！');
			}
			pdo_delete($this->table_video, array('video_id' => $video_id));
			message('删除成功！', referer(), 'success');
		} else if ($operation == 'display') {
			$condition = '';
			$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_video)." WHERE weid = '{$_W['weid']}' $condition ORDER BY video_id DESC");
		}
		include $this->template('video');
	}

	public function doWebAnalysis() {
		echo "开发中...";
	}

	private function isVideoLibraryEmpty() {
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM ".tablename($this->table_video)." WHERE weid = '{$_W['weid']}'");
		return ($result['cnt'] <= 0);
	}

	public function doWebTape() {
		global $_W;
		global $_GPC;
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		
		// 首次使用时没有试卷，直接进入新建试题界面
		if (empty($_GPC['op']) && $this->isTapeLibraryEmpty()) {
			$operation = 'post';
		}

		if ($operation == 'post') {
			$tape_id = intval($_GPC['tape_id']);
			$video_ids = array();
			if (!empty($tape_id)) {
				$tape = pdo_fetch("SELECT * FROM ".tablename($this->table_tape)." WHERE tape_id =".$tape_id);
				if (empty($tape)) {
					message('抱歉，专辑不存在或是已经删除！', '', 'error');
				}
				$video_ids = iunserializer($tape['video_ids']);
				$video_ids_seq = iunserializer($tape['video_ids_seq']);
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('请输入专辑标题');
				}

				$new_video_ids = array();
				foreach($_GPC['video_id'] as $video_id => $on)
				{
					$new_video_ids[] = $video_id;
				}
				$new_video_ids_seq = array();
				foreach($_GPC['video_ids_seq'] as $t_cid => $t_seq) {
					// 41_1 - video 41 is the first one
					// 43_2 - video 43 is the second one
					$new_video_ids_seq[$t_cid] = $t_seq;
				}

				$data = array(
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'logo' => $_GPC['logo'],
					'background' => $_GPC['background'],
					'explain' => $_GPC['explain'],
					'video_ids' => iserializer($new_video_ids),
					'video_ids_seq' => iserializer($new_video_ids_seq),
				);
				if (!empty($tape_id)) {
					pdo_update($this->table_tape, $data, array('tape_id' => $tape_id));
				} else {
					pdo_insert($this->table_tape, $data);
				}
				message('更新成功！', $this->CreateWebUrl('Tape', array('op' => 'display')), 'success');
			}
			$condition = '';
			$video_list = pdo_fetchall("SELECT * FROM ".tablename($this->table_video)." WHERE weid = '{$_W['weid']}' $condition ORDER BY video_id DESC");
		} else if ($operation == 'delete') { //删除
			$tape_id = intval($_GPC['tape_id']);
			$row = pdo_fetch("SELECT tape_id FROM ".tablename($this->table_tape)." WHERE tape_id = ".$tape_id);
			if (empty($row)) {
				message('抱歉，专辑不存在或是已经被删除！');
			}
			pdo_delete($this->table_tape, array('tape_id' => $tape_id));
			message('删除成功！', referer(), 'success');
		} else if ($operation == 'display') {
			$condition = '';
			$tape_list = pdo_fetchall("SELECT * FROM ".tablename($this->table_tape)." WHERE weid = '{$_W['weid']}' $condition ORDER BY tape_id DESC");
		}
		
		include $this->template('tape');
	}
		
	private function isTapeLibraryEmpty() {
		global $_W;
		$result = pdo_fetch("SELECT count(*) as cnt FROM ".tablename($this->table_tape)." WHERE weid = '{$_W['weid']}'");
		return ($result['cnt'] <= 0);
	}

	public function doWebHelp() {
		global $_W;
		include $this->template('help');
	}





	public function doMobileCenter() {
		global $_W, $_GPC;
		//$this->checkAuth();
		$profile = fans_search($_W['fans']['from_user']);
		$tape_list = pdo_fetchall("SELECT * FROM ".tablename($this->table_tape)." WHERE weid={$_W['weid']}");
		foreach($tape_list as &$item) {
			$item['explain'] = $this->deleteSpace(strip_tags(htmlspecialchars_decode($item['explain'])));
			$item['logo'] = $this->getPicUrl($item['logo']);
		}
		include $this->template('center');
	}

	public function doMobileTape() {
		global $_W, $_GPC;
		$preview = intval($_GPC['preview']);
		$video_id = intval($_GPC['video_id']);
		$tape_id = intval($_GPC['tape_id']);
	
		if (!$preview) {
			$this->checkTapeState();	
		}
	
		// 检查用户权限		
		if (!$preview) {
			//$this->checkAuth();
			//$fans = mc_require($_W['fans']['from_user'], array('realname', 'mobile'));
			$fans = fans_search($_W['fans']['from_user']);
		} else {
			$fans = fans_search($_GPC['from_user']);
		}

		// support video and tape
		if ($preview && !empty($video_id)) {
			
			
			$where = "weid = '{$_W['weid']}' AND video_id = $video_id";
			$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_video)." WHERE {$where}", array(), "video_id");

		} else if (!empty($tape_id)) {
			
			
			$where = "weid = '{$_W['weid']}'";
			$sql = ("SELECT * FROM ".tablename($this->table_tape). "WHERE {$where} AND tape_id=$tape_id");
			$tape = pdo_fetch($sql);
			if (empty($tape)) {
				message("抱歉，专辑[{$tape_id}]]不存在或是已经删除！", '', 'error');
			}else {
        $video_ids_arr = iunserializer($tape['video_ids']);
        if (count($video_ids_arr) > 0)
        {
          $video_ids_str = join(',', $video_ids_arr);
          $list = pdo_fetchall("SELECT * FROM ".tablename($this->table_video)." WHERE {$where} AND video_id in ($video_ids_str)", array(), "video_id");
        } else {
          message("本专辑下还没有任何视频，请选择其它专辑.", referer(), "error");
        }
			}

			$ids_seq = iunserializer($tape['video_ids_seq']);
			//按照ids_seq排序专辑
			foreach($list as &$t_elem) {
				$t_elem['order'] = (empty($ids_seq[$t_elem['video_id']]) ? 0 : $ids_seq[$t_elem['video_id']]); 
			}
			usort($list, "my_quickvideo_display_order_sort");
		} else {
			message('必须指定专辑！', '', 'error');
		}
		$current = null;
		if (empty($video_id)) {
			$current = $list[0];
		} else {
			foreach($list as $m) {
				if ($m['video_id'] == $video_id) {
					$current = $m;
					break;
				}
			}
		}
		include $this->template('player');
	}


	private function getBackgroundPicUrl($url) {
		global $_W;
		if (empty($url)) {
			$day = date("d", time()) % 12;
			$r = $_W['siteroot'] . "./addons/quickvideo/images/bg/bg".$day.".jpg";
		} else {
			if(!preg_match('/^(http|https)/', $url)) {  //如果是相对路径
				$r = $_W['attachurl'] .  $url;
			} else {
				$r = $url;
			}
		}
		return $r;
	}


	private function getPicUrl($url) {
		global $_W;
		if (empty($url)) {
			$r = $_W['siteroot'] . "./addons/quickvideo/images/default_cover.jpg";
			/*} else {
				if(!preg_match('/^(http|https)/', $url)) {  //如果是相对路径
					$r = $_W['attachurl'] .  $url;
				} else {
					$r = $url;
				}	
			}*/
		} else {
			if(!preg_match('/^(http|https)/', $url)) {  //如果是相对路径
				$r = $_W['attachurl'] .  $url;
			} else {
				$r = $url;
			}
		}
		return $r;
	}

	private function checkAuth() {
		global $_W;
		if (empty($_W['fans']['from_user'])) {
			include $this->template('auth');
			exit;
		} else {
			checkauth(); // fallback to org check
		}
	}


	private function getTape($tape_id) {
		global $_W;
		$tape_id = intval($tape_id);
		$where = "weid = '{$_W['weid']}'  AND tape_id={$tape_id}";
		$sql_tape_info = "SELECT * FROM " . tablename($this->table_tape) . "WHERE {$where}";	
		$tape = pdo_fetch($sql_tape_info);
		if (empty($tape)) {
			message("对不起，专辑[{$tape_id}]不存在，可能已经被删除！", '', 'error');
		}
		return $tape;
	}

	private function checkTapeState() {
		global $_W, $_GPC;

		$where = "weid = '{$_W['weid']}'  AND tape_id={$_GPC['tape_id']}";
		$sql_tape_info = "SELECT * FROM " . tablename($this->table_tape) . "WHERE {$where}";	
	
		$tape = pdo_fetch($sql_tape_info);

		if (empty($tape)) {
			message("对不起，专辑[{$_GPC['tape_id']}]不存在，可能已经被删除！", '', 'error');
		}
	}

	private function deleteSpace($str) {
		$str = trim($str);
		$str = strtr($str,"\t","");
		$str = strtr($str,"\r\n","");
		$str = strtr($str,"\r","");
		$str = strtr($str,"\n","");
		$str = strtr($str," ","");
		$str = str_replace('&nbsp;', "",$str);
		return trim($str);
	}

        public function getCategoryTiles() {
                global $_W;
		$list = pdo_fetchall("SELECT * FROM ".tablename('quickvideo_tape')." WHERE weid={$_W['weid']}");
		$urls[] = array('title' => '视频中心', 'url' => $this->createMobileUrl('Center'));
		if (!empty($list)) {
			foreach($list as $item) {
				$urls[] = array('title' => $item['title'], 'url' => $this->createMobileUrl('Tape', array("tape_id" => $item['tape_id'])));		
			}
		}
		return $urls;
	}
}
