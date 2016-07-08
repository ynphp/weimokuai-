H = 960;

function qp_a(a) {}
var qp_b = param.CountdownTime,
	qp_c, qp_d = 5,
	qp_e = 3,
	qp_f = qp_e,
	qp_g = 420,
	qp_h = 0,
	qp_i = [],
	qp_j = 20,
	qp_k = 0,
	qp_l, qp_m = 0,
	qp_n = 0,
	qp_o = 0;

function qp_p() {
	qipaStage.stage.arrow.visible = !0;
	qp_q = qipaApp.score = 0;
	qp_n = qp_b;
	qp_m = -1;
	qipaStage.stage.num.txt.text = qp_n + '"';
	qp_k = 0;
	qp_o = 1;
	qipaApp.onGameStarted()
}

function qp_r() {
	qipaStage.stage.splash.visible = !0
}

function qp_s() {
	qipaStage.stage.arrow.visible = !1;
	qp_m = 0
}

function qp_t() {
	qp_o = 3;
	qp_l = setTimeout(function() {
		window.clearTimeout(qp_l)
	}, 900);
	qp_u();
	qipaApp.onNewScore(qipaApp.score);
	qipaApp.onGameOver();
	qipaStage.stage.gameover.visible = !0;
	qipaStage.stage.gameover.refresh()
}

function qp_v(a) {
	IS_ANDROID && (createjs.Sound.registMySound("count", 0), createjs.Sound.registMySound("silenttail", 0.25));
	qp_w();
	qp_u()
}

function Qp_x() {
	this.initialize();
	this.bg = new createjs.Shape;
	this.bg.graphics.beginFill(param.clockBgColor).drawRect(0, 0, W, H);
	this.addChild(this.bg);
	if(null != param.bgImgUrl && '' != param.bgImgUrl){
		this.bgImg = new createjs.Bitmap(param.bgImgUrl);
		this.bgImg.x = 0,
		this.bgImg.y = 0,
		this.addChild(this.bgImg);
	}
	//this.label = new createjs.Bitmap(qipaStage.queue.getResult("splashtitle"));
	if(null != param.Bannersrc && '' != param.Bannersrc){
		this.label = new createjs.Bitmap(param.Bannersrc); //顶部banner图
		this.label.x = (W - this.label.getBounds().width) / 2;
		this.label.y = 0;
		this.addChild(this.label);
	}
	this.start = new createjs.Bitmap(qipaStage.queue.getResult("mb0"));
	this.start.y = H - 400;
	this.start.x = (W - this.start.getBounds().width) / 2;
	this.addChild(this.start);
	this.arrow = new createjs.Bitmap(qipaStage.queue.getResult("starttip"));
	this.arrow.y = H - 400;
	this.arrow.x = (W - this.arrow.getBounds().width) / 2;
	this.addChild(this.arrow);
	var a, b;
	this.start.on("mousedown", function(c) {
		//游戏准备开始 先判断是否需要注册 是否还有游戏机会
		if(param.isNeedRegister == 1 && param.registerFlag == 0){
           $("#register").show();
           return;
		}

		if (param.CountgameTime>0){
		   0 == qp_o && (a = c.localY, b = H - 300);
		} else {
		   showTips(param.Gameovertext);
		}
	});
	this.start.on("pressmove", function(c) {
		0 == qp_o && SplashPressmoveEvent(c.localY - a, b)
	});
	this.start.on("pressup", function(b) {
		0 == qp_o && 30 > a - b.localY && (createjs.Sound.play("count", !0), createjs.Tween.get(qipaStage.stage.splash.start).to({
			y: -H
		}, 400).call(function(a) {
			qipaStage.stage.splash.arrow.visible = !1;
			qp_p();
			qipaStage.stage.splash.visible = !1
		}))
	});
}
Qp_x.prototype = new createjs.Container;

function SplashPressmoveEvent(a, b) {
	qipaStage.stage.splash.start.y + a < b && (qipaStage.stage.splash.start.y += a)
}

