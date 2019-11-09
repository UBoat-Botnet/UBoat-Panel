<script>
$(function() {
    $(".<?php echo $_route; ?>").addClass('active').siblings().removeClass('active');
});
</script>

<div class="col-lg-2">
    <div class="profile-sidebar">
        <div class="profile-userpic"><img alt="" class="img-responsive" src="<?=goat::$app->config['base_url'];?>/images/spectral_logo.png"></div>

        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                Spectral
            </div>
            <div class="profile-usertitle-job">
                Panel
            </div>
        </div>

        <div class="profile-userbuttons">
            <a class="btn btn-danger btn-sm" href="logout">Logout</a>
        </div>

        <div class="profile-usermenu">
            <ul class="nav">
                <li class="dashboard<?=goat::$app->getMatchedController() == 'dashboard' ? ' active' : '';?>">
                    <a href="dashboard"><i class="glyphicon glyphicon-globe"></i> Dashboard</a>
                </li>
                <li class="main<?=goat::$app->getMatchedController() == 'main' ? ' active' : '';?>">
                    <a href="main"><i class="glyphicon glyphicon-home"></i> Bots</a>
                </li>
                <li class="account<?=goat::$app->getMatchedController() == 'account' ? ' active' : '';?>">
                    <a href="account"><i class="glyphicon glyphicon-user"></i> Account Settings</a>
                </li>
                <li class="tasks<?=goat::$app->getMatchedController() == 'tasks' ? ' active' : '';?>">
                    <a href="tasks"><i class="glyphicon glyphicon-ok"></i> Tasks</a>
                </li>
            </ul>
        </div>
    </div>
</div>
