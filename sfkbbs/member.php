<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '会员Id错误',2);
}
$query = "select * from sfk_member where id={$_GET['id']} ";
$res_member = execute($link, $query);
if (mysqli_num_rows($res_member) == 0) {
    skip('index.php', '该用户不存在',2);
}
$data_member = mysqli_fetch_assoc($res_member);
?>
<?php $template['title'] = '会员中心'?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
	<div id="position" class="auto">
		<a href="index.php">首页</a> &gt; <?php echo $data_member['name'];?>
	</div>
	<div id="main" class="auto">
		<div id="left">
			<ul class="postsList">
                <?php
                    $query="select count(*) from sfk_content where member_id={$_GET['id']}";
                    $num_ALL = num($link,$query); 
                    $page = page($num_ALL,5);
                   $query = "select 
                   sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.member_id,sfk_content.times,sfk_member.name,sfk_member.photo 
                   from sfk_content,sfk_member where 
                   sfk_content.member_id ={$_GET['id']} and 
                   sfk_content.member_id=sfk_member.id order by id desc {$page['limit']}";
                   $res_content = execute($link,$query);
                   while($data_content  = mysqli_fetch_assoc($res_content)){
                    $query="select time from sfk_reply where content_id={$data_content['id']} order by id desc limit 1";
                    $last_time = execute($link,$query);
                    if(mysqli_num_rows($last_time)==0){
                        $last_time_info = '暂无';
                    }else{
                        $data_last_info = mysqli_fetch_assoc($last_time);
                        $last_time_info =  $data_last_info['time'];
                    }
                    $query="select count(*) from sfk_reply where content_id={$data_content['id']}";
                    $num_all = num($link,$query);      
                ?>
				<li>
					<div class="smallPic">
							<img width="45" height="45" src="<?php echo $data_content['photo'] ? Pth.$data_content['photo']:IMG_USER;?>" />
					</div>
					<div class="subject">
						<div class="titleWrap"><h2><a target="_blank" href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title']?></a></h2></div>
						<p style="margin: 0;padding:0;">
							<?php 
								if($member_id == $data_content['member_id']){
									$url = urlencode("content_delete.php?id={$data_content['id']}");
									$return_url = $_SERVER['REQUEST_URI'];
									$message = "{$data_content['title']}";
									$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
									echo "<a target='_blank' href='content_edit.php?id={$data_content['id']}'>编辑</a> | <a href='{$delete_url}'>删除</a><br/>";
								}
							?>	
                            发帖时间：<?php echo $data_content['time'] ?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time_info;?>
						</p>
					</div>
					<div class="count">
						<p>
							回复<br /><span><?php echo $num_all;?></span>
						</p>
						<p>
							浏览<br /><span><?php echo $data_content['times'] ?></span>
						</p>
					</div>
					<div style="clear:both;"></div>
				</li>
                <?php  } ?>
			</ul>
			<div class="pages">
            <?php
            	echo $page['html'] ?>
            </div>
		</div>
		<div id="right">
			<div class="member_big">
				<dl>
					<dt>
					   <img onclick="show('<?php echo $data_member['photo'] ? $data_member['photo'] : IMG_USER; ?>')" width="180" height="180" src="<?php echo $data_member['photo'] ? $data_member['photo'] : IMG_USER; ?>" />
					</dt>
					<dd class="name"><?php echo $data_member['name'];?></dd>
					<dd>帖子总计：<?php echo $num_ALL;?></dd>
					<?php if($member_id==$data_member['id']){?>
					<dd>操作：<a target="_blank" href="member_photo_update.php">修改头像</a> | <a target="_blank" href="password_update.php">修改密码</a></dd>
				   <?php }?>
				</dl>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
<?php include_once 'inc/imgshow.inc.php'?>
<?php include_once 'inc/footer.inc.php' ?>

