<?php
    
    session_start();
    include '../../connection.php';

    $friend = isset($_GET['friend']) && is_numeric($_GET['friend']) ? intval ($_GET['friend']) : 0;
    $my_id = $_SESSION['user-id'];

    if($my_id > $friend)
        $chat_id = $my_id.$friend;
    else
        $chat_id = $friend.$my_id;

    $stmt = $conn->prepare("SELECT * FROM users U INNER JOIN chats C ON U.id = C.sender WHERE C.chat_id = $chat_id ORDER BY C.id_inc ");
    $stmt->execute();
    $all_msg = $stmt->fetchAll();
 
     foreach($all_msg as $msg) {
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