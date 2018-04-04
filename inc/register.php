<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if(isset($_SESSION['valid'])): ?>
    <?php $_SESSION['flash']['danger'] = "You are already log-in <strong>".$_SESSION['username']."</strong> ..."; ?>
    <?php header("Location: ?page=home"); exit(); ?>
<?php endif; ?>
<h1><?php echo $txt_identify; ?> <i class="glyphicon glyphicon-lock pull-right"></i></h1>
<div class="clearfix"></div>

<?php
/* Create New Account */
if (isset($_POST['create']))
{
    if ($invisible_antispam === TRUE)
    {
        if (!empty($_POST['robots']))
        {
            $_SESSION['flash']['danger'] = $txt_antispam_alert." ...";
            header("Location: ?page=home");
            exit();
        }
    }

    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['repeat']) && !empty($_POST['model']))
    {
        if ($allow_registration === TRUE)
        {
            $password = $_POST['password'];
            $repeat = $_POST['repeat'];

            if ($password === $repeat)
            {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];
                $model = $_POST['model'];
                $old = isset($_POST['old']);
                $tos = isset($_POST['tos']);

                if (!username_exist($db, $firstname, $lastname))
                {
                    if (!email_exist($db, $email))
                    {
                        if ($model <> "")
                        {
                            if ($old <> 0)
                            {
                                if ($tos <> 0)
                                {
                                    $uuid = generate_uuid();
                                    $username = $firstname." ".$firstname;

                                    create_useraccount($db, $uuid, $firstname, $lastname, $email);
                                    create_userauth($db, $uuid, $password);
                                    create_griduser($db, $uuid);
                                    create_inventory($db, $uuid);
                                    create_appearance($db, $uuid, $model);

                                    $_SESSION['flash']['success'] = $txt_ua_successfully_alert." <strong>".$username."</strong> ...";

                                    if ($send_mail_to_user === TRUE)
                                        send_email_to_user($uuid, $firstname, $lastname, $password, $email, $title);
                                    if ($send_mail_to_admin === TRUE)
                                        send_email_to_admin($uuid, $firstname, $lastname, $email, $title, $webmaster_email);

                                    header("Location: ?page=login");
                                    exit;
                                }
                                else {$_SESSION['flash']['danger'] = $txt_ua_accepttos_alert." ...";}
                            }
                            else {$_SESSION['flash']['danger'] = $txt_ua_18yearsold_alert." ...";}
                        }
                        else {$_SESSION['flash']['danger'] = $txt_ua_choiceavatar_alert." ...";}
                    }
                    else {$_SESSION['flash']['danger'] = $txt_ua_emailused_alert." ...";}
                }
                else {$_SESSION['flash']['danger'] = $txt_ua_nameused_alert." ...";}
            }
            else {$_SESSION['flash']['danger'] = $txt_ua_passworerror_alert." ...";}
        }
        else {$_SESSION['flash']['danger'] = $txt_ua_registrationoff_alert." ...";}
    }
    else {$_SESSION['flash']['danger'] = $txt_ua_emptyfield_alert." ...";}
}
?>

<form class="form form-horizontal form-register" role="form" action="?page=register" method="post">
    <?php if ($invisible_antispam === TRUE): ?>
        <input type="hidden" name="robots" value="">
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <span class="text-muted"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> <?php echo $txt_ua_requieredfields; ?></span>
            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
                </span>
                <input type="text" name="firstname" class="form-control" placeholder="<?php echo $txt_firstname; ?>" required autofocus>
            </div>

            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
                </span>
                <input type="text" name="lastname" class="form-control" placeholder="<?php echo $txt_lastname; ?>" required>
            </div>

            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
                </span>
                <input type="email" name="email" id="register_email" class="form-control" placeholder="<?php echo $txt_email; ?>" autocomplete="new-email" required>
            </div>

            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                </span>
                <input type="password" name="password" id="register_password" class="form-control" placeholder="<?php echo $txt_password; ?>" required>
                <div class="input-group-addon">
                    <span toggle="#register_password" class="glyphicon glyphicon-eye-open toggle-register-password"></span>
                </div>
            </div>

            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                </span>
                <input type="password" name="repeat" id="register_repeat" class="form-control" placeholder="<?php echo $txt_repeatpass; ?>" required>
                <div class="input-group-addon">
                    <span toggle="#register_repeat" class="glyphicon glyphicon-eye-open field-icon toggle-register-repeat"></span>
                </div>
            </div>

            <div class="padding-3"></div>

            <?php
            echo '<select class="form-control" id="model" name="model">';
            echo '<option value="0" selected disabled hidden>'.$txt_selectavatar.' ...</option>';

            foreach($models AS $uuid => $name)
            {
                echo '<option value="'.$uuid.'">'.$name.'</option>';
            }
            echo '</select>';

            foreach($models AS $uuid => $name)
            {
                $file = 'img/models/'.$uuid.'.jpg';
                if (!file_exists($file)) {$uuid = $uuid_zero;} // else {;}
                echo '<div class="model model_hide model_'.$uuid.' text-center">';
                echo '<img class="img img-responsive img-thumbnail" src="'.$file.'" alt="'.$name.'" title="'.$name.'" />';
                echo '</div>';
            }
            ?>

            <div class="checkbox">
                <input type="checkbox" id="old" name="old" class="styled" value="">
                <label for="old"><?php echo $txt_ua_im18yearsold; ?></label>
            </div>

            <div class="checkbox">
                <input type="checkbox" id="tos" name="tos" class="styled" value="">
                <label for="tos"><?php echo $txt_ua_imunderstood; ?> 
                    <a href="#" data-toggle="modal" data-target="#terms">
                        <?php echo $txt_ua_tos; ?>
                    </a>
                </label>
            </div>

            <button class="btn btn-success btn-block btn-padding-top" type="submit" name="create">
                <i class="glyphicon glyphicon-ok"></i> <?php echo $txt_register; ?>
            </button>

            <a class="btn btn-primary btn-block btn-padding-top" href="?page=login">
                <i class="glyphicon glyphicon-log-in"></i> <?php echo $txt_login; ?>
            </a>
        </div>
        <div class="panel-footer"></div>
    </div>
</form>

<!--Terms of Use Modal-->
<div class="modal fade" id="terms" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $title. " v" .$version.": ".$txt_ua_tos; ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $file = file_get_contents('tos.txt', true);
                echo '<pre>'.$file.'</pre>';
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
