<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if (isset($_POST['submit'])) {
	foreach ($_POST['sort'] as $key => $val) {
		if (!is_numeric($val) || !is_numeric($key)) {
			skip('father_module.php', '排序参数错误');
		}
		if (mb_strlen($key) > 10 || mb_strlen($val) > 10) {
			skip('father_module.php', '数量过长');
		}
		$query[] = "update sfk_father_module set sort={$val} where id={$key}";
	}
	if (execute_multi($link, $query, $error)) {
		skip('father_module.php', '排序修改成功');
	} else {
		skip('father_module.php', $error);
	}
}
$template['title'] = '父板块列表页';
?>
<?php include 'inc/header.inc.php' ?>
<div id="main">
	<div class="title">父版块列表</div>
	<form method="post">
		<table class="list">
			<tr>
				<th>排序</th>
				<th>版块名称</th>
				<th>下属子版块数量</th>
				<th>操作</th>
			</tr>


			<?php
			$query = "select * from sfk_father_module";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) { //类似模板渲染
				$url = urlencode("father_module_delete.php?id={$data['id']}");
				$return_url = $_SERVER['REQUEST_URI'];
				$message = "{$data['module_name']}";
				// $message = DelHtml($message);//语义化处理
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				//定界符号(A)随意
				$query =  "select COUNT(*) from sfk_son_module where father_module_id={$data['id']}"; //查询数量语句
				$res = num($link, $query);

				$html = <<<A
			<tr>
				<td><input class="sort" type="text" name="sort[{$data['id']}]" value="{$data['sort']}" /></td>
				<td>{$data['module_name']}[id:{$data['id']}]</td>
				<td>{$res}</td>
				<td><a href="../list_father.php?id={$data['id']}">[访问]</a>&nbsp;&nbsp;<a href="father_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
			</tr>
A;
				echo $html;
			}
			?>
		</table>
		<?php
		if ($result->num_rows) {
			echo "<input type='submit' class='btn' name='submit' value='修改排序'/>";
		}
		?>
	</form>
</div>
<?php include 'inc/footer.inc.php' ?>