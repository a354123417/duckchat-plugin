<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/record_detail.css?v=2"/>

</head>

<body>

<div class="wrapper_div">
    <div class="wrapper" id="wrapper">
        <div class="layout-all-row">
            <div class="list-item-center">

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

                            <div class="item-body-tail"><?php echo date("H:i", $record['createTime'] / 1000); ?>
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
                                <?php if ($record['type'] == 1) { ?>
                                    <div class="row-head cell">
                                        充值<?php echo $record['status'] == 1 ? "完成" : "中"; ?></div>
                                <?php } elseif ($record['type'] == 2) { ?>
                                    <div class="row-head cell">
                                        提现<?php echo $record['status'] == 1 ? "完成" : "中"; ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="division-line"></div>


            </div>

        </div>
    </div>


    <?php if ($record['status'] == 0) { ?>
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
        var recordId = $(this).attr("record-id");
        var data = {
            "recordId": recordId
        };
        var url = "<?php echo $serverAddress;?>/index.php?action=api.manage.confirm";
        zalyjsCommonAjaxPostJson(url, data, handleConfirmOperationResponse)
    });

    function handleConfirmOperationResponse(url, data, result) {
        alert(result);
        window.location.reload();
    }

</script>

</body>
</html>




