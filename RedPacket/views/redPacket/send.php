<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>发红包</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="../../public/manage/config.css"/>

    <style>

        .red_packet_textarea_parent {
            height: 76px;
            width: 100%;
            background: rgba(255, 255, 255, 1);
            align-items: center;
            border-radius: 6px;
            align-items: center;
        }

        .red_packet_textarea {
            height: 54px;
            width: 99%;
            background: rgba(255, 255, 255, 1);
            border-width: 0;
            outline: none;
            font-size: 16px;
            font-family: PingFangSC-Regular;
            font-weight: 400;
            margin-top: 11px;
        }

        .red_packet_input {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 1);
            border-radius: 6px;
            border-width: 0;
            font-size: 16px;
            font-family: PingFangSC-Regular;
            font-weight: 400;
            color: rgba(27, 27, 27, 1);
            text-align: right;
            outline: none;
            margin-right: 10px;
        }

        .red_packet_amount {
            width: 100%;
            height: 48px;
            font-size: 48px;
            font-family: PingFangSC-Semibold;
            font-weight: 600;
            color: rgba(27, 27, 27, 1);
            line-height: 48px;
            margin-top: 26px;
            margin-bottom: 26px;
        }

        .red_packet_real {
            display: flex;
            justify-content: center;
        }

        .red_packet_bg {
            background: rgba(255, 255, 255, 1);
        }

        .red_packet_send {
            width: 100%;
            height: 50px;
            background: rgba(223, 84, 88, 1);
            border-radius: 6px 7px 7px 7px;
            font-size: 18px;
            font-family: PingFangSC-Medium;
            font-weight: 500;
            color: rgba(255, 255, 255, 1);
            border-width: 0;
            outline: none;
        }

        .red_packet_send:active {
            background: rgba(226, 195, 196, 1);
        }

        .red_packet_tip {
            font-size: 14px;
            font-family: PingFangSC-Regular;
            font-weight: 400;
            color: rgba(153, 153, 153, 1);
            line-height: 14px;
            margin-left: 25px;
            margin-top: 5px;
        }

        .margin_title {
            margin-top: 30px;
        }

        .margin_amount {
            margin-top: 11px;
        }

        .margin_desc {
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="wrapper" id="wrapper">

    <!--  site basic config  -->
    <div class="layout-all-row">

        <div class="list-item-center">

            <div class="item-row red_packet_bg margin_title" id="red-packet-amount">
                <div class="item-body">
                    <div class="item-body-display">
                        <div class="item-body-desc">总金额</div>
                        <div class="item-body-tail">
                            <div class="item-body-value">
                                <input type="number" min=0.1 max=500.0 step="0.1" class="red_packet_input"
                                       placeholder="0.00" id="rp-amount-input">
                            </div>
                            <div class="item-body-value-more">元</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="red_packet_tip">拼手气红包</div>

            <div class="item-row red_packet_bg margin_amount" id="red-packet-quantity">
                <div class="item-body">
                    <div class="item-body-display">
                        <div class="item-body-desc">红包个数</div>
                        <div class="item-body-tail">
                            <div class="item-body-value">
                                <input type="number" min="1" max="100" class="red_packet_input" placeholder="数量"
                                       id="rp-quantity-input">
                            </div>
                            <div class="item-body-value-more">个</div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="red_packet_tip">本群共X人</div>


            <div class="item-row margin_desc">
                <div class="red_packet_textarea_parent">
                    <textarea class="red_packet_textarea" placeholder="恭喜发财，万事如意" id="rp-desc-input"></textarea>
                </div>
            </div>

            <div class="item-row">
                <div class="red_packet_amount">
                    <div class="red_packet_real" id="red-packet-realamount">
                        <div>¥&nbsp;</div>
                        <div>100.00</div>
                    </div>
                </div>
            </div>

            <div class="item-row">
                <button class="red_packet_send" onclick="sendRedPacket();">塞钱进红包</button>
            </div>

        </div>

    </div>


</div>

<script type="text/javascript" src="../../public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>

<script type="text/javascript" src="../../public/sdk/zalyjsNative.js"></script>

<script type="text/javascript">


    function checkQuantity(value) {
        var reg = new RegExp("^[0-9]+(.[0-9]{2})?$");
        if (!reg.test(value)) {
            alert("不符合!");
            return false;
        }
    }


    function checkQuantity(value) {
        var reg = new RegExp("^[0-9]*$");
        if (!reg.test(value)) {
            return false;
        }

        if (value > 1000 || value <= 0) {
            return false;
        }
    }

    $("#red-packet-amount").bind("input propertychange", function (event) {
        var amount = $("#rp-amount-input").val();


    });


    $("#red-packet-quantity").bind("input propertychange", function (event) {
        var quantity = $("#rp-quantity-input").val();
        checkQuantity(quantity);
    });


    function sendRedPacket() {
        var totalAmount = $("#rp-amount-input").val();
        var quantity = $("#rp-quantity-input").val();
        var desc = $("#rp-desc-input").val();

        var url = "./index.php?action=api.redPacket.send";

        var data = {
            "total": totalAmount,
            "quality": quantity,
            "description": desc,
        };
        zalyjsCommonAjaxPostJson(url, data, sendResponse)
    }

    function sendResponse(url, data, result) {
        if (result) {

            var res = JSON.parse(result);
            if ("success" != res.errCode) {
                alert(res.errInfo);
                zalyjsClosePage();
            }
        } else {
            alert("发送红包失败");
        }
        zalyjsClosePage();
    }

</script>


</body>
</html>




