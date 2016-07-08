<?php
/**
 * 粉丝啪啪啪模块微站定义
 *
 * @author Michael Hu
 * @url http://bbs.9ye.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Jy_pppModuleSite extends WeModuleSite {

	public function __construct(){
		load()->classs('wesession');
		if($_SERVER['PHP_SELF'] != '/web/index.php'){
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
				WeSession::$expire = 10800;
			}
			else
			{
				WeSession::$expire = 360000;
			}
		}
	}
	public function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	}
	public function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = $this->xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return $arr;
	}

	public function isMobile()
	{
	    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    {
	        return true;
	    }
	    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	    if (isset ($_SERVER['HTTP_VIA']))
	    {
	        // 找不到为flase,否则为true
	        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	    }
	    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT']))
	    {
	        $clientkeywords = array ('nokia',
	            'sony',
	            'ericsson',
	            'mot',
	            'samsung',
	            'htc',
	            'sgh',
	            'lg',
	            'sharp',
	            'sie-',
	            'philips',
	            'panasonic',
	            'alcatel',
	            'lenovo',
	            'iphone',
	            'ipod',
	            'blackberry',
	            'meizu',
	            'android',
	            'netfront',
	            'symbian',
	            'ucweb',
	            'windowsce',
	            'palm',
	            'operamini',
	            'operamobi',
	            'openwave',
	            'nexusone',
	            'cldc',
	            'midp',
	            'wap',
	            'mobile'
	            );
	        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
	        {
	            return true;
	        }
	    }
	    // 协议法，因为有可能不准确，放到最后判断
	    if (isset ($_SERVER['HTTP_ACCEPT']))
	    {
	        // 如果只支持wml并且不支持html那一定是移动设备
	        // 如果支持wml和html但是wml在html之前则是移动设备
	        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
	        {
	            return true;
	        }
	    }
	    return false;
	}

	public function doMobileZhaohu() {
		global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'zhaohu','foo'=>$_GPC['foo']))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'zhaohu','foo'=>$_GPC['foo']))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$op=$_GPC['op'];
			$foo=$_GPC['foo'];
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
			$match_age=array('0'=>'不限','1'=>'18-25岁','2'=>'26-35岁','3'=>'36-45岁','4'=>'46-55岁','5'=>'55岁以上');
			$match_height=array('0'=>'不限','1'=>'160cm以下','2'=>'161-165cm','3'=>'166-170cm','4'=>'170以上');
			if ($op=='zhaohu') {
				$str=$_GPC['str'];
				if(!empty($str))
				{
					$str=substr($str, 0 ,-1);
				}
				$zh=explode(",", $str);
				if($member['sex']==1)
				{
					$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
					$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
					$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
					$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
					$temp_wt=array();
					if(!empty($member['brith']))
					{
						array_push($temp_wt, '1','2','3');
						if(!empty($basic['height']))
						{
							array_push($temp_wt, '4');
						}
					}
					if(!empty($member['province']))
					{
						array_push($temp_wt, '7');
						if(!empty($aihao))
						{
							array_push($temp_wt, '6');
						}
						if(!empty($tezheng))
						{
							array_push($temp_wt, '5');
						}
					}
					if(!empty($basic['blood']))
					{
						array_push($temp_wt, '8');
					}
					if(!empty($desc['leixin']))
					{
						array_push($temp_wt, '9');
					}
					if(!empty($aihao))
					{
						array_push($temp_wt, '10');
					}
					if(!empty($tezheng))
					{
						array_push($temp_wt, '11');
					}
					array_push($temp_wt, '12','13','14','15','16','17','18');
					$temp_num=count($temp_wt)-1;
				}

				foreach ($zh as $key => $value) {
					$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$value." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
					if(!empty($temp) && $temp['createtime']>$today)
					{

					}
					else
					{
						$data=array(
							'weid'=>$weid,
							'mid'=>$value,
							'sendid'=>$mid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
						if($member['sex']==2)
						{
							$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
							$data['content']=$wenti['name'];
							$data['zhaohuid']=$wenti['id'];
							if(empty($data['content']))
							{
								$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
							}
						}
						else
						{
							$xiabiao=mt_rand(0,$temp_num);
							$rand_wt=$temp_wt[$xiabiao];
							switch ($rand_wt) {
								case 1:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
									break;
								case 2:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
									break;
								case 3:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
									break;
								case 4:
									$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
									break;
								case 5:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
									break;
								case 6:
									$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
									break;
								case 7:
									$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
									break;
								case 8:
									$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
									break;
								case 9:
									$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
									break;
								case 10:
									$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
									break;
								case 11:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
									break;
								case 12:
									$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
									break;
								case 13:
									$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
									break;
								case 14:
									$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
									break;
								case 15:
									$data['content']="hey，我觉得你很有气质，可以认识你吗？";
									break;
								case 16:
									$data['content']="我们好像在那里见过，可以认识你吗？";
									break;
								case 17:
									$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
									break;
								default:
									$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
									break;
							}
						}
						pdo_insert("jy_ppp_xinxi",$data);
					}
				}

				echo 1;
				exit;
			}
			else
			{

				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);

				if($member['sex']==1)
				{
					$condition.=" AND sex=2 ";
				}
				else
				{
					$condition.=" AND sex=1 ";
				}
				$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
				if(!empty($match))
				{
					if($match['province']>0)
					{
						$temp_province=$match['province'];
					}
					else
					{
						if(!empty($member['province']))
						{
							$temp_province=$member['province'];
						}
						else
						{
							$temp_province=11;
						}
					}
					$condition.=" AND a.province= ".$temp_province;
				}
				$temp_tjid=pdo_fetch("SELECT id FROM ".tablename("jy_ppp_member")." WHERE weid=".$weid." ORDER BY id DESC LIMIT 1 ");
				$temp_tjid=$temp_tjid['id'];

				$temp_tjid_rand=mt_rand(0,9800);
				$temp_tjid_rand=$temp_tjid_rand/10000.0;
				$temp_tjid=floor($temp_tjid*$temp_tjid_rand);
				$tuijian=pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid  WHERE a.weid=".$weid.$condition." AND a.id >= ".$temp_tjid."  LIMIT 3");
				if(!empty($tuijian))
				{
					include $this->template('zhaohu');
				}
				else
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl($foo)."';
					</script>";
				}
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}

	public function doMobileIndex() {

		global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'index'))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'index'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}
		if(!empty($member))
		{
			$op=$_GPC['op'];
			$temp_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC LIMIT 1 ");
			$today=strtotime("today");

			if($temp_log['createtime']<$today)
			{
				$data=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'createtime'=>TIMESTAMP,
					);
				pdo_insert('jy_ppp_login_log',$data);

				echo "<script>
					window.location.href = '".$this->createMobileUrl('zhaohu',array('foo'=>'index'))."';
				</script>";
				message($today);
				exit;
			}
			else
			{
				$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
				$match_age=array('0'=>'不限','1'=>'18-25岁','2'=>'26-35岁','3'=>'36-45岁','4'=>'46-55岁','5'=>'55岁以上');
				$match_height=array('0'=>'不限','1'=>'160cm以下','2'=>'161-165cm','3'=>'166-170cm','4'=>'170以上');
				if($op=='zhaohu2')
				{
					$temp_thumb=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$mid." AND type!=4");
					if(empty($temp_thumb))
					{
						echo 3;
						exit;
					}
					$id=$_GPC['id'];
					$today=strtotime("today");
					$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$id." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
					if(!empty($temp) && $temp['createtime']>$today)
					{
						echo 2;
					}
					else
					{
						$data=array(
								'weid'=>$weid,
								'mid'=>$id,
								'sendid'=>$mid,
								'type'=>0,
								'yuedu'=>0,
								'createtime'=>TIMESTAMP,
							);
						if($member['sex']==2)
						{
							$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
							$data['content']=$wenti['name'];
							$data['zhaohuid']=$wenti['id'];
							if(empty($data['content']))
							{
								$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
							}
						}
						else
						{
							$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
							$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
							$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
							$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");

							$temp_wt=array();
							if(!empty($member['brith']))
							{
								array_push($temp_wt, '1','2','3');
								if(!empty($basic['height']))
								{
									array_push($temp_wt, '4');
								}
							}
							if(!empty($member['province']))
							{
								array_push($temp_wt, '7');
								if(!empty($aihao))
								{
									array_push($temp_wt, '6');
								}
								if(!empty($tezheng))
								{
									array_push($temp_wt, '5');
								}
							}
							if(!empty($basic['blood']))
							{
								array_push($temp_wt, '8');
							}
							if(!empty($desc['leixin']))
							{
								array_push($temp_wt, '9');
							}
							if(!empty($aihao))
							{
								array_push($temp_wt, '10');
							}
							if(!empty($tezheng))
							{
								array_push($temp_wt, '11');
							}
							array_push($temp_wt, '12','13','14','15','16','17','18');
							$temp_num=count($temp_wt)-1;
							$xiabiao=mt_rand(0,$temp_num);
							$rand_wt=$temp_wt[$xiabiao];
							switch ($rand_wt) {
								case 1:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
									break;
								case 2:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
									break;
								case 3:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
									break;
								case 4:
									$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
									break;
								case 5:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
									break;
								case 6:
									$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
									break;
								case 7:
									$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
									break;
								case 8:
									$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
									break;
								case 9:
									$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
									break;
								case 10:
									$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
									break;
								case 11:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
									break;
								case 12:
									$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
									break;
								case 13:
									$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
									break;
								case 14:
									$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
									break;
								case 15:
									$data['content']="hey，我觉得你很有气质，可以认识你吗？";
									break;
								case 16:
									$data['content']="我们好像在那里见过，可以认识你吗？";
									break;
								case 17:
									$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
									break;
								default:
									$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
									break;
							}
						}
						pdo_insert("jy_ppp_xinxi",$data);
						echo 1;
						$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
						$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
						if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
						{
							$send_kefu_tf=false;
							$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
							if($sitem['jiange']>0)
							{
								$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
								$temp_jg_time=time()-$temp_kefu['createtime'];
								if($temp_jg_time>$sitem['jiange']*60)
								{
									$today=strtotime('today');
									$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
									if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
									{
										$send_kefu_tf=true;
									}
								}
							}
							else
							{
								$today=strtotime('today');
								$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
								if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
								{
									$send_kefu_tf=true;
								}
							}

							if($send_kefu_tf==true)
							{
								$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
								$text=urlencode($text2);
								$desc2="点击下方【信箱】按钮查看所有来信";
								$desc=urlencode($desc2);
								$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
								$pic=urlencode($pic2);
								$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
								$url=urlencode($url2);
								$access_token = WeAccount::token();
								$data = array(
								  "touser"=>$kefu_member['from_user'],
								  "msgtype"=>"news",
								  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								load()->func('communication');
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'mid'=>$id,
										'type'=>'news',
										'content'=>$text2,
										'desc'=>$desc2,
										'url'=>$url2,
										'picurl'=>$pic2,
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
									);
								pdo_insert("jy_ppp_kefu",$data3);
							}
						}
					}
					exit;
				}elseif($op=='more')
				{
					$str=$_GPC['str'];
					if(empty($str))
					{
						echo json_encode(array('status'=>3));
						exit;
					}
					else
					{
						$str=substr($str, 0,-1);
					}
					$condition=$condition.=" AND a.id NOT IN ( ".$str." ) ";
					if($member['sex']==1)
					{
						$condition.=" AND sex=2 ";
					}
					else
					{
						$condition.=" AND sex=1 ";
					}
					$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
					if(!empty($match))
					{
						if($match['province']>0)
						{
							$temp_province=$match['province'];
						}
						else
						{
							if(!empty($member['province']))
							{
								$temp_province=$member['province'];
							}
							else
							{
								$temp_province=11;
							}
						}
						$condition.=" AND a.province= ".$temp_province;
						if(!empty($match['height']))
						{
							if($match['height']==1)
							{
								$condition.=" AND b.height<= 160 ";
							}
							elseif ($match['height']==2) {
								$condition.=" AND b.height> 160 AND b.height<=165 ";
							}
							elseif ($match['height']==3) {
								$condition.=" AND b.height> 165 AND b.height<=170 ";
							}
							else
							{
								$condition.=" AND b.height> 170 ";
							}
						}
						if(!empty($match['income']))
						{
							if($match['income']==1)
							{
								$condition.=" AND ( b.income= '2000-5000' OR b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}
							elseif ($match['income']==2) {
								$condition.=" AND ( b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}
							elseif ($match['income']==3) {
								$condition.=" AND ( b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}

						}
						if(!empty($match['education']))
						{
							if($match['education']==1)
							{
								$condition.=" AND ( b.education= '高中及中专' OR b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
							}
							elseif ($match['education']==2) {
								$condition.=" AND ( b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
							}
							elseif ($match['income']==3) {
								$condition.=" AND ( b.education= '本科' OR b.education= '硕士及以上' ) ";
							}

						}
						if(!empty($match['age']))
						{
							$now=time();
							$y=date('Y',$now);
							$m=date('m',$now);
							$d=date('d',$now);

							if($match['age']==1)
							{
								$temp_y=$y-18;
								$temp_y2=$y-25;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==2) {
								$temp_y=$y-25;
								$temp_y2=$y-35;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==3) {
								$temp_y=$y-35;
								$temp_y2=$y-45;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==4) {
								$temp_y=$y-45;
								$temp_y2=$y-55;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							else {
								$temp_y=$y-55;
								$temp_y=''.$temp_y.$m.$d;
								$temp_brith=strtotime($temp_y);
								$condition.=" AND ( a.brith< ".$temp_brith." ) ";
							}

						}
					}
					if($member['sex']==2)
					{
						$xz_sex="女生";
					}
					else
					{
						$xz_sex="男生";
					}
					$today=strtotime("today");
					$temp_tjid=pdo_fetch("SELECT id FROM ".tablename("jy_ppp_member")." WHERE weid=".$weid." ORDER BY id DESC LIMIT 1 ");
					$temp_tjid=$temp_tjid['id'];

					$temp_tjid_rand=mt_rand(0,9800);
					$temp_tjid_rand=$temp_tjid_rand/10000.0;
					$temp_tjid=floor($temp_tjid*$temp_tjid_rand);
					
					$tuijian=pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_match')." as c on a.id=c.mid WHERE a.weid=".$weid.$condition." AND a.id >= ".$temp_tjid." LIMIT 10");

					$html='';
					if(!empty($tuijian))
					{
						foreach ($tuijian as $key => $l) {
							if(!empty($l['avatar']))
							{
								$avatar=$_W['attachurl'].$l['avatar'];
							}
							else
							{
								if($l['sex']==1)
								{
									$avatar="../addons/jy_ppp/images/boy.png";
								}
								else
								{
									$avatar="../addons/jy_ppp/images/girl.png";
								}
							}
							$ziliao='';

							if(!empty($l['brith']))
							{
								$birthday=$l['brith'];
								$now=time();
							    $month=0;
							    if(date('m', $now)>date('m', $birthday))
							    $month=1;
							    if(date('m', $now)==date('m', $birthday))
							    if(date('d', $now)>=date('d', $birthday))
							    $month=1;
							    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
								$ziliao.=$nianlin."岁";
							}
							if(!empty($l['province']))
							{
								$ziliao.=" | ".$province[$l['province']];
							}

							$thumb=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$l['id']." AND ( type=1 OR type=2 ) ");
							$ziliao.=" | ".$thumb."张照片";
							$temp_aihao=pdo_fetch("SELECT aihao FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$l['id']." LIMIT 1 ");
							$aihao=$temp_aihao['aihao'];
							$temp_tezheng=pdo_fetch("SELECT tezheng FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$l['id']." LIMIT 1 ");
							$tezheng=$temp_tezheng['tezheng'];

							if(!empty($l['beizhu']))
							{
								$match=$l['beizhu'];
							}
							else
							{
								if (!empty($l['province2']))
								{
									$match="寻找".$province[$l['province2']];
									if(!empty($l['age']))
									{
										$match.=" ".$match_age[$l['age']];
									}
									if(!empty($l['height2']))
									{
										$match.=" ".$match_height[$l['height2']]."cm";
									}
									$match.="的".$xz_sex;
								}
								else
								{
									$match="寻找";
									if (!empty($l['province']))
									{
										$match.=$province[$l['province']];
									}
									else
									{
										$match.=$province[11];
									}
									$match.="的".$xz_sex;
								}
							}
							$a_zh='<a class="hello" onclick="sayHello(this,'.$l['id'].')" >打招呼</a>';
							$html.='<div class="inflo" data-id="'.$l['id'].'"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['id'])).'"><ul class="disbox-hor home-user-list"><li class="foot-icon"><img src="'.$avatar.'" /></li><li class="disbox-flex user_mession"><b class="tit">'.$l['nicheng'].'</b><p>'.$ziliao.'</p><p class="feature">'.$biaoqian.'</p><p class="recite">'.$match.'</p></ul></a><div class="bot_btn"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['id'])).'" class="look">看看Ta</a><a class="hello" onclick="sayHello(this,'.$l['id'].')">打招呼</a></div></div>';
						}
					}
					if(count($tuijian)<10)
					{
						echo json_encode(array('status'=>2,'log'=>$html));
						exit;
					}
					else
					{
						echo json_encode(array('status'=>1,'log'=>$html));
						exit;
					}
				}
				else
				{
					$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
					$menu=1;

					if($member['sex']==1)
					{
						$condition.=" AND sex=2 ";
					}
					else
					{
						$condition.=" AND sex=1 ";
					}
					$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
					if(!empty($match))
					{
						if($match['province']>0)
						{
							$temp_province=$match['province'];
						}
						else
						{
							if(!empty($member['province']))
							{
								$temp_province=$member['province'];
							}
							else
							{
								$temp_province=11;
							}
						}
						$condition.=" AND a.province= ".$temp_province;
						if(!empty($match['height']))
						{
							if($match['height']==1)
							{
								$condition.=" AND b.height<= 160 ";
							}
							elseif ($match['height']==2) {
								$condition.=" AND b.height> 160 AND b.height<=165 ";
							}
							elseif ($match['height']==3) {
								$condition.=" AND b.height> 165 AND b.height<=170 ";
							}
							else
							{
								$condition.=" AND b.height> 170 ";
							}
						}
						if(!empty($match['income']))
						{
							if($match['income']==1)
							{
								$condition.=" AND ( b.income= '2000-5000' OR b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}
							elseif ($match['income']==2) {
								$condition.=" AND ( b.income= '5000-10000' OR b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}
							elseif ($match['income']==3) {
								$condition.=" AND ( b.income= '10000-20000' OR b.income= '20000以上' ) ";
							}

						}
						if(!empty($match['education']))
						{
							if($match['education']==1)
							{
								$condition.=" AND ( b.education= '高中及中专' OR b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
							}
							elseif ($match['education']==2) {
								$condition.=" AND ( b.education= '大专' OR b.education= '本科' OR b.education= '硕士及以上' ) ";
							}
							elseif ($match['income']==3) {
								$condition.=" AND ( b.education= '本科' OR b.education= '硕士及以上' ) ";
							}

						}
						if(!empty($match['age']))
						{
							$now=time();
							$y=date('Y',$now);
							$m=date('m',$now);
							$d=date('d',$now);

							if($match['age']==1)
							{
								$temp_y=$y-18;
								$temp_y2=$y-25;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==2) {
								$temp_y=$y-25;
								$temp_y2=$y-35;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==3) {
								$temp_y=$y-35;
								$temp_y2=$y-45;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							elseif ($match['age']==4) {
								$temp_y=$y-45;
								$temp_y2=$y-55;
								$temp_y=''.$temp_y.$m.$d;
								$temp_y2=''.$temp_y2.$m.$d;
								$temp_brith=strtotime($temp_y);
								$temp_brith2=strtotime($temp_y2);
								$condition.=" AND ( a.brith> ".$temp_brith2." && a.brith< ".$temp_brith." ) ";
							}
							else {
								$temp_y=$y-55;
								$temp_y=''.$temp_y.$m.$d;
								$temp_brith=strtotime($temp_y);
								$condition.=" AND ( a.brith< ".$temp_brith." ) ";
							}

						}
					}
					else
					{
						if(!empty($member['province']))
						{
							$temp_province=$member['province'];
						}
						else
						{
							$temp_province=11;
						}
					}
					if($member['sex']==2)
					{
						$xz_sex="女生";
					}
					else
					{
						$xz_sex="男生";
					}

					$temp_tjid=pdo_fetch("SELECT id FROM ".tablename("jy_ppp_member")." WHERE weid=".$weid." ORDER BY id DESC LIMIT 1 ");
					$temp_tjid=$temp_tjid['id'];

					$temp_tjid_rand=mt_rand(0,9800);
					$temp_tjid_rand=$temp_tjid_rand/10000.0;
					$temp_tjid=floor($temp_tjid*$temp_tjid_rand);
					$tuijian=pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height,c.age,c.height as height2,c.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_match')." as c on a.id=c.mid WHERE a.weid=".$weid.$condition." AND a.id >= ".$temp_tjid."  LIMIT 10");
					foreach ($tuijian as $key => $value) {
						$tuijian[$key]['thumb']=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$value['id']." AND ( type=1 OR type=2 ) ");
						$temp_aihao=pdo_fetch("SELECT aihao FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$value['id']." LIMIT 1 ");
						$tuijian[$key]['aihao']=$temp_aihao['aihao'];
						$temp_tezheng=pdo_fetch("SELECT tezheng FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$value['id']." LIMIT 1 ");
						$tuijian[$key]['tezheng']=$temp_tezheng['tezheng'];
					}
					//echo "SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,a.mobile_auth,a.card_auth,b.height,c.age,c.height as height2,c.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_match')." as c on a.id=c.mid WHERE a.weid=".$weid.$condition." AND a.id >= (SELECT FLOOR( MAX(a.id) * RAND()) FROM ".tablename('jy_ppp_member')." ) ORDER BY a.id LIMIT 10";

					include $this->template('index');
				}
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileMail() {
		global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mail'))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'mail'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$op=$_GPC['op'];
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');

			if($op=='more')
			{
				$str=$_GPC['str'];
				if(!empty($str))
				{
					$str=substr($str, 0 ,-1 );
					$str=" AND a.id NOT IN ( ".$str." ) ";
					$list=pdo_fetchall("SELECT a.id,a.sendid as mid,a.type,a.createtime,a.yuedu,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.sendid=b.id left join ".tablename('jy_ppp_basic')." as c on a.sendid=c.mid WHERE a.weid=".$weid." AND a.mid=".$mid.$str." GROUP BY a.sendid ORDER BY a.yuedu ASC,a.createtime DESC LIMIT 10 ");
					$html='';
					foreach ($list as $key => $l) {
						if($l['type']!=3)
						{
							if(!empty($l['avatar']))
							{
								$avatar=$_W['attachurl'].$l['avatar'];
							}
							else
							{
								if($l['sex']==1)
								{
									$avatar="../addons/jy_ppp/images/boy.png";
								}
								else
								{
									$avatar="../addons/jy_ppp/images/girl.png";
								}
							}
						}
						else
						{
							if (!empty($sitem['adminthumb']))
							{
                        		$avatar=$_W['attachurl'].$sitem['adminthumb'];
							}
                        	else
                        	{
                        		$avatar="../addons/jy_ppp/images/adminthumb.png";
                        	}
						}
						$avatar='<img src='.$avatar.'>';

						$title='';
						if($l['type']!=3)
						{
							$title='<b class="tit">'.$l['nicheng'].'<span class="pink">';
							if(empty($l['yuedu']))
							{
								if( $l['type']==1 )
								{
									$title.='[会员推荐]'.'</span>';
								}
								if( $l['type']==2 )
								{
									$title.='[最新回信]'.'</span>';
								}
							}
						}
						else
						{
							$title='<b class="tit">管理员<span class="pink"></span>';
						}

						$ziliao='';
						if($l['type']!=3)
						{
							if(!empty($l['brith']))
							{
								$birthday=$l['brith'];
								$now=time();
							    $month=0;
							    if(date('m', $now)>date('m', $birthday))
							    $month=1;
							    if(date('m', $now)==date('m', $birthday))
							    if(date('d', $now)>=date('d', $birthday))
							    $month=1;
							    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
								$ziliao.=$nianlin."岁·";
							}
							if(!empty($l['province']))
							{
								$ziliao.=$province[$l['province']];
							}
							if (!empty($l['height']))
							{
								$ziliao.='·'.$l['height'].'cm';
							}
						}
						if($l['yuedu']==0)
						{
							$yuedu='<i class="cycle_tag">未读</i>';
						}
						else
						{
							$yuedu='';
						}
						$html.="<a href=".$this->createMobileUrl('chat',array('id'=>$l['mid'])).'><ul class="disbox-hor user-list mailid" data-id="'.$l['id'].'"><li class="foot-icon disbox-center">'.$avatar.'</li><li class="disbox-flex user_mession">'.$title.'</b><p class="bot">'.$ziliao.'</p></li><div class="right">'.date("G:i",$l['createtime']).'</div>'.$yuedu.'</ul></a>';
					}
					if(count($list)>9)
					{
						echo json_encode(array('status'=>1,'log'=>$html));
						exit;
					}
					else
					{
						echo json_encode(array('status'=>2,'log'=>$html));
						exit;
					}
				}
				else
				{
					echo 3;
					exit;
				}
			}
			else
			{
				$temp_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC LIMIT 1 ");
				$today=strtotime("today");

				if($temp_log['createtime']<$today)
				{
					$data=array(
							'weid'=>$weid,
							'mid'=>$mid,
							'createtime'=>TIMESTAMP,
						);
					pdo_insert('jy_ppp_login_log',$data);

					echo "<script>
						window.location.href = '".$this->createMobileUrl('zhaohu',array('foo'=>'mail'))."';
					</script>";
					exit;
				}
				else
				{
					$menu=2;

					$lianxi=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND type!=0");
					$visit=pdo_fetch("SELECT b.nicheng FROM ".tablename('jy_ppp_visit')." as a left join ".tablename('jy_ppp_member')." as b on a.visitid=b.id WHERE a.weid=".$weid." AND a.mid=".$mid." ORDER BY a.createtime DESC LIMIT 1");
					$list=pdo_fetchall("SELECT a.id,a.sendid as mid,a.type,a.createtime,a.yuedu,b.avatar,b.nicheng,b.province,b.brith,b.sex,c.height FROM ".tablename('jy_ppp_xinxi')." as a left join ".tablename('jy_ppp_member')." as b on a.sendid=b.id left join ".tablename('jy_ppp_basic')." as c on a.sendid=c.mid WHERE a.weid=".$weid." AND a.mid=".$mid." GROUP BY a.sendid ORDER BY a.yuedu ASC,a.createtime DESC LIMIT 10 ");

					include $this->template('mail');
				}
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileLuck() {
		global $_W,$_GPC;

		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'luck'))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'luck'))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$temp_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC LIMIT 1 ");
			$today=strtotime("today");

			if($temp_log['createtime']<$today)
			{
				$data=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'createtime'=>TIMESTAMP,
					);
				pdo_insert('jy_ppp_login_log',$data);

				echo "<script>
					window.location.href = '".$this->createMobileUrl('zhaohu',array('foo'=>'luck'))."';
				</script>";
				message($today);
				exit;
			}
			else
			{
				$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
				$op=$_GPC['op'];
				if($op=='zhaohu2')
				{
					$id=$_GPC['id'];
					$today=strtotime("today");
					$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$id." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
					if(!empty($temp) && $temp['createtime']>$today)
					{
						echo 2;
					}
					else
					{
						$data=array(
								'weid'=>$weid,
								'mid'=>$id,
								'sendid'=>$mid,
								'type'=>0,
								'yuedu'=>0,
								'createtime'=>TIMESTAMP,
							);
						if($member['sex']==2)
						{
							$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
							$data['content']=$wenti['name'];
							$data['zhaohuid']=$wenti['id'];
							if(empty($data['content']))
							{
								$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
							}
						}
						else
						{
							$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
							$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
							$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
							$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");

							$temp_wt=array();
							if(!empty($member['brith']))
							{
								array_push($temp_wt, '1','2','3');
								if(!empty($basic['height']))
								{
									array_push($temp_wt, '4');
								}
							}
							if(!empty($member['province']))
							{
								array_push($temp_wt, '7');
								if(!empty($aihao))
								{
									array_push($temp_wt, '6');
								}
								if(!empty($tezheng))
								{
									array_push($temp_wt, '5');
								}
							}
							if(!empty($basic['blood']))
							{
								array_push($temp_wt, '8');
							}
							if(!empty($desc['leixin']))
							{
								array_push($temp_wt, '9');
							}
							if(!empty($aihao))
							{
								array_push($temp_wt, '10');
							}
							if(!empty($tezheng))
							{
								array_push($temp_wt, '11');
							}
							array_push($temp_wt, '12','13','14','15','16','17','18');
							$temp_num=count($temp_wt)-1;
							$xiabiao=mt_rand(0,$temp_num);
							$rand_wt=$temp_wt[$xiabiao];
							switch ($rand_wt) {
								case 1:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
									break;
								case 2:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
									break;
								case 3:
									$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
									break;
								case 4:
									$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
									break;
								case 5:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
									break;
								case 6:
									$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
									break;
								case 7:
									$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
									break;
								case 8:
									$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
									break;
								case 9:
									$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
									break;
								case 10:
									$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
									break;
								case 11:
									$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
									break;
								case 12:
									$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
									break;
								case 13:
									$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
									break;
								case 14:
									$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
									break;
								case 15:
									$data['content']="hey，我觉得你很有气质，可以认识你吗？";
									break;
								case 16:
									$data['content']="我们好像在那里见过，可以认识你吗？";
									break;
								case 17:
									$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
									break;
								default:
									$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
									break;
							}
						}
						pdo_insert("jy_ppp_xinxi",$data);
						echo 1;
						$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
						$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
						if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
						{
							$send_kefu_tf=false;
							$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
							if($sitem['jiange']>0)
							{
								$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
								$temp_jg_time=time()-$temp_kefu['createtime'];
								if($temp_jg_time>$sitem['jiange']*60)
								{
									$today=strtotime('today');
									$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
									if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
									{
										$send_kefu_tf=true;
									}
								}
							}
							else
							{
								$today=strtotime('today');
								$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
								if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
								{
									$send_kefu_tf=true;
								}
							}

							if($send_kefu_tf==true)
							{
								$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
								$text=urlencode($text2);
								$desc2="点击下方【信箱】按钮查看所有来信";
								$desc=urlencode($desc2);
								$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
								$pic=urlencode($pic2);
								$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
								$url=urlencode($url2);
								$access_token = WeAccount::token();
								$data = array(
								  "touser"=>$kefu_member['from_user'],
								  "msgtype"=>"news",
								  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
								);
								$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
								load()->func('communication');
								$response = ihttp_request($url, urldecode(json_encode($data)));
								$errcode=json_decode($response['content'],true);
								$data3=array(
										'weid'=>$weid,
										'mid'=>$id,
										'type'=>'news',
										'content'=>$text2,
										'desc'=>$desc2,
										'url'=>$url2,
										'picurl'=>$pic2,
										'status'=>$errcode['errcode'],
										'createtime'=>TIMESTAMP,
									);
								pdo_insert("jy_ppp_kefu",$data3);
							}
						}
					}
					exit;
				}
				elseif ($op=='zhaohu') {
					$str=$_GPC['str'];
					if(!empty($str))
					{
						$str=substr($str,0,-1 );
					}
					else{
						echo 1;
						exit;
					}
					$zh=explode(",", $str);
					if($member['sex']==1)
					{
						$basic=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
						$desc=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
						$aihao=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
						$tezheng=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$mid." LIMIT 1");
						$temp_wt=array();
						if(!empty($member['brith']))
						{
							array_push($temp_wt, '1','2','3');
							if(!empty($basic['height']))
							{
								array_push($temp_wt, '4');
							}
						}
						if(!empty($member['province']))
						{
							array_push($temp_wt, '7');
							if(!empty($aihao))
							{
								array_push($temp_wt, '6');
							}
							if(!empty($tezheng))
							{
								array_push($temp_wt, '5');
							}
						}
						if(!empty($basic['blood']))
						{
							array_push($temp_wt, '8');
						}
						if(!empty($desc['leixin']))
						{
							array_push($temp_wt, '9');
						}
						if(!empty($aihao))
						{
							array_push($temp_wt, '10');
						}
						if(!empty($tezheng))
						{
							array_push($temp_wt, '11');
						}
						array_push($temp_wt, '12','13','14','15','16','17','18');
						$temp_num=count($temp_wt)-1;
					}

					foreach ($zh as $key => $value) {
						$temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$value." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
						if(!empty($temp) && $temp['createtime']>$today)
						{

						}
						else
						{
							$data=array(
								'weid'=>$weid,
								'mid'=>$value,
								'sendid'=>$mid,
								'type'=>0,
								'yuedu'=>0,
								'createtime'=>TIMESTAMP,
							);
							if($member['sex']==2)
							{
								$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
								$data['content']=$wenti['name'];
								$data['zhaohuid']=$wenti['id'];
								if(empty($data['content']))
								{
									$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
								}
							}
							else
							{
								$xiabiao=mt_rand(0,$temp_num);
								$rand_wt=$temp_wt[$xiabiao];
								switch ($rand_wt) {
									case 1:
										$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,有时间可以聊会儿天么？";
										break;
									case 2:
										$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,希望能够了解你，可以交个朋友吗？";
										break;
									case 3:
										$data['content']="哈喽！我生于".date('Y',$member['brith'])."年,我觉得我们挺合适的。";
										break;
									case 4:
										$data['content']="可以认识一下么？我生于".date('Y',$member['brith'])."年,身高".$basic['height']."cm,我觉得我们挺合适的。";
										break;
									case 5:
										$data['content']="哈喽！我个人很".$tezheng['tezheng'].",现居住在".$province[$member['province']]."，希望能够了解你，你怎么看？";
										break;
									case 6:
										$data['content']="嗨！我居住在".$province[$member['province']]."，平时喜欢".$aihao['aihao']."，希望你能和你交朋友哈！";
										break;
									case 7:
										$data['content']="嗨！我是来自".$province[$member['province']]."的年轻人，希望你会记得我哈~";
										break;
									case 8:
										$data['content']="嗨！我是".$basic['blood']."血型的,不知道你是不是也是这样的呢？";
										break;
									case 9:
										$data['content']="哈喽！我喜欢".$basic['leixin']."的女生,不知道你是不是也是这样的呢？";
										break;
									case 10:
										$data['content']="哈喽！我平时很喜欢".$aihao['aihao'].",不知道你是不是也是这样的呢？";
										break;
									case 11:
										$data['content']="哈喽！我个人很".$tezheng['tezheng'].",希望能够了解你，可以交个朋友吗？";
										break;
									case 12:
										$data['content']="哈喽！美女！我对你很感兴趣，不知道我们有缘分吗？";
										break;
									case 13:
										$data['content']="Hi,我觉得你很不错，有时间可以聊会儿天么？";
										break;
									case 14:
										$data['content']="hello,你很漂亮，我看上你了，我真心想找个合适的女朋友，也许我们能交个朋友哈~";
										break;
									case 15:
										$data['content']="hey，我觉得你很有气质，可以认识你吗？";
										break;
									case 16:
										$data['content']="我们好像在那里见过，可以认识你吗？";
										break;
									case 17:
										$data['content']="hey，我觉得你的打扮很多看，可以认识你吗？";
										break;
									default:
										$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
										break;
								}
							}
							pdo_insert("jy_ppp_xinxi",$data);
							$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$value);
							$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
							if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
							{
								$send_kefu_tf=false;
								$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
								if($sitem['jiange']>0)
								{
									$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$value." ORDER BY createtime DESC LIMIT 1 ");
									$temp_jg_time=time()-$temp_kefu['createtime'];
									if($temp_jg_time>$sitem['jiange']*60)
									{
										$today=strtotime('today');
										$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$value." AND createtime>".$today);
										if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
										{
											$send_kefu_tf=true;
										}
									}
								}
								else
								{
									$today=strtotime('today');
									$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$value." AND createtime>".$today);
									if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
									{
										$send_kefu_tf=true;
									}
								}

								if($send_kefu_tf==true)
								{
									$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
									$text=urlencode($text2);
									$desc2="点击下方【信箱】按钮查看所有来信";
									$desc=urlencode($desc2);
									$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
									$pic=urlencode($pic2);
									$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
									$url=urlencode($url2);
									$access_token = WeAccount::token();
									$data = array(
									  "touser"=>$kefu_member['from_user'],
									  "msgtype"=>"news",
									  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
									);
									$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
									load()->func('communication');
									$response = ihttp_request($url, urldecode(json_encode($data)));
									$errcode=json_decode($response['content'],true);
									$data3=array(
											'weid'=>$weid,
											'mid'=>$value,
											'type'=>'news',
											'content'=>$text2,
											'desc'=>$desc2,
											'url'=>$url2,
											'picurl'=>$pic2,
											'status'=>$errcode['errcode'],
											'createtime'=>TIMESTAMP,
										);
									pdo_insert("jy_ppp_kefu",$data3);
								}
							}
						}
					}
					echo 1;
					exit;
				}elseif($op=='more')
				{
					$str=$_GPC['str'];
					if(empty($str))
					{
						echo json_encode(array('status'=>3));
						exit;
					}
					else
					{
						$str=substr($str, 0,-1);
					}
					$condition=$condition.=" AND a.id NOT IN ( ".$str." ) ";
					if($member['sex']==1)
					{
						$condition.=" AND sex=2 ";
					}
					else
					{
						$condition.=" AND sex=1 ";
					}
					$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
					if(!empty($match))
					{
						if($match['province']>0)
						{
							$temp_province=$match['province'];
						}
						else
						{
							if(!empty($member['province']))
							{
								$temp_province=$member['province'];
							}
							else
							{
								$temp_province=11;
							}
						}
						$condition.=" AND a.province= ".$temp_province;
					}
					if($member['sex']==2)
					{
						$xz_sex="女生";
					}
					else
					{
						$xz_sex="男生";
					}
					$today=strtotime("today");
					$temp_tjid=pdo_fetch("SELECT id FROM ".tablename("jy_ppp_member")." WHERE weid=".$weid." ORDER BY id DESC LIMIT 1 ");
					$temp_tjid=$temp_tjid['id'];

					$temp_tjid_rand=mt_rand(0,9800);
					$temp_tjid_rand=$temp_tjid_rand/10000.0;
					$temp_tjid=floor($temp_tjid*$temp_tjid_rand);

					$tuijian=pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_match')." as c on a.id=c.mid WHERE a.weid=".$weid.$condition." AND a.id >= ".$temp_tjid." LIMIT 10");

					$html='';
					if(!empty($tuijian))
					{
						foreach ($tuijian as $key => $l) {
							if(!empty($l['avatar']))
							{
								$avatar=$_W['attachurl'].$l['avatar'];
							}
							else
							{
								if($l['sex']==1)
								{
									$avatar="../addons/jy_ppp/images/boy.png";
								}
								else
								{
									$avatar="../addons/jy_ppp/images/girl.png";
								}
							}
							$ziliao='';
							if(!empty($l['province']))
							{
								$ziliao.="·".$province[$l['province']];
							}
							if(!empty($l['brith']))
							{
								$birthday=$l['brith'];
								$now=time();
							    $month=0;
							    if(date('m', $now)>date('m', $birthday))
							    $month=1;
							    if(date('m', $now)==date('m', $birthday))
							    if(date('d', $now)>=date('d', $birthday))
							    $month=1;
							    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;
								$ziliao.=$nianlin."岁";
							}
							if(!empty($l['height']))
							{
								$ziliao.="·".$l['height']."cm";
							}
							// $temp=pdo_fetch("SELECT createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$l['id']." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
							// if($temp['createtime']>$today)
							// {
							// 	$a_zh='<span class="hello_out" >已打招呼</span>';
							// }
							// else
							// {
							// 	$a_zh='<span class="hello" onclick="sayPeople2(this,'.$l['id'].')" >打招呼</span>';
							// }
							if(!empty($l['beizhu']))
							{
								$match=$l['beizhu'];
							}
							else
							{
								if (!empty($l['province2']))
								{
									$match="寻找".$province[$l['province2']];
									if(!empty($l['age']))
									{
										$match.=" ".$match_age[$l['age']];
									}
									if(!empty($l['height2']))
									{
										$match.=" ".$match_height[$l['height2']]."cm";
									}
									$match.="的".$xz_sex;
								}
								else
								{
									$match="寻找";
									if (!empty($l['province']))
									{
										$match.=$province[$l['province']];
									}
									else
									{
										$match.=$province[11];
									}
									$match.="的".$xz_sex;
								}
							}
							$a_zh='<span class="hai_hello" onclick="sayHello(this,'.$l['id'].')" style="font-size:13px" >打招呼</span>';
							$html.='<ul class="disbox-hor user-list" data-id="'.$l['id'].'"><li class="foot-icon"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['id'])).'"><img src="'.$avatar.'" /></a></li><li class="disbox-flex user_mession"><a href="'.$this->createMobileUrl('detail',array('id'=>$l['id'])).'"><b class="tit">'.$l['nicheng'].'</b><p class="bot">'.$ziliao.'</p><p class="foot_imgarrange">'.$match.'</p></a></li><div>'.$a_zh.'</div></ul>';
						}
					}
					if(count($tuijian)<10)
					{
						echo json_encode(array('status'=>2,'log'=>$html));
						exit;
					}
					else
					{
						echo json_encode(array('status'=>1,'log'=>$html));
						exit;
					}
				}
				else
				{
					$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
					$menu=3;

					if($member['sex']==1)
					{
						$condition.=" AND sex=2 ";
					}
					else
					{
						$condition.=" AND sex=1 ";
					}
					$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
					if(!empty($match))
					{
						if($match['province']>0)
						{
							$temp_province=$match['province'];
						}
						else
						{
							if(!empty($member['province']))
							{
								$temp_province=$member['province'];
							}
							else
							{
								$temp_province=11;
							}
						}
						$condition.=" AND a.province= ".$temp_province;
					}
					if($member['sex']==2)
					{
						$xz_sex="女生";
					}
					else
					{
						$xz_sex="男生";
					}
					$temp_tjid=pdo_fetch("SELECT id FROM ".tablename("jy_ppp_member")." WHERE weid=".$weid." ORDER BY id DESC LIMIT 1 ");
					$temp_tjid=$temp_tjid['id'];

					$temp_tjid_rand=mt_rand(0,9800);
					$temp_tjid_rand=$temp_tjid_rand/10000.0;
					$temp_tjid=floor($temp_tjid*$temp_tjid_rand);
					$tuijian=pdo_fetchall("SELECT a.id,a.avatar,a.sex,a.nicheng,a.province,a.brith,a.beizhu,b.height,c.age,c.height as height2,c.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_match')." as c on a.id=c.mid WHERE a.weid=".$weid.$condition." AND a.id >= ".$temp_tjid." LIMIT 10");
					include $this->template('luck');
				}
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileGeren() {
		//个人中心
		global $_W,$_GPC;

		$xuniid=intval($_GPC['xuniid']);
		if(empty($xuniid))
		{
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
				$weixin=0;

				$weid=$_GPC['i'];

				$mid=$_SESSION['mid'];
				if(!empty($mid))
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
				}
			}
			else
			{
				$weixin=1;

				$weid=$_W['uniacid'];
				//checkAuth();
				$from_user=$_SESSION['jy_openid'];
				if(empty($from_user))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'geren'))."';
					</script>";
				}
				else
				{
					$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
					if(empty($member_temp['nickname']) || $member_temp['uid']==0)
					{
						unset($uid);
					}
					else
					{
						$uid=$member_temp['uid'];
						unset($member_temp);
					}
					if(empty($uid))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'geren'))."';
						</script>";
					}
					else
					{
						$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
						$mid=$member['id'];
					}
				}
			}
		}
		else
		{
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
				$weixin=0;

				$weid=$_GPC['i'];

				$dyid=$_SESSION['dyid'];
				if(!empty($dyid))
				{
					$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND id=".$dyid);
				}
			}
			else
			{
				$weixin=1;

				$weid=$_W['uniacid'];
				//checkAuth();
				$from_user=$_SESSION['jy_openid'];
				if(empty($from_user))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'geren'))."';
					</script>";
				}
				else
				{
					$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
					if(empty($member_temp['nickname']) || $member_temp['uid']==0)
					{
						unset($uid);
					}
					else
					{
						$uid=$member_temp['uid'];
						unset($member_temp);
					}
					if(empty($uid))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'geren'))."';
						</script>";
					}
					else
					{
						$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
						$dyid=$dianyuan['id'];
					}
				}
			}
			if(empty($dianyuan))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('dy_login')."';
				</script>";
			}
			else
			{
				$xuni_qx=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xuni_member')." WHERE weid=".$weid." AND mid=".$xuniid." AND dyid=".$dyid);
				if(empty($xuni_qx))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('dy_user')."';
					</script>";
				}
				else
				{
					$mid=$xuniid;

					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
				}
			}
		}

		if(!empty($member))
		{
			$temp_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC LIMIT 1 ");
			if (empty($_GPC['xuniid']) || $member['type']!=3)
			{
				$today=strtotime("today");
			}
			else
			{
				$today=0;
			}

			if($temp_log['createtime']<$today)
			{
				$data=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'createtime'=>TIMESTAMP,
					);
				pdo_insert('jy_ppp_login_log',$data);

				echo "<script>
					window.location.href = '".$this->createMobileUrl('zhaohu',array('foo'=>'geren'))."';
				</script>";
				message($today);
				exit;
			}
			else
			{
				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				$menu=4;
				// load()->classs('weixin.account');
				// $accObj= WeixinAccount::create($_W['acid']);
				// $notice=$accObj->sendTplNotice($from_user,'57_MppLNo_sej2j2Zeh2eIyXfzvQcBD4xArfuaJ3Sps',array('first'=>array('value'=>'测试测试'),'keyword1'=>array('value'=>'123'),'keyword2'=>array('value'=>'456'),'remark'=>array('value'=>'789')),$_W['siteroot'].'app/'.substr($this->createMobileUrl('safe'),2),'#112255');
				// print_r($notice);

				$birthday=$member['brith'];
				if(empty($birthday))
				{
					$birthday=662688000;
				}
				$now=time();
			    $month=0;
			    if(date('m', $now)>date('m', $birthday))
			    $month=1;
			    if(date('m', $now)==date('m', $birthday))
			    if(date('d', $now)>=date('d', $birthday))
			    $month=1;
			    $nianlin=date('Y', $now)-date('Y', $birthday)+$month;

				if(empty($member['baoyue']))
				{
					$baoyue=0;
				}
				else
				{
					$baoyue=$member['baoyue']-time();
					if($baoyue<=0)
					{
						$baoyue=0;
					}
					else
					{
						$baoyue=floor($baoyue/86400);
					}
				}
				if(empty($member['shouxin']))
				{
					$shouxin=0;
				}
				else
				{
					$shouxin=$member['shouxin']-time();
					if($shouxin<=0)
					{
						$shouxin=0;
					}
					else
					{
						$shouxin=floor($shouxin/86400);
					}
				}

				$basic=pdo_fetch("SELECT blank FROM ".tablename('jy_ppp_basic')." WHERE weid=".$weid." AND mid=".$mid);
				$desc=pdo_fetch("SELECT blank FROM ".tablename('jy_ppp_desc')." WHERE weid=".$weid." AND mid=".$mid);
				$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);

				include $this->template('geren');
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileDetail() {
		//
		global $_W,$_GPC;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'detail','id'=>$_GPC['id']))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'detail','id'=>$_GPC['id']))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$temp_log=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_login_log')." WHERE weid=".$weid." AND mid=".$mid." ORDER BY createtime DESC LIMIT 1 ");
			$today=strtotime("today");

			if($temp_log['createtime']<$today)
			{
				$data=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'createtime'=>TIMESTAMP,
					);
				pdo_insert('jy_ppp_login_log',$data);

				echo "<script>
					window.location.href = '".$this->createMobileUrl('zhaohu',array('foo'=>'detail'))."';
				</script>";
				message($today);
				exit;
			}
			else
			{
				$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
				$op=$_GPC['op'];
				if($op=='zhaohu')
				{

					echo 1;
					exit;
				}
				elseif ($op=='pic') {
					$id=$_GPC['id'];
					if(empty($id))
					{
						echo 3;
						exit;
					}
					else
					{
						$text="你好，可以上传几张你的照片吗？想多了解你一些。";
						$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$id." AND sendid=".$id." AND content='".$text."'");
						if(empty($temp))
						{
							$data4=array(
								'weid'=>$weid,
								'mid'=>$id,
								'sendid'=>$mid,
								'content'=>$text,
								'type'=>0,
								'yuedu'=>0,
								'createtime'=>TIMESTAMP,
							);
							pdo_insert("jy_ppp_xinxi",$data4);
							echo 1;
							exit;
						}
						else
						{
							echo 2;
							exit;
						}
					}
				}
				elseif ($op=='attent') {
					$id=$_GPC['id'];
					if(empty($id))
					{
						echo 3;
						exit;
					}
					else
					{
						$attent=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_attent')." WHERE weid=".$weid." AND mid=".$mid." AND attentid=".$id);
						if(empty($attent))
						{
							$data=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'attentid'=>$id,
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_attent",$data);
							echo 1;
							exit;
						}
						else
						{
							pdo_delete("jy_ppp_attent",array('weid'=>$weid,'mid'=>$mid,'attentid'=>$id));
							echo 2;
							exit;
						}
					}
				}
				elseif ($op=='black') {
					$id=$_GPC['id'];
					if(empty($id))
					{
						echo 3;
						exit;
					}
					else
					{
						$attent=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_black')." WHERE weid=".$weid." AND mid=".$mid." AND blackid=".$id);
						if(empty($attent))
						{
							$data=array(
									'weid'=>$weid,
									'mid'=>$mid,
									'blackid'=>$id,
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_black",$data);
							echo 1;
							exit;
						}
						else
						{
							pdo_delete("jy_ppp_black",array('weid'=>$weid,'mid'=>$mid,'blackid'=>$id));
							pdo_delete("jy_ppp_attent",array('weid'=>$weid,'mid'=>$mid,'attentid'=>$id));
							echo 2;
							exit;
						}
					}
				}
				else
				{
					$id=$_GPC['id'];
					if(empty($id))
					{
						if($member['sex']==1)
						{
							$condition.=" AND sex=2 ";
							$condition2.=" AND sex=2 ";
						}
						else
						{
							$condition.=" AND sex=1 ";
							$condition2.=" AND sex=1 ";
						}
						$match=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_match')." WHERE weid=".$weid." AND mid=".$mid);
						if(!empty($match))
						{
							if($match['province']>0)
							{
								$temp_province=$match['province'];
							}
							else
							{
								if(!empty($member['province']))
								{
									$temp_province=$member['province'];
								}
								else
								{
									$temp_province=11;
								}
							}
							$condition.=" AND province= ".$temp_province;
						}
						$tuijian=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_member')." ) ORDER BY id LIMIT 1");
						if(!empty($tuijian))
						{
							$id=$tuijian['id'];
						}
						else
						{
							$tuijian=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition2." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_member')." ) ORDER BY id LIMIT 1");
							if(!empty($tuijian))
							{
								$id=$tuijian['id'];
							}
							else
							{
								$tuijian=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid.$condition2." LIMIT 100 ");
								$num=count($tuijian)-1;
								$ran=mt_rand(0,$num);
								$id=$tuijian[$ran]['id'];
							}
						}
					}
					if(empty($id))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('index')."';
						</script>";
					}
					else
					{
						$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
						$detail=pdo_fetch("SELECT a.id,a.nicheng,a.avatar,a.beizhu,a.sex,a.brith,a.province,a.city,a.baoyue,a.mobile_auth,a.card_auth,b.height,b.weight,b.blood,b.education,b.job,b.income,b.marriage,b.house,c.child,c.yidi,c.leixin,c.sex as sex2,c.fumu,c.meili,d.age as age2,d.height as height2,d.education as education2,d.income as income2,d.province as province2 FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid left join ".tablename('jy_ppp_desc')." as c on a.id=c.mid left join ".tablename('jy_ppp_match')." as d on a.id=d.mid WHERE a.weid=".$weid." AND a.id= ".$id);
						$thumb=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$id." AND ( type=1 OR type=2 )");
						$aihao=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_aihao')." WHERE weid=".$weid." AND mid=".$id." LIMIT 3");
						$tezheng=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_tezheng')." WHERE weid=".$weid." AND mid=".$id." LIMIT 3");
						if(!empty($aihao))
						{
							foreach ($aihao as $key => $value) {
								$detail['aihao'].=$value['aihao'].",";
							}
							$detail['aihao']=substr($detail['aihao'], 0,-1);
						}
						if(!empty($tezheng))
						{
							foreach ($tezheng as $key => $value) {
								$detail['tezheng'].=$value['tezheng'].",";
							}
							$detail['tezheng']=substr($detail['tezheng'], 0,-1);
						}
						$attent=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_attent')." WHERE weid=".$weid." AND mid=".$mid." AND attentid=".$id);
						$black=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_black')." WHERE weid=".$weid." AND mid=".$mid." AND blackid=".$id);
						if($member['sex']==1)
						{
							if(empty($member['baoyue']))
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=$member['baoyue']-time();
								if($baoyue<=0)
								{
									$baoyue=0;
								}
								else
								{
									$baoyue=1;
								}
							}
							if(!empty($baoyue))
							{
								$ltlx=2;
							}
							else
							{
								$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
								if(empty($temp))
								{
									$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=2 ");
									if(!empty($temp))
									{
										$ltlx=3;
									}
									else
									{
										$today=strtotime('today');
										$temp=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=0 AND createtime> ".$today);
										if(empty($temp))
										{
											$ltlx=1;
										}
										else
										{
											if(count($temp)>1)
											{
												$ltlx=3;
											}
											else
											{
												$ltlx=1;
											}
										}
									}
								}
								else
								{
									$ltlx=2;
								}
							}
						}
						else
						{
							$duifang=pdo_fetch("SELECT baoyue FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
							if(empty($duifang['baoyue']))
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=$duifang['baoyue']-time();
								if($baoyue<=0)
								{
									$baoyue=0;
								}
								else
								{
									$baoyue=1;
								}
							}
							if(!empty($baoyue))
							{
								$ltlx=2;
							}
							else
							{
								$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
								if(!empty($temp))
								{
									$ltlx=2;
								}
								else
								{
									$ltlx=1;
								}
							}
						}
						include $this->template('detail');
					}
				}
			}
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileUserthumb() {
		//
		include_once 'inc/mobile/userthumb.php';
	}
	public function doMobileChat() {
		//
		global $_W,$_GPC;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'chat','id'=>$_GPC['id']))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'chat','id'=>$_GPC['id']))."';
					</script>";
				}
				else
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
					$mid=$member['id'];
				}
			}
		}

		if(!empty($member))
		{
			$province=array('11'=>'北京市','12'=>'天津市','13'=>'河北省','14'=>'山西省','15'=>'内蒙古','21'=>'辽宁省','22'=>'吉林省','23'=>'黑龙江省','31'=>'上海市','32'=>'江苏省','33'=>'浙江省','34'=>'安徽省','35'=>'福建省','36'=>'江西省','37'=>'山东省','41'=>'河南省','42'=>'湖北省','43'=>'湖南省','44'=>'广东省','45'=>'广西','46'=>'海南省','50'=>'重庆市','51'=>'四川省','52'=>'贵州省','53'=>'云南省','54'=>'西藏','61'=>'陕西省','62'=>'甘肃省','63'=>'青海省','64'=>'宁夏','65'=>'新疆','71'=>'台湾省','81'=>'香港','82'=>'澳门');
			$op=$_GPC['op'];
			if($op=='zhaohu')
			{
				$id=$_GPC['id'];
				if(empty($id))
				{
					echo 3;
					exit;
				}
				$today=strtotime("today");
				$temp=pdo_fetch("SELECT zhaohuid,createtime FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$mid." AND mid=".$id." AND type=0 ORDER BY createtime DESC LIMIT 1 ");
				if(!empty($temp) && $member['sex']==2 && !empty($temp['zhaohuid']))
				{
					$temp_huifu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND sendid=".$id." AND mid=".$mid." AND type=0 AND zhaohuid=".$temp['zhaohuid']." AND createtime>".$temp['createtime']);
					if(!empty($temp_huifu))
					{
						unset($temp['createtime']);
					}
				}
				if(!empty($temp) && $temp['createtime']>$today)
				{
					echo 2;
				}
				else
				{
					$data=array(
							'weid'=>$weid,
							'mid'=>$id,
							'sendid'=>$mid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
					if($member['sex']==2)
					{
						$wenti=pdo_fetch("SELECT id,name FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=0 AND enabled=1 "." AND id >= (SELECT FLOOR( MAX(id) * RAND()) FROM ".tablename('jy_ppp_zhaohu')." ) ORDER BY id LIMIT 1");
						$data['content']=$wenti['name'];
						$data['zhaohuid']=$wenti['id'];
						if(empty($data['content']))
						{
							$data['content']="hello,您好！很高兴认识你，可以交个朋友么？";
						}
					}
					else
					{
						$data['content']="你觉得她很赞，和她打了个招呼";
					}
					pdo_insert("jy_ppp_xinxi",$data);
					echo 1;
					$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
					if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
					{
						$send_kefu_tf=false;
						$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
						if($sitem['jiange']>0)
						{
							$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
							$temp_jg_time=time()-$temp_kefu['createtime'];
							if($temp_jg_time>$sitem['jiange']*60)
							{
								$today=strtotime('today');
								$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
								if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
								{
									$send_kefu_tf=true;
								}
							}
						}
						else
						{
							$today=strtotime('today');
							$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
							if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
							{
								$send_kefu_tf=true;
							}
						}

						if($send_kefu_tf==true)
						{
							$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
							$text=urlencode($text2);
							$desc2="点击下方【信箱】按钮查看所有来信";
							$desc=urlencode($desc2);
							$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
							$pic=urlencode($pic2);
							$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
							$url=urlencode($url2);
							$access_token = WeAccount::token();
							$data = array(
							  "touser"=>$kefu_member['from_user'],
							  "msgtype"=>"news",
							  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
							);
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
							load()->func('communication');
							$response = ihttp_request($url, urldecode(json_encode($data)));
							$errcode=json_decode($response['content'],true);
							$data3=array(
									'weid'=>$weid,
									'mid'=>$id,
									'type'=>'news',
									'content'=>$text2,
									'desc'=>$desc2,
									'url'=>$url2,
									'picurl'=>$pic2,
									'status'=>$errcode['errcode'],
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_kefu",$data3);
						}
					}
					exit;
				}
			}
			elseif ($op=='huifu') {
				$id=$_GPC['id'];
				if($member['sex']==2)
				{
					echo 3;
					exit;
				}
				else
				{
					if(empty($member['baoyue']))
					{
						$baoyue=0;
					}
					else
					{
						$baoyue=$member['baoyue']-time();
						if($baoyue<=0)
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=1;
						}
					}
					if(!empty($baoyue))
					{
						echo 3;
						exit;
					}
					else
					{
						$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
						if(!empty($temp))
						{
							echo 3;
							exit;
						}
						else
						{
							if(empty($member['credit']))
							{
								echo 1;
								exit;
							}
							else
							{
								echo 2;
								exit;
							}
						}
					}
				}
			}
			elseif ($op=='ans') {
				$id=$_GPC['id'];
				$zhaohuid=$_GPC['wtid'];
				$ansid=$_GPC['ansid'];
				if(empty($zhaohuid) || empty($ansid) || empty($id))
				{
					echo json_encode(array('status'=>2));
					exit;
				}
				else
				{
					$ans=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND id=".$ansid);
					$html=$ans['description'];
					if(empty($html))
					{
						$html=$ans['name'];
					}

					$data=array(
							'weid'=>$weid,
							'mid'=>$id,
							'sendid'=>$mid,
							'content'=>$html,
							'zhaohuid'=>$zhaohuid,
							'huifuid'=>$ansid,
							'type'=>0,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_xinxi",$data);
					echo json_encode(array('status'=>1,'time'=>date('Y-m-d G:i'),'data'=>$html));
					$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
					if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
					{
						$send_kefu_tf=false;
						$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
						if($sitem['jiange']>0)
						{
							$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
							$temp_jg_time=time()-$temp_kefu['createtime'];
							if($temp_jg_time>$sitem['jiange']*60)
							{
								$today=strtotime('today');
								$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
								if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
								{
									$send_kefu_tf=true;
								}
							}
						}
						else
						{
							$today=strtotime('today');
							$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
							if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
							{
								$send_kefu_tf=true;
							}
						}

						if($send_kefu_tf==true)
						{
							$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
							$text=urlencode($text2);
							$desc2="点击下方【信箱】按钮查看所有来信";
							$desc=urlencode($desc2);
							$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
							$pic=urlencode($pic2);
							$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
							$url=urlencode($url2);
							$access_token = WeAccount::token();
							$data = array(
							  "touser"=>$kefu_member['from_user'],
							  "msgtype"=>"news",
							  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
							);
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
							load()->func('communication');
							$response = ihttp_request($url, urldecode(json_encode($data)));
							$errcode=json_decode($response['content'],true);
							$data3=array(
									'weid'=>$weid,
									'mid'=>$id,
									'type'=>'news',
									'content'=>$text2,
									'desc'=>$desc2,
									'url'=>$url2,
									'picurl'=>$pic2,
									'status'=>$errcode['errcode'],
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_kefu",$data3);
						}
					}
					exit;
				}
			}
			elseif ($op=='ans2') {
				$id=$_GPC['id'];
				$str=$_GPC['str'];
				if(empty($id))
				{
					echo json_encode(array('status'=>2));
					exit;
				}
				else
				{
					$data=array(
							'weid'=>$weid,
							'mid'=>$id,
							'sendid'=>$mid,
							'content'=>$str,
							'type'=>2,
							'yuedu'=>0,
							'createtime'=>TIMESTAMP,
						);
					pdo_insert("jy_ppp_xinxi",$data);
					echo json_encode(array('status'=>1,'time'=>date('Y-m-d G:i')));
					$kefu_member=pdo_fetch("SELECT type,from_user,nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					$send_member=pdo_fetch("SELECT nicheng FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
					if($kefu_member['type']!=3 && !empty($kefu_member['from_user']))
					{
						$send_kefu_tf=false;
						$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
						if($sitem['jiange']>0)
						{
							$temp_kefu=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." ORDER BY createtime DESC LIMIT 1 ");
							$temp_jg_time=time()-$temp_kefu['createtime'];
							if($temp_jg_time>$sitem['jiange']*60)
							{
								$today=strtotime('today');
								$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
								if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
								{
									$send_kefu_tf=true;
								}
							}
						}
						else
						{
							$today=strtotime('today');
							$temp_kefu_num=pdo_fetchcolumn("SELECT count(id) FROM ".tablename('jy_ppp_kefu')." WHERE weid=".$weid." AND mid=".$id." AND createtime>".$today);
							if(!empty($sitem['shangxian']) && $temp_kefu_num<$sitem['shangxian'])
							{
								$send_kefu_tf=true;
							}
						}

						if($send_kefu_tf==true)
						{
							$text2="附近的'".$send_member['nicheng']."'给你发了一封表白信";
							$text=urlencode($text2);
							$desc2="点击下方【信箱】按钮查看所有来信";
							$desc=urlencode($desc2);
							$pic2=$_W['siteroot']."addons/jy_ppp/images/notice.jpg";
							$pic=urlencode($pic2);
							$url2=$_W['siteroot']."app/".substr($this->createMobileUrl('mail'), 2);
							$url=urlencode($url2);
							$access_token = WeAccount::token();
							$data = array(
							  "touser"=>$kefu_member['from_user'],
							  "msgtype"=>"news",
							  "news"=>array("articles"=>array(0=>(array("title"=>$text,"description"=>$desc,"url"=>$url,'picurl'=>$pic))))
							);
							$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
							load()->func('communication');
							$response = ihttp_request($url, urldecode(json_encode($data)));
							$errcode=json_decode($response['content'],true);
							$data3=array(
									'weid'=>$weid,
									'mid'=>$id,
									'type'=>'news',
									'content'=>$text2,
									'desc'=>$desc2,
									'url'=>$url2,
									'picurl'=>$pic2,
									'status'=>$errcode['errcode'],
									'createtime'=>TIMESTAMP,
								);
							pdo_insert("jy_ppp_kefu",$data3);
						}
					}
					exit;
				}
			}
			elseif ($op=='more') {
				$id=$_GPC['id'];
				$lastid=$_GPC['lastid'];
				if(empty($lastid))
				{
					echo json_encode(array('status'=>3));
					exit;
				}
				$temp=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND id=".$lastid);
				if(empty($temp['createtime']))
				{
					echo json_encode(array('status'=>4));
					exit;
				}
				if(empty($temp['sendid']))
				{
					$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
					if(!empty($sitem['adminthumb']))
					{
						$avatar2=$_W['attachurl'].$sitem['adminthumb'];
					}
					else
					{
						$avatar2="../addons/jy_ppp/images/adminthumb.png";
					}
				}
				else
				{
					$send=pdo_fetch("SELECT sex,avatar FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
					if(!empty($send['avatar']))
					{
						$avatar2=$_W['attachurl'].$send['avatar'];
					}
					else
					{
						if($send['sex']==1)
						{
							$avatar2="../addons/jy_ppp/images/boy.png";
						}
						else
						{
							$avatar2="../addons/jy_ppp/images/girl.png";
						}
					}
				}
				$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND (( mid=".$mid." AND sendid=".$id." ) OR ( mid=".$id." AND sendid=".$mid." ) ) AND createtime<".$temp['createtime']." ORDER BY createtime DESC LIMIT 3");
				sort($list);
				$html='';
				
				if(!empty($member['avatar']))
				{
					$avatar=$_W['attachurl'].$member['avatar'];
				}
				else
				{
					if($member['sex']==1)
					{
						$avatar="../addons/jy_ppp/images/boy.png";
					}
					else
					{
						$avatar="../addons/jy_ppp/images/girl.png";
					}
				}
				if(!empty($list))
				{
					foreach ($list as $key => $l) {
						$html.='<li class="time" data-id="'.$l['id'].'">'.date('Y-m-d G:i',$l['createtime']).'</li>';
						if($l['sendid']==$mid)
						{
							$html.='<li class="me disbox-hor dis_block"><div class="f_right" style="position: relative;"><img src="'.$avatar.'"></div><div class="disbox-flex f_right no_padding"><p class="talk">'.$l['content'].'</p></div><div class="f_right"></div></li>';
						}
						else
						{
							$html.='<li class="you disbox-hor dis_block "><div class="f_left" style="position: relative;"><img src="'.$avatar2.'"></div><div class="disbox-flex f_left no_padding "><p class="talk">'.$l['content'].'</p></div><div class="f_left"></div></li>';
						}
					}
				}

				if(count($list)<3)
				{
					echo json_encode(array('status'=>2,'log'=>$html));
					exit;
				}
				else
				{
					echo json_encode(array('status'=>1,'log'=>$html));
					exit;
				}
			}
			elseif ($op=='next')
			{
				$next=pdo_fetch("SELECT sendid FROM ".tablename('jy_ppp_xinxi')."  WHERE weid=".$weid." AND mid=".$mid." GROUP BY sendid ORDER BY yuedu ASC,createtime DESC LIMIT 1 ");
				if(!empty($next['sendid']))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('chat',array('id'=>$next['sendid']))."';
					</script>";
				}
				else
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('mail')."';
					</script>";
				}
			}
			else
			{
				$id=$_GPC['id'];

				$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
				if(empty($id))
				{
					//管理员
					$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=0 AND type=3 ORDER BY createtime DESC LIMIT 3");
					pdo_update("jy_ppp_xinxi",array('yuedu'=>1,'yuedutime'=>TIMESTAMP),array('weid'=>$weid,'mid'=>$mid,'sendid'=>0,'type'=>3));
					include $this->template('chat');
				}
				else
				{
					$detail=pdo_fetch("SELECT a.*,b.height,b.income,b.marriage FROM ".tablename('jy_ppp_member')." as a left join ".tablename('jy_ppp_basic')." as b on a.id=b.mid  WHERE a.weid=".$weid." AND a.id= ".$id);
					if($member['sex']==1)
					{
						if(empty($member['baoyue']))
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=$member['baoyue']-time();
							if($baoyue<=0)
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=1;
							}
						}
						if(!empty($baoyue))
						{
							$ltlx=2;
						}
						else
						{
							$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$mid." AND chatid=".$id);
							if(empty($temp))
							{
								$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=2 ");
								if(!empty($temp))
								{
									$ltlx=3;
								}
								else
								{
									$today=strtotime('today');
									$temp=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND mid=".$mid." AND sendid=".$id." AND type=0 AND createtime> ".$today);
									if(empty($temp))
									{
										$ltlx=1;
									}
									else
									{
										if(count($temp)>1)
										{
											$ltlx=3;
										}
										else
										{
											$ltlx=1;
										}
									}
								}
							}
							else
							{
								$ltlx=2;
							}
						}
					}
					else
					{
						$duifang=pdo_fetch("SELECT baoyue FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$id);
						if(empty($duifang['baoyue']))
						{
							$baoyue=0;
						}
						else
						{
							$baoyue=$duifang['baoyue']-time();
							if($baoyue<=0)
							{
								$baoyue=0;
							}
							else
							{
								$baoyue=1;
							}
						}
						if(!empty($baoyue))
						{
							$ltlx=2;
						}
						else
						{
							$temp=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_chat_doubi')." WHERE weid=".$weid." AND mid=".$id." AND chatid=".$mid);
							if(!empty($temp))
							{
								$ltlx=2;
							}
							else
							{
								$ltlx=1;
							}
						}
					}

					if(!empty($member['avatar']))
					{
						$avatar=$_W['attachurl'].$member['avatar'];
					}
					else
					{
						if($member['sex']==1)
						{
							$avatar="../addons/jy_ppp/images/boy.png";
						}
						else
						{
							$avatar="../addons/jy_ppp/images/girl.png";
						}
					}

					//1为打招呼,2为写信,3为回复并索要联系方式
					$list=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_xinxi')." WHERE weid=".$weid." AND ( ( mid=".$mid." AND sendid=".$id." ) OR ( mid=".$id." AND sendid=".$mid." ) ) ORDER BY createtime DESC LIMIT 3");
					if($member['sex']==1 && empty($list[0]['type']) && !empty($list[0]['zhaohuid']) && empty($list[0]['huifuid']))
					{
						$zhaohuid=$list[0]['zhaohuid'];
						$wt=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_zhaohu')." WHERE weid=".$weid." AND parentid=".$list[0]['zhaohuid']." AND enabled=1");
					}

					sort($list);
					pdo_update("jy_ppp_xinxi",array('yuedu'=>1,'yuedutime'=>TIMESTAMP),array('weid'=>$weid,'mid'=>$mid,'sendid'=>$id));
					include $this->template('chat');
				}
			}

		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileReport() {
		//
		include_once 'inc/mobile/report.php';
	}
	public function doMobileCz() {
		//
		include_once 'inc/mobile/cz.php';
	}
	public function doMobileXhdoubi() {
		//
		include_once 'inc/mobile/xhdoubi.php';
	}
	public function doMobileVisit() {
		//
		include_once 'inc/mobile/visit.php';
	}
	public function doMobileLianxi() {
		//
		include_once 'inc/mobile/lianxi.php';
	}
	public function doMobileMylove() {
		//
		include_once 'inc/mobile/mylove.php';
	}
	public function doMobileLoveme() {
		//
		include_once 'inc/mobile/loveme.php';
	}
	public function doMobileMyblack() {
		//
		include_once 'inc/mobile/myblack.php';
	}
	public function doMobileSetting() {
		//设置中心
		include_once 'inc/mobile/setting.php';
	}

	public function doMobileAccount() {
		//
		include_once 'inc/mobile/account.php';
	}

	public function doMobileFeedback() {
		//
		include_once 'inc/mobile/feedback.php';
	}
	public function doMobileFdbk_s() {
		//
		include_once 'inc/mobile/fdbk_s.php';
	}
	public function doMobileBeizhu() {
		//
		include_once 'inc/mobile/beizhu.php';
	}
	public function doMobileMobile() {
		//
		include_once 'inc/mobile/mobile.php';
	}
	public function doMobileIdcard() {
		//
		include_once 'inc/mobile/idcard.php';
	}
	public function doMobileRenzheng() {
		//
		include_once 'inc/mobile/renzheng.php';
	}
	public function doMobileUpload() {
		//
		global $_W,$_GPC;
		$xuniid=intval($_GPC['xuniid']);
		if(empty($xuniid))
		{
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
				$weixin=0;

				$weid=$_GPC['i'];

				$mid=$_SESSION['mid'];
				if(!empty($mid))
				{
					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
				}
			}
			else
			{
				$weixin=1;

				$weid=$_W['uniacid'];
				//checkAuth();
				$from_user=$_SESSION['jy_openid'];
				if(empty($from_user))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'upload'))."';
					</script>";
				}
				else
				{
					$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
					if(empty($member_temp['nickname']) || $member_temp['uid']==0)
					{
						unset($uid);
					}
					else
					{
						$uid=$member_temp['uid'];
						unset($member_temp);
					}
					if(empty($uid))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'upload'))."';
						</script>";
					}
					else
					{
						$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
						$mid=$member['id'];
					}
				}
			}
		}
		else
		{
			if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
				$weixin=0;

				$weid=$_GPC['i'];

				$dyid=$_SESSION['dyid'];
				if(!empty($dyid))
				{
					$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND id=".$dyid);
				}
			}
			else
			{
				$weixin=1;

				$weid=$_W['uniacid'];
				//checkAuth();
				$from_user=$_SESSION['jy_openid'];
				if(empty($from_user))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'upload'))."';
					</script>";
				}
				else
				{
					$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
					if(empty($member_temp['nickname']) || $member_temp['uid']==0)
					{
						unset($uid);
					}
					else
					{
						$uid=$member_temp['uid'];
						unset($member_temp);
					}
					if(empty($uid))
					{
						echo "<script>
							window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'upload'))."';
						</script>";
					}
					else
					{
						$dianyuan=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_dianyuan')." WHERE weid=".$weid." AND from_user='".$from_user."' AND status=1");
						$dyid=$dianyuan['id'];
					}
				}
			}
			if(empty($dianyuan))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('dy_login')."';
				</script>";
			}
			else
			{
				$xuni_qx=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_xuni_member')." WHERE weid=".$weid." AND mid=".$xuniid." AND dyid=".$dyid);
				if(empty($xuni_qx))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('dy_user')."';
					</script>";
				}
				else
				{
					$mid=$xuniid;

					$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid);
				}
			}
		}

		if(!empty($member))
		{
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			if ($_POST ['status']) {

				echo "SUCCESS";
				echo $_POST ['filedata'];
				load ()->func ( 'file' );
				// 获取图片扩展名及图片data
				preg_match ( "/data:image\/(.*?);base64/", $_POST ['filedata'], $res );
				$ext = $res [1];
				echo $ext;
				$data = preg_replace ( "/data:image\/(.*);base64,/", "", $_POST ['filedata'] );
				echo $data;
				// 生成最终文件名
				$uniacid = intval ( $_W ['uniacid'] );
				$path = "images/{$uniacid}/" . date ( 'Y/m/' );
				// 生成目标文件夹－－！from func('file')
				mkdirs ( ATTACHMENT_ROOT . '/' . $path );
				// 生成目标文件名－－！from func('file')
				$filename = file_random_name ( ATTACHMENT_ROOT . '/' . $path, $ext );
				file_put_contents ( ATTACHMENT_ROOT . '/' . $path . $filename, base64_decode ( $data ) );
				$result['path'] = $path . $filename;
				pdo_insert('jy_ppp_thumb', array ('weid' => $weid,'mid' => $mid,'thumb' => $result ['path'],'type'=>0,'createtime'=>TIMESTAMP));
				$thumb_id=pdo_insertid();
				$href=$_W['siteroot'].'app/'.substr($this->createMobileUrl('mythumb',array('id'=>$thumb_id)),2);
				echo "<script>parent.iframecallback('".$_W['attachurl'].$path.$filename."','".$href."')</script>";
				exit ();
			}
			else
			{
				$thumb=pdo_fetchall("SELECT * FROM ".tablename('jy_ppp_thumb')." WHERE weid=".$weid." AND mid=".$mid." AND type!=4 ORDER BY id DESC ");
			}
			include $this->template('upload');
		}
		else
		{
			if($weixin==1)
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('zhuce')."';
					</script>";
			}
			else
			{
				echo "<script>
						window.location.href = '".$this->createMobileUrl('login')."';
					</script>";
			}
		}
	}
	public function doMobileMythumb() {
		//
		include_once 'inc/mobile/mythumb.php';
	}
	public function doMobileTp_notice() {
		//首页
		include_once 'inc/mobile/tp_notice.php';
	}
	public function doMobileBasic() {
		//
		include_once 'inc/mobile/basic.php';
	}
	public function doMobileDesc() {
		//
		include_once 'inc/mobile/desc.php';
	}
	public function doMobileMatch() {
		//
		include_once 'inc/mobile/match.php';
	}
	public function doMobileMianrao() {
		//
		include_once 'inc/mobile/mianrao.php';
	}
	public function doMobileVip() {
		//
		include_once 'inc/mobile/vip.php';
	}
	public function doMobileDoubi() {
		//
		include_once 'inc/mobile/doubi.php';
	}
	public function doMobileBaoyue() {
		//
		include_once 'inc/mobile/baoyue.php';
	}
	public function doMobileShouxin() {
		//
		include_once 'inc/mobile/shouxin.php';
	}
	public function doMobileBind() {
		//
		global $_W,$_GPC;
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false ) {
			$weixin=0;

			$weid=$_GPC['i'];

			$mid=$_SESSION['mid'];
			if(!empty($mid))
			{
				$member=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_member')." WHERE weid=".$weid." AND id=".$mid." AND status=1");
			}
		}
		else
		{
			$weixin=1;

			$weid=$_W['uniacid'];
			//checkAuth();
			$from_user=$_SESSION['jy_openid'];
			if(empty($from_user))
			{
				echo "<script>
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'bind','id'=>$_GPC['id']))."';
				</script>";
			}
			else
			{
				$member_temp=pdo_fetch("SELECT uid,nickname,follow FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
				if(empty($member_temp['nickname']) || $member_temp['uid']==0)
				{
					unset($uid);
				}
				else
				{
					$uid=$member_temp['uid'];
					unset($member_temp);
				}
				if(empty($uid))
				{
					echo "<script>
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'bind','id'=>$_GPC['id']))."';
					</script>";
				}
			}
		}

		$id=$_GPC['id'];

		$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
		if($weixin==1 && !empty($from_user) && !empty($uid) && !empty($id))
		{
			pdo_update("jy_ppp_member",array('status'=>0,'uid'=>0,'from_user'=>''),array('from_user'=>$from_user));
			pdo_update("jy_ppp_member",array('status'=>1,'uid'=>$uid,'from_user'=>$from_user),array('id'=>$id));
			include $this->template("bind");
		}
		else
		{
			include $this->template("bind2");
		}
	}
	public function doMobilePcpay() {
		//
		include_once 'inc/mobile/pcpay.php';
	}
	public function doMobilePay_c() {
		//
		include_once 'inc/mobile/pay_c.php';

	}
	public function doMobilePay() {
		//
		include_once 'inc/pay.php';
	}

	public function payResult($params) {
		include_once 'inc/pay2.php';
	}
	public function doMobileZhuce() {
		//首页
		include_once 'inc/mobile/zhuce.php';
	}
	public function doMobileZhuce2() {
		//首页
		include_once 'inc/mobile/zhuce2.php';
	}
	public function doMobileLogin() {
		//登陆页
		include_once 'inc/mobile/login.php';
	}
	public function doMobileLogout() {
		//首页
		include_once 'inc/mobile/logout.php';
	}
	public function doMobileRule() {
		//首页
		include_once 'inc/mobile/rule.php';
	}
	public function doMobileForget() {
		//首页
		include_once 'inc/mobile/forget.php';
	}
	public function doMobileHelp() {
		//帮助中心
		include_once 'inc/mobile/help.php';
	}
	public function doMobileHelp_l() {
		//帮助中心分类列表页
		include_once 'inc/mobile/help_l.php';
	}
	public function doMobileHelp_d() {
		//帮助中心文章页
		include_once 'inc/mobile/help_d.php';
	}
	public function doMobileSafe() {
		//安全中心手机端
		include_once 'inc/mobile/safe.php';
	}
	public function doMobileSafe_l() {
		//安全中心分类列表页
		include_once 'inc/mobile/safe_l.php';
	}
	public function doMobileSafe_d() {
		//安全中心分类列表页
		include_once 'inc/mobile/safe_d.php';
	}
	public function doMobileDy_login() {
		//店员登陆页
		include_once 'inc/mobile/dy_login.php';
	}
	public function doMobileDycenter() {
		//店员管理中心页
		include_once 'inc/mobile/dycenter.php';
	}
	public function doMobileDy_user() {
		//店员管理中心页
		include_once 'inc/mobile/dy_user.php';
	}
	public function doMobileDy_msg() {
		//店员信息中心页
		include_once 'inc/mobile/dy_msg.php';
	}
	public function doMobileDy_chat() {
		//店员聊天页
		include_once 'inc/mobile/dy_chat.php';
	}
	public function doMobileDy_add() {
		//新增店员页
		include_once 'inc/mobile/dy_add.php';
	}
	public function doMobileDy_man() {
		//新增店员页
		include_once 'inc/mobile/dy_man.php';
	}
	public function doWebSetting2() {
		$sql = "

			CREATE TABLE IF NOT EXISTS `ims_jy_ppp_login_log` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `weid` int(10) unsigned NOT NULL,
			  `mid` int(10) NOT NULL,
			  `createtime` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

			CREATE TABLE IF NOT EXISTS `ims_jy_ppp_dianyuan` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `weid` int(10) unsigned NOT NULL,
			  `from_user` varchar(50) NOT NULL DEFAULT '',
			  `uid` int(10) NOT NULL,
			  `status` int(10) unsigned NOT NULL DEFAULT 1,
			  `username` varchar(50) NOT NULL DEFAULT '',
			  `mobile` varchar(20) NULL,
			  `mail` varchar(200) NULL,
			  `QQ` varchar(200) NULL,
			  `wechat` varchar(200) NULL,
			  `password` varchar(200) NULL,
			  `description` text,
			  `createtime` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

			CREATE TABLE IF NOT EXISTS `ims_jy_ppp_xuni_member` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `weid` int(10) unsigned NOT NULL,
			  `mid` int(10) NOT NULL,
			  `dyid` int(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

			CREATE TABLE IF NOT EXISTS `ims_jy_ppp_xunithumb` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`mid` int(11)  NOT NULL,
				`sex` int(2) NOT NULL COMMENT '1为男,2为女',
				`avatar` int(2) NOT NULL DEFAULT 0 COMMENT '1为头像,0为普通',
				`thumb` text,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

			CREATE TABLE IF NOT EXISTS `ims_jy_ppp_price` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`weid` int(11)  NOT NULL,
				`displayorder` int(11)  NOT NULL DEFAULT '0' COMMENT 'ForOrder',
				`fee` int(10) NOT NULL,
				`credit` int(10) NOT NULL,
				`baoyue` int(10) NOT NULL,
				`shouxin` int(10) NOT NULL,
			  	`log` int(10) NOT NULL COMMENT '1为购买豆币,2为购买包月服务,3为购买收信宝',
				`description` varchar(255) NOT NULL,
				`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

		";
		pdo_query($sql);

		if(!pdo_fieldexists('jy_ppp_setting', 'address')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `address` int(10) DEFAULT '0';");
		}
		if(!pdo_fieldexists('jy_ppp_setting', 'province')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `province` int(10) DEFAULT '11';");
		}
		if(!pdo_fieldexists('jy_ppp_setting', 'city')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_setting')." ADD `city` int(10) DEFAULT '1101';");
		}
		if(!pdo_indexexists('jy_ppp_member', 'mobile')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_member')." ADD INDEX (mobile);");
		}
		if(!pdo_indexexists('jy_ppp_member', 'province')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_member')." ADD INDEX (province);");
		}
		if(!pdo_indexexists('jy_ppp_member', 'sex')) {
			pdo_query("ALTER TABLE ".tablename('jy_ppp_member')." ADD INDEX (sex);");
		}
		echo 2;

	}
	public function doWebSetting() {
		//基本设置
		include_once 'inc/web/setting.php';
	}
	public function doWebHelp() {
		//帮助中心
		include_once 'inc/web/help.php';
	}
	public function doWebSafe() {
		//这个操作被定义用来呈现 管理中心导航菜单
		include_once 'inc/web/safe.php';
	}
	public function doWebFeedback() {
		//需求管理
		include_once 'inc/web/feedback.php';
	}
	public function doWebThumb() {
		//需求管理
		include_once 'inc/web/thumb.php';
	}
	public function doWebXunithumb() {
		//需求管理
		include_once 'inc/web/xunithumb.php';
	}
	public function doWebZhaohu() {
		//帮助中心
		include_once 'inc/web/zhaohu.php';
	}
	public function doWebFenpei() {
		//帮助中心
		include_once 'inc/web/fenpei.php';
	}
	public function doWebDianyuan()
	{
		include_once 'inc/web/dianyuan.php';
	}
	public function doMobileDybind()
	{
		include_once 'inc/mobile/dybind.php';
	}
	public function doWebUnbind()
	{
		include_once 'inc/web/unbind.php';
	}
	public function doWebXuni() {
		//基本设置
		include_once 'inc/web/xuni.php';
	}
	public function doWebMember() {
		//基本设置
		include_once 'inc/web/member.php';
	}
	public function doWebPrice() {
		//基本设置
		include_once 'inc/web/price.php';
	}
	public function doWebCaiwu() {
		//这个操作被定义用来呈现 管理中心导航菜单
		include_once 'inc/web/caiwu.php';
	}
	public function doWebStat() {
		//这个操作被定义用来呈现 管理中心导航菜单
		include_once 'inc/web/stat.php';
	}
	public function doMobileUserinfo() {
		global $_W,$_GPC;

			if (!empty($_W['openid']) && intval($_W['account']['level']) > 3) {
				$accObj = WeiXinAccount::create($_W['account']);
				$userinfo = $accObj->fansQueryInfo($_W['openid']);

			}

			// if(empty($_W['oauth_account'])){
			// 	return error(-1, '未指定网页授权公众号, 无法获取用户信息.');
			// }
			// if(empty($_W['oauth_account']['key']) || empty($_W['oauth_account']['secret'])){
			// 	return error(-2, '公众号未设置 appId 或 secret.');
			// }
			// if(intval($_W['oauth_account']['level']) < 4){
			// 	return error(-3, '公众号非认证服务号, 无法获取用户信息.');
			// }


			// $state = 'weihezisid-'.$_W['session_id'];

			// $_SESSION['dest_url'] = base64_encode($_SERVER['QUERY_STRING']);

			$op=$_GPC['op'];

			$code = $_GET['code'];

			if(empty($code)){
				if($userinfo['subscribe']==0)
				{
					$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('userinfo',array('op'=>$op,'id'=>$_GPC['id'],'foo'=>$_GPC['foo'],'fdbk'=>$_GPC['fdbk']));
					$callback = urlencode($url);
					$forward = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_W['oauth_account']['key'].'&redirect_uri='.$callback.'&response_type=code&scope=snsapi_userinfo&state=jiuye#wechat_redirect';

					header("Location: ".$forward);
				}
				else
				{
					//用户已经关注改公众号了
					$weid=$_W['uniacid'];
					$from_user=$_W['openid'];
					$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);


					if(!empty($userinfo) && !empty($userinfo['nickname']))
					{
						$userinfo['avatar'] = $userinfo['headimgurl'];
						unset($userinfo['headimgurl']);

						//
						$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
						$_SESSION['openid'] = $from_user;
						$_SESSION['jy_openid'] = $from_user;


						//开启了强制注册，自定义注册
						$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));

						$data = array(
							'uniacid' => $weid,
							'salt' => random(8),
							'groupid' => $default_groupid,
							'createtime' => TIMESTAMP,
							'nickname' 		=> stripslashes($userinfo['nickname']),
							'avatar' 		=> $userinfo['avatar'],
							'gender' 		=> $userinfo['sex'],
							'nationality' 	=> $userinfo['country'],
							'resideprovince'=> $userinfo['province'] . '省',
							'residecity' 	=> $userinfo['city'] . '市',
						);
						$data['password'] = md5($_W['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);

						if(empty($fan_temp))
						{
							pdo_insert('mc_members', $data);
							$uid = pdo_insertid();
						}
						else
						{
							pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
							$uid=$fan_temp['uid'];
						}



						$record = array(
							'openid' 		=> $_W['openid'],
							'uid' 			=> $uid,
							'acid' 			=> $_W['acid'],
							'uniacid' 		=> $weid,
							'salt' 			=> random(8),
							'updatetime' 	=> TIMESTAMP,
							'nickname' 		=> $userinfo['nickname'],
							'follow' 		=> $userinfo['subscribe'],
							'followtime' 	=> $userinfo['subscribe_time'],
							'unfollowtime' 	=> 0,
							'tag' 			=> base64_encode(iserializer($userinfo))
						);
						$record['uid'] = $uid;
						if(empty($fan_temp))
						{
							pdo_insert('mc_mapping_fans', $record);
						}
						else
						{
							pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
						}

					}
				}
			}
			else
			{
				//未关注，通过网页授权
				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$_W['oauth_account']['key']."&secret=".$_W['oauth_account']['secret']."&code=".$code."&grant_type=authorization_code";
				load()->func('communication');
				$response = ihttp_get($url);
				$oauth = @json_decode($response['content'], true);

				$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$oauth['access_token']}&openid={$oauth['openid']}&lang=zh_CN";
				$response = ihttp_get($url);


				if (!is_error($response)) {

					$userinfo = array();
					$userinfo = @json_decode($response['content'], true);

					$userinfo['avatar'] = $userinfo['headimgurl'];
					unset($userinfo['headimgurl']);

					$_SESSION['userinfo'] = base64_encode(iserializer($userinfo));
					$_SESSION['openid'] = $oauth['openid'];
					$_SESSION['jy_openid'] = $oauth['openid'];
					$from_user=$oauth['openid'];

						if(!empty($userinfo) && !empty($userinfo['nickname']))
						{
							$acid=$_W['oauth_account']['acid'];
							$weid=$_W['uniacid'];
							// if($acid==$_W['account']['acid'])
							// {
							// 	$weid=$_W['account']['uniacid'];
							// }
							// else
							// {
							// 	$temp_uniacid=pdo_fetch("SELECT uniacid FROM ".tablename('account')." WHERE acid=".$acid);
							// 	$weid = $temp_uniacid['uniacid'];
							// }

							$fan_temp=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid='$from_user' AND uniacid=".$weid);
							//开启了强制注册，自定义注册
							$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $weid));
							$data = array(
								'uniacid' => $weid,
								'salt' => random(8),
								'groupid' => $default_groupid,
								'createtime' => TIMESTAMP,
								'nickname' 		=> stripslashes($userinfo['nickname']),
								'avatar' 		=> rtrim($userinfo['avatar'], '0') . 132,
								'gender' 		=> $userinfo['sex'],
								'nationality' 	=> $userinfo['country'],
								'resideprovince'=> $userinfo['province'] . '省',
								'residecity' 	=> $userinfo['city'] . '市',
							);
							$data['password'] = md5($oauth['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);

							if(empty($fan_temp))
							{
								pdo_insert('mc_members', $data);
								$uid = pdo_insertid();
							}
							else
							{
								pdo_update('mc_members' ,$data ,array('uid'=>$fan_temp['uid']));
								$uid=$fan_temp['uid'];
							}

							$record = array(
								'openid' 		=> $oauth['openid'],
								'uid' 			=> $uid,
								'acid' 			=> $acid,
								'uniacid' 		=> $weid,
								'salt' 			=> random(8),
								'updatetime' 	=> TIMESTAMP,
								'nickname' 		=> $userinfo['nickname'],
								'follow' 		=> $userinfo['subscribe'],
								'followtime' 	=> $userinfo['subscribe_time'],
								'unfollowtime' 	=> 0,
								'tag' 			=> base64_encode(iserializer($userinfo))
							);
							$record['uid'] = $uid;

							if(empty($fan_temp))
							{
								pdo_insert('mc_mapping_fans', $record);
							}
							else
							{
								$temp=pdo_update('mc_mapping_fans' ,$record ,array('fanid'=>$fan_temp['fanid']));
							}

						}

				} else {
					message('微信授权获取用户信息失败,请重新尝试: ' . $response['message']);
				}
			}


		echo "<script>
					window.location.href = '".$this->createMobileUrl($op,array('id'=>$_GPC['id'],'foo'=>$_GPC['foo'],'fdbk'=>$_GPC['fdbk']))."';
				</script>";
	}

}