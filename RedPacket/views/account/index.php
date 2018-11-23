<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的余额</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/account.css"/>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="account_box">
            <div class="money_img"><img src="./public/img/account/money.png"></div>
            <div class="money_tip">我的余额</div>
            <div class="money">￥1000.45</div>
            <button class="recharge_account">充值</button>
            <button class="withdraw_account">提现</button>

        </div>
        <div class="div_line">
        </div>
        <div class="account_bill">
            <div class="bill-log-div" style="margin-top:10px;">
                <div class="table">
                    <div class="row" style="border-top: 1px solid #999999;">
                        <div class="row-head cell">时间</div>
                        <div class="row-head cell">ID</div>
                        <div class="row-head cell">金额</div>
                        <div class="row-head cell">状态</div>
                        <div class="data cell">答复</div>
                    </div>

                    <?php foreach ($billLogs as $log) {?>
                        <div class="row bill_id_<?php echo $log['id']?>" style="border-top: 1px solid #999999;">
                            <div class="row-head cell"><?php echo $log['createTime']?></div>
                            <div class="row-head cell"><?php echo $log['loginName']?></div>
                            <div class="row-head cell"><?php echo $log['money']?></div>
                            <div class="row-head cell"><?php echo $log['status']?></div>
                            <div class="data cell"><?php echo $log['reply']?></div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="./public/sdk/zalyjsNative.js"></script>
<script type="text/javascript">

    function openPage(url) {
        if (isMobile()) {
            zalyjsOpenNewPage(url);
        } else {
            zalyjsOpenPage(url);
        }
    }
    // http://192.168.3.152:8089/index.php?action=page.redAccount
    $(".recharge_account").click(function () {
        var url = "/index.php?action=page.redAccount&page=recharge";
        openPage(url);
    });
    $(".withdraw_account").click(function () {
        var url = "/index.php?action=page.redAccount&page=withdraw";
        openPage(url);
    });

</script>
</body>
</html>
