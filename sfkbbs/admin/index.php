<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
$template['title'] = '后台首页';
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
$query = "select * from sfk_manage where id= {$_SESSION['manage']['id']}";
$res = execute($link,$query);
$data_manage = mysqli_fetch_assoc($res);
//查询父板块
$query = "select count(*) from sfk_father_module ";
$father_module_number = num($link,$query);
//查询子版块
$query = "select count(*) from sfk_son_module ";
$son_module_number = num($link,$query);
//查询会员数
$query = "select count(*) from sfk_member ";
$memeber_number = num($link,$query);
//查询管理员数量
$query = "select count(*) from sfk_manage ";
$manage_number = num($link,$query);
//查询帖子数量
$query = "select count(*) from sfk_content ";
$content_number = num($link,$query);
//查询回复数量
$query = "select count(*) from sfk_reply ";
$reply_number = num($link,$query);
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
	<div class="title">系统信息</div>
	<div class="explain">
		<ul>
			<li>|- 您好，<?php echo $_SESSION['manage']['name']?></li>
			<li>|- 所属角色：<?php echo !$_SESSION['manage']['level']?'超级管理员':'普通管理员'; ?> </li>
			<li>|- 创建时间：<?php echo $data_manage['createtime']?></li>
		</ul>
	</div>
	<div class="explain">
		<ul>
			<li>|- 父版块(<?php echo $father_module_number;?>)&nbsp;&nbsp;
			子版块(<?php echo $son_module_number;?>) &nbsp;&nbsp;
			帖子(<?php echo $content_number;?>) &nbsp;&nbsp;
			回复(<?php echo $reply_number;?>) &nbsp;&nbsp;
			会员(<?php echo $memeber_number;?>)&nbsp;&nbsp;
			 管理员(<?php echo $manage_number;?>)</li>
		</ul>
	</div>
	<div class="explain">
		<ul>
			<li>|- 服务器操作系统：<?php echo PHP_OS;?> </li>
			<li>|- 服务器软件：<?php echo $_SERVER['SERVER_SOFTWARE'];?> </li>
			<li>|- MySQL 版本：<?php echo mysqli_get_server_info($link);?> </li>
			<li>|- 最大上传文件：<?php echo ini_get('upload_max_filesize');?></li>
			<li>|- 内存限制：<?php echo ini_get('memory_limit');?></li>
			<li>|- <a target="_blank" href="php_info.php">PHP 配置信息</a></li>
		</ul>
	</div>
	
	<div class="explain">
		<ul>
			<li>|- 程序安装位置(绝对路径)：<?php echo A_PATH;?></li>
			<li>|- 程序在web根目录下的位置(首页的url地址)：<?php echo Pth;?></li>
			<li>|- 程序版本：bbs V1.0 <a  href="javascript:;">[查看最新版本]</a></li>
			<li>|- 程序作者：汪太宗 </li>
			<li>|- 网站：<a target="_blank" href="http://taizonga.top">taizonga.top</a></li>
		</ul>
	</div>
</div>


<?php include_once 'inc/footer.inc.php' ?>
