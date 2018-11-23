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
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function addUserAccount($data)
    {
        return $this->saveData($this->table, $data, $this->columns);
    }

    public function saveOrUpdateSite($data, $where)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;

        $result = false;
        try {
            $result = $this->saveData($this->table, $where, $data, $this->columns);
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        }

        if (!$result) {
            return $this->updateUserAccount($where, $data);
        }

        return $result;

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
            $this->logger->writeSqlLog($tag, $sql, $userId, $this->getCurrentTimeMills() - $startTime);
        }
        return false;
    }

}