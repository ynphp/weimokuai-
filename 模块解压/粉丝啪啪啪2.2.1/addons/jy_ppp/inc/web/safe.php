<?php
global $_W,$_GPC;
		$weid=$_W['uniacid'];
		checklogin();

		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	    if ($operation == 'display') {
	        if (!empty($_GPC['displayorder'])) {
	            foreach ($_GPC['displayorder'] as $id => $displayorder) {
	                pdo_update('jy_ppp_safe', array('displayorder' => $displayorder), array('id' => $id));
	            }
	            message('安全中心更新成功！', $this->createWebUrl('safe', array('op' => 'display')), 'success');
	        }
	        $children = array();
	        $category = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_safe') . " WHERE weid = '{$_W['weid']}' ORDER BY displayorder DESC,id ASC");
	        foreach ($category as $index => $row) {
	            if (!empty($row['parentid'])) {
	                $children[$row['parentid']][] = $row;
	                unset($category[$index]);

	                $category2 = pdo_fetchall("SELECT * FROM " . tablename('jy_ppp_safe') . " WHERE weid = '{$_W['weid']}' AND parentid=".$row['id']." ORDER BY displayorder DESC,id ASC");
	            	foreach ($category2 as $key => $value) {
	            		$children2[$row['id']][]=$value;
	            	}
	            }
	        }

	        if (checksubmit('submit2')) {
	        	$last=pdo_fetch("SELECT id FROM ".tablename('jy_ppp_safe')." ORDER BY id DESC LIMIT 1 ");
	        	$lastid=$last['id'];
	        	$sql = "
	        		INSERT INTO ".tablename('jy_ppp_safe')." (`id`, `weid`, `name`, `parentid`, `displayorder`, `description`, `enabled`, `paixu`) VALUES
						(".($lastid+46).",".$weid.",	'赴异地约会陷入传销窝',	".($lastid+6).",	0,	'&lt;p&gt;刘先生离异多年，偶然机会注册了某婚恋网并结识了异地女孩C。C通过聊天了解到刘先生寻偶心情较急迫，便邀请刘先生来到其所在城市约会。当刘先生抵达C所在城市后发现其从事传销行业，并强迫刘先生加入。后经客服向警方求助，刘先生脱离危险并对C进行刑事拘留。&lt;/p&gt;\r\n&lt;p&gt;提示：请不要轻易单独去其他城市约见陌生异性。&lt;/p&gt;',	1,	0),
						(".($lastid+44).",".$weid.",	'假冒客服电话',	".($lastid+6).",	0,	'&lt;p&gt;近来有不法分子用虚假电话号码冒充客服行骗，请认准官方唯一客服电话&lt;/p&gt;\r\n&lt;p&gt;客服人员不会以任何借口索要用户充值卡/银行卡密码！官方将严肃追究该类行骗者应承担的责任.&lt;/p&gt;',	1,	0),
						(".($lastid+45).",".$weid.",	'帮充话费遇到骗子',	".($lastid+6).",	0,	'&lt;p&gt;王先生在某婚恋网结识了年龄女孩M。经聊天，王先生对M有了初步了解，这时M提出要求王先生帮其充值手机费。M先生为&ldquo;挣面子&rdquo;和表达诚意，便购买了充值卡，向M提供的手机号进行了充值。事后王先生醒悟，怀疑对方可能在行骗并向客服进行了求助。&lt;/p&gt;\r\n&lt;p&gt;提示：请不要帮异性充值话费，这是最常见骗术之一&lt;/p&gt;',	1,	0),
						(".($lastid+42).",".$weid.",	'举报须知',	".($lastid+5).",	0,	'&lt;p&gt;请您本着诚实、守信的原则,对举报行为负责,同时您将受到保护.如有虚假或恶意投诉,将受到网站严肃处理,后果严重者承担相应法律责任.&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;站方免责声明&lt;/p&gt;\r\n&lt;p&gt;官方会尽力维护网络交友平台的严肃性,但仍难确保每位用户资料的真实性及其交友动机的正当性,特别提醒广大用户在交友过程中注意人身财产安全&lt;/p&gt;',	1,	0),
						(".($lastid+43).",".$weid.",	'投诉举报入口说明',	".($lastid+5).",	0,	'点击对方空间页下方的【举报】链接',	1,	0),
						(".($lastid+39).",".$weid.",	'封号',	".($lastid+4).",	0,	'违反有关规定并予以警告过的用户仍屡此违反规定,则封锁其账号.',	1,	0),
						(".($lastid+40).",".$weid.",	'报警',	".($lastid+4).",	0,	'情节严重,超出站方能力范围则向公安机关举报. ',	1,	0),
						(".($lastid+41).",".$weid.",	'黑名单公告',	".($lastid+4).",	0,	'&lt;p&gt;经核实，以下被加入黑名单的账号存在违反有关规定的行为，特此予以公告。&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;今生 女 27岁 广西&lt;/p&gt;\r\n&lt;p&gt;来世 女 35岁 上海&lt;/p&gt;',	1,	0),
						(".($lastid+37).",".$weid.",	'交往提高警惕',	".($lastid+30).",	0,	'&lt;p&gt;交往过程中,对方出现以下任何行为,请您提高警惕&lt;/p&gt;\r\n&lt;p&gt;1.首次见面或交往时间不长索取钱财或提出性要求&lt;/p&gt;\r\n&lt;p&gt;2.个人信息自相矛盾,包括年龄、兴趣、外貌、婚姻状况、职业等&lt;/p&gt;\r\n&lt;p&gt;3.本人外表与网站形象照明显不符&lt;/p&gt;\r\n&lt;p&gt;4.在网络上建立了深入发展的亲密关系,却拒绝进行电话交谈或见面&lt;/p&gt;',	1,	0),
						(".($lastid+38).",".$weid.",	'警告',	".($lastid+4).",	0,	'违反有关规定且情节较轻的用户,予以警告.',	1,	0),
						(".($lastid+36).",".$weid.",	'时刻保持警觉',	".($lastid+29).",	0,	'&lt;p&gt;时刻保持警觉:&lt;/p&gt;\r\n&lt;p&gt;在约会过程中要保持警觉,如果感觉到或发生让自己害怕或危险的事情,要保持冷静,及时离开.必要时可请求他人帮助或报警以保障人身安全.&lt;/p&gt;',	1,	0),
						(".($lastid+34).",".$weid.",	'确认真实身份',	".($lastid+29).",	0,	'&lt;p&gt;确认真实身份:&lt;/p&gt;\r\n&lt;p&gt;当双方见面后最好先确认真实身份,可出示身份证或其他有效证件,保证交友安全.&lt;/p&gt;',	1,	0),
						(".($lastid+35).",".$weid.",	'控制约会时间',	".($lastid+29).",	0,	'&lt;p&gt;控制约会时间:&lt;/p&gt;\r\n&lt;p&gt;如果您是新手,则请格外牢记忠告,即使企盼这次约会已经很长时间,而且做好了精心准备,并且约会非常美满,我们还是请您不要忘记早些回家,让家人放心.&lt;/p&gt;',	1,	0),
						(".($lastid+31).",".$weid.",	'地址信息要保密',	".($lastid+28).",	0,	'&lt;p&gt;地址信息要保密:&lt;/p&gt;\r\n&lt;p&gt;骗子为了不浪费时间和金钱,往往发信直接索要您的家庭住址或工作地点等详细信息,方便日后骗取钱财.若您不提供,骗子可能会马上放弃行骗,转移目标&lt;/p&gt;',	1,	0),
						(".($lastid+32).",".$weid.",	'沟通工具不泄露',	".($lastid+28).",	0,	'&lt;p&gt;沟通工具不泄露:&lt;/p&gt;\r\n&lt;p&gt;电话号及QQ号属于私人沟通工具.骗子获取联系方式后,会拨打骚扰电话或发送垃圾广告信息.望用户在与对方深入了解后再告知此类信息.&lt;/p&gt;',	1,	0),
						(".($lastid+33).",".$weid.",	'如何选择约会地点',	".($lastid+29).",	0,	'&lt;p&gt;如何选择约会地点:&lt;/p&gt;\r\n&lt;p&gt;选择公共场所约会,如肯德基、麦当劳、公共餐厅等.并告知自己的朋友或家人,不要去对方的家里或者陌生的KTV,酒吧等.&lt;/p&gt;',	1,	0),
						(".($lastid+28).",".$weid.",	'初期联系，隐私内容不透露',	".($lastid+3).",	0,	'',	1,	0),
						(".($lastid+29).",".$weid.",	'约会见面，防人之心不可无',	".($lastid+3).",	0,	'',	1,	0),
						(".($lastid+30).",".$weid.",	'交往期间，日久接触见人心',	".($lastid+3).",	0,	'',	1,	0),
						(".($lastid+27).",".$weid.",	'事例四:提供性服务',	".($lastid+14).",	0,	'&lt;p&gt;提供性服务:对方初次来信便透漏QQ、手机号,宣传提供性服务,骗取钱财.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 有些女用户上传虚假漂亮照片,直接给男性发送私信,包含&quot;兼职一夜情&quot;、&quot;新鲜妹妹上门服务&quot;等内容,并留有联系方式.当男性与其取得联系后提供违法性服务.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;为了你的人身和财物安全,请用户朋友们杜绝与这类人员联系,以免被骗财骗色或者产生其他不良后果.&lt;/p&gt;',	1,	0),
						(".($lastid+26).",".$weid.",	'事例三:代孕广告',	".($lastid+14).",	0,	'&lt;p&gt;代孕广告:骗子以代孕为由,发送代孕信息,骗取钱财.若发现此类信息请及时举报,以净化网站交友环境.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 台湾年轻美貌的少妇因为丈夫没有生育能力,在网上求代孕,并称愿意给代孕者50万,19岁的贵州男子小张竟信以为真,结果50万没赚到,倒是被骗了28800元的所谓&quot;代孕手续费&quot;.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;网友要懂得甄别真假,不要抱着贪小便宜的心理,轻信各种所谓的&quot;代孕&quot;信息,上当受骗.&lt;/p&gt;',	1,	0),
						(".($lastid+25).",".$weid.",	'事例二:异地传销',	".($lastid+14).",	0,	'&lt;p&gt;异地传销:对方以约会为由,要求您去其所在地区见面,对方很可能从事传销行业,并会丛恿你一同进行传销,害人害己 .&lt;/p&gt;\r\n&lt;p&gt;案例分析: 徐女士在网上认识A先生,沟通一段时间后,男方便要求徐女士到异地看他,徐女士欣然接受并前往.哪知到了异地才知男方是某传销行业的下线,与徐女士接触是进行有目的的传销.徐女士惊慌逃脱后向警方报案.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;异地网友见面需谨慎,去之前需对目的地进行一番了解,并一路保持电话畅通,若感觉情况不对,应及时离开或选择报警.&lt;/p&gt;',	1,	0),
						(".($lastid+24).",".$weid.",	'事例一:中奖圈套',	".($lastid+14).",	0,	'&lt;p&gt;中奖圈套:骗子会以站方的名义发送中奖消息.请不要轻信信件中公布的&ldquo;领奖&rdquo;网站.官方不会授权其他单位发布任何形式的中奖通知.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 骗子多以中奖通知的形式发送如下虚假信息:&quot;您|已|获|得|免|费|苹|果|手|机|一|部,请|邮|寄|邮|费|至****.&quot;因邮寄费用不是很高,多数被骗用户便自认倒霉不再追究.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;该网发布的中奖信息均为管理员名称及管理员统一头像发送,以区别于会员间的往来信件.&lt;/p&gt;',	1,	0),
						(".($lastid+23).",".$weid.",	'事例二:地址信息泄露',	".($lastid+13).",	0,	'&lt;p&gt;地址信息泄露:沟通中泄露自己的家庭住址、公司地址等信息,可能造成人身财产安全损失.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 陈女士在网站认识某男子,在多次交流中曾透露其单位地址.一天陈女士在单位门口见到一男士等她还自称是网上认识的,但陈女士并未想与此人见面.陈女士觉得自己的人身安全受到了威胁,自此陈女士在网上交友都不再透漏地址信息.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;提供信息时需谨慎,为保证人身财产安全,请与对方进一步了解后再告知个人信息.&lt;/p&gt;',	1,	0),
						(".($lastid+22).",".$weid.",	'事例一:沟通工具泄露',	".($lastid+13).",	0,	'&lt;p&gt;沟通工具泄露：骗子会在沟通过程中获取你的手机号或QQ号码,随后会给你拨打骚扰电话或发送垃圾广告信息.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 北京某男在网上交友过程中,有一女生主动发信提供手机号,并要求对方与之交换联系方式.几天后,男用户收到某婚介公司的电话,声称可以为其提供线下婚姻介绍服务,并不断对其进行电话骚扰.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;建议你与对方有一定了解后再提供联系方式,避免受到骚扰电话和垃圾信息的打扰.&lt;/p&gt;',	1,	0),
						(".($lastid+21).",".$weid.",	'事例二:利用感情骗钱财',	".($lastid+12).",	0,	'&lt;p&gt;利用感情骗钱财:骗子在和你深入了解,获得你的信任后,以各种方法骗取你的个人信息、银行卡号、密码等.以骗取巨额钱财.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 方女士网上认识A先生,在3个月交往期间,A先生假意骗方女士出钱投资开店,并劝说投资越多回报越多.在A先生的花言巧语下,方女士先后3次投资近十万元.之后,该男子就杳无音讯了.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;交友时需小心谨慎,建议在双方感情稳定后再告知一些私人信息或与之有钱财往来,以防人财两空.&lt;/p&gt;',	1,	0),
						(".($lastid+20).",".$weid.",	'事例一:以性需求为目的',	".($lastid+12).",	0,	'&lt;p&gt;以性需求为目的:骗子在与你深入了解后,便会提出发生性关系等要求.以感情为谎骗财骗色.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 女孩小张哭着到派出所报警,称前一天晚上与一名男网友见面开房,早晨醒来发现网友不辞而别,自己衣衫不整,手机钱包都不见了.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;女性用户交友时需提高警惕,对方提出的要求需仔细斟酌,以免给自己带来伤害.&lt;/p&gt;',	1,	0),
						(".($lastid+19).",".$weid.",	'事例五:高额声讯电话',	".($lastid+11).",	0,	'&lt;p&gt;高额声讯电话:骗子在沟通过程中,主动或被动提供电话号码声称是自己的电话,但其实是高额收费的声讯电话.请勿随便轻信拨打！&lt;/p&gt;\r\n&lt;p&gt;案例分析: 安徽的王先生在网上认识了一个美女网友,两人聊的很是投机.美女多次要求给她打电话陪她聊天.事后王先生发现他的电话账单中莫名其妙多出了300元,当王先生意识到是高额声讯电话诈骗时,该女网友也消失得无影无踪了.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;遇到网友提供的电话号码需要仔细查看,必要时通过搜索引擎进行查询&lt;/p&gt;',	1,	0),
						(".($lastid+18).",".$weid.",	'事例四:借款诈骗',	".($lastid+11).",	0,	'&lt;p&gt;借款诈骗:对方在与你进一步了解后,会以家庭困难、异地被骗等各种理由诉说自己的苦境,请求你给其汇款、转账等.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 王先生和刘女士在网上认识有2个月了,一日王先生与刘女士哭诉家中父亲有病住院,需支付高额手术费,自己却没钱医治.刘女士怜悯心起便答应汇款相助,哪知自此以后这个所谓的王先生杳无音讯.刘女士只好报警.&lt;/p&gt;\r\n&lt;p&gt;提醒:&lt;/p&gt;\r\n&lt;p&gt;请勿相信任何未曾见面即要求汇款或充值的理由,即便见面后,涉及到金钱往来的事情,也需格外小心.&lt;/p&gt;',	1,	0),
						(".($lastid+17).",".$weid.",	'事例三:酒托饭托',	".($lastid+11).",	0,	'&lt;p&gt;酒托饭托:对方会与你约会见面,随后将你带至不知名的KTV、饭店或其他娱乐场所,与不良商家勾结欺骗,巨额敲诈.&lt;/p&gt;\r\n&lt;p&gt;案例分析: 李先生是辽宁人,在网上与女网友聊天,觉得甚是投缘.对方主动提约会见面.见面后女方带领其去某KTV,但结账时,李先生傻眼了,他没想到唱个歌,点了点东西竟然要1800多元钱,只得硬着头皮付了钱.&lt;/p&gt;\r\n&lt;p&gt;建议&lt;/p&gt;\r\n&lt;p&gt;用户在交友时需谨慎,如不慎被骗到KTV、咖啡厅,结帐时发现帐单价格过高,可借去洗手间等为由拨打电话报警.&lt;/p&gt;',	1,	0),
						(".($lastid+16).",".$weid.",	'事例二:骗电话费',	".($lastid+11).",	0,	'&lt;p&gt;骗电话费:对方会与您进行沟通,取得你的信任后,便会要你帮助充值手机话费,请不要上当！&lt;/p&gt;\r\n&lt;p&gt;案例分析: 刘先生在网上认识美女C,并与之深入沟通后,美女C多次声称自己手机欠费,撒娇要刘先生给其购买充值卡或充话费,当刘先生提出见面时,对方又婉言拒绝.最终刘先生意识到美女C就是骗他钱财时也只能认栽.&lt;/p&gt;\r\n&lt;p&gt;提醒：&lt;/p&gt;\r\n&lt;p&gt;遇到非常热情,联系时间很短就主动要求确立情侣关系的人需要提高警惕.不要轻易为他人支付电话费,以免上当受骗&lt;/p&gt;',	1,	0),
						(".($lastid+14).",".$weid.",	'其他骗术',	".($lastid+2).",	0,	'',	1,	0),
						(".($lastid+15).",".$weid.",	'事例一:骗路费',	".($lastid+11).",	0,	'&lt;p&gt;骗路费：对方会以赴异地与您见面为由,要求您提供其路费花销（火车票/飞机票）,以骗取钱财.&lt;/p&gt;\r\n&lt;p&gt;案例分析:&lt;/p&gt;\r\n&lt;p&gt;朱女士在网上认识了某男,俩人深入了解后,决定约会见面.过了一天,男方来电声称路上钱丢了,要求朱女士汇款救济,朱女士照做后,男方又声称没收到,要求再汇一次,朱女士也照做了.然而过了两天此男士都没再联系过.当朱女士再拨打其电话时已找不到此人,方知自己被骗.&lt;/p&gt;\r\n&lt;p&gt;建议:&lt;/p&gt;\r\n&lt;p&gt;请勿相信任何未曾见面即要求汇款或转账的理由,涉及到金钱往来的事情,需格外小心.&nbsp;&lt;/p&gt;',	1,	0),
						(".($lastid+12).",".$weid.",	'情感诈骗',	".($lastid+2).",	0,	'',	1,	0),
						(".($lastid+13).",".$weid.",	'隐私泄露',	".($lastid+2).",	0,	'',	1,	0),
						(".($lastid+11).",".$weid.",	'经济诈骗',	".($lastid+2).",	0,	'',	1,	0),
						(".($lastid+10).",".$weid.",	'花篮托',	".($lastid+1).",	0,	'&lt;p&gt;此类行骗人员多为30-50岁之间声称事业有成的男性，他们通常不会留下自己的任何联系方式，只是在一开始便索要对方的电话号码，通过电话急切的与对方建立亲密关系，使用各种亲昵的方式骗取对方信任，接着就会以公司开业、店面开张为由，要求对方送花篮。&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;防花篮托指南：&lt;/p&gt;\r\n&lt;p&gt;1、衣着西装革履（比较光鲜），年龄在40几岁的男性用户在一开始便索要联系电话时，需要提高谨慎；&lt;/p&gt;\r\n&lt;p&gt;2、在电话联系的过程中，对方一旦提出自己的公司开业、店面开张，并且要求汇款时，务必提高警惕，断绝与此人的联系！&lt;/p&gt;',	1,	0),
						(".($lastid+9).",".$weid.",	'钱财诈骗',	".($lastid+1).",	0,	'&lt;p&gt;女性通常会找各种理由让对方为其买充值卡、汇钱、寄路费等，甚至以&lsquo;考验对方的真诚度&rsquo;为借口，要求对方充值汇款。&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;防钱财欺诈指南：&lt;/p&gt;\r\n&lt;p&gt;交流过程中，对方一旦提及钱财相关的语句即需提高谨慎。&lt;/p&gt;',	1,	0),
						(".($lastid+8).",".$weid.",	'代孕',	".($lastid+1).",	0,	'&lt;p&gt;此类行骗人员会假借各种理由寻找健康男士要求代孕，并承诺给予高额的回报来诱惑男用户上当。她们通常会持续发送带有联系方式的信件，但是在网站上不与对方进行下一步的沟通，当用户加了她们留在网站上的qq时，会发现其qq的资料里写着代孕相关的信息来进一步行骗。&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;防代孕指南：&lt;/p&gt;\r\n&lt;p&gt;1、当女性用户上来就给你发联系方式，并且没有进一步沟通时，男性用户需要提高警惕；&lt;/p&gt;\r\n&lt;p&gt;2、当怀疑其存在代孕行为倾向时，即考虑停止通过其他联系方式与其取得进一步的联系。&lt;/p&gt;',	1,	0),
						(".($lastid+1).",".$weid.",	'行骗类型',	0,	0,	'',	1,	1),
						(".($lastid+2).",".$weid.",	'警惕诈骗',	0,	0,	'',	1,	1),
						(".($lastid+3).",".$weid.",	'防骗指南',	0,	0,	'',	1,	0),
						(".($lastid+4).",".$weid.",	'处罚方式',	0,	0,	'',	1,	1),
						(".($lastid+5).",".$weid.",	'投诉举报',	0,	0,	'',	1,	0),
						(".($lastid+6).",".$weid.",	'热点问题',	0,	0,	'',	1,	0),
						(".($lastid+7).",".$weid.",	'酒托',	".($lastid+1).",	0,	'&lt;p&gt;双方聊天时会索要对方联系方式，持续坚持双方见面了解，并且找各种理由约对方到指定地点见面，被指定地点提供的饮料酒水或食品价格通常会比较昂贵，对方会非常热情的点单，大部分是高级酒水、果盘（她们可以从中获利），消费完后，会找各种借口先行离开，由对方结账。&lt;/p&gt;\r\n&lt;p&gt;&nbsp;&lt;/p&gt;\r\n&lt;p&gt;防酒托指南：&lt;/p&gt;\r\n&lt;p&gt;1、女性在沟通过程中积极主动的索要你的联系方式，在没有深入了解的情况下即提出见面了解时，男性朋友需提高警惕；&lt;/p&gt;\r\n&lt;p&gt;2、首次见面由自己确定见面的地点，或者尽量选择人多的公共场合见面，如KFC、麦当劳等；&lt;/p&gt;\r\n&lt;p&gt;3、如不慎被骗，在发现后需冷静对待，及时打电话报警。&lt;/p&gt;',	1,	0),
						(".($lastid+47).",".$weid.",	'陷入热恋人财两空',	".($lastid+6).",	0,	'&lt;p&gt;郑女士30岁，从事金融行业，单身待嫁。近日郑女士在某婚恋网认识了同城男士Z，几日聊天后觉得相见恨晚，Z提出见面后郑女士欣然同意。双方首次约会后均表示满意，迅速确立了恋爱关系，当晚用餐后，Z和郑女士便在某快捷酒店开房留宿。数日后，Z以生意周转为借口多次向郑女士借钱，郑女士没有提防，将数万元积蓄都交付给Z。交往一月后，Z以各种理由搪塞郑女士，不再与其见面，并更换电话号码，郑女士意识到可能上当，并在客服协助下报警。&lt;/p&gt;\r\n&lt;p&gt;提示：骗子往往在取得异性信任后骗财骗色，请不要轻信陌生人！&lt;/p&gt;',	1,	0);

	        	";
	        	pdo_query($sql);
	        	message('更新信息设置成功！', $this->createWebUrl('safe', array('op' => 'display')), 'success');
	        }
	        include $this->template('web/safe');
	    } elseif ($operation == 'post') {
	        $parentid = intval($_GPC['parentid']);
	        $id = intval($_GPC['id']);

	        if (!empty($id)) {
	            $category = pdo_fetch("SELECT * FROM " . tablename('jy_ppp_safe') . " WHERE id = '$id'");
	        } else {
	            $category = array(
	                'displayorder' => 0,
	                'enabled' => 1,
	                'status' => 2,
	            );
	        }
	        if (!empty($parentid)) {
	            $parent = pdo_fetch("SELECT id, name,parentid FROM " . tablename('jy_ppp_safe') . " WHERE id = '$parentid'");
	            if (empty($parent)) {
	                message('抱歉，该文章不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
	            }
	            else
	            {
	            	if(!empty($parent['parentid']))
	            	{
	            		$wenzhang=1;
	            	}
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
	                'paixu' => intval($_GPC['paixu']),
	                'parentid' => intval($parentid),
	            );


	            if (!empty($id)) {
	                unset($data['parentid']);
	                pdo_update('jy_ppp_safe', $data, array('id' => $id));
	            } else {
	                pdo_insert('jy_ppp_safe', $data);
	                $id = pdo_insertid();
	            }
	            message('更新信息设置成功！', $this->createWebUrl('safe', array('op' => 'display')), 'success');
	        }
	        include $this->template('web/safe');
	    } elseif ($operation == 'delete') {
	        $id = intval($_GPC['id']);
	        $category = pdo_fetch("SELECT id, parentid FROM " . tablename('jy_ppp_safe') . " WHERE id = '$id'");
	        if (empty($category)) {
	            message('抱歉，不存在或是已经被删除！', $this->createWebUrl('safe', array('op' => 'display')), 'error');
	        }
	        pdo_delete('jy_ppp_safe', array('id' => $id, 'parentid' => $id), 'OR');
	        message('删除成功！', $this->createWebUrl('safe', array('op' => 'display')), 'success');
	    }