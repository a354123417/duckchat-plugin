<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>积分管理后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/account.css"/>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="account_bill">
            <div class="bill-log-div" style="margin-top:10px;">
                <div class="table">
                    <div class="row" style="border-top: 1px solid #999999;">
                        <div class="row-head cell">ID</div>
                        <div class="row-head cell">时间</div>
                        <div class="row-head cell">金额</div>
                        <div class="row-head cell">状态</div>
                        <div class="data cell">操作</div>
                    </div>

                    <?php if (count($records)): ?>
                        <?php foreach ($records as $record) : ?>
                            <div class="row record_id_<?php echo $record['id'] ?>"
                                 style="border-top: 1px solid #999999;">
                                <div class="row-head cell"><?php echo $record['id'] ?></div>
                                <div class="row-head cell"><?php $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
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

                                <div class="data cell operation" record-id="<?php echo $record['id'] ?>">
                                    详情
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./public/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="./public/sdk/zalyjsNative.js"></script>
<script type="text/javascript" src="../../public/manage/native.js"></script>
<script type="text/javascript" src="./public/manage/template-web.js"></script>


<script id="tpl-record" type="text/html">
    <div class="row record_id_{{id}}" style="border-top: 1px solid #999999;">
        <div class="row-head cell">{{id}}</div>
        <div class="row-head cell">{{createTime}}</div>
        <div class="row-head cell">{{amount}}</div>
        <div class="row-head cell">{{status}}</div>
        <div class="data cell operation" record-id="{{id}}">详情</div>
    </div>
</script>


<script type="text/javascript">

    function openPage(url) {
        if (isMobile()) {
            zalyjsOpenNewPage(url);
        } else {
            zalyjsOpenPage(url);
        }
    }

    $("body").on("click", ".operation", function () {
        var recordId = $(this).attr("record-id");
        var url = "<?php echo $serverAddress; ?>/index.php?action=page.manage&page=detail&recordId=" + recordId;
        openPage(url);
    });

    var currentPageNum = 1;
    var loading = true;
    var wrapperDivHeight = $(".wrapper")[0].clientHeight;
    $(".bill-log-div")[0].style.height = Number(wrapperDivHeight - 20) + "px";


    $(".bill-log-div").scroll(function () {
        //判断是否滑动到页面底部
        var errorLogDiv = $(".bill-log-div")[0];
        var sh = errorLogDiv.scrollHeight;
        var ch = errorLogDiv.clientHeight;
        var st = $('.bill-log-div').scrollTop();

        if ((sh - ch - st) <= 1) {
            if (!loading) {
                return;
            }
            loadMoreRecords();
        }
    });

    function loadMoreRecords() {
        var data = {
            'pageNum': ++currentPageNum,
        };

        var url = "<?php echo $serverAddress; ?>/index.php?action=page.manage";
        zalyjsCommonAjaxPostJson(url, data, handleLoadMoreRecordsResponse)
    }

    function handleLoadMoreRecordsResponse(url, data, result) {
        if (result) {
            var datas = JSON.parse(result);

            if (datas && datas.length > 0) {
                $.each(datas, function (index, record) {

                    var statusText = "";

                    if (record.status == -1) {

                        if (record.type == 1) {
                            statusText = "[充值]已拒绝";
                        } else if (record.type == 2) {
                            statusText = "[提现]已拒绝";
                        }

                    } else if (record.status == 0) {
                        if (record.type == 1) {
                            statusText = "[充值]中";
                        } else if (record.type == 2) {
                            statusText = "[提现]中";
                        }
                    } else if (record.status == 1) {
                        if (record.type == 1) {
                            statusText = "[充值]完成";
                        } else if (record.type == 2) {
                            statusText = "[提现]完成";
                        }
                    }

                    var html = template("tpl-record", {
                        id: record.id,
                        createTime: timeMillisToDate(record.createTime),
                        amount: record.amount,
                        status: statusText,
                    });
                    $(".table").append(html);
                });
                loading = true;
                return;
            }
        }
        loading = false;
    }


    function timeMillisToDate(timeStampMillis) {
        timeStampMillis = timeStampMillis / 1000 * 1000;
        var time = new Date(timeStampMillis);
        var dataTime = "";
        dataTime += time.getUTCFullYear() + "-";
        dataTime += (time.getUTCMonth() + 1) + "-";
        dataTime += time.getUTCDate();
        dataTime += " " + time.getUTCHours() + ":";
        dataTime += time.getUTCMinutes() + ":";
        return dataTime;
    }

</script>
</body>
</html>

