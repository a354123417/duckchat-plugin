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
        "redis" => [
            "host" => "127.0.0.1",
            "port" => 6379,
        ],
        "mysql" => [
            "host" => "127.0.0.1",
            "port" => 3306,
            "dbName" => "redPacket_test001",
            "user" => "duckchat",
            "password" => "1234567890",
        ],
    ];

    public static function getConfig($key = "")
    {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }
        return self::$config;
    }
}
