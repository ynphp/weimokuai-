function showNoticeFunc(str){
    var newNode = document.createElement("div");
    newNode.className = "alertContent";
    var objStr = '<div class="alertContent-con"><p>'+str+'</p><span id="noticeSure" onclick="hideNoticeFunc(this)">确定</span></div>';
    newNode.innerHTML = objStr;
    document.body.appendChild(newNode);
}
function hideNoticeFunc(obj){
    var objPar = document.getElementById("noticeSure").parentNode.parentNode;
    document.body.removeChild(objPar);
}