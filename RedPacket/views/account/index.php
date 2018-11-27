<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>我的余额</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/account.css?v=1"/>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="account_box">
            <div class="money_img"><img src="./public/img/account/money.png"></div>
            <div class="money_tip">我的余额</div>
            <div class="money">￥ <?php echo $account['amount']; ?> 元</div>
            <button class="recharge_account">充值</button>
            <button class="withdraw_account">提现</button>
        </div>
        <div class="div_line">
        </div>
        <div class="account_bill">
            <div class="bill-log-div" style="margin-top:10px;">
                <div class="table">
                    <div class="row" style="border-top: 1px solid #999999;">
                        <div class="row-head cell">ID</div>
                        <div class="row-head cell">时间</div>
                        <div class="row-head cell">金额</div>
                        <div class="row-head cell">状态</div>
                        <div class="data cell">答复</div>
                    </div>

                    <?php foreach ($records as $record) { ?>
                        <div class="row bill_id_<?php echo $record['id'] ?>" style="border-top: 1px solid #999999;">
                            <div class="row-head cell"><?php echo $record['id'] ?></div>
                            <div class="row-head cell"><?php
                                $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                                $timeMillis = $record['createTime'];
                                if ($timeMillis >= $beginToday * 1000) {
                                    echo date("H:i", $timeMillis / 1000);
                                } else {
                                    echo date("Y-m-d H:i", $timeMillis / 1000);
                                }
                                ?></div>
                            <div class="row-head cell"><?php echo $record['amount'] ?></div>

                            <div class="row-head cell">
                                <?php if ($record['status'] == -1) { ?>
                                    [<?php echo $record['type'] == 1 ? "充值" : "提现"; ?>]已拒绝
                                <?php } elseif ($record['status'] == 0) { ?>
                                    [<?php echo $record['type'] == 1 ? "充值" : "提现"; ?>]中
                                <?php } elseif ($record['status'] == 1) { ?>
                                    [<?php echo $record['type'] == 1 ? "充值" : "提现"; ?>]完成
                                <?php } ?>
                            </div>
                            <div class="data cell"></div>
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

    $(".recharge_account").click(function () {
        var url = "<?php echo $serverAddress;?>/index.php?action=page.account&page=recharge";
        openPage(url);
    });

    $(".withdraw_account").click(function () {
        var url = "<?php echo $serverAddress;?>/index.php?action=page.account&page=withdraw";
        openPage(url);
    });

</script>
</body>
</html>

