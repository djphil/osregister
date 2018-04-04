<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if(isset($_SESSION['valid'])): ?>
    <?php $_SESSION['flash']['danger'] = "You are already log-in ".$_SESSION['username']." ..."; ?>
    <?php header("Location: ?page=home"); exit(); ?>
<?php endif; ?>
<h1><?php echo $txt_identify; ?><i class="glyphicon glyphicon-lock pull-right"></i></h1>
<div class="clearfix"></div>

<?php
if (isset($_POST['forgetpass']))
{
    if (!empty($_POST['email']))
    {
        $email = $_POST['email'];
        $userdatas = get_userdatas_by_email($db, $email);

        if ($userdatas)
        {
            $PrincipalID = $userdatas['PrincipalID'];
            $FirstName = $userdatas['FirstName'];
            $LastName = $userdatas['LastName'];
            $username = $FirstName." ".$LastName;
            $password = generate_password(10);

            change_password($db, $PrincipalID, $password);

            /*sendmail*/
            $toname = $email;
            $subject = $title.' v'.$version.' - Reset Password';
            $message = "
            <html>
            <head>
                <title>".$title.' v'.$version."</title>
            </head>
            <body>
                <h1>".$title.' v'.$version."</h1>
                <h2>Reset Password</h2>
                <p><strong>Username:</strong> ".$username." </br /><strong>Password:</strong> ".$password."</p>    
            </body>
            </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <noreply@noreply.com>' . "\r\n";
            $headers .= 'Cc: noreply@noreply.com' . "\r\n";
            mail($toname, $subject, $message, $headers);
            $_SESSION['flash']['success'] = "Password reseted successfully ...";
            header("Location: ?page=login");
        }

        else 
        {
            debug($userdatas);
            $_SESSION['flash']['success'] = "Unknow email ...";
            header("Location: ?page=home");
        }
    }
}
?>

<?php if (!isset($_POST['forgetpass'])): ?>
<form class="form form-horizontal form-forget" role="form" action="?page=forgetpass" method="post">
    <?php if ($invisible_antispam === TRUE): ?>
        <input type="hidden" name="robots" value="">
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <span class="text-muted"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> <?php echo $txt_ua_requieredfields; ?></span>
            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo $txt_email; ?>" required autofocus>
            </div>
            <button class="btn btn-success btn-block btn-padding-top" type="submit" name="forgetpass">
                <i class="glyphicon glyphicon-ok"></i> <?php echo $txt_resetpass; ?>
            </button>
            <a class="btn btn-primary btn-block btn-padding-top" href="?page=login">
                <i class="glyphicon glyphicon-log-in"></i> <?php echo $txt_login; ?>
            </a>
        </div>
        <div class="panel-footer"></div>
    </div>
</form>
<?php endif; ?>
