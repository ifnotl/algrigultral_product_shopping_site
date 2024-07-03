<?php
session_start();
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier' && $_SESSION['role'] !== 'purchaser') {
    header("Location: login_page.php");
    exit();
}
// var_dump($_GET['id']);
$account = $_SESSION['account'];
$user_name = $_SESSION['name'];
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

// 获取产品ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 获取商品详情
$sql = "SELECT *
        FROM `supply products`
        WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
	
    $product = $result->fetch_assoc();
} else {
    die("产品未找到");
}

$conn->close();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>商品详情页</title>
	    <style>
        /* 样式重置，确保所有元素的默认样式一致 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* 整体导航栏样式 */
        .navbar {
    background-color: #70DC88; /* 绿色背景 */
    overflow: hidden;/* 容器溢出时*/
    display: flex;
    align-items: center;
    justify-content: space-between; /* 均匀分布链接 */
    padding: 0 10px; /* 为导航栏添加一些内边距 */
	transition: background-color 0.3s ease; 
        }

        /* 导航栏链接样式 */
        .navbar a {
    display: inherit;
    color: white;
    text-align: center;
    padding: 14px 20px;
    text-decoration: none;
    flex-grow: 1; /* 链接均匀分布 */
        }

        /* Logo 样式 */
        .navbar .logo img {
            width: 130px; /* 调整logo大小 */
            height: auto;
			
        }

        /* 登录链接样式 */
        .navbar a.login {
            font-size: 14px; /* 字体较小 */
            color: rgba(255, 255, 255, 0.7); /* 更淡的字体颜色 */
            flex-grow: 0; /* 不占据太多空间 */
            padding: 10px 15px; /* 较小的内边距 */
        }

        /* 鼠标悬停效果 这里有问题，只是字体周围一小片区域变色，而不是整个块*/
		/* ：hoover定义了.navbar类下所有的超链接a被鼠标悬停时的格式*/
        .navbar a:hover {
    background-color: #5CD778; /* 鼠标悬停时变色 */
			color: black;
        }


/*底部的友情链接*/
        .footer {
            background-color: #70DC88;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .footer ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer ul li {
            margin: 10px;
        }

        .footer ul li a {
            text-decoration: none;
            padding: 10px;
        }
		.footer ul li a:hover {
    		background-color: #5CD778; /* 鼠标悬停时变色 */
			color: black;
        }


		/* 下面供应信息和采购信息表格格式*/
		 ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        li.header, li.item {
            display: flex;
            padding: 8px 0;
        }
        li.header {
            font-weight: bold;
            border-bottom: 2px solid #000;
        }
        li.item {
            border-bottom: 1px solid #ddd;
        }
        li div {
            flex: 1;
            padding: 8px;
        }
        li div.name {
            flex: 1.5;
        }
		li div.unit {
            flex: 0.5;
        }
		li div.release-time {
            flex: 2.5;
        }
		li div.origin {
            flex: 2.5;
        }
        a {
            text-decoration: none;
            color: inherit;
            display: flex;
            width: 100%;
        }
        a:hover {
            background-color: #f0f0f0; /* Adjust color as needed */
        }
	/*除固定模板外的格式*/
		.container { display: flex; }
        .left { width: 50%; padding: 20px; }
        .right { width: 50%; padding: 20px; }
        .images { overflow-y: scroll; height: 400px; }
        .images img { width: 100%; margin-bottom: 10px; }
        .details { margin-bottom: 20px; }
        .details div { margin-bottom: 10px; }
    </style>
</head>

<body>
   <div class="navbar">
        <a href="index.php" class="logo"><img src="pictures\logo.png" alt="Logo"></a><!--这里一定要使用相对路径，不然无法显示!-->
        <a href="supply_page.php" target="_blank">供应大厅</a>  
        <a href="purchase_page.php" target="_blank">采购大厅</a>
        <a href="#" target="_blank">行情大厅</a>
		<?php if(isset($_SESSION['name'])): ?>
            <?php if($_SESSION['role'] == 'supplier'): ?>
                <a href="supplier_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php elseif($_SESSION['role'] == 'purchaser'): ?>
                <a href="purchaser_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php elseif($_SESSION['role'] == 'admin'): ?>
                <a href="admin_dashboard.php" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login_page.php" target="_blank" class="login">登录</a>
        <?php endif; ?>
    </div> 
	
    <main>
        <div class="container">
            <div class="left images">
                <?php
                // 显示对应商品ID的图片
                $image_path = "pictures/{$product['id']}.jpg";
                echo "<img src='$image_path' alt='产品图片'>";
                ?>
            </div>
            <div class="right">
                <div class="details">
                    <div>产品名称: <?php echo $product['name']; ?></div>
                    <div>种类: <?php echo $product['type']; ?></div>
                    <div>价格: <?php echo $product['price']; ?></div>
                    <div>数量: <?php echo $product['num']; ?> <?php echo $product['unit']; ?></div>
                    <div>发布时间: <?php echo $product['release time']; ?></div>
                    <div>原产地: <?php echo $product['place of origin']; ?></div>
                </div>
                <form action="purchase.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" min="1" max="<?php echo $product['num']; ?>" required>
                    <button type="submit">购买</button>
                </form>
            </div>
        </div>
    </main>
	
	<div class="footer">
        <p>友情链接</p>
		<ul>
		<li>
        <a href="https://gaoyou.yangzhou.gov.cn/gaoyou/index.shtml">高邮市人民政府</a> <a href="http://dag.gaoyou.gov.cn/">高邮市档案史志馆</a>
		</li>
		<li>
        <a href="https://yzgy.jszwfw.gov.cn/">高邮市政务服务网</a>
        <a href="https://www.cnhnb.com/">农产品购物：惠农网</a>
		</li>
		</ul>
    </div>
</body>
</html>