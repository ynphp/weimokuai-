<?php
/**
 * codeMonkey:631872807
 */
defined('IN_IA') or exit('Access Denied');
define("MON_XS_VOICE", "mon_xsvoice");
define("MON_XS_VOICE_RES", "../addons/" . MON_XS_VOICE . "/");
define("MEDIA_PATH", IA_ROOT."/addons/mon_xsvoice/media/");
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/dbutil.class.php";
require IA_ROOT . "/addons/" . MON_XS_VOICE . "/oauth2.class.php";
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/value.class.php";
require_once IA_ROOT . "/addons/" . MON_XS_VOICE . "/monUtil.class.php";


/**
 * Class Mon_XSVoiceModuleSite
 */
class Mon_XSVoiceModuleSite extends WeModuleSite
{
    public $weid;
    public $acid;
    public $oauth;
    public static $USER_COOKIE_KEY = "__monxsvoiceuser";



    public static $KJ_STATUS_WKS = 0;//未开始
    public static $KJ_STATUS_ZC = 1;//正常
    public static $KJ_STATUS_JS = 2;//结束
    public static $KJ_STATUS_XD = 3;//已下单
    public static $KJ_STATUS_GM = 4;//已购买


    function __construct()
    {
        global $_W;
        $this->weid = $_W['uniacid'];

        $this->oauth = new Oauth2("", "");


    }


