<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
    skip('login.php', '请登录后在进行操作',2);
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '编辑的帖子Id不合法');
}
$query = "select * from sfk_content where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip("member.php?id={$member_id}", '编辑的帖子Id不存在',2);
}else{
    $data_content = mysqli_fetch_assoc(execute($link, $query));
    if($data_content['member_id']==$member_id){
        if (isset($_POST['submit'])) {
            include 'inc/check_edit_inc.php';
            $_POST = escape($link, DelHtml($_POST));
            $query = "update  sfk_content set module_id={$_POST['module_id']},title='{$_POST['title']}',content='{$_POST['content']}' where id={$_GET['id']}";
            execute($link, $query);
            if(isset($_GET['return_url'])){
                $return_url = $_GET['return_url'];
            }else{
                $return_url = "member.php?id={$member_id}";
            }
            if (mysqli_affected_rows($link) == 1) {
                skip($return_url,'修改成功',2);
            } else {
                skip($return_url,'修改失败',2);
            }
        }
    }else{
        skip("member.php?id={$member_id}", '这个帖子不属于你,你没有权限',2);
    }
}
$template['title'] = '编辑帖子';
?>
<?php include 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; 编辑帖子
</div>
<div id="publish">
    <form method="post">
    <select name="module_id">
            <option>选择一项父板块</option>
            <?php
            if(isset($_GET['father_module_id'])&&is_numeric($_GET['father_module_id'])){
              $where = "where id={$_GET['father_module_id']} ";
            }
            $query = "select * from sfk_father_module {$where} order by sort desc";
            $res_father = execute($link, $query);
            while ($data_father = mysqli_fetch_assoc($res_father)) {
                echo "<optgroup label='{$data_father['module_name']}'>";
                $query = "select * from sfk_son_module where father_module_id={$data_father['id']} order by sort desc";
                $res_son = execute($link, $query);
                while ($data_son = mysqli_fetch_assoc($res_son)) {
                    if ($data_content['module_id'] == $data_son['id']) {
                        echo "<option selected value='{$data_son['id']}'>{$data_son['module_name']}</option>";
                    } else {
                        echo "<option value='{$data_son['id']}'>{$data_son['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ?>
        </select>
        <input class="title" placeholder="请输入标题" value="<?php echo $data_content['title'];?>" name="title" type="text" />
        <textarea name="content" class="content"><?php echo $data_content['content'];?></textarea>
        <input class="publish" type="submit" name="submit" value="修改" />
        <div style="clear:both;"></div>
    </form>
</div>
<?php include 'inc/footer.inc.php' ?>