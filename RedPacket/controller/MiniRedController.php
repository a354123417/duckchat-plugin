<?php
/**
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 21/11/2018
 * Time: 6:32 PM
 */

abstract class MiniRedController extends MiniProgramController
{

    /**
     * 在处理正式请求之前，预处理一些操作，比如权限校验
     * @return bool
     */
    protected function preRequest()
    {
        return true;
    }

    /**
     * preRequest && doRequest 发生异常情况，执行
     * @param $ex
     * @return mixed
     */
    protected function requestException($ex)
    {
        error_log("===============" . $ex);
    }

}