<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 28/07/2018
 * Time: 10:49 PM
 */

class ZalyConfig
{
    private static $sampleConfig = array(
        'miniProgramId' => 200,
        'miniProgramName' => '红包',
        'miniProgramSecretKey' => 'Q968Pix85z2wLRDqZ4C89Bgg0mb5Apvz',
        'duckChatAddress' => "http://192.168.3.4:8888/",
        "mysql" => [
            "host" => "127.0.0.1",
            "port" => 3306,
            "dbName" => "redPacket_test001",
            "user" => "duckchat",
            "password" => "1234567890",
        ],
    );

    private static $config = [];

    private static function loadConfig()
    {
        if (!empty(self::$config)) {
            return self::$config;
        }

        $configPath = WPF_ROOT_DIR . "config.php";

        if (!file_exists($configPath)) {
            $contents = var_export(self::$sampleConfig, true);
            file_put_contents($configPath, "<?php\n return {$contents};\n ");

            if (function_exists("opcache_reset")) {
                opcache_reset();
            }
        }
        self::$config = require($configPath);
    }

    public static function getConfig($key = "")
    {
        self::loadConfig();
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }
        return self::$config;
    }

    public static function getAllConfig()
    {
        self::loadConfig();
        return self::$config;
    }
}
