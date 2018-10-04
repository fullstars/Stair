<?php
//省略了文件接收判断isset部分
//当前目录下建立一个uploads文件夹
//接收文件名时进行转码，防止中文乱码。
$target = "uploads/" .iconv("utf-8","gbk",$_POST["name"]) . '-' . $_POST['index'];
move_uploaded_file($_FILES['file']['tmp_name'], $target);

// Might execute too quickly.
sleep(1);
?>