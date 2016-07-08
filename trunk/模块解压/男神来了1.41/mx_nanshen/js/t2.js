var load_img = [];
load_img.push( '../addons/mx_nanshen/images/area-input.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-1.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-2.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-3.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-4.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-5.png' );
load_img.push( '../addons/mx_nanshen/images/arrow-6.png' );
load_img.push( '../addons/mx_nanshen/images/bar-bottom.png' );
load_img.push( '../addons/mx_nanshen/images/bg-download.jpg' );
load_img.push( '../addons/mx_nanshen/images/btn-1.png' );
load_img.push( '../addons/mx_nanshen/images/btn-2.png' );
load_img.push( '../addons/mx_nanshen/images/btn-3.png' );
load_img.push( '../addons/mx_nanshen/images/dot.png' );
load_img.push( '../addons/mx_nanshen/images/emo-1.png' );
load_img.push( '../addons/mx_nanshen/images/emo-2.png' );
load_img.push( '../addons/mx_nanshen/images/emo-3.png' );
load_img.push( '../addons/mx_nanshen/images/emo-4.png' );
load_img.push( '../addons/mx_nanshen/images/icon-1.png' );
load_img.push( '../addons/mx_nanshen/images/icon-2.png' );
load_img.push( '../addons/mx_nanshen/images/icon-3.png' );
load_img.push( '../addons/mx_nanshen/images/icon-camera.png' );
load_img.push( '../addons/mx_nanshen/images/icon-comment.png' );
load_img.push( '../addons/mx_nanshen/images/icon-out-1.png' );
load_img.push( '../addons/mx_nanshen/images/icon-out-2.png' );
load_img.push( '../addons/mx_nanshen/images/icon-praise.png' );
load_img.push( '../addons/mx_nanshen/images/icon-record.png' );
load_img.push( '../addons/mx_nanshen/images/icon-search.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-1.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-2.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-3.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-4.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-5.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-6.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-7.png' );
load_img.push( '../addons/mx_nanshen/images/icon-weixin-40.png' );
load_img.push( '../addons/mx_nanshen/images/last.jpg' );
load_img.push( '../addons/mx_nanshen/images/light-btn.png' );
load_img.push( '../addons/mx_nanshen/images/num.png' );
load_img.push( '../addons/mx_nanshen/images/photo-1.png' );
load_img.push( '../addons/mx_nanshen/images/photo-2.png' );
load_img.push( '../addons/mx_nanshen/images/photo-3.png' );
load_img.push( '../addons/mx_nanshen/images/photo-4.png' );
load_img.push( '../addons/mx_nanshen/images/photo-5.png' );
load_img.push( '../addons/mx_nanshen/images/photo-guli.png' );
load_img.push( '../addons/mx_nanshen/images/photo-guojingming.png' );
load_img.push( '../addons/mx_nanshen/images/photo-linxiao.png' );
load_img.push( '../addons/mx_nanshen/images/photo-lushao.png' );
load_img.push( '../addons/mx_nanshen/images/photo-nanxiang.png' );
load_img.push( '../addons/mx_nanshen/images/photo-tangwanru.png' );
load_img.push( '../addons/mx_nanshen/images/photo-xicheng.png' );
load_img.push( '../addons/mx_nanshen/images/quan-1.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-2.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-3.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-4.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-5.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-6.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-7.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-8.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-1-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-2-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-3-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-4-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-5-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-6-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-7-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/quan-8-b.jpg' );
load_img.push( '../addons/mx_nanshen/images/share.png' );
load_img.push( '../addons/mx_nanshen/images/time.png' );

var load_img_progress = 0;
var load_img_total = load_img.length;

// 资源图片加载
jQuery.imgpreload(load_img,{
	all: function() {
		$('#loading').hide();
		$('#p-index').show();
		indexList();
	}
});

var password = '';
// var userName = '用户昵称';
// var userPhoto = '../addons/mx_nanshen/images/photo-user.png';
// userName 与 userPhoto 已定义在index.php

