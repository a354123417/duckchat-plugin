<?php
/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 21/11/2018
 * Time: 6:32 PM
 */

abstract class MiniRedController extends MiniProgramController
{

    /**
     * 在处理正式请求之前，预处理一些操作，比如权限校验
     * @return bool
     */
    protected function preRequest()
    {
        return true;
    }

    /**
     * preRequest && doRequest 发生异常情况，执行
     * @param Exception $ex
     * @return mixed
     */
    protected function requestException($ex)
    {
        echo $ex->getMessage() . "->" . $ex->getTraceAsString();
    }

    protected function getUserAccount($userId)
    {
        $account = $this->ctx->DuckChatUserAccountDao->queryUserAccount($userId);
        return $account;
    }


    protected function getRedPacketInfo($packetId)
    {
        return $this->ctx->DuckChatRedPacketDao->queryRedPacket($packetId);
    }

    protected function getRedPacketGrabbers($packetId)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        try {
            $grabbers = $this->ctx->DuckChatRedPacketGrabberDao->queryRedPacketGrabbers($packetId,
                false, false, RedPacketStatus::grabbedStatus);

            return $grabbers;
        } catch (Exception $e) {
            $this->logger->error($tag, $e);
        }
        return [];
    }

    protected function getRedPacketGrabbersWithProfile($packetId)
    {
        $grabbers = $this->getRedPacketGrabbers($packetId);

        $grabbersProfile = [];
        if ($grabbers) {
            foreach ($grabbers as $grabber) {

                $userId = $grabber["userId"];
                $userProfile = $this->dcApi->getUserProfile($userId);

                $userProfile = json_decode($userProfile, true);
                $userNickname = $userProfile["body"]["profile"]["public"]["nickname"];
                $userAvatar = $userProfile["body"]["profile"]["public"]["avatar"];

                $grabber["nickname"] = $userNickname;
                $grabber["avatar"] = $this->siteAddress . "/_api_file_download_/?fileId=" . $userAvatar;

                $grabbersProfile[] = $grabber;
            }

        }

        return $grabbersProfile;
    }

    protected function getRedPacketGrabbersCount($packetId)
    {
        return $this->ctx->DuckChatRedPacketGrabberDao->queryRedPacketGrabbersCount($packetId, RedPacketStatus::grabbedStatus);
    }

    protected function generateRedData($amount, $number)
    {
        $defaultValue = 0.01;
        //每个人默认0.01
        $arr_num = [];

        $leftAmount = $amount - $defaultValue * $number;

        for ($i = 0; $i < $number; $i++) {
            $tempValue = $this->randomAmount($leftAmount, $number - $i);
            $arr_num[$i] = $tempValue + $defaultValue;
            $leftAmount = $leftAmount - $tempValue;
        }

        $totalSum = array_sum($arr_num);
        error_log("send amount=" . $amount . " number=" . $number . " array sum=" . $totalSum);

        if ($totalSum == $amount) {
            return $arr_num;
        } else {
            error_log("error=====send equal=" . ($totalSum === $amount));
            throw new Exception("红包金额不匹配，请重试");
        }
    }

    function randomAmount($amount, $count)
    {
        if ($count == 1) {
            return $amount;
        }
        if ($amount > 0) {
            $result = random_int(1, $amount * 100 / $count) / 100;
            return $result;
        }
        return 0;
    }

}