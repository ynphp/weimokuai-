<?php

//微赞科技 by QQ:800083075 http://www.012wz.com/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
if (!class_exists('TmessageModel')) {
    class TmessageModel extends PluginModel
    {
        function perms()
        {
            return array(
                'tmessage' => array(
                    'text' => $this->getName(),
                    'isplugin' => true,
                    'view' => '浏览',
                    'add' => '添加-log',
                    'edit' => '修改-log',
                    'delete' => '删除-log',
                    'send' => '发送-log'
                )
            );
        }
    }
}