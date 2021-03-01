<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
include_once 'tool.inc.php';
Isempty($_POST['name'],'register.php','用户名不得为空');
if(mb_strlen(DelHtml($_POST['name']))>32){
    skip('register.php','字符数过长');
}
Isempty($_POST['pw'],'register.php','密码不得为空');
if(mb_strlen(DelHtml($_POST['pw']))<6 || mb_strlen(DelHtml($_POST['confirm_pw']))<6){
    skip('register.php','密码最少6位');
}
Isempty($_POST['confirm_pw'],'register.php','确认密码不得为空');
Isempty($_POST['vcode'],'register.php','验证码不得为空');
if($_POST['pw']!=$_POST['confirm_pw']){
    skip('register.php','密码和确认密码不一致');
}
if(mb_strtolower($_POST['vcode']) != $_SESSION['vcode']){
    skip('register.php','验证码错误!');
}
$_POST = escape($link,DelHtml($_POST));
$query = "select * from sfk_member where name= '{$_POST['name']}'";
if(mysqli_num_rows(execute($link,$query))){
    skip('register.php','该用户名已被注册');
}
?>