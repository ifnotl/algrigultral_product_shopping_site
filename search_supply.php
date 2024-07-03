<?php
// 连接数据库
$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname, (int)$port);

// 检查连接
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 接收搜索参数
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : "";
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : "";
$origin = isset($_GET['origin']) ? $_GET['origin'] : "";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : "";
$format = isset($_GET['format']) ? $_GET['format'] : "html";

// 每页显示的记录数
$records_per_page = 5;
$offset = ($page - 1) * $records_per_page;

// 构建查询
$sql = "SELECT `id`, `name`, `type`, `price`, `num`, `unit`, `release account`, `release time`, `place of origin`
        FROM `supply products`
        WHERE `name` LIKE ?";

// 动态添加条件
$params = ["%$product_name%"];
$types = "s"; // 预处理参数类型

if (!empty($quantity)) {
    $sql .= " AND num >= ?";
    $params[] = $quantity;
    $types .= "i";
}

if (!empty($origin)) {
    $sql .= " AND `place of origin` LIKE ?";
    $params[] = "%$origin%";
    $types .= "s";
}

// 处理排序
switch ($sort_order) {
    case "release_time_asc":
        $sql .= " ORDER BY `release time` ASC";
        break;
    case "release_time_desc":
        $sql .= " ORDER BY `release time` DESC";
        break;
    case "price_asc":
        $sql .= " ORDER BY `price` ASC";
        break;
    case "price_desc":
        $sql .= " ORDER BY `price` DESC";
        break;
    default:
        $sql .= " ORDER BY `release time` DESC"; // 默认排序
        break;
}

// 查询总记录数
$total_sql = "SELECT COUNT(*)
              FROM `supply products`
              WHERE `name` LIKE ?";

$total_params = ["%$product_name%"];
$total_types = "s";

if (!empty($quantity)) {
    $total_sql .= " AND num >= ?";
    $total_params[] = $quantity;
    $total_types .= "i";
}

if (!empty($origin)) {
    $total_sql .= " AND `place of origin` LIKE ?";
    $total_params[] = "%$origin%";
    $total_types .= "s";
}

$total_stmt = $conn->prepare($total_sql);
$total_stmt->bind_param($total_types, ...$total_params);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_row();
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);

// 添加分页限制
$sql .= " LIMIT ?, ?";
$params[] = $offset;
$params[] = $records_per_page;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($format === "json") {
    // JSON 格式输出
    $supplies = [];
    while ($row = $result->fetch_assoc()) {
        $supplies[] = $row;
    }
    echo json_encode($supplies);
} else {
    // HTML 格式输出
    // 显示结果
    if ($result->num_rows > 0) {
        echo '<ul>';
        echo '<li class="header">';
		echo '<div class="name">产品id</div>';
        echo '<div class="name">产品名称</div>';
        echo '<div class="type">种类</div>';
        echo '<div class="price">价格</div>';
        echo '<div class="num">数量</div>';
        echo '<div class="unit">单位</div>';
        echo '<div class="release-time">发布时间</div>';
        echo '<div class="origin">原产地</div>';
        echo '</li>';

        // 表内容
        while ($row = $result->fetch_assoc()) {
            echo '<li class="item">';
            echo '<a href="product_detail.php?id=' . $row['id'] . '">';//这里是超链接！！之后要补充的
			echo '<div class="id">' . $row['id'] . '</div>';
            echo '<div class="name">' . $row['name'] . '</div>';
            echo '<div class="type">' . $row['type'] . '</div>';
            echo '<div class="price">' . $row['price'] . '</div>';
            echo '<div class="num">' . $row['num'] . '</div>';
            echo '<div class="unit">' . $row['unit'] . '</div>';
            echo '<div class="release-time">' . $row['release time'] . '</div>';
            echo '<div class="origin">' . $row['place of origin'] . '</div>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';

        // 分页链接
        if ($total_pages > 1) {
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<strong>$i</strong>";
                } else {
                    echo "<a href='#' data-page='$i'>$i</a>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "暂时没有您搜索的供应产品";
    }
}

$conn->close();
?>
