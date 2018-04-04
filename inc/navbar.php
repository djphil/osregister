<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
    <nav class="<?php echo $CLASS_NAVBAR; ?>">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">
                    <i class="glyphicon glyphicon-th-large"></i> <strong>LOGO</strong>
                </a>
            </div>

            <div id="navbar" class="collapse navbar-collapse">
                <ul class="<?php echo $CLASS_NAV; ?>">
                    <li <?php if (isset($_GET['page']) && $_GET['page'] == "home") {echo 'class="active"';} ?>>
                        <a href="./?page=home"><i class="glyphicon glyphicon-home"></i> 
                            <?php echo $menu_home; ?>
                        </a>
                   </li>
                    <li <?php if (isset($_GET['page']) && $_GET['page'] == "help") {echo 'class="active"';} ?>>
                        <a href="./?page=help"><i class="glyphicon glyphicon-education"></i> 
                            <?php echo $menu_help; ?>
                        </a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right"> 
                    <li><a href="?lang=fr">Fr</a></li>
                    <li><a href="?lang=en">En</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-lr" style="padding: 15px; padding-bottom: 15px;">
                        <?php if (!isset($_SESSION['valid'])): ?>
                            <form class="form-horizontal form-dropdown-login" action="?page=login" method="post" accept-charset="UTF-8">
                                <?php if ($invisible_antispam === TRUE): ?>
                                    <input type="hidden" name="robots" value="">
                                <?php endif; ?>

                                <div class="input-group dropdown-login">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-user"></i>
                                    </span>
                                    <input type="text" class="form-control " name="username" placeholder="Username">
                                </div>
                                <div class="input-group dropdown-login">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-lock" aria-hidden="true"></i>
                                    </span>
                                    <input type="password" name="password" id="dropdown_password" class="form-control" placeholder="<?php echo $txt_password; ?>" required>
                                    <div class="input-group-addon">
                                        <span toggle="#dropdown_password" class="glyphicon glyphicon-eye-open toggle-dropdown-password"></span>
                                    </div>
                                </div>
                                <button class="btn btn-default btn-block" type="submit" name="login">
                                    <i class="glyphicon glyphicon-log-in"></i> <?php echo $txt_login; ?>
                                </button>
                            </form>
                            <div class="clearfix"></div>
                        <?php else: ?>

                            <div class="input-group dropdown-login">
                                <p class="welcome"><?php echo $txt_welcome; ?> <i class="glyphicon glyphicon-user"></i> <?php echo $_SESSION['username']; ?></p>
                            </div>

                            <li <?php if (isset($_GET['account'])) {echo 'class="active"';} ?>>
                                <a href="./?page=account"><i class="glyphicon glyphicon-cog"></i> <?php echo $txt_myaccount; ?></a>
                            </li>

                            <li class = "divider"></li>
                            <form class="form-horizontal" action="?page=logout" method="post" accept-charset="UTF-8">
                                <button class="btn btn-default btn-block" type="submit" name="logout">
                                    <i class="glyphicon glyphicon-log-out"></i> <?php echo $txt_logout; ?>
                                </button>
                            </form>
                            <div class="clearfix"></div>

                        <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
