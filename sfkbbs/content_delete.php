<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
    skip('login.php', '请登录后在进行操作',2);
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '删除的帖子Id不合法');
}
$query = "select member_id from sfk_content where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip("member.php?id={$member_id}", '删除的帖子Id不存在',2);
}else{
    $data_content = mysqli_fetch_assoc(execute($link, $query));
    if($data_content['member_id']==$member_id){
        $query="delete from sfk_content where id={$_GET['id']}";
        execute($link,$query);
        if(isset($_GET['return_url'])){
            $return_url = $_GET['return_url'];
        }else{
            $return_url = "member.php?id={$member_id}";
        }
        if (mysqli_affected_rows($link) == 1) {
            skip($return_url,'删除成功',2);
        } else {
            skip($return_url,'删除失败',2);
        }
    }else{
        skip("member.php?id={$member_id}", '这个帖子不属于你,你没有权限',2);
    }
}
