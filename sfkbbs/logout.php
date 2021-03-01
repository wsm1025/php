<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if(!IsLogin($link)){
    skip('index.php','您没有登录,此操作无效');
}
setcookie('sfk[name]',"",time()-1);
setcookie('sfk[pw]',"",time()-1);
		skip('index.php','退出成功',1);
?>
