<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 11/09/2018
 * Time: 6:50 PM
 */

class ZalyPushLog
{
    private $logType = "info";
    private $fileName = 'push-auth';
    private $fileHandler;

    public function __construct($action)
    {
        $fileName = $action . "-" . date("Ymd") . ".log";

        $filePath = dirname(__DIR__) . "/../../duck.platform.log";
        $filePath = $filePath . "/" . $fileName;
        $this->fileHandler = fopen($filePath, "a+");
    }


    public function info($tag, $infoMsg)
    {
        $this->writeLog($tag, $infoMsg);
    }

    /**
     * write log
     * @param $tag
     * @param $msg
     */
    private function writeLog($tag, $msg)
    {
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }

        $content = "[$this->logType] " . date("Y-m-d_H:i:s") . " $tag $msg \n";

        fwrite($this->fileHandler, $content);
    }

}
