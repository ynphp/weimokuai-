/**
 * Created by Neksus(n@wowdg.com)  on 2014/11/3.
 */
function PKGameInitialization(init){window.SetImg=function($img,thumb_img){$img.attr("src","Preloader_4.gif");var img=new Image();$(img).load(function(){$img.attr("src",$(this).attr("src"));}).error(function(){}).attr("src",thumb_img);}
window.newvote=function(){var Url="/index.php?s=/addon/PKGame/PKGameApi/newvote";var data={sex:sex};if(typeof mugen!="undefined"&&mugen==1)data.mugen=1;$.ajax({type:"POST",url:Url,data:data,error:function(request){console.log("Connection error");},success:function(data){switch(data.status){case 8000:SetImg($("#user0 .thumb"),data.content[0].src_img);$("#user0 .nickname").html(data.content[0].nickname);$("#user0 .school").html(data.content[0].school);SetImg($("#user1 .thumb"),data.content[1].src_img);$("#user1 .nickname").html(data.content[1].nickname);$("#user1 .school").html(data.content[1].school);break;case 8102:SetImg($("#user0 .thumb"),data.content[0].src_img);$("#user0 .nickname").html(data.content[0].nickname);$("#user0 .school").html(data.content[0].school);SetImg($("#user1 .thumb"),data.content[1].src_img);$("#user1 .nickname").html(data.content[1].nickname);$("#user1 .school").html(data.content[1].school);console.log("exceed")
break;default:console.log("default")}}});}
window.ranking=function(){var Url="/index.php?s=/addon/PKGame/PKGameApi/ranking";$("#sex").html(sex==0?"女生":"男生");$.ajax({type:"POST",url:Url,data:{sex:sex},error:function(request){console.log("Connection error");},success:function(data){d=data;switch(data.status){case 8200:console.log("GET_RANKING_SUCCESS");for(n=0;n<d.content.length;n++){$("<div/>",{class:"panel panel-default"}).append($("<div/>",{class:"panel-heading",html:"NO."+(n+1).toString()+" "+d.content[n].nickname+" "+d.content[n].school+" "+d.content[n].rank+"分"})).append($("<div/>",{class:"panel-body",html:$("<img/>",{src:d.content[n].src_img,alt:d.content[n].nickname,class:"listimg"})})).appendTo("#RankingList");}
break;case 8201:console.log("GET_RANKING_FAIL");break;default:console.log(data);console.log("default");}}});}
window.vote=function(id){var Url="/index.php?s=/addon/PKGame/PKGameApi/vote"
var data={id:id};if(typeof mugen!="undefined"&&mugen==1)data.mugen=1;$.ajax({type:"POST",url:Url,data:data,error:function(request){console.log("Connection error");},success:function(data){switch(data.status){case 8100:console.log("投票成功");newvote();break;case 8104:console.log("已经投票成功");newvote();break;case 8102:$('#subscribe').modal('show');console.log("exceed");break;default:console.log("default")}}});}
window.report=function(data){var Url="/index.php?s=/addon/PKGame/PKGameApi/report"
$.ajax({type:"POST",url:Url,data:data,error:function(request){console.log("Connection error");},success:function(data){switch(data.status){case 8300:console.log("举报成功");$("#SubmitReport").text("举报成功");t=setTimeout(function(){$('#ReportModal').modal('hide');$('#report_content').val("");$(".report_status").val("")
$("#SubmitReport").text("确定");},1000);break;default:console.log("default")}}});}
$(".btn_zan").click(function(){$(this).clone().addClass("floatBtn").appendTo($(this).parent()).animate({bottom:'150px',opacity:'0.5'},500,function(){$(this).remove();vote($(this).attr("who"));voteTime++;if(voteTime==20&&typeof(localStorage.morethan20time)=='undefined'){$(".container").show();localStorage.morethan20time=true;}});});$(".votebtn").click(function(){$(this).parent().parent().find('a.btn_zan').click()})
$("#pass").click(function(){vote(-1);})
$(".reportBtn").click(function(){$("#badguy").val($(this).attr("who"));$('#ReportModal').modal('show');});$("#SubmitReport").click(function(){if($('#report_content').val()==""){$(".report_status").text("请输入举报原因");setTimeout(function(){$(".report_status").text("")},5000)}else{var data=$("#reportForm").serializeArray();report(data);}});$(".container").click(function(){$(this).hide();})
voteTime=0;var Initfunction=eval(init.function);var sex=(init.argument);var mugen=init.mugen;Initfunction();}