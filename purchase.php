<?php
session_start();

// 假设登录信息保存在 session 中
$user_type = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$user_id = isset($_SESSION['account']) ? $_SESSION['account'] : '';

if ($user_type !== 'purchaser') {
    echo "<script>alert('请登录采购商账号'); history.back();</script>";
    exit();
}



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

// 获取采购商信息
$purchaser_sql = "SELECT `name`, `account`, `address1` FROM `purchaser` WHERE `account` = ?";
$purchaser_stmt = $conn->prepare($purchaser_sql);
$purchaser_stmt->bind_param("i", $user_id);
$purchaser_stmt->execute();
$purchaser_result = $purchaser_stmt->get_result();

if ($purchaser_result->num_rows == 0) {
    die("采购商信息未找到");
}

$purchaser_info = $purchaser_result->fetch_assoc();
$purchaser_name = $purchaser_info['name'];
$purchaser_account = $purchaser_info['account'];
$purchaser_address1 = $purchaser_info['address1'];



// 获取购买信息
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];


// 获取产品信息
$sql = "SELECT `id`, `name`,`price`, `num`, `unit`, `release account`
        FROM `supply products`
        WHERE `id` = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("产品未找到");
}

$product = $result->fetch_assoc();

if ($product['num'] < $quantity) {
    die("库存不足");
}



// 获取供应商信息
$supplier_id=$product['release account'];
$supplier_sql = "SELECT `name`, `address1` FROM `supplier` WHERE `account` = ?";
$supplier_stmt = $conn->prepare($supplier_sql);
$supplier_stmt->bind_param("i", $supplier_id);
$supplier_stmt->execute();
$supplier_result = $supplier_stmt->get_result();
echo $supplier_id;
if ($supplier_result->num_rows == 0) {
    die("供应商信息未找到");
}

$supplier_info = $supplier_result->fetch_assoc();
$supplier_name = $supplier_info['name'];
$supplier_address1 = $supplier_info['address1'];

// 生成订单号和下单日期
$order_id = 'O' . date('YmdHis');
$order_date = date('Y-m-d');

// 记录订单
$sql = "INSERT INTO `purchasing orders` (
    `orderid`, `supplier`, `supplier_name`, `purchaser`,`purchaser_name`,
    `unit price`,`num`, `total price`,`unit`,
    `destination`, `address`,
    `order date`,
    `name`, `productsid`,
    `state`
) VALUES (
    '$order_id', '$supplier_id', '$supplier_name',
    '$purchaser_account', '$purchaser_name',
    {$product['price']}, $quantity, " . ($product['price'] * $quantity) . ", 'kg',
    '$purchaser_address1', '$supplier_address1',
    '$order_date',
    '{$product['name']}', '$product_id',
    '未发货'
)";

if ($conn->query($sql) === TRUE) {
    echo "购买成功";
	$new_quantity = $product['num'] - $quantity;

	$sql = "UPDATE `supply products`
        SET `num` = ?
        WHERE `id` = ?";

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ii", $new_quantity, $product_id);
	$stmt->execute();
	echo "<script>
		alert('购买成功');
		window.location.href = 'index.php';
	  </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
// 减少库存


$conn->close();
?>
