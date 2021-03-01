<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    skip('index.php', '详细Id错误');
}
$query = "select sc.id cid,sc.module_id,sc.title,sc.content,sc.time,sc.member_id,sc.times,sm.name,sm.photo from sfk_content sc,sfk_member sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$res_content = execute($link, $query);
if (mysqli_num_rows($res_content) == 0) {
    skip('index.php', '该帖子不存在');
}
$query ="update sfk_content set times=times+1 where id={$_GET['id']}";//更新浏览量
execute($link,$query);
$data_content = mysqli_fetch_assoc($res_content);
$query = "select * from sfk_son_module where id={$data_content['module_id']}";
$res_son = execute($link, $query);
$data_son=mysqli_fetch_assoc($res_son);

$query = "select count(*) from sfk_reply where content_id={$_GET['id']}";
$count_reply = num($link,$query);
$pageSize = 5;
$page = page($count_reply,$pageSize);

$query = "select * from sfk_father_module where id={$data_son['father_module_id']}";
$res_father = execute($link, $query);
$data_father=mysqli_fetch_assoc($res_father);
?>
<?php $template['title'] = '帖子详细页'.'---'.$data_content['title']?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name'];?></a> &gt; <?php echo $data_content['title']?>
</div>
<div id="main" class="auto">
    <div class="wrap1">
        <div style="clear:both;"></div>
    </div>
    <?php 
        if($_GET['page']==1){
    ?>
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a  href="javascript:;">
                    <img width="120" height="120" src="<?php echo $data_content['photo'] ? $data_content['photo']:IMG_USER;?>" />
                </a>
            </div>
            <div class="name">
                <a href="member.php?id=<?php echo $data_content['member_id'];?>"><?php echo $data_content['name']?></a>
            </div>
        </div>
        <div class="right">
            <div class="title">
                <h2><?php echo $data_content['title']?></h2>
                <?php
                     $query="select count(*) from sfk_reply where content_id={$_GET['id']}";
                     $num_all = num($link,$query); 
                ?>
                <span>阅读：<?php echo ($data_content['times']+1)?>&nbsp;|&nbsp;回复：<?php echo $num_all;?></span>
                <div style="clear:both;"></div>
            </div>
            <div class="pubdate">
                <span class="date">发布于：<?php echo $data_content['time']?> </span>
                <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
            </div>
            <div class="content">
            <!-- 换行处理 -->
            <?php echo nl2br($data_content['content'])?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <?php }?>
    <?php 
        $query="select sm.name,sr.member_id,sr.quote_id,sm.photo,sr.time,sr.id,sr.content from sfk_reply sr,sfk_member sm where sr.member_id = sm.id and sr.content_id={$_GET['id']} order by id asc {$page['limit']}";
        $res_reply = execute($link,$query);
        $index=($_GET['page']-1)*$pageSize+1;//楼号
        while($data_reply=mysqli_fetch_assoc($res_reply)){
    ?>
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a  data-uid="2374101" href="javascript:;">
                    <img width="120" height="120" src="<?php echo $data_content['photo'] ? $data_content['photo']:IMG_USER;?>" />
                </a>
            </div>
            <div class="name">
                <a class="J_user_card_show mr5" data-uid="2374101" href="member.php?id=<?php echo $data_reply['member_id'];?>"><?php echo $data_reply['name']?></a>
            </div>
        </div>
        <div class="right">
            <div class="pubdate">
                <span class="date">回复时间：<?php echo $data_reply['time']?></span>
                <?php
                if($member_id==$data_reply['member_id']){
                    $url = urlencode("reply_delete.php?id={$data_reply['id']}");
                    $return_url = $_SERVER['REQUEST_URI'];
                    $message = "{$data_reply['content']}";
                    $delete_url = "confirm.php?url={$url}&return_url={$return_url}&message={$message}";
                    echo "<a href='{$delete_url}' style='margin-left: 20px;'>删除</a>";
                }
                ?>
                <span class="floor"><? echo $index++;?>楼&nbsp;|&nbsp;<a href="qoute.php?id=<?php echo $_GET['id']?>&reply_id=<?php echo $data_reply['id'];?>" target="_blank">引用</a></span>
            </div>
            <div class="content">
                <?php 
                if($data_reply['quote_id']){
                    $query = "select count(*) from sfk_reply where content_id={$_GET['id']} and id<={$data_reply['quote_id']}";
                    $floor = num($link,$query);
                    $query="select sfk_reply.content,sfk_member.name from sfk_reply,sfk_member where sfk_reply.id={$data_reply['quote_id']} and sfk_reply.content_id={$_GET['id']} and sfk_reply.member_id=sfk_member.id";
                    $res_quote = execute($link, $query);
                    $data_quote = mysqli_fetch_assoc($res_quote);
                ?>
                    <div class="quote">
                    <h2><?php 
                        if($data_quote){
                            echo  "引用". $floor;?>楼 <?php echo $data_quote['name']?> 发表的: </h2><?php echo $data_quote['content'];
                            }else{
                                echo '该评论已被删除!';
                            }?>
                    </div>
                <?php }?>
                <?php echo $data_reply['content'] = nl2br( $data_reply['content'])?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
     <?php }?>
    <div class="wrap1">
        <div class="pages">
            <?php 
                echo $page['html']
            ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>"></a>
        <div style="clear:both;"></div>
    </div>
</div>
<?php include_once 'inc/footer.inc.php' ?>