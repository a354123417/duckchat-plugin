<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 13/07/2018
 * Time: 6:32 PM
 */

use Google\Protobuf\Any;
use Zaly\Proto\Core\TransportData;
use Zaly\Proto\Core\TransportDataHeaderKey;
use Google\Protobuf\Internal\Message;

abstract class BaseController extends \Wpf_Controller
{
    protected $logger;
    protected $ctx;
    protected $httpHeader = ["KeepSocket" => true];
    protected $headers = [];
    protected $bodyFormatType;
    protected $bodyFormatArr = [
        "json",
        "pb",
        "base64pb"
    ];
    protected $defaultBodyFormat = "json";
    private $requestTransportData;
    protected $action = '';
    public $defaultErrorCode = "success";
    private $cookieTimeOut = 3600 * 24;////24小时有效
    private $cookieName = "zaly_user";

    // return the name for parse json to Any.
    abstract public function rpcRequestClassName();

    abstract public function rpcResponseClassName();

    // waiting for son~
    abstract public function rpc(\Google\Protobuf\Internal\Message $request, \Google\Protobuf\Internal\Message $transportData);

    public function getPreSessionIdByCookie()
    {
        try {
            $preSessionId = isset($_COOKIE[$this->cookieName]) ? $_COOKIE[$this->cookieName] : "";
            if (!$preSessionId) {
                throw new Exception("cookie is not found");
            }
            return $preSessionId;
        } catch (Exception $ex) {
            error_log("error_msg " . $ex->getMessage());
            return "";
        }
    }

    public function setCookie($val)
    {
        setcookie($this->cookieName, $val, time() + $this->cookieTimeOut, "/", "", false, true);
    }

    /**
     * 设置transData header
     * @param $key
     * @param $val
     */
    public function setTransDataHeaders($key, $val)
    {
        $key = "_{$key}";
        $this->headers[$key] = $val;
    }

    public function keepSocket()
    {
        header("KeepSocket: true");
    }

    public function setRpcError($errorCode, $errorInfo)
    {
        $this->setTransDataHeaders(TransportDataHeaderKey::HeaderErrorCode, $errorCode);
        $this->setTransDataHeaders(TransportDataHeaderKey::HeaderErrorInfo, $errorInfo);
    }

    /**
     * 返回需要格式的数据
     * @param $action
     * @param Message $response
     */
    public function rpcReturn($action, \Google\Protobuf\Internal\Message $response)
    {

        $anyBody = new Any();
        $anyBody->pack($response);

        $transData = new TransportData();
        $transData->setAction($action);
        $transData->setBody($anyBody);
        $transData->setHeader($this->headers);
        $transData->setPackageId($this->requestTransportData->getPackageId());

        $body = "";
        if ("json" == $this->bodyFormatType) {
            $body = $transData->serializeToJsonString();
            $body = trim($body);
        } elseif ("pb" == $this->bodyFormatType) {
            $body = $transData->serializeToString();
        } elseif ("base64pb" == $this->bodyFormatType) {
            $body = $transData->serializeToString();
            $body = base64_encode($body);
        } else {
            return;
        }
        echo $body;
    }

    // ignore.~

    public function __construct(BaseCtx $context)
    {
//        $this->ctx = $context;
        $this->ctx = $context;
        $this->logger = $context->getLogger();
    }

    /**
     * 处理方法， 根据bodyFormatType, 获取transData
     * @return string|void
     */
    public function doIndex()
    {
        // 判断请求格式 json， pb, pb64
        // body_format 只从$_GET中接收
        $this->action = $_GET['action'];


        $this->bodyFormatType = isset($_GET['body_format']) ? $_GET['body_format'] : "";
        $this->bodyFormatType = strtolower($this->bodyFormatType);

        if (!in_array($this->bodyFormatType, $this->bodyFormatArr)) {
            $this->bodyFormatType = $this->defaultBodyFormat;
        }

        // 接收的数据流
        $reqData = file_get_contents("php://input");

//        $this->ctx->Wpf_Logger->info("api-request", "action=" . $this->action);
//        $this->ctx->Wpf_Logger->info("api-request", "body_format=" . $this->bodyFormatType);
//        $this->ctx->Wpf_Logger->info("api-request", "body_data=" . $reqData);

        // 将数据转为TransportData
        $this->requestTransportData = new \Zaly\Proto\Core\TransportData();

        ////判断 request proto 类 是否存在。
        $requestClassName = $this->rpcRequestClassName();
        if (class_exists($requestClassName, true)) {
            $usefulForProtobufAnyParse = new $requestClassName();
        } else {
            trigger_error("no request proto class: " . $requestClassName, E_USER_ERROR);
            die();
        }

        try {
            if ("json" == $this->bodyFormatType) {
                $this->requestTransportData->mergeFromJsonString($reqData);
            } elseif ("pb" == $this->bodyFormatType) {
                $this->requestTransportData->mergeFromString($reqData);
            } elseif ("base64pb" == $this->bodyFormatType) {
                $realData = base64_decode($reqData);
                $this->requestTransportData->mergeFromString($realData);
            }
        } catch (Exception $e) {
            $info = sprintf("parse proto error, format: %s, error: %s", $this->bodyFormatType, $e->getMessage());
            // disabled the rpcReturn online.
            $this->setRpcError("error.proto.parse", $info);
            $responseClassName = $this->rpcResponseClassName();
            $this->rpcReturn($this->action, new $responseClassName());
            die();
        }

        $this->handleTransportDataHeader();

        $requestMessage = $usefulForProtobufAnyParse;
        ////解析请求数据，
        if (null !== $this->requestTransportData->getBody()) {
            $requestMessage = $this->requestTransportData->getBody()->unpack();
        }
        $this->rpc($requestMessage, $this->requestTransportData);
    }


    private function handleTransportDataHeader()
    {
        $headers = $this->requestTransportData->getHeader();

        foreach ($headers as $key => $val) {
            $key = str_replace("_", "", $key);
            $headers[$key] = $val;
        }
        $this->requestTransportData->setHeader($headers);
    }


    protected function getCurrentTimeMills()
    {
        return $this->ctx->ZalyHelper->getMsectime();
    }

    public function setPreSessionIdForUser($userId, $sitePubkPem, $nickname = '', $invitationCode = '', $allowShareRealname = false)
    {
        $preSessionId = $this->ctx->ZalyHelper->generateStrId();
        ///TODO preSessionId 存入redis，
        $userInfo = [
            "userId" => $userId,
            "sitePubkPem" => $sitePubkPem,
            "invitationCode" => $invitationCode,
            "allowShareRealname" => $allowShareRealname,
            "nickname" => $nickname,
        ];
        $preSessionIdKey = ZalyConfig::getConfig("preSessionIdKey");
        $flag = $this->ctx->ZalyRedis->hset($preSessionIdKey, $preSessionId, json_encode($userInfo));
        if (!$flag) {
            throw new Exception("login failed");
        }
        return $preSessionId;
    }
}