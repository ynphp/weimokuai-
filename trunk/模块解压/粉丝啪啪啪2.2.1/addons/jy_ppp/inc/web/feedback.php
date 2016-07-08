<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();	

		$list=pdo_fetchall("SELECT a.*,b.mobile as mobile2,b.nicheng,b.mobile_auth,c.nickname,c.avatar FROM ".tablename('jy_ppp_feedback')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid=".$weid);

		$op=$_GPC['op'];
		if($op=='del')
		{
			$id=$_GPC['id'];
			pdo_delete("jy_ppp_feedback",array('id'=>$id));
			message("删除成功！",$this->createWebUrl('feedback'),'success');
		}

		include $this->template('web/feedback');