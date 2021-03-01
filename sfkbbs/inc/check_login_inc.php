<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
Isempty($_POST['name'],'login.php','用户名不得为空',1);
if(mb_strlen(DelHtml($_POST['name']))>32){
    skip('login.php','字符数过长',1);
}
Isempty($_POST['pw'],'login.php','密码不得为空',1);
if(mb_strlen(DelHtml($_POST['pw']))<6){
    skip('login.php','密码最少6位',1);
}
Isempty($_POST['vcode'],'login.php','验证码不得为空',1);
if(mb_strtolower($_POST['vcode']) != $_SESSION['vcode']){
    skip('login.php','验证码错误!',1);
}
if(empty($_POST['time'])||is_numeric($_POST['time']) || $_POST['time']>2592000){
    $_POST['time'] = 2592000;
}
?>