<?php
/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 2018/11/21
 * Time: 7:53 PM
 */

class Api_RedPacket_SendController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {

    }

    /**
     * http post request
     */
    protected function doPost()
    {
        $params = [
            "errCode" => "error",
        ];

        $userId = $this->userId;
        $total = trim($_POST['total']);
        $quality = trim($_POST['quality']);
        $description = trim($_POST['description']);

        $requestParams = $this->getRequestParams();
        $isGroup = $requestParams['isGroup'];
        $roomId = $requestParams['roomId'];


        $packetId = ZalyHelper::generateRandomKey(16);

        $result = $this->sendRedPacket($packetId, $userId, $total, $quality, $description, $isGroup, $roomId);

        if ($result) {
            $params["errCode"] = "success";
            $this->proxyRedPacketMessage($packetId, $isGroup, $roomId);
        }

        echo json_encode($params);
        return;
    }


    private function sendRedPacket($packetId, $userId, $totalAmount, $quantity, $description, $isGroup, $roomId)
    {
        $data = [
            "packetId" => $packetId,
            "userId" => $userId,
            "totalAmount" => $totalAmount,
            "quantity" => $quantity,
            "description" => $description,
            "isGroup" => $isGroup,
            "roomId" => $roomId,
        ];

        return $this->ctx->DuckChatRedPacketDao->insertRedPacket($data);
    }

    private function proxyRedPacketMessage($packetId, $isGroup, $roomId)
    {
        $fromUserId = $this->userId;
        $toId = $roomId;

        $title = "[红包]";
        $width = 230;
        $height = 84;
        $cssAddress = "http://192.168.3.4:8088/public/manage/red.css?version=200";
        $iconAddress = "http://192.168.3.4:8088/public/img/red-icon.png";
        $webCode = '<!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset="UTF-8">
                            <title>红包</title>
                            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
                            <link rel="stylesheet" href="' . $cssAddress . '"/>
                        </head>
                        <body>
                        <div class="wrapper">
                            <div class="red_packet_show red_packet_content">
                                <div class="red-desc"><img class="red-icon" src="' . $iconAddress . '"></div>
                                <div class="red-desc">
                                    <div class="red-desc-text">恭喜发财，万事如意！</div>
                                    <div class="red-see-text">查看红包</div>
                                </div>
                            </div>
                            <div class="red_packet_tail">
                                <div class="tail-text">红包</div>
                                <div class="tail-text">DuckChat</div>
                            </div>
                        </div>
                        </body>
                        </html>';
        $webCode = str_replace(PHP_EOL, "", $webCode);
        $gotoUrl = "http://192.168.3.4:8088/index.php?action=page.grab?packetId=" . $packetId;
        $this->dcApi->sendWebMessage($isGroup, $fromUserId, $toId, $title, $webCode, $width, $height, $gotoUrl);
    }
}