<?php
$_route = explode('/', $route);
$_route = end($_route);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <title>Spectral</title>
</head>
<body>

<script>
    $(function() {
        $(".<?php echo $_route; ?>").addClass('active').siblings().removeClass('active');
    });
</script>

<div class="col-lg-2">
    <div class="profile-sidebar">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
            <img src="<?php echo WEB_DIR; ?>/images/spectral_logo.png" class="img-responsive" alt="">
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                Spectral
            </div>
            <div class="profile-usertitle-job">
                Panel
            </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons">
            <a href="logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
            <ul class="nav">
                <li class="dashboard">
                    <a href="dashboard">
                        <i class="glyphicon glyphicon-globe"></i>
                        Dashboard </a>
                </li>
                <li class="main active">
                    <a href="main">
                        <i class="glyphicon glyphicon-home"></i>
                        Bots </a>
                </li>
                <li class="account">
                    <a href="account">
                        <i class="glyphicon glyphicon-user"></i>
                        Account Settings </a>
                </li>
                <li class="tasks">
                    <a href="tasks">
                        <i class="glyphicon glyphicon-ok"></i>
                        Tasks </a>
                </li>
            </ul>
        </div>
        <!-- END MENU -->
    </div>
</div>