<?php
/**
 * 小程序基础类
 * Author: SAM<an.guoyue254@gmail.com>
 * Date: 21/11/2018
 * Time: 10:58 AM
 */

abstract class MiniProgramController extends \Wpf_Controller
{
    protected $logger;

    protected $action;

    protected $userProfile; //json
    protected $userId;
    protected $loginName;

    protected $success = "success";
    protected $error = "error.alert";

    protected $ctx;

    protected $language = 1;
    protected $requestData;

    private $miniProgramId = 200;
    private $miniProgramSecretKey = "Q968Pix85z2wLRDqZ4C89Bgg0mb5Apvz";   //小程序的密钥
    protected $siteAddress = "http://192.168.3.4:8888";

    /**
     * @var
     */
    protected $dcApi;

    public function __construct(BaseCtx $context)
    {
        $this->ctx = $context;
        $this->logger = $context->getLogger();


        $config = ZalyConfig::getAllConfig();

        $this->siteAddress = $config["duckChatAddress"];
        $this->miniProgramId = $config["miniProgramId"];
        $this->miniProgramSecretKey = $config["miniProgramSecretKey"];

        $this->dcApi = new DC_Open_Api($this->siteAddress,
            $this->miniProgramId,
            $this->miniProgramSecretKey);
    }

    //for permission

    /**
     * 在处理正式请求之前，预处理一些操作，比如权限校验
     * @return bool
     */
    protected abstract function preRequest();

    /**
     * http get request
     */
    abstract protected function doGet();

    /**
     * http post request
     */
    abstract protected function doPost();

    /**
     * preRequest && doRequest 发生异常情况，执行
     * @param $ex
     * @return mixed
     */
    protected abstract function requestException($ex);

    /**
     * 根据http request cookie中的duckchat_sessionId 做权限判断
     * @return string|void
     */
    public function doIndex()
    {
        $tag = __CLASS__ . "-" . __FUNCTION__;

        try {
            parent::doIndex();

            // 接收的数据流
            $this->requestData = file_get_contents("php://input");

            $action = $_GET['action'];
            $this->action = $action;

            //兼容web
            $duckchatSessionId = isset($_GET["duckchat_sessionid"]) ? trim($_GET["duckchat_sessionid"]) : false;

            if (empty($duckchatSessionId)) {
                $duckchatSessionId = isset($_COOKIE["duckchat_sessionid"]) ? trim($_COOKIE["duckchat_sessionid"]) : false;
            } else {
                setcookie("duckchat_sessionid", $duckchatSessionId);
            }

            if (empty($duckchatSessionId)) {
                throw new Exception("duckchat_sessionid is empty in cookie");
            }

            $userPublicProfile = $this->dcApi->getSessionProfile($duckchatSessionId);

            if (empty($userPublicProfile)) {
                throw new Exception("get empty user profile by duckchat_sessionid error");
            }

            $userPublicProfile = json_decode($userPublicProfile, true);

            $this->userProfile = $userPublicProfile['body']['profile']['public'];
            $this->userId = $this->userProfile['userId'];
            $this->loginName = $userPublicProfile['body']['profile']['public']['loginName'];
            $this->logger->info("", "Mini Program Request UserId=" . $this->userId);

            $this->preRequest();

            $method = $_SERVER["REQUEST_METHOD"];

            if ($method == "POST") {
                $this->doPost();
            } elseif ($method == "GET") {
                $this->doGet();
            }

        } catch (Exception $ex) {
            echo $ex->getMessage();
            $this->ctx->Wpf_Logger->error($tag, "error msg =" . $ex);
            $this->requestException($ex);
        }

        return;
    }

    protected function getRequestParams()
    {
        $duckPageUrl = isset($_GET["duckchat_page_url"]) ? trim($_GET["duckchat_page_url"]) : false;

        if (empty($duckPageUrl)) {
            $duckPageUrl = isset($_COOKIE["duckchat_page_url"]) ? trim($_COOKIE["duckchat_page_url"]) : false;
        } else {
            setcookie("duckchat_page_url", $duckPageUrl);
        }

        $urlParams = parse_url($duckPageUrl);

        parse_str(trim($urlParams['query']), $queries);

        $pageType = isset($queries['page']) ? $queries['page'] : "";

        $params = [
            "roomId" => $queries['x'],
        ];

        if ("groupMsg" == $pageType) {
            $params["isGroup"] = true;
        } elseif ("u2Msg" == $pageType) {
            $params["isGroup"] = false;
        } else {
            throw new Exception("none support pageType");
        }
        return $params;
    }

    protected function getCurrentTimeMills()
    {
        return ZalyHelper::getMsectime();
    }

    protected function finish_request()
    {
        if (!function_exists("fastcgi_finish_request")) {
            function fastcgi_finish_request()
            {
            }
        }
        fastcgi_finish_request();
    }

    protected function getLanguageText($zhText, $enText)
    {
        return $this->language == 1 ? $zhText : $enText;
    }

}