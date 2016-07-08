<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$zid= intval($_GPC['zid']);
if(empty($zid)){
    message('抱歉，传递的参数错误！','', 'error');              
}



$list= pdo_fetchall("select r.*, u.nickname as nickname,u.headimgurl as headimgurl,u.tel as tel,(select pname from ".tablename(CRUD::$table_zjp_prize)." c where c.id=r.pid ) as pname from ".tablename(CRUD::$table_zjp_record)." r left join   ".tablename(CRUD::$table_zjp_user)." u on r.uid=u.id where r.zid=:zid  order by r.createtime desc ",array(':zid'=>$zid));



foreach ($list as &$row) {

	if($row['award_status'] == 0){
		$row['award_status']=iconv("UTF-8", "GB2312", '未中奖' );
	}elseif($row['award_status'] == 1){
		$row['award_status']=iconv("UTF-8", "GB2312", '已中奖' );
	}else{
		$row['award_status']=iconv("UTF-8", "GB2312", '已领奖' );
	}
}
$tableheader = array('openID',  iconv("UTF-8", "GB2312", '昵称' ), iconv("UTF-8", "GB2312", '手机号' ),iconv("UTF-8", "GB2312", '奖品' ) ,iconv("UTF-8", "GB2312", '状态' ),iconv("UTF-8", "GB2312", '抽奖时间' ));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .=iconv("UTF-8", "GB2312", $value['nickname'] )  . "\t ,";

    if(!empty($value['tel'])){
        $html .= $value['tel'] . "\t ,";
    }else{
        $html .=  iconv("UTF-8", "GB2312", '未绑定' ) . "\t ,";
    }

	$html .= iconv("UTF-8", "GB2312", $value['pname'] ) . "\t ,";


    $html .= $value['award_status'] . "\t ,";

	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=全部数据.csv");

echo $html;
exit();
