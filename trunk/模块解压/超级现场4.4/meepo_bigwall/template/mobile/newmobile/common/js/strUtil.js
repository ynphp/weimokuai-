/**
 * �ַ��ܹ���
 * mark
 */
 
//��ȡ�ַ��ݣ��������İ���2��Ӣ�İ�����1����
//str.sub(8,'...') ����,��׺
String.prototype.sub = function (n) {  
	var r = /[^\x00-\xff]/g;  
	if ( this.replace(r, "mm" ).length <= n){
	 	return this.substr(0, this .length);  
	}
	// n = n - 3;  
	var m = Math.floor(n/2);  
	for ( var i=m; i< this .length; i++) {  
		if ( this.substr(0, i).replace(r, "mm" ).length>=n) {  
		return this.substr(0, i) + '...'; }  
	} 
	return this;  
}; 

//�����ַ��ܵĳ��ȣ����ְ�2�����㣬ƴ������Ϊ1.
String.prototype.length = function() {
    var realLength = 0, len = this.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = this.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) realLength += 1;
        else realLength += 2;
    }
    return realLength;
};


//�滻�ַ���1����ֹjsִ��
//��"<" �� ">" ���� �� &lt &gt ��ʾ
function changeURLCode(str){
	str = str.replaceAll("&","&amp;");
	str = str.replace("<","&lt;");
	str = str.replace(">","&gt;");
	str = str.replace("/","&frasl;");
	return str;
}

//�ַ����滻2
//ע��ֻ�����ַ��ܣ��Դ���/���ַ������ã�Ҳ����׷��
function changeStrCode(str){
	return str.replaceAll("'","&apos;");
	return str.replaceAll('"',"&quot;");
}

//�滻ȫ��
String.prototype.replaceAll = function (AFindText,ARepText){
	raRegExp = new RegExp(AFindText,"g");
	return this.replace(raRegExp,ARepText)
}

//ȥ����β�ո�
String.prototype.trim = function(){
    return this.replace(/^\s+|\s+$/g,'');
}

//ȥ���ַ����е����пո�
String.prototype.delSpace = function(){
	return this.replace(/\s/ig,'');
}
