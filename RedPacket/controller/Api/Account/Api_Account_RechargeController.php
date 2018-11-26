<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 2018/11/26
 * Time: 11:22 AM
 */

class Api_Account_RechargeController extends MiniRedController
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

        try {
            $money = trim($_POST['money']);
            $remarks = "";
            if (empty($money)) {
                $params["errInfo"] = "金额不能为空";
            } else {
                $result = $this->rechargeMoney($this->userId, $money, $remarks);
                if ($result) {
                    $params["errCode"] = "success";
                } else {
                    $params["errInfo"] = "充值失败，请重试";
                }
            }
        } catch (Exception $e) {
            $params["errInfo"] = $e->getMessage();
            $this->logger->error($this->action, $e);
        }

        echo json_encode($params);
        return;
    }


    private function rechargeMoney($userId, $money, $remarks)
    {
        $data = [
            "userId" => $userId,
            "amount" => $money,
            "type" => RedPacketStatus::AccountRechargeType, // 1：充值 2：提现
            "remarks" => $remarks,
        ];
        return $this->ctx->DuckChatUserAccountRecordsDao->addAccountRecords($data);
    }

}