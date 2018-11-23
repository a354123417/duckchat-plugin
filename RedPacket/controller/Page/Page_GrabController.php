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


        $redPacketInfo = $this->getRedPacketInfo($packetId);

        if (!$redPacketInfo) {
            throw new Exception("红包已经失效");
        }

        $redPacketAmount = $redPacketInfo["totalAmount"];
        $redPacketQuantity = $redPacketInfo["quantity"];
        $redPacketSendTime = $redPacketInfo["sendTime"];
        $redPacketFinishTime = $redPacketInfo["finishTime"];
        $redPacketDesc = $redPacketInfo["description"];
        $sendUserId = $redPacketInfo["userId"];

        $sendUserProfile = $this->dcApi->getUserProfile($sendUserId);

        $sendUserNickname = '';

        $isGrabber = $this->isPacketGrabber($packetId, $this->userId);

        $params = [
            'packetId' => $packetId,
            'redPacketQuantity' => $redPacketQuantity,
            'sendUserNickname' => $sendUserNickname,
            'redPacketAmount' => $redPacketAmount,
            'redPacketDesc' => $redPacketDesc,
        ];

        if ($isGrabber) {
            //get grabbers
            $grabbers = $this->getRedPacketGrabbers($packetId);
            $params['redPacketGrabbers'] = $grabbers;
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
        return true;
    }

    private function isPacketGrabber($packetId, $userId)
    {
        $grabber = $this->ctx->DuckChatRedPacketGrabberDao->queryRedPacketGrabbers($packetId,
            $userId, false, RedPacketStatus::grabbedStatus);

        if ($grabber && count($grabber) > 0) {
            return true;
        }
        return false;
    }

}