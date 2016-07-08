<?php

/**
 * Class MonUtil
 * 工具类
 */
   class MonUtil{

      public static $DEBUG=false;

      public static $IMG_COVER_IMG = 1;
      public static $IMG_BTN_CONS =  2;
       public static $IMG_SHARE_LAYER = 3;
      /**
       * author: codeMonkey QQ:mitusky QQ：229364369
       * @param $url
       * @return string
       */
      public static function str_murl($url)
      {
          global $_W;

          return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

      }


      /**
       * author: codeMonkey QQ:mitusky QQ：229364369
       * 检查手机
       */
      public static function  checkmobile()
      {

          if (!MonUtil::$DEBUG) {
              $user_agent = $_SERVER['HTTP_USER_AGENT'];
              if (strpos($user_agent, 'MicroMessenger') === false) {
                  echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
                  exit();
              }
          }


      }

      /**
       * author:codeMonkey QQ mitusky QQ：229364369
       * 获取哟规划信息
       * @return array|mixed|stdClass
       */
      public static function  getClientCookieUserInfo($cookieKey)
      {
          global $_GPC;
          $session = json_decode(base64_decode($_GPC[$cookieKey]), true);
          return $session;

      }


      /**
       * author: codeMonkey QQ:mitusky QQ：229364369
       * @param $openid
       * @param $accessToken
       * @return unknown
       * cookie保存用户信息
       */
      public static  function setClientCookieUserInfo($userInfo=array(),$cookieKey)
      {

          if (!empty($userInfo)&&!empty($userInfo['openid'])) {
              $cookie = array();
              foreach ($userInfo as $key=>$value)
              $cookie[$key] = $value;
              $session = base64_encode(json_encode($cookie));

              isetcookie($cookieKey, $session, 1 * 3600 * 1);

          }else{

              message("获取用户信息错误");
          }


      }


       public static  function getpicurl($url) {
           global $_W;
           return $_W ['attachurl'] . $url;

       }


       public static function  emtpyMsg($obj,$msg){
           if(empty($obj)){
               message($msg);
           }
       }


       public static function defaultImg($img_type,$mag='') {
           switch($img_type) {
               case MonUtil::$IMG_COVER_IMG:
                   if (!empty($mag) && !empty($mag['cover_img'])) {
                       return MonUtil::getpicurl($mag['cover_img']);
                   }
                   return "../addons/mon_magazine/weixin/resources/v2.1/images/sy-bg.png";
                   break;
               case MonUtil::$IMG_BTN_CONS:
                   if (!empty($mag) && !empty($mag['btn_icons'])) {
                       return MonUtil::getpicurl($mag['btn_icons']);
                   }
                   return "../addons/mon_magazine/weixin/resources/v2.1/images/icon100x100.png";
                   break;
               case MonUtil::$IMG_SHARE_LAYER:
                   if (!empty($mag) && !empty($mag['share_Layer'])) {
                       return MonUtil::getpicurl($mag['share_Layer']);
                   }
                   return "../addons/mon_magazine/weixin/resources/v1.0/images/share.gif";
                   break;
           }


       }

       public static  function deleteInstall() {
           if (!MonUtil::$DEBUG) {
				/*
               load()->func('file');
               $mfile=IA_ROOT . "/addons/" . MON_MAGAZINE . "/install.php";
               if( is_file( $mfile )) {
                   unlink($mfile);
               }*/
           }

       }

  }