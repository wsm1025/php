<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
?>
<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '父板块Id不合法');
}
$query = "select * from sfk_father_module where id={$_GET['id']}";
if (mysqli_num_rows(execute($link, $query)) == 0) {
    skip('index.php', '父板块Id不合法');
}
$data_father = mysqli_fetch_assoc(execute($link, $query));
$query = "select * from sfk_son_module where father_module_id='{$_GET['id']}'";
$res_son =  execute($link, $query);
$id_son = '';
$son_list = '';
while ($data_son = mysqli_fetch_assoc($res_son)) {
    $id_son .= $data_son['id'] . ',';
    $son_list .= "<a href='list_son.php?id={$data_son['id']}'>{$data_son['module_name']}</a> ";
}
$id_son = trim($id_son, ',');
if($id_son==''){
    $id_son ='-1';
}
$query = "select count(*) from sfk_content where module_id in ({$id_son})";
$res_content_cout = num($link, $query);
$query = "select count(*) from sfk_content where module_id in ({$id_son}) and time>CURDATE()";
$today_content_cout = num($link, $query);
?>

<?php $template['title'] = '父板块列表页' . "---" . $data_father['module_name']; ?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id'] ?>"><?php echo $data_father['module_name'] ?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo $data_father['module_name']; ?></h3>
            <div class="num">
                今日：<span><?php echo $today_content_cout; ?></span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $res_content_cout; ?></span>
                <div class="moderator"> 子版块：<?php echo $son_list; ?></div>
            </div>
            <div class="pages_wrap">
               <a class="btn publish" href="publish.php?father_module_id=<?php echo $_GET['id']?>" target="_blank"></a>
                <div class="pages">
                   <?php 
                   $page= page($res_content_cout,3);
                   echo $page['html'];
                   ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
                $query = "select 
                sfk_content.title,sfk_content.id,sfk_content.time,sfk_content.member_id,sfk_content.times,sfk_member.name,sfk_member.photo,sfk_son_module.module_name,sfk_son_module.id ssm_id  
                from sfk_content,sfk_member,sfk_son_module where 
                sfk_content.module_id in({$id_son}) and 
                sfk_content.member_id=sfk_member.id and 
                sfk_content.module_id=sfk_son_module.id {$page['limit']}";
                $res_content = execute($link, $query);
                while($data_content = mysqli_fetch_assoc($res_content)){
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
                        <img width="45" height="45" src="<?php echo $data_content['photo']? Pth.$data_content['photo']:IMG_USER;?>">
                    </a>
                </div>
                <div class="subject">
                    <div class="titleWrap">[<a href="list_son.php?id=<?php echo $data_content['ssm_id'];?>"><?php echo $data_content['module_name']?></a>]&nbsp;&nbsp;<h2><a href="show.php?id=<?php echo $data_content['id']?>"  target="_blank"><?php echo $data_content['title']?></a></h2>
                    </div>
                    <p style="padding: 0;margin:0;">
                    <?php 
								if($member_id == $data_content['member_id']){
                                    $return_url = $_SERVER['REQUEST_URI'];
									$url = urlencode("content_delete.php?id={$data_content['id']}&return_url={$return_url}");
									$message = "{$data_content['title']}";
									$delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
									echo "<a target='_blank' href='content_edit.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a> | <a href='{$delete_url}'>删除</a><br/>";
								}
							?>	
                        楼主：<?php echo $data_content['name']?>&nbsp;<?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time_info?>
                    </p>
                </div>
                <div class="count">
                    <p>
                        回复<br /><span><?php echo $num_all;?></span>
                    </p>
                    <p>
                        浏览<br /><span><?php echo $data_content['times']?></span>
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
                  $query= "select * from sfk_father_module";
                  $res = execute($link,$query);
                  while($data_father = mysqli_fetch_assoc($res)){
                ?>
                <li>
                    <h2><a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a></h2>
                    <ul>
                    <?php
                        $query= "select * from sfk_son_module where father_module_id={$data_father['id']}";
                        $res_son = execute($link,$query);
                        while($data_son = mysqli_fetch_assoc($res_son)){
                    ?>
                        <li>
                            <h3><a href="<?php echo "list_son.php?id={$data_son['id']}"?>"><?php echo $data_son['module_name']?></a></h3>
                        </li>
                        <?php }?>
                    </ul>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<?php include_once 'inc/footer.inc.php' ?>