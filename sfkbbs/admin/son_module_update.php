<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '子版块修改';
$link = connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('son_module.php', 'id参数错误!');
}
$query = "select * from sfk_son_module where id={$_GET['id']}";
$result = execute($link, $query);
if (!mysqli_num_rows($result)) {
    skip('father_module.php', '该子板块不存在!');
}
if(isset($_POST['submit'])){
    Isnumber($_POST['father_module_id'],'son_module.php','父板块必选');
    //验证是否被修改
    $query = "select * from sfk_father_module where id={$_POST['father_module_id']}";
    $res = execute($link,$query);
    if(!mysqli_num_rows($res)){
        skip('son_module.php','所选的父板块不存在');
    }
    Isempty($_POST['module_name'],'son_module.php','板块名称不能为空');//验证板块名称
    Isempty($_POST['sort'],'son_module.php','板块序号不能为空');//验证板块序号
    Isempty($_POST['info'],'son_module.php','详细信息不能为空');//验证板块序号
    Isnumber($_POST['sort'],'son_module.php','板块序号只能为数字');//验证板块序号
    // Isnumber($_POST['member_id'],'son_module.php','板主必选');
    if(mb_strlen(DelHtml($_POST['module_name'])) > 66){
        skip('son_module.php','版块名称过长');
    };
    if(mb_strlen(DelHtml($_POST['info'])) > 255){
        skip('son_module.php','简介信息过长(最多255)');
    };
    // $query = "select * from sfk_son_module where module_name='{$_POST['module_name']}' and id!='{$_POST['id']}' and info!='{$_POST['info']}' and member_id!='{$_POST['member_id']}' and sort!='{$_POST['sort']}'";
    $query = "select * from sfk_son_module where module_name='{$_POST['module_name']}' and id!='{$_POST['id']}'";
    $res = execute($link,$query);
    if(mysqli_num_rows($res)){
        skip('son_module.php','子版块已存在');
    }

    $query = "update sfk_son_module set father_module_id='{$_POST['father_module_id']}',module_name='{$_POST['module_name']}',info='{$_POST['info']}',member_id='{$_POST['member_id']}',sort='{$_POST['sort']}' where id='{$_GET['id']}'";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('son_module.php','数据修改成功');
    }else{
        skip('son_module.php','数据修改失败');
    }
}
$data = mysqli_fetch_assoc($result);
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
    <div class="title"><?php echo $temlpate['title'] . $data['module_name'] ?></div>
    <form method="post">
        <table class="au">
            <tr>
                <td>所属父板块</td>
                <td>
                    <select name="father_module_id">
                        <option value="0">===请选择一个父板块===</option>
                        <?php
                        $query = "select * from sfk_father_module";
                        $res = execute($link, $query);
                        while ($data_father = mysqli_fetch_assoc($res)) {
                            if ($data_father['id'] == $data['father_module_id']) {
                                echo "<option selected value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            } else {
                                echo "<option value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>必须选择父板块</td>
            </tr>
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" value="<?php echo $data['module_name'] ?>" /></td>
                <td>不为空,66个字符</td>
            </tr>
            <tr>
                <td>版块简介</td>
                <td><textarea name="info" cols="30" rows="10"><?php echo $data['info'] ?></textarea></td>
                <td>此为简介内容</td>
            </tr>
            <tr>
                <td>版主</td>
                <td>
                    <select name="member_id">
                        <option value="1">===请选择一个版主===</option>
                    </select>
                </td>
                <td>必须选择版主</td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" value="<?php echo $data['sort'] ?>" type="text" /></td>
                <td>支持数字</td>
            </tr>
        </table>
        <input type="submit" class="btn" name="submit" value="确定" />
    </form>
</div>
<?php include_once 'inc/footer.inc.php' ?>