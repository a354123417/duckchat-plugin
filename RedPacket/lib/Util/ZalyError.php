<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 19/07/2018
 * Time: 5:08 PM
 */

class ZalyError
{
    public $errorCodeType   = "error.code.type";
    public $errorPhoneType  = "error.phone.type";
    public $errorCodeValide = "error.code.valide";
    public $errorPreSessionId = "error.preSessionId";
    public $errorExistsLoginName = "error.exists.loginName";
    public $errorInfo = [
        'error.code.type'   => '获取验证码类型不对',
        'error.phone.type'  => '手机号不正确',
        'error.code.valide' => '验证码错误',
        "error.preSessionId" => "登录过期",
        "error.exists.loginName" => "用户名已经存在",
    ];

    public function getErrorInfo($errorCode)
    {
        return $this->errorInfo[$errorCode];
    }
}