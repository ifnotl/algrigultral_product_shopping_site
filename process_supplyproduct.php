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
    die("连接失败 " . mysqli_connect_error());
}

// 获取当前供应商的账号
$supplier_account = $_SESSION['account'];

// 准备查询语句
$sql = "SELECT * FROM `supply products` WHERE `release account` = '$supplier_account' ";

if (isset($_GET['search'])) {
    $conditions = array();

    // 商品号
    if (!empty($_GET['productid'])) {
        $conditions[] = "id = '" . $_GET['productid'] . "'";
    }

    // 商品名称
    if (!empty($_GET['name'])) {
        $conditions[] = "name LIKE '%" . $_GET['name'] . "%'";
    }


    // 下单时间范围
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $conditions[] = "`release date` BETWEEN '" . $_GET['start_date'] . "' AND '" . $_GET['end_date'] . "'";
    }

    // 构建条件字符串
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }
}

// 排序
if (isset($_GET['sort'])) {
    $sort_by = $_GET['sort_by']; // 获取排序方式，升序还是降序
    $sql .= " ORDER BY `release date` $sort_by";
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
</head>
<body>
    <ul>
        <li class="header">
            <div>商品编号</div>
            <div>商品名称</div>
			<div>种类</div>
			<div>单价</div>
			<div>数量</div>
			<div>单位</div>
			<div>原产地</div>
			<div>发布时间</div>

        </li>
		 <?php
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<li class='item'>";
					echo "<a href='edit_product.php?id=" . htmlspecialchars($row['id']) . "' target='_blank'>";
					echo "<div>" . htmlspecialchars($row['id']) . "</div>";
					echo "<div>" . htmlspecialchars($row['name']) . "</div>";
					echo "<div>" . htmlspecialchars($row['type']) . "</div>";
					echo "<div>" . htmlspecialchars($row['price']) . "</div>";
					echo "<div>" . htmlspecialchars($row['num']) . "</div>";
					echo "<div>" . htmlspecialchars($row['unit']) . "</div>";
					echo "<div>" . htmlspecialchars($row['place of origin']) . "</div>";
					echo "<div>" . htmlspecialchars($row['release time']) . "</div>";
					echo "</a>";
					echo "</li>";
				}
			} else {
				echo '<li>未查询到相关数据</li>';
			}
			?>
    </ul>
</body>
</html>

