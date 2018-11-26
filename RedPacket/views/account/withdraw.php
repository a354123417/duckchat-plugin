<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的余额</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/recharge.css?v=2"/>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="recharge_box">
            <div class="recharge_tip">提现金额</div>
            <div class="recharge_money_div">
                <span>￥</span>
                <input type="text" class="withdraw_money"/>
            </div>
            <div class="line">
            </div>
            <div class="withdraw_tip">
                当前全部余额￥<span class="all_money"><?php echo $account['amount']; ?></span>，<span
                        class="withdraw_all">全部提现</span>
            </div>
            <button class="confirm_operation submit_disable">提现</button>
        </div>

    </div>
</div>
<script type="text/javascript" src="./public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>
<script type="text/javascript" src="./public/sdk/zalyjsNative.js"></script>
<script type="text/javascript">

    $(".withdraw_money").on("input porpertychange", function () {
        checkOperstaionButtonStatus();
    });

    $(".withdraw_all").on("click", function () {
        var allMoney = $(".all_money").html();
        $(".withdraw_money").val(allMoney);
        checkOperstaionButtonStatus();
    });

    function checkOperstaionButtonStatus() {
        var valueMoney = $(".withdraw_money").val();
        if (valueMoney != undefined && valueMoney.length > 0) {
            $(".confirm_operation").removeClass("submit_disable");
            $(".confirm_operation").addClass("submit");
            return;
        }
        $(".confirm_operation").addClass("submit_disable");
        $(".confirm_operation").removeClass("submit");
    }


    $(".confirm_operation").on("click", function () {
        var money = $(".withdraw_money").val();

        var url = "./index.php?action=api.account.withdraw";
        var data = {
            "money": money,
        };

        zalyjsCommonAjaxPostJson(url, data, withdrawResponse);
    });


    function withdrawResponse(url, data, result) {
        if (result) {
            var res = JSON.parse(result);
            if ("success" != res.errCode) {
                alert(res.errInfo);
            } else {
                alert("申请提现成功,等待站长审核");
            }

        } else {
            alert("提现失败，请重试");
        }

        zalyjsClosePage();
    }

</script>
</body>
</html>

