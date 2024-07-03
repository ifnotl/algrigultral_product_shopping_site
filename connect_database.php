<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
	<?php
$servername = "localhost";
$username = "root";
$password = "";
$port = 3316;
$dbname = "agricutural shopping";

// 创建连接
$conn =mysqli_connect($servername, $username, $password, $dbname, (int)$port); // 将端口号转换为整数类

// 检查连接
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}

	$sql = "SELECT  `name`, `num`, `unit`, `release account`, `release time`, `place of origin` FROM `supply products` ORDER BY `release time` DESC LIMIT 10";/*如果列名有空格，需要放到''中*/
    $result = $conn->query($sql);
	if ($result->num_rows > 0) {
                        echo '<table border="0">';
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><a href="product_details.php?id=' . '">' . $row['name'] . '</a></td>';
                            echo '<td>' . $row['release time'] . '</td>';
                            echo '<td>' . $row['place of origin'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo "0 结果";
                    }
	echo "连接成功";
	

?>

</body>
</html>