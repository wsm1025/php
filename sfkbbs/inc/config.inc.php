<?php 
date_default_timezone_set('Asia/Shanghai');
define('IN_WSM',true);
session_start();
header('Content-type:text/html;charset=utf-8');
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','980703');
define('DB_DATABASE','sfkbbs');
define('DB_PORT',3306);
define('IMG_USER','https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=1851241116,346865000&fm=11&gp=0.jpg');
//定义全局变量为其他php提供参数
//绝对路径
define("A_PATH",dirname(dirname(__FILE__)));
//相对路径
define('Pth',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',A_PATH)).'/');//项目地址
if(!file_exists(A_PATH.'/inc/install.lock')){
	header("Location:".Pth."install.php");
}
?>