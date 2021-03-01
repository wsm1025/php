<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '添加子板块';
$link=connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if(isset($_POST['submit'])){
    Isnumber($_POST['father_module_id'],'son_module_add.php','父板块必选');
    //验证是否被修改
    $query = "select * from sfk_father_module where id={$_POST['father_module_id']}";
    $res = execute($link,$query);
    if(!mysqli_num_rows($res)){
        skip('son_module_add.php','所选的父板块不存在');
    }
    Isempty($_POST['module_name'],'son_module_add.php','板块名称不能为空');//验证板块名称
    Isempty($_POST['sort'],'son_module_add.php','板块序号不能为空');//验证板块序号
    Isempty($_POST['info'],'son_module_add.php','详细信息不能为空');//验证板块序号
    Isnumber($_POST['sort'],'son_module_add.php','板块序号只能为数字');//验证板块序号
    // Isnumber($_POST['member_id'],'son_module_add.php','板主必选');
    if(mb_strlen(DelHtml($_POST['module_name'])) > 66){
        skip('son_module_add.php','版块名称过长');
    };
    if(mb_strlen(DelHtml($_POST['info'])) > 255){
        skip('son_module_add.php','简介信息过长(最多255)');
    };
    $query = "select * from sfk_son_module where module_name='{$_POST['module_name']}'";
    $res = execute($link,$query);
    if(mysqli_num_rows($res)){
        skip('son_module_add.php','子版块已存在');
    }


    $query = "insert into sfk_son_module(father_module_id,module_name,info,member_id,sort) values({$_POST['father_module_id']},'{$_POST['module_name']}','{$_POST['info']}',{$_POST['member_id']},{$_POST['sort']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip('son_module.php','数据添加成功');
    }else{
        skip('son_module_add.php','数据添加失败');
    }
}
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
        <div class="title"><?php echo $temlpate['title']?></div>
        <form method="post"> 
        <table class="au">
        <tr>
            <td>所属父板块</td>
            <td>
                <select name="father_module_id">
                    <option value="0">===请选择一个父板块===</option>
                    <?php
                        $query = "select * from sfk_father_module";
                        $res = execute($link,$query);
                        while($data=mysqli_fetch_assoc($res)){
                            echo"<option value='{$data['id']}'>{$data['module_name']}</option>";
                        }
                    ?>
                </select>
            </td>
            <td>必须选择父板块</td>
        </tr>
        <tr>
            <td>版块名称</td>
            <td><input name="module_name" type="text"/></td>
            <td>不为空,66个字符</td>
        </tr>
        <tr>
            <td>版块简介</td>
            <td><textarea name="info" cols="30" rows="10"></textarea></td>
            <td>此为简介内容</td>
        </tr>
        <tr>
            <td>版主</td>
            <td>
                <select name="member_id">
                    <option value="0">===请选择一个版主===</option>
                </select>
            </td>
            <td>必须选择版主</td>
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