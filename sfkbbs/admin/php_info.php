<?php
include_once '../../sfkbbs/inc/config.inc.php';
include_once '../../sfkbbs/inc/mysql.inc.php';
include_once '../admin/inc/tool.inc.php';
$link = connect();
if(!ManageIslogin($link)){
    skip('login.php', '未登录');
}
phpinfo();