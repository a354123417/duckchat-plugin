<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 23/11/2018
 * Time: 10:24 AM
 */
class  Page_RedAccount_ManageController  extends MiniRedController
{

    protected $pageSize = 200;
    /**
     * http get request
     */
    protected function doGet()
    {
        $pageView = isset($_GET['page']) ? $_GET['page'] : "";

        switch ($pageView) {
            case "detail":
                $recordDetail = $this->handleRecord();
                echo $this->display("account_manage_detail", $recordDetail);
                break;
            default :
                $records = $this->getRecordLists(0);
                echo $this->display("account_manage_index", $records);
        }

        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        // TODO: Implement doPost() method.
        //TODO handle record
        $operation = isset($_POST['operation']) ? $_POST['operation'] : "";

        error_log("===========do post request");
        switch ($operation) {
            case "handle":
                $recordId = isset($_POST['recordId']) ? $_POST['recordId'] :"" ;
                break;
            case "results":
                $pageNum =  isset($_POST['pageNum']) ? $_POST['pageNum'] : 1;
                $offset = ($pageNum-1)*$this->pageSize;
                $lists =  $this->getRecordLists($offset);
                echo json_encode($lists);
                break;
        }

    }

    protected  function handleRecord()
    {
        $recordId = isset($_POST['recordId']) ? $_POST['recordId'] : "";
        if(!$recordId) {
            return ['id'=> $recordId];
        }
        //TODO get recordId detail

        return ['id'=> $recordId];
    }

    protected function getRecordLists($offset)
    {
        //todo getList
        $results = [];
        for($i=0; $i< 20; $i++) {
            $results[]['id'] = $i+$offset;
        }
        return ['datas' => $results];
    }

}