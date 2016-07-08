// JavaScript Document
var myFirstW;
var mySecW;
var mySecCanX;
function setPhone(){
		draw2();
		draw3();
}
function draw2(){
	myFirstW = 37;
	document.getElementById("f-phone").style.left = myStageW * 0.04 + "px";
	document.getElementById("f-hero").style.left = myStageW * 0.04 + myFirstW - 25 + "px";
	document.getElementById("f-myTitle").style.left = myStageW * 0.04 + myFirstW - 15 + "px";
	b = myStageW * 0.04 + myFirstW - 25;
}
function draw3(){
	mySecW =37;
	if(myExpBoo) mySecCanX = myStageW * 0.2 + myFirstW + parseInt(0.9* (myStageW * 0.9-myFirstW  - 105));
	else mySecCanX = myStageW * 0.06 + myFirstW + parseInt(Math.random() * (myStageW * 0.9-myFirstW  - 50));
	document.getElementById("f-phone1").style.left = mySecCanX + "px";
	c = mySecCanX + mySecW - 24 - b;
	if(myExpBoo) setTimeout(startLineDraw, myPlayTime);
}
function startLineDraw(){
	myPlayTime = 200;
	myLineY = myStageH - 153;
	document.getElementById("f-hand").src = "images/vivo-hand_17.gif";
	MouseDownInterval = setInterval(drawLine,30);
}
function GetArgsFromHref(sHref, sArgName){
      var args    = sHref.split("?");
      var retval = "";
      if(args[0] == sHref)
      {
           return retval;
      }  
      var str = args[1];
      args = str.split("&");
      for(var i = 0; i < args.length; i ++)
      {
          str = args[i];
          var arg = str.split("=");
          if(arg.length <= 1) continue;
          if(arg[0] == sArgName) retval = arg[1]; 
      }
      return retval;
}