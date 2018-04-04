<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
/* Flags by djphil (CC-BY-NC-SA 4.0) */
foreach ($languages as $langCode => $langName)
{
    if ($langCode != $language_code)
    {
        echo '<a class="btn btn-default btn-xs" href="?lang='.$langCode.'">';
        echo '<img src="./img/flags/flag-'.$langCode.'.png" alt="'.$langName.'" title="'.$langName.'" />';
        echo '</a> ';
        echo '';
    }
}
?>
