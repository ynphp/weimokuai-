
var Zhu = {
		_elementStyle	: document.createElement('div').style,	
		_UC 			: RegExp("Android").test(navigator.userAgent)&&RegExp("UC").test(navigator.userAgent)? true : false,
		_weixin			: RegExp("MicroMessenger").test(navigator.userAgent)? true : false,
		_iPhone			: RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)? true : false,
		_Android		: RegExp("Android").test(navigator.userAgent)? true : false,
		_IsPC			: function(){ 
						var userAgentInfo = navigator.userAgent; 
						var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
						var flag = true; 
						for (var v = 0; v < Agents.length; v++) { 
							if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
						} 
						return flag; 
		} ,	
		_isOwnEmpty		: function (obj) { 
						for(var name in obj) { 
							if(obj.hasOwnProperty(name)) { 
								return false; 
							} 
						} 
						return true; 
					},
	
		// 判断浏览器内核类型
		_vendor			: function () {
							var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
								transform,
								i = 0,
								l = vendors.length;
					
							for ( ; i < l; i++ ) {
								transform = vendors[i] + 'ransform';
								if ( transform in document.createElement('div').style ) return vendors[i].substr(0, vendors[i].length-1);
							}
							return false;
						},
		// 判断浏览器来适配css属性值
		_prefixStyle	: function (style) {
							if ( this._vendor() === false ) return false;
							if ( this._vendor() === '' ) return style;
							return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
						},
	
		_translateZ : function(){
							if(Zhu._hasPerspective){
								return ' translateZ(0)';
							}else{
								return '';
							}
						}
		
	};
	
	
	


var IsShareUser = ZQ.tool.getQueryString('shareid');






	
				
var loadingL1 = [];
	
for ( var i = 1 ; i< 24 ; i++ ) {		//116 75
		loadingL1.push (IMG_PATH + 'loading/'+ i + '.png?1');	
};

var loadingSprite = new ZQ.fx.spriteCanvas ('x1', 30, true );



loadingSprite.initDraw ( document.querySelector('.loading-sprite') , loadingL1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
					
						this.draw ( result  ,function ( s ) {} , function (){});
						
					}	
} , false);



/*
loadingSprite.play ($('.loading-sprite')[0]  , [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22] ,  true , 30 , true );
*/
$('#textarea').focus( function (){
	if( this.value == '请输入你想要告白的话，仅限20个中文字') this.value = '';
}).blur( function (){
	
	$(window).scrollTop(0);
	
	//if( this.value == '请输入你想要告白的话，仅限20个中文字') this.value = '';
});

$$('.step-box2 .btn').tap( function (){
		

if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【立即提交】');


		if($('#textarea').val().indexOf('请输入你想要告白的话') > -1)
		{
			alert('请输入你想要告白的话!');
			return;	
		}
		if($('#textarea').val().length > 20)
		{
			alert('仅限20个中文字哦!');
			return;	
		}
		
		
		if(/[a-zA-Z]/g.test($('#textarea').val())){
			alert('请输入非英文字符!')
			return;
		};
		
		
		window.USERTXT = $('#textarea').val();
		
		
		
	$('.step-box2').cssHide('fadeOutLeft animated5')
	$('.step-box3').cssShow('fadeInRight animated5')
			$('.stp3-bj img').each( function ( i ){
					if(i == 0) return true;
					var t = $(this);
					t.from({ opacity : 0 , transform : 'translate(0,20px)' , delay : i * .1 + .5} , .5 , function (){
						t.addClass('fudong')	
					})
					
					
				})
});


$('.keshi').bind('touchend' , function (){
	$(this).addClass('hover').siblings('.keshi').removeClass('hover');
	if($(this).attr('index') == '1'){
		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【计算机系】');
	}
	if($(this).attr('index') == '2'){
		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【中文系】');
	}
	if($(this).attr('index') == '3'){
		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【化学系】');
	}
	if($(this).attr('index') == '4'){
		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【音乐系】');
	}
	


	
	
})



$$('.step-box1 .btn').tap( function (){
	
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【开始告白】');
	
	ZQ.fx.stopAllSprite();	
	$('.step-box1').cssHide('fadeOutLeft animated5')
	$('.step-box2').cssShow('fadeInRight animated5')
	
				$('.stp2-bj img').each( function ( i ){
					if(i == 0) return true;
					var t = $(this);
					t.css('opacity' , .3)
					t.from({ opacity : 0 , transform : 'translate(0,20px)' , delay : i * .1 + .5} , .5 , function (){
						t.addClass('shan')	
					})
					
					
				})
				
});

$$('.step-box3 .btn , .seltxt').tap( function (){
	
	if($(this).hasClass('seltxt'))
	{
		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【对告白加密】');
	}else{
				
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '【开始加密】');	
	}
	
	

		
	
	
		
		if(!$('.keshi.hover').size()) {
			
			
			$('.seltxt').fadeOut(200, function (){
					$('.seltxt').fadeIn(200, function (){
							$('.seltxt').fadeOut(200, function (){
								$('.seltxt').fadeIn(200, function (){
									
							});	
					});
				});	
			});
			
			
			
			
			return;
		};
		loadingSprite.start();
		$('#loading').fadeIn();
		$('.loading-p').hide();
		$('.jiamining').show();
		window.Category = GetRandomNum(0,2);
		
		
	/*	
			$('.zhuquan1').fadeOut( function (){
							if($('.keshi.hover').attr('index') == '1') Animate_diannao();
							if($('.keshi.hover').attr('index') == '2') Animate_zhongwen();
							if($('.keshi.hover').attr('index') == '3') Animate_huaxue();
							if($('.keshi.hover').attr('index') == '4') Animate_yinyue();
						});
		
		
		*/
		
		$.ajax({
			type:'POST',
			url:POST_URL,
			data:{
				content : window.USERTXT,
				department : $('.keshi.hover').attr('index') * 1 ,	
				category : window.Category + 1
			},
			dataType:"json",
			success: function ( res ){
					if( res.result == 100)
					{
						window.LoveRes = res;
						
						
						window.textArr = [];
	
						for( var j = 0 ; j <  window.USERTXT.length ; j ++)
						{
							window.textArr.push([window.USERTXT.charAt(j) , res.content_pingyin.split(' ')[j]]);
						}
	
	
	
						
						share_timeline_data.title = share_app_data.desc = [
						'代号“520爱神”的黑客入侵，求你破译我的心！ ',
						'谁说神藏头只出自李白，我也能把告白写成神作！',
						'我的心动元素周期表，你能看懂多少？',
						'“心”技能GET，这可能是世界上最“动听”的告白！'][$('.keshi.hover').attr('index') * 1 - 1];
						share_timeline_data.link = share_app_data.link = INDEX_URL + '&shareid=' + res.id;
						
						hide_menu_list = [];
						construct('wx6173eb6085accfec',request_url, jsApiList, share_timeline_data, share_app_data,hide_menu_list);
						
						
						$('.zhuquan1').fadeOut( function (){
							if($('.keshi.hover').attr('index') == '1') Animate_diannao();
							if($('.keshi.hover').attr('index') == '2') Animate_zhongwen();
							if($('.keshi.hover').attr('index') == '3') Animate_huaxue();
							if($('.keshi.hover').attr('index') == '4') Animate_yinyue();
						});
		
			
					}else
					{
						alert('提交失败!')	
					}
			}
		})
		



		
		
		
		
		
		
		
		
		
		
		
});


		///zqqqqqqqqqqqqqqqqqqqqqqqqqqq
		
	/*
		loadingSprite.start();
		$('#loading').fadeIn();
		$('.loading-p').hide();
		$('.jiamining').show();
		
		$('.zhuquan1').fadeOut( function (){
			Animate_huaxue();
		})
		
		*/
		
		
		
		
		
