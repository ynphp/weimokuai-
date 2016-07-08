<?php
global $_W, $_GPC;
		$weid=$_W['weid'];
		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'dianyuan';
		if($op=='dianyuan')
		{
			$condition = '';

			if (!empty($_GPC['keyword'])) {
					$condition .= " AND a.username LIKE '%{$_GPC['keyword']}%'";
				}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;

			$sql = "SELECT a.*,c.nickname,c.avatar FROM " . tablename('jy_ppp_dianyuan') . " AS a
					LEFT JOIN ".tablename('mc_members')." as c on a.uid=c.uid WHERE a.weid = ".$weid." AND a.status!=0 $condition LIMIT " . ($pindex - 1) * $psize . ",{$psize}";
			$list = pdo_fetchall($sql);
			$total = count(pdo_fetchall("SELECT a.* FROM " . tablename('jy_ppp_dianyuan') . " as a WHERE a.weid = ".$weid." AND a.status!=0 $condition"));
			if(!empty($list))
			{
				foreach ($list as $key => $value) {
					$list[$key]['num']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xuni_member')." WHERE weid=".$weid." AND dyid=".$value['id']);
				}
			}

			$pager = pagination($total, $pindex, $psize);
		}
		elseif ($op=='edit')
		{
			$id = intval($_GPC['id']);
			load()->func('tpl');
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE id = :id", array(':id' => $id));
				if (empty($item)) {
					message('抱歉，该工作人员不存在或是已经删除！', '', 'error');
				}

			}

			if (checksubmit('submit')) {
				if (empty($_GPC['username'])) {
					message('请输入用户名！');
				}
				if(!empty($_GPC['mobile']))
				{
					if(preg_match('/1[345789]{1}\d{9}$/', $_GPC['mobile']))
					{
						if(!empty($id))
						{
							$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND mobile='".$_GPC['mobile']."' AND id!=".$id);
						}
						else
						{
							$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND mobile='".$_GPC['mobile']."'");
						}
						
						if(!empty($temp))
						{
							message("该手机号已经被其他工作人员所注册，请更改手机号！");
						}
					}
					else{
						message('店员手机格式错误!');
					}
				}
				else
				{
					message('请输入店员手机！');
				}
				if (empty($_GPC['password'])) {
					message('请输入店员密码！');
				}
				if (!empty($_GPC['mail'])) {
					if(preg_match('([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$', $_GPC['mail']))
					{

					}
					else{
						message('邮箱地址错误!');
					}
				}
				$data = array(
					'weid' => $_W['weid'],
					'username' =>$_GPC['username'],
					'mobile' => $_GPC['mobile'],
					'mail' => $_GPC['mail'],
					'description' => $_GPC['description'],
					'QQ' => $_GPC['QQ'],
					'wechat' => $_GPC['wechat'],
					'password' =>$_GPC['password'],
					'createtime' =>TIMESTAMP,
				);
				if(!empty($id))
				{
					pdo_update('jy_ppp_dianyuan', $data,array('id'=>$id));
				}
				else
				{
					pdo_insert('jy_ppp_dianyuan', $data);
				}
				message('信息更新成功！', $this->createWebUrl('dianyuan', array('op' => 'dianyuan')), 'success');
			}
		}
		elseif ($op=='del') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE id = ".$id);
			if (empty($item)) {
				message('抱歉，该用户不存在或是已经删除！', '', 'error');
			}
			pdo_update('jy_ppp_dianyuan',array('status'=>0,'from_user'=>'','uid'=>0),array('id'=>$id));
			message('删除成功！', referer(), 'success');
		}
		include $this->template('web/dianyuan');