<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
    skip('login.php', '请登录后在进行操作',2);
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '删除的回复Id不合法');
}

$query = "select content_id from sfk_reply where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip("index.php", '删除的回复Id不存在',2);
}else{
        $query="delete from sfk_reply where id={$_GET['id']}";
        execute($link,$query);
        $return_url = $_GET['return_url'];
        if (mysqli_affected_rows($link) == 1) {
            skip($return_url,'删除成功',2);
        } else {
            skip($return_url,'删除失败',2);
        }
}
