<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
$_SESSION['flash']['success'] = $txt_ua_logoutsuccess_alert." <strong>".$_SESSION["username"]."</strong> ...";
unset($_SESSION["valid"]);
unset($_SESSION["username"]);
unset($_SESSION['useruuid']);
if (isset($_COOKIE[$cookie_name])) {setcookie ($cookie_name, '', time() - $cookie_time);}
header ('Location: ?page=home');
exit;
?>
