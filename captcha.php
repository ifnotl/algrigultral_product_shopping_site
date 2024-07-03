<?php
session_start();

// 生成随机验证码
$captcha_code = '';
for ($i = 0; $i < 4; $i++) {
    $captcha_code .= rand(0, 9);
}

// 将验证码存储到 session 中
$_SESSION['captcha'] = $captcha_code;

// 创建图片
$width = 100;
$height = 40;
$image = imagecreatetruecolor($width, $height);

// 设置颜色
$background_color = imagecolorallocate($image, 255, 255, 255); // 白色背景
$text_color = imagecolorallocate($image, 0, 0, 0); // 黑色文字
$line_color = imagecolorallocate($image, 64, 64, 64); // 灰色线条
$pixel_color = imagecolorallocate($image, 0, 0, 255); // 蓝色像素

// 填充背景色
imagefilledrectangle($image, 0, 0, $width, $height, $background_color);

// 添加随机线条
for ($i = 0; $i < 3; $i++) {
    imageline($image, 0, rand() % $height, $width, rand() % $height, $line_color);
}

// 添加随机像素
for ($i = 0; $i < 1000; $i++) {
    imagesetpixel($image, rand() % $width, rand() % $height, $pixel_color);
}

// 添加验证码到图片
$font_size = 20;
$text_box = imagettfbbox($font_size, 0, 'arial.ttf', $captcha_code); // 获取文字边界框
$text_width = $text_box[2] - $text_box[0]; // 文字宽度
$text_height = $text_box[3] - $text_box[5]; // 文字高度
$x = ($width - $text_width) / 2; // 计算x坐标使文字居中
$y = ($height - $text_height) / 2 + $text_height; // 计算y坐标使文字垂直居中
imagettftext($image, $font_size, 0, $x, $y, $text_color, 'arial.ttf', $captcha_code); // 使用TrueType字体添加文字

// 输出图片
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
