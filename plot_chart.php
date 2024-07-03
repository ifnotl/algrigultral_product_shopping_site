<?php
	session_start();
?>
<!DOCTYPE html>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>价格对比图</title>
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

        /* 鼠标悬停效果 */
        .navbar a:hover {
            background-color: #5CD778; /* 鼠标悬停时变色 */
            color: black;
        }

        /* 底部的友情链接 */
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
	</style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
        document.addEventListener('DOMContentLoaded', function() {
            var data = JSON.parse(window.localStorage.getItem('chartData'));
            
            var ctx = document.getElementById('myChart').getContext('2d');
            var labels = data.map(function(item) {
                return item.id;
            });

            var prices = data.map(function(item) {
                return item.price;
            });

            var quantities = data.map(function(item) {
                return item.num;
            });

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '价格',
                        data: prices,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-axis-1'
                    }, {
                        label: '数量',
                        data: quantities,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        yAxisID: 'y-axis-2'
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            type: 'linear',
                            display: true,
                            position: 'left',
                            id: 'y-axis-1',
                        }, {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            id: 'y-axis-2',
                            gridLines: {
                                drawOnChartArea: false,
                            },
                        }]
                    }
                }
            });
        });
    </script>
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
	
	<canvas id="myChart" width="300" height="150"></canvas>

    <div class="footer">
        <p>友情链接</p>
        <ul>
            <li>
                <a href="https://gaoyou.yangzhou.gov.cn/gaoyou/index.shtml">高邮市人民政府</a>
                <a href="http://dag.gaoyou.gov.cn/">高邮市档案史志馆</a>
            </li>
            <li>
                <a href="https://yzgy.jszwfw.gov.cn/">高邮市政务服务网</a>
                <a href="https://www.cnhnb.com/">农产品购物：惠农网</a>
            </li>
        </ul>
    </div>
</body>
</html>