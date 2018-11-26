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
        $money = trim($_POST['money']);
        $remarks = "";

        if (empty($money)) {
            $params["errInfo"] = "金额不能为空";
        } else {
            $result = $this->withdrawMoney($this->userId, $money, $remarks);
            if ($result) {
                $params["errCode"] = "success";
            } else {
                $params["errInfo"] = "提现失败，请重试";
            }
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