$(function(){
	document.getElementById('audio-1').load();

	$('#p-chat .list li.user img').attr('src',userPhoto);
	$('#p-quan .user img').attr('src',userPhoto);
	$('#p-quan .user span').html(userName);

	touch.on('#p-index', 'swiperight', function(ev){
		$('#p-unlock').show();
		document.getElementById('audio-2').load();
	});

	$('#p-unlock .num li').bind('touchstart',function(event){
		event.preventDefault();
		$(this).addClass('hover');
	});

	$('#p-unlock .num li').bind('touchend',function(event){
		event.preventDefault();
		$(this).removeClass('hover');
		var index = $(this).index() + 1;
		if ( index == 10 ) {
			index = 0;
		};
		if ( password.length < 5 ) {
			password = password + index;
			var eq = password.length - 1;
			$('#p-unlock .tips p span:eq(' + eq + ')').addClass('on');
			if ( password.length == 4 ) {
				if ( password == '0706' ) {
					$('#p-chat').show();
					chatList();
				} else {
					$('#p-unlock .tips p').addClass('shake');
					setTimeout(function(){
						$('#p-unlock .tips p').removeClass('shake');
						$('#p-unlock .tips p span').removeClass('on');
						password = '';
					},1000);
				};
			};
		};
	});

	$('#p-chat .area').bind('touchend',function(event){
		event.preventDefault();
		$('#p-chat .list li:eq(5)').show();
		document.getElementById('audio-2').play();
		$('#p-chat').removeClass('after');
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
		setTimeout(function(){
			$('#p-chat .list li:eq(6)').show();
			$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
			document.getElementById('audio-2').play();
		},2000);
		setTimeout(function(){
			$('#p-weixin-1').show();
		},4000);
	});

	$('#p-weixin-1 .bar').bind('touchend',function(event){
		event.preventDefault();
		$('#p-weixin-2').show();
	});

	$('#p-weixin-2 .bar').bind('touchend',function(event){
		event.preventDefault();
		$('#p-weixin-2').hide();
	});

	$('#p-weixin-2 .li-1').bind('touchstart',function(event){
		event.preventDefault();
		$(this).addClass('hover');
	});

	$('#p-weixin-2 .li-1').bind('touchend',function(event){
		event.preventDefault();
		$(this).removeClass('hover');
		$('#p-quan').show();
	});

	$('#p-quan .list li .detail .info .btn').bind('touchend',function(event){
		event.preventDefault();
		$(this).next('.out').toggle();
		$('.dot-hide').hide();
	});

	$('#p-quan .list li .out .out-1').bind('touchend',function(event){
		event.preventDefault();
		var li = $(this).parents('li');
		if ( !$(this).hasClass('cancel') ) {
			var zan = li.find('.zan');
			if ( zan.length > 0 ) {
				zan.append('<strong>，<span>' + userName + '</span></strong>')
			} else {
				li.find('.detail .comment').prepend('<div class="zan"><span>' + userName + '</span></div>')
			};
			$(this).addClass('cancel');
			$(this).html('<i></i>取消');			
		} else {
			var strong = li.find('.detail .comment .zan strong');
			if ( strong.length > 0 ) {
				strong.remove();
			} else {
				li.find('.zan').remove();
			};
			$(this).removeClass('cancel');
			$(this).html('<i></i>赞');
		};
		setTimeout(function(){
			li.find('.out').hide();
		},500);
	});

	$('#p-quan .list li .out .out-2').bind('touchend',function(event){
		event.preventDefault();
		var li = $(this).parents('li');
		li.find('.comment-list').append('<p><span>' + userName + '</span>：<input type="text"></p>');
		li.find('.comment-list p input').focus();
		setTimeout(function(){
			li.find('.out').hide();
			li.find('.comment-list p input').blur(function(){
				var value = $(this).val();
				var element = $(this).parent('p');
				$(this).remove();
				element.append(value);
			});
		},500);
	});

	$('#p-quan .list li .photo img').bind('touchend',function(event){
		event.preventDefault();
		var img = $(this).attr('src').split('.jpg')[0];
		$('#pic img').attr('src',img + '-b.jpg');
		$('#pic').show();
	});

	$('#pic').bind('touchend',function(event){
		event.preventDefault();
		$('#pic img').attr('src','');
		$('#pic').hide();
	});

	$('#p-quan .list li.end .photo span').bind('touchend',function(event){
		event.preventDefault();
		$('#p-end').show();
	});

	$('.p-end .btn-1').bind('touchend',function(event){
		$('#share').fadeIn();
	});

	$('.p-end .btn-3').bind('touchend',function(event){
		$('#share').fadeIn();
	});

	$('.p-end .btn-2').bind('touchend',function(event){
		window.location.href = 'download.php';
	});

	$('#p-end .btn-1').bind('touchend',function(event){
		$('#share').fadeIn();
	});

	$('#p-end .btn-3').bind('touchend',function(event){
		$('#share').fadeIn();
	});

	$('#p-end .btn-2').bind('touchend',function(event){
		window.location.href = 'download.php';
	});

	$('#share').bind('touchend',function(event){
		event.preventDefault();
		$('#share').fadeOut();
	});
})

function indexList(){
	$('#p-index .weixin li:eq(0)').show();
	document.getElementById('audio-1').play();
	setTimeout(function(){
		$('#p-index .weixin li:eq(1)').show();
	},600);
	setTimeout(function(){
		$('#p-index .weixin li:eq(2)').show();
	},1200);
	setTimeout(function(){
		$('#p-index .weixin li:eq(3)').show();
	},1800);
	setTimeout(function(){
		$('#p-index .weixin li:eq(4)').show();
	},2400);
}

function chatList(){
	$('#p-chat .list li:eq(0)').show();
	document.getElementById('audio-2').play();
	setTimeout(function(){
		$('#p-chat .list li:eq(1)').show();
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
		document.getElementById('audio-2').play();
	},2000);
	setTimeout(function(){
		$('#p-chat .list li:eq(2)').show();
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
		document.getElementById('audio-2').play();
	},4000);
	setTimeout(function(){
		$('#p-chat .list li:eq(3)').show();
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
		document.getElementById('audio-2').play();
	},6000);
	setTimeout(function(){
		$('#p-chat .list li:eq(4)').show();
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
		document.getElementById('audio-2').play();
	},8000);
	setTimeout(function(){
		$('#p-chat').addClass('after');
		$('#p-chat .scroll').scrollTop( $('#p-chat .scroll')[0].scrollHeight );
	},10000);
}