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

        $agreeStatus = true;//false;

        $feedBack = "账户金额错误";

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
            if (RedPacketStatus::AccountRechargeType == $recordInfo["type"]) {
                //充值
            } elseif (RedPacketStatus::AccountWithdrawType == $recordInfo["type"]) {

            }
        } else {
            //refuse

        }

        return;
    }

    private function getRecord($recordId)
    {
        return $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecord($recordId);
    }

    private function refuseRecord($recordId, $feedBack)
    {
//        $data = [
//            ""=>
//        ];
//
//        $where = [];
//
//        return $this->ctx->DuckChatUserAccountRecordsDao->updateAccountRecords();

        return;
    }
}