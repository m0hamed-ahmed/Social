<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        $id = $_SESSION['user-id'];
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute(array($id));
        $row = $stmt->fetch();

    }else{
        header('location: login.php');
    }
?>

<div class="container">
    <div class="col-xs-12 col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p>المنشورات</p>
            </div>
            <div class="panel-body">
                
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p>الصورة الشخصية</p>
            </div>
            <div class="panel-body">
                <a href="upload-avatar.php" class="upload-avatar">
                    <p>تحميل صورة شخصية</p>
                </a>

                <?php 
                    $id =  $_SESSION['user-id'];
                    $stmt2 = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC");
                    $stmt2->execute();
                    $row2 = $stmt2->fetch();
                ?>

                <?php  if(empty($row2['avatar_name'])) { ?>
                    <img src="upload/avatar/user-profile-default.png" alt="photo" class="img-responsive img-thumbnail">
                <?php }else{ ?>
                    <img src="upload/avatar/<?php echo $row2['avatar_name'] ?>" alt="photo" class="img-responsive img-thumbnail">
                <?php } ?>

                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default"><i class="fa fa-user-plus fa-lg"></i></button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default"><i class="fa fa-comments fa-lg"></i></button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default"><i class="fa fa-heart fa-lg"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-3 col-md-offset-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p>معرض الصور</p>
            </div>
            <div class="panel-body">
                <?php 
                    $id = $_SESSION['user-id'];
                    $stmt3 = $conn->prepare("SELECT * FROM avatars WHERE user_id = '$id' ORDER BY AVATAR_ID DESC LIMIT 4");
                    $stmt3->execute();
                    $row3 = $stmt3->fetchAll();

                    foreach($row3 as $r) {
                    ?>
                        <div class="col-xs-12 col-md-6" style="padding-right:5px;padding-left:5px;padding-bottom:5px">
                            <img src="upload/avatar/<?php echo $r['avatar_name'] ?>" class="img-responsive" style="height:100px;width:100%">
                        </div>
                    <?php } ?>
                    <a href="gallery.php?uid=<?php echo $id ?>" class="col-xs-12 text-center">Show More</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-3 col-md-offset-9 info">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p>المعلومات الشخصية</p>
            </div>
            <div class="panel-body">
                <h4><strong><?php echo ucfirst($row['fname']) . ' ' . ucfirst($row['lname'])  ?></strong></h4>
                <ul class="list-unstyled">
                    <li><span>السن</span> | <span><?php echo $row['age'] ?></span></li>
                    <li><span>المدينة</span> | <span><?php echo $row['town'] ?></span></li>
                    <li><span>الحالةالاجتماعية</span> | <span><?php echo $row['relstatus'] ?></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
    include $tmbl . 'footer.php';
?>