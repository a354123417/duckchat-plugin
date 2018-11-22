<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 08/08/2018
 * Time: 8:00 PM
 */

class DuckChatUserAccountDao extends BaseCtx
{
    private $table = "DuckChatUserAccount";

    private $columns = [
        "id",
        "userId",
        "amount",
    ];

    private $queryColumns;

    public function __construct()
    {
        parent::__construct();
        $this->queryColumns = implode(",", $this->columns);
    }

    public function addUserAccount($data)
    {
        return parent::saveData($this->table, $data, $this->columns);
    }

    public function saveOrUpdateSite($data, $where)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;

        $result = false;
        try {
            $result = $this->saveData($this->table, $where, $data, $this->columns);
        } catch (Exception $e) {
            $this->Wpf_Logger->error($tag, $e);
        }

        if (!$result) {
            return $this->updateSite($where, $data);
        }

        return $result;

    }

    public function updateSite($data, $where)
    {
        return parent::updateData($this->table, $where, $data, $this->columns);
    }

    public function querySite($siteId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $startTime = $this->getCurrentTimeMills();
        $sql = "select $this->queryColumns from $this->table where siteId=:siteId;";

        try {
            $prepare = $this->db->prepare($sql);
            $this->handlePrepareError($tag, $prepare);
            $prepare->bindValue(":siteId", $siteId);
            $flag = $prepare->execute();
            $result = $prepare->fetch(\PDO::FETCH_ASSOC);
            if ($flag && $result) {
                return $result;
            }
        } catch (Exception $e) {
            $this->Wpf_Logger->error($tag, $e);
        } finally {
            $this->Wpf_Logger->writeSqlLog($tag, $sql, $siteId, $this->getCurrentTimeMills() - $startTime);
        }
        return null;
    }

}