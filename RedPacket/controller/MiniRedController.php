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
        error_log("===============" . $ex);
        echo $ex->getMessage() . "->" . $ex->getTraceAsString();
    }

    protected function getUserAccount($userId)
    {
        $account = $this->ctx->DuckChatUserAccountDao->queryUserAccount($userId);
        error_log("===============user Account=" . var_export($account, true));
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

}