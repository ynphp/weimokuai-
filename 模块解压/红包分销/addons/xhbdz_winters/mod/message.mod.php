<?php

defined('IN_IA') or exit('Access Denied');

class message {
    
    public function sendCustomNotice($openid, $msg, $url = '')
        {
        
        $account = m('common')->getAccount();
        if (!empty($url)) {
            $msg .= "<a href='{$url}'>点击查看详情</a>";
        }
        return $account->sendCustomNotice(array(
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => array(
                'content' => urlencode($msg)
            )
        ));
    }
    public function sendImage($openid, $mediaid)
    {
        $account = m('common')->getAccount();
        return $account->sendCustomNotice(array(
            'touser' => $openid,
            'msgtype' => 'image',
            'image' => array(
                'media_id' => $mediaid
            )
        ));
    }

}