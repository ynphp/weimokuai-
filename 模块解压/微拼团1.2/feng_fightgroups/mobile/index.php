<?php



	$pindex = max(1, intval($_GPC['page'])); //当前页码

		$psize = 2;	//设置分页大小                                                               

		$condition = ''; 

		$params = array(

			':uniacid'=>$_W['uniacid']

		);

		$goodses = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$weid}' $condition ORDER BY id desc LIMIT ".(1-1)* $psize.','.$psize);

		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('tg_goods') . "WHERE uniacid = '{$weid}'"); //记录总数

		$pager = pagination($total, $pindex, $psize);



		

	include $this->template('index');

?>