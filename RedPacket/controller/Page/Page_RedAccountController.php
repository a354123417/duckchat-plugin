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
                echo $this->display("account_withdraw", []);
                break;
            default:
                echo $this->display("account_index", []);
        }

        return;
    }

    /**
     * http post request
     */
    protected function doPost()
    {
        // TODO: Implement doPost() method.
        error_log("===========do post request");
    }

}