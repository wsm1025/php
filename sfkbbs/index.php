<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link = connect();
$member_id = IsLogin($link);
?>
<?php
$template['title'] = '欢迎观临';
?>
<?php include_once 'inc/header.inc.php' ?>
<div style="margin-top:55px;"></div>
<div id="hot" class="auto">
    <div class="title">热门帖子</div>
    <ul class="newlist">
        <!-- 20条 -->
        <?php
            $query="select count(*) from sfk_content where times>=10";
            $number_content = num($link,$query);
            $page = page($number_content,3);
            $query="select * from sfk_content where times>=10 {$page['limit']}";
            $res_content = execute($link,$query);
            while($data_content = mysqli_fetch_assoc($res_content)){?>
            <li>
                <a target="_blank" href="show.php?id=<?php echo $data_content['id'];?>"><?php echo $data_content['title'];?>[访问次数:<?php echo $data_content['times']?>]</a>
            </li>
        <?php }?>
        </ul>
    <div style="clear:both;"></div>
    <div class="pages" style="margin: 20px 10px 0 10px;">
            <?php
            	echo $page['html'] ?>
    </div>
</div>
<?php
$query = "select * from sfk_father_module order by sort desc";
$res_father = execute($link, $query);
while ($data_father = mysqli_fetch_assoc($res_father)) {
?>
    <div class="box auto">
        <div class="title">
            <a href="list_father.php?id=<?php echo $data_father['id']?>" style="color:#105cb6"><?php echo $data_father['module_name'] ?></a>
        </div>
        <div class="classList">
            <?php
            $query = "select * from sfk_son_module where father_module_id={$data_father['id']} order by sort desc";
            $res_son = execute($link, $query);
            if (mysqli_num_rows($res_son)) {
                while ($data_son = mysqli_fetch_assoc($res_son)) {//比较时间
                    $query = "select COUNT(*) from sfk_content where module_id={$data_son['id']} and time>CURDATE() ";
                    $num = num($link,$query);
                    $query = "select COUNT(*) from sfk_content where module_id={$data_son['id']}";
                    $allnum = num($link,$query);
                    $html = <<<A
                    <div class='childBox new'>
                    <h2><a href='list_son.php?id={$data_son['id']}'>{$data_son['module_name']}</a> <span>(今日{$num})</span></h2>
                    帖子：{$allnum}<br />
                    </div>
A;
                    echo $html;
                }
            } else {
                echo '<div style="padding:10px 0;">暂无子版块.....</div>';
            }
            ?>
            <div style="clear:both;"></div>
        </div>
    </div>
<?php } ?>
<?php include_once 'inc/footer.inc.php' ?>
