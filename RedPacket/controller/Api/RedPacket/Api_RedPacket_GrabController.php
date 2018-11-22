<?php
/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 2018/11/21
 * Time: 7:53 PM
 */

class Api_RedPacket_GrabController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        return true;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        $params = [
            "errCode" => "error",
        ];

        $packetId = trim($_POST['packetId']);

        $redPacketInfo = $this->getRedPacketInfo($packetId);

        if (!$redPacketInfo) {
            throw new  Exception("红包已经失效");
        }

        $totalAmount = $redPacketInfo['totalAmount'];

        $grabbers = $this->getRedPacketGrabbers($packetId);
        $grabberCount = empty($grabbers) ? 0 : count($grabbers);

        if ($grabberCount < $totalAmount) {
            //抢光了
            $result = $this->saveGrabRedPacket($packetId, $this->userId);
            if ($result) {
                $result['errCode'] = "success";
            }
        } else {
            $result['errCode'] = "success";
        }


        echo json_encode($params);
        return;
    }

    private function saveGrabRedPacket($packetId, $userId, $amount)
    {
        $data = [
            "packetId" => $packetId,
            "userId" => $userId,
            "amount" => $amount,
        ];

        return $this->ctx->DuckChatRedPacketGrabberDao->insertGrabbers($data);
    }

    private function calculate($leftAmount, $leftNum)
    {

    }

}