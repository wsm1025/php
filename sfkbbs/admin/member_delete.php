<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link=connect();
if(!ManageIslogin($link)){
	skip('index.php', '未登录');
}
if($_SESSION['manage']['level']!=0){
		skip('index.php', '权限不足'); 
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){//没有参数  或者参数有误，直接退出
	skip('user_list.php','错误的参数id');
}
$link=connect();
if($_GET['id']==1){
	skip("index.php","非法操作");	
}
$query="delete  from sfk_member where id={$_GET['id']}";
execute($link,$query);
if(mysqli_affected_rows($link)==1){
	skip("user_list.php","删除成功!");
}else{
	skip('user_list.php','错误的参数id');
}
?>