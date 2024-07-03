<?php
	session_start();
?>
<!DOCTYPE html>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>采购大厅</title>
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

        /* 供应信息和采购信息表格格式 */
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

        /* 分页样式 */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #000;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #ddd;
            color: black;
        }

        .pagination strong {
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #70DC88;
            color: white;
        }
    </style>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#searchForm').on('submit', function(e){
                e.preventDefault(); // 防止表单提交

                $.ajax({
                    type: 'GET',
                    url: 'search_purchase.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('.search-results').html(response);
                    }
                });
            });

            // 处理分页链接的点击事件
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                var form_data = $('#searchForm').serialize() + '&page=' + page;

                $.ajax({
                    type: 'GET',
                    url: 'search_purchase.php',
                    data: form_data,
                    success: function(response) {
                        $('.search-results').html(response);
                    }
                });
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

    <!-- 搜索框 -->
    <div class="search-container">
        <form id="searchForm" action="search_purchase.php" method="GET">
            <input type="text" placeholder="产品名称" name="product_name" required>
            <input type="text" placeholder="数量" name="quantity"><text color=#808080 font-size='10px'>kg</text>
            <input type="text" placeholder="原产地" name="origin">
            <input type="text" placeholder="目的地" name="destination">
            <select name="sort_order">
                <option value="release_time_asc">按日期升序</option>
                <option value="release_time_desc">按日期降序</option>
                <option value="price_asc">按价格升序</option>
                <option value="price_desc">按价格降序</option>
            </select>
            <button type="submit">搜索</button>
            <button type="reset">重置</button>
        </form>
    </div>

    <!-- 搜索结果 -->
    <div class="search-results">
        <!-- AJAX 结果将显示在这里 -->
    </div>

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
