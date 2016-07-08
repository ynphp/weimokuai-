	$('.m_m_m').height($('.m_con').height());
	function m_back_top(){
			var st = $(document).scrollTop(), winh = $(window).height();
			$("html, body").scrollTop('0');
	}

	var m_mun = 0;
	$('.m_header_r').click(function(){
		var m = $('.m_m_m');
		if(m_mun == 0){
			m_mun = 1;
			m.css('display','block');
		}else{
			m_mun = 0;
			m.css('display','none');
		}
	})

	$("#verify").bind("click",function(){
		timenow = new Date().getTime();
		$(this).attr("src",$(this).attr("src")+"?rand="+timenow);
	}); 

	$('.m_show_js_a a').click(function(){
		if($(this).attr('class') != 'over'){
			$('.m_show_js_a a').attr('class','');
			$(this).attr('class','over');
			$('.m_show_content').css('display','none');
			$('.conid'+$(this).attr('date-conid')).css('display','block');
			if($(this).attr('date-conid') == '1'){
				$('.bujsfk').find('input').val('我要支持');
			}
			if($(this).attr('date-conid') == '2'){
				$('.bujsfk').find('input').val('查看项目详情');
			}
		}
	});

	$('.bujsfk').click(function(){
		if($(this).find('input').val() == '我要支持'){
			$('.m_show_js_a a').attr('class','');
			$('#right').attr('class','over');
			$('.m_show_content').css('display','none');
			$('.conid2').css('display','block');
			$(this).find('input').val('查看项目详情');m_back_top()
		}else{
			$('.m_show_js_a a').attr('class','');
			$('#left').attr('class','over');
			$('.m_show_content').css('display','none');
			$('.conid1').css('display','block');
			$(this).find('input').val('我要支持');m_back_top()
		}
	});