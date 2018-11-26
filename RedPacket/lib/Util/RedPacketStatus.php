<?php
/**
 *
 * Author: SAM<an.guoyue254@gami.com>
 * Date: 2018/11/23
 * Time: 1:56 PM
 */

class RedPacketStatus
{
    //正常的用户账号
    const AccountDisable = 0;
    const AccountNormal = 1;

    //账户充值
    const AccountRechargeType = 1;
    const AccountWithdrawType = 2;

    const AccountRefuseStatus = -1;    //拒绝说
    const AccountTodoStatus = 0;    //处理中
    const AccountDoneStatus = 1;    //处理完成

    //红包状态
    const grabbingStatus = 0;
    const grabbedStatus = 1;
}