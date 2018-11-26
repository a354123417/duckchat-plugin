<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 22/11/2018
 * Time: 5:36 PM
 */

class Page_RedAccountController extends MiniRedController
{

    /**
     * http get request
     */
    protected function doGet()
    {
        $pageView = isset($_GET['page']) ? $_GET['page'] : "";
        switch ($pageView) {
            case "recharge":
                echo $this->display("account_recharge", []);
                break;
            case "withdraw":
                $account = $this->getUserAccount($this->userId);
                $params["account"] = $account;
                echo $this->display("account_withdraw", $params);
                break;
            default:
                $account = $this->getUserAccount($this->userId);
                $params["account"] = $account;
                $params["records"] = $this->getAccountRecords($this->userId);
                echo $this->display("account_index", $params);
        }

        return;
    }

    private function getAccountRecords($userId)
    {
        $records = $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecords($userId);
        error_log("=========get user account records=" . var_export($records, true));
        return $records;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        return true;
    }

}