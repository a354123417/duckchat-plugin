<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的余额</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/recharge.css"/>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="recharge_box">
            <div class="recharge_tip">充值金额</div>
            <div class="recharge_money_div">
                <span>￥</span>
                <input type="text" class="recharge_money"/>
            </div>
            <div class="line">
            </div>
            <textarea class="remark" placeholder="请备注充值渠道信息…"></textarea>
            <button class="confirm_operation submit_disable">充值</button>
        </div>

    </div>
</div>
<script type="text/javascript" src="./public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="./public/sdk/zalyjsNative.js"></script>
<script type="text/javascript">

    $(".recharge_money").on("input porpertychange", function () {
        var valueMoney = $(".recharge_money").val();
        if(valueMoney != undefined && valueMoney.length>0) {
            $(".confirm_operation").removeClass("submit_disable");
            $(".confirm_operation").addClass("submit");
            return;
        }
        $(".confirm_operation").addClass("submit_disable");
        $(".confirm_operation").removeClass("submit");
    });

    $(".confirm_operation").on("click", function () {
         var money = $(".recharge_money").val();
         var remark = $(".remark").val();
         //TODO post recharge
    });


</script>
</body>
</html>

