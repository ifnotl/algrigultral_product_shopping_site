<?php
// 连接数据库
$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取表单提交的数据
$orderid = $_POST['orderid'];
$name = $_POST['name'];
$purchaser_name = $_POST['purchaser_name'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$status = $_POST['status'];
$sort_by = $_POST['sort_by'];

// 插入新订单到数据库
$insert_sql = "INSERT INTO orders (orderid, name, purchaser_name, start_date, end_date, status)
               VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);
if ($stmt === false) {
    die("SQL 准备失败: " . $conn->error);
}
$stmt->bind_param("ssssss", $orderid, $name, $purchaser_name, $start_date, $end_date, $status);
if ($stmt->execute()) {
    echo "订单上传成功";
} else {
    echo "订单上传失败，请重试";
}
$stmt->close();

// 查询订单信息并排序
$query_sql = "SELECT * FROM orders WHERE orderid = ? OR name LIKE ? OR purchaser_name LIKE ? 
              OR (start_date >= ? AND end_date <= ?) ORDER BY start_date $sort_by";
$stmt = $conn->prepare($query_sql);
if ($stmt === false) {
    die("SQL 准备失败: " . $conn->error);
}
$name = "%$name%";
$purchaser_name = "%$purchaser_name%";
$stmt->bind_param("sssss", $orderid, $name, $purchaser_name, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

// 显示查询结果
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div><a href='order_details.php?orderid=" . $row['orderid'] . "'>订单号: " . $row['orderid'] . " - 商品名称: " . $row['name'] . " - 收货人姓名: " . $row['purchaser_name'] . " - 状态: " . $row['status'] . " - 开始日期: " . $row['start_date'] . " - 结束日期: " . $row['end_date'] . "</a></div>";
    }
} else {
    echo "没有找到订单";
}

$stmt->close();
$conn->close();
?>
