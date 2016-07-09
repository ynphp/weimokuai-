<?php
/**
 * 通缉单身瞄模块微站定义
 *
 * @author
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Czt_mojingModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		global $_W, $_GPC;
    $answer=array(
      array('韩剧看多了','韩剧里恋上帅气多金又爱你的男主角，现实遇不到完美奥巴，活在幻想中了。'),
      array('因为穷','眼光上去了，实力没跟上，多么痛的领悟！'),
      array('要求高','主动追求的不是月球表面就是屌丝腔，高不成低不就，就此一直单。'),
      array('单身瘾者','单着单着就习惯了，渐渐失去长久爱一个人的能力，不想受伤，也不想伤害。'),
      array('放不下过去','忘不了那个渣渣的前任，又遇不到一个可以卸下防备的人，一个人生活未尝不是一种自由。'),
      array('无私把对象送人','整天帮人出谋划策，解决情感问题，但是自己却没有勇气去追求。'),
      array('圈子小','一直想找对象，只是圈子太小，自己又是个矜持的人。'),
      array('因为等待','我好好过，你慢慢来，如果最后能在一起，那么晚点也没关系。'),
      array('不随便','一直想找对象，只是圈子太小，自己又是个矜持的人。'),
      array('遇不到眼瞎的','还未遇到不在乎外表，喜欢我本人的人。'),
      array('自卑作祟','遇到自己喜欢的不敢说话，担心Ta看不上我！然后，然后。就错过了。'),
      array('朋友圈装b','朋友圈发个西餐美食、出国旅游，别人都以为和我一起会很贵，就不敢告白了。'),
      array('被离婚率吓着','离婚率太高，吓着宝宝了，与其迟早分开，不如不开始！'),
      array('忽视别人的好','总是忽视对你嘘寒问暖，无微不至的人，却苦苦去等待那个让你揪心流泪的人。')
      );

    $answer=$answer[rand(0,count($answer)-1)];

    // $_W['openid']='sasasasdsjkdhasj';

    $sql = 'SELECT * FROM ' . tablename('czt_mojing') . ' WHERE `uniacid` = :uniacid and `openid`=:openid';


    if ($_GPC['openid']) {
      $params = array(':openid' => $_GPC['openid'],':uniacid'=>$_W['uniacid']);
      $result = pdo_fetch($sql, $params);
      $answer=unserialize($result['answer']);
      $comment=unserialize($result['comment']);

      if ($_GPC['openid']==$_W['openid']) {
        include $this->template('index3');
      }else{
        include $this->template('index2');
      }
    }else{
      $params = array(':openid' => $_W['openid'],':uniacid'=>$_W['uniacid']);
      $result = pdo_fetch($sql, $params);
      if ($result) {

        pdo_update('czt_mojing', array('answer' => serialize($answer),'comment'=>''), array('id' => $result['id']));
      }else{
        pdo_insert('czt_mojing', array('uniacid' => $_W['uniacid'],'openid'=>$_W['openid'],'answer'=>serialize($answer)));
      }
      $setting=$this->module['config'];
      include $this->template('index');
    }

	}
  public function doMobileComment() {
    global $_W, $_GPC;
    $sql = 'SELECT * FROM ' . tablename('czt_mojing') . ' WHERE `id` = :id';
    $params = array(':id' => $_GPC['id']);
    $result = pdo_fetch($sql, $params);
    if ($result) {
      $comment=unserialize($result['comment']);
      if ($comment) {
        $comment[]=$_GPC['comment'];
      }else{
        $comment=array($_GPC['comment']);
      }
      $comment=serialize($comment);
      pdo_update('czt_mojing', array('comment' => $comment), array('id' => $_GPC['id']));

    }
  }
}


function dump($vars, $label = '', $return = false) {
  if (ini_get('html_errors')) {
    $content = "<pre>\n";
    if ($label != '') {
        $content .= "<strong>{$label} :</strong>\n";
    }
    $content .= htmlspecialchars(print_r($vars, true));
    $content .= "\n</pre>\n";
  } else {
    $content = $label . " :\n" . print_r($vars, true);
  }
  if ($return) { return $content; }
  echo $content;
  return null;
}
