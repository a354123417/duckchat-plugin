
<script id="tpl-site-register-nickname" type="text/html">
    <div class="login_input_phone_div" >
        <div class="d-flex flex-row justify-content-center " style="margin-top: 8rem;">
            <span class="login_phone_tip_font edit_user_info">编辑个人信息</span>
        </div>

        <div class="loginNameDiv" style="margin-top: 7rem;">
            <div class="d-flex flex-row input_div justify-content-between">
                <input type="text" class="register_site register_nickname" placeholder="输入用户昵称" >
            </div>
            <div class="line"></div>
        </div>

        {{if enableInvitationCode != "1" && enableRealName != "1"}}
            <div class="d-flex flex-row justify-content-center ">
                <button type="button" class="btn site_register_btn" allow_real_name="0" disabled><span class="span_btn_tip">注册并登录</span></button>
            </div>
        {{else}}
            {{ if enableInvitationCode == "1"}}
                <div class="d-flex flex-row justify-content-center ">
                    <button type="button" class="btn register_next_btn" register-data="invitation_code" disabled><span class="span_btn_tip">下一步</span></button>
                </div>
            {{else}}
                <div class="d-flex flex-row justify-content-center ">
                    <button type="button" class="btn register_next_btn " register-data="real_name" disabled><span class="span_btn_tip">下一步</span></button>
                </div>
            {{/if}}
        {{/if}}
    </div>
</script>


<script id="tpl-site-register-invitecode" type="text/html">
    <div class="login_input_phone_div" >
        <div class="d-flex flex-row justify-content-center margin-top8">
            <span class="login_phone_tip_font edit_user_info">输入邀请码</span>
        </div>

        <div class="inviteCodeDiv" >
            <div class="d-flex flex-row input_div justify-content-between">
                <input type="text" class="register_site register_invitecode" placeholder="请输入邀请码" >
            </div>
            <div class="line"></div>
        </div>

        {{if enableRealName != "1"}}
        <div class="d-flex flex-row justify-content-center ">
            <button type="button" class="btn site_register_btn" allow_real_name="0" ><span class="span_btn_tip">注册并登录</span></button>
        </div>
        {{else}}
        <div class="d-flex flex-row justify-content-center ">
            <button type="button" class="btn register_next_btn " register-data="real_name" disabled><span class="span_btn_tip">下一步</span></button>
        </div>
        {{/if}}
    </div>
</script>

<script id="tpl-site-register-realname" type="text/html">
    <div class="login_input_phone_div" >
        <div class="d-flex flex-row justify-content-center margin-top8">
            <span class="login_phone_tip_font edit_user_info">实名授权</span>
        </div>

        <div class="d-flex flex-row justify-content-left margin-top8" style="margin-bottom: 0.15rem;">
            <img src="../../public/img/phone.png" style="height:2rem;margin-right: 1rem;margin-top: 0.5rem;">
            <span class="realname_phone">{{phoneNumber}}</span>
        </div>
        <div class="line"></div>
        <div class="agree_realname_for_site">
            继续使用即代表同意 <span class="site-domain">{{siteDomain}}</span>站点实名授权
        </div>
        <div class="d-flex flex-row justify-content-center margin-top5" >
            <button type="button" class="btn cancel_realname" >取消</button>
            <button type="button" class="btn  site_register_btn agree_realname_btn" allow_real_name="1">同意，授权并登录</button>
        </div>
    </div>
</script>