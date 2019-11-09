<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 1/23/2016
 * Time: 8:13 PM.
 */
?>
<script>
$(function() {
    $('.password_repeat').change(function(e){

       if($(this).val() != $('.password').val())
       {
           $('.pass-check').addClass('has-error');
       }else{
           $('.pass-check').removeClass('has-error');
       }
    });
});
</script>
<div class="alpha-container container-fluid">
<?php echo $header; ?>
<div class="container col-lg-10" style="margin-right: 0; background-color: white; margin-top: 1em; height: 65em">
    <br>
    <?php
    $_fl = goat::$app->getFlash('_account_error');
    if (! empty($_fl) && isset($_fl['Error'])) {
        echo '<div class="alert alert-danger" role="alert">'.$_fl['Error'].'</div>';
    } elseif (! empty($_fl) && isset($_fl['Success'])) {
        echo '<div class="alert alert-success" role="alert">'.$_fl['Success'].'</div>';
    }
    ?>
    <div class="group-box" style="height: auto">
        <form action="account" method="post">
            <span>Username :</span>
            <input class="form-control" name="username" type="text" placeholder="Username" value="<?php echo $user['username']; ?>" style="width: 100%">
            <br>
            <span>Password :</span>
            <input class="form-control password" name="password" type="password" placeholder="" value="" style="width: 100%">
            <br>
            <span>Repeat Password :</span>
            <div class="pass-check">
                <input class="form-control password_repeat" name="password_repeat" type="password" placeholder="" value="" style="width: 100%">
            </div>
            <br>
            <button type="submit" class="btn btn-default">Change</button>
        </form>
    </div>
</div>
</div>
