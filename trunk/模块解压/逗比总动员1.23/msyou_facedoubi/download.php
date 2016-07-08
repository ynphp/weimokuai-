<?php
	
/**
 * 合体红包
 *
 * @author ewei qq:22185157
 * @url 
 */
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$rid= intval($_GPC['rid']);
if(empty($rid)){
    message('抱歉，传递的参数错误！','', 'error');              
}

  $params = array(':rid' => $rid);
  $list = pdo_fetchall("SELECT * FROM " . tablename('msyou_facedoubi_lists') . " WHERE rid = :rid " . $where . " ORDER BY createtime desc ", $params);
  foreach ($list as &$row) {
	if($row['issubmit'] == 0){
		$row['issubmit']='未提交';
	}elseif($row['issubmit'] == 1){
		$row['issubmit']='已提交';
	}
}
$tableheader = array('RID','uniacid','ID', 'fanid','手机号', '逗比图','逗比宣言', '评分', '点赞', '分享数', '浏览数', '参与时间', '状态');
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['RID'] . "\t ,";
	$html .= $value['uniacid'] . "\t ,";
	$html .= $value['id'] . "\t ,";
	 $html .= $value['fanid'] . "\t ,";	
	$html .= $value['phonenum'] . "\t ,";	
        $html .= $value['imgurl'] . ".jpg\t ,";	
        $html .= $value['contact'] . "\t ,";	
        $html .= $value['doubival'] . "\t ,";	
        $html .= $value['zancount'] . "\t ,";	
        $html .= $value['sharecount'] . "\t ,";	
        $html .= $value['viewcount'] . "\t ,";	
        $html .= date('Y-m-d H:i:s', $value['createtime']) . "\t ,";	
        $html .= $value['issubmit'] . "\n";	
}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部用户数据.csv");

echo $html;
exit();
