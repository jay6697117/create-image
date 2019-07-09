<?php
date_default_timezone_set('PRC'); //默认时区北京时间
header("Content-type:text/html;charset=utf-8"); //定义字符集
require_once "./api/upload_file.php";

// 读取excel文件，并进行相应处理
$fileName = 'doc/' . $newfilname;
if (!file_exists($fileName)) {
    echo '<br />';
    exit("文件不存在: " . '<a href="JavaScript:history.back()">重新选择上传</a>');
}

$startTime = time(); //返回当前时间的Unix时间戳
require_once './lib/PHPExcel/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($fileName);

//获取sheet表格数目
$sheetCount = $objPHPExcel->getSheetCount();

//默认选中sheet1表
$sheetSelected = 0;
$objPHPExcel->setActiveSheetIndex($sheetSelected);

//获取表格行数
$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();

//获取表格列数
$columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();

//接收数据的数组
$sheetData = array();
// 循环读取每个单元格的数据
//1.行数循环
for ($row = 1; $row <= $rowCount; $row++) {
    //2.列数循环 , 列数是以A列开始
    for ($column = 'A'; $column <= $columnCount; $column++) {
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    }
    $dataArr = null;
}

//测试PHPExcel代码
// echo '<pre>';
// var_dump($sheetData);
// echo count($sheetData);
// echo '</pre>';

for ($i = 2; $i <= count($sheetData); $i++) {
    //定义变量接收excel数据
    $main_title = $sheetData[$i]['A'];
    $sub_title = $sheetData[$i]['B'];
    $retail_price = $sheetData[$i]['C'];
    $member_price = $sheetData[$i]['D'];
    $right_line1 = $sheetData[$i]['E'];
    $right_line2 = $sheetData[$i]['F'];
    $right_line3 = $sheetData[$i]['G'];
    $right_line4 = $sheetData[$i]['H'];
    $right_line5 = $sheetData[$i]['I'];
    $right_line6 = $sheetData[$i]['J'];

    //'元/个'
    $yuan_per_one = '元/个';

    //输出画布
    $im = imagecreatetruecolor(1134, 661); // 设置画布
    $bg = imagecreatefromjpeg('./bgimg/bg1.jpg'); // 设置背景图片
    imagecopy($im, $bg, 0, 0, 0, 0, 1134, 661); // 将背景图片拷贝到画布相应位置
    imagedestroy($bg); // 销毁背景图片

    // 定义颜色以供调用
    $black = imagecolorallocate($im, 0, 0, 0);
    $gray = imagecolorallocate($im, 104, 100, 101);

    //获得文字区域
    $textArrRetailPrice = imagettfbbox(85, 0, __DIR__ . '/font/lanting_zhongheicn.TTF', $retail_price); //零售价
    $textArrMemberPrice = imagettfbbox(85, 0, __DIR__ . '/font/lanting_cuheicn.TTF', $member_price); //会员价
    $textArr1 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line1); //右1行
    $textArr2 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line2); //右2行
    $textArr3 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line3); //右3行
    $textArr4 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line4); //右4行
    $textArr5 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line5); //右5行
    $textArr6 = imagettfbbox(17, 0, __DIR__ . '/font/lanting_heicn.TTF', $right_line6); //右6行

    //计算文字区域宽度
    $RetailPriceWidth = $textArrRetailPrice[2] - $textArrRetailPrice[0]; //零售价
    $MemberPriceWidth = $textArrMemberPrice[2] - $textArrMemberPrice[0]; //会员价
    $right_line1_width = $textArr1[2] - $textArr1[0]; //右1行
    $right_line2_width = $textArr2[2] - $textArr2[0]; //右2行
    $right_line3_width = $textArr3[2] - $textArr3[0]; //右3行
    $right_line4_width = $textArr4[2] - $textArr4[0]; //右4行
    $right_line5_width = $textArr5[2] - $textArr5[0]; //右5行
    $right_line6_width = $textArr6[2] - $textArr6[0]; //右6行

    // 定义副标题不在的情况主标题的水平方向起点的定位
    $y_main = ($sub_title === '' || $sub_title === null) ? 168 : 148;

    // 绘制文字
    //1.可变文字
    imagettftext($im, 88, 0, 58, $y_main, $black, __DIR__ . '/font/lanting_cuheicn.TTF', $main_title);
    imagettftext($im, 22, 0, 58, 218, $black, __DIR__ . '/font/lanting_cuheicn.TTF', $sub_title);
    imagettftext($im, 85, 0, 230, 375, $gray, __DIR__ . '/font/lanting_zhongheicn.TTF', $retail_price); //零售价
    imagettftext($im, 85, 0, 230, 535, $black, __DIR__ . '/font/lanting_cuheicn.TTF', $member_price); //会员价
    imagettftext($im, 17, 0, 1066 - $right_line1_width, 345, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line1);
    imagettftext($im, 17, 0, 1066 - $right_line2_width, 380, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line2);
    imagettftext($im, 17, 0, 1066 - $right_line3_width, 415, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line3);
    imagettftext($im, 17, 0, 1066 - $right_line4_width, 450, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line4);
    imagettftext($im, 17, 0, 1066 - $right_line5_width, 485, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line5);
    imagettftext($im, 17, 0, 1066 - $right_line6_width, 520, $gray, __DIR__ . '/font/lanting_heicn.TTF', $right_line6);
    //2.左侧固定文字：元/个
    imagettftext($im, 43, 0, 245 + $RetailPriceWidth, 375, $gray, __DIR__ . '/font/lanting_heicn.TTF', $yuan_per_one);
    imagettftext($im, 43, 0, 250 + $MemberPriceWidth, 535, $black, __DIR__ . '/font/lanting_heicn.TTF', $yuan_per_one);

    //输出图片
    imagejpeg($im, 'pic/pic' . ($i - 1) . '.jpg'); // 生成jpeg格式图片
    imagedestroy($im); // 销毁图片
}

require_once './api/zipimg.php';
unlink($newfile);