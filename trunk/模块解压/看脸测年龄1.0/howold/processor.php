<?php
/**
 * how-old模块微站定义
 *
 * @author 冯齐跃 158881551
 * @url http://www.wifixc.com
 */
defined('IN_IA') or exit('Access Denied');

class HowoldModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W,$_GPC;
		$picurl = $this->message['picurl'];
		$picurl = rtrim($picurl, '/0').'/640';
		set_time_limit(0); 
		$ch = curl_init('http://www.how-old.net/Home/Analyze?isTest=False&faceUrl='.urlencode($picurl));
		curl_setopt($ch, CURLOPT_POST, 1);
		$user_agent ="Baiduspider+(+http://www.baidu.com/search/spider.htm)";//这里模拟的是百度蜘蛛 
		curl_setopt($ch, CURLOPT_POSTFIELDS, '');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER,"http://www.how-old.net/");
		curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain;charset=UTF-8'));
		$data = curl_exec($ch);
		curl_close($ch);
		$result=str_replace('\\', '', $data);
		$result=str_replace('rn', '', $result);
		$result=str_replace('"[', '[', $result);
		$result=str_replace(']"', ']', $result);
		$result=trim($result,'"');
		$result=ltrim($result,'"');
		$result=json_decode($result,true);

		if (count($result['Faces'])>0){
			pdo_insert('qiyue_howold', array(
				'uniacid' => $_W['uniacid'],	
				'uid' => $_W['member']['uid'],
				'picurl' => $picurl,
				'data' => base64_encode(json_encode($result)),
				'addtime' => TIMESTAMP
			));
			$id = pdo_insertid();
			return $this->respText('<a href="'.$this->createMobileUrl('show',array('id'=>$id),false).'">测试结果出来啦，请点击查看！</a>');
			//return $this->respText("<a href='http://www.how-old.net/'>点我测试</a>");
		}
		else{
			return $this->respText('/::<拜托~请发有脸的并且是非宠物的照片吧！保证不打你~');
		}
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