function qp_w() {
	var a = new createjs.Shape;
	// a.graphics.beginFill(param.clockBgColor).drawRect(0, 0, W, H);
	if(null != param.bgImgUrl && '' != param.bgImgUrl){
		a = new createjs.Bitmap(param.bgImgUrl);
		a.x = 0,
		a.y = 0;
		// this.addChild(this.bgImg);
	}else{
		a.graphics.beginFill(param.clockBgColor).drawRect(0, 0, W, H);
	}
	qipaStage.stage.addChild(a);
	var b = new createjs.Shape;
	b.graphics.beginFill("white").rect(0, 200, W, H);
	a.hitArea = b;
	var c = 0,
		d = 0;
	a.on("mousedown", function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || 2 != qp_o && 1 != qp_o || (c = a.localY, d = qipaStage.stage.player.m[qp_f].y)
	});
	a.on("pressmove", function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || (1 == qp_o && (qp_s(), qp_o = 2), 2 == qp_o && (qipaStage.stage.player.m[qp_f].visible = !0, qipaStage.stage.player.m[qp_f].y += (a.localY - c) / 1.5))
	});
	var f = 0;
	a.on("pressup", function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || 2 != qp_o || (50 < c - a.localY ? (a = (new Date).getTime(), 0 < qp_i.length && qp_i[qp_i.length - 1] + 50 > a ? qp_a("WARNING: Too fast! maybe engine error.") : (f = qp_y(a), f <= qp_j ? (qp_k++, qipaApp.score += parseInt(param.baseNumber), qipaStage.stage.player.playAnimation(qipaStage.stage.player.m[qp_f]), createjs.Sound.play("count", !0)) : (qp_i.length--, qp_a("WARN: " + f)))) : (qp_z(d), qipaStage.stage.player.m[qp_f].visible = !1))
	});
	qp_c = [];
	for (a = 0; a <= qp_e; a++)
		for (qp_c[a] = [], b = 0; b < qp_d; b++) {
			var e = new createjs.Bitmap(qipaStage.queue.getResult("d0"));
			e.regX = e.getBounds().width / 2;
			e.regY = e.getBounds().height / 2;
			e.x = genRandom(W);
			e.y = -H / 2 + genRandom(H);
			e.visible = !1;
			qp_c[a].push(e);
			qipaStage.stage.addChild(qp_c[a][b])
		}
	qipaStage.stage.player = new Qp_A;
	qipaStage.stage.addChild(qipaStage.stage.player);
	qipaStage.stage.num = new Qp_B;
	qipaStage.stage.num.y = 30;
	qipaStage.stage.addChild(qipaStage.stage.num);
	qipaStage.stage.arrow = new createjs.Bitmap(qipaStage.queue.getResult("starttip"));
	qipaStage.stage.arrow.x = (W - qipaStage.stage.arrow.getBounds().width) / 2;
	qipaStage.stage.arrow.y = 290;
	qipaStage.stage.arrow.visible = !1;
	qipaStage.stage.addChild(qipaStage.stage.arrow);
	qipaStage.stage.gameover = new Qp_C;
	qipaStage.stage.gameover.x = 0;
	qipaStage.stage.gameover.y = 260;
	qipaStage.stage.gameover.visible = !1;
	qipaStage.stage.addChild(qipaStage.stage.gameover);
	qipaStage.stage.splash = new Qp_x;
	qipaStage.stage.addChild(qipaStage.stage.splash);
	setInterval(qp_D, 1E3);
	createjs.Ticker.addEventListener("tick",
		function(a) {
			0 <= qp_m && (qp_m += a.delta, a = param.CountdownTime - parseInt(qp_m / 1E3), a != qp_n && (qp_n = a, qipaStage.stage.num.txt.text = qp_n + '"'), 0 >= qp_n && (qp_m = -1, qp_t()));
//			qipaStage.stage.num.sum.text = "￥" + qipaApp.score
			qipaStage.stage.num.sum.text = param.signtext + qipaApp.score
		})
}

