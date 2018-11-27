<?php

/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 17/11/2018
 * Time: 2:25 PM
 */
class Page_SendController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        // $params = [
        //  "isGroup" => true,
        //  "roomId" => "xxx",
        //];
        $params = $this->getRequestParams();
        echo $this->display("redPacket_send", $params);
        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        return false;
    }

}