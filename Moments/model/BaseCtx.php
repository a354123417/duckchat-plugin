<?php

/**
 * Class BaseCtx
 *
 *
 * @property PlatformSessionCtx PlatformSessionCtx
 * @property PlatformUserCtx PlatformUserCtx
 * @property DuckChatUserAccountDao PlatformSiteDao
 * @property PlatformUserDeviceDao PlatformUserDeviceDao
 *
 * @property ZalyRedis ZalyRedis
 * @property ZalyMysql ZalyMysql
 * @property Wpf_Logger Wpf_Logger
 *
 * @property ZalyHelper ZalyHelper
 * @property ZalyCurl ZalyCurl
 * @property ZalyRsa ZalyRsa
 *
 * @property MiniProgram_Client MiniProgram_Client
 * @property Mail_Client Mail_Client
 * @property Data_Client Data_Client
 *
 */
class BaseCtx extends Wpf_Ctx
{
    private $logger;
    public $db;

    public function __construct()
    {
        $mysqlDB = new ZalyMysql();
        $this->db = $mysqlDB->db;
        $this->logger = new Wpf_Logger();
    }

    /**
     * 公用的插入方法，基本满足所有的插入状况
     * @param $tableName
     * @param $data
     * @param $defaultColumns
     * @return bool
     * @throws Exception
     */
    protected function saveData($tableName, $data, $defaultColumns)
    {
        $startTime = microtime(true);
        $tag = __CLASS__ . "-" . __FUNCTION__;
        $insertKeys = array_keys($data);
        $insertKeyStr = implode(",", $insertKeys);
        $placeholderStr = "";
        foreach ($insertKeys as $key => $val) {
            if (!in_array($val, $defaultColumns)) {
                continue;
            }
            $placeholderStr .= ",:" . $val . "";
        }
        $placeholderStr = trim($placeholderStr, ",");
        if (!$placeholderStr) {
            throw new Exception("update is fail");
        }

        $sql = " insert into  $tableName({$insertKeyStr}) values ({$placeholderStr});";
        $prepare = $this->db->prepare($sql);
        $this->handlePrepareError($tag, $prepare);

        foreach ($data as $key => $val) {
            if (!in_array($key, $defaultColumns)) {
                continue;
            }
            $prepare->bindValue(":" . $key, $val);
        }

        $flag = $prepare->execute();
        $this->logger->writeSqlLog($tag, $sql, $data, $startTime);
        $count = $prepare->rowCount();

        if ($flag && $count > 0) {
            return true;
        } else {
            $this->logger->info($tag, "db errInfo=" . var_export($prepare->errorInfo(), true));
        }
        return false;
    }

    /**
     * 公用的更新方法，仅仅适用于and更新
     * @param $tableName
     * @param $where
     * @param $data
     * @param $defaultColumns
     * @return bool
     * @throws Exception
     */
    protected function updateData($tableName, $where, $data, $defaultColumns)
    {
        $tag = __CLASS__ . "-" . __FUNCTION__;
        $startTime = microtime(true);
        $updateStr = "";
        $updateKeys = array_keys($data);
        foreach ($updateKeys as $updateField) {
            if (!in_array($updateField, $defaultColumns)) {
                continue;
            }
            $updateStr .= "$updateField=:$updateField,";
        }
        $updateStr = trim($updateStr, ",");
        if (!is_array($where)) {
            throw new Exception("update fail");
        }
        $whereKeys = array_keys($where);
        $whereKeyStr = "";
        foreach ($whereKeys as $k => $val) {
            if (!in_array($val, $defaultColumns)) {
                continue;
            }
            $whereKeyStr .= " $val=:$val and";
        }

        $whereKeyStr = trim($whereKeyStr, "and");

        if (!$whereKeyStr) {
            throw new Exception("update is fail");
        }

        $sql = "update  $tableName set  $updateStr where  $whereKeyStr";

//        $this->Wpf_Logger->writeSqlLog($tag, $sql, $data, $startTime);

        $prepare = $this->db->prepare($sql);
        $this->handlePrepareError($tag, $prepare);
        foreach ($data as $key => $val) {
            if (!in_array($updateField, $defaultColumns)) {
                continue;
            }
            $prepare->bindValue(":" . $key, $val);
        }

        foreach ($where as $key => $val) {
            $prepare->bindValue(":$key", $val);
        }
        $this->Wpf_Logger->writeSqlLog($tag, $sql, $data, $startTime);
        $flag = $prepare->execute();
        $count = $prepare->rowCount();

        if ($flag && $count > 0) {
            return true;
        }
        return false;
    }

    public function handlePrepareError($tag, $prepare)
    {
        if (!$prepare) {
            $error = [
                "error_code" => $this->db->errorCode(),
                "error_info" => $this->db->errorInfo(),
            ];
            $this->Wpf_Logger->error($tag, $error);
            throw new Exception("execute prepare fail");
        }

    }

    protected function getCurrentTimeMills()
    {
        return $this->ZalyHelper->getMsectime();
    }

    public function getLogger()
    {
        return $this->logger;
    }

}