function Qp_A() {
	this.initialize();
	this.mb = new createjs.Bitmap(qipaStage.queue.getResult("mb0"));
	this.mb.regX = this.mb.getBounds().width / 2;
	this.mb.regY = this.mb.getBounds().height / 2;
	this.mb.y = qp_g;
	this.x = W / 2;
	this.y = H / 2 - 150;
	this.addChild(this.mb);
	this.m = [];
	for (var a = 0; 3 >= a; a++) this.m[a] = new createjs.Bitmap(qipaStage.queue.getResult("m0")), this.m[a].regX = this.m[a].getBounds().width / 2, this.m[a].regY = this.m[a].getBounds().height / 2, this.m[a].y = qp_g, this.m[a].visible = !1, this.addChild(this.m[a]);
	for (a = 0; a <=
		qp_e; a++) this.m[a].image = qipaStage.queue.getResult("m0");
	for (a = 0; a < qp_c.length; a++)
		for (var b = 0; b < qp_c[a].length; b++) qp_c[a][b].image = qipaStage.queue.getResult("d0")
}
Qp_A.prototype = new createjs.Container;
Qp_A.prototype.playAnimation = function(a) {
	a.visible = !0;
	createjs.Tween.get(a).to({
		scaleX: 0.5,
		scaleY: 0.5,
		y: -H
	}, 300).to({
		visible: !1,
		y: qp_g,
		scaleX: 1,
		scaleY: 1
	}, 0);
	0 < qp_f ? qp_f-- : qp_f = qp_e
};

function genRandom(a) {
	return parseInt(Math.random() * a)
}

function qp_E(a) {
	return 10
}
var qp_F = 0;

function qp_D() {
	for (var a = 0; a < qp_d; a++){
		qp_c[qp_F][a].visible = !0, createjs.Tween.get(qp_c[qp_F][a]).to({
			y: H + qp_c[qp_F][a].getBounds().height / 2 + 100,
			rotation: 720 + genRandom(400),
			x: genRandom(W)
		}, 1E3 + genRandom(800)).to({
			visible: !1
		}, 10).to({
			x: genRandom(W),
			y: -H / 2 + genRandom(H / 2),
			rotation: 0
		}, 10);
	}
	qp_F < qp_e ? qp_F++ : qp_F = 0
}

function qp_z(a) {
	var b = Math.abs(qipaStage.stage.player.m[qp_f] - a);
	createjs.Tween.get(qipaStage.stage.player.m[qp_f]).to({
		y: a
	}, 20 * b)
}

//加载排行榜
var isRunning = false;
function loadLatestRankList(){
	if(isRunning){return};
	isRunning = true;
    var showSelf = true;
	//alert('debug');
	$.ajax({
		type : "POST",
		url : APP_API_RANK,
		data : {},
		dataType : "json",
		success : function(data){
			$("#ranking-list ul").html("");
			for(var i=0;i< data.topList.length;i++){
		    	var html = '<li>';
		    	if(i < 3){
		    		html += '<span class="n"><img src="../addons/weisrc_money/template/mobile/money/hd/images/rank-'+(i+1)+'.png" /></span>';
				//../addons/weisrc_money/template/money/
		    	}else{
		    		html += '<span class="n">'+(i+1)+'</span>';
		    	}
		    	var headimg = data.topList[i].headimg?data.topList[i].headimg:"../web/resource/images/noavatar_middle.gif";
		    	var username = data.topList[i].username?data.topList[i].username:"匿名";
		    	var score = data.topList[i].score;
		    	html +='<span class="thumb"><img src="'+headimg+'" /></span>';
		    	html +='<span>'+username+'</span>';
		    	html +='<span class="gold-num">'+score+'</span></li>';
		    	$("#ranking-list ul").append(html);

		    	if(!data.self || (data.self.wxid == data.topList[i].wxid)) showSelf = false;
		    }
			if(showSelf){
		    	var headimg = data.self.headimg?data.self.headimg:"../web/resource/images/noavatar_middle.gif";
		    	var username = data.self.username?data.self.username:"匿名";
		    	var score = data.self.score;

		    	var html = '<li class="more"><p>……</p></li>';
		    	html += '<li><span class="n">'+data.self.rank+'</span>';
		    	html +='<span class="thumb"><img src="'+headimg+'" /></span>';
		    	html +='<span>'+username+'</span>';
		    	html +='<span class="gold-num">'+score+'</span></li>';
		    	$("#ranking-list ul").append(html);
		    }
		    $("#rankinglist").fadeIn();
			window.loaded();
		    isRunning = false;
		},
		timeout : 15000,
		error : function(xhr, type){
			isRunning = false;
		}
    });
}

