<?php
session_start();

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

// 获取当前供应商的账号
$supplier_account = $_SESSION['account'];

// 准备查询语句，只查询purchasing orders表格，并且状态不是“已完成”
$sql = "SELECT * FROM `purchasing orders` WHERE `supplier` = '$supplier_account' AND `state` != '已完成'";

// 构建查询条件
if (isset($_GET['search'])){
	$conditions = array();

	// 订单号
	if (!empty($_GET['orderid'])) {
		$conditions[] = "orderid = '" . $_GET['orderid'] . "'";
	}

	// 商品名称
	if (!empty($_GET['name'])) {
		$conditions[] = "name LIKE '%" . $_GET['name'] . "%'";
	}

	// 收货人姓名
	if (!empty($_GET['purchaser_name'])) {
		$conditions[] = "purchaser_name LIKE '%" . $_GET['purchaser_name'] . "%'";
    }

	// 状态
	if (!empty($_GET['choose'])) {
		$conditions[] = "`state` LIKE '%" . $_GET['choose'] . "%'";
	}

	// 下单时间范围
	if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
		$conditions[] = "`order date` BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['end_date'] . "'";
	}

	// 构建条件字符串
	if (!empty($conditions)) {
		$sql .= " AND " . implode(" AND ", $conditions);
	}
}
// 排序
if (isset($_GET['sort'])) {
    $sort_by = $_GET['sort_by']; // 获取排序方式，升序还是降序
    $sql .= " ORDER BY `order date` $sort_by";
}


// 执行查询
$result = mysqli_query($conn, $sql);

// 关闭数据库连接
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>供应商管理页面</title>
    <style>
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li.header, li.item {
            display: flex;
            padding: 8px 0;
        }

        li.header {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }

        li.item {
            border-bottom: 1px solid #ddd;
        }

        li div {
            flex: 1;
            padding: 8px;
        }

        li div.name {
            flex: 1.5;
        }

        li div.unit {
            flex: 0.5;
        }

        li div.release-time {
            flex: 2.5;
        }

        li div.origin {
            flex: 2.5;
        }

        a {
            text-decoration: none;
            color: inherit;
            display: flex;
            width: 100%;
        }

        a:hover {
            background-color: #f0f0f0; /* 调整颜色 */
        }
    </style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <ul>
        <li class="header">
            <div>订单号</div>
            <div>商品名称</div>
            <div>收货人姓名</div>
            <div>单价</div>
            <div>数量</div>
            <div>单位</div>
            <div>总金额</div>
			<div>发货地址</div>
            <div>状态</div>
            <div>下单时间</div>
        </li>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li class='item'>";
                echo "<div>" . htmlspecialchars($row['orderid']) . "</div>";
                echo "<div>" . htmlspecialchars($row['name']) . "</div>";
                echo "<div>" . htmlspecialchars($row['purchaser_name']) . "</div>";
                echo "<div>" . htmlspecialchars($row['unit price']) . "</div>";
                echo "<div>" . htmlspecialchars($row['num']) . "</div>";
                echo "<div>" . htmlspecialchars($row['unit']) . "</div>";
                echo "<div>" . htmlspecialchars($row['total price']) . "</div>";
				echo "<div>" . htmlspecialchars($row['address']) . "</div>";
                echo "<div>" . htmlspecialchars($row['state']) . "</div>";
                echo "<div>" . htmlspecialchars($row['order date']) . "</div>";
                echo "</li>";
                echo "</li>";
            }
        } else {
            echo '<li>未查询到相关数据</li>';
        }
        ?>
    </ul>
</body>
</html>
