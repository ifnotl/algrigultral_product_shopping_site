<?php
	session_start();
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>登陆页面</title>
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
		/*登陆风格设置*/
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #70DC88;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #5CD778;
        }
        .captcha-image {
            display: flex;
            align-items: center;
        }
        .captcha-image img {
            margin-left: 10px;
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
            <a href="#" class="login">欢迎, <?php echo htmlspecialchars($_SESSION['name']); ?></a>
        <?php else: ?>
            <a href="login_page.php" target="_blank" class="login">登录</a>
        <?php endif; ?>
    </div>  <!--这里做好网页后加入连接--!>

		
	<!-- 登陆界面 -->
    <div class="container">
        <form action="search_login.php" method="post">
            <div class="form-group">
                <label for="role">角色</label>
                <select name="role" id="role">
                    <option value="supplier">供应商</option>
                    <option value="purchaser">采购商</option>
                    <option value="admin">管理员</option>
                </select>
            </div>
            <div class="form-group">
                <label for="account">账号</label>
                <input type="text" name="account" id="account" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
				<label for="captcha">验证码</label>
				<div class="captcha-image" id="captchaContainer">
					<input type="text" name="captcha" id="captcha" required>
					<img id="captchaImage" src="captcha.php" alt="验证码">
				</div>
            </div>
            <div class="buttons">
                <button type="submit">确认登录</button>
                <button type="reset">重置</button>
            </div>
        </form>
    </div>
	<!-- 获取验证码 -->
	<script>
		document.getElementById('captchaImage').onclick = function() {
			this.src = 'captcha.php?' + Math.random();
		}
    </script>

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

