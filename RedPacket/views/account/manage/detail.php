<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="./public/css/record_detail.css"/>

</head>

<body>

<div class="wrapper_div">
    <div class="wrapper" id="wrapper">
        <div class="layout-all-row">
            <div class="list-item-center">
                <div class="item-row" id="mini-program-id">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">
                               时间
                            </div>

                            <div class="item-body-tail">
                                2018-09-12
                            </div>
                        </div>

                    </div>
                </div>
                <div class="division-line"></div>


                <div class="item-row" id="user-manage-id">
                    <div class="item-body">
                        <div class="item-body-display">
                            <div class="item-body-desc">
                                ID
                            </div>

                            <div class="item-body-tail">
                               少爷
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
                                30元
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
                                未提现
                            </div>
                        </div>
                    </div>
                </div>
                <div class="division-line"></div>


            </div>

        </div>
    </div>
    <button class="confirm_operation" record-id="111111">确认并执行操作</button>


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
       //todo post
        var data = {
            recordId:recordId
        };
        var url = "index.php?action=page.redAccount.manage";
        zalyjsCommonAjaxPostJson(url, data, handleConfirmOperationResponse)
    });

    function handleConfirmOperationResponse() {
        window.location.reload();

    }


</script>

</body>
</html>




