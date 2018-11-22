<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 20/09/2018
 * Time: 6:57 PM
 */

abstract class BaseDao
{
    /**
     * @var BaseCtx
     */
    protected $ctx;
    protected $logger;
    protected $db;

    public function __construct(BaseCtx $ctx)
    {
        $this->ctx = $ctx;
        $this->db = $ctx->db;
        $this->logger = $ctx->getLogger();

        $this->__init();
    }

    abstract function __init();

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
        }

        if ("42S02" == $prepare->errorCode()) {
            throw new Exception(var_export($prepare->errorInfo(), true));
        }
        return false;
    }

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

        $this->logger->writeSqlLog($tag, $sql, $data, $startTime);

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
        $this->logger->writeSqlLog($tag, $sql, $data, $startTime);
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
            $this->logger->error($tag, $error);
            throw new Exception("execute prepare fail");
        }

    }

    protected function getCurrentTimeMills()
    {
        return $this->ctx->ZalyHelper->getMsectime();
    }

}