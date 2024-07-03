<?php
session_start();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>主页</title>
	<script>//信息的上下页点击事件
		let purchasePage = 1;
		let supplyPage = 1;

		function changePage(type, increment) {
			if (type === 'purchase') {
				purchasePage += increment;
				loadPageData('purchase', purchasePage);
			} else if (type === 'supply') {
				supplyPage += increment;
				loadPageData('supply', supplyPage);
			}
		}

		function loadPageData(type, page) {
			const xhr = new XMLHttpRequest();
			xhr.open('GET', `get_mainpage_data.php?table=${type}&page=${page}`, true);
			xhr.onload = function() {
				if (this.status === 200) {
					if (type === 'purchase') {
						document.getElementById('purchase-info').innerHTML = this.responseText;
					} else if (type === 'supply') {
						document.getElementById('supply-info').innerHTML = this.responseText;
					}
				}
			};
			xhr.send();
		}

		// 初始化加载第一页数据
		loadPageData('purchase', purchasePage);
		loadPageData('supply', supplyPage);

	</script>
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

        /* 图片展示区样式 */
        .slideshow-container {
            position: relative;
            max-width: 70%;
            margin: auto;
            overflow: hidden;
        }

        .slideshow-container img {
            width: 100%;
            vertical-align: middle;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 28px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        /* 内容区域样式 */
        .content {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .content .section {
            width: 48%;
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

    <!-- 图片展示 -->
    <div class="slideshow-container">
        <div class="mySlides fade">
            <img src="pictures\main_pic1.png" alt="Image 1">
        </div>
        <div class="mySlides fade">
            <img src="pictures\main_pic2.png" alt="Image 2">
        </div>
        <div class="mySlides fade">
            <img src="pictures\main_pic3.png" alt="Image 3">
        </div>
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
	<hr></hr>

    <!-- 内容展示区 -->
    <div class="content">
		<div class="section">
			<h2>最新采购信息</h2>

			<!-- 这里将显示最新的采购信息 -->
			<div id="purchase-info"></div>

			<div class="pagination">
				<button onclick="changePage('purchase', -1)">上一页</button>
				<button onclick="changePage('purchase', 1)">下一页</button>
			</div>
		</div>
        <div class="section">
			<h2>最新供应信息</h2>

			<!-- 这里将显示最新的供应信息 -->
			<div id="supply-info"></div>

			<div class="pagination">
				<button onclick="changePage('supply', -1)">上一页</button>
				<button onclick="changePage('supply', 1)">下一页</button>
			</div>
		</div>
    </div>

    <!-- 页脚友情链接 -->
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

    <script>//是海报的左右滑动事件
        let slideIndex = 0;

        function showSlides(n) {
            let slides = document.getElementsByClassName("mySlides");
            if (n >= slides.length) { slideIndex = 0 }
            if (n < 0) { slideIndex = slides.length - 1 }
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex].style.display = "block";
        }

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function autoShowSlides() {
            let slides = document.getElementsByClassName("mySlides");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex >= slides.length) { slideIndex = 0 }
            slides[slideIndex].style.display = "block";
            setTimeout(autoShowSlides, 2000); // Change image every 2 seconds
        }

        document.querySelector('.prev').addEventListener('click', function() {
            plusSlides(-1);
        });

        document.querySelector('.next').addEventListener('click', function() {
            plusSlides(1);
        });

        autoShowSlides(); // Start automatic slide show
    </script>

</body>
</html>

