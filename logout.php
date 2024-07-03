<?php
session_start();

// 清空所有会话变量
$_SESSION = array();

// 如果存在会话 cookie，则将其删除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 销毁会话
session_destroy();

// 重定向到登录页面
header("Location: login_page.php");
exit();
?>