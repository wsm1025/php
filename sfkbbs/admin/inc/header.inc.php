<?php
if(!defined('IN_WSM')){
    exit('非法请求');
}
$query ="select * from php_web where id = 1";
$data= mysqli_fetch_assoc(execute($link,$query));
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title><?php echo $template['title']?></title>
<meta name="keywords" content="<?php echo $data['keyword']?>" />
<meta name="description" content="<?php echo $data['description']?>" />
<link rel="icon"  href="../style/favicon.ico">
<link rel="stylesheet" type="text/css" href="style/public.css" />
</head>
<body>
<div id="top">
	<div class="logo">
		管理中心
	</div>
	<ul class="nav">
		<!--  -->
	</ul>
	<div class="login_info">
		<a href="../index.php" style="color:#fff;" target="_blank">网站首页</a>&nbsp;|&nbsp;
		管理员： <?php echo $_SESSION['manage']['name']?> <a href="logout_admin.php">[注销]</a>
	</div>
</div>
<div id="sidebar">
	<ul>
		<li>
			<div class="small_title">系统</div>
			<ul class="child">
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='index.php'){echo 'class="current"';}?> href="index.php">系统信息</a></li>
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='manage.php'){echo 'class="current"';}?> href="manage.php">管理员列表</a></li>
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='manage_add.php'){echo 'class="current"';}?> href="manage_add.php">添加管理员</a></li>
				<li><a href="web_set.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='web_set.php'){echo 'class="current"';}?>>站点设置</a></li>
			</ul>
		</li>
		<li><!--  class="current" -->
			<div class="small_title">内容管理</div>
			<ul class="child">
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='father_module.php'){echo 'class="current"';}?> href="father_module.php">父板块列表</a></li>
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='father_module_add.php'){echo 'class="current"';}?>href="father_module_add.php">添加父板块</a></li>
				<?php
				if(basename($_SERVER['SCRIPT_NAME'])=='father_module_update.php'){
					echo '<li><a class="current">编辑父板块</a></li>';
				}
				?>
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='son_module.php'){echo 'class="current"';}?> href="son_module.php">子板块列表</a></li>
				<li><a <?php if(basename($_SERVER['SCRIPT_NAME'])=='son_module_add.php'){echo 'class="current"';}?> href="son_module_add.php">添加子板块</a></li>
				<?php
				if(basename($_SERVER['SCRIPT_NAME'])=='son_module_update.php'){
					echo '<li><a class="current">编辑子板块</a></li>';
				}
				?>
				<li><a href="content_manage.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='content_manage.php'){echo 'class="current"';}?> >帖子管理</a></li>
				<?php
				if(basename($_SERVER['SCRIPT_NAME'])=='content_manage_update.php'){
					echo '<li><a class="current">编辑帖子</a></li>';
				}
				?>
			</ul>
		</li>
		<li>
			<div class="small_title">用户管理</div>
			<ul class="child">
				<li><a href="user_list.php" <?php if(basename($_SERVER['SCRIPT_NAME'])=='user_list.php'){echo 'class="current"';}?>>用户列表</a></li>
			</ul>
		</li>
	</ul>
</div>