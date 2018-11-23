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
            throw new Exception("红包已经失效");
        }

        $totalAmount = $redPacketInfo['totalAmount'];

        $grabbers = $this->getRedPacketGrabbers($packetId);
        $grabberCount = empty($grabbers) ? 0 : count($grabbers);

        if ($grabberCount < $totalAmount) {
            $quantity = $redPacketInfo['quantity'];
            $result = $this->grabRedPacket($packetId, $this->userId, $quantity);
            if ($result) {
                $result['errCode'] = "success";
            }
        } else {
            //抢光了
            $result['errCode'] = "success";
        }


        echo json_encode($params);
        return;
    }

    //grab red packet
    private function grabRedPacket($packetId, $userId, $quantity)
    {
        //遍历循环，获取没有被抢走的
        $grabbingPackets = $this->ctx->DuckChatRedPacketGrabberDao->queryRedPacketGrabbers($packetId,
            false, false, RedPacketStatus::grabbingStatus);

        if (empty($grabbingPackets)) {
            return true;
        }

        $count = count($grabbingPackets);

        for ($j = 0; $j < $count; $j++) {
            $randomKey = array_rand($grabbingPackets, 1);
            $randomRedPacket = $grabbingPackets[$randomKey];
            $currentTime = ZalyHelper::getCurrentTimeMillis();
            $result = $this->calculateGrabbingRedPackets($packetId, $userId, $randomRedPacket, $currentTime);

            if ($result) {
                //检测所有红包是不是抢完
                $this->checkAllRedPackets($packetId, $quantity, $currentTime);
                return true;
            }
            //remove it after used
            unset($grabbingPackets[$randomKey]);
        }

    }

    private function calculateGrabbingRedPackets($packetId, $userId, $randomRedPacket, $currentTime)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        $this->ctx->db->beginTransaction();

        try {
            $id = $randomRedPacket["id"];

            error_log("=============grab red packet packetId=" . $packetId . " id=" . $id);

            //add table row lock
            $result = $this->ctx->DuckChatRedPacketGrabberDao->lockRedPacket($id);

            if (!$result) {
                throw new Exception("红包已经被抢1");
            }

            $data = [
                "status" => RedPacketStatus::grabbedStatus,
            ];
            $where = [
                "packetId" => $packetId,
            ];

            $result = $this->ctx->DuckChatRedPacketGrabberDao->updateRedPacket($data, $where);

            if (!$result) {
                throw new Exception("红包已经被抢2");
            }

            $data = [
                "userId" => $userId,
                "status" => RedPacketStatus::grabbedStatus,
                'grabTime' => $currentTime,
            ];
            $where = [
                "packetId" => $packetId,
            ];
            $result = $this->ctx->DuckChatRedPacketGrabberDao->updateRedPacket($data, $where);

            if (!$result) {
                throw new Exception("更新我的红包状态失败");
            }

            //inc my amount

            $this->ctx->db->commit();
            return true;
        } catch (Exception $e) {
            $this->ctx->db->rollBack();
            $this->logger->error($tag, $e);
        }
        return false;
    }

    private function checkAllRedPackets($packetId, $quantity, $currentTime)
    {
        $grabbersCount = $this->getRedPacketGrabbersCount($packetId);

        if ($grabbersCount >= $quantity) {
            $this->ctx->DuckChatRedPacketDao->updateRedPacketFinish($packetId, $currentTime);
        }

    }

}