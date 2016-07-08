
var Animate ={1:{up:{out:"pt-page-moveToTop",IN:"pt-page-moveFromBottom"},down:{out:"pt-page-moveToBottom",IN:"pt-page-moveFromTop"},left:{out:"pt-page-moveToLeft",IN:"pt-page-moveFromRight"},right:{out:"pt-page-moveToRight",IN:"pt-page-moveFromLeft"}},2:{up:{out:"pt-page-rotatePushTop",IN:"pt-page-moveFromBottom"},down:{out:"pt-page-rotatePushBottom",IN:"pt-page-moveFromTop"},left:{out:"pt-page-rotatePushLeft",IN:"pt-page-moveFromRight"},right:{out:"pt-page-rotatePushRight",IN:"pt-page-moveFromLeft"}},3:{up:{out:"pt-page-rotatePushTop",IN:"pt-page-rotatePullBottom pt-page-delay180"},down:{out:"pt-page-rotatePushBottom",IN:"pt-page-rotatePullTop pt-page-delay180"},left:{out:"pt-page-rotatePushLeft",IN:"pt-page-rotatePullRight pt-page-delay180"},right:{out:"pt-page-rotatePushRight",IN:"pt-page-rotatePullLeft pt-page-delay180"}},4:{up:{out:"pt-page-rotateBottomSideFirst",IN:"pt-page-moveFromBottom pt-page-delay200 pt-page-ontop"},down:{out:"pt-page-rotateTopSideFirst",IN:"pt-page-moveFromTop pt-page-delay200 pt-page-ontop"},left:{out:"pt-page-rotateRightSideFirst",IN:"pt-page-moveFromRight pt-page-delay200 pt-page-ontop"},right:{out:"pt-page-rotateLeftSideFirst",IN:"pt-page-moveFromLeft pt-page-delay200 pt-page-ontop"}},5:{up:{out:"pt-page-flipOutTop",IN:"pt-page-flipInBottom pt-page-delay500"},down:{out:"pt-page-flipOutBottom",IN:"pt-page-flipInTop pt-page-delay500"},left:{out:"pt-page-flipOutRight",IN:"pt-page-flipInLeft pt-page-delay500"},right:{out:"pt-page-flipOutLeft",IN:"pt-page-flipInRight pt-page-delay500"}},6:{up:{out:"pt-page-rotateFall pt-page-ontop",IN:"pt-page-scaleUp"},down:{out:"pt-page-rotateFalltoright pt-page-ontop",IN:"pt-page-scaleUp"},left:{out:"pt-page-rotateFall pt-page-ontop",IN:"pt-page-scaleUp"},right:{out:"pt-page-rotateFalltoright pt-page-ontop",IN:"pt-page-scaleUp"}},7:{up:{out:"pt-page-rotateFoldTop",IN:"pt-page-moveFromBottomFade"},down:{out:"pt-page-rotateFoldBottom",IN:"pt-page-moveFromTopFade"},left:{out:"pt-page-rotateFoldLeft",IN:"pt-page-moveFromRightFade"},right:{out:"pt-page-rotateFoldRight",IN:"pt-page-moveFromLeftFade"}},8:{up:{out:"pt-page-moveToTopFade",IN:"pt-page-rotateUnfoldBottom"},down:{out:"pt-page-moveToBottomFade",IN:"pt-page-rotateUnfoldTop"},left:{out:"pt-page-moveToLeftFade",IN:"pt-page-rotateUnfoldRight"},right:{out:"pt-page-moveToRightFade",IN:"pt-page-rotateUnfoldLeft"}},9:{up:{out:"pt-page-rotateCubeTopOut pt-page-ontop",IN:"pt-page-rotateCubeTopIn"},down:{out:"pt-page-rotateCubeBottomOut pt-page-ontop",IN:"pt-page-rotateCubeBottomIn"},left:{out:"pt-page-rotateCubeLeftOut pt-page-ontop",IN:"pt-page-rotateCubeLeftIn"},right:{out:"pt-page-rotateCubeRightOut pt-page-ontop",IN:"pt-page-rotateCubeRightIn"}},10:{up:{out:"pt-page-rotateCarouselTopOut pt-page-ontop",IN:"pt-page-rotateCarouselTopIn"},down:{out:"pt-page-rotateCarouselBottomOut pt-page-ontop",IN:"pt-page-rotateCarouselBottomIn"},left:{out:"pt-page-rotateCarouselLeftOut pt-page-ontop",IN:"pt-page-rotateCarouselLeftIn"},right:{out:"pt-page-rotateCarouselRightOut pt-page-ontop",IN:"pt-page-rotateCarouselRightIn"}},11:{up:{out:"pt-page-fad",IN:"pt-page-moveFromBottom pt-page-ontop"},down:{out:"pt-page-fade",IN:"pt-page-moveFromTop pt-page-ontop"},left:{out:"pt-page-fade",IN:"pt-page-moveFromRight pt-page-ontop"},right:{out:"pt-page-fade",IN:"pt-page-moveFromLeft pt-page-ontop"}},12:{up:{out:"pt-page-moveToTopFade",IN:"pt-page-moveFromBottomFade"},down:{out:"pt-page-moveToBottomFade",IN:"pt-page-moveFromTopFade"},left:{out:"pt-page-moveToLeftFade",IN:"pt-page-moveFromRightFade"},right:{out:"pt-page-moveToRightFade",IN:"pt-page-moveFromLeftFade"}},13:{up:{out:"pt-page-moveToTopEasing pt-page-ontop",IN:"pt-page-moveFromBottom"},down:{out:"pt-page-moveToBottomEasing pt-page-ontop",IN:"pt-page-moveFromTop"},left:{out:"pt-page-moveToLeftEasing pt-page-ontop",IN:"pt-page-moveFromRight"},right:{out:"pt-page-moveToRightEasing pt-page-ontop",IN:"pt-page-moveFromLeft"}},14:{up:{out:"pt-page-scaleDown",IN:"pt-page-moveFromBottom pt-page-ontop"},down:{out:"pt-page-scaleDown",IN:"pt-page-moveFromTop pt-page-ontop"},left:{out:"pt-page-scaleDown",IN:"pt-page-moveFromRight pt-page-ontop"},right:{out:"pt-page-scaleDown",IN:"pt-page-moveFromLeft pt-page-ontop"}},15:{up:{out:"pt-page-scaleDownUp",IN:"pt-page-scaleUp pt-page-delay200"},down:{out:"pt-page-scaleDown",IN:"pt-page-scaleUpDown pt-page-delay200"},left:{out:"pt-page-scaleDownUp",IN:"pt-page-scaleUp pt-page-delay200"},right:{out:"pt-page-scaleDown",IN:"pt-page-scaleUpDown pt-page-delay200"}},16:{up:{out:"pt-page-moveToTop pt-page-ontop",IN:"pt-page-scaleUp"},down:{out:"pt-page-moveToBottom pt-page-ontop",IN:"pt-page-scaleUp"},left:{out:"pt-page-moveToLeft pt-page-ontop",IN:"pt-page-scaleUp"},right:{out:"pt-page-moveToRight pt-page-ontop",IN:"pt-page-scaleUp"}},17:{up:{out:"rotateSlideOuttotop",IN:"rotateSlideIntotop"},down:{out:"rotateSlideOuttobottom",IN:"rotateSlideIntobottom"},left:{out:"pt-page-rotateSlideOut",IN:"pt-page-rotateSlideIn"},right:{out:"rotateSlideOuttoright",IN:"rotateSlideIntoright"}},18:{up:{out:"rotateSlideOuttotop-10-17",IN:"rotateSlideIntotop-10-17"},down:{out:"rotateSlideOuttobottom-10-17",IN:"rotateSlideIntobottom-10-17"},left:{out:"rotateSlideOuttoleft-10-17",IN:"rotateSlideIntoleft-10-17"},right:{out:"rotateSlideOuttoright-10-17",IN:"rotateSlideIntoright-10-17"}}};







