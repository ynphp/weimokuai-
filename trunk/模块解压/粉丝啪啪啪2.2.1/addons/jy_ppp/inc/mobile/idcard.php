<?php
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
					window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'idcard'))."';
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
						window.location.href = '".$this->createMobileUrl('userinfo',array('op'=>'idcard'))."';
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
			$sitem=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_setting')." WHERE weid=".$weid);
			$idcard=pdo_fetch("SELECT * FROM ".tablename('jy_ppp_idcard')." WHERE weid=".$weid." AND mid=".$mid);
			if(!empty($idcard))
			{
				$idno_tou=substr($idcard['idcard'], 0,4);
				$idno_wei=substr($idcard['idcard'], -4);
			}
			$op=$_GPC['op'];
			if($op=='add')
			{
				if(empty($sitem['idcard']))
				{
					$sitem['idcard']=60;
				}
				if($member['sex']==1 && $member['credit']<$sitem['idcard'])
				{
					echo json_encode(array('status'=>-1));
					exit;
				}else
				{

					$num=$_GPC['cardNo'];
					if(strlen($num) != 15 && strlen($num) != 18){
			            return false;
			        }

			        // 是数值
			        if(is_numeric($num)){
		                // 如果是15位身份证号
			            if(strlen($num) == 15 ){
			                // 省市县（6位）
			                $areaNum = substr($num,0,6);
			                // 出生年月（6位）
			                $dateNum = substr($num,6,6);
			                // 性别（3位）
			                $sexNum = substr($num,12,3);
			            }else{
			            // 如果是18位身份证号
			                // 省市县（6位）
			                $areaNum = substr($num,0,6);
			                // 出生年月（8位）
			                $dateNum = substr($num,6,8);
			                // 性别（3位）
			                $sexNum = substr($num,14,3);
			                // 校验码（1位）
			                $endNum = substr($num,17,1);
			            }
			        }else{
			        // 不是数值
			            if(strlen($num) == 15){
			                return false;
			            }else{
			                // 验证前17位为数值，且18位为字符x
			                $check17 = substr($num,0,17);
			                if(!is_numeric($check17)){
			                    return false;
			                }
			                // 省市县（6位）
			                $areaNum = substr($num,0,6);
			                // 出生年月（8位）
			                $dateNum = substr($num,6,8);
			                // 性别（3位）
			                $sexNum = substr($num,14,3);
			                // 校验码（1位）
			                $endNum = substr($num,17,1);
			                if($endNum != 'x' && $endNum != 'X'){
			                    return false;
			                }
			            }
			        }

			        $num1 = substr($areaNum,0,2);
			        $num2 = substr($areaNum,0,4);
			        // 根据GB/T2260—999，省市代码11到65
			        if(10 < $num1 && $num1 < 66){
			        }else{
			            return false;
			        }

			        $date=$dateNum;
			        if(strlen($date) == 6){
			        	$date1 = substr($date,0,2);
			            $date2 = substr($date,2,2);
			            $date3 = substr($date,4,2);
			            $date1 = '19'.$date1;
			        }
			        else
			        {
			        	$date1 = substr($date,0,4);
			            $date2 = substr($date,4,2);
			            $date3 = substr($date,6,2);
			        }
		            $nowY = date("Y",time());
		            if(1900 < $date1 && $date1 <= $nowY){
		                if(getType($date1) == 'string'){
				            $date1 = (int)$date1;
				        }
				        if($Y % 100 == 0){
				            if($Y % 400 == 0){
				                $statusY=1;
				            }else{
				                $statusY=0;
				            }
				        }else if($Y % 4 ==  0){
				            $statusY=1;
				        }else{
				            $statusY=0;
				        }
		            }else{
		                return false;
		            }

		            if(0<$date2 && $date2 <13){
			            if($date2 == 2){
			                // 润年
			                if($statusY){
			                    if(0 < $date3 && $date3 <= 29){
			                    }else{
			                        return false;
			                    }
			                }else{
			                // 平年
			                    if(0 < $date3 && $date3 <= 28){
			                    }else{
			                        return false;
			                    }
			                }
			            }else{
			            	if($date2 == 1 || $date2 == 3 || $date2 == 5 || $date2 == 7 || $date2 == 8 || $date2 == 10 || $date2 == 12){
					            $maxDateNum = 31;
					        }else if($month == 2){
					        }else{
					            $maxDateNum = 30;
					        }
			                if(0<$date3 && $date3 <=$maxDateNum){
			                }else{
			                    return false;
			                }
			            }
			        }else{
			            return false;
			        }

			        $brith= strtotime($dateNum);
			        $birthday= date('Y年m月d日',$brith);
			        if($sexNum % 2 == 0){
			        	$sex=2;
			        }
			        else
			        {
			        	$sex=1;
			        }

			        if($sex!=$member['sex'])
			        {
			        	return false;
			        }

			        $checkHou = array(1,0,'x',9,8,7,6,5,4,3,2);
			        $checkGu = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
			        $sum = 0;
			        for($i = 0;$i < 17; $i++){
			            $sum += (int)$checkGu[$i] * (int)$num[$i];
			        }
			        $checkHouParameter= $sum % 11;
			        if($checkHou[$checkHouParameter] != $num[17]){
			            return false;
			        }

					$data=array(
							'weid'=>$weid,
							'mid'=>$mid,
							'idcard'=>$num,
							'sex'=>$sex,
							'province'=>$num1,
							'city'=>$num2,
							'brith'=>$brith,
							'realname'=>$_GPC['realname'],
							'createtime'=>TIMESTAMP,
						);

					if(!empty($idcard))
					{
						pdo_update("jy_ppp_idcard",$data,array('id'=>$idcard['id']));
					}
					else
					{

						pdo_insert("jy_ppp_idcard",$data);
					}
					if($member['sex']==1)
					{
						pdo_update("jy_ppp_member",array('credit'=>$member['credit']-$sitem['idcard'],'card_auth'=>1),array('id'=>$mid));
						$data2=array(
								'weid'=>$weid,
								'mid'=>$mid,
								'credit'=>$sitem['idcard'],
								'type'=>'reduce',
								'log'=>1,
								'logid'=>0,
								'createtime'=>TIMESTAMP,
							);
						pdo_insert("jy_ppp_credit_log",$data2);
					}
					$xinxi="恭喜你,您已进行实名认证-身份证认证。祝您尽快找到您的有缘人!";
					$data4=array(
						'weid'=>$weid,
						'mid'=>$mid,
						'sendid'=>0,
						'content'=>$xinxi,
						'type'=>3,
						'yuedu'=>0,
						'createtime'=>TIMESTAMP,
					);
					pdo_insert("jy_ppp_xinxi",$data4);
					echo json_encode(array('status'=>1,'needFee'=>1,'name'=>$_GPC['realname'],'sex'=>$sex,'cardNo'=>$_GPC['cardNo'],'birthday'=>$birthday,'province'=>$num1,'city'=>$num2));
					exit;
				}
			}
			include $this->template('idcard');
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