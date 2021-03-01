<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$template['title'] = '子板块列表';
$link = connect();
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
if (isset($_POST['submit'])) {
	foreach ($_POST['sort'] as $key => $val) {
		if (!is_numeric($val) || !is_numeric($key)) {
			skip('son_module.php', '排序参数错误');
		}
		if (mb_strlen($key) > 10 || mb_strlen($val) > 10) {
			skip('son_module.php', '数量过长');
		}
		$query[] = "update sfk_son_module set sort={$val} where id={$key}";
	}
	if (execute_multi($link, $query, $error)) {
		skip('son_module.php', '排序修改成功');
	} else {
		skip('son_module.php', $error);
	}
}
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
	<div class="title"><?php echo $temlpate['title'] ?></div>
	<form method="post">
		<table class="list">
			<tr>
				<th>排序</th>
				<th>版块名称</th>
				<th>所属父板块</th>
				<th>版主</th>
				<th>操作</th>
			</tr>
			<?php
			$query = "select son.sort, son.id,son.module_name,father.module_name father_module_name,son.member_id from sfk_son_module son,sfk_father_module father where son.father_module_id=father.id order by father.id ";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) { //类似模板渲染
				$url = urlencode("son_module_delete.php?id={$data['id']}");
				$return_url = urlencode($_SERVER['REQUEST_URI']);
				$message = "(子){$data['module_name']}";
				$message = DelHtml($message); //语义化处理
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				//定界符号(A)随意
				$html = <<<A
			<tr>
				<td><input class="sort" type="text" name="sort[{$data['id']}]" value="{$data['sort']}" /></td>
				<td>{$data['module_name']}[id:{$data['id']}]</td>
				<td>{$data['father_module_name']}</td>
				<td>{$data['member_id']}</td>
				<td><a href="../list_son.php?id={$data['id']}">[访问]</a>&nbsp;&nbsp;<a href="son_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
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
<?php include_once 'inc/footer.inc.php' ?>