window.POPBOXTIMER = null;

function Animate_diannao (){
	
	$('.sound-on , .gamereturn').removeClass('huaxue zhongwen')
	$('.zhuquan2').show();
	$('.z2-maintxt').html(window.USERTXT)
	var L1 = [];
	
	for ( var i = 1 ; i< 32 ; i++ ) {
		var JPG = i > 15 ? '.png' : '.jpg';
		L1.push (IMG_PATH + 'diannao/1/'+ i + JPG);	
	};
	
	
	var L2 = [];
	
	for ( var i = 1 ; i< 23 ; i++ ) {
		L2.push (IMG_PATH + 'diannao/2/'+ i + '.jpg');	
	};
	
	var L3 = [];
	
	for ( var i = 1 ; i< 43 ; i++ ) {
		L3.push (IMG_PATH + 'diannao/3/'+ i + '.png');	
	};
	
	
	var _loadSrc = [];
	_loadSrc = _loadSrc.concat(L1);
	_loadSrc = _loadSrc.concat(L2);
	_loadSrc = _loadSrc.concat(L3);
	var sprite1 , sprite2 , sprite3;
	
	$('.canvas-text').hide();
	$('.zhiwen-ico').hide();
	$('.zhiwen').hide();
	$('.z2-canvasbg2').removeAttr('style')
	$('.zhuquan2 .btn1').hide()
	$('.zhuquan2 .btn2').hide()
	$('.zhuquan2 .txt0').hide()
	ZQ.tool.imgLoader ({
			img : _loadSrc ,
			onloading : function (p) {
				
			},
			callback : function () {
				sprite1 = new ZQ.fx.spriteCanvas ('x1', 50, false );
	
				sprite1.initDraw ( document.querySelector('.z2-canvasbg2') , L1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
						$('#loading , .zhuquan0').fadeOut();
						if(loadingSprite2) loadingSprite2.stop();
						
						
						this.draw ( result  ,function ( s ) {
							
							
							if(s == 15) {
								$('.z2-canvasbg2').height('auto').css({ top : '50%' , marginTop : -504})
								$('.z2-canvasbg1').from({ transform : 'scale(1.2)'})
							};
							if(s == 29) {
									$('.canvas-text').fadeIn();
									 var canvas = document.getElementById("canvas-01"),
										context = canvas.getContext("2d");
							
									var fontSize = 26,
										listText = "01".split(""),
										column, row,
										listColumn = [];
							
									function draw() {
										//画背景
										context.fillStyle = "rgba(11,11,11,0.05)";
										context.fillRect(0, 0, canvas.width, canvas.height);
										context.save();
							
										//画代码
										context.restore();
										context.font = "bold " + fontSize + "px Arial";
										context.fillStyle = "#b5418c";
										for (var i = 0; i < column; i++) {
											fontSize = 25.5
											if (Math.random() > 0.5) {
												var str = listText[parseInt(Math.random() * listText.length)];
												context.fillText(str, i * fontSize, listColumn[i] * fontSize);
												listColumn[i] += 1;
												if (listColumn[i] >= row) {
													listColumn[i] = 0;
												}
											}
										}
									}
							
									function resize() {
										canvas.width = 400;
										canvas.height = 400;
							
										column = canvas.width / fontSize,
										row = canvas.height / fontSize;
							
										for (var i = 0; i < column; i++) {
											listColumn[i] = 1;
										}
									};
									
									
							
									window.addEventListener("resize", resize);
							
									canvas.addEventListener("mousedown", function () {
										clearInterval(timer);
										timer = setInterval(draw, 20);
									});
							
									canvas.addEventListener("mouseup", function () {
										clearInterval(timer);
										timer = setInterval(draw, 40);
									});
							
									resize();
									var timer = setInterval(draw, 40);
									
									
									
									
									
									
									
									
									
									
									/////////////////zqqqqqq////////////////////
									
									
									if(IsShareUser)
									{
										$('.zhiwen-ico').show().from({ width : 0 , opacity : 0 , delay : 4.5} , .5)
										$('.zhiwen').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : 4} , .5)
									}else
									{
										AllFn ();
										$('.zhuquan2 .btn1').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : 2} , .5);
										$('.zhuquan2 .txt0').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : 1.5} , .5);
										$('.zhuquan2 .btn2').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : 2.5} , .5);		
									}
									
									
			
							};
						});
						
					}	
				} , false);
				
				
				sprite2 = new ZQ.fx.spriteCanvas ('x2', 50, true )
				sprite2.initDraw ( document.querySelector('.z2-canvasbg1') , L2, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
						this.draw ( result  ,function ( s ) {});
						
					}	
				} , false);	
				
				
				
				
				
				$('.zhiwen').bind('touchend' , function (){
					
						
						$(this).fadeOut();
						
						$('.z2-canvasbg2 , .canvas-text').to({ transform : 'scale(.8)' , opacity : .7} , .5);
						
						
						
						
						setTimeout( function (){
							
								$('.z2-canvasbg3').show();
						
								sprite3 = new ZQ.fx.spriteCanvas ('x3', 50, false )
								sprite3.initDraw ( document.querySelector('.z2-canvasbg3') , L3, function ( p , result ) {
									
									//document.title = p.jd
									if ( p.redayState == 'complete' ) {
										
										this.draw ( result  ,function ( s ) {
											
											if(s == 25)
											{
												$('.z2-maintxt').fadeIn();
												
												
												if(IsShareUser)
												{
													
													$('.zhuquan2 .btn3').show().from({ transform : 'scale(1.5)' , opacity : 0 } , .5);
												}else{
														
													$('.zhuquan2 .btn1').show().from({ transform : 'scale(1.5)' , opacity : 0 } , .5);
													$('.zhuquan2 .txt0').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : 1.5} , .5);
													$('.zhuquan2 .btn2').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : .5} , .5);		
												}
													
											}
										
										} , function (){
												
										});
										
									}	
								} , false);		
						
						} , 500)
					
						
				});
			}	
	}) ;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
};


