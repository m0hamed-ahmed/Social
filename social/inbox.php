<?php
    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        $friend = isset($_GET['friend']) && is_numeric($_GET['friend']) ? intval ($_GET['friend']) : 0;
        $my_id = $_SESSION['user-id'];

        if($my_id > $friend)
            $chat_id = $my_id.$friend;
        else
            $chat_id = $friend.$my_id;

        if($_SERVER['REQUEST_METHOD'] == 'POST'):
    
            $message = $_POST['message'];
            
            if(!empty($message))
            {
                $stmt = $conn->prepare("INSERT INTO `chats` (`chat_id`, `sender`, `reciver`, `message`, `time`, `date`) 
                                        VALUES (:chatid, :sender, :reciver, :msg, now(), now())");
                $stmt->execute(array(
                    'sender'  => $my_seesion,
                    'reciver' => $friend,
                    'msg'     => $message,
                    'chatid'  => $chat_id
                ));
            }
    
    
        endif;
            
        $stmt = $conn->prepare("SELECT * FROM users U INNER JOIN chats C ON U.id = C.sender WHERE C.chat_id = $chat_id ORDER BY C.id_inc ");
        $stmt->execute();
        $all_msg = $stmt->fetchAll();

    } else {
        header('location: login.php');
    }
?>

<div class="container">
    <div class="row">

        <div class="col-xs-12 col-md-9">
            <div class="left-chat"> <!-- Start Left -->
                <div class="panel panel-default"> <!-- Start Panel -->
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM users WHERE id = $friend ");
                        $stmt->execute();
                        $name = $stmt->fetch();
                    ?>
                    <div class="panel-heading">
                        <a href="profile.php?user-id=<?php echo $friend ?>"><p><?php echo ucfirst($name['fname']) . ' ' . ucfirst($name['lname']) ?></p></a>
                    </div>
                    <div class="panel-body cha"> <!-- Start panel-body -->
                        <script>
                            setInterval(function() {
                                $("#Success").load('inc/chat/referchChat.php?friend=<?php echo $friend ?>');
                            }, 2000);
                        </script>
                        <div class="chat" id="Success"> <!-- Start Chat -->
                            <?php 
                                foreach($all_msg as $msg) {

                                    $stmt = $conn->prepare("UPDATE chats SET seen = 1 WHERE sender = ".$msg['sender']." AND reciver = ".$_SESSION['user-id']." ");
                                    $stmt->execute();

                                 if($msg['sender'] == $_SESSION['user-id']) {
                            ?>
                            <div class="media by-me"> <!-- Start by-me -->
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
                                    <?php } ?>                                </div>
                                <div class="media-body content">
                                    <div class="media-heading">
                                        <div class="info"><span><?php echo ucfirst($msg['fname'])?></span><span class="pull-right" style="text-align:right"><?php echo $msg['time'] ?></span></div>
                                        <div class="msg"><?php echo $msg['message'] ?></div>
                                    </div>
                                </div>
                            </div> <!-- End by-me -->

                            <?php } else { ?>

                            <div class="media by-other"> <!-- Start by-other -->
                                <?php
                                    $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = $friend ORDER BY avatar_id DESC ");
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
                                        <div class="info"><span><?php echo $msg['time'] ?></span><span class="pull-right" style="text-align:right"><?php echo ucfirst($msg['fname'])?></span></div>
                                        <div class="msg pull-right"><?php echo $msg['message'] ?></div>
                                    </div>
                                </div>
                            </div> <!-- End by-other -->
                            <?php } } ?>

                        </div> <!-- End Chat -->
                    </div> <!-- End panel-body -->

                    <div class="form-sending"> <!-- Start form-sending -->
                        <form class="form-inline" method="post" id="Chat">
                            <input type="hidden" value="<?php echo $friend ?>" name="friend">
                            <div class="form-group col-xs-10 col-sm-11">
                                <input type="text" name="message" class="form-control" placeholder="...اكتب رسالة">
                            </div>
                            <div class="bt">
                                <button type="submit" class="btn btn-info">ارسال</button>
                            </div>
                        </form>
                    </div> <!-- End form-sending -->
                </div> <!-- End Panel -->
            </div>
        </div> <!-- End Left -->

        <?php
            $my_id = $_SESSION['user-id'];
            $stmt = $conn->prepare("SELECT DISTINCT(chat_id),sender,reciver FROM chats WHERE sender = $my_id ORDER BY id_inc DESC");
            $stmt->execute();
            $chats = $stmt->fetchAll();
        ?>
        <div class="col-xs-12 col-md-3">
            <div class="right-friends row"> <!-- Start Right -->
                <?php foreach($chats as $chat) { ?>
                <?php
                    $reci = $chat['reciver'];

                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = $reci ");
                    $stmt->execute();
                    $friend = $stmt->fetch();
                    
                    $stmt = $conn->prepare("SELECT * FROM avatars WHERE user_id = $reci ORDER BY avatar_id DESC limit 1");
                    $stmt->execute();
                    $img = $stmt->fetch();
                    
                    $stmt = $conn->prepare("SELECT message, time  FROM chats WHERE (sender = ".$my_id." AND reciver = ".$reci.") OR (sender = ".$reci." AND reciver = ".$my_id.") ORDER BY id_inc DESC");
                    $stmt->execute();
                    $last_msg = $stmt->fetch();
                ?>
                <a href="inbox.php?friend=<?php echo $reci ?>">
                    <div class="media">
                        <div class="pull-right">
                            <?php if(!empty($img['avatar_name'])) { ?>
                            <img src="upload/avatar/<?php echo $img['avatar_name'] ?>" style="height:60px;width:60px;border-radius:50%">
                            <?php } else { ?>
                            <img src="upload/avatar/user-profile-default.png" style="height:60px;width:60px;border-radius:50%">
                            <?php } ?>
                        </div>
                        <div class="media-body">
                            <p style="color:#777"><?php echo ucfirst($friend['fname']) . ' ' . ucfirst($friend['lname']) ?></p>
                            <?php if(strlen($last_msg['message']) > 20 ) { ?>
                                <p style="font-weight:bold"><?php echo substr($last_msg['message'],0,20) . '...' ?></p>
                            <?php } else { ?>
                                <p style="font-weight:bold"><?php echo $last_msg['message'] ?></p>
                            <?php } ?>
                            <p style="color:#777"><?php echo $last_msg['time'] ?></p>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div> <!-- End Right -->

    </div> <!-- End Row -->
</div> <!-- End Container -->

<?php include $tmbl . 'footer.php'; ?>