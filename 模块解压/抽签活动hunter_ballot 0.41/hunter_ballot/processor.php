<?php
/**
 * 新年签1模块处理程序
 *
 * @author hunter
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hunter_ballotModuleProcessor extends WeModuleProcessor {

    public $table_reply  = 'hunter_ballot_reply';   
    public $table_ballot = "hunter_ballot_ballot";
    public function respond() {
        global $_W;
        $rid = $this->rule;
        $fromuser = $this->message ['from'];
        if ($rid) {
            $reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE rid = :rid", array (':rid' => $rid ) );
            // var_dump($reply);exit();
            if ($reply) {                
                $news = array ();
                $news [] = array ('title' => $reply['new_title'], 'description' => $reply['new_desc'], 'picurl' => $this->getpicurl ( $reply ['new_pic']), 'url' => $this->createMobileUrl ( 'index',array ('id' => $reply ['sid'])));
                return $this->respNews ( $news );
            }
        }
        return null;
    }
    private function getpicurl($url) {
        global $_W;
        return $_W ['attachurl'] . $url;
    }
}