if(Zhu._weixin && Zhu._Android)
{
	
}





document.addEventListener('touchmove' , function (ev){
	
	
	if($('.guizhebox').is(':visible')) return;	
	
	ev.preventDefault();
	return false;
} , false)



var Animate_Index = 0;
var Animate_lastIndex = Animate_Index;
var Animate_isSwipe = true;
var Animate_css = Animate[15];
var Animate_Bstop = true;

$('.box-step').eq(Animate_lastIndex).show();




				
					
function AnimateTween(){
		
		
		switch(Animate_Index)
		{
			
			case 1:
			
			
			
			

				
				
	///$(xxxxxxxx).from({ transform : 'translate(0 , 50px)' , opacity : 0 , delay : .5} ,1);

				
				
				break;
			
			case 2:
			
					
				break;	
			
				
		
			
		}
		
		
};


function GetRandomNum(Min,Max)
{   
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
} 

function swipeUpFn ( index ){
	if( index && index == Animate_Index) return;
	if(!Animate_Bstop) return;
	Animate_Bstop = false;
	
	var NowStep = $('.box-content .box-step').eq(Animate_lastIndex);
	var NextStep = $('.box-content .box-step').eq(index ? index : ++ Animate_Index);
	
	
	if( !NextStep.size() ) {
		Animate_Index -- ;
		Animate_Bstop = true;
		return;	
	}
	if(index) Animate_Index = index;
	NowStep.cssHide(Animate_css.up.out , function (){
		Animate_Bstop = true;	
	})
	NextStep.cssShow(Animate_css.up.IN);
	Animate_lastIndex = Animate_Index;
	setTimeout( AnimateTween , 100)
};
function swipeDownFn ( index ){
	if(Animate_lastIndex == 0) return;
	if( index && index == Animate_Index) return;
	if(!Animate_Bstop) return;
	Animate_Bstop = false;
	
	
	var NowStep = $('.box-content .box-step').eq(Animate_lastIndex);
	var NextStep = $('.box-content .box-step').eq(index ? index : -- Animate_Index);
	
	if(index) Animate_Index = index;
	
	
	NowStep.cssHide(Animate_css.down.out , function (){
		Animate_Bstop = true;	
	})
	NextStep.cssShow(Animate_css.down.IN)
	Animate_lastIndex = Animate_Index;
	setTimeout( AnimateTween , 100)

};
		






