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
                                <div class="row-head cell"><?php echo date("H:i", $record['createTime'] / 1000); ?></div>
                                <div class="row-head cell"><?php echo $record['amount'] ?></div>


                                <?php if ($record['type'] == 1) { ?>
                                    <div class="row-head cell">
                                        充值<?php echo $record['status'] == 1 ? "完成" : "中"; ?></div>
                                <?php } elseif ($record['type'] == 2) { ?>
                                    <div class="row-head cell">
                                        提现<?php echo $record['status'] == 1 ? "完成" : "中"; ?></div>
                                <?php } ?>

                                <div class="data cell operation"
                                     record-id="<?php echo $record['id'] ?>"><?php echo $record['reply'] ?>详情
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
        <div class="row-head cell">{{loginName}}</div>
        <div class="row-head cell">{{money}}</div>
        <div class="row-head cell">{{status}}</div>
        <div class="data cell operation" record-id="{{id}}">{{reply}}</div>
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

    $(".operation").on("click", function () {
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
            var res = JSON.parse(result);

            var datas = res['datas'];

            if (datas && datas.length > 0) {
                $.each(datas, function (index, record) {
                    var html = template("tpl-record", {
                        id: record.id,
                    });
                    $(".table").append(html);
                });
                loading = true;
                return;
            }
        }
        loading = false;
    }
</script>
</body>
</html>

