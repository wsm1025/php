<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
if(ManageIslogin($link)){
	skip('index.php', '您已经登陆了，不需要再重复登录');
}
if (isset($_POST['submit'])) {
	Isempty($_POST['name'], 'login.php', '用户名不得为空', 1);
	if (mb_strlen(DelHtml($_POST['name'])) > 32) {
		skip('login.php', '字符数过长', 1);
	}
	Isempty($_POST['pw'], 'login.php', '密码不得为空', 1);
	if (mb_strlen(DelHtml($_POST['pw'])) < 6) {
		skip('login.php', '密码最少6位', 1);
	}
	Isempty($_POST['vcode'], 'login.php', '验证码不得为空', 1);
	// if(mb_strtolower($_POST['vcode']) != $_SESSION['vcode']){
	// 	skip('login.php','验证码错误!',1);
	// }
	$query = "select * from sfk_manage where name='{$_POST['name']}' and pw=md5('{$_POST['pw']}')";
	if (mysqli_num_rows($res = execute($link, $query)) == 1) {
		$data = mysqli_fetch_assoc($res);
		$_SESSION['manage']['name'] = $data['name'];
		$_SESSION['manage']['pw'] = sha1($data['pw']);
		$_SESSION['manage']['id'] = $data['id'];
		$_SESSION['manage']['level'] = $data['level'];
		skip('index.php', '登录成功',1);
	} else {
		skip('login.php', '用户名或者密码错误!',2);
	}
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<meta charset="utf-8" />
	<title>管理员登录</title>
	<meta name="keywords" content="后台管理" />
	<meta name="description" content="" />
	<style type="text/css">
		html,body {
			width: 100%;
			height: 100%;
			background: #f7f7f7;
			font-size: 14px;
		}
		#main {
			width: 360px;
			height: 320px;
			background: #fff;
			border: 1px solid #ddd;
			position: absolute;
			top: 50%;
			left: 50%;
			margin-left: -180px;
			margin-top: -160px;
		}

		#main .title {
			height: 48px;
			line-height: 48px;
			color: #333;
			font-size: 16px;
			font-weight: bold;
			text-indent: 30px;
			border-bottom: 1px dashed #eee;
		}

		#main form {
			width: 300px;
			margin: 20px 0 0 40px;
		}

		#main form label {
			margin: 10px 0 0 0;
			display: block;
		}

		#main form label input.text {
			width: 200px;
			height: 25px;
		}

		#main form label .vcode {
			display: block;
			margin: 0 0 0 56px;
		}

		#main form label input.submit {
			width: 200px;
			display: block;
			height: 35px;
			cursor: pointer;
			margin: 0 0 0 56px;
		}
	</style>
</head>

<body>
	<div id="main">
		<div class="title">管理员登录</div>
		<form method="post">
			<label>用户名：<input class="text" type="text" name="name" /></label>
			<label>密　码：<input class="text" type="password" name="pw" /></label>
			<label>验证码：<input class="text" type="text" name="vcode" /></label>
			<label><img class="vcode" src="../../sfkbbs/inc/show_code.php" /></label>
			<label><input class="submit" type="submit" name="submit" value="登录" /></label>
		</form>
	</div>
</body>

</html>