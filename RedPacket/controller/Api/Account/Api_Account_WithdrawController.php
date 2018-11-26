<?php
/**
 * Withdraw account
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 2018/11/26
 * Time: 11:22 AM
 */

class Api_Account_WithdrawController extends MiniRedController
{

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
        $money = trim($_POST['money']);
        $remarks = "";

        try {
            if (empty($money)) {
                $params["errInfo"] = "金额不能为空";
            } else {

                $userAccount = $this->getUserAccount($this->userId);

                if (empty($userAccount)) {
                    throw new Exception("账户金额为空");
                } else {

                    $userHasAmount = $userAccount["amount"];

                    if ($userHasAmount < $money) {
                        throw new Exception("账户金额不足");
                    }
                }

                $result = $this->withdrawMoney($this->userId, $money, $remarks);
                if ($result) {
                    $params["errCode"] = "success";
                } else {
                    $params["errInfo"] = "提现失败，请重试";
                }
            }
        } catch (Exception $e) {
            $params["errInfo"] = $e->getMessage();
            $this->logger->error($this->action, $e);
        }

        echo json_encode($params);
        return;
    }

    private function withdrawMoney($userId, $money, $remarks)
    {
        $data = [
            "userId" => $userId,
            "amount" => $money,
            "type" => RedPacketStatus::AccountWithdrawType, // 1：充值 2：提现
            "remarks" => $remarks,
        ];
        return $this->ctx->DuckChatUserAccountRecordsDao->addAccountRecords($data);
    }
}