function Animate_zhongwen (){
	
	
	
	$('.sound-on , .gamereturn').removeClass('huaxue zhongwen')
	$('.sound-on , .gamereturn').addClass('zhongwen')
	
	
	$('.zhuquan3 .btn1').hide()
	$('.zhuquan3 .txt0').hide()//.from({ transform : 'scale(1.2)' , opacity : 0}, .5)
	$('.zhuquan3 .btn2').hide()//.from({ transform : 'scale(1.2)' , opacity : 0 , delay : .2}, .5)	
	$('.shi-txt').html('')
	$('.z3-3').removeAttr('style');
	
	
	
	
	
	
	
	var SHI = [['全唐诗续拾',
				'君生我未生，我生君已老。',
				'君恨我生迟，我恨君生早。', '君生我未生，我生君已老。',
				'恨不生同时，日日与君好。', '我生君未生，君生我已老。',
				'我离君天涯，君隔我海角。', '我生君未生，君生我已老。',
				'化蝶去寻花，夜夜栖芳草。']
				,
				['卜算子 答施',
				'相思似海深，旧事如天远。',
				'泪滴千千万万行，',
				'更使人、愁肠断。',
				'要见无因见，拚了终难拚。',
				'若是前生未有缘，',
				'待重结、来生愿。']
				,
				['呼啸山庄',
				'你如果藏在我的心里，',
				'就不难把你找到。',
				'但是如果你藏到你的壳里去，',
				'那么任何人也找你不到的。',
				'你是你所爱的人的奴隶，',
				'因为你爱了他；', '你也是爱你的人的奴隶，', '因为他爱了你。']];
				
				
				
	
	
	
	var userTxt = window.USERTXT || '我爱你';
	var b = SHI[window.Category || 0];
	
	
	var size = 0;
	var _size = 0;
	
	for( var i = 1 ; i < b.length ; i ++)
	{
		size += b[i].length;
	};
	
	var JG = Math.ceil(size/ ( userTxt.length +  GetRandomNum (1,4) ));
	
	
	
	var HTML = '';
	
	for( var i = 1 ; i < b.length ; i ++)
	{
	
		for( var j = 0 ; j < b[i].length ; j ++)	
		{
			
			var STYLE = 'right:' + (40 + i * 40) + 'px;top:' + (30 + j * 40)+ 'px;'
			
			var t = userTxt.charAt(Math.floor((_size + j) / JG) - 1);
			if((_size + j) % JG == 0 && !(j == 0 && i == 0) && t !== '')
			{
				
				
				HTML += '<span class="usertxt" style="' + STYLE + '">' + t  + '</span>'	
			}else
			{
				HTML += '<span style="' + STYLE + '">' + b[i].charAt(j) + '</span>'	
			}
			
			
			var _index = i	
		}
		
		_size += b[i].length;
	};
	
	

	setTimeout( function (){
						$('.shi-txt').html('<span class="shi-title">' +b[0] + '</span>')
						$('.shi-txt').append(HTML);
						
						
						$('.shi-txt span').each( function ( v ){
							$(this).from({   opacity : 0 , delay : v * .05} , .5)	
						});
						
						
						
						if(IsShareUser)
						{
							setTimeout( function (){
								var _timer = null;
						
						
								$('.zhuquan3 .thispop').fadeIn();
								
								
			
			
			
								function _showpop (){
									window.POPBOXTIMER = setTimeout ( function () {
										$('.zhuquan3 .thispop').fadeIn ();	
										
										
										window.POPBOXTIMER = setTimeout( function (){
											$('.zhuquan3 .thispop').fadeOut ();	
											
											_showpop ();
												
										} , 3000)
										
									} ,5000);	
								}
								
								
								
								window.POPBOXTIMER = setTimeout( function (){
									$('.zhuquan3 .thispop').fadeOut ();	
									
									
									_showpop ();
									
										
								} , 3000)
													
								
								
								window.CHITIMER = setTimeout( function (){
									AWARD_FN ();	
								} , 6000)
								
								
								
								function AWARD_FN (){
									clearTimeout(window.CHITIMER);
									$('.zhuquan3 .thispop').fadeOut();
									TXTIN ();
									
									clearTimeout(window.POPBOXTIMER);
								};
								
								function CHUI_init() {
									function a(e) {
										e = e.beta;
										-5 > e && -30 < e && k && (window.removeEventListener("deviceorientation", a), setTimeout(d, 800));
									}
									function d() {
										AWARD_FN ()
										
										cleanFun = e;
									}
									function e() {
									   
									}
									var k = !1;
									(function() {
										window.DeviceOrientationEvent ? window.addEventListener("deviceorientation", a, !1) : setTimeout(d, 2e3);
										setTimeout(function() {
											k = !0;
										}, 1200);
									})();
									}
								CHUI_init();
								
								
								
								
						} , ($('.shi-txt span').size() * .05) * 1000 + 500)
						}else
						{
							setTimeout( function (){
								AllFn()
								$('.zhuquan3 .txt0').show().from({ transform : 'scale(1.2)' , opacity : 0}, .5)
								$('.zhuquan3 .btn1').show().from({ transform : 'scale(1.2)' , opacity : 0}, .5)
								$('.zhuquan3 .btn2').show().from({ transform : 'scale(1.2)' , opacity : 0 , delay : .2}, .5)	
							} , ($('.shi-txt span').size() * .05) * 1000 + 500)
													
													
						}
						
						
						
						function TXTIN (){
							$('.shi-txt span').not('.usertxt').each( function ( v ){
								$(this).to({   opacity : 0 , delay : GetRandomNum(0,4) * .2} , .5)		
							});
							
							
							setTimeout( function (){
								
								var _size = $('.shi-txt span.usertxt').size();
								for( var k = 0 ; k < _size ; k ++)
								{
									
									
									$('.shi-txt span.usertxt').eq(k).css('color' , '#000').to({ right : 170 + Math.floor(k / 10) * 40 , top : (30 + (k % 10) * 40) , delay : k * .05});
								};
								
								
								
								
								setTimeout( function (){
									$('.yinzhang').show().from({ transform : 'scale(2)'}, .5 , function (){
										
												if(IsShareUser)
												{
													
													$('.zhuquan3 .btn3').show().from({ transform : 'scale(1.2)' , opacity : 0}, .5)
												}else{
												
								$('.zhuquan3 .txt0').show().from({ transform : 'scale(1.2)' , opacity : 0}, .5)
													$('.zhuquan3 .btn1').show().from({ transform : 'scale(1.2)' , opacity : 0}, .5)
													$('.zhuquan3 .btn2').show().from({ transform : 'scale(1.2)' , opacity : 0 , delay : .2}, .5)
												}
												
												
										
									})
								} , (_size * .05 + 1.2) * 1000)
								
								
							} , 1200)
							
							
							
							
							
							
						};
						
						
						
						
						
						
					
						
						
						
						////////////////////////////////////////////////////////////
						//AWARD_FN ();
					} , 4000)
					
					



	$('.zhuquan3').show();
	var L1 = [];
	
	for ( var i = 1 ; i< 78 ; i++ ) {		//116 75
		L1.push (IMG_PATH + 'zhongwen/1/'+ i + '.png?1');	
	};
	
	var _loadSrc = [];
	_loadSrc = _loadSrc.concat(L1);
	var sprite1 ;
	
	
	ZQ.tool.imgLoader ({
			img : _loadSrc ,
			onloading : function (p) {
				
			},
			callback : function () {
				
				if($(window).height() < 850)
				{
					
				$('.z3-3').to({ transform : 'scale(0.6) translate(350px, 310px)' , delay : 2} , 1 ,function (){})
				}else
				{
				$('.z3-3').to({ transform : 'scale(.8) translate(190px, 210px)' , delay : 2} , 1 ,function (){})
						
				}
				
				
				
				sprite1 = new ZQ.fx.spriteCanvas ('x4', 50, false );
	
				sprite1.initDraw ( document.querySelector('.z3-canvas') , L1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
						$('#loading , .zhuquan0').fadeOut();
						if(loadingSprite2) loadingSprite2.stop();
						this.draw ( result  ,function ( s ) {
							
								
						} , function (){
							
							function _a(){
								sprite1.start(35 , '' ,_a);	//52 32
							}
							_a();
							
							
						});
						
					}	
				} , false);
				
				
				
				
			}	
	}) ;
	
	
	
	
	
	
	
	
	
}
		


