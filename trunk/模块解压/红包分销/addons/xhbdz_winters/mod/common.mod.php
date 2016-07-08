<?php
defined('IN_IA') or exit('Access Denied');
class common
{
    public function getAccount()
    {
        global $_W;
        if (!empty($_W['acid'])) {
            return WeAccount::create($_W['acid']);
        } else {
            $acid = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `uniacid`=:uniacid LIMIT 1", array(
                ':uniacid' => $_W['uniacid']
            ));
            return WeAccount::create($acid);
        }
        return false;
    }
    public function Api()
    {
//        $api = file_get_contents('http://shouquan.wtsgod.com/api.php?url=' . $_SERVER['HTTP_HOST']);
//        $Api = json_decode($api, true);
//        if ($Api['MsgRet'] == 'error') {
//            die("<!DOCTYPE html>
//                <html>
//                    <head>
//                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
//                        <title>" . $Api['Msg'] . "</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
//                    </head>
//                    <body>
//                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>" . $Api['Msg'] . "</h4></div></div></div>
//                    </body>
//                </html>");
//        }
    }
}
?>