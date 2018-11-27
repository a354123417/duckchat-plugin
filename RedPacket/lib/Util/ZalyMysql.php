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

        $hasInitDB = ZalyConfig::getConfig("initDB");

        $dbHost = $config["host"];
        $dbPort = $config['port'];
        $dbName = $config["dbName"];
        $userName = $config["user"];
        $password = $config["password"];

        $dbDsn = "mysql:host=$dbHost;port=$dbPort;";
        if ($hasInitDB) {
            $dbDsn .= "dbname=$dbName;";
        }

        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            PDO::ATTR_PERSISTENT => true,
        );
        $this->db = new PDO($dbDsn, $userName, $password, $options);

        if (!$hasInitDB) {
            $sql = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci; USE `$dbName`;";
            $this->db->exec($sql);

            $this->executeMysqlScript();
            ZalyConfig::setInitedDB();
        }
    }


    protected function executeMysqlScript()
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $mysqlScriptPath = WPF_ROOT_DIR . "/model/database-sql/mysql.sql";

        $_sqlContent = file_get_contents($mysqlScriptPath);
        $_sqlArr = explode(';', $_sqlContent);

        try {
            $this->db->beginTransaction();
            foreach ($_sqlArr as $sql) {
                $this->db->exec($sql);
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            $this->logger->error($tag, $e);
            throw $e;
        }

    }
}