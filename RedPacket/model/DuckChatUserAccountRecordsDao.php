<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 8:00 PM
 */

class DuckChatUserAccountRecordsDao extends BaseDao
{
    private $table = "DuckChatUserAccountRecords";

    private $columns = [
        "id",
        "userId",
        "amount",
        "type", // 1：充值 2：提现
        "remarks",
        "status",   //0 未处理状态   1：处理完成状态
        "createTime",
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function addAccountRecords($data)
    {
        $data["createTime"] = ZalyHelper::getCurrentTimeMillis();
        $data["status"] = 0;
        return $this->saveData($this->table, $data, $this->columns);
    }

    public function updateAccountRecords($data, $where)
    {
        return $this->updateData($this->table, $where, $data, $this->columns);
    }

    public function queryAccountRecords($userId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where userId=:userId;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":userId", $userId);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $userId, $this->getCurrentTimeMills() - $startTime);
        }
        return false;
    }

}