<?php
		$nowpage=$_GPC['pages'];
		$pindex = max(2, intval($nowpage));
		$psize = 2;
		$list = pdo_fetchall("SELECT * FROM ".tablename('tg_goods')." WHERE uniacid = '{$weid}' order by id desc LIMIT ".($pindex-1) * $psize.','.$psize); 
		$info=array();
		if (!empty($list)){
			foreach ($list as $item){
				
			$row=array(
					'id'=>$item['id'],
					
					'gname'=>$item['gname'],
					'id'=>$item['id'],
					'fk_typeid'=>$item['fk_typeid'],
					'gsn'=>$item['gsn'],
					'gnum'=>$item['gnum'],
					'groupnum'=>$item['groupnum'],
					'gname'=>$item['gname'],
					'gprice'=>$item['gprice'],
					'oprice'=>$item['oprice'],			
					'mprice'=>$item['mprice'],
					'gdesc'=>$item['gdesc'],
					'gimg'=>tomedia($item['gimg']),
					'gubtime'=>$item['gubtime'],
					'salenum'=>$item['salenum'],
					'ishot'=>$item['ishot'],
					'uniacid'=>$item['uniacid'],
			);
			$info[]=$row;			
			}
			$sta =1;
		}else{
			$sta =0;
		}
		
		$result=array(
			'success'=>$sta,
			'list'=>$info,	
		);
		
		sleep(2);	//用于延时,以看到正在载入的图标.延时2秒
		 
		echo json_encode($result);
?>