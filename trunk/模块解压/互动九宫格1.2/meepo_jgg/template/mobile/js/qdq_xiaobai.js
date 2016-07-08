var per = 0;
var cont =40;
var opened = 0;
var mperson;
var smperson;
var allper,allvisper;
var sper = 0;
var lastperid=0;
var dataper=new Array();
function personAdd(){
		if(per >= cont){
			clearInterval(mperson);
			$("#start-action").hide();
			setTimeout(changepage,2000);
			}else{
				$('#sign').append("<div class='perhead' id=per"+per+"><div class='ui image' ><div class='ui dimmer visible active sign-text' id=nickname"+per+"><div class='content'>"+dataper[per]['nickname']+"</div></div><img style='width:107px; height:107px;' src='"+dataper[per]['avatar']+"' /></div></div>");
				$('#per'+per).transition('fade down');	
				$('.ui.dimmer').dimmer({
			on: 'hover',
			duration: {
				  show : 500,
				  hide : 3000
				  }
		});
		$('#nickname'+per).dimmer('hide');
				per++;
				
				}
		  }

function smallpersonAdd(){
			if(per == allper){
				per = 0;
				}
				$('.sign-small').append("<img style='width:53.75px; height:53.75px;  margin-left:0px;margin-top:0px' id=per"+sper+" src='"+dataper[per]['avatar']+"' />");
				$('#per'+sper).transition('fade up');
				if(sper<allvisper){
				per++;
				sper++;
				}else{
			clearInterval(smperson);
			opened = 1;
			$('.btnCheckin').attr("id","btnCheckin");
				$('.shine-img').transition('fade');
				$('#sign').prepend("<div class='sign-endbutt'><div class='ui huge buttons'><div class='ui green button' id='toshake'>摇一摇</div><div class='or'></div><div class='ui orange button' id='btnCheckin'>微信墙</div></div></div>");
				
				$('.sign-endbutt').transition('horizontal flip',function(){
					$('.sign-endbutt').css('display','block');
					$('.sign-endbutt').popup({content: '<h2>签到结束，共到<a class="ui green circular label">'+allper+'</a>人！</h2>'});
					setTimeout ("$('.sign-endbutt').popup('show');",2000);
					});
					}
		  }
function smallcycleindex(){
	var widg,heg;
		widg =($(".sign-small").width()/53.75);
		heg = ($(".sign-small").height()/53.75);
		allvisper = parseInt(widg) * parseInt(heg);
	}
function persondata(){
	if(qdqopen){
	    $.getJSON("qdq_plug/qdq_data.php",{lastperid:lastperid},function(d) {
        //alert(d);return;
        if(d[0]['id']>0)
        {
            dataper = d;
        }
  		window.setTimeout(persondata(), 2000);
  });
	}
	}
function changepage(){
		$("#sign").transition('fade',function() {
		  $(".perhead").detach();
			cont = cont+40;
		}).transition('fade',500,function(){
	$("#start-action").transition('fade',1000,function(){$("#start-action").show();});
 			mperson=setInterval(personAdd,300);
			});
	}
	
$(function(){
	$(document).on('click','#toshake', function(){
		window.location.href='../shake/';
		});
		$(document).on('click','#btnCheckin', function(){
			oopen=switchto(oopen,'sign');
	});
	$("#start-action").click(function(){
			clearInterval(mperson);
			$('.btnCheckin').attr("id","#btnCheckinzzz");
		  $("#sign").transition('fade',1000,function(){
			 allper=per;
			per = 0;
			$("#sign").empty();
			$("#sign").append("<div class='shine-img'></div><div class='sign-small'></div>");
			 smallcycleindex();
			}).transition('fade',500,function(){
		smperson=setInterval(smallpersonAdd,100);
			});
		});
	$(document).keydown(function (event)
    {    
           if (event.keyCode == 81) {
				$('#btnCheckin').click();
            }
			if(oopen == 'sign'){
				if(event.keyCode == 32){
					$('#start-action').click();
					}}
    });  


});
