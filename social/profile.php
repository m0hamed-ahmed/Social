<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        $id = isset($_GET['user-id']) && is_numeric($_GET['user-id']) ? intval($_GET['user-id']) : 0;
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
            <div class="panel-body"></div>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <p>الصورة الشخصية</p>
            </div>
            <div class="panel-body">

            <?php 
                $id = isset($_GET['user-id']) && is_numeric($_GET['user-id']) ? intval($_GET['user-id']) : 0;

                $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC ");
                $stmt->execute();
                $img = $stmt->fetch();
            ?>
                <?php if(empty($img['avatar_name'])) { ?>
                    <img src="upload\avatar\user-profile-default.png" alt="photo" class="img-responsive img-thumbnail">
                <?php  } else { ?>
                    <img src="upload\avatar\<?php echo $img['avatar_name'] ?>" alt="photo" class="img-responsive img-thumbnail">
                <?php }?>

            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default"><i class="fa fa-user-plus fa-lg"></i></button>
                    </div>
                    <div class="btn-group" role="group">
                        <a href="chat.php?friend=<?php echo $id ?>"><button type="button" class="btn btn-default"><i class="fa fa-comments fa-lg"></i></button></a>
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
            <div class="panel-body"></div>
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