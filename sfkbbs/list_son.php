<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
?>
<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '子板块Id不合法');
}
$query = "select * from sfk_son_module where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip('index.php', '子板块Id不合法');
}
$data_son = mysqli_fetch_assoc(execute($link, $query));

$query = "select count(*) from sfk_content where module_id ={$_GET['id']}";
$res_content_cout = num($link, $query);
$query = "select count(*) from sfk_content where module_id ={$_GET['id']} and time>CURDATE()";
$today_content_cout = num($link, $query);
$query = "select * from sfk_member where id='{$data_son['member_id']}'";
$member_name = execute($link, $query);
?>
<?php $template['title'] = '子板块列表页' . "---" . $data_son['module_name']; ?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <?php
    $query = "select * from sfk_father_module where id={$data_son['father_module_id']}";
    $data_father = mysqli_fetch_assoc(execute($link, $query));
    ?>
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>"> <?php echo $data_father['module_name'] ?></a> &gt; <?php echo $data_son['module_name'] ?>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo $data_son['module_name'] ?></h3>
            <div class="num">
                今日：<span>
                    <?echo $today_content_cout;?>
                </span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $res_content_cout ?></span>
            </div>
            <div class="moderator">版主：<span><?php if (!mysqli_num_rows($member_name)) {
                                                echo '暂无版主';
                                            } else {
                                                $data_name = mysqli_fetch_assoc($member_name);
                                                echo $data_name['name'];
                                            } ?></span></div>
            <div class="notice"><?php echo $data_son['info'] ?></div>
            <div class="pages_wrap">
                <a class="btn publish" href="publish.php?son_id=<?php echo $_GET['id'] ?>" target="_blank"></a>
                <div class="pages">
                    <?php $page = page($res_content_cout, 3);
                    echo $page['html'] ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
            $query = "select 
                sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.member_id,sfk_content.times,sfk_member.name,sfk_member.photo 
                from sfk_content,sfk_member where 
                sfk_content.module_id ={$_GET['id']} and 
                sfk_content.member_id=sfk_member.id  {$page['limit']}";
            $res_content = execute($link, $query);
            while ($data_content = mysqli_fetch_assoc($res_content)) {
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
                    <a href="member.php?id=<?php echo "{$data_content['member_id']}"?>" target="_blank">
                            <img width="45" height="45" src="<?php echo $data_content['photo'] ? Pth.$data_content['photo'] : IMG_USER;
                                                                ?>">
                        </a>
                    </div>
                    <div class="subject">
                        <div class="titleWrap">
                            <h2><a href="show.php?id=<?php echo $data_content['id'] ?>" target="_blank"><?php echo $data_content['title'] ?></a></h2>
                        </div>
                        <p style="padding: 0;margin:0;">
                         <?php 
								if($member_id == $data_content['member_id']){
									$return_url=urlencode($_SERVER['REQUEST_URI']);
							        $url=urlencode("content_delete.php?id={$data_content['id']}&return_url={$return_url}");
									$message = "{$data_content['title']}";
									$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
									echo "<a target='_blank' href='content_edit.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a> | <a href='{$delete_url}'>删除</a><br/>";
								}
							?>
                            楼主：<?php echo $data_content['name'] ?>&nbsp;<?php echo $data_content['time'] ?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time_info;?>
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
            <?php
            }
            ?>
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