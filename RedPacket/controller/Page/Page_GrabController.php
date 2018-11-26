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
        $isOver = isset($_GET['viewDetails']) ? true : false;
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

        $grabbersCount = $this->getRedPacketGrabbersCount($packetId);
        $isGrabbedOver = $grabbersCount >= $redPacketQuantity;
        $isOver = $isOver && $isGrabbedOver;

        $sendUserProfile = $this->dcApi->getUserProfile($sendUserId);

        $sendUserProfile = json_decode($sendUserProfile, true);
        $sendUserNickname = $sendUserProfile["body"]["profile"]["public"]["nickname"];
        $sendUserAvatar = $sendUserProfile["body"]["profile"]["public"]["avatar"];

        $isGrabber = $this->isPacketGrabber($packetId, $this->userId);

        $params = [
            'packetId' => $packetId,
            'redPacketQuantity' => $redPacketQuantity,
            'sendUserNickname' => $sendUserNickname,
            'sendUserAvatar' => $this->siteAddress . "/_api_file_download_/?fileId=" . $sendUserAvatar,
            'redPacketAmount' => $redPacketAmount . "元",
            'redPacketDesc' => !empty($redPacketDesc) ? $redPacketDesc : "恭喜发财，万事如意",
        ];


        if ($redPacketSendTime > $this->getCurrentTimeMills() - 24 * 60 * 60 * 1000) {

        }


        if ($isGrabber || $isOver) {
            //get grabbers
            $grabbers = $this->getRedPacketGrabbersWithProfile($packetId);
            $params['redPacketGrabbers'] = $grabbers;

            if ($isGrabbedOver) {
                $costTimeSec = ($redPacketFinishTime - $redPacketSendTime) / 1000;
                $overTip = $redPacketQuantity . "个红包，" . $costTimeSec . "秒被抢光";
                $params['redPacketTip'] = $overTip;
            } else {
                $overTip = $redPacketQuantity . "个红包，已被抢了" . $grabbersCount . "个";
                $params['redPacketTip'] = $overTip;
            }
            echo $this->display("redPacket_grabber", $params);
        } else {
            if ($isGrabbedOver) {
                $params['redPacketDesc'] = "手慢了，红包派完了";
            }
            $params["isGrabbedOver"] = $isGrabbedOver;
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