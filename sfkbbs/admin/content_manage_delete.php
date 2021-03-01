<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
if(!ManageIslogin($link)){
    skip('login.php', '未登录');
 }
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '删除的帖子Id不合法');
}
$query = "select member_id from sfk_content where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip("content_manage.php", '删除的帖子Id不存在',2);
}else{
        $query="delete from sfk_content where id={$_GET['id']}";
        execute($link,$query);
        $return_url = "content_manage.php";
        if (mysqli_affected_rows($link) == 1) {
            skip($return_url,'删除成功',2);
        } else {
            skip($return_url,'删除失败',2);
        }
}
