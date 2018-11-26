<?php
/**
 * User Account Management
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 2018/11/26
 * Time: 2:02 PM
 */

class Api_Manage_ConfirmController extends MiniRedController
{

    protected function preRequest()
    {
        //管理员权限，check user is site manager
    }

    /**
     * http get request
     */
    protected function doGet()
    {
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
        $recordId = isset($_POST['recordId']) ? $_POST['recordId'] : "";
        $recordId = trim($recordId);

        $agreeStatus = isset($_POST['agreeStatus']) ? $_POST['agreeStatus'] : false;

        $feedBack = isset($_POST['feedBack']) ? $_POST['feedBack'] : "账户金额错误";

        if (empty($recordId)) {
            throw new Exception("错误：记录ID为空");
        }

        $recordInfo = $this->getRecord($recordId);

        if ($recordInfo["status"] >= 1) {
            throw new Exception("错误：交易记录已完成");
        } elseif ($recordInfo["status"] <= -1) {
            throw new Exception("错误：交易已被拒绝");
        }

        if ($agreeStatus) {
            $applyUserId = $recordInfo["userId"];
            $applyUserAccount = $this->ctx->DuckChatUserAccountDao->queryUserAccount($applyUserId);
            $applyId = $applyUserAccount["id"];
            if (RedPacketStatus::AccountRechargeType == $recordInfo["type"]) {
                //充值
            } elseif (RedPacketStatus::AccountWithdrawType == $recordInfo["type"]) {

            }
        } else {
            //refuse
            $result = $this->refuseRecord($recordId, $feedBack);
            if ($result) {
                $params["errCode"] = "success";
            } else {
                $params["errInfo"] = "拒绝操作失败";
            }
        }

        return;
    }

    private function getRecord($recordId)
    {
        return $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecord($recordId);
    }

    private function refuseRecord($recordId, $feedBack)
    {
        return $this->ctx->DuckChatUserAccountRecordsDao->refuseRecord($recordId, $this->userId, $feedBack);
    }

    private function agreeRecharge($applyId, $applyUserId, $recordId, $feedBack)
    {
        $tag = __CLASS__ . "->" . __FUNCTION__;
        try {
            $this->ctx->db->beginTransaction();

            $recordInfo = $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecordForLock($recordId);

            $rechargeAmount = $recordInfo["amount"];
            $rechargeStatus = $recordInfo["status"];

            //check
            $result = $this->checkoutStatus($rechargeStatus);

            $applyUserAccount = $this->ctx->DuckChatUserAccountDao->queryAccountForLock($applyId);

            if (empty($applyUserAccount) || empty($applyId)) {
                //insert into
                $data = [
                    "userId" => $applyUserId,
                    "amount" => $rechargeAmount,
                ];
                $result = $this->ctx->DuckChatUserAccountDao->addUserAccount($data);
            } else {
                //update
                $data = [
                    "amount" => $rechargeAmount + $applyUserAccount["amount"],
                ];
                $where = [
                    "userId" => $applyUserId,
                ];
                $result = $this->ctx->DuckChatUserAccountDao->updateUserAccount($data, $where);
            }

            if (!$result) {
                throw new Exception("向用户账号充值过程失败");
            }

            $result = $this->ctx->DuckChatUserAccountRecordsDao->agreeRecord($recordId, $this->userId, $feedBack);

            if (!$result) {
                throw new Exception("同意充值，记录状态更新失败");
            }

            $this->ctx->db->commit();

            return true;
        } catch (Exception $e) {
            $this->ctx->db->rollBack();
            $this->logger->error($tag, $e);
        }

        return false;
    }


    private function agreeWithdraw($applyId, $applyUserId, $recordId, $feedBack)
    {

        $tag = __CLASS__ . "->" . __FUNCTION__;
        try {
            $this->ctx->db->beginTransaction();

            $recordInfo = $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecordForLock($recordId);

            $withdrawAmount = $recordInfo["amount"];
            $withdrawStatus = $recordInfo["status"];

            //check
            $result = $this->checkoutStatus($withdrawStatus);

            $applyUserAccount = $this->ctx->DuckChatUserAccountDao->queryAccountForLock($applyId);

            if (!empty($applyUserAccount)) {

                $userHasAmount = $applyUserAccount["amount"];
                if ($userHasAmount < $withdrawAmount) {
                    throw new Exception("用户账户金额不足");
                }

            } else {
                throw new Exception("用户账户金额为空");
            }

            //update
            $data = [
                "amount" => $applyUserAccount["amount"] - $withdrawAmount,
            ];
            $where = [
                "userId" => $applyUserId,
            ];
            $result = $this->ctx->DuckChatUserAccountDao->updateUserAccount($data, $where);

            if ($result) {
                throw new Exception("更新用户账户金额失败");
            }

            $result = $this->ctx->DuckChatUserAccountRecordsDao->agreeRecord($recordId, $this->userId, $feedBack);

            if (!$result) {
                throw new Exception("同意提现，记录状态更新失败");
            }

            $this->ctx->db->commit();
            return true;
        } catch (Exception $e) {
            $this->ctx->db->rollBack();
            $this->logger->error($tag, $e);
        }

        return false;
    }

    private function checkoutStatus($status)
    {
        if ($status >= 1) {
            throw new Exception("错误：交易记录已完成");
        } elseif ($status <= -1) {
            throw new Exception("错误：交易已被拒绝");
        } elseif ($status !== 0) {
            throw new Exception("错误：交易状态错误");
        }

        return true;
    }
}