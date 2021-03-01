<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
if ($member_id =IsLogin($link)) {
	skip('index.php', '您已经登陆了，不需要在重复注册');
}
if (isset($_POST['submit'])) {
	include 'inc/check_registre_inc.php';
	$query = "insert into sfk_member(name,pw,register_time,photo,last_time) values('{$_POST['name']}',md5('{$_POST['pw']}'),now(),'',now())";
	execute($link, $query);
	if (mysqli_affected_rows($link) == 1) {
		setcookie('sfk[name]', $_POST['name']);
		setcookie('sfk[pw]', sha1(md5($_POST['pw'])));
		skip('index.php', '注册成功');
	} else {
		skip('register.php', '注册失败');
	}
}
$template['title'] = "用户注册";
?>

<?php include_once 'inc/header.inc.php' ?>

<div style="margin-top:55px;"></div>
<div id="register" class="auto">
	<h2>欢迎注册成为 wbbs 会员</h2>
	<form method="post">
		<label>用户名：<input type="text" name="name" /><span>*用户名不得为空，并且长度不得超过32个字符</span></label>
		<label>密码：<input type="password" name="pw" /><span>*密码不得少于6位</span></label>
		<label>确认密码：<input type="password" name="confirm_pw" /><span>*请输入与上面一致</span></label>
		<label>验证码：<input name="vcode" type="text" /><span>*请输入下方验证码</span></label>
		<img class="vcode" src="inc/show_code.php" />
		<div style="clear:both;"></div>
		<input class="btn" name="submit" type="submit" value="确定注册" />
	</form>
</div>
<?php include_once 'inc/footer.inc.php' ?>