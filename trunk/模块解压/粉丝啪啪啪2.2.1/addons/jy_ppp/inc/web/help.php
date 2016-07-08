<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	    if ($operation == 'display') {
	        if (!empty($_GPC['displayorder'])) {
	            foreach ($_GPC['displayorder'] as $id => $displayorder) {
	                pdo_update('jy_ppp_help', array('displayorder' => $displayorder), array('id' => $id));
	            }
	            message('帮助中心更新成功！', $this->createWebUrl('help', array('op' => 'display')), 'success');
	        }
	        $children = array();
	        $category = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_help') . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC");
	        foreach ($category as $index => $row) {
	            if (!empty($row['parentid'])) {
	                $children[$row['parentid']][] = $row;
	                unset($category[$index]);
	            }
	        }
	        if (checksubmit('submit2')) {
	        	$last=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_help')." ORDER BY id DESC LIMIT 1 ");
	        	$lastid=$last['id'];
	        	$sql = "
	        		INSERT INTO ".tablename('jy_ppp_help')." (`id`, `weid`, `name`, `parentid`, `displayorder`, `description`, `enabled`) VALUES
					(".($lastid+1).",".$weid.",	'服务充值',	0,	0,	'',	1),
					(".($lastid+2).",".$weid.",	'账号密码',	0,	0,	'',	1),
					(".($lastid+3).",".$weid.",	'照片问题',	0,	0,	'',	1),
					(".($lastid+4).",".$weid.",	'活动咨询',	0,	0,	'',	1),
					(".($lastid+5).",".$weid.",	'聊天相关',	0,	0,	'',	1),
					(".($lastid+6).",".$weid.",	'投诉举报',	0,	0,	'',	1),
					(".($lastid+7).",".$weid.",	'其他常见',	0,	0,	'',	1),
					(".($lastid+9).",".$weid.",	'银行卡充值已经准确的填写了相关信息，还是失败怎么办?',	".($lastid+1).",	0,	'您选择的银行卡手机支付,失败原因可能有多种：1.您的银行卡号,身份证号、姓名等信息输入有误；2.手机状态异常，未能顺利完成银联支付系统的电话语音确认；3.您的银行卡暂不被支持；如信息正确的情况下，尝试3次依然失败，请更换其他银行卡重试或购买全国通用的移动或联通充值卡在页面上进行充值',	1),
					(".($lastid+10).",".$weid.",	'如何办理聊天的业务呢？',	".($lastid+1).",	0,	'您好，请您登陆后，进入【我】点击【豆币账户】或【写信包月】选择需要的服务，进入充值界面。我们提供银行卡，充值卡，支付宝，汇款转账等方式充值。请根据页面提示操作。',	1),
					(".($lastid+11).",".$weid.",	'和异性聊天，豆币是如何扣除的？',	".($lastid+1).",	0,	'您好，豆币是按人数收费的，即花费20豆币可以和一位女用户终身免费聊天。',	1),
					(".($lastid+12).",".$weid.",	'可以用两张50元的充值卡充值100元的业务吗？',	".($lastid+1).",	0,	'您好，不可以，我们网站不支持合并充值，请您根据服务选择相应面额充值卡进行充值。',	1),
					(".($lastid+13).",".$weid.",	'充值卡充值失败怎么办?',	".($lastid+1).",	0,	'请确认您的充值卡是全国通用的移动或者联通充值卡，如果提示您卡密已失效还未获得服务，请拨打易宝客服电话400-150-0800撤销订单，重新充值。',	1),
					(".($lastid+14).",".$weid.",	'红娘服务是什么？',	".($lastid+1).",	0,	'为了帮助女用户找到符合自己要求的异性，提供了红娘服务。红娘服务内容包括：优先将红娘会员推荐给优质男性用户，获得更多交流机会；红娘将帮助申请红娘服务的用户向符合其征友要求的异性询问交友意向；实时监控询问总数。',	1),
					(".($lastid+15).",".$weid.",	'如何申请红娘服务？',	".($lastid+1).",	0,	'女性用户，进入【我】—【设置中心】模块，点击【红娘设置】直接按提示申请即可。',	1),
					(".($lastid+16).",".$weid.",	'如何取消红娘服务？',	".($lastid+1).",	0,	'进入【我】—【设置中心】模块，点击【有缘设置】退订服务即可取消该功能。',	1),
					(".($lastid+17).",".$weid.",	'自动续费是什么，如何办理？',	".($lastid+1).",	0,	'自动续费是主动签约服务,支付时选择自动续费,服务到期/豆币余额不足时，将自动续费,自动续费同时赠送服务到期提醒/豆币余额提醒服务，到期前/豆币不足100时会给您发短信和站内信提醒。',	1),
					(".($lastid+18).",".$weid.",	'收到心仪女生的回复，怎样才能给她写信？',	".($lastid+1).",	0,	'使用豆币和包月写信服务，可以给对方写信，请先购买豆币或升级成为写信包月会员。',	1),
					(".($lastid+19).",".$weid.",	'购买的充值卡不是你们上面显示那些位数是怎么回事？',	".($lastid+1).",	0,	'您好，全国通用移动充值卡序列号是17位密码是18位，全国通用联通充值卡序列号15位，密码19位，如果您的位数不对，可能是地方卡，我们不支持地方充值卡充值。',	1),
					(".($lastid+20).",".$weid.",	'如何确认我充值是否成功及服务期限？',	".($lastid+1).",	0,	'您好，每次充值管理员会发出充值成功与否的信息，请您注意查看您的信箱。如果您没有看到信息在您充值那个服务的页面会显示您充值后的信息及服务时间，豆币余额。',	1),
					(".($lastid+21).",".$weid.",	'银行卡支付时，卡号和身份证需要是同一个人吗？',	".($lastid+1).",	0,	'是的，银行卡充值卡号和身份证姓名必须一致，否则支付不会成功。',	1),
					(".($lastid+22).",".$weid.",	'手机号需要和开户时候预留的手机号码一致吗',	".($lastid+1).",	0,	'建议最好和银行预留手机号码一致，如果忘记号码，请咨询银行修改。',	1),
					(".($lastid+24).",".$weid.",	'以前注册手机号码不用了能否将账号更改为现在手机号码？',	".($lastid+2).",	0,	'您好，账号已经注册不能更改，但是不影响您的正常使用，请您牢记您的账号密码。',	1),
					(".($lastid+25).",".$weid.",	'如何修改密码？',	".($lastid+2).",	0,	'触屏版用户进入【我】—【设置中心】—【账号管理】，点击【修改密码】',	1),
					(".($lastid+26).",".$weid.",	'如何上传相片？',	".($lastid+3).",	0,	'您好，请您点击【我】-进入我的相册中点击上传相片，选择您要上传的照片直接上传即可，上传之后，我们的客服人员会对您的照片进行审核，审核通过后会在相册中看到照片。',	1),
					(".($lastid+27).",".$weid.",	'我的照片显示待审核状态怎么回事？',	".($lastid+3).",	0,	'您好，我们是一个正规的交友网站，上传的照片都是需要审核的，确保您的照片文明合理，五官清晰，24小时之后就会通过审核，所以请您耐心等待。',	1),
					(".($lastid+28).",".$weid.",	'什么情况下照片只能显示在相册，而不能作为头像？',	".($lastid+3).",	0,	'您好，当您上传的照片过明或者过暗，只能看到头部不能看到一点肩部，头部在图片中过小以至看不清楚五官，五官被遮挡一部分，戴墨镜或者抽烟的都不能作为形象照，只能显示在您的相册中。',	1),
					(".($lastid+29).",".$weid.",	'如何更换头像？',	".($lastid+3).",	0,	'您好，请您进入【我】-【我的相册】-查看相册，确定您要更换头像的照片，点击照片下的设形象照即可。',	1),
					(".($lastid+30).",".$weid.",	'我的照片合格，但是没有通过审核，我要申诉',	".($lastid+3).",	0,	'请您点击“没有解决，进入人工咨询”，输入详细情况，我们的工作人员会在一个工作日内回复您，请登陆信箱中查看管理员来信。',	1),
					(".($lastid+31).",".$weid.",	'我怎么看不到我上传的照片了，什么情况下照片会被删除？',	".($lastid+3).",	0,	'您好，当您上传的照片是色情的，非本人，裸露，闪图，带有联系方式的，年龄不符，看不到五官，合影，侧脸严重的都会被删除，所以建议您上传文明合理，五官清晰的个人照片。',	1),
					(".($lastid+32).",".$weid.",	'如何删除已经上传的照片？',	".($lastid+3).",	0,	'您好，请您进入【我】-【我的相册】-查看相册，点击照片下面的删除，确定即可。',	1),
					(".($lastid+33).",".$weid.",	'话费领取了怎么还没有到账？',	".($lastid+4).",	0,	'您好，话费领取之后，48小时后可查询到账话费',	1),
					(".($lastid+34).",".$weid.",	'如何增加异性来信？',	".($lastid+5).",	0,	'您好，您可以多向异性用户打招呼，或者完善您的资料，提高您的诚信度，在空间-诚信里边验证您的手机号和身份证号（确保安全）这样会增加女会员对您的信任度。或者您可以在服务中办理爱情直通车，您的信息会优先被女会员搜索到，增多来信。',	1),
					(".($lastid+35).",".$weid.",	'为什么提示系统禁言，并且发不了信？',	".($lastid+5).",	0,	'您好，你的信息包含本站的非法词汇，所以禁言48小时，请您耐心等待禁言时间过后，账号就能够正常使用了,谢谢！',	1),
					(".($lastid+36).",".$weid.",	'有人违反了相关规定，我要揭发TA怎么做？',	".($lastid+6).",	0,	'您好.，针对用户的投诉请您在对方空间最下方点击“举报”并说明理由，会有专人审核处理。',	1),
					(".($lastid+37).",".$weid.",	'如何才能索要到对方联系方式？',	".($lastid+7).",	0,	' 您好，建议您在和对方聊天过程中向对方索要联系方式，或者将您的联系方式发送给对方即可！',	1),
					(".($lastid+38).",".$weid.",	'用户之间匹配度是怎么产生的？',	".($lastid+7).",	0,	'您好，匹配度不是随机的产生的，是按一定的算法计算出来的。主要是按自已与对方的资料及征友要求来计算的，如果较低说明有一方还是有些条件不适合的（但也仅供参考）。 ',	1),
					(".($lastid+39).",".$weid.",	'为什么我的网页无法转跳?',	".($lastid+7).",	0,	'您好，建议您清空手机缓存尝试。',	1),
					(".($lastid+40).",".$weid.",	'为什么访问我空间的人都说没看过?',	".($lastid+7).",	0,	'您好.当用户访问了用户列表，该列表中的所有用户即显示为被看过。',	1),
					(".($lastid+41).",".$weid.",	'性别错误如何修改？',	".($lastid+7).",	0,	'您好，我们为严肃的婚恋网站，不能随意更改性别。如要更改性别需要在注销账号中进行更改。每月限使用1次修改性别功能。',	1),
					(".($lastid+42).",".$weid.",	'怎么才能修改征友要求？',	".($lastid+7).",	0,	'您好，方法为点击资料中的征友要求进行修改即可。',	1),
					(".($lastid+43).",".$weid.",	'怎样加黑对方？',	".($lastid+7).",	0,	'您好，进入对方空间，点加黑。',	1),
					(".($lastid+44).",".$weid.",	'其他联系客服方式？',	".($lastid+7).",	0,	'除了客服电话外您也可以用其它反馈方式进行答疑在线答疑将问题提出第二天会有专人作答（在线答疑回复时间：9:00--18:00(周一至周五)',	1),
					(".($lastid+45).",".$weid.",	'我填写联系方式对方是否看的到？',	".($lastid+7).",	0,	'对个人隐私有着严格的保密措施，对方浏览空间时是看不到的。请您放心。',	1),
					(".($lastid+46).",".$weid.",	'手机号码验证流程？',	".($lastid+7).",	0,	'您好，点击诚信-手机号码验证---提交要验证的手机号码---按页面提示使用正确方法发送短信',	1),
					(".($lastid+47).",".$weid.",	'为什么上传3张本人照片还不添加诚信等级？',	".($lastid+7).",	0,	'您好，只有成功上传三张照片并通过审核设为形象照后，等级才会得到增加。',	1),
					(".($lastid+48).",".$weid.",	'如何查找对方是否在线?',	".($lastid+7).",	0,	'您好，您办理高级会员后就可查看对方是否在线。高级会员办理流程为“服务”里“高级会员”',	1);
	        	";
	        	pdo_query($sql);
	        	message('更新信息设置成功！', $this->createWebUrl('help', array('op' => 'display')), 'success');
	        }
	        include $this->template('web/help');
	    } elseif ($operation == 'post') {
	        $parentid = intval($_GPC['parentid']);
	        $id = intval($_GPC['id']);

	        if (!empty($id)) {
	            $category = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_help') . " WHERE id = '$id'");
	        } else {
	            $category = array(
	                'displayorder' => 0,
	                'enabled' => 1,
	                'status' => 2,
	            );
	        }
	        if (!empty($parentid)) {
	            $parent = pdo_fetch("SELECT id, name,parentid FROM " . tablename('jy_ppp_help') . " WHERE id = '$parentid'");
	            if (empty($parent)) {
	                message('抱歉，该文章不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
	            }
	        }
	        if (checksubmit('submit')) {
	            if (empty($_GPC['catename'])) {
	                message('抱歉，请输入标题名称！');
	            }
	            $data = array(
	                'weid' => $_W['weid'],
	                'name' => $_GPC['catename'],
	                'description' => $_GPC['description'],
	                'enabled' => intval($_GPC['enabled']),
	                'displayorder' => intval($_GPC['displayorder']),
	                'enabled' => intval($_GPC['enabled']),
	                'parentid' => intval($parentid),
	            );


	            if (!empty($id)) {
	                unset($data['parentid']);
	                pdo_update('jy_ppp_help', $data, array('id' => $id));
	            } else {
	                pdo_insert('jy_ppp_help', $data);
	                $id = pdo_insertid();
	            }
	            message('更新信息设置成功！', $this->createWebUrl('help', array('op' => 'display')), 'success');
	        }
	        include $this->template('web/help');
	    } elseif ($operation == 'delete') {
	        $id = intval($_GPC['id']);
	        $category = pdo_fetch("SELECT id, parentid FROM " . tablename('jy_ppp_help') . " WHERE id = '$id'");
	        if (empty($category)) {
	            message('抱歉，不存在或是已经被删除！', $this->createWebUrl('help', array('op' => 'display')), 'error');
	        }
	        pdo_delete('jy_ppp_help', array('id' => $id, 'parentid' => $id), 'OR');
	        message('删除成功！', $this->createWebUrl('help', array('op' => 'display')), 'success');
	    }