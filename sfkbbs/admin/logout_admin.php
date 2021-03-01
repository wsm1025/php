<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link=connect();
if(!ManageIslogin($link)){
	header('Location:login.php');
}
session_unset();
session_destroy();
setcookie(session_name(),time()-1,'/');
header('Location:login.php');
?>