<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if (!isset($_SESSION['valid']) && $private_website === TRUE) {header("Location: ?page=login"); exit();} ?>
<h1><?php echo $menu_home; ?><i class="glyphicon glyphicon-home pull-right"></i></h1>

<?php if($display_jumbotron): ?>
<div class="jumbotron-particles">
    <div class="img-thumbnail" id="particles-js"></div>
    <div class="jumbotron-holder">
        <h1><?php echo $title. " v" .$version; ?></h1>
        <h3><?php echo $txt_title_desc; ?></h3>
        <br />
        <?php if (isset($_SESSION['valid'])): ?>
            <a href="./?page=account" class="btn <?php echo $jumbotrom_class; ?>">
                <i class="glyphicon glyphicon-ok"></i> <?php echo $txt_manageaccount; ?>
            </a>
        <?php else: ?>
            <a href="./?page=register" class="btn <?php echo $jumbotrom_class; ?>">
                <i class="glyphicon glyphicon-ok"></i> <?php echo $txt_joinnow; ?>
            </a>
        <?php endif; ?>
    </div>
</div>
<script src="js/particles.min.js"></script>
<script src="js/jumbotron.js"></script>
<hr class="divider">
<?php endif; ?>
