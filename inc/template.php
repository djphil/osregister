<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $title. " v" .$version; ?>">
    <meta name="author" content="Philippe Lemaire (djphil)">
    <link rel="icon" href="img/favicon.ico">
    <link rel="author" href="inc/humans.txt" />
    <meta name="robots" content="noindex">
    <title><?php echo $title. " v" .$version; ?></title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/bootstrap-extras.css">
    <link rel="stylesheet" href="css/ie10-viewport-bug-workaround.css">
    <link rel="stylesheet" href="css/gh-fork-ribbon.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-loveit.css">
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/login.css">
    <?php include_once("css/css.php"); ?>
    <link rel="stylesheet" href="css/jumbotron.css">
    <link rel="stylesheet" href="css/osregister.css">
</head>

<body>
    <?php if($display_forkme): ?>
    <div class="github-fork-ribbon-wrapper left">
        <div class="github-fork-ribbon">
            <a href="https://github.com/djphil/osregister" target="_blank">Fork me on GitHub</a>
        </div>
    </div>
    <?php endif; ?>

    <div class="spacer"></div>
    <div class="container">
        <?php include_once("inc/navbar.php"); ?>

        <!--FASH MESSAGE-->
        <?php if(isset($_SESSION['flash'])): ?>
            <?php foreach($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?php echo $type; ?> alert-anim">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $message; ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!--CONTENT-->
        <div class="osregister">
            <?php echo $content; ?>
        </div>

        <footer class="footer navbar navbar-default">
            <div class="container-fluid">
                <div class="text-muted">
                    <div class="pull-right">
                        <?php echo $title.' v'.$version.' '.$txt_by; ?> djphil 
                        <span class="label label-default">CC-BY-NC-SA 4.0</span>
                    </div>
                    &copy; 2015 - <?php $date = date('Y'); echo $date; ?> Digital Concepts - <?php echo $txt_allrightreserved; ?>
                </div>
            </div>
        </footer>

        <div class="text-center text-muted">
            <?php echo $txt_handcrafted; ?><br />
            <?php echo $txt_pageloadtime." <span class=badge>".page_load_time(); ?></span> ms
        </div>
    </div>

    <!--SCRIPTS-->
    <script src="js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap-select.js"></script>

    <script>$(document).ready(function() {$('[data-toggle="tooltip"]').tooltip();});</script>
    <script>$(document).ready(function() {$('[data-toggle="popover"]').popover();});</script>
    <script>$(document).ready(function() {$('.selectpicker').selectpicker();});</script>

    <!--TOGGLE PASSWORD VISIBILITY-->
    <script>
    var targets = ".toggle-register-password";
        targets += ", .toggle-register-repeat";
        targets += ", .toggle-dropdown-password";
        targets += ", .toggle-login-password";
        targets += ", .toggle-account-password";
        targets += ", .toggle-current-password";
        targets += ", .toggle-new-password";
        targets += ", .toggle-repeat-password";
    $(targets).click(function() {
        $(this).toggleClass("glyphicon-eye-open glyphicon-eye-close");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {input.attr("type", "text");}
        else {input.attr("type", "password");}
    });
    </script>

    <!--ANIM ALERT MESSAGES-->
    <script>
    $(document).ready(function() {
        $(".alert-anim").fadeTo(4000, 500).slideUp(500, function() {
            $(".alert-anim").alert('close');
        });
    });
    </script>

    <!--MODELS-->
    <script>
    $('.model_hide').addClass('collapse');
    $('#model').change(function() {
        var selector = '.model_' + $(this).val();
        $('.model_hide').collapse('hide');
        $(selector).collapse('show');
        $('.model_hide').collapse('hide');
    });
    </script>

    <!--CHECKBOX-->
    <script>
    function changeState(el) {
        if (el.readOnly) el.checked=el.readOnly=false;
        else if (!el.checked) el.readOnly=el.indeterminate=true;
    }
    </script>

    <script>
    $('#nav-list li').on('click', function() {$(this).find('div').slideToggle('100');});
    </script>
</body>
</html>
