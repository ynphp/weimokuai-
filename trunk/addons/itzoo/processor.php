<?php
/**
 * location模块处理程序
 *
 * @author
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class ItzooModuleProcessor extends WeModuleProcessor
{
    public function respond()
    {


        $content = $this->message['content'];


//        return $this->respText($content);


        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $uniacid = $_W['uniacid'];
        $from_user = $_W['fans']['from_user'];
        $fromuser = authcode(base64_decode($_GPC['from_user']), 'DECODE');
        $acid = $_W['acid'];
        if (empty($acid)) {
//            $acid = pdo_fetchcolumn("select share_acid from " . tablename('kang_bigwheel_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
        }
        //print_r($_W);
        //exit;



        if (empty($from_user)) {
            //没有取得OPENID设置时间为OPENID保存分享记录
            $from_user = '无OPENID' . TIMESTAMP;
        }
        if ($from_user != $fromuser) {


            $sql_user = "select userCode, love from " . "itzoo_userinfo" . " where openid = '$from_user'";

            $articles = pdo_fetchall($sql_user);
            if (!empty($articles)) {



                foreach ($articles as $row) {

//                        $urls[] = array('title' => $row['userCode'], 'url' => $this->createMobileUrl('index', array('id' => $row['rid'])));

                    $rst = "你的编号是：" . $row['userCode'] . "\n爱心值：" . $row['love'];

                    return $this->respText($rst);

                }
            }
        }

    }
}


