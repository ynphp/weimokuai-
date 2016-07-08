/**
 * 微互动统计相关的Javascript API，功能包括：
 *
 * //统计前初始化统计参数  格式、属性值如下：
 * var uuParams ={
 *     tenantId:{tenantId},              //商户id   0-未知
 *     modName:{modName},                //所属模块名 whd-微互动插件 magazine-微杂志 wuye-物业  evip-会员卡二期
 *     aid:{aid},                        //活动id   0-未知
 *     openid:{openid},                  //微信openid
 *     fromWxid:{fromWxid},              //来源微信openid（谁分享的地址）
 *     networkType:{networkType},        //当前网络类型 'wifi'-wifi网络 'edge'-非wifi,包含3G/2G  'fail'-网络断开连接 'wwan'-2g或者3g
 *     attent:{attent},                  //当前openid是否关注公众号  0-未关注  1-关注
 *     srcType:{srcType},                //来源类型  0-未知 1-公众号 2-朋友圈 3-朋友
 *     shareType:{shareType},            //分享事件才有值 2-分享给朋友圈 3-分享给好友
 *     shareUrl:"{shareUrl},             //分享事件才有值,需要被统计的分享地址
 * }
 *
 * 1、页面打开统计        uuStatApi.page();
 * 2、分享给微信好友统计  uuStatApi.sendAppMessage();
 * 3、分享给朋友圈统计    uuStatApi.sendTimeline();
 */
var uuStatApi = (function () {

    /*
     * 页面打开
     *
     */
    function page(){
        uuParams.logType = 'page';
        stat();
    }


    /*
     * 分享给朋友圈
     *
     */
    function sendTimeline(){
        uuParams.logType = 'share';
        uuParams.shareType = 2 ;
        stat();
    }

    /*
     * 分享给好友
     *
     */
    function sendAppMessage(){
        uuParams.logType = 'share';
        uuParams.shareType = 3 ;
        stat();
    }

    function stat(){
        var pageUrl = encodeURIComponent(uuParams.pageUrl ? uuParams.pageUrl : window.location.href),    //当前页面地址
            pageTitle = encodeURIComponent(uuParams.pageTitle ? uuParams.pageTitle : document.title), //当前页面标题
			tenantId = uuParams.tenantId ? uuParams.tenantId : '0', 
			modName = uuParams.modName ? uuParams.modName : '', 
            aid = uuParams.aid ? uuParams.aid : '0',                 
            openid = uuParams.openid ? uuParams.openid : 'unknown',           
            fromWxid = uuParams.fromWxid ? uuParams.fromWxid : 'unknown',   
            networkType = uuParams.networkType ? uuParams.networkType : 'unknown',
            attent = uuParams.attent ? uuParams.attent : '0',                    
            srcType = uuParams.srcType ? uuParams.srcType : '0',                 
            logType = uuParams.logType ? uuParams.logType : 'page',                 
            shareType = uuParams.shareType ? uuParams.shareType : '',            
            shareUrl = uuParams.shareUrl ? uuParams.shareUrl : '',               
            rnd = new Date().getTime();                                             

        var statUrl = "http://hd.playwx.com/page/stat"
            + "?pageUrl=" + pageUrl
            + "&pageTitle=" + pageTitle
			+ "&tenantId=" + tenantId
			+ "&modName=" + modName
            + "&aid=" + aid
            + "&wxid=" + openid
            + "&fromWxid=" + fromWxid
            + "&networkType=" + networkType
            + "&attent=" + attent
            + "&srcType=" + srcType
            + "&logType=" + logType;
            
            //如果是分享事件统计,需要统计分享类型和分享地址
            if(logType!="" && logType=="share"){
                statUrl += "&shareType=" + shareType;
                statUrl += "&shareUrl=" + shareUrl;
            }

        var img = new Image();
            img.onload = img.onerror = function() {};
            img.src = statUrl + "&rnd=" + rnd;
    }

    return {
        page             :page,
        sendTimeline     :sendTimeline,
        sendAppMessage   :sendAppMessage
    };
})();