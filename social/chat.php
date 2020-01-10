<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        $id = isset($_GET['friend']) && is_numeric($_GET['friend']) ? intval($_GET['friend']) : 0;
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        $sender  = $_SESSION['user-id'];
        $reciver = isset($_GET['friend']) && is_numeric($_GET['friend']) ? intval($_GET['friend']) : 0;
        if($sender > $reciver)
            $chatid  = $sender.$reciver;
        else
            $chatid  = $reciver.$sender;

        if($_SERVER['REQUEST_METHOD'] == 'POST'):

            $message = $_POST['message'];
            
            if(!empty($message))
            {
                $stmt2 = $conn->prepare("INSERT INTO `chats` (`chat_id`, `sender`, `reciver`, `message`, `time`, `date`) 
                                         VALUES (:chatid, :sender, :reciver, :msg, now(), now())");
                $stmt2->execute(array(
                    'sender'  => $sender,
                    'reciver' => $reciver,
                    'msg'     => $message,
                    'chatid'  => $chatid
                ));
            }

        endif;

        $stmt3 = $conn->prepare("SELECT * FROM users U INNER JOIN chats C ON U.id = C.sender WHERE C.chat_id = '$chatid' ORDER BY C.id_inc ");
        $stmt3->execute();
        $row2 = $stmt3->fetchAll();

    }else{
        header('location: login.php');
    }
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                <a href="profile.php?user-id=<?php echo $id ?>"><p><?php echo ucfirst($row['fname']) . ' ' . ucfirst($row['lname']) ?></p></a>
                </div>
                <div class="panel-body cha">
                    <script>
                        setInterval(function(){
                            $("#Success").load('inc/chat/referchChat.php?friend=<?php echo $reciver ?>');
                        }, 2000);
                    </script>
                    <div class="chat" id="Success">
                        <?php 
                            foreach($row2 as $r) {

                            $stmt = $conn->prepare("UPDATE chats SET seen = 1 WHERE sender = ".$r['sender']." AND reciver = ".$_SESSION['user-id']." ");
                            $stmt->execute();

                            if($r['id'] == $_SESSION['user-id']) {
                        ?>
                        <div class="media by-me">
                            <?php
                                $img_session = $_SESSION['user-id'];
                                $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = '$img_session' ORDER BY avatar_id DESC");
                                $stmt->execute();
                                $pic = $stmt->fetch();
                            ?>
                            <div class="pull-left pull-top">
                                <?php if(!empty($pic['avatar_name'])) { ?>
                                    <img class="media-object" src="upload/avatar/<?php echo $pic['avatar_name'] ?>" style="height:30px;width:30px">
                                <?php } else { ?>
                                    <img class="media-object" src="upload/avatar/user-profile-default.png" style="height:30px;width:30px">
                                <?php } ?>
                            </div>
                            <div class="media-body content">
                                <div class="media-heading">
                                    <div class="info"><span><?php echo ucfirst($r['fname']) ?></span><span class="pull-right" style="text-align:right"><?php echo $r['time'] ?></span></div>
                                    <div class="msg"><?php echo $r['message'] ?></div>
                                </div>
                            </div>
                        </div> <!-- End by-me -->
                        
                        <?php 
                            } else {
                        ?>

                        <div class="media by-other">
                            <?php
                                $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC ");
                                $stmt->execute();
                                $pic = $stmt->fetch();
                            ?>
                            <div class="pull-right pull-top">
                                <?php if(!empty($pic['avatar_name'])) { ?>
                                    <img class="media-object" src="upload/avatar/<?php echo $pic['avatar_name'] ?>" style="height:30px;width:30px">
                                <?php } else { ?>
                                    <img class="media-object" src="upload/avatar/user-profile-default.png" style="height:30px;width:30px">
                                <?php } ?>
                            </div>
                            <div class="media-body content">
                                <div class="media-heading">
                                    <div class="info"><span><?php echo $r['time'] ?></span><span class="pull-right" style="text-align:right"><?php echo ucfirst($r['fname']) ?></span></div>
                                    <div class="msg pull-right"><?php echo $r['message'] ?></div>
                                </div>
                            </div>
                        </div> <!-- End by-other -->

                        <?php } } ?>

                    </div> <!-- End Chat -->
                </div> <!-- End panel-body -->

                <div class="form-sending">
                    <form class="form-inline" method="post" id="Chat">
                        <input type="hidden" value="<?php echo $reciver ?>" name="friend">
                        <div class="form-group col-xs-10 col-sm-11">
                            <input type="text" name="message" class="form-control" placeholder="...اكتب رسالة">
                        </div>
                        <div class="bt">
                            <button type="submit" class="btn btn-info">ارسال</button>
                        </div>
                    </form>
                </div> <!-- End form-sending -->
            </div>
        </div>
        
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>الصورة الشخصية</p>
                </div>
                <div class="panel-body">
                    <?php
                        $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC");
                        $stmt->execute();
                        $pic = $stmt->fetch();
                    ?>
                    <?php if(!empty($pic['avatar_name'])) { ?>
                        <img class="media-object" src="upload/avatar/<?php echo $pic['avatar_name'] ?>" style="height:250px;width:100%">
                    <?php } else { ?>
                        <img class="media-object" src="upload/avatar/user-profile-default.png" style="height:250px;width:100%">
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
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>معرض الصور</p>
                </div>
                <div class="panel-body"></div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3 info">
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
</div>

<?php
    include $tmbl . 'footer.php';
?>