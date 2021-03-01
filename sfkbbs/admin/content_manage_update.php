<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '编辑帖子';
$link = connect();
if(!ManageIslogin($link)){
    skip('login.php', '未登录');
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('content_manage.php', '编辑的帖子Id不合法');
}
$query = "select * from sfk_content where id={$_GET['id']}";
$data_edit = mysqli_fetch_assoc(execute($link, $query));
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip("content_manage.php", '编辑的帖子Id不存在',2);
}else{
        if (isset($_POST['submit'])) {
            Isempty($_POST['title'],$_SERVER["REQUEST_URI"],'板块标题不能为空');//验证板块名称
            Isempty($_POST['content'],$_SERVER["REQUEST_URI"],'板块内容不能为空');//验证板块序号
            if(mb_strlen(DelHtml($_POST['content'])) > 66){
                skip($_SERVER["REQUEST_URI"],'版块标题过长');
            };
            $_POST = escape($link, DelHtml($_POST));
            $query = "update  sfk_content set title='{$_POST['title']}',content='{$_POST['content']}' where id={$_GET['id']}";
            execute($link, $query);
            if (mysqli_affected_rows($link) == 1) {
                skip('content_manage.php','修改成功',2);
            } else {
                skip($_SERVER["REQUEST_URI"],'修改失败',2);
            }
        }
}
?>
<?php include 'inc/header.inc.php'?>    
    <div id="main">
        <div class="title"><?php echo $template['title']."------".$data['title']?></div>
        <form method="post">
        <table class="au">
        <tr>
            <td>版块标题</td>
            <td><input name="title" value="<?php echo $data_edit['title']?>" type="text"/></td>
            <td>不为空,66个字符</td>
        </tr>
        <tr>
            <td>版块内容</td>
            <td><input name="content" value="<?php echo $data_edit['content']?>" type="text"/></td>
            <td>不为空</td>
        </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="修改"/>
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>