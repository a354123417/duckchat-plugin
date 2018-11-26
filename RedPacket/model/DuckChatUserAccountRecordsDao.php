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
        "status",   //0 未处理状态   1：处理完成状态 -1 拒绝
        "adminUserId",
        "adminFeedback",
        "createTime",
        "finishTime",
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function addAccountRecords($data)
    {
        $data["createTime"] = ZalyHelper::getCurrentTimeMillis();
        $data["status"] = RedPacketStatus::AccountTodoStatus;
        return $this->saveData($this->table, $data, $this->columns);
    }

    public function updateAccountRecords($data, $where)
    {
        return $this->updateData($this->table, $where, $data, $this->columns);
    }


    public function agreeRecord($recordId, $adminUserId, $adminFeedback)
    {
        $data = [
            "status" => RedPacketStatus::AccountDoneStatus,
            "adminUserId" => $adminUserId,
            "adminFeedback" => $adminFeedback,
            "finishTime" => ZalyHelper::getCurrentTimeMillis(),
        ];

        $where = [
            "id" => $recordId,
        ];

        return $this->updateAccountRecords($data, $where);
    }

    public function refuseRecord($recordId, $adminUserId, $adminFeedback)
    {
        $data = [
            "status" => RedPacketStatus::AccountRefuseStatus,
            "adminUserId" => $adminUserId,
            "adminFeedback" => $adminFeedback,
            "finishTime" => ZalyHelper::getCurrentTimeMillis(),
        ];

        $where = [
            "id" => $recordId,
        ];

        return $this->updateAccountRecords($data, $where);
    }

    public function queryAccountRecordForLock($recordId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where id=:id for update nowait;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":id", $recordId, PDO::PARAM_INT);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $recordId, $startTime);
        }
        return false;
    }

    public function queryAccountRecord($recordId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where id=:id;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":id", $recordId, PDO::PARAM_INT);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $recordId, $startTime);
        }
        return false;
    }

    public function queryUserAccountRecords($userId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where userId=:userId;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":userId", $userId);
            $flag = $prepare->execute();
            $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $userId, $startTime);
        }
        return false;
    }


    public function queryAllAccountRecords($pageNum, $pageSize, $type = false, $status = false)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where id>0 ";

        if ($type !== false) {
            $sql .= " and type=:type";
        }

        if ($status !== false) {
            $sql .= " and status=:status";
        }

        $sql .= " order by id desc limit :offset,:limit;";

        $offset = ($pageNum - 1) * $pageSize;

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);


            if ($type !== false) {
                $prepare->bindValue(":type", $type, PDO::PARAM_INT);
            }

            if ($status !== false) {
                $prepare->bindValue(":status", $status, PDO::PARAM_INT);
            }

            $prepare->bindValue(":offset", $offset, PDO::PARAM_INT);
            $prepare->bindValue(":limit", $pageSize, PDO::PARAM_INT);

            $flag = $prepare->execute();

            $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, [$pageNum, $pageSize, $type, $status], $startTime);
        }
        return false;
    }
}