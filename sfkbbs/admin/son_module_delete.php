<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link=connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){//没有参数  或者参数有误，直接退出
	skip('son_module.php','错误的参数id');
}
$query="delete  from sfk_son_module where id={$_GET['id']}";
// echo $query;
execute($link,$query);
if(mysqli_affected_rows($link)==1){
	// exit('删除成功！');
	skip("son_module.php","删除成功!");
}else{
	skip('son_module.php','错误的参数id');
}
?>