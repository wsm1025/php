<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
?>
<?php
if(!isset($_GET['keyword']) || trim($_GET['keyword'])==''){
    skip('index.php', '无关键字',2);
}
$_GET['keyword']=trim($_GET['keyword']);
$query="select count(*) from sfk_content where title like '%{$_GET['keyword']}%'";
$count_all=num($link,$query);
?>
<?php $template['title'] = '搜索页' ?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="search.php">搜索</a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3>共有<?php echo $count_all;?>条匹配记录</h3>
            <div class="pages_wrap">
                <div class="pages">
                     <?php $page=page($count_all,5); echo $page['html'];?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
        <?php 
			$query="select
			sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.times,sfk_content.member_id,sfk_member.name,sfk_member.photo
			from sfk_content,sfk_member where
			sfk_content.title like '%{$_GET['keyword']}%' and
			sfk_content.member_id=sfk_member.id
			{$page['limit']}";
			$result_content=execute($link,$query);
			while($data_content=mysqli_fetch_assoc($result_content)){
            //文本变色
			$data_content['title_color']=str_replace($_GET['keyword'],"<span style='color:red;'>{$_GET['keyword']}</span>",$data_content['title']);
			$query="select time from sfk_reply where content_id={$data_content['id']} order by id desc limit 1";
			$result_last_reply=execute($link, $query);
			if(mysqli_num_rows($result_last_reply)==0){
				$last_time='暂无';
			}else{
				$data_last_reply=mysqli_fetch_assoc($result_last_reply);
				$last_time=$data_last_reply['time'];
			}
			$query="select count(*) from sfk_reply where content_id={$data_content['id']}";
			?>
			<li>
				<div class="smallPic">
					<a target="_blank" href="member.php?id=<?php echo $data_content['member_id']?>">
						<img width="45" height="45"src="<?php echo $data_content['photo'] ? $data_content['photo'] : IMG_USER; ?>">
					</a>
				</div>
				<div class="subject">
					<div class="titleWrap"><h2><a target="_blank" href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title_color']?></a></h2></div>
					<p style="margin: 0;padding:0;">
                    <?php 
								if($member_id == $data_content['member_id']){
                                    $return_url = $_SERVER['REQUEST_URI'];
									$url = urlencode("content_delete.php?id={$data_content['id']}&return_url={$return_url}");
									$message = "{$data_content['title']}";
									$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
									echo "<a target='_blank' href='content_edit.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a> | <a href='{$delete_url}'>删除</a><br/>";
								}
							?>
						楼主：<?php echo $data_content['name']?>&nbsp;<?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time?><br />
					</p>
				</div>
				<div class="count">
					<p>
						回复<br /><span><?php echo num($link,$query)?></span>
					</p>
					<p>
						浏览<br /><span><?php echo $data_content['times']?></span>
					</p>
				</div>
				<div style="clear:both;"></div>
			</li>
			<?php }?>
        </ul>
        <div class="pages_wrap">
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">版块列表</div>
            <ul class="listWrap">
                <?php
                $query = "select * from sfk_father_module";
                $res = execute($link, $query);
                while ($data_father = mysqli_fetch_assoc($res)) {
                ?>
                    <li>
                        <h2><a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a></h2>
                        <ul>
                            <?php
                            $query = "select * from sfk_son_module where father_module_id={$data_father['id']}";
                            $res_son = execute($link, $query);
                            while ($data_son = mysqli_fetch_assoc($res_son)) {
                            ?>
                                <li>
                                    <h3><a href="<?php echo "list_son.php?id={$data_son['id']}" ?>"> <?php echo $data_son['module_name'] ?></a></h3>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<?php include_once 'inc/footer.inc.php' ?>