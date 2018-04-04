<section>
    <article>
        <h1><?php echo $menu_help; ?><i class="glyphicon glyphicon-education pull-right"></i></h1>
        <?php echo $txt_welcometo.' '.$title." v".$version; ?>
        <br />This is a Registration Web Interface for OpenSimulator
    </article>
    <article>
        <h2><?php echo $txt_feature; ?></h2>
        <?php echo $txt_feature_createaccount; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_createinventory; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_createappearance; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_changeresetpass; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_loginwithemail; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_multilingual; ?> <i class="glyphicon glyphicon-ok text-success"></i><br />
        <?php echo $txt_feature_morecomingsoon; ?> ...
    </article>

    <article>
        <h2><?php echo $txt_requirement; ?></h2>
        Mysql, Php5, Apache<br />
        OpenSimulator v0.9.x
    </article>

    <article>
        <h2><?php echo $txt_download; ?></h2>
        <a class="btn btn-default btn-success btn-xs" href="https://github.com/djphil/osregister" target="_blank">
        <i class="glyphicon glyphicon-save"></i> GithHub</a> Source Code
    </article>

    <article>
        <h2><?php echo $txt_license; ?></h2>
        GNU/GPL General Public License v3.0<br />
    </article>

    <article>
        <h2><?php echo $txt_credit; ?></h2>
        Philippe Lemaire (djphil)
    </article>

    <article>
        <h2><?php echo $txt_donation; ?></h2>
        <p><?php include_once("inc/paypal.php"); ?></p>
    </article>
</section>
