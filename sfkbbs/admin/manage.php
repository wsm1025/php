<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
$template['title'] = '管理员列表页';
if(!ManageIslogin($link)){
	skip('login.php', '未登录');
}
?>
<?php include_once 'inc/header.inc.php' ?>

<div id="main">
	<div class="title">管理员列表页</div>
		<table class="list">
			<tr>
				<th>名称</th>
				<th>等级</th>
				<th>创建日期</th>
                <th>操作</th>
			</tr>

			<?php
			$query = "select * from sfk_manage";
			$result = execute($link, $query);
			while ($data = mysqli_fetch_assoc($result)) { //类似模板渲染
				$url = urlencode("manage_delete.php?id={$data['id']}");
				$return_url = $_SERVER['REQUEST_URI'];
				$message = "{$data['name']}";
				$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				//定界符号(A)随意\
                if($data['level']==1){
                    $data['level']='普通管理员';
                }elseif($data['level']==0){
                    $data['level']='超级管理员';
                }
				$html = <<<A
			<tr>
				<td>{$data['name']}[id:{$data['id']}]</td>
				<td>{$data['level']}</td>
				<td>{$data['createtime']}</td>
				<td><a href="$delete_url">[删除]</a></td>
			</tr>
A;
				echo $html;
			}
			?>
		</table>
</div>
<?php include_once 'inc/footer.inc.php' ?>