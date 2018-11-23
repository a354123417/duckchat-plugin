<?php

/**
 * Class BaseCtx
 *
 * @property DuckChatUserAccountDao DuckChatUserAccountDao
 * @property DuckChatRedPacketDao DuckChatRedPacketDao
 * @property DuckChatRedPacketGrabberDao DuckChatRedPacketGrabberDao
 *
 * @property ZalyRedis ZalyRedis
 * @property ZalyMysql ZalyMysql
 * @property Wpf_Logger Wpf_Logger
 *
 * @property ZalyHelper ZalyHelper
 * @property ZalyCurl ZalyCurl
 * @property ZalyRsa ZalyRsa
 *
 */
class BaseCtx extends Wpf_Ctx
{
    protected $logger;
    public $db;

    public function __construct()
    {
        $mysqlDB = new ZalyMysql();
        $this->db = $mysqlDB->db;
        $this->logger = new Wpf_Logger();
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