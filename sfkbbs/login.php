<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
?>
<?php
if($member_id = IsLogin($link)){
	skip('index.php', '您已经登陆了，不需要再重复登录');
}

if (isset($_POST['submit'])) {
	include 'inc/check_login_inc.php';
	$_POST = escape($link, DelHtml($_POST));
	$query = "select * from sfk_member where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
	$res = mysqli_fetch_assoc(execute($link, $query));
	if (mysqli_num_rows(execute($link, $query)) == 1) {
		$query = "update sfk_member set last_time = now() where id ={$res['id']}";
		execute($link, $query);
		setcookie('sfk[name]', $_POST['name'],time()+$_POST['time']);
		setcookie('sfk[pw]', sha1(md5($_POST['pw'])),time()+$_POST['time']);
		skip('index.php', '登录成功',1);
	} else {
		skip('login.php', '用户名或者密码错误!',2);
	}
}
?>
<?php
$template['title'] = '欢迎回来';
include 'inc/header.inc.php';
?>
<div style="margin-top:55px;"></div>
<div id="register" class="auto">
	<h2>请登录</h2>
	<form method="post">
		<label>用户名：<input type="text" name="name" /><span>*请填写用户名</span></label>
		<label>密码：<input type="password" name="pw" /><span>*请填写密码</span></label>
		<label>验证码：<input name="vcode" type="text" /><span>*请输入下方验证码</span></label>
		<img class="vcode" src="inc/show_code.php" />
		<label>自动登录：
			<select style="width:236px;height:25px;" name="time">
				<option value="3600">1小时内</option>
				<option value="86400">1天内</option>
				<option value="259200">3天内</option>
				<option value="2592000">30天内</option>
			</select>
			<span>*公共电脑上请勿长期自动登录</span>
		</label>
		<div style="clear:both;"></div>
		<input class="btn" type="submit" name="submit" value="登录" />
	</form>
</div>
<?php include 'inc/footer.inc.php' ?>