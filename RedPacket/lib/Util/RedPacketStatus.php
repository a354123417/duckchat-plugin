<?php
/**
 *
 * Author: SAM<an.guoyue254@gami.com>
 * Date: 2018/11/23
 * Time: 1:56 PM
 */

class RedPacketStatus
{
    //账户充值
    const AccountRechargeType = 1;
    const AccountWithdrawType = 2;

    const AccountTodoStatus = 0;    //处理中
    const AccountDoneStatus = 1;    //处理完成

    //红包状态
    const grabbingStatus = 0;
    const grabbedStatus = 1;
}