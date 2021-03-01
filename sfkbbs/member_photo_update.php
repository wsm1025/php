<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/uploadImg.inc.php';
$link = connect();
if (!$member_id = IsLogin($link)) {
	skip('login.php', '错误操作,请先登录', 2);
}
if (isset($_POST['submit'])) {
	if (!empty($_POST['photo'])) {
		skip('member_photo_update.php', '请上传合适的文件', 2);
	}
	$date = date('Y/m/d');
	$position = 'uploads/' . $date; //服务器上文件系统的路径
	$upload = upload($position, '4M', 'photo');
	if ($upload['return']) {
		$query = "update sfk_member set photo='{$upload['position']}' where id ={$member_id}";
		execute($link, $query);
		if (mysqli_affected_rows($link) == 1) {
			skip('member_photo_update.php', '头像设置成功', 2);
		} else {
			skip('member_photo_update.php', '头像设置失败,请重试', 2);
		}
	} else {
		skip('member_photo_update.php', $upload['err'], 2);
	}
}
$template['title'] = '修改头像';
?>
<?php include 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<?php
	$query = "select photo from sfk_member where id={$member_id} ";
	$res_member = execute($link, $query);
	$data_member = mysqli_fetch_assoc($res_member);
?>
<div id="main">
	<h2>更改头像</h2>
	<div>
		<h3>原头像：</h3>
		<img onclick="show('<?php echo $data_member['photo'] ? Pth.$data_member['photo'] : IMG_USER; ?>')" width="180" height="180" src="<?php echo $data_member['photo'] ?: IMG_USER; ?>" />
		<p>最佳尺寸：180*180</p>
	</div>
	<div style="margin:15px 0 0 0;">
		<form method="post" enctype="multipart/form-data">
			<input style="cursor:pointer;" width="100" type="file" name="photo" /><br /><br />
			<input class="submit" type="submit" name="submit" value="保存" />
		</form>
	</div>
</div>
<?php include_once 'inc/imgshow.inc.php'?>
<?php include 'inc/footer.inc.php' ?>