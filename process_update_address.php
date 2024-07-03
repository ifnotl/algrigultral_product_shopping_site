<?php
session_start();

// 检查用户是否登录并且角色为供应商
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    echo json_encode(['status' => 'redirect', 'url' => 'login_page.php']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 检查连接是否成功
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => '连接失败: ' . $conn->connect_error]);
    exit();
}

// 获取当前供应商的账号信息
$supplier_account = $_SESSION['account'];

// 获取表单提交的数据
$province_id = $_POST['province_id'];
$city_id = $_POST['city_id'];
$county_id = $_POST['county_id'];


// 获取表单ID来区分是哪个地址表单
$form_id = $_POST['form_id'];
$address_field = '';

switch ($form_id) {
    case 'addressForm1':
        $address_field = 'address1';
        break;
    case 'addressForm2':
        $address_field = 'address2';
        break;
    case 'addressForm3':
        $address_field = 'address3';
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => '无效的表单ID']);
        exit();
}

// 准备更新语句
$update_sql = "UPDATE `supplier` SET `$address_field` = CONCAT_WS(', ', ?, ?, ?) WHERE `account` = ?";

// 使用准备好的语句执行更新操作
$stmt = $conn->prepare($update_sql);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'SQL准备失败: ' . $conn->error]);
    exit();
}
$stmt->bind_param("ssss", $province_id, $city_id, $county_id, $supplier_account);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => '地址更新成功', 'url' => 'supplier_dashboard.php']);
} else {
    echo json_encode(['status' => 'error', 'message' => '地址更新失败，请重试']);
}

$stmt->close();
$conn->close();
?>
