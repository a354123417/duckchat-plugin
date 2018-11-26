<html>

<head>
    <title>红包</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../public/css/grab.css?v=2"/>
</head>
<style>
    .red_back {
        width:325px;
        background:rgba(218,74,78,1);
        position: relative;
        background:#DF5458;
        height: 250px;
    }
    .wrapper {
        position: relative;
        background:rgba(218,74,78,1);
    }
    .open_btn {
        position: absolute;
        top:40%;
        left:0;
        right:0;
    }
    .bgcolor{
        background: #DF5458;
    }

    .bg_circle {
        height: 60px;
        width: 100%;
        border-radius: 50%;
        position: absolute;
        top: 220px;
        background: #DF5458;
    }
</style>

<body>
<div class="wrapper">
          <div class="red_back">
              <div class="bgcolor"><img class="user-avatar" src="<?php echo $sendUserAvatar; ?>"></div>
              <div class="red-center bgcolor">
                  <div class="send-user"><?php echo $sendUserNickname; ?></div>
              </div>
              <div class="red-center bgcolor">
                  <div class="red-random">发了一个红包,金额随机</div>
              </div>
              <div class="red-center bgcolor">
                  <div class="red-desc"><?php echo $redPacketDesc; ?></div>
              </div>
              <div class="bg_circle"></div>
          </div>
        <div class="red-center open_btn" style="position: relative">
            <?php if ($isGrabbedOver) { ?>
                <div class="red-grab-over" style="position: absolute;bottom:50px;" onclick="showDetails('<?php echo $packetId; ?>');">点击查看领取详情</div>
            <?php } else { ?>
                <button class="red-open-button" style="position: absolute;bottom:100px;"  onclick="grabRedPacket('<?php echo $packetId; ?>')">開</button>
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