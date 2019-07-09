<?php
date_default_timezone_set('PRC'); //默认时区北京时间
header("Content-type:text/html;charset=utf-8"); //定义字符集


if (!isset($_POST['submit'])) {
    include 'index.html';
    // echo '
    //     <p>请选择要上传的文件</p>
    //     <form action="#" method="post" enctype="multipart/form-data">
    //         <input type="file" name="upfile" id="upfile_input" /><br/><br/>
    //         <input type="submit" name="submit" value="上传" id="submit_input" />
    //     </form>
    //     ';
} else {
    $upload_dir = getcwd() . "\\doc\\"; //getcwd()获取当前脚本目录
    if (!is_dir($upload_dir)) //如果目录不存在,则创建
    {
        mkdir($upload_dir);
    }

    function makefilename()
    { //根据上传时间生成上传文件名
        $current = getdate();
        $filename = $current['year'] . $current['mon'] . $current['mday'] . $current['hours'] . $current['minutes'] . $current['seconds'] . ".xlsx";
        return $filename;
    }
    $newfilname = makefilename();
    $newfile = $upload_dir . $newfilname;
    if (file_exists($_FILES['upfile']['tmp_name'])) {
        move_uploaded_file($_FILES['upfile']['tmp_name'], $newfile);
        echo "上传的文件信息：<br/>";
        echo "客户端文件名：" . $_FILES['upfile']['name'] . "<br/>";
        // echo "文件类型：" . $_FILES['upfile']['type'] . "<br/>";
        echo "字节大小：" . $_FILES['upfile']['size'] . "<br/>";
        // echo "上传后文件名：" . $newfilname . "<br/>"; //显示路径输出$newfile
        echo "文件上传成功: " . '<a href="JavaScript:history.back()">继续上传其他文件</a>';
    } else {
        echo "上传文件失败，错误类型" . $_FILES['upfile']['error'];
    }
}
