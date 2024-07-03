<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>未完成订单管理</title>
    <style>
        /* 调整搜索表单的位置 */
        #searchForm {
            margin-top: -260px; /* 根据需要调整 */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        /* 使搜索结果占满剩余空白区域 */
        #searchResult {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .main-content {
            padding: 0px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <!-- 搜索表单 -->
        <form id="searchForm" method="get" action="">
            <input type="text" name="orderid" placeholder="订单号" autocomplete="on">
            <input type="text" name="name" placeholder="商品名称" autocomplete="on">
            <input type="text" name="purchaser_name" placeholder="收货人姓名" autocomplete="on">
			<select name="choose">
				<option value="">无</option>
                <option value="onroad">在途</option>
                <option value="notdeliver">未发货</option>
				<option value="notpaid">未付款</option>
            <input type="date" name="start_date" autocomplete="on">
            <input type="date" name="end_date" autocomplete="on">
            <select name="sort_by">
                <option value="ASC">升序</option>
                <option value="DESC">降序</option>
            </select>
            <button type="submit" id="submitButton">搜索</button>
        </form>

        <!-- 显示查询结果 -->
        <div id="searchResult">
            <!-- 查询结果将在这里显示 -->
        </div>
    </div>

    <!-- 将 jQuery 和自定义脚本放在页面底部 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("JavaScript loaded successfully.");

            // 使用 AJAX 提交表单
            $('#searchForm').on('submit', function(event) {
                event.preventDefault(); // 阻止表单的默认提交行为
                console.log("Form submitted.");

                var formData = $(this).serialize(); // 序列化表单数据
                console.log("Form data: " + formData); // 打印表单数据

                $.ajax({
                    type: "GET", // 使用 GET 方法
                    url: "process_supply_ongoingorders.php", // 确保这是正确的文件路径
                    data: formData, // 表单数据
                    success: function(response) {
                        console.log("AJAX request succeeded."); // 添加调试日志
                        $("#searchResult").html(response); // 将结果显示在页面上
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed:", error); // 处理错误
                        console.error("Status: " + status);
                        console.error("Error: " + error);
                        console.log("Response Text: " + xhr.responseText); // 输出响应文本
                    }
                });
            });
        });
    </script>
</body>
</html>
