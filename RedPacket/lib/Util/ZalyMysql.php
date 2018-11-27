<?php

/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 7:29 PM
 */
class ZalyMysql
{
    public $db;

    public function __construct()
    {
        $config = ZalyConfig::getConfig("mysql");
        $dbHost = $config["host"];
        $dbPort = $config['port'];
        $dbName = $config["dbName"];
        $userName = $config["user"];
        $password = $config["password"];
//        $dbDsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;";
        $dbDsn = "mysql:host=$dbHost;port=$dbPort;";
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_PERSISTENT => true,
        );
        $this->db = new PDO($dbDsn, $userName, $password, $options);

        $sql = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci; USE `$dbName`;";
        $this->db->exec($sql);
    }

}