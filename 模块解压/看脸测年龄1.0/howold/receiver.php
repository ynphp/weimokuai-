<?php
/**
 * how-old模块微站定义
 *
 * @author 冯齐跃 158881551
 * @url http://www.wifixc.com
 */
defined('IN_IA') or exit('Access Denied');

class HowoldModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
	}
}