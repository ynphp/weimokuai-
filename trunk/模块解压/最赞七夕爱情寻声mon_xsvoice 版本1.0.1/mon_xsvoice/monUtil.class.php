<?php

/**
 * Class MonUtil
 * 工具类
 */
   class MonUtil{

      public static $DEBUG=true;
      public static $IMG_TITLE = 1;
      public static $IMG_CRP  = 2;
      public static $IMG_IMG1 = 3;
      public static $IMG_IMG2 = 4;
      public static $IMG_IMG3 = 5;
      public static $IMG_IMG4 = 6;

      /**
       * author: codeMonkey QQ:631872807
       * @param $url
       * @return string
       */
      public static function str_murl($url)
      {
          global $_W;

          return $_W['siteroot'] . 'app' . str_replace('./', '/', $url);

      }


      /**
       * author: codeMonkey QQ:631872807
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
       * author:codeMonkey QQ 631872807
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
       * author: codeMonkey QQ:631872807
       * @param $openid
       * @param $accessToken
       * @return unknown
       * cookie保存用户信息
       */
      public static  function setClientCookieUserInfo($userInfo=array(),$cookieKey)
      {

          if (!empty($userInfo)&&!empty($userInfo['openid'])) {
              $cookie = array();
              $cookie['openid'] = $userInfo['openid'];
              $cookie['nickname'] = $userInfo['nickname'];
              $cookie['headimgurl'] = $userInfo['headimgurl'];
              $session = base64_encode(json_encode($cookie));

              isetcookie($cookieKey, $session, 24 * 3600 * 365);

          }else{

              message("获取用户信息错误");
          }


      }

       public static function defaultImg($img_type,$voice='')
       {
           switch ($img_type) {

               //首页
               case MonUtil::$IMG_TITLE:
                   if (!empty($voice)&&!empty($voice['title_img'])) {
                       return MonUtil::getpicurl($voice['title_img']);
                   }
                   $img_name = "tt_t1-2.png";
                   break;
               case MonUtil::$IMG_CRP:
                   if (!empty($voice)&&!empty($voice['crp_img'])) {
                       return MonUtil::getpicurl($voice['crp_img']);
                   }
                   $img_name = "lg_2.png";
                   break;
               case MonUtil::$IMG_IMG1:
                   if (!empty($voice)&&!empty($voice['img1'])) {
                       return MonUtil::getpicurl($voice['img1']);
                   }
                   $img_name = "img_b1.jpg";
                   break;
               case MonUtil::$IMG_IMG2:
                   if (!empty($voice)&&!empty($voice['img2'])) {
                       return MonUtil::getpicurl($voice['img2']);
                   }
                   $img_name = "img_b2.jpg";
                   break;
               case MonUtil::$IMG_IMG3:
                   if (!empty($voice)&&!empty($voice['img3'])) {
                       return MonUtil::getpicurl($voice['img3']);
                   }
                   $img_name = "img_b3.jpg";
                   break;
               case MonUtil::$IMG_IMG4:
                   if (!empty($voice)&&!empty($voice['img4'])) {
                       return MonUtil::getpicurl($voice['img4']);
                   }
                   $img_name = "img_b4.jpg";
                   break;

           }
           return "../addons/mon_xsvoice/img/" . $img_name;

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



  }