//提示语
function showTips(text){
    $("#tips .content p").html(text);
	$("#tips").fadeIn();
}

function Qp_C() {
	this.initialize();
	var a = new createjs.Shape,
		b = qipaStage.queue.getResult("dlgbg");
	a.setBounds(0, 0, W, b.height);
	a.graphics.bf(b).r(0, 0, W, b.height);
	this.addChild(a);
	b = new createjs.Bitmap(qipaStage.queue.getResult("start"));
	b.x = 40;
	b.y = a.y + a.getBounds().height - 160;
	b.on("click", function(a) {
		if (param.CountgameTime>0){
			qp_p();
		    qipaStage.stage.gameover.visible = !1
		} else {
			showTips(param.Gameovertext);
		}
	});
	var c = new createjs.Bitmap(qipaStage.queue.getResult("rank"));
	c.x = W / 2;
	c.y = b.y;
	c.regX = c.getBounds().width / 2;
	c.on("click", function(a) {
		//点击排行榜 先判断是否需要注册
		if(param.isNeedRegister == 2 && param.registerFlag == 0){
           $("#register").show();
           return;
		}
		loadLatestRankList();//加载最新排行榜
	});
	var d = new createjs.Bitmap(qipaStage.queue.getResult("share"));
	d.x = W - 40;
	d.y = b.y;
	d.regX = c.getBounds().width;
	d.on("click", function(a) {
		$("#about").fadeIn();
		window.aboutloaded();
	});
	this.addChild(b);
	this.addChild(c);
	this.addChild(d);
	this.scoreText = new createjs.Text("", "bold 50px Arial", "#c57a22");
	this.scoreText.textAlign = "center";
	this.scoreText.x = W / 2;
	this.scoreText.y = a.y + 50;
	this.addChild(this.scoreText);
	this.shareText = new createjs.Text("", "38px Arial", "#c57a22");
	this.shareText.textAlign =
		"center";
	this.shareText.x = W / 2;
	this.shareText.y = this.scoreText.y + 95;
	this.addChild(this.shareText)
}
Qp_C.prototype = new createjs.Container;
Qp_C.prototype.refresh = function() {
//	this.scoreText.text = "￥" + qipaApp.score;
	this.scoreText.text = param.signtext + qipaApp.score;
	this.shareText.text = 0 < qipaApp.score ? qipaShare.desc : ""
};

function Qp_B() {
	this.initialize();
	this.tmbg = new createjs.Bitmap(qipaStage.queue.getResult("tmbg"));
	this.tmbg.x = (W - this.tmbg.getBounds().width) / 2;
	this.tmbg.y = 30;
	this.addChild(this.tmbg);
//	this.sum = new createjs.Text("￥" + qipaApp.score, "bold 46px Arial", "yellow");
    this.sum = new createjs.Text(param.signtext + qipaApp.score, "bold 46px Arial", "yellow");
	this.sum.textAlign = "center";
	this.sum.textBaseline = "middle";
	this.sum.x = W / 2;
	this.sum.y = this.tmbg.y + this.tmbg.getBounds().height / 2;
	this.addChild(this.sum);
	this.tmbg1 = new createjs.Bitmap(qipaStage.queue.getResult("tmbg"));
	this.tmbg1.scaleX = 0.7;
	this.tmbg1.x = (W - 0.7 * this.tmbg.getBounds().width) / 2;
	this.tmbg1.y = this.tmbg.y + this.tmbg.getBounds().height + 15;
	this.addChild(this.tmbg1);
	this.tmicon = new createjs.Bitmap(qipaStage.queue.getResult("tmicon"));
	this.tmicon.x = this.tmbg1.x + 14;
	this.tmicon.y = this.tmbg1.y + 14;
	this.addChild(this.tmicon);
	this.txt = new createjs.Text(qp_n + '"', "bold 44px Arial", "white");
	this.txt.textAlign = "center";
	this.txt.textBaseline = "middle";
	this.txt.x = W / 2 + this.tmicon.getBounds().width / 2;
	this.txt.y = this.tmbg1.y + this.tmbg1.getBounds().height /
		2;
	this.addChild(this.txt)
}
Qp_B.prototype = new createjs.Container;

