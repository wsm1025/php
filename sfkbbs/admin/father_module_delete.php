<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../../sfkbbs/admin/inc/tool.inc.php';
$link=connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){//没有参数  或者参数有误，直接退出
	skip('father_module.php','错误的参数id');
}
$query = "select * from sfk_son_module where father_module_id={$_GET['id']}";

if(mysqli_num_rows(execute($link,$query))){
	skip('father_module.php','该父板块下存在子版块，请将该下属子版块删除');
}
$query="delete  from sfk_father_module where id={$_GET['id']}";
// echo $query;
execute($link,$query);
if(mysqli_affected_rows($link)==1){
	// exit('删除成功！');
	skip("father_module.php","删除成功!");
}else{
	skip('father_module.php','错误的参数id');
}
?>