<html>

<head>
    <title>红包</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../public/css/grab.css?v=2"/>
</head>
<style>
</style>

<body>
<div class="wrapper">
    <div><img class="user-avatar" src="<?php echo $sendUserAvatar; ?>"></div>
    <div class="red-center">
        <div class="send-user"><?php echo $sendUserNickname; ?></div>
    </div>
    <div class="red-center">
        <div class="red-random">发了一个红包,金额随机</div>
    </div>
    <div class="red-center">
        <div class="red-desc"><?php echo $redPacketDesc; ?></div>
    </div>
    <div class="red-center">
        <?php if ($isGrabbedOver) { ?>
            <div class="red-grab-over" onclick="showDetails('<?php echo $packetId; ?>');">点击查看领取详情</div>
        <?php } else { ?>
            <button class="red-open-button" onclick="grabRedPacket('<?php echo $packetId; ?>')">開</button>
        <?php } ?>
    </div>
</div>

<script type="text/javascript" src="../../public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>

<script type="text/javascript" src="../../public/sdk/zalyjsNative.js"></script>

<script type="text/javascript">

    function grabRedPacket(packetId) {
        var url = "./index.php?action=api.redPacket.grab";

        var data = {
            "packetId": packetId,
        };

        zalyjsCommonAjaxPostJson(url, data, grabResponse);
    }

    function grabResponse(url, data, result) {
        if (result) {
            var res = JSON.parse(result);
            if ("success" != res.errCode) {
                alert("抢红包失败，原因：" + res.errInfo);
            }

        } else {
            alert("抢红包失败，请重新操作");
        }
        location.reload();
    }


    function showDetails(packetId) {
        var url = "./index.php?action=page.grab&packetId=" + packetId+"&viewDetails=1;";
        zalyjsOpenPage(url);
    }

</script>

</body>

</html>