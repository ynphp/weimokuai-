<?php
/**
 * 女神来了模块定义
 *
 * @author 幻月科技
 * @url http://bbs.fmoons.com/
 */
defined('IN_IA') or exit('Access Denied');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$fm_list = pdo_fetchall('SELECT * FROM '.tablename($this->table_reply).' WHERE uniacid= :uniacid order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $uniacid) );
		$pager = pagination($total, $pindex, $psize);
		
		if (!empty($fm_list)) {
			foreach ($fm_list as $mid => $list) {
				$count = pdo_fetch("SELECT count(id) as tprc FROM ".tablename($this->table_log)." WHERE rid= ".$list['rid']."");
				//$count1 = pdo_fetch("SELECT count(id) as share FROM ".tablename($this->table_log)." WHERE rid= ".$list['rid']." AND afrom_user != ''");
				$count1 = pdo_fetch("SELECT COUNT(id) as share FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $list['rid']));
				$count2 = pdo_fetch("SELECT count(id) as ysh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '1' ");
				$count3 = pdo_fetch("SELECT count(id) as wsh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '0' ");
				$count4 = pdo_fetch("SELECT count(id) as cyrs FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");
		        $fm_list[$mid]['user_tprc'] = $count['tprc'];//投票人次
		        $fm_list[$mid]['user_share'] = $count1['share'] + pdo_fetchcolumn("SELECT sum(sharenum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");//分享人数
		        $fm_list[$mid]['user_ysh'] = $count2['ysh'];//已审核
		        $fm_list[$mid]['user_wsh'] = $count3['wsh'];//未审核
		        $totalrq = pdo_fetch("SELECT hits,xuninum FROM ".tablename($this->table_reply_display)." WHERE rid= ".$list['rid']."");
		        $fm_list[$mid]['user_cyrs'] = $count4['cyrs'] + $totalrq['xuninum'];//参与人数
				
				 $fm_list[$mid]['user_hits'] =   $fm_list[$mid]['user_cyrs'] +  $totalrq['hits'] + pdo_fetchcolumn("SELECT sum(hits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."") + pdo_fetchcolumn("SELECT sum(xnhits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");
				
				
			}
		}
		$styles = pdo_fetchall('SELECT * FROM '.tablename($this->table_templates).' WHERE uniacid= :uniacid or uniacid = 0 order by `name` desc,`createtime` desc', array(':uniacid' => $uniacid));
		
		include $this->template('index');
