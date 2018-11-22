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
        $this->getRequestParams();

        $packetId = $_GET['packetId'];

        $isGrabber = $this->isPacketGrabber($packetId, $this->userId);

        $params = [
            'packetId' => $packetId,
        ];

        if ($isGrabber) {
            echo $this->display("redPacket_grabber", $params);
        } else {
            echo $this->display("redPacket_grab", $params);
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

    private function isPacketGrabber($packetId, $userId)
    {
        $grabber = $this->ctx->DuckChatRedPacketGrabberDao->queryRedPacketGrabbers($packetId, $userId);

        if ($grabber) {
            return true;
        }
        return false;
    }

}