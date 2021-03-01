<?php
session_start();
include_once 'vcode.inc.php';
$_SESSION['vcode'] = vcode(100,40,15);
?>