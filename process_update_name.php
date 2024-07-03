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

$supplier_account = $_SESSION['account'];
$new_name = $_POST['name'];

if (empty($new_name)) {
    echo json_encode(['status' => 'error', 'message' => '新名称不能为空']);
    exit();
}

$update_sql = "UPDATE `supplier` SET `name` = '$new_name' WHERE `account` = '$supplier_account'";
if (mysqli_query($conn, $update_sql)) {
	$_SESSION['name']=$new_name;
    echo json_encode(['status' => 'success', 'message' => '名称更新成功', 'url' => 'supplier_dashboard.php']);
} else {
    echo json_encode(['status' => 'error', 'message' => '名称更新失败，请重试']);
}

mysqli_close($conn);
?>
