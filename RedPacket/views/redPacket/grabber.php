<html>

<head>
    <title>红包</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../../public/css/grabber.css"/>
</head>
<style>
</style>

<body>
<div class="wrapper">
    <div><img class="user-avatar" src="http://www.jituwang.com/uploads/allimg/151003/258203-1510030RP894.jpg"></div>
    <div class="red-center">
        <div class="send-user">运营部-李萌</div>
    </div>
    <div class="red-center">
        <div class="red-desc">恭喜发财，万事如意</div>
    </div>
    <div class="red-center">
        <div class="red-amount">1.12 元</div>
    </div>

    <div class="layout-all-row">

        <div class="list-item-center">
            <div class="item-title">
                <div class="item-title-content">
                    8个红包，50秒被抢光
                </div>
            </div>
            <div class="division-line"></div>

            <div class="item-row">
                <div class="item-header">
                    <img class="grabber-avatar"
                         src="http://www.jituwang.com/uploads/allimg/151003/258203-1510030RP894.jpg"/>
                </div>
                <div class="item-body">
                    <div class="item-body-display">
                        <div class="item-body-desc">我的朋友们</div>

                        <div class="item-body-tail">
                            2.12元
                        </div>
                    </div>
                </div>
            </div>
            <div class="division-line"></div>

            <div class="item-row">
                <div class="item-header">
                    <img class="grabber-avatar"
                         src="http://www.jituwang.com/uploads/allimg/151003/258203-1510030RP894.jpg"/>
                </div>
                <div class="item-body">
                    <div class="item-body-display">
                        <div class="item-body-desc">我的朋友们</div>

                        <div class="item-body-tail">
                            2.12元
                        </div>
                    </div>
                </div>
            </div>
            <div class="division-line"></div>


        </div>

    </div>
</div>

<script type="text/javascript" src="../../public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>

<script type="text/javascript" src="../../public/sdk/zalyjsNative.js"></script>

<script type="text/javascript">

    function grabRedPacket() {
        var url = "http://192.168.3.4:8088/index.php?action=api.redPacket.grab";

        var data = {
            "packetId": "10001",
        };

        alert("url=" + url);
        zalyjsCommonAjaxPostJson(url, data, grabResponse)
    }

    function grabResponse(url, data, result) {
        alert("result=" + result);
        var url = "http://192.168.3.4:8088/index.php?action=page.grab&type=grabber";
        zalyjsOpenPage(url);
    }

</script>

</body>

</html>