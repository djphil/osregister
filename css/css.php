<style>
<?php
/* JUMBOTRON DYNAMIC CSS v0.1 by djphil (CC-BY-NC-SA 4.0) */
if (!empty($jumbotrom_color))
{
    $jumbotrom_color = strtolower($jumbotrom_color);
    echo '#particles-js {background-image: url("");}';

    if ($jumbotrom_color == "blue")
    {
        echo "#particles-js {background: linear-gradient(270deg, #8851FF, #44AEFF);}";
    }

    if ($jumbotrom_color == "green")
    {
        echo "#particles-js {background: linear-gradient(270deg, #09B400, #4DDF45);}";
    }

    if ($jumbotrom_color == "orange")
    {
        echo "#particles-js {background: linear-gradient(270deg, #FF9400, #FFC100);}";
    }

    if ($jumbotrom_color == "red")
    {
        echo "#particles-js {background: linear-gradient(270deg, #FF0000, #9E0000);}";
    }
}
?>
</style>