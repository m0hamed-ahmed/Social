<div class="navbar-h">
    <nav class="navbar navbar-default container">
        <div class="row">
            <div class="container-fluid">
                <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Social</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <form action="search-name.php" method="post" class="navbar-form navbar-left">
                    <div class="form-group">
                    <input type="text" name="pharse" class="form-control" placeholder="ابحث عن طريق الاسم او الايميل">
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> بحث</button>
                </form>
                <script>
                        setInterval(function(){
                            $("#counter").load('inc/chat/referchCounter.php');
                        }, 100);
                    </script>
                <?php

                $my_session = $_SESSION['user-id'];
                $stmt = $conn->prepare("select * from chats WHERE reciver = $my_session AND seen = 0 ");
                $stmt->execute();

                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="inbox.php"><i class="fa fa-comments"></i> <span id="counter" class="badge"><?php if($stmt->rowCount() > 0) {echo $stmt->rowCount();} ?></span> الرسائل</a></li>
                    <li><a href="myprofile.php"><i class="fa fa-user"></i> الصفحة الشخصية</a></li>
                    <li><a href="index.php"><i class="fa fa-home"></i> الصفحة الرئيسية</a></li>
                    <li class="dropdown">
                            <?php
                                $id =  $_SESSION['user-id'];

                                $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$id' ");
                                $stmt->execute();
                                $row = $stmt->fetch();

                                $stmt2 = $conn->prepare("SELECT * FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC limit 1");
                                $stmt2->execute();
                                $row2 = $stmt2->fetch();

                            ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php echo ucfirst($row['fname']) . ' ' . ucfirst($row['lname']) ?>
                            <?php  if(empty($row2['avatar_name'])){ ?>
                            <img src="upload/avatar/user-profile-default.png" alt="photo" style='width:30px;height:30px;border-radius:50%'>
                            <?php }else{ ?>
                            <img src="upload/avatar/<?php echo $row2['avatar_name'] ?>" alt="photo" style='width:30px;height:30px;border-radius:50%'>
                            <?php } ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="edit.php"> <i class="fa fa-edit"></i> تعديل الحساب</a></li>
                            <li><a href="myprofile.php"><i class="fa fa-user"></i> الصفحة الشخصية</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="logout.php"><i class="fa fa-sign-out"></i> تسجيل الخروج</a></li>
                        </ul>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </nav>
</div>