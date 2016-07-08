<?php
@eval('//www.zhaoyuanma.com 专属VIP会员加密! ');
?><?php
class WxPayConf_micropay
{
    const CURL_TIMEOUT = 30;
    public $appid;
    public $mchid;
    public $key;
    public $sslcertpath;
    public $sslkeypath;
    public function __construct($appid, $mchid, $key, $sslcert_path, $sslkey_path)
    {
        $this->appid       = $appid;
        $this->mchid       = $mchid;
        $this->key         = $key;
        $this->sslcertpath = $sslcert_path;
        $this->sslkeypath  = $sslkey_path;
    }
}
