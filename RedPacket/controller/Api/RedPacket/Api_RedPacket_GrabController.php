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
        // TODO: Implement doGet() method.
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

        $result = $this->grabRedPacket($packetId, $this->userId);


        if ($result) {
            $result['errCode'] = "success";
        }

        echo json_encode($params);
        return;
    }

    private function grabRedPacket($packetId, $userId)
    {
        $data = [
            "packetId" => $packetId,
            "userId" => $userId,
            "amount" => 2.0,
        ];

        return $this->ctx->DuckChatRedPacketGrabberDao->insertGrabbers($data);
    }
}