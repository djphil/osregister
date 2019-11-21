<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php if (!isset($_SESSION['valid'])) {header("Location: ?page=login"); exit();} ?>

<?php if(isset($_SESSION['valid'])): ?>
    <h1><?php echo $txt_myaccount; ?> <i class="glyphicon glyphicon-user pull-right"></i></h1>
    <p>
        <?php echo $txt_hello; ?> <strong><?php echo $_SESSION['username']; ?></strong>, 
        <?php echo strtolower($txt_welcometo); ?> <strong><?php echo $title.' v'.$version; ?></strong>
        <a class="btn btn-primary btn-xs pull-right" href="?page=account" title="Refresh">
        <i class="glyphicon glyphicon-refresh"></i></a>
    </p>
    <div class="clearfix"></div>
<?php endif; ?>

<?php
/*change email*/
if (isset($_POST['changeemail']))
{
    if (!empty($_POST['email']) && !empty($_POST['password']))
    {
        $password = $_POST['password'];

        if (verify_password($db, $_SESSION['useruuid'], $password) === 1)
        {
            change_email($db, $_SESSION['useruuid'], $_POST['email']);
        }
        else {$_SESSION['flash']['danger'] = "Invalid password, try again ...";}
    }
    else {$_SESSION['flash']['danger'] = "Empty field(s), try again ...";}
}

/*change password*/
if (isset($_POST['changepass'])) 
{
    if (!empty($_POST['password']) && !empty($_POST['newpass']) && !empty($_POST['repeat']))
    {
        $password = $_POST['password'];
        $newpass = $_POST['newpass'];
        $repeat = $_POST['repeat'];

        if ($newpass == $repeat && $newpass <> $password)
        {
            if (verify_password($db, $_SESSION['useruuid'], $password) === 1)
            {
                if (change_password($db, $_SESSION['useruuid'], $newpass) === 1)
                {
                    $_SESSION['flash']['success'] = "Your new password is <strong>".$newpass."</strong>";
                }
                else {$_SESSION['flash']['danger'] = "New password error, please contact webmaster ...";} 
            }
            else {$_SESSION['flash']['danger'] = "Invalid password, try again ...";}
        }
        else {$_SESSION['flash']['danger'] = "New password error, try again ...";}
    }
}

if (empty($_SESSION['username']) || 
    empty($_SESSION['email']) || 
    empty($_SESSION['password']) || 
    empty($_SESSION['created']) ||
    empty($_SESSION['useruuid']) ||
    // empty($_SESSION['userlevel']) ||
    // empty($_SESSION['userflags']) ||
    empty($_SESSION['usertitle']) ||
    empty($_SESSION['active'])) {
    header("Location: ?page=logout"); 
    exit();
}
?>

<?php if (isset($_SESSION['valid'])): ?>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-info-sign"></i> <?php echo $txt_ua_infos; ?>
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <!--<dt>...</dt><dd>...</dd>-->
                    <dt><i class="fa fa-user"></i> Usename:</dt>
                    <dd><p><?php echo $_SESSION['username'] ?></p></dd>

                    <dt><i class="fa fa-envelope"></i> Email:</dt>
                    <dd>
                        <p>
                            <?php echo $_SESSION['email'] ?>
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#changeemail" title="Change email">
                                <i class="glyphicon glyphicon-edit"></i> Change
                            </a>
                        </p>
                    </dd>

                    <dt><i class="fa fa-key"></i> Password:</dt>
                    <dd>
                        <p>
                            <?php echo $_SESSION['password'] ?>
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#changepass" title="Change password">
                                <i class="glyphicon glyphicon-edit"></i> Change
                            </a>
                        </p>
                    </dd>

                    <dt><i class="fa fa-gift"></i> Created:</dt>
                    <dd><p><?php echo date('d:m:Y \@ h:m:s', $_SESSION['created']) ?></p></dd>

                    <dt><i class="fa fa-heart"></i> Partner:</dt>
                    <dd>
                        <p>
                            None
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#invitepartner" title="Invite partner">
                                <i class="glyphicon glyphicon-heart-empty"></i> Invite
                            </a>
                        </p>
                    </dd>
                </dl>
            </div>
            <!--<div class="panel-footer"></div>-->
        </div>
    </div>

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-info-sign"></i> <?php echo $txt_ua_advanced; ?>
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt><i class="fa fa-tag"></i> Uuid:</dt>
                    <dd><p><?php echo $_SESSION['useruuid'] ?></p></dd>

                    <dt><i class="fa fa-leaf"></i> Level:</dt>
                    <dd><p><?php echo $_SESSION['userlevel'] ?></p></dd>

                    <dt><i class="fa fa-flag"></i> Flag:</dt>
                    <dd><p><?php echo $_SESSION['userflags'] ?></p></dd>

                    <dt><i class="fa fa-user-circle"></i> Title:</dt>
                    <dd><p><?php echo $_SESSION['usertitle'] ?></p></dd>

                    <dt><i class="fa fa-adn"></i> Active:</dt>
                    <dd><p><?php echo $_SESSION['active'] ?></p></dd>
                </dl>
            </div>
            <!--<div class="panel-footer"></div>-->
        </div>
    </div>
