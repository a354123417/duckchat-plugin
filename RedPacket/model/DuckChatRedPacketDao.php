<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 8:00 PM
 */

class DuckChatRedPacketDao extends BaseDao
{
    private $table = "DuckChatRedPacket";

    private $columns = [
        "id",
        "packetId",
        "userId",
        "totalAmount",
        "quantity",
        "description",
        "isGroup",
        "roomId",
        "sendTime",
        "finishTime",
    ];

    private $queryColumns;

    public function __init()
    {
        $this->queryColumns = implode(",", $this->columns);
    }

    public function insertRedPacket($data)
    {
        $data['sendTime'] = ZalyHelper::getCurrentTimeMillis();
        $data['isGroup'] = empty($data['isGroup']) ? 0 : 1;

        return $this->saveData($this->table, $data, $this->columns);
    }

    public function updateRedPacket($data, $where)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        return $this->updateData($this->table, $where, $data, $this->columns);
    }

    public function queryRedPacket($packetId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where packetId=:packetId;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":packetId", $packetId);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
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

}