<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$vid=$_GPC['vid'];
$dc = $_GPC['dc'];
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


$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_VOICE_USER) . " WHERE vid =:vid " . $where . "  ORDER BY ".$orderStr, $params);

$tableheader = array($this->encode("openid",$dc), $this->encode("昵称",$dc),$this->encode("姓名",$dc),$this->encode("手机号",$dc),$this->encode("点赞数",$dc),$this->encode('参与时间',$dc ));
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
    $html .= $value['openid'] . "\t ,";
    $html .= $this->encode( $value['nickname'],$dc )  . "\t ,";
    $html .= $this->encode( $value['uname'],$dc )  . "\t ,";
    $html .= $this->encode( $value['tel'],$dc )  . "\t ,";
    $html .= $this->encode( $value['zan'],$dc )  . "\t ,";
    $html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");
echo $html;
exit();
