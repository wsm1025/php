<?php
if(file_exists('inc/install.lock')){
	header("Location:index.php");
}
header('content-type:text/html;charset=utf-8');
if(version_compare(PHP_VERSION,'5.4.0')<0){
    //检测版本
    exit('您的php版本为'.PHP_VERSION.'此版本最低要求5.4.0');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>欢迎使用 本引导安装程序</title>
    <meta name="keywords" content="欢迎使用本引导安装程序" />
    <meta name="description" content="欢迎使用本引导安装程序" />
    <script src="message.js"></script>
    <style type="text/css">
        body {
            background: #f7f7f7;
            font-size: 14px;
            background: url("../sfkbbs/style/bg.jpg");
        }

        #main {
            width: 560px;
            height: 490px;
            background: #fff;
            border: 1px solid #ddd;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -280px;
            margin-top: -280px;
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
            width: 400px;
            margin: 20px 0 0 10px;
        }

        #main form label {
            margin: 10px 0 0 0;
            display: block;
            text-align: right;
        }

        #main form label input.text {
            width: 200px;
            height: 25px;
        }

        #main form label input.submit {
            width: 204px;
            display: block;
            height: 35px;
            cursor: pointer;
            float: right;
        }
    </style>
</head>

<body>
    <div id="main">
        <div class="title">欢迎使用 BBS_W 本引导安装程序</div>
        <form method="post">
            <label>数据库地址：<input class="text" type="text" name="db_host" value="localhost" /></label>
            <label>端口：<input class="text" type="text" name="db_port" value="3306" /></label>
            <label>数据库用户名：<input class="text" type="text" name="db_user" value="" /></label>
            <label>数据库密码：<input class="text" type="text" name="db_pw" value="" /></label>
            <label>数据库名称：<input class="text" type="text" name="db_database" value="" /></label>
            <br /><br />
            <label>后台管理员名称：<input class="text" type="text" name="manage_name" readonly="readonly" value="admin" /></label>
            <label>密码：<input class="text" type="password" name="manage_pw" value="" /></label>
            <label>密码确认：<input class="text" type="password" name="manage_pw_confirm" value="" /></label>
            <label><input class="submit" type="submit" name="submit" value="确定安装" /></label>
        </form>
    </div>
