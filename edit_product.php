<?php
session_start();

// 检查用户是否登录并且角色为供应商
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    header("Location: login_page.php");
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
    die("连接失败 " . mysqli_connect_error());
}

// 获取商品ID
$product_id = $_GET['id'];

// 查询商品信息
$sql = "SELECT * FROM `supply products` WHERE `id` = '$product_id'";
$result = mysqli_query($conn, $sql);

// 检查是否找到商品
if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    die("未找到商品");
}

// 关闭数据库连接
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>编辑商品</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="editProductForm">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        商品名称: <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        种类: <input type="text" name="type" value="<?php echo htmlspecialchars($product['type']); ?>" required><br>
        单价: <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>
        数量: <input type="text" name="num" value="<?php echo htmlspecialchars($product['num']); ?>" required><br>
        单位: <input type="text" name="unit" value="<?php echo htmlspecialchars($product['unit']); ?>" required><br>
        原产地: <input type="text" name="place_of_origin" value="<?php echo htmlspecialchars($product['place of origin']); ?>" required><br>
        <button type="submit">提交</button>
    </form>

    <script>
        $(document).ready(function() {
            $("#editProductForm").submit(function(event) {
                event.preventDefault(); // 防止表单默认提交

                $.ajax({
                    url: 'process_update_product.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            window.location.href = "supplier_dashboard.php";//这里定义了process弹出窗口后返回的页面，而不是process_update_product  echo那里定义
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("提交失败: " + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