function Animate_huaxue (){
	
	$('.sound-on , .gamereturn').removeClass('huaxue zhongwen')
	$('.sound-on , .gamereturn').addClass('huaxue')
	
	$('.zq4-1').show();
	$('.zq4-2').hide();
	$('.s4-3').html('');
								
								
	var textArr = window.textArr || [['我','w']];
		
	$('.zhuquan4').show();
	var L1 = [];
	
	
	for ( var i = 1 ; i< 4 ; i++ ) {
		L1.push (IMG_PATH + 'huaxue/1/'+ i + '.jpg');	
	};
	for ( var i = 1 ; i< 4 ; i++ ) {
		L1.push (IMG_PATH + 'huaxue/1/'+ i + '.jpg');	
	};
	for ( var i = 1 ; i< 4 ; i++ ) {
		L1.push (IMG_PATH + 'huaxue/1/'+ i + '.jpg');	
	};
	for ( var i = 1 ; i< 4 ; i++ ) {
		L1.push (IMG_PATH + 'huaxue/1/'+ i + '.jpg');	
	};
	for ( var i = 1 ; i< 27 ; i++ ) {
		L1.push (IMG_PATH + 'huaxue/1/'+ i + '.jpg');	
	};
	
	
	var L2 = [];
	
	for ( var i = 1 ; i< 26 ; i++ ) {
		L2.push (IMG_PATH + 'huaxue/2/'+ i + '.png');	
	};
	
	var _loadSrc = [];
	_loadSrc = _loadSrc.concat(L1);
	_loadSrc = _loadSrc.concat(L2);
	var sprite1 , sprite2 ;
	
	
	
	
	if(IsShareUser)
	{
			$('.s4-5 .txt0').hide();
			$('.zhuquan4 .btn3').show();
			$('.zhuquan4 .btn1').remove();
			$('.zhuquan4 .btn2').remove();
	}else{
		$('.s4-5 .txt0').show();
			$('.zhuquan4 .btn3').remove();
	}
	
	function creatBox () {
		var u3 = document.querySelector('.s4-3');
		for ( var i=0; i<28; i++ ) {
			var d = document.createElement('li'),
				d2 =document.createElement('div');
				d2.innerHTML =' <p class="s4-p"></p><p class="s4-z"></p>  ';
				var d3 =d2.cloneNode(true);
				d2.className = 'box_front show';	
				d3.className = 'box_back';
				d.appendChild(d2);
				d.appendChild(d3);
				u3.appendChild(d);
		}	
		
		var aLi = u3.children;
		var box_front = u3.querySelectorAll('.box_front');
		var box_back = u3.querySelectorAll('.box_back');
		
		
		
			
		
		
		for ( var i=0; i<28; i++ ) {
			box_front [i].children[0].innerHTML = box_back [i].children[0].innerHTML= Math.round(Math.random()*500);
			
			
			
			if ( textArr[i] &&  textArr[i][1] !== 'x') {
				
				
				
				box_front [i].children[1].innerHTML =  textArr[i][1].charAt(0).toUpperCase() + textArr[i][1].charAt(1);	
					
				box_back [i].children[1].innerHTML = textArr[i][0];			
				
				//alert(textArr[i][1].charAt(0).toUpperCase() + textArr[i][1].charAt(1))
				
			} else {
				if ( textArr[i]) {
					box_back [i].children[1].innerHTML = textArr[i][0];
				}else
				{
					box_back [i].classList.add('xin');		
				}
				box_front [i].classList.add('xin');
				
					
			}
			
		}
		
		for ( var i=0; i<28; i++ ) {
			$(aLi).eq(i).from( {transform : 'scale(1.5)' , opacity: 0, delay : Math.max(.3,Math.random()) } , .8 );	
		}
		
		
		
		
		
			if(IsShareUser)
			{
				setTimeout ( function () {
					$('.popbox-1').fadeIn ();	
					
					
					
					function _showpop (){
						window.POPBOXTIMER = setTimeout ( function () {
							$('.popbox-1').fadeIn ();	
							
							
							window.POPBOXTIMER = setTimeout( function (){
								$('.popbox-1').fadeOut ();	
								
								_showpop ();
									
							} , 3000)
							
						} ,5000);	
					}
					
					
					
					window.POPBOXTIMER = setTimeout( function (){
						$('.popbox-1').fadeOut ();	
						
						
						_showpop ();
						
							
					} , 3000)
					
					} ,4000);
					
						
				var init =false;
				
				
				$$('.s4-3 , .zhuquan4 .popbox').bind('swipeLeft' , function (){
						
						if(init) return;
						init = !init;
						clearTimeout(window.POPBOXTIMER);
						$('.zhuquan4 .popbox').hide();
					
						$('.s4-3 li').each( function ( i ){
							
							var _this = this;
							setTimeout( function (){
									$(_this).find('div:first').cssHide('flipOutX3602');
									$(_this).find('div:last').cssShow('flipInX3602');
							} , i * 10)
							
							
						});
							
				}).bind('swipeRight' , function (){
						if(!init) return;
						init = !init;
						clearTimeout(window.POPBOXTIMER);
						$('.zhuquan4 .popbox').hide();
							$('.s4-3 li').each( function ( i ){
									
									var _this = this;
									setTimeout( function (){
											$(_this).find('div:last').cssHide('flipOutX3602');
											$(_this).find('div:first').cssShow('flipInX3602');
									} , i * 10)
									
									
								});
							
				})
			
			
			
			
				
				$('.popbox').click ( function () {
					$(this).fadeOut( function () {	
						ready = true ;
					});	
				});
				
		
			}else
			{
				AllFn();	
			}
						
		
		
	
		
	
		
		
	}
	
	

	ZQ.tool.imgLoader ({
			img : _loadSrc ,
			onloading : function (p) {
				
			},
			callback : function () {
				sprite1 = new ZQ.fx.spriteCanvas ('x5', 100, false );
				sprite2 = new ZQ.fx.spriteCanvas ('x6' , 90 , true )
	
				sprite1.initDraw ( document.querySelector('.zq4-c') , L1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
						$('#loading , .zhuquan0').fadeOut();
						if(loadingSprite2) loadingSprite2.stop();
						this.draw ( result  ,function ( s ) {
					
						} , function () {
							
							setTimeout ( function () {
								
								
								
								$('.zq4-1').fadeOut (1000);	
								$('.zq4-2').fadeIn  (1000 , function (){
									creatBox ();	
								});	
								
								sprite2.initDraw ( document.querySelector('.zq4-c2') , L2, function ( p , result ) {
					
									//document.title = p.jd
									if ( p.redayState == 'complete' ) {
										sprite1.stop ();
										this.draw ( result  ,function ( s ) {});
										
									}	
								} , false);	
							} ,1500);
								
						});
						
					}	
				} , false);
				
				
			
			}	
	}) ;
	
	
	
}
		

