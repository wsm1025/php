<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if(!$member_id = IsLogin($link)){
    skip('login.php', '请先登录,在回复吧',1);
};
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '回复帖子Id不合法',2);
}
if(isset($_POST['submit'])){
    Isempty($_POST['content'],$_SERVER['REQUEST_URI'],'回复内容不得为空',2);
    if(mb_strlen(DelHtml($_POST['content']))>255){
        skip($_SERVER['REQUEST_URI'],'字符数过长',2);
    }
    $_POST = escape($link, DelHtml($_POST));
    $query = "insert into sfk_reply(content_id,content,time,member_id) values({$_GET['id']},'{$_POST['content']}',now(),{$member_id})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1){
        skip("show.php?id={$_GET['id']}",'回复成功',2);
    }else{
        skip($_SERVER['REQUEST_URI'],'回复失败',2);
    }
}
$query = "select sc.id,sc.title,sm.name from sfk_content sc,sfk_member sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$res_content = execute($link, $query);
if (mysqli_num_rows($res_content) != 1) {
    skip('index.php', '该帖子不存在',2);
}
$data_content = mysqli_fetch_assoc($res_content);
?>
<?php $template['title'] ="回复帖子"?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
	<div id="position" class="auto">
		 <a href="show.php?id=<?php echo $_GET['id']?>">首页</a> &gt; 回复帖子
	</div>
	<div id="publish">
		<div>回复：由 <?php echo $data_content['name']?> 发布的 <a href="show.php?id=<?php echo $_GET['id']?>" style="color:steelblue;"><?php echo $data_content['title']?></a></div>
		<form method="post">
			<textarea name="content" class="content"></textarea>
			<input class="reply" type="submit" name="submit" value="" />
			<div style="clear:both;"></div>
		</form>
	</div>
<?php include_once 'inc/footer.inc.php' ?>