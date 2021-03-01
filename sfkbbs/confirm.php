<?php
include_once 'inc/config.inc.php';
include_once 'inc/tool.inc.php';
// print_r($_SERVER['HTTP_REFERER']);
if(!isset($_GET['message']) || !isset($_GET['url'])||!isset($_GET['return_url'])){//检测字段
 exit('参数错误');   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>删除模块</title>
</head>
<body>
    <p style="text-align: center;">
        你真的要删除<span><?php echo DelHtml($_GET['message'])?></span>吗?
        <a style="color: red;" href="<?php echo $_GET['url']."&return_url=".$_GET['return_url']?>">确定</a>
        <a href="<?php echo $_GET['return_url']?>">取消</a>
    </p>
</body>
</html>