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
        //不支持get
        return false;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        $params = [
            "errCode" => "error",
        ];

        try {
            $userId = $this->userId;
            $sendAmount = trim($_POST['total']);
            $quality = trim($_POST['quality']);
            $description = trim($_POST['description']);
            $requestParams = $this->getRequestParams();
            $isGroup = $requestParams['isGroup'];
            $roomId = $requestParams['roomId'];
            $sendUserAccount = $this->getUserAccount($userId);
            $userAmount = $sendUserAccount["amount"];

            if (empty($sendAmount) || $sendAmount < 0 || !is_numeric($sendAmount)) {
                throw new Exception("发送余额错误");
            }

            if ($sendAmount > $userAmount) {
                throw new Exception("账户余额不足，请联系站长充值");
            }

            if ($sendAmount < $quality * 0.01) {
                throw new Exception("人均金额小雨0.01元");
            }

            $packetId = ZalyHelper::generateRandomKey(16, "0123456789");
            $result = $this->sendRedPacket($packetId, $userId, $userAmount, $sendAmount, $quality, $description, $isGroup, $roomId);
            if ($result) {
                $params["errCode"] = "success";
                $this->proxyRedPacketMessage($packetId, $isGroup, $roomId);
            }
        } catch (Exception $e) {
            $params["errInfo"] = $e->getMessage();
            $this->logger->error($this->action, $e);
        }

        echo json_encode($params);
        return;
    }

    protected function getUserAccount($userId)
    {
        $account = parent::getUserAccount($userId);

        if ($account) {
            return $account;
        }

        throw new Exception("用户账户金额为空");
    }

    private function sendRedPacket($packetId, $userId, $userAmount, $sendAmount, $quantity, $description, $isGroup, $roomId)
    {
        $result = false;
        $tag = __CLASS__ . "->" . __FUNCTION__;
        //开启事务
        $this->ctx->db->beginTransaction();

        try {
            //firstly，reduce user account
            $account_data = [
                "amount" => $userAmount - $sendAmount,
            ];
            $account_where = [
                "userId" => $userId,
            ];

            $result = $this->ctx->DuckChatUserAccountDao->updateUserAccount($account_data, $account_where);

            if (!$result) {
                throw new Exception("扣除用户账户失败");
            }

            //secondly，add redPacket
            $redPacket_data = [
                "packetId" => $packetId,
                "userId" => $userId,
                "totalAmount" => $sendAmount,
                "quantity" => $quantity,
                "description" => $description,
                "isGroup" => $isGroup,
                "roomId" => $roomId,
            ];
            $result = $this->ctx->DuckChatRedPacketDao->insertRedPacket($redPacket_data);

            if (!$result) {
                throw new Exception("生成红包失败");
            }

            //last，create each amount for user
            $amountArray = $this->generateRedData($sendAmount, $quantity);
            for ($i = 0; $i < $quantity; $i++) {
                $grabberData = [
                    "packetId" => $packetId,
                    "number" => $i,
                    "amount" => $amountArray[$i],
                ];
                $result = $this->ctx->DuckChatRedPacketGrabberDao->insertGrabbers($grabberData);
                if (!$result) {
                    throw new Exception("生成红包随机金额失败");
                }
            }

            $this->ctx->db->commit();
        } catch (Exception $e) {
            $this->ctx->db->rollBack();
            throw $e;
        }

        return $result;
    }

    private function proxyRedPacketMessage($packetId, $isGroup, $roomId)
    {
        $serverAddress = $this->getServerAddress();

        $fromUserId = $this->userId;
        $toId = $roomId;

        $title = "[红包]";
        $width = 230;
        $height = 84;
        $cssAddress = $serverAddress . "/public/manage/red.css?version=200";
        $iconAddress = $serverAddress . "/public/img/red-icon.png";
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
        $gotoUrl = $serverAddress . "/index.php?action=page.grab&packetId=" . $packetId;
        $this->dcApi->sendWebMessage($isGroup, $fromUserId, $toId, $title, $webCode, $width, $height, $gotoUrl);
    }
}