</div>

<?php if (true === false): ?>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-cog"></i> <?php echo $txt_additionaltools; ?>
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt><p><i class="fa fa-trash"></i> Trash:</p></dt>
                    <dd>
                        <p> 
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#purgetrash" title="Purge Trash">
                                <i class="fa fa-trash"></i> Purge
                            </a>
                        </p>
                    </dd>

                    <dt><p><i class="fa fa-eye"></i> Appearance:</p></dt>
                    <dd>
                        <p> 
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#purgeappearance" title="Purge Appearance">
                                <i class="fa fa-trash"></i> Purge
                            </a> 
                            <a class="btn btn-xs btn-default" data-toggle="modal" data-target="#changeappearance" title="Change Appearance">
                                <i class="fa fa-refresh"></i> Change
                            </a>
                        </p>
                    </dd>
                </dl>
            </div>
            <!--<div class="panel-footer"></div>-->
        </div>
    </div>
    <div class="col-sm-6"></div>
</div>
<?php endif; ?>

<!-- Modal Change Email -->
<div id="changeemail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-envelope"></i> Change email</h4>
            </div>
            <div class="modal-body">
                <form class="form-signin" role="form" action="?page=account" method="post">
                <div class="input-group btn-padding-top-off">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                    </span>
                    <input type="password" name="password" id="account_password" class="form-control" placeholder="Password" required>
                    <div class="input-group-addon">
                        <span toggle="#account_password" class="glyphicon glyphicon-eye-open toggle-account-password"></span>
                    </div>
                </div>

                <div class="input-group btn-padding-top">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
                    </span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="New Email Address" required >
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit" name="changeemail"><i class="glyphicon glyphicon-ok"></i> Change Email</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Change Password -->
<div id="changepass" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-key"></i> Change password</h4>
            </div>
            <div class="modal-body">
                <form class="form-changepass" role="form" action="?page=account" method="post">
                <div class="input-group btn-padding-top-off">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                    </span>
                    <input type="password" name="password" id="current_password" class="form-control" placeholder="<?php echo $txt_currentpass; ?>" required>
                    <div class="input-group-addon">
                        <span toggle="#current_password" class="glyphicon glyphicon-eye-open  toggle-current-password"></span>
                    </div>
                </div>

                <div class="input-group btn-padding-top">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                    </span>
                    <input type="password" name="newpass" id="new_password" class="form-control" placeholder="<?php echo $txt_newpass; ?>" required>
                    <div class="input-group-addon">
                        <span toggle="#new_password" class="glyphicon glyphicon-eye-open  toggle-new-password"></span>
                    </div>
                </div>

                <div class="input-group btn-padding-top">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                    </span>
                    <input type="password" name="repeat" id="repeat_password" class="form-control" placeholder="<?php echo $txt_repeatpass; ?>" required>
                    <div class="input-group-addon">
                        <span toggle="#repeat_password" class="glyphicon glyphicon-eye-open  toggle-repeat-password"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit" name="changepass"><i class="fa fa-check"></i> Change password</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal invite Partner -->
<div id="invitepartner" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-heart-empty"></i> Invite partner</h4>
            </div>
            <div class="modal-body">
                Coming soon ...
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit" name="changepass"><i class="fa fa-check"></i> Invite partner</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