function Animate_yinyue (){
	$('.zhuquan5 .txt0').hide();
	$('.sound-on , .gamereturn').removeClass('huaxue zhongwen')
	$('.z5-box').html('');
	
	
	$('.s4-5 .txt0').hide();
														
														
	
	var CODE = ['abc','def','ghi','jkl','mno','pqr','stuv','wxyz'];
	
	
	
	
		/*
		playList = [2]
		now = 0;
		if(musicLoaded) playMusic (playList);
	
	
	
	*/
	
	
	
	
	
	
	
	$('.zhuquan5').show();	
	
	var textArr = window.textArr || [['','']];
	var L1 = [];
	
	for ( var i = 1 ; i< 40 ; i++ ) {
		L1.push (IMG_PATH + 'yinyue/1/'+ i + '.jpg');	
	};
	
	var _loadSrc = [];
	_loadSrc = _loadSrc.concat(L1);
	var sprite1 ;
	
	
	ZQ.tool.imgLoader ({
			img : _loadSrc ,
			onloading : function (p) {
				
			},
			callback : function () {
				
			
			
			
		
				
				var pos = [ 
				[111, 11] ,
				[160 ,70] ,
				[233,90] ,
				[280,30] ,
				[344,90],
				[395,31],
				[440,90],
				[506,50],
				[560,110],
				[582,34],
				[22,262],
				[90,226],
				[158,245],
				[222,225],
				[288,244],
				[345,207],
				[407,245],
				[473,240],
				[534,275],
				[577,215]];

				
				var HTML = '';
				
				
				var animateD = ZQ.tool.prefixedSupport('animation-delay');
				
				
				var bb = Math.floor((10 - textArr.length / 2 ) / 2) 
				
				
				for( var j = 0 ; j < textArr.length ; j ++)
				{
					
					var _b = 0;
					if( j < textArr.length / 2)
					{
						_b = bb + j;
					}else
					{
						_b = bb + 10 + j - (textArr.length / 2)	
					}
						_b = Math.floor(_b);
					
					if(! pos[_b]) continue;
					
					
					var STYLE = 'left:' + pos[_b][0] + 'px;top:' + pos[_b][1] + 'px;';
					
					
					var Z = textArr[j][1].charAt(0).toUpperCase();
					var ZZ = textArr[j][1].charAt(0).toUpperCase();
					
					if(textArr[j][1] == 'x' || textArr[j][1] == ' ') {
						Z = '<img src="' + IMG_PATH + 'yinyue/xin.png" style="margin-top:18px" />';
					}
					
					
					
					
					
					if(window.Zhu._Android)
					{
						HTML += '<div class="z5x z5x1 az" style="' + STYLE +'">' +
							'<span class="zi" zimu="' + ZZ + '">' + Z + '</span>' +
							'</div>';
					}else
					{
						HTML += '<div class="z5x z5x1" style="' + STYLE +'">' +
							'<span class="z5x1-1 z5x1-1'+ GetRandomNum (1,3) +'"></span>' +
							'<span class="z5x1-2"></span>' +
							'<span class="zi" zimu="' + ZZ + '">' + Z + '</span>' +
							'</div>';	
					}
					
					
							
							
							
							
							
						
								
				}
				
				
				
				
			
				
				
				
				
				var Bstop = true;
				
				sprite1 = new ZQ.fx.spriteCanvas ('x', 120, false );
	
				sprite1.initDraw ( document.querySelector('.z5canvas') , L1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
						$('#loading , .zhuquan0').hide();
						if(loadingSprite2) loadingSprite2.stop();
						this.draw ( result  ,function ( s ) {
							
								
						} , function (){
							
							
							if(Bstop) {
								
								
								
								$('.z5-box').html(HTML)
								$('.z5-box div').each( function ( v ){
									$(this).from({   opacity : 0 , delay : v * .05} , .5)	
								});
								Bstop = false;
								
								
								
								
								
								
								$('.z5-box .z5x').bind('touchend' , function (){
									/*var m = $(this).find('.zi').attr('zimu').toLowerCase();
									
									var index = 0;
									for( var j = 0 ; j < CODE.length ; j ++)
									{
										if(CODE[j].indexOf(m) > -1)
										{
											index = j;
											break;	
										}	
									}
										now = 0;
										if(musicLoaded) playMusic ([index + 1]);*/
										
										
											
											
											var PLAYLIST = [];
											$('.z5-box .z5x').each( function (){
													var index = 0;
													var m = $(this).find('.zi').attr('zimu').toLowerCase();
													for( var j = 0 ; j < CODE.length ; j ++)
													{
														if(CODE[j].indexOf(m) > -1)
														{
															index = j;
															break;	
														}	
													};
													PLAYLIST.push(index + 1)
													
											})
											now = 0;
											$('#sound0')[0].pause();	
											$('.sound-on').addClass('sound-off');
											if(musicLoaded) playMusic (PLAYLIST , function (){
												$('#audio2')[0].play();
												
												
												
												
												
											});
									
											
											
											
										
								})
								
								if(IsShareUser)
												{
													
														$('.zhuquan5 .btn3').show().from({ transform : 'scale(1.5)' , opacity : 0 } , .5);
												}else{
														
														$('.zhuquan5 .txt0').show().from({ transform : 'scale(1.5)' , opacity : 0 } , .5);
														$('.zhuquan5 .btn1').show().from({ transform : 'scale(1.5)' , opacity : 0  , delay : .5} , .5);
														$('.zhuquan5 .btn2').show().from({ transform : 'scale(1.5)' , opacity : 0 , delay : .5} , .5);		
												}	
								
								if(IsShareUser)
								{
									setTimeout( function (){
								
										$('.zhuquan5 .thispop').fadeIn();
										function _showpop (){
											window.POPBOXTIMER = setTimeout ( function () {
												$('.zhuquan5 .thispop').fadeIn ();	
												
												
												window.POPBOXTIMER = setTimeout( function (){
													$('.zhuquan5 .thispop').fadeOut ();	
													
													_showpop ();
														
												} , 3000)
												
											} ,5000);	
										}
										
										
										
										window.POPBOXTIMER = setTimeout( function (){
											$('.zhuquan5 .thispop').fadeOut ();	
											
											
											_showpop ();
											
												
										} , 3000);
										
										
										
										
										
										
										
										var b = new ZQ.gravitySensor();
										
										
										
										setTimeout( function (){
												b.listenShake (function () {
											
											
										
									
											
											
											$('.zhuquan5 .thispop').hide();
											clearTimeout(window.POPBOXTIMER);
											$('.z5-box .z5x .zi').each( function ( i ){
												
												var _this = this;
												setTimeout( function (){
													$(_this).text(textArr[i][0])
												} , i * 200)	
											})
											
											b.stopListenShake ();	
										} , 130)	
										} , 2000)
										
										
										
											var PLAYLIST = [];
											$('.z5-box .z5x').each( function (){
													var index = 0;
													var m = $(this).find('.zi').attr('zimu').toLowerCase();
													for( var j = 0 ; j < CODE.length ; j ++)
													{
														if(CODE[j].indexOf(m) > -1)
														{
															index = j;
															break;	
														}	
													};
													PLAYLIST.push(index + 1)
													
											})
											now = 0;
											$('#sound0')[0].pause();	
											$('.sound-on').addClass('sound-off');
											if(musicLoaded) playMusic (PLAYLIST , function (){
												$('#audio2')[0].play();
												
												
												
												
												
												
											});
											
										
										
													
								} , 3000)
								}else
								{
									
										AllFn();	
									
									
									var PLAYLIST = [];
											$('.z5-box .z5x').each( function (){
													var index = 0;
													var m = $(this).find('.zi').attr('zimu').toLowerCase();
													for( var j = 0 ; j < CODE.length ; j ++)
													{
														if(CODE[j].indexOf(m) > -1)
														{
															index = j;
															break;	
														}	
													};
													PLAYLIST.push(index + 1)
													
											})
											now = 0;
											$('#sound0')[0].pause();	
											$('.sound-on').addClass('sound-off');
											if(musicLoaded) playMusic (PLAYLIST , function (){
												$('#audio2')[0].play();
												
												
												
												
												
												
											});	
											
																			
								}
								
								
								
								
							
								
								
								
								
								
								
								
								
								
								
							};
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							function _a(){
								sprite1.start(32 , '' ,_a);	
							}
							_a();
							
							
						});
						
					}	
				} , false);
				
				
				
				
			}	
	}) ;
}

       

