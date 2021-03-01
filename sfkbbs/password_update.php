<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/uploadImg.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
	skip('login.php', '错误操作,请先登录', 2);
}
$template['title'] = '修改密码';
?>
<?php 
if(isset($_POST['submit'])){
	Isempty($_POST['agopassword'],'password_update.php','原密码不得为空',1);
	Isempty($_POST['nowpassword'],'password_update.php','现密码不得为空',1);
	if(mb_strlen(DelHtml($_POST['nowpassword']))>32){
    	skip('password_update.php','字符数过长',1);
	}
	if(mb_strlen(DelHtml($_POST['nowpassword']))<6){
    	skip('password_update.php','新密码字符数过短,至少6位',1);
	}
	$query = "select * from sfk_member where name='{$_COOKIE['sfk']['name']}' and pw=md5('{$_POST['agopassword']}')";
	if (mysqli_num_rows(execute($link,$query)) == 0) {
		skip('password_update.php', '原密码错误',2);
	}else{
		    $query = "update  sfk_member set pw=md5('{$_POST['nowpassword']}') where id={$member_id}";
            execute($link, $query);
            if (mysqli_affected_rows($link) == 1) {
                skip("login.php", '密码修改成功',2);
				setcookie('sfk[name]',"",time()-1);
				setcookie('sfk[pw]',"",time()-1);
            } else {
                skip("password_update.php", '密码修改失败',2);
            }
	}
}
?>
<?php include 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="main">
	<h2>修改密码</h2>
	<div>
		<h3>原密码：</h3>
	</div>
	<div style="margin:15px 0 0 0;">
		<form method="post" enctype="multipart/form-data">
			<input class="password" style="cursor:pointer;" width="100" type="password" name="agopassword" /><br /><br />
			<h3>修改的密码：</h3>
			<input class="password" style="cursor:pointer;" width="100" type="password" name="nowpassword" /><br /><br />
			<input class="submit" type="submit" name="submit" value="保存" />
		</form>
	</div>
</div>
<?php include 'inc/footer.inc.php' ?>