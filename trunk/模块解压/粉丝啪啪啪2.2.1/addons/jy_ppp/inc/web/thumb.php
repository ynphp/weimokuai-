<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();	

		
		$op=$_GPC['op'];
		if($op=='del')
		{
			$id=$_GPC['id'];
			$thumb=pdo_fetch("SELECT a.thumb,b.avatar,a.mid FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
			if($thumb['avatar']==$thumb['thumb'])
			{
				pdo_update("jy_ppp_member",array('avatar'=>''),array('id'=>$thumb['mid']));
			}
			pdo_update("jy_ppp_thumb",array('type'=>4),array('id'=>$id));
			message("删除成功！",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='pass') {
			$id=$_GPC['id'];
			$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
			pdo_update("jy_ppp_thumb",array('type'=>1),array('id'=>$id));
			if(empty($temp_thumb['avatar']))
			{
				pdo_update("jy_ppp_member",array('avatar'=>$temp_thumb['thumb']),array('id'=>$temp_thumb['id']));
			}
			if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
			{
				$text2="你提交的相片已被管理员审核成功！";
				$text=urlencode($text2);
				$pic2=$_W['attachurl'].$temp_thumb['thumb'];
				$pic=urlencode($pic2);
				$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
				$url=urlencode($url2);
				$access_token = WeAccount::token();
				$data = array(
				  "touser"=>$temp_thumb['from_user'],
				  "msgtype"=>"news",
				  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
				);
				$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
				load()->func('communication');
				$response = ihttp_request($url, urldecode(json_encode($data)));
				$errcode=json_decode($response['content'],true);
				$data3=array(
						'weid'=>$weid,
						'mid'=>$temp_thumb['id'],
						'type'=>'news',
						'content'=>$text2,
						'url'=>$url2,
						'picurl'=>$pic2,
						'status'=>$errcode['errcode'],
						'createtime'=>TIMESTAMP,
					);
				pdo_insert("jy_ppp_kefu",$data3);
			}
			message("审核为头像成功",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='pass2') {
			$id=$_GPC['id'];
			$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
			pdo_update("jy_ppp_thumb",array('type'=>2),array('id'=>$id));
			if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
			{
				$text2="你提交的相片已被管理员审核成功！";
				$text=urlencode($text2);
				$pic2=$_W['attachurl'].$temp_thumb['thumb'];
				$pic=urlencode($pic2);
				$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
				$url=urlencode($url2);
				$access_token = WeAccount::token();
				$data = array(
				  "touser"=>$temp_thumb['from_user'],
				  "msgtype"=>"news",
				  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
				);
				$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
				load()->func('communication');
				$response = ihttp_request($url, urldecode(json_encode($data)));
				$errcode=json_decode($response['content'],true);
				$data3=array(
						'weid'=>$weid,
						'mid'=>$temp_thumb['id'],
						'type'=>'news',
						'content'=>$text2,
						'url'=>$url2,
						'picurl'=>$pic2,
						'status'=>$errcode['errcode'],
						'createtime'=>TIMESTAMP,
					);
				pdo_insert("jy_ppp_kefu",$data3);
			}
			message("审核为非头像成功",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='nopass') {
			$id=$_GPC['id'];
			$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
			pdo_update("jy_ppp_thumb",array('type'=>3),array('id'=>$id));
			if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
			{
				$text2="你提交的相片审核不通过！";
				$text=urlencode($text2);
				$pic2=$_W['attachurl'].$temp_thumb['thumb'];
				$pic=urlencode($pic2);
				$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
				$url=urlencode($url2);
				$access_token = WeAccount::token();
				$data = array(
				  "touser"=>$temp_thumb['from_user'],
				  "msgtype"=>"news",
				  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
				);
				$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
				load()->func('communication');
				$response = ihttp_request($url, urldecode(json_encode($data)));
				$errcode=json_decode($response['content'],true);
				$data3=array(
						'weid'=>$weid,
						'mid'=>$temp_thumb['id'],
						'type'=>'news',
						'content'=>$text2,
						'url'=>$url2,
						'picurl'=>$pic2,
						'status'=>$errcode['errcode'],
						'createtime'=>TIMESTAMP,
					);
				pdo_insert("jy_ppp_kefu",$data3);
			}
			message("设置为审核不通过！",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='return') {
			$id=$_GPC['id'];
			$thumb=pdo_fetch("SELECT a.thumb,b.avatar,a.mid FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
			if($thumb['avatar']==$thumb['thumb'])
			{
				pdo_update("jy_ppp_member",array('avatar'=>''),array('id'=>$thumb['mid']));
			}
			pdo_update("jy_ppp_thumb",array('type'=>0),array('id'=>$id));
			message("设置为未审核成功！",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='allc') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				$id=$value;
				$thumb=pdo_fetch("SELECT a.thumb,b.avatar,a.mid FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
				if($thumb['avatar']==$thumb['thumb'])
				{
					pdo_update("jy_ppp_member",array('avatar'=>''),array('id'=>$thumb['mid']));
				}
				pdo_update("jy_ppp_thumb",array('type'=>0),array('id'=>$id));
			}
			message("设置为重新审核！",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='allb') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				$id=$value;
				$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
				pdo_update("jy_ppp_thumb",array('type'=>3),array('id'=>$id));
				if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
				{
					$text2="你提交的相片审核不通过！";
					$text=urlencode($text2);
					$pic2=$_W['attachurl'].$temp_thumb['thumb'];
					$pic=urlencode($pic2);
					$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
					$url=urlencode($url2);
					$access_token = WeAccount::token();
					$data = array(
					  "touser"=>$temp_thumb['from_user'],
					  "msgtype"=>"news",
					  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
					);
					$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
					load()->func('communication');
					$response = ihttp_request($url, urldecode(json_encode($data)));
					$errcode=json_decode($response['content'],true);
					$data3=array(
							'weid'=>$weid,
							'mid'=>$temp_thumb['id'],
							'type'=>'news',
							'content'=>$text2,
							'url'=>$url2,
							'picurl'=>$pic2,
							'status'=>$errcode['errcode'],
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_kefu",$data3);
				}
			}
			message("设置为审核不通过！",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='allf') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				$id=$value;
				$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
				pdo_update("jy_ppp_thumb",array('type'=>2),array('id'=>$id));
				if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
				{
					$text2="你提交的相片已被管理员审核成功！";
					$text=urlencode($text2);
					$pic2=$_W['attachurl'].$temp_thumb['thumb'];
					$pic=urlencode($pic2);
					$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
					$url=urlencode($url2);
					$access_token = WeAccount::token();
					$data = array(
					  "touser"=>$temp_thumb['from_user'],
					  "msgtype"=>"news",
					  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
					);
					$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
					load()->func('communication');
					$response = ihttp_request($url, urldecode(json_encode($data)));
					$errcode=json_decode($response['content'],true);
					$data3=array(
							'weid'=>$weid,
							'mid'=>$temp_thumb['id'],
							'type'=>'news',
							'content'=>$text2,
							'url'=>$url2,
							'picurl'=>$pic2,
							'status'=>$errcode['errcode'],
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_kefu",$data3);
				}
			}
			message("审核为非头像成功",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='allt') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				$id=$value;
				$temp_thumb=pdo_fetch("SELECT a.thumb,b.id,b.from_user,b.type,b.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
				pdo_update("jy_ppp_thumb",array('type'=>1),array('id'=>$id));
				if(empty($temp_thumb['avatar']))
				{
					pdo_update("jy_ppp_member",array('avatar'=>$temp_thumb['thumb']),array('id'=>$temp_thumb['id']));
				}
				if($temp_thumb['type']!=3 AND !empty($temp_thumb['from_user']))
				{
					$text2="你提交的相片已被管理员审核成功！";
					$text=urlencode($text2);
					$pic2=$_W['attachurl'].$temp_thumb['thumb'];
					$pic=urlencode($pic2);
					$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('upload'), 2);
					$url=urlencode($url2);
					$access_token = WeAccount::token();
					$data = array(
					  "touser"=>$temp_thumb['from_user'],
					  "msgtype"=>"news",
					  "news"=>array("articles"=>array(0=>(array("title"=>$text,"url"=>$url,'picurl'=>$pic))))
					);
					$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
					load()->func('communication');
					$response = ihttp_request($url, urldecode(json_encode($data)));
					$errcode=json_decode($response['content'],true);
					$data3=array(
							'weid'=>$weid,
							'mid'=>$temp_thumb['id'],
							'type'=>'news',
							'content'=>$text2,
							'url'=>$url2,
							'picurl'=>$pic2,
							'status'=>$errcode['errcode'],
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_kefu",$data3);
				}
			}
			message("审核为头像成功!",$this->createWebUrl('thumb'),'success');
		}
		elseif ($op=='delall') {
			$str=$_GPC['str'];
			if(!empty($str))
			{
				$str=substr($str, 0 , -1);
			}
			$str_arr=explode(',', $str);
			foreach ($str_arr as $key => $value) {
				$id=$value;
				$thumb=pdo_fetch("SELECT a.thumb,b.avatar,a.mid FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.id=".$id);
				if($thumb['avatar']==$thumb['thumb'])
				{
					pdo_update("jy_ppp_member",array('avatar'=>''),array('id'=>$thumb['mid']));
				}
				pdo_update("jy_ppp_thumb",array('type'=>4),array('id'=>$id));
			}
			message("删除成功!",$this->createWebUrl('thumb'),'success');
		}
		else
		{
			$condition = '';

			if (!empty($_GPC['keyword'])) {
				$condition .= " AND ( b.mobile LIKE '%{$_GPC['keyword']}%' OR b.nicheng LIKE '%{$_GPC['keyword']}%' )";
			}
			if (!empty($_GPC['type'])) {
				if($_GPC['type']!=4)
				{
					$condition .= " AND a.type=".$_GPC['type'];
				}
				else
				{
					$condition .= " AND a.type!=4";
				}
			}
			else
			{
				$condition .= " AND a.type=0";
			}

			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list=pdo_fetchall("SELECT a.*,b.mobile as mobile2,b.nicheng,b.mobile_auth,b.sex,c.nickname,c.avatar FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id left join ".tablename('mc_members')." as c on b.uid=c.uid WHERE a.weid=".$weid." AND a.type!=4 $condition LIMIT " . ($pindex - 1) * $psize . ",{$psize}");
			$total=pdo_fetchcolumn("SELECT count(a.id) FROM ".tablename('jy_ppp_thumb')." as a left join ".tablename('jy_ppp_member')." as b on a.mid=b.id WHERE a.weid=".$weid." AND a.type!=4 ".$condition);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('web/thumb');
		}