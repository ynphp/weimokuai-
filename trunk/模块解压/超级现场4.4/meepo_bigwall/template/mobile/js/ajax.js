var len=3;
var cur=0;//��ǰλ��
var mtime;
var data=new Array();
data[0]=new Array('0','http://yanson-data.stor.sinaapp.com/196333960head.jpg','yanson2','ǽ����');


//data[1]=new Array('2','files/12.jpg','yanson2','#yanson#����2');
//data[2]=new Array('3','files/1.jpg','yanson2','#yanson#����399');

//var word_id='96';
var lastid='0';
var vep=true;//�鿴��ǽ˵��
var vone=false;//�鿴����

function viewOneHide(){
	vone=false;
	$("#mone").hide();
}
function viewOne(cid,t)
{
	if(vone==false)
	{
		vone=true;
		var str=t.innerHTML;
		$("#mone").html(str);
		$("#mone").fadeIn(700);
	}else
	{
		vone=false;
		$("#mone").hide();
	}
}
function viewExplan()
{
	if(vep==false)
	{
		vep=true;
		$("#explan").fadeIn(700);
		//clearInterval(mtime);
	}else
	{
		vep=false;
		$("#explan").hide();
		//mtime=setInterval(messageAdd,5000);
	}
}
function messageAdd()
{
	if(cur==len)
	{
		messageData();
		return false;
	}
	var str='<li id=li'+cur+' onclick="viewOne('+cur+',this);"><div class=m1><div class=m2><div class="pic"><img src="'+data[cur][1]+'" width="100" height="100" /></div><div class="c f2"><span>'+data[cur][2]+'��</span>'+data[cur][3]+'<div class="num">'+data[cur][0]+'#</div></div></div></div></li>';
	$("#list").prepend(str);
	$("#li"+cur).slideDown(800);
	cur++;
	messageData();
}
function messageData()
{
	var url='../api.php';
	$.getJSON(url,{lastid:lastid},function(d) {
		//alert(d);return;
		if(d['ret']==1)
		{
			$.each(d['data'], function(i,v){
				data.push(new Array(v['num'],v['avatar'],v['nickname'],v['content']));
				lastid=v['num'];
				len++;
			});
		}else{
				//	alert('ľ������Ϣ..ÿ5��ajaxһ��');
					window.setTimeout('messageData();', 3000);
		}	
	});
}
window.onload=function()
{
		messageAdd();
	mtime=setInterval(messageAdd,3000);
	}