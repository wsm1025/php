<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
$template['title'] = '添加管理员';
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if($_SESSION['manage']['level']!=0){
    if(!isset($_SERVER['HTTP_REFERER'])){
		$_SERVER['HTTP_REFERER']='index.php';
	}
		skip('index.php', '权限不足'); 
}
?>
<?php 
if(isset($_POST['submit'])){
    Isempty($_POST['name'],'manage_add.php','管理员名称不能为空',2);
    Isempty($_POST['pw'],'manage_add.php','密码不能为空',2);
    Isempty($_POST['doublepw'],'manage_add.php','确认密码不能为空',2);
    if(mb_strlen(DelHtml($_POST['name'])) >=10){
        skip('manage_add.php','管理员名称过长',2);
    };
    if($_POST['doublepw']!=$_POST['pw']){
        skip('manage_add.php','密码与确认密码不一致',2);
    }
    if(mb_strlen(DelHtml($_POST['pw'])) < 6){
        skip('manage_add.php','管理员密码不能少于6位',2);
    };
    $_POST = escape($link,DelHtml($_POST));
    //检测名称是否重复
    $query = "select * from sfk_manage where name='{$_POST['name']}'";
    if(mysqli_num_rows(execute($link,$query))){
        skip('manage_add.php','管理员名称重复',2);
    }
    if(!isset($_POST['level'])){
        $_POST['level']=1;
    }elseif($_POST['level']=="0"){
        $_POST['level']=0;
    }elseif($_POST['level']=="1"){
        $_POST['level']=1;
    }else{
        $_POST['level']=1;
    }
    $query = "insert into sfk_manage(name,pw,createtime,level) values('{$_POST['name']}',md5('{$_POST['pw']}'),now(),{$_POST['level']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('manage_add.php','管理员添加成功',2);
    }else{
        skip('manage_add.php','管理员添加失败,请重试',2);
    }
}
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
        <div class="title">添加管理员</div>
        <form method="post">
        <table class="au">
        <tr>
            <td>管理员名称</td>
            <td><input name="name" type="text"/></td>
            <td>不为空,10个字符</td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input name="pw" type="password"/></td>
            <td>不为空,不少于6位</td>
        </tr>
        <tr>
            <td>确认密码</td>
            <td><input name="doublepw" type="password"/></td>
            <td>不为空,不少于6位</td>
        </tr>
        <tr>
            <td>等级</td>
            <td> 
               <select name="level">
                    <option value="0">超级管理员</option>
                    <option value="1" selected>普通管理员</option>
                </select>
             </td>
        </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="确定"/>
        </form>
    </div>
<?php include_once 'inc/footer.inc.php' ?>

