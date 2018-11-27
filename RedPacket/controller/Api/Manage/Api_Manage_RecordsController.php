<?php
/**
 * Created by PhpStorm.
 * User: anguoyue
 * Date: 2018/11/27
 * Time: 12:09 PM
 */

class Api_Manage_RecordsController extends MiniRedController
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

        $pageNum = isset($_POST['pageNum']) ? $_POST['pageNum'] : 1;
        $offset = ($pageNum - 1) * $this->pageSize;
        $lists = $this->getRecordLists($offset);
        echo json_encode($lists);

    }
}