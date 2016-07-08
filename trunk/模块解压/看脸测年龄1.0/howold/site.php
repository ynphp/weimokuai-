<?php
/**
 * how-old模块微站定义
 *
 * @author 冯齐跃 158881551
 * @url http://www.wifixc.com
 */
defined('IN_IA') or exit('Access Denied');

class HowoldModuleSite extends WeModuleSite {

	public function doWebManage() {
		global $_W, $_GPC;
        if (checksubmit('submit')) {
            $fanids = array();
            foreach($_GPC['delete'] as $v) {
                $fanids[] = intval($v);
            }
            pdo_query("DELETE FROM " . tablename('qiyue_howold') . " WHERE id IN ('" . implode("','", $fanids) . "')");
            message('删除成功！', 'referer', 'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 12;
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('qiyue_howold')." WHERE uniacid = :uniacid", array(":uniacid" => $_W['uniacid']));
        $list = pdo_fetchall("SELECT * FROM ".tablename('qiyue_howold')." WHERE uniacid = :uniacid ORDER BY `id` DESC LIMIT ".($pindex - 1) * $psize.','.$psize, array(":uniacid" => $_W['uniacid']));
        if(!empty($list)){
        	foreach ($list as &$val) {
        		$url = $this->createMobileUrl('show', array('id'=>$val['id']), true);
                $val['url'] = $_W['siteroot'].'app/'.$url;
        	}
        	unset($val);
        }
        $pager = pagination($total, $pindex, $psize);
		include $this->template('manage');
	}

	public function doMobileShow(){
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		if(empty($id)){
			message('缺少参数！');
		}
        $item = pdo_fetch("SELECT * FROM " . tablename("qiyue_howold") . " WHERE id = :id", array(":id" => $id));
        if(empty($item)){
            message('该信息不存！');
        }
		$item['data'] = base64_decode($item['data']);

        $mod = $this->module['config'];
        $_share = $mod['share'];
        if(empty($share)){
            $_share['title'] = '刚“策”了下我的照.片.面.相.哖.龄，';
            $_share['desc'] = '看脸的世界，你也来吧!';
            $_share['imgUrl'] = MODULE_URL.'static/img/photo-icon.png';
        }
        if(!empty($item['picurl'])){
            $_share['imgUrl'] = $item['picurl'];
        }
        $_share['link'] = ltrim($this->createMobileUrl('show',array('id'=>$item['id']),true),'./');
        $_share['link'] = $_W['siteroot'].'app/'.$_share['link'];

        $data = json_decode($item['data'],true);
        if(isset($data['Faces'][0]['attributes']['age'])){
            $age = $data['Faces'][0]['attributes']['age'];
            if($age <= 6){
                $_share['title'].= $age.'岁了，O(∩_∩)O~ 哈哈，萌萌哒！！！';
            }
            elseif($age <= 16){
                $_share['title'].= '才'.$age.'岁，O(∩_∩)O~ 哈哈，萌萌哒！！';
            }
            elseif($age <= 23){
                $_share['title'].= $age.'岁，(¯(∞)¯) 哈哈，小鲜肉！！';
            }
            elseif($age <= 29){
                $_share['title'].= $age.'岁，(＝^ω^＝) 小鲜肉！！';
            }
            elseif($age <= 40){
                $_share['title'].= $age.'岁了，（*>.<*） 成熟型男！！';
            }
            elseif($age <= 60){
                $_share['title'].= $age.'岁了，O(∩_∩)O~ 风采依旧！';
            }
            else{
                $shareTiele .= $age.'岁，O(∩_∩)O~ 风采依旧！';
            }
        }
        else{
            $_share['title'].= '还很嫩，O(∩_∩)O，你也来“策策”看你的哖.龄吧！';
        }

		include $this->template('show');
	}

    // 重写url
    protected function createMobileUrl_($do, $query = array(), $noredirect = false) {
        global $_W;
        $query['do'] = $do;
        $query['m'] = strtolower($this->modulename);
        $url = murl('entry', $query, $noredirect);
        $parse = parse_url($url);
        parse_str($parse['query'], $query_arr);
        unset($query_arr['wxref'],$query_arr['from'],$query_arr['isappinstalled']);
        $query_string = http_build_query($query_arr);
        $url = $_W['siteroot'].'qy'.random(3).'/'.base64_encode($query_string);
        return $url;
    }
}