<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login</title>
    <!-- Latest compiled and minified CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>

<div class="zaly_container" >
    <div class="zaly_login zaly_login_by_phone">
        <div class="login-header" style="height:6rem;background-color: #6B52FF; text-align: center;">
            <div style="font-size:2.25rem;line-height: 6rem;font-family:PingFangSC-Regular;font-weight:500;color: #FFFFFF;">
                你即将登录站点
                 <span class="site-domain"></span>
            </div>
        </div>

        <div class="login_input_phone_div " style="margin-top:8rem; " >
            <div class="d-flex flex-row justify-content-center login-header" >
                <span class="login_phone_tip_font">手机号登录</span>
            </div>

            <div class=" d-flex flex-row justify-content-left margin-top3 phoneNumberDiv">
                    <img src="../../public/img/phone.png" style="height:2rem;margin-right: 1rem;margin-top: 0.5rem;">
                <input type="text" class="phone_num  login_input_phone"  placeholder="输入手机号" >
            </div>
            <div class="line"></div>
            <div class="d-flex flex-row input_div" style="margin-top: 2rem;position: relative">
                <img src="../../public/img/verify_code.png" style="height:2rem;margin-right: 1rem;margin-top: 0.5rem;">
                <input type="text" class="login_input_verify_code "placeholder="输入验证码" >
                <span class="get_verify_code" onclick="getVerifyCode()">获取验证码</span>
            </div>

            <div class="line"></div>

            <div class="loginNameDiv" style="display: none;margin-top: 2rem;">
                <div class="d-flex flex-row input_div justify-content-between">
                    <input type="text" class="loginName" placeholder="输入登录名" >
                </div>
                <div class="line"></div>
                <span style="color: rgba(223,223,223,1);">16字符以内，可包含数字,字母,下划线,</span>
            </div>

            <div class="d-flex flex-row justify-content-center ">
                <button type="button" class="btn login_button" style="margin-top: 4rem;"><span class="span_btn_tip">登录</span></button>
            </div>
        </div>
    </div>
    <div class="zaly_login zaly_site_register zaly_site_register-nickname" style="display: none;">

    </div>
    <div class="zaly_login zaly_site_register zaly_site_register-invitecode" style="display: none;">

    </div>

    <div class="zaly_login zaly_site_register zaly_site_register-realname" style="display: none;">

    </div>
</div>

<?php include (dirname(__DIR__) ."/Login/template_register.php");?>

<input type="hidden" value="<?php echo $domain; ?>" class="domain">
<script src="../../public/js/zalyTransportData.js"></script>

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/2.2.4/jquery.js"></script>
<script src="../../public/js/zalyjsNative.js"></script>
<script src="../../public/js/template-web.js"></script>

<script src="../../public/js/zalyHelper.js"></script>
<script src="../../public/js/login.js"></script>

</body>
</html>
