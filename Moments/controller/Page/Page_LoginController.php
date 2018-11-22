<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 17/07/2018
 * Time: 2:25 PM
 */
class Page_LoginController extends BaseController
{
    private $classNameForRequest = "";
    private $classNameForResponse = "";

    public function __construct(Wpf_Ctx $context)
    {
        parent::__construct($context);
    }

    public function doIndex()
    {
        $params =  [
            "domain"  => ZalyConfig::getConfig("domain"),
        ];
        echo $this->display("Login_login", $params);
    }

    public function rpcRequestClassName()
    {
        return $this->classNameForRequest;
    }

    public function rpcResponseClassName()
    {
        return $this->classNameForResponse;
    }

    public function rpc(\Google\Protobuf\Internal\Message $request, \Google\Protobuf\Internal\Message $transportData)
    {
        // TODO: Implement rpc() method.
    }


}