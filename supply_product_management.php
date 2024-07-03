<?php
session_start();
//这段代码在html之前，确保读取数据库，还有顶端的显示！！！

// 检查用户是否登录并且角色为供应商
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    header("Location: login_page.php");
    exit(); // 确保在重定向之后立即停止脚本执行
}

// 连接数据库
$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname, (int)$port);

// 检查连接是否成功
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}

// 获取当前供应商的账号信息
$supplier_account = $_SESSION['account'];
$sql = "SELECT name FROM `supplier` WHERE `account` = '$supplier_account'";
$result = mysqli_query($conn, $sql);
$supplier = mysqli_fetch_assoc($result);

// 检查是否成功获取供应商信息
if (!$supplier) {
    die("未找到相关供应商信息");
}

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>账号管理</title>
    <style>
        /* 调整主内容容器的位置 */
        .main-content {
            margin-top: -280px; /* 根据需要调整 */
            padding: 20px;
        }

        /* 调整表单样式 */
        .form-container {
            margin-bottom: 20px; /* 下方外边距20px */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        /* 使输入框和标签垂直排列 */
        .form-container label,
        .form-container input {
            display: block;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="main-content">
        <!-- 查询商品 -->
        <form id="searchForm" class="form-container" method="post" action="process_supplyproduct.php">
            <label for="name">商品名称:</label>
            <input type="text" name="name" id="name" value="" required>
			<label for="productid">商品id:</label>
            <input type="text" name="productid" id="productid" value="" >
			<input type="date" name="start_date" autocomplete="on">
            <input type="date" name="end_date" autocomplete="on">
            <button type="submit">搜索</button>
        </form>
		
		<div id="searchResult">
            <!-- 查询结果将在这里显示 -->
        </div>
		
    </div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("JavaScript loaded successfully.");

            // 使用 AJAX 提交表单
            $('#searchForm').on('submit', function(event) {
                event.preventDefault(); // 阻止表单的默认提交行为

                var formData = $(this).serialize(); // 序列化表单数据
				var actionUrl = $(this).attr('action');

                $.ajax({
                    type: "GET", // 使用 GET 方法
                    url: actionUrl, // 确保这是正确的文件路径
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

</body>
</html>