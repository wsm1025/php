<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '站点设置';
$link = connect();
if(!ManageIslogin($link)){
    skip('login.php', '未登录');
}
$query ="select * from php_web where id = 1";
$data= mysqli_fetch_assoc(execute($link,$query));
if(isset($_POST['submit'])){
    $_POST = escape($link,DelHtml($_POST));
    $query ="update php_web set title='{$_POST['title']}',keyword='{$_POST['keyword']}',description='{$_POST['description']}' where id = 1";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('web_set.php','数据修改成功');
    }else{
        skip('web_set.php','数据修改失败');
    }
}
?>
<?php include 'inc/header.inc.php'?>
<div id="main">
        <div class="title"><?php echo $template['title']?></div>
        <form method="post">
        <table class="au">
        <tr>
            <td>网站标题</td>
            <td><input name="title" value="<?php echo $data['title']?>" type="text"/></td>
            <td>前台网站标题</td>
        </tr>
        <tr>
            <td>关键字</td>
            <td><input name="keyword"  value="<?php echo $data['keyword']?>" type="text"/></td>
            <td>网站关键字</td>
        </tr>
        <tr>
            <td>描述</td>
            <td><textarea name="description"   type="text"><?php echo $data['description']?></textarea></td>
            <td>网站描述</td>
        </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="设置"/>
        </form>
    </div>   
<?php include 'inc/footer.inc.php'?>   