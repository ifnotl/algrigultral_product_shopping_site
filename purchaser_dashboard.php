<?php
session_start();
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'purchaser') {
    header("Location: login_page.php");
    exit();
}

$account = $_SESSION['account'];
$user_name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>采购商管理页面</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar {
            background-color: #70DC88;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
            transition: background-color 0.3s ease;
        }
        .navbar a {
            display: inherit;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            flex-grow: 1;
        }
        .navbar .logo img {
            width: 130px;
            height: auto;
        }
        .navbar a.login {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            flex-grow: 0;
            padding: 10px 15px;
        }
        .navbar a:hover {
            background-color: #5CD778;
            color: black;
        }
        .sidebar {
            width: 200px;
            background-color: #f1f1f1;
            padding: 40px;
        }
        .content {
            margin-left: 240px;
            padding: 40px;
        }
        .sidebar a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
        }
        .sidebar a:hover {
            background-color: #ddd;
        }
        .footer {
            background-color: #70DC88;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .footer ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .footer ul li {
            margin: 10px;
        }
        .footer ul li a {
            text-decoration: none;
            padding: 10px;
        }
        .footer ul li a:hover {
            background-color: #5CD778;
            color: black;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="navbar">
        <a href="index.php" class="logo"><img src="pictures/logo.png" alt="Logo"></a>
        <a href="supply_page.php" target="_blank">供应大厅</a>  
        <a href="purchase_page.php" target="_blank">采购大厅</a>
        <a href="#" target="_blank">行情大厅</a>
		<?php if(isset($_SESSION['name'])): ?>
            <?php if($_SESSION['role'] == 'supplier'): ?>
                <a href="supplier_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php elseif($_SESSION['role'] == 'purchaser'): ?>
                <a href="purchaser_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php elseif($_SESSION['role'] == 'admin'): ?>
                <a href="admin_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login_page.php" target="_blank" class="login">登录</a>
        <?php endif; ?>
    </div> 
    
    <div class="sidebar">
        <a href="#" onclick="loadContent('purchase_history_orders.php')">历史订单管理</a>
        <a href="#" onclick="loadContent('purchase_ongoing_orders.php')">未完成订单管理</a>
        <a href="#" onclick="loadContent('purchase_product_management.php')">商品管理</a>
        <a href="#" onclick="loadContent('purchase_account_management.php')">账号管理</a>
        <a href="logout.php">注销</a>
    </div>
    <div id="mainContent" class="content">
        <!-- 这里将显示加载的内容 -->
    </div>
    <div class="footer">
        <p>友情链接</p>
        <ul>
            <li>
                <a href="https://gaoyou.yangzhou.gov.cn/gaoyou/index.shtml">高邮市人民政府</a> 
                <a href="http://dag.gaoyou.gov.cn/">高邮市档案史志馆</a>
            </li>
            <li>
                <a href="https://yzgy.jszwfw.gov.cn/">高邮市政务服务网</a>
                <a href="https://www.cnhnb.com/">农产品购物：惠农网</a>
            </li>
        </ul>
    </div>
    <script>
        function loadContent(page) {
            $.ajax({
                type: "POST",
                url: page,
                success: function(response) {
                    $("#mainContent").html(response);

                },
                error: function(xhr, status, error) {
                    console.error("Failed to load content:", error);
                }
            });
        }



$(document).ready(function() {
    
});

    </script>
</body>
</html>
