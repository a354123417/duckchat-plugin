<?php

/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 17/11/2018
 * Time: 2:25 PM
 */
class Page_IndexController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        $this->getRequestParams();
        echo $this->display("redPacket_send", []);
        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        // TODO: Implement doPost() method.
        error_log("===========do post request");
    }

}