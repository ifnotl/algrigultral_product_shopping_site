<?php
session_start();
// 检查用户是否登录并且角色为供应商
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    echo json_encode(["status" => "error", "message" => "用户未登录或不是供应商"]);
    exit();
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
    echo json_encode(["status" => "error", "message" => "连接失败: " . mysqli_connect_error()]);
    exit();
}

// 获取表单数据
$id = $_POST['id'];
$name = $_POST['name'];
$type = $_POST['type'];
$price = $_POST['price'];
$num = $_POST['num'];
$unit = $_POST['unit'];
$place_of_origin = $_POST['place_of_origin'];

// 准备更新语句
$sql = "UPDATE `supply products` SET 
    `name` = '$name', 
    `type` = '$type', 
    `price` = '$price', 
    `num` = '$num', 
    `unit` = '$unit', 
    `place of origin` = '$place_of_origin' 
    WHERE `id` = '$id'";

// 执行更新
if (mysqli_query($conn, $sql)) {
    echo json_encode(["status" => "success", "message" => "更新成功"]);
} else {
    echo json_encode(["status" => "error", "message" => "更新失败: " . mysqli_error($conn)]);
}

// 关闭数据库连接
mysqli_close($conn);
?>
