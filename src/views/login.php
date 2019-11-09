<?php

/**
 * This file is part of UBoat - HTTP Botnet Project
 */

?>
<script>
    $(function(){
       $(".capcha").click(function(){
           location.reload();
       });
    });
</script>

<!-- Where all the magic happens -->
<!-- LOGIN FORM -->
<div class="text-center login-container" style="padding:50px 0">
    <?php
    if (goat::$app->config['display_login_error']) {
        $_fl = goat::$app->getFlash('_login_error');
        if (! empty($_fl)) {
            echo '<div class="alert alert-danger" role="alert">'.$_fl['Error'].'</div>';
        }
    }
    ?>
    <div class="logo"></div>
    <!-- Main Form -->

    <div class="login-form-1">
        <form id="login-form" class="text-left" method="post" action="">
            <div class="login-form-main-message"></div>
            <div class="main-login-form">
                <div class="login-group">
                    <div class="form-group">
                        <label for="lg_username" class="sr-only">Username</label>
                        <input type="text" class="form-control" id="lg_username" name="lg_username" placeholder="Username..">
                    </div>
                    <div class="form-group">
                        <label for="lg_password" class="sr-only">Password</label>
                        <input type="password" class="form-control" id="lg_password" name="lg_password" placeholder="Password..">
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-9">
                            <label for="capcha" class="sr-only">Capcha</label>
                            <input type="text" class="form-control" id="capcha" name="capcha" placeholder="Capcha..">
                        </div>
                        <div class="col-lg-3">
                            <img class="capcha" style="margin-top: 0.3em;" src='<?php echo 'data:image/png;base64, '.$cap->recap(); ?>'/>
                        </div>
                    </div>
                    <div class="form-group login-group-checkbox">
                        <input type="checkbox" id="lg_remember" name="lg_remember">
                        <label for="lg_remember">remember</label>
                    </div>
                </div>
                <button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="etc-login-form">
                <p>forgot your password? <a href="#">click here</a></p>
                <p>new user? <a href="#">create new account</a></p>
            </div>
        </form>
    </div>
    <!-- end:Main Form -->
</div>
