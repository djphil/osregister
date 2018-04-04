<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
/* Translator by djphil (CC-BY-NC-SA 4.0) */
$translator = true;
$languages = array(
    "fr" => "Français",
    "en" => "English"
);

if (!empty($_COOKIE['lang'])) $lang = $_COOKIE['lang'];
if (!empty($_GET['lang'])) $lang = $_GET['lang'];
if (!empty($lang) && array_key_exists($lang, $languages))
{
    include('./lang/lang_'.$lang.'.php');
    setcookie('lang', $lang,time() + 3600*25*365, '/');
}

else include('./lang/lang_fr.php');
?>
