<?php
//文件合并
$target = "uploads/" .iconv("utf-8","gbk",$_POST["name"]);
$dst = fopen($target, 'wb');

for($i = 0; $i < $_POST['index']; $i++) {
    $slice = $target . '-' . $i;
    $src = fopen($slice, 'rb');
    stream_copy_to_stream($src, $dst);
    fclose($src);
    unlink($slice);
}

fclose($dst);
?>