<?php
if(isset($_POST['submit'])){
    $arr = [];
    empty($_POST['db_host'])?array_push($arr,false):'';
    empty($_POST['db_port'])?array_push($arr,false):'';
    empty($_POST['db_user'])?array_push($arr,false):'';
    empty($_POST['db_pw'])?array_push($arr,false):'';
    empty($_POST['db_database'])?array_push($arr,false):'';
    empty($_POST['manage_name'])?array_push($arr,false):'';
    empty($_POST['manage_pw'])?array_push($arr,false):'';
    empty($_POST['manage_pw_confirm'])?array_push($arr,false):'';
    if($_POST['manage_pw']!=$_POST['manage_pw_confirm']){
        echo "<script class='tips'>cocoMessage.error('两次密码不一致');</script>";
    }
    if(mb_strlen($_POST['manage_pw'])<6){
        echo "<script class='tips'>cocoMessage.error('管理员密码不得少于6位');</script>";
    }
    $res = !in_array(false,$arr);
    if(!$res){
        echo "<script class='tips'>cocoMessage.error('必填信息未填写');</script>";
    }
    $_POST['manage_name'] = 'admin';
    @$link = mysqli_connect($_POST['db_host'],$_POST['db_user'],$_POST['db_pw'],'',$_POST['port']);
    if(mysqli_connect_errno()){
        echo "<script class='tips'>cocoMessage.error('数据库连接失败');</script>";
        exit();
    }
	mysqli_set_charset($link,'utf8');//设置编码
    if(!mysqli_select_db($link,$_POST['db_database'])){
        $query="CREATE DATABASE IF NOT EXISTS `{$_POST['db_database']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        mysqli_query($link,$query);
        if(mysqli_errno($link)){
            exit('数据库创建失败，请检查数据库账户权限！<a href="install.php">点击返回</a>');
        }
        mysqli_select_db($link,$_POST['db_database']);
    }
    $query=array();
    $query['php_web']="
    CREATE TABLE `php_web` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) DEFAULT NULL,
        `keyword` varchar(255) DEFAULT NULL,
        `description` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	";
	$query['sfk_content']="
    CREATE TABLE `sfk_content` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `module_id` int(11) DEFAULT NULL,
        `title` varchar(66) DEFAULT NULL,
        `content` text,
        `time` datetime DEFAULT NULL,
        `member_id` int(11) DEFAULT NULL,
        `times` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;	
	";
	$query['sfk_father_module']="
    CREATE TABLE `sfk_father_module` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `module_name` varchar(66) NOT NULL,
        `sort` int(11) DEFAULT '0',
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='父版块信息表';
	";
	$query['sfk_manage']="
    CREATE TABLE `sfk_manage` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) DEFAULT NULL,
        `pw` varchar(255) DEFAULT NULL,
        `createtime` datetime DEFAULT NULL,
        `level` int(11) DEFAULT '1' COMMENT '1为普通管理员',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;		
	";
	$query['sfk_member']="
    CREATE TABLE `sfk_member` (
        `id` int(16) NOT NULL AUTO_INCREMENT,
        `name` varchar(32) NOT NULL,
        `pw` varchar(32) NOT NULL,
        `photo` varchar(255) DEFAULT NULL,
        `register_time` datetime NOT NULL,
        `last_time` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	";
	$query['sfk_reply']="
    CREATE TABLE `sfk_reply` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `content_id` int(11) DEFAULT NULL,
        `quote_id` int(11) DEFAULT '0' COMMENT '回复的帖子id',
        `content` text,
        `time` datetime DEFAULT NULL,
        `member_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	";
	$query['sfk_son_module']="
    CREATE TABLE `sfk_son_module` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `father_module_id` int(11) DEFAULT NULL,
        `module_name` varchar(66) DEFAULT NULL,
        `info` varchar(255) DEFAULT NULL,
        `member_id` int(11) DEFAULT '0' COMMENT 'hui',
        `sort` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	";
    foreach ($query as $key=>$val){
		mysqli_query($link,$val);
		if(mysqli_errno($link)){
			echo "数据表 {$key} 创建失败，请检查数据库账户是否有创建表的权限！<a href='install.php'>点击返回</a>";
			exit();
		}
	}
    $query_info_s="select * from php_web where id=1";
	$result=mysqli_query($link, $query_info_s);
	if(mysqli_num_rows($result)!=1){
		$query_info_i="INSERT INTO `php_web` (`id`, `title`, `keyword`, `description`) VALUES(1, 'BBS_P_W', '基于php的小demo,创意来源于私房库BBS', 'demo,php,学习,前后端不分离,sql,小白的进阶');";
		mysqli_query($link,$query_info_i);
		if(mysqli_errno($link)){
			exit("数据库sfk_info写入数据失败请检查相应权限!<a href='install.php'>点击返回</a>");
		}
	}
    $query_manage_s="select * from sfk_manage where name='admin'";
	$result=mysqli_query($link, $query_manage_s);
	if(mysqli_num_rows($result)!=1){
		$query_manage_i="INSERT INTO `sfk_manage` (`name`, `pw`, `createtime`, `level`) VALUES('admin',md5('{$_POST['manage_pw']}'),now(), 0)";
		mysqli_query($link,$query_manage_i);
		if(mysqli_errno($link)){
			exit("管理员创建失败，请检查数据表sfk_manage是否具有写权限!<a href='install.php'>点击返回</a>");
		}
	}
    $filename='inc/config.inc.php';
	$str_file=file_get_contents($filename);
	$pattern="/'DB_HOST',.*?\)/";
	if(preg_match($pattern,$str_file)){
		$_POST['db_host']=addslashes($_POST['db_host']);
		$str_file=preg_replace($pattern,"'DB_HOST','{$_POST['db_host']}')", $str_file);
	}
	$pattern="/'DB_USER',.*?\)/";
	if(preg_match($pattern,$str_file)){
		$_POST['db_user']=addslashes($_POST['db_user']);
		$str_file=preg_replace($pattern,"'DB_USER','{$_POST['db_user']}')", $str_file);
	}
	$pattern="/'DB_PASSWORD',.*?\)/";
	if(preg_match($pattern,$str_file)){
		$_POST['db_pw']=addslashes($_POST['db_pw']);
		$str_file=preg_replace($pattern,"'DB_PASSWORD','{$_POST['db_pw']}')", $str_file);
	}
	$pattern="/'DB_DATABASE',.*?\)/";
	if(preg_match($pattern,$str_file)){
		$_POST['db_database']=addslashes($_POST['db_database']);
		$str_file=preg_replace($pattern,"'DB_DATABASE','{$_POST['db_database']}')", $str_file);
	}
	$pattern="/\('DB_PORT',.*?\)/";
	if(preg_match($pattern,$str_file)){
		$_POST['db_port']=addslashes($_POST['db_port']);
		$str_file=preg_replace($pattern,"('DB_PORT',{$_POST['db_port']})", $str_file);
	}
    if(!file_put_contents($filename, $str_file)){
		exit("配置文件写入失败，请检查config.inc.php文件的权限!<a href='install.php'>点击返回</a>");
	}
    if(!file_put_contents('inc/install.lock',':))')){
		exit('文件inc/install.lock创建失败，但是您的系统其实已经安装了，您可以手动建立inc/install.lock文件!');
	}
$html = <<<A
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>安装成功</title>
    </head>
    <body>
        <div style='font-size:16px;color:green;'>:)) 恭喜您,安装成功! <a href='index.php'>访问首页</a> | <a href='admin/login.php'>访问后台</a></div>
    </body>
    </html>
A;
    echo $html;
	exit();
}
?>
<script>
        cocoMessage.config({
		    duration: 1500,
	    });  
</script>
</body>
</html>