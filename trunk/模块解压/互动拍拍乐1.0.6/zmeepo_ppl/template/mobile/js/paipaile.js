var scrwidth;
$(function(){
var hoko;
var ss=3;
var isstop = 0;
var tt;
var stime=3*1000;
//抽奖数量
var cjnum=1;
var gogo_ppl=1;
var pplcj_step=0;
var cjuserinfo=new Array();
var pplcj_gogo=new Array();
function querystart(){
	   $.get(start_url, function(startnum){
		  // var startnum = 1;
		  if(startnum==1){
			$("#bignum").hide();
			$("#voting").show();
			gocirculation();
		  }else{
			  setTimeout(function(){
			      querystart();
			  },3*1000);
		  }
		});
   }
 function count(){
    $(".lg").html(ss);
    ss=ss-1;  
    if(ss<=-1){
        $(".lg").html('<div id="updataing"></div>');
		querystart();
    }else{
        setTimeout(count,1000)
    }

  }
 $("#c").click(function(){
    count();
 })
$('.ui.dropdown').dropdown('setting', {
    onChange:function(value){
        cjnum=value;
        changepplcjnum(value);
    }
});
//添加抽奖框函数
function changepplcjnum(value){
    var userhtml='<div class="pplcjuser" name=""><div class="pplfrombox"><img src="../wall/images/ico-weixin.png" class="pplfromtype"/></div><img src="images/pair-default.jpg" class="pplluky"><center><a class="ui red label pplcjname">。。。</a></center></div>';
    $("#pplcjuserbox").empty();
        if(value>3){
            $("#pplcjmodal").css("margin-top","-364px");
        }else{
            $("#pplcjmodal").css("margin-top","-245px");
        }
    for(var i=0;i<value;i++){
        $("#pplcjuserbox").append(userhtml);
    }
}
//抽奖模块点击触发事件
$("#votestop").click(function(){
    if(confirm('确定结束本轮投票，并开始抽奖吗？')){
        gogo_ppl=0;
    	$('#pplcjmodal').modal('setting', {
            closable  : false,
            onShow:function(){
              readycjinfo();  
            },
            onHide : function() {
               
                //getwendacj();//所调用函数
            }
            }).modal('show');
        }
    });
//准抽奖信息函数
function readycjinfo(){
    $.getJSON('date.php',{do:'getcjinfo'}, function(json){
		if(json){
		    cjuserinfo=json;
		    $(".pplbutton").text('开始抽奖');
		    pplcj_step = 1;
			}
    });
}
//抽奖按钮点击触发事件
$(".pplbutton").click(function(){
    switch(pplcj_step){
		case 1:
		start_pplcj();
		break;
		case 2:
		stop_pplcj();
		break;
    }
});
//开启的大抽奖进程抽奖
function start_pplcj(){
    var len = cjuserinfo.length;
	if(len>0){
		pplcj_step =2
		for(var i=0;i<cjnum;i++){
		    smallcj(i,len);
		}
        $(".pplbutton").text("停止");
	}else{
		alert("目前还没有人符合抽奖条件哦！");
	}
    function smallcj(step_num,len){
        pplcj_gogo[step_num] = setInterval(function(){
    	    var num = Math.floor(Math.random()*len);
    		//var id = obj[num]['id'];
    		$(".pplcjuser").eq(step_num).attr("date-key",cjuserinfo[num].key); 
            $(".pplluky").eq(step_num).attr("src",cjuserinfo[num].avatar);
            $(".pplfromtype").eq(step_num).attr("src","../wall/images/ico-"+cjuserinfo[num].from+".png");
            $(".pplcjuser").eq(step_num).attr("name",cjuserinfo[num].openid); 
    		$(".pplcjname").eq(step_num).html(cjuserinfo[num].nickname); 
    	    },100);
    }
}
//写入数据库中奖人等
function posttocopy(zjuser){
$.post("date.php?do=posttocopy", { zjuser: zjuser},
   function(data){
      $(".pplfooter").html('<center><a class="huge ui green button" id="pplend" href="#" onclick="window.location.reload();">数据已经记录，点击进入下一轮</a></center>');
   });
}
function stop_pplcj(){
		pplcj_step =1;
        var zjuser=new Array();
		for(var i=0;i<cjnum;i++){
		    smallcj(i);
		}
		$(".pplfooter").empty();
        posttocopy(zjuser);
    function smallcj(step_num){
        clearInterval(pplcj_gogo[step_num]);
        var len = cjuserinfo.length;
        var num = Math.floor(Math.random()*len);
        if(cjuserinfo[num] != null){
            $(".pplluky").eq(step_num).attr("src",cjuserinfo[num].avatar);
            $(".pplfromtype").eq(step_num).attr("src","../wall/images/ico-"+cjuserinfo[num].from+".png");
            $(".pplcjuser").eq(step_num).attr("name",cjuserinfo[num].openid); 
    		$(".pplcjname").eq(step_num).html(cjuserinfo[num].nickname);
            zjuser[step_num]=cjuserinfo[num].openid;
            cjuserinfo.splice(num,1);
        }else{
            $(".pplluky").eq(step_num).attr("src","images/pair-default.jpg");
            $(".pplfromtype").eq(step_num).attr("src","../wall/images/ico-weixin.png");
    		$(".pplcjname").eq(step_num).html("。。。"); 
        }
    }
}
$("body").on("click","#votefresh", function(){
    gofreshvote();
});
//3s循环刷新投票区域
function gocirculation(){
    if(gogo_ppl){
        gofreshvote();
        setTimeout(function(){
    			      gocirculation();
    			  },5*1000);
    }
}
//重新刷新vote区域
function gofreshvote(){
    $('#wrap').empty().ready(function(){
		 getStartNum = 0;
	    getppldata();
	 });
}
	//模拟数据
	var ppldata = new Array;
	var datalen;
	var voteaddnum = 0;
	function getppldata(){
			$.getJSON("date.php?do=getppldate", function(json){
				if(json){
					ppldata = json;
					datalen=ppldata.length;
					voteaddnum = 0;
					addbox();
				}
				});
		}
	function addbox(){
			var ppli=voteaddnum;
			var img = new Image();
			 var tmp='<div class="box"><div class="info ui left medium image"> <a class="ui left corner red label"><div class="text">'+ppldata[ppli].id+'</div></a><div class="pic"><img src="'+ppldata[ppli].picurl+'"></div><div class="title"><a href="#"><img class="ui avatar image" src="'+ppldata[ppli].avatar+'">'+ppldata[ppli].nickname+'</a><div class="pplprogress ui grid"> <div class="three wide column"><center>'+ppldata[ppli].voteres+'票 </center></div><div class="thirteen wide column"><div class="ui red progress"><div class="bar" style="width:'+ppldata[ppli].progress+'%"></div> </div></div></div></div></div></div>';
			 img.src =ppldata[ppli].picurl;
			 
			  img.onload = function(){
				 $('#wrap').append(tmp);
				 voteaddnum++;
				 if(voteaddnum<datalen){
					 addbox();
				 }else if(voteaddnum=datalen){
					PBL('wrap','box');
				 }
				}
	}
/**
* 瀑布流主函数
* @param  wrap	[Str] 外层元素的ID
* @param  box 	[Str] 每一个box的类名
*/
function PBL(wrap,box){
	//	1.获得外层以及每一个box
	var wrap = document.getElementById(wrap);
	var boxs  = getClass(wrap,box);
	//	2.获得屏幕可显示的列数
	var boxW = boxs[0].offsetWidth;
	var colsNum = Math.floor($('#wrap').width()/boxW);
	
	//	3.循环出所有的box并按照瀑布流排列
	var everyH = [];//定义一个数组存储每一列的高度
	for (var i = 0; i < boxs.length; i++) {
		if(i<colsNum){
			everyH[i] = boxs[i].offsetHeight;
		}else{
			var minH = Math.min.apply(null,everyH);//获得最小的列的高度
			var minIndex = getIndex(minH,everyH); //获得最小列的索引
			
			getStyle(boxs[i],minH,boxs[minIndex].offsetLeft,i);
			everyH[minIndex] += boxs[i].offsetHeight;//更新最小列的高度
		}
	}
}
/**
* 获取类元素
* @param  warp		[Obj] 外层
* @param  className	[Str] 类名
*/
function getClass(wrap,className){
	var obj = wrap.getElementsByTagName('*');
	var arr = [];
	for(var i=0;i<obj.length;i++){
		if(obj[i].className == className){
			arr.push(obj[i]);
		}
	}

	return arr;
}
/**
* 获取最小列的索引
* @param  minH	 [Num] 最小高度
* @param  everyH [Arr] 所有列高度的数组
*/
function getIndex(minH,everyH){
	for(index in everyH){
		if (everyH[index] == minH ) return index;
	}
}
/**
* 数据请求检验
*/
function getCheck(){
	var documentH = document.documentElement.clientHeight;
	var scrollH = document.documentElement.scrollTop || document.body.scrollTop;
	return documentH+scrollH>=getLastH() ?true:false;
}
/**
* 获得最后一个box所在列的高度
*/
function getLastH(){
	var wrap = document.getElementById('wrap');
	var boxs = getClass(wrap,'box');
	return boxs[boxs.length-1].offsetTop+boxs[boxs.length-1].offsetHeight;
}
/**
* 设置加载样式
* @param  box 	[obj] 设置的Box
* @param  top 	[Num] box的top值
* @param  left 	[Num] box的left值
* @param  index [Num] box的第几个
*/
var getStartNum = 0;//设置请求加载的条数的位置
function getStyle(box,top,left,index){
    if (getStartNum>=index) return;
    $(box).css({
    	'position':'absolute',
        'top':top,
        "left":left,
        "opacity":"0"
    });
    $(box).stop().animate({
        "opacity":"1"
    },999);
    getStartNum = index;//更新请求数据的条数位置
}

});
