<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
//数据验证
if (!ManageIslogin($link)) {
	skip('login.php', '未登录');
}
$template['title'] = '帖子管理';
?>
<?php include_once 'inc/header.inc.php' ?>
<div id="main">
	<div class="title"><?php echo $template['title'] ?></div>
	<form method="post">
		<table class="list">
			<tr>
				<th>排序</th>
				<th>版块名称</th>
				<th>父模块</th>
				<th>内容</th>
				<th>时间</th>
				<th>发布人</th>
				<th>浏览次数</th>
				<th>操作</th>
			</tr>
			<?php
			$query = "select count(*) from sfk_content";
			$total = num($link, $query);
			$page = page($total, 5);
			$query = "select * from sfk_content  order by id {$page['limit']} ";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) { //类似模板渲染
				$query = "select name from sfk_member where id = {$data['member_id']}";
				$data_user_name = mysqli_fetch_assoc(execute($link, $query));
				$query = "select module_name from sfk_son_module where id = {$data['module_id']}";
				$data_son_module_name = mysqli_fetch_assoc(execute($link, $query));
				$url = urlencode("content_manage_delete.php?id={$data['id']}");
				$return_url = urlencode($_SERVER['REQUEST_URI']);
				$message = "(帖子){$data['title']}";
				// $message = DelHtml($message); //语义化处理
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				//定界符号(A)随意
				$html = <<<A
			<tr>
				<td>{$data['id']}</td>
				<td>{$data['title']}</td>
				<td>{$data_son_module_name['module_name']}</td>
				<td class="title_show">{$data['content']}</td>
				<td>{$data['time']}</td>
            	<td>{$data_user_name['name']}</td> 
            	<td>{$data['times']}</td>
            	<td><a href="content_manage_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="$delete_url">[删除]</a></td>
         </tr>
A;
				echo $html;
			}
			?>
		</table>
	</form>
	<div class="pages_wrap ">
		<div class="pages">
			<?php
			echo $page['html'] ?>
		</div>
	</div>
</div>
<?php include_once 'inc/footer.inc.php' ?>