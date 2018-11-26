<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 8:00 PM
 */

class DuckChatUserAccountDao extends BaseDao
{
    private $table = "DuckChatUserAccount";

    private $columns = [
        "id",
        "userId",
        "amount",
        "status",
        "createTime",
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function addUserAccount($data)
    {
        $data["createTime"] = ZalyHelper::getCurrentTimeMillis();
        $data["status"] = RedPacketStatus::AccountNormal;
        return $this->saveData($this->table, $data, $this->columns);
    }

    public function updateUserAccount($data, $where)
    {
        return $this->updateData($this->table, $where, $data, $this->columns);
    }

    public function queryUserAccount($userId)
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
            $this->logger->writeSqlLog($tag, $sql, $userId, $startTime);
        }
        return false;
    }

    public function queryAccountForLock($id)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where id=:id for update nowait;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":id", $id, PDO::PARAM_INT);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $id, $startTime);
        }
        return false;
    }

}