<?php

/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 17/11/2018
 * Time: 2:25 PM
 */
class Page_GrabController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        error_log("===========do get request");
        $this->getRequestParams();

        $type = $_GET['type'];

        if (isset($type)) {
            echo $this->display("redPacket_grabber", []);
        } else {
            //判断用户是否抢过红包
            echo $this->display("redPacket_grab", []);
        }

        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        // TODO: Implement doPost() method.
        error_log("===========do post request");

        return;
    }

}