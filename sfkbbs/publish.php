<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
    skip('login.php', '请登录后在发帖',2);
}
?>
<?php
$template['title'] = '发帖';
if (isset($_POST['submit'])) {
    include 'inc/check_publish_inc.php';
    $_POST = escape($link, DelHtml($_POST));
    $query = "insert into sfk_content(module_id,title,content,time,member_id) values({$_POST['module_id']},'{$_POST['title']}','{$_POST['content']}',now(),${member_id})";
    execute($link, $query);
    if (mysqli_affected_rows($link) == 1) {
        skip('index.php', '发布成功');
    } else {
        skip('publish.php', '发布失败');
    }
}
?>
<?php include 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; 发布帖子
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
                    if (isset($_GET['son_id']) && $_GET['son_id'] == $data_son['id']) {
                        echo "<option selected value='{$data_son['id']}'>{$data_son['module_name']}</option>";
                    } else {
                        echo "<option value='{$data_son['id']}'>{$data_son['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ?>
        </select>
        <input class="title" placeholder="请输入标题" name="title" type="text" />
        <textarea name="content" class="content"></textarea>
        <input class="publish" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
</div>
<?php include 'inc/footer.inc.php' ?>