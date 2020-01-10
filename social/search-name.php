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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>الاعضاء</p>
                </div>
                <div class="panel-body">
                    <?php

                        if($_SERVER['REQUEST_METHOD'] == 'POST')
                        {
                            $pharse = $_POST['pharse'];

                            $stmt = $conn->prepare("SELECT *
                                                    FROM users
                                                    WHERE CONVERT(fname USING utf8) LIKE '%$pharse%'
                                                    OR CONVERT(lname USING utf8) LIKE '%$pharse%'
                                                    OR CONVERT(email USING utf8) LIKE '%$pharse%'");

                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                        }
                        else{
                            header('location: index.php');
                        }

                    ?>
                    <div class="row">
                        <?php 
                            foreach($rows as $row)
                            {
                                $uid =$row['id'];
                                $stmt = $conn->prepare("SELECT * FROM avatars WHERE user_id = $uid ORDER BY avatar_id DESC LIMIT 1");
                                $stmt->execute();
                                $img = $stmt->fetch();
                            
                        ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail">
                            <a href="profile.php">
                                <?php if(!empty($img['avatar_name'])) { ?>
                                    <img src="upload\avatar\<?php echo $img['avatar_name'] ?>" alt="photo" class="img-responsive" style="height:150px">
                                <?php } else { ?>
                                    <img src="upload\avatar\user-profile-default.png" alt="photo" class="img-responsive" style="height:150px">
                                <?php } ?>
                            </a>
                            <div class="caption text-center">
                                <h5><strong><?php echo $row['fname'] . ' ' . $row['lname']; ?></strong></h5>
                                <p><span> <?php echo $row['town']; ?> </span> | <span> <?php echo $row['age']; ?> </span></p>
                                <p>
                                    <a href="#" class="btn btn-primary" role="button"><i class="fa fa-user-plus"></i></a>
                                    <a href="#" class="btn btn-primary" role="button"><i class="fa fa-comments"></i></a>
                                </p>
                            </div>
                            </div>
                        </div>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-3">
            <?php include $tmbl . 'buttons.php'; ?>
        </div>
    </div>
</section>

<?php
    
    include  $tmbl . '/footer.php';

?>