function AllFn (){
	$('.gamereturn').show();
	
	
}

$$('.gamereturn').tap( function (){
	ZQ.fx.stopAllSprite();	
	$(this).fadeOut();
	$('.zhuquan1').fadeIn().siblings('.box-step:visible').fadeOut();
	
})
  
  


	function MenuShareTimeLine (){
		
		 /* wx.hideMenuItems({
			  menuList: [
				'menuItem:share:appMessage'
			  ]
			});
			*/
			 wx.showMenuItems({
			  menuList: [
				'menuItem:share:timeline'
			  ]
			});
			$('.chakanbtn').bind('touchend' , function ( ev ){
				ev.preventDefault();
				ev.stopPropagation();
				$('.guizhebox').show();
										
if($('.zhuquan2').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系高调秀出【8折入口BTN】');	
if($('.zhuquan2').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系单独告白【8折入口BTN】');	

if($('.zhuquan3').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系高调秀出【8折入口BTN】');	
if($('.zhuquan3').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系单独告白【8折入口BTN】');	

if($('.zhuquan4').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系高调秀出【8折入口BTN】');	
if($('.zhuquan4').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系单独告白【8折入口BTN】');
	
if($('.zhuquan5').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系高调秀出【8折入口BTN】');	
if($('.zhuquan6').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系单独告白【8折入口BTN】');	
	
	
			});
			
		

	};

	function MenuShareAppMessage (){
		  wx.showMenuItems({
			  menuList: [
				'menuItem:share:appMessage'
			  ]
			});
			
			 wx.hideMenuItems({
			  menuList: [
				'menuItem:share:timeline'
			  ]
			});
			
			$('.chakanbtn').bind('touchend' , function ( ev ){
				ev.stopPropagation();
				ev.preventDefault();
				$('.guizhebox').show();
				
					
if($('.zhuquan2').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系高调秀出【8折入口BTN】');	
if($('.zhuquan2').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系单独告白【8折入口BTN】');	

if($('.zhuquan3').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系高调秀出【8折入口BTN】');	
if($('.zhuquan3').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系单独告白【8折入口BTN】');	

if($('.zhuquan4').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系高调秀出【8折入口BTN】');	
if($('.zhuquan4').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系单独告白【8折入口BTN】');
	
if($('.zhuquan5').is(':visible') && $('.sharebox').attr('type') == '1' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系高调秀出【8折入口BTN】');	
if($('.zhuquan6').is(':visible') && $('.sharebox').attr('type') == '2' && window._tag)   _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系单独告白【8折入口BTN】');	
	
	
					
			});
				
	};





$('.btn3').click( function (){
	

	
	if($('.zhuquan2').is(':visible')) {

		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系【我也要告白】');
		
		
			
	}
	
	if($('.zhuquan3').is(':visible')) {

		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系【我也要告白】');
			
	}
	
	if($('.zhuquan4').is(':visible')) {

		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系【我也要告白】');
			
	}
	
	if($('.zhuquan5').is(':visible')) {

		if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系【我也要告白】');
			
	}
	
	
	setTimeout( function (){
			location.href = INDEX_URL
	} , 300)	
})







	
	




	$$('.zhuquan2 .btn1').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系【高调示爱】');
		$('.sharebox').attr('type' , 1).fadeIn().html('<img src="' + IMG_PATH + 'diannao/share1.png" style="right:80px" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'diannao/n1.png" class="chakanbtn" /></div>')
		MenuShareTimeLine ();
	});
	$$('.zhuquan2 .btn2').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '计算机系【悄悄告白】');
		
		$('.sharebox').attr('type' , 2).fadeIn().html('<img src="' + IMG_PATH + 'diannao/share2.png"  style="right:80px" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'diannao/n1.png" class="chakanbtn" /></div>')
		MenuShareAppMessage();
	});



	$$('.zhuquan3 .btn1').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系【高调示爱】');
		
		$('.sharebox').attr('type' , 1).fadeIn().html('<img src="' + IMG_PATH + 'zhongwen/share2.png" style="left:70px;top:0;bottom:0;right:0;margin:auto;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'zhongwen/n1.png" class="chakanbtn" /></div>');
		MenuShareTimeLine ();
	});
	$$('.zhuquan3 .btn2').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '中文系【悄悄告白】');
		
		$('.sharebox').attr('type' , 2).fadeIn().html('<img src="' + IMG_PATH + 'zhongwen/share1.png" style="left:70px;top:0;bottom:0;right:0;margin:auto;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'zhongwen/n1.png" class="chakanbtn" /></div>');
		MenuShareAppMessage();
		
	});
	
	$$('.zhuquan4 .btn1').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系【高调示爱】');
		
		$('.sharebox').attr('type' , 1).fadeIn().html('<img src="' + IMG_PATH + 'huaxue/f2.png" style="left:0px;top:100px;right:0;margin:auto;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'huaxue/n1.png" class="chakanbtn" /></div>');
		MenuShareTimeLine ();
	});
	$$('.zhuquan4 .btn2').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '化学系【悄悄告白】');
		
		$('.sharebox').attr('type' , 2).fadeIn().html('<img src="' + IMG_PATH + 'huaxue/f3.png"  style="left:0px;top:100px;right:0;margin:auto;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'huaxue/n1.png" class="chakanbtn" /></div>');
		MenuShareAppMessage();
		
	});
	

	$$('.zhuquan5 .btn1').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系【高调示爱】');
		
		$('.sharebox').attr('type' , 1).fadeIn().html('<img src="' + IMG_PATH + 'yinyue/pop1.png"   style="left:0px;top: 50%;right:0;margin:auto;margin-top: -430px;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'yinyue/n1.png" class="chakanbtn" /></div>');
		MenuShareTimeLine ();
	});
	$$('.zhuquan5 .btn2').bind('touchend', function (){
if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'click', 'wt.msg', '音乐系【悄悄告白】');
		
		$('.sharebox').attr('type' , 2).fadeIn().html('<img src="' + IMG_PATH + 'yinyue/pop2.png"   style="left:0px;top: 50%;;right:0;margin:auto;margin-top: -430px;" /><div><img src="' + AD_IMG + '" class="bazhetxt" /><img src="' + IMG_PATH + 'yinyue/n1.png" class="chakanbtn" /></div>');
		MenuShareAppMessage();
		
	});
	
          
	


		
		
		
	
	var playList = [
		1,1,5,5,6,6,5,4,4,3,3,2,2,1,
		5,5,4,4,3,3,2,5,5,4,4,3,3,2,
		1,1,5,5,6,6,5,4,4,3,3,2,2,1
	];
	
	
	
	function setMedia ( num ) {
		var s;
		switch ( num ) {
			case 0 :
				s ='none';
				break;	
			case 1 :
				s ='do';
				break;	
			case 2 :
				s ='re';
				break;
			case 3 :
				s ='mi';
				break;	
			case 4 :
				s ='fa';
				break;	
			case 5 :
				s ='so';
				break;	
			case 6 :
				s ='la';
				break;	
			case 7 :
				s ='si';
				break;
			case 8 :
				s ='da';
				break;		
		}
		return s;
	}
	var m1 = $('#audio1')[0];	
	var now = 0, timeout= null	
	function  playMusic(playList , fn) {
		var ss2 = {
			none: [0,0],
			do: [2500, 550],
			re: [3100, 550],
			mi: [3700, 550],
			fa: [4200, 550],
			so: [4800, 550],
			la: [5300, 550],
			si: [5900, 550],
			da: [6400, 550]
		}
		clearTimeout (timeout);
		
		var length = m1.duration;
		
		
		
		m1.currentTime  =  ss2[ setMedia ( playList[now] )] [0]  / 1000 ;	
		
		
		
		m1.play();	
		timeout = setTimeout (function () {
			if ( ++ now == playList.length ) {
				m1.pause();
				
				if(fn) fn();	
				return;	
			}
			playMusic( playList , fn);
	
		} , ss2[ setMedia ( playList[now] )] [1] );
	}
	
		
	
	var musicLoaded = true;
	function handelLoad () {
		
			m1.play();
			m1.pause();
			function h () {musicLoaded = true; document.body.removeEventListener(ZQ.event.eventStart , handelLoad , false);}
			m1.addEventListener ('loadeddata' ,h , false) ;	 
			m1.addEventListener ('load' ,h , false) ;
			
	};

	//document.body.addEventListener(ZQ.event.eventStart , handelLoad , false);
		
	
	
	

if(IsShareUser)
{
	
	$('.zhuquan0').show();
	$('.zhuquan1').remove();
	var loadingSprite2 = new ZQ.fx.spriteBackground ('load' , 640, 400 ,'v');
	
					
var loadingL1 = [];
	
for ( var i = 1 ; i< 24 ; i++ ) {		//116 75
		loadingL1.push (IMG_PATH + 'loading/'+ i + '.png?1');	
};

var loadingSprite2 = new ZQ.fx.spriteCanvas ('x1', 30, true );

loadingSprite2.initDraw ( document.querySelector('.loading-sprite2') , loadingL1, function ( p , result ) {
					
					//document.title = p.jd
					if ( p.redayState == 'complete' ) {
						
					
						this.draw ( result  ,function ( s ) {} , function (){});
						
					}	
} , false);


	/*
	loadingSprite2.play ($('.loading-sprite2')[0]  , [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22] ,  true , 30 , true );
	*/
	
	
	$.ajax({
		type:'GET',
		url:GET_URL,
		data:{
			id : IsShareUser * 1
		},
		dataType:"json",
		success: function ( res ){
			
			
				//{"result":"100","data":{"id":"36","content":"\u6211\u771f\u7684\u559c\u6b22\u4f60","content_pinyin":"wo zhen de xi huan ni ","content_binary":"1100111011010010 1101010111100110 1011010111000100 1100111110110010 1011101110110110 1100010011100011 ","department":"4","category":"1"}}
				if( res.result == 100)
				{
					$('.share-txt').addClass('b' + res.data.department);
					
					
					
						window.textArr = [];
						
						window.USERTXT = res.data.content;
	
						for( var j = 0 ; j <  window.USERTXT.length ; j ++)
						{
							window.textArr.push([window.USERTXT.charAt(j) , res.data.content_pinyin.split(' ')[j]]);
						}
						
						
						
						
	
						window.Category = 	res.data.category * 1 - 1;
						window.department = res.data.department;
						
						
						
						
						
						
						
	ZQ.tool.imgLoader ({
			img : loadStatr ,
			onloading : function (p) {
				
			},
			callback : function () {
				
				ZQ.tool.imgLoader ({
									img : loadSrc ,
									onloading : function (p) {
										
										var b =  Math.min(parseInt(p) , 99).toString();
										
										if(b.length == 1) {
											$('.loading-p span:last')[0].className = 's' + b;
										}else
										{
											$('.loading-p span:first')[0].className = 's' + b.charAt(0);
											$('.loading-p span:last')[0].className = 's' + b.charAt(1);	
										}
											
										
										
									},
									callback : function () {
										
										
										loadingSprite.stop();
										$('#loading').fadeOut();
										
										setTimeout( function (){
								if(window.department == '1') Animate_diannao();
								if(window.department == '2') Animate_zhongwen();
								if(window.department == '3') Animate_huaxue();
								if(window.department == '4') Animate_yinyue();
							} , 2000)	
										
										
							
										var images = document.querySelectorAll('img');
										for ( var i = 0 ; i < images.length ; i++ ) {
											if(images [i].src) continue;
											
											images [i].src = $(images [i]).attr('data-src');
											images [i].removeAttribute ('data-src');	
										};
										
										
										
										
										
										
										
										
										
										
										
										
										
										
										
									
										
												
									}	
								}) ;	
					
		}	
}) ;
		

					
						
								
							
					
					
					
		
				}else
				{
					location.href = INDEX_URL;
				};
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
		}
	})	
}else
{
	$('.zhuquan0').remove();	
}



	
	
		
var loadStatr = [IMG_PATH + '1234567890.png',IMG_PATH + 'bj.jpg',IMG_PATH + 'logo.png',IMG_PATH + 'load-1.png'];

loadStatr = loadStatr.concat(loadingL1);

var loadSrc = [];

$('img').each( function (){
	
	if(!$(this).attr('data-src')) return;
	loadSrc.push($(this).attr('data-src'));
		
});


loadSrc.push('../addons/hx_lovedecipher/template/mobile/style/sound/2.mp3');
loadSrc.push(IMG_PATH + 'bj.jpg');
loadSrc.push(IMG_PATH + 'loading.gif');
loadSrc.push(IMG_PATH + 'loading.png');
loadSrc.push(IMG_PATH + 'loading.png');
loadSrc.push(IMG_PATH + 'load-1.png');
loadSrc.push(IMG_PATH + '1234567890.png');
loadSrc.push(IMG_PATH + 'bj.jpg');
loadSrc.push(IMG_PATH + 'btn1.png');
loadSrc.push(IMG_PATH + 'btn2.png');
loadSrc.push(IMG_PATH + 'btn3.png');
loadSrc.push(IMG_PATH + '1-2-1.png');
loadSrc.push(IMG_PATH + '1-2-2.png');
loadSrc.push(IMG_PATH + 'keshi.png');
loadSrc.push(IMG_PATH + 'keshi_h.png');
loadSrc.push(IMG_PATH + 'diannao/1/1.jpg');
loadSrc.push(IMG_PATH + 'diannao/2.png');
loadSrc.push(IMG_PATH + 'diannao/1.png');
loadSrc.push(IMG_PATH + 'btn4.png');
loadSrc.push(IMG_PATH + 'btn4.png');
loadSrc.push(IMG_PATH + 'btn5.png');
loadSrc.push(IMG_PATH + 'zhongwen/bj.jpg');
loadSrc.push(IMG_PATH + 'zhongwen/1.png');
loadSrc.push(IMG_PATH + 'zhongwen/2.png');
loadSrc.push(IMG_PATH + 'zhongwen/pop.png');
loadSrc.push(IMG_PATH + 'zhongwen/3.png');
loadSrc.push(IMG_PATH + 'zhongwen/3.png');
loadSrc.push(IMG_PATH + 'zhongwen/5.png');
loadSrc.push(IMG_PATH + 'huaxue/hxbg.jpg');
loadSrc.push(IMG_PATH + 'huaxue/hx2.png');
loadSrc.push(IMG_PATH + 'huaxue/hx2.png');
loadSrc.push(IMG_PATH + 'huaxue/xin.png');
loadSrc.push(IMG_PATH + 'huaxue/xin.png');
loadSrc.push(IMG_PATH + 'yinyue/btn2.png');
loadSrc.push(IMG_PATH + 'yinyue/btn3.png');
loadSrc.push(IMG_PATH + 'yinyue/btn2.png');
loadSrc.push(IMG_PATH + 'yinyue/pop.png');
loadSrc.push(IMG_PATH + 'share/bj.png');
loadSrc.push(IMG_PATH + 'share/w1-1.png');
loadSrc.push(IMG_PATH + 'share/w1-2.png');
loadSrc.push(IMG_PATH + 'share/w1-3.png');
loadSrc.push(IMG_PATH + 'share/w1-4.png');

if(!IsShareUser)
{
	if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'PV', 'wt.msg', '网站首页PV/UV');

	
	var L1 = [];
	for ( var i = 1 ; i< 40 ; i++ ) {
		L1.push (IMG_PATH + 'index/'+ i + '.png');	
	};
	
	
	loadSrc = loadSrc.concat(L1);
	var sprite1 ;
	
				
			
			
				
				
				
				
			
	
	
	ZQ.tool.imgLoader ({
			img : loadStatr ,
			onloading : function (p) {
				
			},
			callback : function () {
				
				ZQ.tool.imgLoader ({
									img : loadSrc ,
									onloading : function (p) {
										
										var b =  Math.min(parseInt(p) , 99).toString();
										
										if(b.length == 1) {
											$('.loading-p span:last')[0].className = 's' + b;
										}else
										{
											$('.loading-p span:first')[0].className = 's' + b.charAt(0);
											$('.loading-p span:last')[0].className = 's' + b.charAt(1);	
										}
											
										
										
									},
									callback : function () {
										
										
										
										
										
										
									
										
										loadingSprite.stop();
										$('#loading').fadeOut();
										
										
										sprite1 = new ZQ.fx.spriteCanvas ('x7', 70, false );
										sprite1.initDraw ( document.querySelector('.indexcanvas') , L1, function ( p , result ) {
											
											//document.title = p.jd
											if ( p.redayState == 'complete' ) {
												
											//	$('#loading , .zhuquan0').fadeOut();
												if(loadingSprite2) loadingSprite2.stop();
												this.draw ( result  ,function ( s ) {
													
														
												} , function (){
													
													
													
													
													
													
													function _a(){
														sprite1.start(24 , '' ,_a);	
													}
													_a();
													
													
												});
												
											}	
										} , false);
										
										
							
										var images = document.querySelectorAll('img');
										for ( var i = 0 ; i < images.length ; i++ ) {
											if(images [i].src) continue;
											
											images [i].src = $(images [i]).attr('data-src');
											images [i].removeAttribute ('data-src');	
										};
										
										
										
										
										
										
										
										
										
										
										
										
										
										
										
									
										
												
									}	
								}) ;	
					
		}	
}) ;
		
}else
{



if (window._tag)  _tag.dcsMultiTrack ('wt.event', 'PV', 'wt.msg', '表白接受者页面PV/UV');



	
}


	if($(window).height() < 850)
	{
		$('.step-box3').css('transform' , 'scale(.9)')
		$('.z5canvas').css('transform' , 'scaleY(0.9) translate(0px, -130px)')
		$('.z5-box').css('top' , '7%')
		$('.z2-canvasbg2 , .canvas-text').css('top' , '45%').css('marginTop' , -160)
		$('.z2-maintxt').css('top' , 290);
		$('.z2-canvasbg3').css('top' , -90);
		$('.s4-5').css('bottom' , '3%')
	}
	
	$('.zhuquan0').css('z-index' , 10)
	
	
	
	$('.sound-on').bind('touchstart' , function ( ev ){
			ev.preventDefault();
			ev.stopPropagation();
				if($(this).hasClass('sound-off'))
				{
					localStorage.setItem('sound' , 1)	
					
					$(this).removeClass('sound-off');
					 $('#sound0')[0].play();
					return;
				}
				$(this).addClass('sound-off');
				$('#sound0')[0].pause();
				localStorage.setItem('sound' , 2)	
				
				
		});
		
		
		
		if(!localStorage.getItem('sound'))
		{
			$('body').one('touchstart' , function (){
				if(localStorage.getItem('sound') == '2') return;
				
				$('#sound0')[0].play();
				localStorage.setItem('sound' , 1)	
			})	;
        	
		}else
		{
			document.body.addEventListener(ZQ.event.eventStart , handelLoad , false);	
		}
		
		
	if(localStorage.getItem('sound') == '1' || !localStorage.getItem('sound'))
	{
		$('#sound0')[0].play();	
	}else
	{
		$('.sound-on').addClass('sound-off');
	};