<?php
/**
 * location模块处理程序
 *
 * @author 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class LocationModuleProcessor extends WeModuleProcessor {
	public function respond() {
		// return $this->respText($this->message['location_x'].','.$this->message['location_y']);
		
		$content = $this->message['content'];
 
		if($this->message['type'] == 'trace'){
			return $this->respText('trace: '. json_encode($this->message));
		}
 
		if($this->message['type'] == 'location'){
			return $this->respText('location: '. json_encode($this->message));
		}
	}
}