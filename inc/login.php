<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if(isset($_SESSION['valid'])): ?>
    <?php $_SESSION['flash']['danger'] = "You are already log-in <strong>".$_SESSION['username']."</strong> ..."; ?>
    <?php header("Location: ?page=home"); exit(); ?>
<?php endif; ?>
<h1><?php echo $txt_identify; ?> <i class="glyphicon glyphicon-lock pull-right"></i></h1>
<div class="clearfix"></div>

<?php
if (isset($_POST['login']))
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

    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password']))
    {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (filter_var($username, FILTER_VALIDATE_EMAIL))
        {
            $username = filter_var($username, FILTER_SANITIZE_EMAIL);

            if (email_exist($db, $username))
            {
                $buffer = get_userdatas_by_email($db, $username);
                $username = $buffer['FirstName']." ".$buffer['LastName'];
            }

            else 
            {
                $_SESSION['flash']['danger'] = $txt_ua_unknowemail_alert." ...";
                header("Location: ?page=home");
                exit();
            }
        }

        $buffer = explode(" ", $username);
        if (isset($buffer[0])) $firstname = $buffer[0];
        else $firstname = "Unknow Firstname";
        if (isset($buffer[1])) $lastname = $buffer[1];
        else $lastname = "Unknow Lastname";
        $username = $firstname." ".$lastname;

        $sql = $db->prepare("
            SELECT *
            FROM useraccounts
            WHERE FirstName = ?
            AND LastName = ?
        ");
        $sql->bindValue(1, $firstname, PDO::PARAM_STR);
        $sql->bindValue(2, $lastname, PDO::PARAM_STR);
        $sql->execute();

        if ($sql->rowCount() == 1)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $PrincipalID = $row['PrincipalID'];
                $Email = $row['Email'];
                $Created = $row['Created'];
                $UserLevel = $row['UserLevel'];
                $UserFlags = $row['UserFlags'];
                $UserTitle = $row['UserTitle'];
                $Active = $row['active'];
                                
                if ($PrincipalID <> "")
                {
                    $sql = $db->prepare("
                        SELECT *
                        FROM auth
                        WHERE UUID = ?
                    ");
                    $sql->bindValue(1, $PrincipalID, PDO::PARAM_STR);
                    $sql->execute();

                    if ($sql->rowCount() > 0)
                    {
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {
                            $passwordHash = $row['passwordHash'];
                            $passwordSalt = $row['passwordSalt'];
                        }

                        if ($passwordHash <> "")
                        {
                            $md5Password   = md5(md5($password).":".$passwordSalt);

                            if ($passwordHash == $md5Password)
                            {
                                $_SESSION['valid']      = TRUE;
                                $_SESSION['username']   = $username;
                                // $_SESSION['password']   = $password;
                                $_SESSION['password']   = "********";
                                $_SESSION['useruuid']   = $PrincipalID;
                                $_SESSION['email']      = $Email;
                                $_SESSION['created']    = $Created;
                                $_SESSION['userlevel']  = $UserLevel;
                                $_SESSION['userflags']  = $UserFlags;
                                $_SESSION['usertitle']  = $UserTitle;
                                $_SESSION['active']     = $Active;

                                $_SESSION['flash']['success'] = $txt_ua_loginsuccess_alert." <strong>".$username."</strong>";

                                /* TODO
                                if ($_POST['remember'])
                                {
                                    $remember_token = str_random(255);
                                    $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->id]);
                                    setcookie('remember', $user->id . '==' . $remember_token, sha1($user->id . 'owigrid'), time() + 60 * 60 * 24 * 7);
                                }
                                */

                                if ($_POST['remember'])
                                {
                                    setcookie($cookie_name, 'osregister_remember_me', time() + $cookie_time);
                                }

                                header("Location: ?page=home");
                                exit();
                            }
                            else $_SESSION['flash']['danger'] = $txt_ua_wrongpass_alert." ...";
                        }
                        else $_SESSION['flash']['danger'] = $txt_ua_invalidpass_alert." ...";
                    }
                    else $_SESSION['flash']['danger'] = $txt_ua_idpassnomatch_alert." ...";
                }
                else $_SESSION['flash']['danger'] = $txt_ua_invalidid_alert." ...";
            }
        }
        else $_SESSION['flash']['danger'] = $txt_ua_invalidusername_alert." ...";
    }
}
?>

<?php if(!isset($_POST['login']) || (!isset($_SESSION['valid']) && isset($_POST['login']))): ?>
<form class="form form-horizontal form-login" action="?page=login" method="post">
    <?php if ($invisible_antispam === TRUE): ?>
        <input type="hidden" name="robots" value="">
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">
            <span class="text-muted"><i class="glyphicon glyphicon-info-sign" aria-hidden="true"></i> <?php echo $txt_ua_requieredfields; ?></span>
            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user"></i>
                </span>
                <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo $txt_username; ?>" required>
            </div>

            <div class="input-group btn-padding-top">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                </span>
                <input type="password" name="password" id="login_password" class="form-control" placeholder="<?php echo $txt_password; ?>" required>
                <div class="input-group-addon">
                    <span toggle="#login_password" class="glyphicon glyphicon-eye-open  toggle-login-password"></span>
                </div>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="remember" value="1" id="remember">
                <label for="remember"> <?php echo $txt_rememberme; ?></label>
                <a class="pull-right" href="?page=forgetpass">
                    <?php echo $txt_forgetpass; ?>
                </a>
            </div>

            <button class="btn btn-success btn-block btn-padding-top" type="submit" name="login">
                <i class="glyphicon glyphicon-log-in"></i> <?php echo $txt_login; ?>
            </button>
            
            <a class="btn btn-primary btn-block btn-padding-top" href="?page=register">
                <i class="glyphicon glyphicon-ok"></i> <?php echo $txt_register; ?>
            </a>
        </div>
        <div class="panel-footer"></div>
    </div>
</form>
<?php endif; ?>
