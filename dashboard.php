<?php
session_start();

// 检查用户是否已登录，没有登陆还是返回登陆页面
if (!isset($_SESSION['account']) || !isset($_SESSION['role'])) {
    header("Location: login_page.php");
    exit();
}

// 获取登录用户的角色和账号
$role = $_SESSION['role'];
$account = $_SESSION['account'];

// 根据角色重定向到相应的页面
switch ($role) {
    case 'supplier':
        header("Location: supplier_account.php?account=$account");
        break;
    case 'purchaser':
        header("Location: purchaser_account.php?account=$account");
        break;
    case 'admin':
        header("Location: admin_account.php?account=$account");
        break;
    default:
        echo "<script>alert('无效的角色'); window.history.back();</script>";
        exit();
}
?>
