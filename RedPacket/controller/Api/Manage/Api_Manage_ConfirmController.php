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
        $recordId = isset($_POST['recordId']) ? $_POST['recordId'] : "";

        if (empty($recordId)) {
            throw new Exception("错误：记录ID为空");
        }

        $recordInfo = $this->getRecord($recordId);

        if ($recordInfo["status"] == 1) {
            throw new Exception("错误：交易记录已完成");
        }

        if (RedPacketStatus::AccountRechargeType == $recordInfo["type"]) {
            //充值
        } elseif (RedPacketStatus::AccountWithdrawType == $recordInfo["type"]) {

        }

        return;
    }

    private function getRecord($recordId)
    {
        return $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecord($recordId);
    }
}