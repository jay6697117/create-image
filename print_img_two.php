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
// echo '</pre>';

for ($i = 2; $i <= count($sheetData); $i++) {
    //定义变量接收excel数据
    $main_title = $sheetData[$i]['A']; //主标题
    $sub_title = $sheetData[$i]['B']; //副标题
    $retail_price = $sheetData[$i]['C']; //零售价
    $bottom_line1 = $sheetData[$i]['D']; //底一行
    $bottom_line2 = $sheetData[$i]['E']; //底二行
    $bottom_line3 = $sheetData[$i]['F']; //底三行
    $bottom_line4 = $sheetData[$i]['G']; //底四行
    $bottom_line5 = $sheetData[$i]['H']; //底五行

    //字符串处理
    $bottom_line1_left = trim(explode(':', $bottom_line1)[0], ' ') . ': ';
    $bottom_line1_right = trim(explode(':', $bottom_line1)[1], ' ');
    $sub_title = ucwords($sub_title); //英文首字母大写

    //定义'元/个'变量
    $yuan_per_one = '元/个';

    //输出画布
    $im = imagecreatetruecolor(945, 472); // 设置画布
    $bg = imagecreatefromjpeg('./bgimg/bg2.jpg'); // 设置背景图片
    imagecopy($im, $bg, 0, 0, 0, 0, 945, 472); // 将背景图片拷贝到画布相应位置
    imagedestroy($bg); // 销毁背景图片

    // 定义颜色以供调用
    $white = imagecolorallocate($im, 255, 255, 255);

    //获得文字区域
    $textAreaMainTitle = imagettfbbox(66, 0, __DIR__ . '/font/lanting_cuheicn.TTF', $main_title); //主标题
    $textAreaSubTitle = imagettfbbox(20, 0, __DIR__ . '/font/FZXBSJW.TTF', $sub_title); //副标题
    $textAreaRetailPrice = imagettfbbox(66, 0, __DIR__ . '/font/lanting_cuheicn.TTF', $retail_price); //零售价
    $textAreaYuanPerOne = imagettfbbox(33, 0, __DIR__ . '/font/lanting_cuheicn.TTF', $yuan_per_one); //元/个
    $textAreaLine1Left = imagettfbbox(13, 0, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line1_left); //底一行左

    //计算文字区域宽度
    $MainTitleWidth = $textAreaMainTitle[2] - $textAreaMainTitle[0]; //主标题：宽
    $SubTitleWidth = $textAreaSubTitle[2] - $textAreaSubTitle[0]; //副标题：宽
    $RetailPriceWidth = $textAreaRetailPrice[2] - $textAreaRetailPrice[0]; //零售价：宽
    $YuanPerOneWidth = $textAreaYuanPerOne[2] - $textAreaYuanPerOne[0]; //元/个：宽
    $Line1LeftWidth = $textAreaLine1Left[2] - $textAreaLine1Left[0]; //底一行左：宽

    //底一行右：限定超过某个字符长度换行
    $bottom_line1_right = forceBlackString($bottom_line1_right, 51);

    // 绘制文字
    //1.可变文字
    imagettftext($im, 66, 0, 825 - $MainTitleWidth, 110, $white, __DIR__ . '/font/lanting_cuheicn.TTF', $main_title);
    imagettftext($im, 20, 0, 825 - $SubTitleWidth, 168, $white, __DIR__ . '/font/FZXBSJW.TTF', $sub_title);
    imagettftext($im, 66, 0, 800 - $YuanPerOneWidth - $RetailPriceWidth, 268, $white, __DIR__ . '/font/lanting_cuheicn.TTF', $retail_price); //零售价
    imagettftext($im, 13, 0, 320, 312, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line1_left);
    imagettftext($im, 13, 0, 320 + $Line1LeftWidth, 312, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line1_right);
    imagettftext($im, 12, 0, 320, 386, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line2);
    imagettftext($im, 12, 0, 320, 408, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line3);
    imagettftext($im, 12, 0, 320, 430, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line4);
    imagettftext($im, 12, 0, 320, 452, $white, __DIR__ . '/font/lanting_zhongheicn.TTF', $bottom_line5);
    //2.固定文字：元/个
    imagettftext($im, 33, 0, 825 - $YuanPerOneWidth, 268, $white, __DIR__ . '/font/lanting_cuheicn.TTF', $yuan_per_one);

    //输出图片
    imagepng($im, 'pic/pic' . ($i - 1) . '.jpg'); // 生成jpeg格式图片
    imagedestroy($im); // 销毁图片

}

require_once './api/zipimg.php';
unlink($newfile);

//换行函数
function forceBlackString($str, $num)
{
    if (strlen($str) > $num) {
        $tempStr = substr($str, 0, $num);
        $lastStr = substr($str, $num);
        $str = $tempStr . PHP_EOL . $lastStr;
    }
    return $str;
}
