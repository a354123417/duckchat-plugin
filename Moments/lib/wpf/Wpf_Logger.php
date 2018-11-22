<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 13/07/2018
 * Time: 11:48 AM
 */
class Wpf_Logger
{
    private $_level = [
        "info",
        "warn",
        "error"
    ];

    private $fileName = 'openzaly-platform-log';
    private $filePath = '/akaxin/';
    private $handler  = '';
    private $logType  = "";

    public function __construct()
    {
        $this->fileName = $this->fileName."-".date("Ymd").".log";
        $this->filePath = $this->filePath."/".$this->fileName;
//        $this->handler = fopen($this->filePath, "a+");
    }

    public function info($tag, $infoMsg)
    {
        $this->logType = "info";
        $this->writeLog($tag, $infoMsg);
    }

    public function warn($tag, $infoMsg)
    {
        $this->logType = "warn";
        $this->writeLog($tag, $infoMsg);
    }

    public function error($tag, $infoMsg)
    {
        $this->logType = "error";
        $this->writeLog($tag, $infoMsg);
    }


    private function writeLog($tag, $msg)
    {
        if(!in_array($this->logType, $this->_level)) {
            return ;
        }

        if(is_array($msg)) {
            $msg = json_encode($msg);
        }

        $content = "[$this->logType] " . date("Y-m-d H:i:s") . " $tag $msg \n";
//        fwrite($this->handler, $content);
        error_log($content);
    }

    public function writeSqlLog($tag, $sql, $params, $expendTimes)
    {
        if (is_array($params)) {
            $params = json_encode($params);
        }
        $this->logType = "sql";

        $content = "[$this->logType] " . date("Y-m-d H:i:s") . " $tag  sql=$sql  params=$params  expend_time=$expendTimes\n";
//        fwrite($this->handler, $content);
        error_log($content);
    }
}
