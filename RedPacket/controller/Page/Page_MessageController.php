<?php

/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 17/11/2018
 * Time: 2:25 PM
 */
class Page_MessageController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        echo $this->display("redPacket_message", []);
        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        return;
    }


}