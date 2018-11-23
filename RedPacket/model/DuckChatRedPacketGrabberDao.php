<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 8:00 PM
 */

class DuckChatRedPacketGrabberDao extends BaseDao
{
    private $table = "DuckChatRedPacketGrabbers";

    private $columns = [
        "id",
        "packetId",
        "userId",
        "amount",
        "number",
        "status", //0 未被抢 1 抢夺
        "grabTime",
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function insertGrabbers($data)
    {
        $data['status'] = RedPacketStatus::grabbingStatus;
        return $this->saveData($this->table, $data, $this->columns);
    }

    public function updateRedPacket($data, $where)
    {
        return $this->updateData($this->table, $where, $data, $this->columns);
    }

    public function lockRedPacket($id)
    {
        $sql = "select * from $this->table where id=$id for update;";

        return $this->ctx->db->exec($sql);
    }

    public function queryRedPacketGrabbers($packetId, $userId = false, $number = false, $status = false, $limit = false)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where packetId=:packetId ";

        if ($userId) {
            $sql .= "and userId=:userId";
        }

        if ($number !== false) {
            $sql .= "and number=:number";
        }

        if ($status !== false) {
            $sql .= "and status=:status";
        }

        if ($limit !== false) {
            $sql .= " limit $limit;";
        }

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":packetId", $packetId);
            if ($userId) {
                $prepare->bindValue(":userId", $userId);
            }
            if ($number !== false) {
                $prepare->bindValue(":number", $number);
            }

            if ($status !== false) {
                $prepare->bindValue(":status", $status);
            }

            $flag = $prepare->execute();
            $result = $prepare->fetchAll(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $packetId, $startTime);
        }
        return false;
    }


    public function queryRedPacketGrabbersCount($packetId, $status = false)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select count(id) from $this->table where packetId=:packetId ";

        if ($status !== false) {
            $sql .= "and status=:status";
        }

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":packetId", $packetId);

            if ($status !== false) {
                $prepare->bindValue(":status", $status);
            }

            $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_COLUMN);
            return $result;
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        } finally {
            $this->logger->writeSqlLog($tag, $sql, $packetId, $startTime);
        }
        return false;
    }

}