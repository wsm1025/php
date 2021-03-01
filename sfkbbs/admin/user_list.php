<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
$template['title'] = '用户列表页';
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
?>
<?php include_once 'inc/header.inc.php' ?>

<div id="main">
	<div class="title"><?php echo $template['title'];?></div>
		<table class="list">
			<tr>
				<th>名称</th>
				<th>头像</th>
				<th>创建日期</th>
                <th>最后登录日期</th>
                <th>操作</th>
			</tr>
			<?php
			$query = "select count(*) from sfk_member";
			$total = num($link, $query);
			$page = page($total, 5);
			$query = "select * from sfk_member {$page['limit']}";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) { //类似模板渲染
				$url = urlencode("member_delete.php?id={$data['id']}");
				$return_url = $_SERVER['REQUEST_URI'];
				$message = "{$data['name']}";
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				// 定界符号(A)随意
				$data['last_time'] = $data['last_time']?$data['last_time']:"null";
				$data['photo'] = $data['photo']?Pth.$data['photo']:IMG_USER;
				$html = <<<A
			<tr>
				<td>{$data['name']}[id:{$data['id']}]</td>
				<td><img onclick="show('{$data['photo']}')" width="50" height="50" src="{$data['photo']}"/></td>
				<td>{$data['register_time']}</td>
				<td>{$data['last_time']}</td>
				<td><a href="$delete_url">[删除]</a></td>
			</tr>
A;
				echo $html;
			}
			?>
		</table>
		<div class="pages_wrap ">
		<div class="pages">
			<?php
			echo $page['html'] ?>
		</div>
	</div>
</div>
<?php include_once '../../sfkbbs/inc/imgshow.inc.php'?>
<?php include_once 'inc/footer.inc.php' ?>