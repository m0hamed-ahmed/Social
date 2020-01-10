<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';
    }else{
        header('location: login.php');
    }

?>

<section class="container">
    <div class="row">

        <div class="col-xs-12 col-md-3">
            <?php include $tmbl . 'ads.php'; ?>
        </div>

        <div class="col-xs-12 col-md-9">
            <?php include $tmbl . 'search-control.php'; ?>
        </div>

        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <?php include $tmbl . 'view-users.php'; ?>
        </div>

        <div class="col-xs-12 col-md-3">
            <?php include $tmbl . 'buttons.php'; ?>
        </div>
    </div>
</section>

<?php include  $tmbl . '/footer.php'; ?>