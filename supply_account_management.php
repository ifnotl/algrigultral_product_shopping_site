<?php
session_start();
//这段代码在html之前，确保读取数据库，还有顶端的显示！！！

// 检查用户是否登录并且角色为供应商
if (!isset($_SESSION['account']) || $_SESSION['role'] !== 'supplier') {
    header("Location: login_page.php");
    exit(); // 确保在重定向之后立即停止脚本执行
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
    die("连接失败: " . mysqli_connect_error());
}

// 获取当前供应商的账号信息
$supplier_account = $_SESSION['account'];
$sql = "SELECT name FROM `supplier` WHERE `account` = '$supplier_account'";
$result = mysqli_query($conn, $sql);
$supplier = mysqli_fetch_assoc($result);

// 检查是否成功获取供应商信息
if (!$supplier) {
    die("未找到相关供应商信息");
}

?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>账号管理</title>
    <style>
        /* 调整主内容容器的位置 */
        .main-content {
            margin-top: -280px; /* 根据需要调整 */
            padding: 20px;
        }

        /* 调整表单样式 */
        .form-container {
            margin-bottom: 20px; /* 下方外边距20px */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        /* 使输入框和标签垂直排列 */
        .form-container label,
        .form-container input {
            display: block;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 	<script>
        $(document).ready(function() {
            const locations = {
    '北京市': {
        '市辖区': ['东城区', '西城区', '朝阳区', '丰台区', '石景山区']
    },
    '天津市': {
        '市辖区': ['和平区', '河东区', '河西区', '南开区', '河北区']
    },
    '上海市': {
        '市辖区': ['黄浦区', '徐汇区', '长宁区', '静安区', '普陀区']
    },
    '重庆市': {
        '市辖区': ['万州区', '涪陵区', '渝中区', '大渡口区', '江北区']
    },
    '河北省': {
        '石家庄市': ['长安区', '桥西区', '新华区', '井陉矿区', '裕华区'],
        '唐山市': ['路南区', '路北区', '古冶区', '开平区', '丰南区']
    },
    '山西省': {
        '太原市': ['小店区', '迎泽区', '杏花岭区', '尖草坪区', '万柏林区'],
        '大同市': ['城区', '矿区', '南郊区', '新荣区']
    },
    '辽宁省': {
        '沈阳市': ['和平区', '沈河区', '大东区', '皇姑区', '铁西区'],
        '大连市': ['中山区', '西岗区', '沙河口区', '甘井子区', '旅顺口区']
    },
    '吉林省': {
        '长春市': ['南关区', '宽城区', '朝阳区', '二道区', '绿园区'],
        '吉林市': ['昌邑区', '龙潭区', '船营区', '丰满区']
    },
    '黑龙江省': {
        '哈尔滨市': ['道里区', '南岗区', '道外区', '平房区', '松北区'],
        '齐齐哈尔市': ['龙沙区', '建华区', '铁锋区', '昂昂溪区', '富拉尔基区']
    },
    '江苏省': {
        '南京市': ['玄武区', '秦淮区', '建邺区', '鼓楼区', '浦口区'],
        '苏州市': ['姑苏区', '虎丘区', '吴中区', '相城区', '吴江区']
    },
    '浙江省': {
        '杭州市': ['上城区', '下城区', '江干区', '拱墅区', '西湖区'],
        '宁波市': ['海曙区', '江北区', '北仑区', '镇海区', '鄞州区']
    },
    '安徽省': {
        '合肥市': ['瑶海区', '庐阳区', '蜀山区', '包河区'],
        '芜湖市': ['镜湖区', '弋江区', '鸠江区', '三山区']
    },
    '福建省': {
        '福州市': ['鼓楼区', '台江区', '仓山区', '马尾区', '晋安区'],
        '厦门市': ['思明区', '海沧区', '湖里区', '集美区', '同安区']
    },
    '江西省': {
        '南昌市': ['东湖区', '西湖区', '青云谱区', '湾里区', '青山湖区'],
        '九江市': ['濂溪区', '浔阳区', '柴桑区']
    },
    '山东省': {
        '济南市': ['历下区', '市中区', '槐荫区', '天桥区', '历城区'],
        '青岛市': ['市南区', '市北区', '黄岛区', '崂山区', '李沧区']
    },
    '河南省': {
        '郑州市': ['中原区', '二七区', '管城回族区', '金水区', '上街区'],
        '洛阳市': ['老城区', '西工区', '瀍河回族区', '涧西区', '吉利区']
    },
    '湖北省': {
        '武汉市': ['江岸区', '江汉区', '硚口区', '汉阳区', '武昌区'],
        '黄石市': ['黄石港区', '西塞山区', '下陆区', '铁山区']
    },
    '湖南省': {
        '长沙市': ['芙蓉区', '天心区', '岳麓区', '开福区', '雨花区'],
        '株洲市': ['荷塘区', '芦淞区', '石峰区', '天元区']
    },
    '广东省': {
        '广州市': ['越秀区', '海珠区', '荔湾区', '天河区', '白云区'],
        '深圳市': ['罗湖区', '福田区', '南山区', '宝安区', '龙岗区']
    },
    '广西壮族自治区': {
        '南宁市': ['兴宁区', '青秀区', '江南区', '西乡塘区', '良庆区'],
        '柳州市': ['城中区', '鱼峰区', '柳南区', '柳北区']
    },
    '海南省': {
        '海口市': ['秀英区', '龙华区', '琼山区', '美兰区'],
        '三亚市': ['海棠区', '吉阳区', '天涯区', '崖州区']
    },
    '四川省': {
        '成都市': ['锦江区', '青羊区', '金牛区', '武侯区', '成华区'],
        '绵阳市': ['涪城区', '游仙区', '安州区']
    },
    '贵州省': {
        '贵阳市': ['南明区', '云岩区', '花溪区', '乌当区', '白云区'],
        '遵义市': ['红花岗区', '汇川区', '播州区']
    },
    '云南省': {
        '昆明市': ['五华区', '盘龙区', '官渡区', '西山区', '东川区'],
        '曲靖市': ['麒麟区', '沾益区', '马龙区']
    },
    '西藏自治区': {
        '拉萨市': ['城关区', '堆龙德庆区', '达孜区'],
        '日喀则市': ['桑珠孜区', '南木林县', '江孜县']
    },
    '陕西省': {
        '西安市': ['新城区', '碑林区', '莲湖区', '灞桥区', '未央区'],
        '咸阳市': ['秦都区', '杨陵区', '渭城区']
    },
    '甘肃省': {
        '兰州市': ['城关区', '七里河区', '西固区', '安宁区'],
        '天水市': ['秦州区', '麦积区']
    },
    '青海省': {
        '西宁市': ['城东区', '城中区', '城西区', '城北区'],
        '海东市': ['乐都区', '平安区']
    },
    '宁夏回族自治区': {
        '银川市': ['兴庆区', '西夏区', '金凤区'],
        '石嘴山市': ['大武口区', '惠农区']
    },
    '新疆维吾尔自治区': {
        '乌鲁木齐市': ['天山区', '沙依巴克区', '新市区', '水磨沟区'],
        '克拉玛依市': ['独山子区', '克拉玛依区']
    }
};

			function initializeSelects(provinceSelect, citySelect, countySelect) {
                for (let province in locations) {
                    provinceSelect.append(new Option(province, province));
                }

                provinceSelect.change(function() {
                    citySelect.empty().append(new Option('请选择城市', ''));
                    countySelect.empty().append(new Option('请选择县/区', ''));

                    let selectedProvince = $(this).val();
                    if (selectedProvince) {
                        let cities = locations[selectedProvince];
                        for (let city in cities) {
                            citySelect.append(new Option(city, city));
                        }
                    }
                });

                citySelect.change(function() {
                    countySelect.empty().append(new Option('请选择县/区', ''));

                    let selectedProvince = provinceSelect.val();
                    let selectedCity = $(this).val();
                    if (selectedProvince && selectedCity) {
                        let counties = locations[selectedProvince][selectedCity];
                        for (let county of counties) {
                            countySelect.append(new Option(county, county));
                        }
                    }
                });
            }

            // 初始化每个表单的省市县选择框
            initializeSelects($('#provinceSelect1'), $('#citySelect1'), $('#countySelect1'));
            initializeSelects($('#provinceSelect2'), $('#citySelect2'), $('#countySelect2'));
            initializeSelects($('#provinceSelect3'), $('#citySelect3'), $('#countySelect3'));
        });

    </script>
</head>
<body>
    <div class="main-content">
        <!-- 修改名称表单 -->
        <form id="nameForm" class="form-container ajaxForm" method="post" action="process_update_name.php">
            <label for="name">修改名称:</label>
            <input type="text" name="name" id="name" value="" required>
            <button type="submit">更新名称</button>
        </form>

        <!-- 修改密码表单 -->
        <form id="passwordForm" class="form-container ajaxForm" method="post" action="process_update_password.php" onsubmit="return validatePasswordForm()">
            <label for="current_password">当前密码:</label>
            <input type="password" name="current_password" id="current_password" required>
            <label for="new_password">新密码:</label>
            <input type="password" name="new_password" id="new_password" required>
            <label for="confirm_password">确认新密码:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit">更新密码</button>
        </form>

        <!-- 修改地址表单 -->
        <form id="addressForm1" class="form-container ajaxForm" method="post" action="process_update_address.php">
            <label for="provinceSelect1">省份:</label>
            <select id="provinceSelect1" name="province_id" required>
                <option value="">请选择省份</option>
            </select>

            <label for="citySelect1">城市:</label>
            <select id="citySelect1" name="city_id" required>
                <option value="">请选择城市</option>
            </select>

            <label for="countySelect1">县/区:</label>
            <select id="countySelect1" name="county_id" required>
                <option value="">请选择县/区</option>
            </select>

            <input type="hidden" name="form_id" value="addressForm1">
            <button type="submit">更新地址</button>
        </form>
        
        <form id="addressForm2" class="form-container ajaxForm" method="post" action="process_update_address.php">
            <label for="provinceSelect2">省份:</label>
            <select id="provinceSelect2" name="province_id" required>
                <option value="">请选择省份</option>
            </select>

            <label for="citySelect2">城市:</label>
            <select id="citySelect2" name="city_id" required>
                <option value="">请选择城市</option>
            </select>

            <label for="countySelect2">县/区:</label>
            <select id="countySelect2" name="county_id" required>
                <option value="">请选择县/区</option>
            </select>

            <input type="hidden" name="form_id" value="addressForm2">
            <button type="submit">更新地址</button>
        </form>
        
        <form id="addressForm3" class="form-container ajaxForm" method="post" action="process_update_address.php">
            <label for="provinceSelect3">省份:</label>
            <select id="provinceSelect3" name="province_id" required>
                <option value="">请选择省份</option>
            </select>

            <label for="citySelect3">城市:</label>
            <select id="citySelect3" name="city_id" required>
                <option value="">请选择城市</option>
            </select>

            <label for="countySelect3">县/区:</label>
            <select id="countySelect3" name="county_id" required>
                <option value="">请选择县/区</option>
            </select>

            <input type="hidden" name="form_id" value="addressForm3">
            <button type="submit">更新地址</button>
        </form>
		
    </div>
	<!--和前两个一样，写到自己的页面来，不用dashboard那个-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
		$(document).ready(function() {
    console.log("JavaScript loaded successfully.");

    // 使用事件委托处理所有带有 .ajaxForm 类的表单提交
    $('body').on('submit', '.ajaxForm', function(event) {
        event.preventDefault(); // 阻止表单的默认提交行为
        console.log("表单已提交.");

        var formData = $(this).serialize(); // 序列化表单数据
        var actionUrl = $(this).attr('action'); // 获取action指向的url

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('AJAX 请求成功，响应:', response);
                if (response.status === 'success') {
                    alert(response.message);
                    if (response.url) {
                        window.location.href = response.url;
                    }
                } else if (response.status === 'error') {
                    alert(response.message);
                } else if (response.status === 'redirect') {
                    window.location.href = response.url;
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX 请求失败:", error);
                alert("发生错误，请稍后再试");
            }
        });
    });
});
</script>

</body>
</html>
