<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 28/07/2018
 * Time: 10:49 PM
 */

class ZalyConfig
{
    private static $config = [
        "domain"    => "http://open.akaxin.com:5208",
        "cookieKey" => "AlYCns5cFusJoe1g",
        "loginUserProfileKey" => "XkYCEM5nFusLNg1g",
        "redis" => [
            "host" => "127.0.0.1",
            "port" => 6379,
        ],
        "verifyCodeKey"   => "zaly_phone_code_",
        "sessionKey "     => "user:session",
        "preSessionIdKey" => "zaly_preSessionId",
    ];

    public static function getConfig($key = "")
    {
        if(isset(self::$config[$key])) {
            return self::$config[$key];
        }
        return self::$config;
    }
}