    /**
     * 活动管理
     */
    public function  doWebXsManage()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_VOICE) . " WHERE weid =:weid  ORDER BY createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $this->weid));
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_VOICE) . " WHERE weid =:weid ", array(':weid' => $this->weid));

            $pager = pagination($total, $pindex, $psize);
        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(DBUtil::$TABLE_VOICE_FIREND, array("vid" => $id));
            pdo_delete(DBUtil::$TABLE_VOICE_USER, array("vid" => $id));
            pdo_delete(DBUtil::$TABLE_VOICE, array('id' => $id));
            message('删除成功！', referer(), 'success');
        }

        include $this->template("voice_manage");

    }


    /**
     * author: codeMonkey QQ:631872807
     * 用户砍价页
     */
    public function  doMobileIndex(){
        global $_W, $_GPC;
        $vid = $_GPC['vid'];
        $voice = DBUtil::findById(DBUtil::$TABLE_VOICE, $vid);
        MonUtil::emtpyMsg($voice, "寻声活动删除或不存在!");

        $openid = $this->getOpenid();
        $userInfo = $this->getClientUserInfo($openid);
        $user = $this->findJoinUser($vid, $openid);
        $follow=0;
        if (!empty($_W['fans']['follow'])){
            $follow=1;//已关注
        }

        include $this->template('index');
    }


    /**
     * author: codeMonkey QQ:631872807
     * 音乐上传
     */
    public function  doMobileUpload()
    {
        global $_W, $_GPC;

        $vid = $_GPC['vid'];
        $mid = $_GPC['mid'];
        $uname = $_GPC['uname'];
        $company = $_GPC['company'];
        $utel = $_GPC['utel'];
        $voice = DBUtil::findById(DBUtil::$TABLE_VOICE, $vid);
        $res = array();
        if (empty($voice)) {
            $res['code'] = 501;
            $res['msg'] = "音乐活动删除或不存在";
            echo json_encode($res);
            exit;
        }

        if (empty($mid)) {
            $res['code'] = 502;
            $res['msg'] = "音乐文件不正常，稍后再试!";
            echo json_encode($res);
            exit;
        }

        if (empty($_W['fans']['follow'])){
            $res['code'] = 503;
            $res['msg'] = "请关注公众账号再进行录制上传哦!";
            echo json_encode($res);
            exit;
        }

        $openid = $this->getOpenid();

        $clientUserInfo = $this->getClientUserInfo($openid);

        $file_path = '';
        $user_data = array(
            'vid' => $voice['id'],
            'uname' => $uname,
            'company' => $company,
            'openid' => $clientUserInfo['openid'],
            'nickname' => $clientUserInfo['nickname'],
            'headimgurl' => $clientUserInfo['headimgurl'],
            'media_id' => $mid,
            'media_path'=>$file_path,
            'createtime'=>TIMESTAMP,
            'tel' => $utel
        );

        DBUtil::create(DBUtil::$TABLE_VOICE_USER, $user_data);
        $userId = pdo_insertid();
        $res['code'] = 200;
        $res['uid'] = $userId;
        echo json_encode($res);

    }


    public function getClientUserInfo($openid)
    {
        global $_W;
        if (!empty($openid) && ($_W['account']['level'] == 3 || $_W['account']['level'] == 4)) {
            load()->classs('weixin.account');
            $accObj = WeixinAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();

            if (empty($access_token)) {
                message("获取accessToken失败");
            }
            $userInfo = $this->oauth->getUserInfo($access_token, $openid);
            return $userInfo;
        }
    }


    /**
     * author: codeMonkey QQ:631872807
     * 排行帮
     */
    public function  doMobileVoiceList(){
        global $_W, $_GPC;
        $vid=$_GPC['vid'];
        $pindex = max(1, intval($_GPC['page']));
        $psize = 15;
        $list = pdo_fetchall("SELECT u.*,(select sum(f.zan) from ".tablename(DBUtil::$TABLE_VOICE_FIREND)." f where f.uid=u.id) as fzan FROM " . tablename(DBUtil::$TABLE_VOICE_USER) . " u WHERE u.vid =:vid  ORDER BY fzan desc, createtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':vid' => $vid));
        //$list = pdo_fetchall("SELECT u.*,(select sum(f.zan) from ".tablename(DBUtil::$TABLE_VOICE_FIREND)." f where f.uid=u.id) as fzan FROM " . tablename(DBUtil::$TABLE_VOICE_USER) . " u WHERE u.vid =:vid  ORDER BY fzan desc, createtime DESC, id DESC LIMIT 0,2", array(':vid' => $vid));
        $res=array('code'=>200,'data'=>$list);
        echo json_encode($res);
    }


    public function doWebDeleteUser()
    {
        global $_GPC, $_W;

        foreach ($_GPC['idArr'] as $k => $uid) {
            $id = intval($uid);
            if ($id == 0)
                continue;
            pdo_delete(DBUtil::$TABLE_VOICE_FIREND, array("uid" => $id));
            pdo_delete(DBUtil::$TABLE_VOICE_USER, array("id" => $id));

        }
        echo json_encode(array('code' => 200));
    }

    /**
     * author: codeMonkey QQ:631872807
     * 播放页面
     */
    public function  doMobilePlay(){
        global $_W, $_GPC;
        $vid=$_GPC['vid'];
        $uid=$_GPC['uid'];

        if(empty($uid)){//随机
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid ", array(':vid' =>$vid));
            $randomIndex=rand(0,$total-1);
            $list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid  ORDER BY zan desc, createtime DESC, id DESC LIMIT " . $randomIndex. ',' . 1, array(':vid' => $vid));
            $user=$list[0];
            $uid=$user['id'];
        }else{
            $user=DBUtil::findById(DBUtil::$TABLE_VOICE_USER,$uid);
        }
        $follow=0;
        if (!empty($_W['fans']['follow'])){
            $follow=1;//已关注
        }
        $voice=DBUtil::findById(DBUtil::$TABLE_VOICE,$vid);

        $openid = $this->getOpenid();
        $yzan=0;
        if(!empty($openid)){//openid不为空查询是不是已经点过站了
            $firend=DBUtil::findUnique(DBUtil::$TABLE_VOICE_FIREND,array(':uid'=>$uid,':fopenid'=>$openid));

            if(!empty($firend)){
                $yzan=$firend['zan'];
            }

        }


      $userP= $this->findUserP($user,$vid);
      $leastZan=$this->getLeastZan($user,$vid);

        include $this->template("play");

    }


    /**
     * author: codeMonkey QQ:631872807
     * 点赞
     */
    public function  doMobileZan(){
        global $_W, $_GPC;
        $vid=$_GPC['vid'];
        $uid=$_GPC['uid'];
        $zan=$_GPC['zan'];
        if($zan>5){
            $zan=5;
        }

        $voice = DBUtil::findById(DBUtil::$TABLE_VOICE, $vid);
        $res = array();
        if (empty($voice)) {
            $res['code'] = 501;
            $res['msg'] = "音乐活动删除或不存在";
            echo json_encode($res);
            exit;
        }


        if (empty($_W['fans']['follow'])){
            $res['code'] = 501;
            $res['msg'] = "请关注后再进行点赞哦！";
            echo json_encode($res);
            exit;
        }

         $fopenid = $this->getOpenid();
         $firendInfo= $this->getClientUserInfo($fopenid);
         $firend=DBUtil::findUnique(DBUtil::$TABLE_VOICE_FIREND,array(':uid'=>$uid,":fopenid"=>$fopenid));

        if(!empty($firend)){
            DBUtil::updateById(DBUtil::$TABLE_VOICE_FIREND,array("zan"=>$zan,'createtime'=>TIMESTAMP),$firend['id']);//更新赞数

        }else{
            $firend_data=array(
                'vid'=>$vid,
                'uid'=>$uid,
                'fopenid'=>$fopenid,
                'nickname'=>$firendInfo['nickname'],
                'headimgurl'=>$firendInfo['headimgurl'],
                'createtime'=>TIMESTAMP,
                'zan'=>$zan
            );

            DBUtil::create(DBUtil::$TABLE_VOICE_FIREND,$firend_data);

        }

        $totalZan=$this->updateUserZan($uid);//更新用户总赞数目

        $user=DBUtil::findById(DBUtil::$TABLE_VOICE_USER,$totalZan);
        $res['code']=200;
        $res['zan']=$totalZan;
        $res['userP']=$this->findUserP($user,$vid);
        $res['leastZ']=$this->getLeastZan($user,$vid);


        echo json_encode($res);

    }


    /**
     * author: codeMonkey QQ:631872807
     * 跟用户的指数
     */
    public function  updateUserZan($uid){

        $totalZan= pdo_fetchcolumn('SELECT sum(zan) FROM ' . tablename(DBUtil::$TABLE_VOICE_FIREND) . " WHERE uid =:uid ", array(':uid' =>$uid));

        DBUtil::updateById(DBUtil::$TABLE_VOICE_USER,array('zan'=>$totalZan),$uid);

        return $totalZan;
    }

    /**
     * author: codeMonkey QQ:631872807
     * @param $user
     * @param $vid
     * 计算用户的名次
     */
    public function findUserP($user,$vid){

        $aboveTotal= pdo_fetchcolumn('SELECT count(*) FROM ' . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid and zan>:zan", array(':vid' =>$vid,':zan'=>$user['zan']));
        return $aboveTotal+1;
    }

    public function getLeastZan($user,$vid){
        $minZan=  pdo_fetchcolumn("select min(zan) from ".tablename(DBUtil::$TABLE_VOICE_USER)." where vid=:vid and zan>:zan ",array(':vid' =>$vid,':zan'=>$user['zan']));
        return $minZan-$user['zan'] >0 ?  $minZan-$user['zan'] : 0;
    }

    public function  getShareUrl($kid)
    {
        return MonUtil::str_murl($this->createMobileUrl('Auth', array('kid' => $kid, 'au' => Value::$REDIRECT_KJ), true));

    }


    /**
     * author: codeMonkey QQ:631872807
     * 参与用户
     */
    public function  doWebUser()
    {
        global $_W, $_GPC;

        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        if ($operation == 'display') {

            $vid = $_GPC['vid'];

            $voice = DBUtil::findById(DBUtil::$TABLE_VOICE, $vid);

            if (empty($voice)) {
                message("音乐活动删除或不存在");

            }


            $ord = $_GPC['ord'];
            if ($ord=='') {
                $orderStr = " createtime desc";
            }
            if($ord == 1) {
                $orderStr = " createtime desc";
            }
            if($ord == 2) {
                $orderStr = " createtime asc";
            }

            if($ord == 3) {
                $orderStr = "zan desc";
            }
            if($ord == 4) {
                $orderStr = "zan asc";
            }
            $keyword = $_GPC['keyword'];
            $where = '';
            $params = array(
                ':vid' => $vid
            );

            if (!empty($keyword)) {
                $where .= ' and (nickname like :nickname)';
                $params[':nickname'] = "%$keyword%";

            }

            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid " . $where . "  ORDER BY ".$orderStr." LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid  " . $where, $params);
            $pager = pagination($total, $pindex, $psize);

        } else if ($operation == 'delete') {
            $id = $_GPC['id'];
            pdo_delete(DBUtil::$TABLE_VOICE_FIREND, array("uid" => $id));
            pdo_delete(DBUtil::$TABLE_VOICE_USER, array("id" => $id));


            message('删除成功！', referer(), 'success');
        }


        include $this->template("user_list");


    }


    /**
     * author: codeMonkey QQ:63187280
     * 抓奖品记录
     */
    public function  doWebzanList()
    {
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $vid = $_GPC['vid'];

        $keyword = $_GPC['keywords'];
        $where = '';

        $params=array();

        if($_GPC['uid']!=''){
            $where.=" and uid=:uid";
            $params[':uid']=$_GPC['uid'];
        }

        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $list = pdo_fetchall("select * from  ".tablename(DBUtil::$TABLE_VOICE_FIREND)." where 1=1". $where . " order by zan desc,createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(DBUtil::$TABLE_VOICE_FIREND) ." where 1=1 ". $where, $params);
            $pager = pagination($total, $pindex, $psize);

        }


        include $this->template('zan_firend');

    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $media_id
     * @param $accessToken
     * @return bool
     * 下载到本地
     */
    public function  downloadMedia($media_id,$accessToken)
    {

        $dowload_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=" . $accessToken . "&media_id=" . $media_id;

        $destination_folder = MEDIA_PATH; // 文件下载保存目录，默认为当前文件目录
        $newfname = $destination_folder . $media_id; // 取得文件的名称
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);

        curl_setopt($ch,CURLOPT_URL,$dowload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);

        $downloaded_file = fopen($newfname, 'w');
        fwrite($downloaded_file, $file_content);
        fclose($downloaded_file);

        return true;

    }


    /**
     * author: codeMonkey QQ:2463619823
     * 检查允许录音不
     */
    public function doMobileCheckAllow() {
        global $_GPC, $_W;
        $vid = $_GPC['vid'];
        $openid = $this->getOpenid();

        $user = $this->findJoinUser($vid, $openid);

        if (!empty($user)) {
            die(json_encode(array('code'=>0,'msg'=>"每人只能录制一条哦。")));
        }else {
            die(json_encode(array('code'=>1)));
        }

    }

    public function getOpenid() {
        global  $_W;
        return $_W['fans']['from_user'];
    }
    /**
     * author: codeMonkey QQ:631872807
     * 抓奖品记录导出
     */
    public function  doWebzjdownload()
    {
        require_once 'zjdownload.php';
    }

    /**
     * author: codeMonkey QQ:631872807
     * 用户信息导出
     */
    public function  doWebUDownload()
    {
        require_once 'udownload.php';
    }


    /**
     * author: codeMonkey QQ:2463619823
     * 查找参加用户
     */
    public function  findJoinUser($vid, $openid)
    {

        return DBUtil::findUnique(DBUtil::$TABLE_VOICE_USER, array(':vid' => $vid, ':openid' => $openid));

    }

    /**
     * author: codeMonkey QQ:631872807
     * @param $uid
     * @param $fopenid
     * @return bool
     * 超找帮助用户
     */
    public function  findHelpFirend($uid, $fopenid)
    {
        return DBUtil::findUnique(DBUtil::$TABLE_WKJ_FIREND, array(':uid' => $uid, ':openid' => $fopenid));

    }


    public function  doMobileAuth()
    {
        global $_GPC, $_W;
        $au = $_GPC['au'];
        $kid = $_GPC['kid'];
        $uid = $_GPC['uid'];


        $params = array();
        $params['kid'] = $kid;
        $params['au'] = $au;
        $params['uid'] = $uid;
        $userInfo = MonUtil::getClientCookieUserInfo(Mon_WkjModuleSite::$USER_COOKIE_KEY);
        if (empty($userInfo)) {//授权

            $redirect_uri = MonUtil::str_murl($this->createMobileUrl('Auth2', $params, true));

            $this->oauth->authorization_code($redirect_uri, Oauth2::$SCOPE_USERINFO, 1);//进行授权

        } else {
            $params['openid'] = $userInfo['openid'];
            $redirect_uri = $this->getRedirectUrl($au, $params);
            header("location: $redirect_uri");
        }

    }

    public function  doMobileAuth2()
    {
        global $_GPC;
        $kid = $_GPC['kid'];
        $uid = $_GPC['uid'];
        $code = $_GPC ['code'];
        $au = $_GPC['au'];
        $tokenInfo = $this->oauth->getOauthAccessToken($code);
        $userInfo = $this->oauth->getOauthUserInfo($tokenInfo['openid'], $tokenInfo['access_token']);
        MonUtil::setClientCookieUserInfo($userInfo, $this::$USER_COOKIE_KEY);//保存到cookie
        $params = array();
        $params['kid'] = $kid;
        $params['au'] = $au;
        $params['uid'] = $uid;
        $params['openid'] = $tokenInfo['openid'];
        $redirect_uri = $this->getRedirectUrl($au, $params);
        header("location: $redirect_uri");
    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $type
     * 获取转向URL
     */
    public function  getRedirectUrl($type, $parmas = array())
    {
        switch ($type) {

            case Value::$REDIRECT_USER_INDEX://首页
                $redirectUrl = $this->createMobileUrl('index', $parmas, true);
                break;
            case Value::$REDIRECT_KJ:
                $redirectUrl = $this->createMobileUrl('kj', $parmas, true);
                breka;


        }

        return MonUtil::str_murl($redirectUrl);


    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $status
     * 状态文字
     */
    public function  getStatusText($status)
    {
        switch ($status) {
            case $this::$KJ_STATUS_WKS:
                return "未开始";
                break;
            case $this::$KJ_STATUS_ZC:
                return "正常";
                break;
            case $this::$KJ_STATUS_JS:
                return "已结束";
                break;
            case $this::$KJ_STATUS_XD:
                return "已下单";
                break;
            case $this::$KJ_STATUS_GM:
                return "已购买";
                break;
        }

    }


    /**
     * author: codeMonkey QQ:631872807
     * @param $wkj
     * @param $joinUser
     * @return int
     * 状态获取
     */
    public function  getStatus($wkj, $joinUser)
    {


        if (TIMESTAMP < $wkj['starttime']) {
            return $this::$KJ_STATUS_WKS;
        }
        if (TIMESTAMP > $wkj['endtime']) {
            return $this::$KJ_STATUS_JS;
        }
        if ($joinUser['status'] != '') {
            return $joinUser['status'];
        }
        return $this::$KJ_STATUS_ZC;


    }

    function  encode($value,$dc)
    {

        if ($dc == 1) {
            return $value;
        }

        if($dc == 2) {
            return iconv("utf-8", "gb2312", $value);
        }

    }

}