function qp_y(a) {
	var b = 0;
	if (0 != qp_i.length) {
		var c;
		for (c = 0; c < qp_i.length && !(qp_i[c] > a - 1E3); c++);
		for (var b = qp_i.length - c, d = c; d < qp_i.length; d++) qp_i[d - c] = qp_i[d];
		qp_i.length -= c
	}
	qp_i.push(a);
	return parseInt(b)
}

/*function qp_u() {
	qipaShare.title = "数钱数到手抽筋！你是数钱高手吗？";
	if (0 == qipaApp.score) qipaShare.desc = qipaShare.title;
	else {
		var a = parseInt(Math.sqrt(1E4 * qipaApp.score / 17E3));
		param.CountgameTime--;
		99 < a && (a = "99.9");
		qipaShare.desc = "我数了￥" + qipaApp.score + "，比" + a + "%的人有钱！我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
			param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。\n还剩" + param.CountgameTime + "次数钱机会。"
	}
}*/
function qp_u() {
	if (0 == qipaApp.score) {
		//dataForWeixin.desc = dataForWeixin.title;
	}else {
		var a = parseInt(Math.sqrt(1E4 * qipaApp.score / 17E3));
		param.CountgameTime--;
		99 < a && (a = "99.9");
  //       if(param.CountgameTime > 1000){
		// 	dataForWeixin.desc = "我数了￥" + qipaApp.score + "，比" + a + "%的人有钱！我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
		// 	param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。";
		// 	qipaShare.desc = "我数了￥" + qipaApp.score + "，\n比" + a + "%的人有钱！\n我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
		// 		param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。";
		// }
  //       else{
  //       	dataForWeixin.desc = "我数了￥" + qipaApp.score + "，比" + a + "%的人有钱！我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
		// 	param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。";
		// 	qipaShare.desc = "我数了￥" + qipaApp.score + "，\n比" + a + "%的人有钱！\n我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
		// 		param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。\n还剩" + param.CountgameTime + "次数钱机会。";
  //       }
        if(param.CountgameTime > 1000){
        	function replaceAllFun(str, sptr, sptr1){
                while (str.indexOf(sptr) >= 0){
                   str = str.replace(sptr, sptr1);
                }
                return str;
            }
			dataForWeixin.desc = replaceAllFun(param.shareDesc, '[score]', qipaApp.score);
			qipaShare.desc = replaceAllFun(param.shareDesc, '[score]', qipaApp.score);
		}else{
        	dataForWeixin.desc = "我"+param.tips1text+"了"+ param.signtext + qipaApp.score + "，比" + a + "%的人"+param.tips2text+"！我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
			param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。";
			qipaShare.desc = "我"+param.tips1text+"了"+ param.signtext + qipaApp.score + "，\n比" + a + "%的人"+param.tips2text+"！\n我是" + (param.Score1 > qipaApp.score ? param.Score1text : param.Score2 > qipaApp.score ? param.Score2text : param.Score3 > qipaApp.score ? param.Score3text : param.Score4 > qipaApp.score ? param.Score4text :
			param.Score5 > qipaApp.Score ? param.Score5text : param.Score6text) + "。\n还剩" + param.CountgameTime + "次"+param.tips3text+"机会。";
        }
	}
}
var _cfg = {
	startFunc: qp_v,
	img: {
		path: param.Imagesrc,
		manifest: [{
			src: "m0.png",
			id: "m0"
		}, {
			src: "mb0.png",
			id: "mb0"
		}, {
			src: "d0.png",
			id: "d0"
		}, {
			src: "starttip.png",
			id: "starttip"
		}, {
			src: "tmbg.png",
			id: "tmbg"
		}, {
			src: "splashtitle.png",
			id: "splashtitle"
		}, {
			src: "tmicon.png",
			id: "tmicon"
		}, {
			src: "start.png",
			id: "start"
		}, {
			src: "rank.png",
			id: "rank"
		}, {
			src: "share.png",
			id: "share"
		}, {
			src: "dlgbg.png",
			id: "dlgbg"
		}]
	},
	audio: {
		path: param.Audiorsrc,
		manifest: [{
			src: "1.mp3",
			id: "count"
		}]
	}
};

qipaStage.init(_cfg);