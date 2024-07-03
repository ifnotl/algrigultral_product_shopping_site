<?php
session_start();

// 连接数据库
$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

$conn = new mysqli($servername, $username, $password, $dbname, (int)$port);

if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 获取表单数据
$role = $_POST['role'];
$account = $_POST['account'];
$password = $_POST['password'];
$captcha = $_POST['captcha'];

// 验证验证码
if ($captcha !== $_SESSION['captcha']) {
    echo "<script>alert('验证码错误'); window.history.back();</script>";
    exit();
}

$table = '';
switch ($role) {
    case 'supplier':
        $table = 'supplier';
        break;
    case 'purchaser':
        $table = 'purchaser';
        break;
    case 'admin':
        $table = 'admin';
        break;
    default:
        echo "<script>alert('无效的角色'); window.history.back();</script>";
        exit();
}

// 查询用户
$sql = "SELECT `account`, `name`, `password` FROM `$table` WHERE `account` = ? AND `password` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $account, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 将查询结果存储到$row
    $row = $result->fetch_assoc();
    
    // 将用户信息存储到会话中
    $_SESSION['role'] = $role;
    $_SESSION['account'] = $account;
    $_SESSION['name'] = $row['name'];
    
    // 重定向到对应的用户界面
    switch ($role) {
        case 'supplier':
            echo "<script>alert('登录成功'); window.location.href='supplier_dashboard.php';</script>";
            break;
        case 'purchaser':
            echo "<script>alert('登录成功'); window.location.href='purchaser_dashboard.php';</script>";
            break;
        case 'admin':
            echo "<script>alert('登录成功'); window.location.href='admin_dashboard.php';</script>";
            break;
        default:
            echo "<script>alert('无效的角色'); window.history.back();</script>";
            break;
    }
} else {
    echo "<script>alert('账号或密码错误'); window.history.back();</script>";
}
$stmt->close();
$conn->close();
?>
