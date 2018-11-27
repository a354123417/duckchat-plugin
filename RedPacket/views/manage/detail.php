<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/record_detail.css?v=3"/>
    <style>
        .site-manage-image {
            border-radius: 0;
        }

        .loginName_div {
            height: 18px;
            line-height: 18px;
            text-align: left;
        }

        .nickname_div {
            font-size: 12px;
            font-family: PingFangSC-Regular;
            font-weight: 400;
            color: rgba(153, 153, 153, 1);
            line-height: 12px;
            height: 12px;
            margin-top: 5px;
            text-align: left;
        }

        .remark {
            width: 95%;
            height: 80px;
            background: rgba(248, 248, 248, 1);
            border-radius: 4px;
            border: 1px solid #DFDFDF;
            margin-top: 30px;
            outline: none;
            resize: none;
            font-size: 14px;
            font-family: PingFangSC-Regular;
            font-weight: 400;
            color: rgba(153, 153, 153, 1);
            padding: 10px;
        }
    </style>
</head>

<body>

<div class="wrapper_div">
    <div class="wrapper" id="wrapper">

        <div class="layout-all-row">
            <div class="list-item-center">

                <div class="item-row" id="site-custom-page">
                    <div class="item-header">
                        <img class="site-manage-image" src="<?php echo $record["avatar"];; ?>"/>
                    </div>
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">
                                <div class="loginName_div">
                                    ID: <?php echo $record['loginName']; ?>
                                </div>
                                <div class="nickname_div">
                                    昵称: <?php echo $record['nickname']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="division-line"></div>


                <div class="item-row">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">ID</div>

                            <div class="item-body-tail"><?php echo $record["id"]; ?></div>
                        </div>

                    </div>
                </div>
                <div class="division-line"></div>

                <div class="item-row">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">时间</div>

                            <div class="item-body-tail"><?php echo date("Y-m-d H:i", $record['createTime'] / 1000); ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="division-line"></div>

                <div class="item-row" id="group-manage-id">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">
                                金额
                            </div>

                            <div class="item-body-tail">
                                <?php echo $record["amount"]; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="division-line"></div>

                <div class="item-row" id="group-manage-id">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">
                                状态
                            </div>

                            <div class="item-body-tail">

                                <div class="row-head cell">
                                    <?php if ($record['status'] == -1) { ?>
                                        拒绝<?php echo $record['type'] == 1 ? "充值" : "提现"; ?>
                                    <?php } elseif ($record['status'] == 0) { ?>
                                        <?php echo $record['type'] == 1 ? "充值" : "提现"; ?>中
                                    <?php } elseif ($record['status'] == 1) { ?>
                                        <?php echo $record['type'] == 1 ? "充值" : "提现"; ?>完成
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="division-line"></div>


            </div>

        </div>
    </div>


    <?php if ($record['status'] == 0) { ?>
        <div style="background: white; padding:20px;">
            <input type="radio" class="confirm_radio" name="confirm_radio" value="0">拒绝
            <input type="radio" class="confirm_radio" name="confirm_radio" value="1" checked>同意
            <textarea class="remark" placeholder="请填写理由…"></textarea>
        </div>
        <button class="confirm_operation" record-id="<?php echo $record["id"]; ?>">确认并执行操作</button>
    <?php } ?>


</div>
<script type="text/javascript" src="../../public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>

<script type="text/javascript">

    function openPage(url) {
        if (isMobile()) {
            zalyjsOpenNewPage(url);
        } else {
            zalyjsOpenPage(url);
        }
    }

    $(".confirm_operation").on("click", function () {
        var selectRadioValue = $(".confirm_radio:checked").val();
        var recordId = $(this).attr("record-id");
        var remark = $(".remark").val();

        var data = {
            "recordId": recordId,
            "agreeStatus": selectRadioValue,
            "feedBack": remark,
        };
        var url = "<?php echo $serverAddress;?>/index.php?action=api.manage.confirm";
        zalyjsCommonAjaxPostJson(url, data, handleConfirmOperationResponse)
    });

    function handleConfirmOperationResponse(url, data, result) {
        if (result) {
            var res = JSON.parse(result);

            if ("success" == res.errCode) {
                alert("执行成功");
            } else {
                alert(res.errInfo);
            }
        } else {
            alert("执行失败");
        }
        window.location.reload();
    }

</script>

</body>
</html>




