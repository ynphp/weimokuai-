<?php
         global $_GPC, $_W;
         if($_W['isajax']){
			$id = intval($_GPC['rid']);
			$pindex = max(1, intval($_GPC['page']));
            $psize = 5;
	        $list = pdo_fetchall("SELECT avatar,nickname,score,createtime,luck_name FROM ".tablename('meepo_qixi_user')." WHERE weid=:weid AND rid=:rid ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize,array(':weid'=>$_W['uniacid'],':rid'=>$id));
			$html = '';
			if(!empty($list) && is_array($list)){
				foreach($list as $row){
				    $html .= '<li><img class="receiver_avatar" width="36" height="36" src="'.$row['avatar'].'" />
                    <div class="receiver_info">
                        <div style="margin-bottom: 2px;">
                            <span class="nickname">'.$row['nickname'].'</span>
                            <span class="time">'.date('y/m/d H:i',$row['createtime']).'</span>
                        </div>
                        <div class="desc">摁死了<font color=red>'.$row['score'].'</font>对</div>
                    </div>
                    <div class="receiver_flow">
                     <div class="red" style="font-size:6px;">';
					if(empty($row['luck_name'])){
					  $html .= '未获奖';
					}else{
						$html .= $row['luck_name'];
					}
					$html .= '</div></div></li> ';
				}
			}else{
			  $html = 'no';
			}
			die(json_encode($html));

		 }