<?php if (session_status() == PHP_SESSION_NONE) {session_start();} ?>
<?php include_once("inc/config.php"); ?>
<?php include_once("inc/PDO-mysql.php"); ?>
<?php include_once("inc/functions.php"); ?>
<?php page_load_time(); ?>
<?php include_once("inc/translator.php"); ?>
<?php
if (isset($_GET['page'])) {$page = $_GET['page'];}
else {$page = 'home';}
ob_start();
if ($page === 'home') {require 'inc/home.php';}
else if ($page === 'login') {require 'inc/login.php';}
else if ($page === 'logout') {require 'inc/logout.php';}
else if ($page === 'register') {require 'inc/register.php';}
else if ($page === 'forgetpass') {require 'inc/forgetpass.php';}
else if ($page === 'account') {require 'inc/account.php';}
else if ($page === 'help') {require 'inc/help.php';}
else if ($page === '404') {require 'inc/404.php';}
else require("inc/404.php");
$content = ob_get_clean();
require 'inc/template.php';
exit();
?>
