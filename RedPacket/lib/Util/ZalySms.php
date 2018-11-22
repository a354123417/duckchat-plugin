<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 18/07/2018
 * Time: 2:43 PM
 */

require __DIR__ . "/../sms/src/index.php";

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;

use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;

class ZalySms
{
    // 短信应用SDK AppID
    private $appid = 1400063986; // 1400开头

// 短信应用SDK AppKey
    private $appkey = "6a468cc0ce6a85df972cf6c2a1cfb73e";


    // 短信模板ID，需要在短信应用中申请
    private $templateId = 80031;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

    // 签名
    private $smsSign = "北京阿卡信"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`

    public function sendMsg($phoneNumber, $code, $phoneCountryCode="86")
    {
        // 指定模板ID单发短信
        try {
            $ssender = new SmsSingleSender($this->appid, $this->appkey);
            $params = [$code, 5];
            $result = $ssender->sendWithParam($phoneCountryCode, $phoneNumber, $this->templateId,
                $params, $this->smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result);
            error_log("json encode == ".$result);
            return $result;
        } catch(\Exception $e) {
            error_log("json encode == ".$e->getMessage());

        }
    }
}
