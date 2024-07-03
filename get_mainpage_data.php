<?php
function get_latest_purchase_data($page = 1, $limit = 5) {
    // 数据库连接配置
    $servername = "localhost";
    $username = "root";
    $password = "";
    $port = 3316;
    $dbname = "agricutural shopping";

    // 创建连接
    $conn = mysqli_connect($servername, $username, $password, $dbname, (int)$port); // 将端口号转换为整数类

    // 计算偏移量
    $offset = ($page - 1) * $limit;

    // 查询最新的记录
    $sql = "SELECT `id`, `name`, `num`, `unit`, `release account`, `release time`, `place of origin`, `destination` FROM `purchase products` ORDER BY `release time` DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    // 检查查询结果
    if ($result->num_rows > 0) {
        // 表头
        echo '<ul>';
        echo '<li class="header">';
        echo '<div class="name">产品名称</div>';
        echo '<div class="num">数量</div>';
        echo '<div class="unit">单位</div>';
        echo '<div class="release-time">发布时间</div>';
        echo '<div class="origin">期望原产地</div>';
        echo '<div class="destination">目的地</div>';
        echo '</li>';

        // 表内容
        while($row = $result->fetch_assoc()) {
            echo '<li class="item">';
            echo '<a href="purchase_product_details.php?id='. $row['id'] . '">';
            echo '<div class="name">' . $row['name'] . '</div>';
            echo '<div class="num">' . $row['num'] . '</div>';
            echo '<div class="unit">' . $row['unit'] . '</div>';
            echo '<div class="release-time">' . $row['release time'] . '</div>';
            echo '<div class="origin">' . $row['place of origin'] . '</div>';
            echo '<div class="destination">' . $row['destination'] . '</div>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "查询有误";
    }
    // 关闭连接
    $conn->close();
}

function get_latest_supply_data($page = 1, $limit = 5) {
    // 数据库连接配置
    $servername = "localhost";
    $username = "root";
    $password = "";
    $port = 3316;
    $dbname = "agricutural shopping";

    // 创建连接
    $conn = mysqli_connect($servername, $username, $password, $dbname, (int)$port); // 将端口号转换为整数类

    // 计算偏移量
    $offset = ($page - 1) * $limit;

    // 查询最新的记录
    $sql = "SELECT `id`,`name`,'price', `num`, `unit`, `release account`, `release time`, `place of origin` FROM `supply products` ORDER BY `release time` DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    // 检查查询结果
    if ($result->num_rows > 0) {
        // 表头
        echo '<ul>';
        echo '<li class="header">';
        echo '<div class="name">产品名称</div>';
		echo '<div class="price">价格</div>';
        echo '<div class="num">数量</div>';
        echo '<div class="unit">单位</div>';
        echo '<div class="release-time">发布时间</div>';
        echo '<div class="origin">原产地</div>';
        echo '</li>';

        // 表内容
        while($row = $result->fetch_assoc()) {
            echo '<li class="item">';
			echo '<a href="product_detail.php?id='. $row['id'] . '">';
            echo '<div class="name">' . $row['name'] . '</div>';
			echo '<div class="price">' . $row['price'] . '</div>';
            echo '<div class="num">' . $row['num'] . '</div>';
            echo '<div class="unit">' . $row['unit'] . '</div>';
            echo '<div class="release-time">' . $row['release time'] . '</div>';
            echo '<div class="origin">' . $row['place of origin'] . '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "查询失败";
    }
    // 关闭连接
    $conn->close();
}
$table = $_GET['table'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;
//调用上面的函数
if ($table == "purchase") {
	get_latest_purchase_data($page, $limit);
} elseif ($table == "supply") {
	get_latest_supply_data($page, $limit);
} else {
	echo "无效的表名";
}
?>
