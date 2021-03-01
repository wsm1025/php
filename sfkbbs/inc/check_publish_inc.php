<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
Isempty($_POST['module_id'],'publish.php','请选择一个父板块');
Isnumber($_POST['module_id'],'publish.php','请选择一个父板块');
$query = "select * from sfk_son_module where id={$_POST['module_id']}";
if(mysqli_num_rows(execute($link,$query))!=1){
    skip('publish.php','请选择一个父板块');
}
Isempty($_POST['title'],$_POST['content'],'标题不得为空');
if(mb_strlen(DelHtml($_POST['title']))>255){
    skip($_SERVER['REQUEST_URI'],'标题字符数过长,仅限255字符');
}
Isempty(trim($_POST['content']," "),$_SERVER['REQUEST_URI'],'内容不得为空');
?>