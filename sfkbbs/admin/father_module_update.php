<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '修改父板块';
$link = connect();
if(!ManageIslogin($link)){
    skip('login.php', '未登录');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    skip('father_module.php','id参数错误!');
}
$query = "select * from sfk_father_module where id={$_GET['id']}";
$result = execute($link,$query);
if(!mysqli_num_rows($result)){
    skip('father_module.php','板块不存在!');
}
if(isset($_POST['submit'])){
     //数据验证
     Isempty($_POST['module_name'],'father_module.php','板块名称不能为空');//验证板块名称
     Isempty($_POST['sort'],'father_module.php','板块序号不能为空');//验证板块序号
     Isnumber($_POST['sort'],'father_module.php','板块序号只能为数字');//验证板块序号
     if(mb_strlen(DelHtml($_POST['module_name'])) > 66){
         skip('father_module.php','版块名称过长');
     };
     $_POST = escape($link,DelHtml($_POST));
    //检测名称是否重复
    $query = "select * from sfk_father_module where module_name='{$_POST['module_name']}' and id!='{$_POST['id']}'";
    if(mysqli_num_rows(execute($link,$query))){
        skip('father_module.php','版块名称重复');
    }
    $query = "update  sfk_father_module set module_name='{$_POST['module_name']}',sort={$_POST['sort']} where id={$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('father_module.php','数据修改成功');
    }else{
        skip('father_module.php','数据修改失败');
    }
}
$data_edit = mysqli_fetch_assoc($result);
?>
<?php include 'inc/header.inc.php'?>    
    <div id="main">
        <div class="title"><?php echo $template['title'].'---'.$data['module_name']?></div>
        <form method="post">
        <table class="au">
        <tr>
            <td>版块名称</td>
            <td><input name="module_name" value="<?php echo $data_edit['module_name']?>" type="text"/></td>
            <td>不为空,26个字符</td>
        </tr>
        <tr>
            <td>排序</td>
            <td><input name="sort" type="text" value="<?php echo $data_edit['sort']?>"/></td>
            <td>支持数字</td>
        </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="修改"/>
        </form>
    </div>
<?php include 'inc/footer.inc.php'?>