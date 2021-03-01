<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
//数据验证
if(!ManageIslogin($link)){
   skip('login.php', '未登录');
}
if(isset($_POST['submit'])){
     Isempty($_POST['module_name'],'father_module_add.php','板块名称不能为空');//验证板块名称
     Isempty($_POST['sort'],'father_module_add.php','板块序号不能为空');//验证板块序号
     Isnumber($_POST['sort'],'father_module_add.php','板块序号只能为数字');//验证板块序号
     if(mb_strlen(DelHtml($_POST['module_name'])) > 66){
         skip('father_module_add.php','版块名称过长');
     };
     $_POST = escape($link,DelHtml($_POST));
    //检测名称是否重复
    $query = "select * from sfk_father_module where module_name='{$_POST['module_name']}'";
    if(mysqli_num_rows(execute($link,$query))){
        skip('father_module_add.php','版块名称重复');
    }
    $query = "insert into sfk_father_module(module_name,sort) values('{$_POST['module_name']}',{$_POST['sort']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('father_module.php','数据添加成功');
    }else{
        skip('father_module_add.php','数据添加失败');
    }
}
$template['title'] = '添加父板块';
?>
<?php include_once 'inc/header.inc.php' ?>
    <div id="main">
        <div class="title">添加父板块</div>
        <form method="post">
        <table class="au">
        <tr>
            <td>版块名称</td>
            <td><input name="module_name" type="text"/></td>
            <td>不为空,26个字符</td>
        </tr>
        <tr>
            <td>排序</td>
            <td><input name="sort" value="1" type="text"/></td>
            <td>支持数字</td>
        </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="确定"/>
        </form>
    </div>
<?php include_once 'inc/footer.inc.php' ?>