$( function (){
	
	
	
	
/*
	
	$$('.box-content').bind('swipeUp' , function (){
					//if($('.zhuquan1').find('.UP_ico').is(':hidden')) return;
					if(!Animate_isSwipe) return;
					swipeUpFn();
					
	}).bind('swipeDown' , function (){
					//if($('.zhuquan2').find('.UP_ico').is(':hidden')) return;
					if(!Animate_isSwipe) return;
					swipeDownFn();
					
	});
	
*/			

	
	
	
	
	
	
	
	$('a[href="#"]').attr('href' , 'javascript:;');

	/*var phone = $('.s3-2 .ipt');
		
		if(!/^1[3|4|5|6|7|8|9][0-9]\d{8}$/.test(phone.val()))
		{
			alert('手机号码输入有误!');
			return;
		};
	*/
});




		
		



function LoadFn ( arr , fn , fn2){
		var loader = new PxLoader();
		for( var i = 0 ; i < arr.length; i ++)
		{
			loader.addImage(arr[i]);
		};
		
		loader.addProgressListener(function(e) {
				var percent = Math.round( e.completedCount / e.totalCount * 100 );
				if(fn2) fn2(percent)
		});	
		
		
		loader.addCompletionListener( function(){
			if(fn) fn();	
		});
		loader.start();	
}






/*

var LoadingImg = [];

$('img').each( function (){
	
	if(!$(this).attr('src')) return;
	LoadingImg.push($(this).attr('src'));
		
});
function HTMLStart  (){
	
	//swipeUpFn(2);
	
	
}


LoadFn(LoadingImg , function (){
	$('#loading').fadeOut();
	HTMLStart ();
	
	

} , function ( p ){
	
	
	//$('#loading .s1-4 i').width( p + '%')
	$('#loading p').html('加载中...<br />' + p + '%')
});



*/


//
//
//
//