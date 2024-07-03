<?php
session_start();

if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    echo json_encode(['status' => 'redirect', 'url' => 'login_page.php']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

$conn = mysqli_connect($servername, $username, $password, $dbname, (int)$port);

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => '连接失败: ' . mysqli_connect_error()]);
    exit();
}

//赋值使用的变量
$supplier_account = $_SESSION['account'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if (empty($current_password) or empty($new_password) or empty($confirm_password)) {
    echo json_encode(['status' => 'error', 'message' => '请填写完整']);
    exit();
}

// 获取数据库中的当前密码
$sql = "SELECT password FROM `supplier` WHERE `account` = '$supplier_account'";
$result = mysqli_query($conn, $sql);
$supplier = mysqli_fetch_assoc($result);

if (!$supplier) {
    echo json_encode(['status' => 'error', 'message' => '未找到相关供应商信息']);
    exit();
}

// 检查当前密码是否正确
if ($supplier['password'] !== $current_password) {
    echo json_encode(['status' => 'error', 'message' => '密码错误']);
    exit();
}

// 检查新密码和确认新密码是否一致
if ($new_password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => '请确认新密码和确认新密码一致']);
    exit();
}

// 更新密码
$update_sql = "UPDATE `supplier` SET `password` = '$new_password' WHERE `account` = '$supplier_account'";
if (mysqli_query($conn, $update_sql)) {
    echo json_encode(['status' => 'success', 'message' => '密码更新成功', 'url' => 'supplier_dashboard.php']);
} else {
    echo json_encode(['status' => 'error', 'message' => '密码更新失败，请重试']);
}

mysqli_close($conn);
?>
