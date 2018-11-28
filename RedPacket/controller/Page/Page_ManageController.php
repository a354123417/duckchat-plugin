<?php

/**
 * User Accoun Management Page
 * User: zhangjun
 * Date: 23/11/2018
 * Time: 10:24 AM
 */
class Page_ManageController extends MiniRedController
{

    protected $pageSize = 20;


    protected function preRequest()
    {
        //管理员权限，check user is site manager
        if (!$this->isSiteAdmin()) {
            throw new Exception("No Permissions");
        }
    }

    /**
     * http get request
     */
    protected function doGet()
    {
        $pageView = isset($_GET['page']) ? $_GET['page'] : "";

        switch ($pageView) {
            case "detail":
                $params["record"] = $this->handleRecord();
                $params["serverAddress"] = $this->getServerAddress();
                echo $this->display("manage_detail", $params);
                break;
            default :
                $records = $this->getRecordLists(1);
                $params['records'] = $records;
                $params["serverAddress"] = $this->getServerAddress();
                echo $this->display("manage_index", $params);
        }

        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        $pageNum = isset($_POST['pageNum']) ? $_POST['pageNum'] : 1;
        $pageNum = trim($pageNum);
        $lists = $this->getRecordLists($pageNum);
        echo json_encode($lists);
        return;
    }

    protected function handleRecord()
    {
        $recordId = isset($_GET['recordId']) ? $_GET['recordId'] : "";

        if (empty($recordId)) {
            throw new Exception("记录ID为空");
        }

        $recordInfo = $this->ctx->DuckChatUserAccountRecordsDao->queryAccountRecord($recordId);

        $userInfo = $this->getUserProfile($recordInfo['userId']);
        $recordInfo['nickname'] = $userInfo['nickname'];
        $recordInfo['avatar'] = $userInfo['avatar'];
        $recordInfo['loginName'] = $userInfo['loginName'];
        return $recordInfo;
    }

    protected function getRecordLists($pageNum)
    {
        $pageSize = $this->pageSize;
        $records = $this->ctx->DuckChatUserAccountRecordsDao->queryAllAccountRecords($pageNum, $pageSize);
        return $records;
    }

}