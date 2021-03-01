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
	<title><?php echo $template['title'] ?></title>
	<meta name="keywords" content="<?php echo $data['keyword']?>" />
	<meta name="description" content="<?php echo $data['description']?>" />
	<link rel="icon"  href="../sfkbbs/style/favicon.ico">
	<link rel="stylesheet" type="text/css" href="style/public.css" />
	<?php if(basename($_SERVER['SCRIPT_NAME'])=='register.php'|| basename($_SERVER['SCRIPT_NAME'])=='login.php'){echo "<link rel='stylesheet' type='text/css' href='style/register.css' />";}?>
	<?php if(basename($_SERVER['SCRIPT_NAME'])=='publish.php'|| basename($_SERVER['SCRIPT_NAME'])=='reply.php'|| basename($_SERVER['SCRIPT_NAME'])=='qoute.php'||basename($_SERVER['SCRIPT_NAME'])=='content_edit.php'){echo "<link rel='stylesheet' type='text/css' href='style/publish.css' />";}?>
	<?php if(basename($_SERVER['SCRIPT_NAME'])=='index.php'){echo "<link rel='stylesheet' type='text/css' href='style/index.css' />";}?>
	<?php if(basename($_SERVER['SCRIPT_NAME'])=='list_father.php'|| basename($_SERVER['SCRIPT_NAME'])=='list_son.php'||basename($_SERVER['SCRIPT_NAME'])=='member.php'|| basename($_SERVER['SCRIPT_NAME'])=='list_son.php'||basename($_SERVER['SCRIPT_NAME'])=='search.php'){echo "<link rel='stylesheet' type='text/css' href='style/list.css' />";}?>
	<?php if(basename($_SERVER['SCRIPT_NAME'])=='show.php'){echo "<link rel='stylesheet' type='text/css' href='style/list.css' /><link rel='stylesheet' type='text/css' href='style/show.css' />";}?>		
</head>

<?php if(basename($_SERVER['SCRIPT_NAME'])=='member.php'){
	$css=<<<A
		<style type="text/css">
			#main #right .member_big {
			margin:20px auto 0 auto;
			width:180px;
		}
		#main #right .member_big dl dd {
			line-height:150%;
		}
		#main #right .member_big dl dd a {
			color:#333;
		}
		#main #right .member_big dl dd.name {
			font-size: 22px;
   			 font-weight: 400;
   			 line-height:140%;
    		padding:5px 0 10px 0px;
		}
		</style>
A;	
echo $css;
}?>
<?php if(basename($_SERVER['SCRIPT_NAME'])=='member_photo_update.php'||basename($_SERVER['SCRIPT_NAME'])=='password_update.php'){
	$css=<<<A
		<style type="text/css">
			body {
				font-size:12px;
				font-family:微软雅黑;
				}
			h2 {
				padding:0 0 10px 0;
				font-size:16px;
				border-bottom: 1px solid #e3e3e3;
				color:#444;
				}
			.submit {
				background-color: #3b7dc3;
				color:#fff;
				padding:5px 22px;
				border-radius:2px;
				border:0px;
				cursor:pointer;
				font-size:14px;
				}
			#main {
				width:80%;
				margin:0 auto;
				}
			.password{
					width:200px;
					height:30px;
					border:none;
					out-line:none;
					border-radius:10px;
				}
			</style>
A;	
echo $css;
}?>
<body>
	<div class="header_wrap">
		<div id="header" class="auto">
			<div class="logo"><?php echo $data['title']?></div>
			<div class="nav">
				<a class="hover" href="index.php">首页</a>
			</div>
			<div class="serarch">
				<form action="search.php" method="get" target="_blank">
					<input class="keyword" type="text" value="<?php if(isset($_GET['keyword'])){echo $_GET['keyword'];}?>" name="keyword" placeholder="搜索其实很简单" />
				    <input class="submit" type="submit" value="" />
				</form>
			</div>
			<div class="login">
				<?php
				if (isset($member_id)&&$member_id) {
					$html = "
					您好！<a href='member.php?id={$member_id}' style='font-size:20px;color:red;'>{$_COOKIE['sfk']['name']}</a><span style='color:#fff;margin: 0 10px;'>|</span><a href='logout.php'>登出</a>";
				} else {
					$html="";
					if(basename($_SERVER['SCRIPT_NAME'])=='register.php'){
						$html="<a href='login.php'>登录</a>";
					}elseif(basename($_SERVER['SCRIPT_NAME'])=='login.php'){
						$html="<a href='register.php'>注册</a>";
					}else{
					$html ="
					<a href='login.php'>登录</a>&nbsp
					<a href='register.php'>注册</a>";
				}
			}
				echo $html;
				?>
			</div>
		</div>
	</div>