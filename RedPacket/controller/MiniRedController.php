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

        $luckUserId = false;
        $luckAmount = 0;
        $grabbersProfile = [];
        if ($grabbers) {
            foreach ($grabbers as $grabber) {

                $userId = $grabber["userId"];
                $userProfile = $this->dcApi->getUserProfile($userId);

                $userProfile = json_decode($userProfile, true);
                $userNickname = $userProfile["body"]["profile"]["public"]["nickname"];
                $userAvatar = $userProfile["body"]["profile"]["public"]["avatar"];

                $grabber["nickname"] = $userNickname;
                $grabber["avatar"] = $this->getAvatarPath($userAvatar);

                $amount = $grabber['amount'];

                if ($amount > $luckAmount) {
                    $luckAmount = $amount;
                    $luckUserId = $userId;
                }

                $grabbersProfile[$userId] = $grabber;
            }
        }

        if ($luckUserId) {
            $grabbersProfile[$luckUserId]["luckDucker"] = true;
        }

        return $grabbersProfile;
    }

    protected function getUserProfile($userId)
    {
        $userProfile = $this->dcApi->getUserProfile($userId);

        $userProfile = json_decode($userProfile, true);

        $userNickname = $userProfile["body"]["profile"]["public"]["nickname"];
        $userAvatar = $userProfile["body"]["profile"]["public"]["avatar"];
        $loginName = $userProfile["body"]["profile"]["public"]["loginName"];
        $userInfo = [];
        $userInfo["nickname"] = $userNickname;
        $userInfo["avatar"] = $this->getAvatarPath($userAvatar);
        $userInfo['loginName'] = $loginName;

        return $userInfo;
    }

    protected function getAvatarPath($userAvatar)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        if (empty($userAvatar)) {
            return null;
        }
        try {
            $fileNameArray = explode("-", $userAvatar);
            $dirName = $fileNameArray[0];
            $fileName = $fileNameArray[1];

            $fileName = str_replace("../", "", $fileName);
            $dirName = str_replace("../", "", $dirName);

            return $this->siteAddress . "/attachment/" . $dirName . "/" . $fileName;
        } catch (Exception $e) {
            $this->wpf_Logger->error($tag, $e->getMessage());
        }
        return null;

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

            error_log("print=====each random amount =" . $tempValue);

            $arr_num[$i] = $tempValue + $defaultValue;
            $leftAmount = $leftAmount - $tempValue;
        }

        $totalSum = array_sum($arr_num);

        $cost = bcsub($amount, $totalSum, 2);

        if ($cost == 0) {
            return $arr_num;
        } else {
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
            return round($result, 2);
        }
        return 0;
    }

    function getServerAddress()
    {
        $scheme = $_SERVER['REQUEST_SCHEME'];
        $serverHost = $_SERVER['HTTP_HOST'];

        $serverAddress = $scheme . "://" . $serverHost;
        return $serverAddress;
    }

    protected function isSiteAdmin()
    {
        $admins = $this->dcApi->getSiteAdmins();

        $admins = json_decode($admins, true);

        $adminUserIds = [];
        if (!empty($admins)) {

            $publicProfiles = $admins["body"]["publicProfiles"];

            $adminUserIds = array_column($publicProfiles, "userId");

        }
        return in_array($this->userId, $adminUserIds);
    }
}