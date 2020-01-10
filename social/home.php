<?php

    session_start();
    if($_SESSION['user-id'])
    {
        include 'init.php';
        
        if(isset($_POST['btnPost'])):
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $id        = $_SESSION['user-id'];
                $posts     = $_POST['posts'];
                $date      = date("Y M,y");
                $time      = date("H:i");
                $dayOfYear = date("Ymd");

                $stmt = $conn->prepare("INSERT INTO `posts` (`user_id`, `content`, `date`, `time`, `day`)
                                        VALUES (:userId, :content, :date, :time, :dayOfYear)");
                $stmt->execute(array(
                    'userId'    => $id,
                    'content'   => $posts,
                    'date'      => $date,
                    'time'      => $time,
                    'dayOfYear' => $dayOfYear   
                ));
            }
        endif;

        if(isset($_POST['btnComment'])):
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $userid  = $_POST['userId'];
                $postid  = $_POST['postId'];
                $content = $_POST['content'];
                $date    = date("d/M,Y"); 
                $time    = date("h:i");

                $stmt = $conn->prepare("INSERT INTO `comments` (`user_id`, `post_id`, `content`, `date`, `time`)
                                        VALUES (:userid, :postid, :content, :date, :time)");
                $stmt->execute(array(
                    'userid'  => $userid,
                    'postid'  => $postid,
                    'content' => $content,
                    'date'    => $date,
                    'time'    => $time
                ));
            }
        endif;

    } else {
        header('location: login.php');
    }

    $stmt = $conn->prepare("SELECT * FROM posts ORDER BY post_id DESC");
    $stmt->execute();
    $row = $stmt->fetchAll();

?>

<div class="container">

    <div class="row">
        <div class="hidden-xs col-sm-4">
            <?php include $tmbl . 'ads.php' ?>
        </div>
        <div class="col-sm-8">
            <div>
                <form method="post">
                    <div class="form-group">
                        <textarea class="form-control" name="posts" rows=10></textarea>
                        <button type="submit" name="btnPost" class="btn btn-primary btn-lg btn-block"><i class="fa fa-edit"></i>اضافة منشور</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <?php 
            foreach($row as $r) {
                $usid = $r['user_id'];
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = '$usid' ");
                $stmt->execute();
                $userName = $stmt->fetch();

                // (Error) Calculate The Date Of Post 
                $postDate = $r['day'];
                $today    = date("Ymd");
                $diff     = $today - $postDate;
                if ($diff == 0)
                    $pdate = 'اليوم';
                else if ($diff == 1)
                    $pdate = 'امس';
                else if ($diff == 2)
                    $pdate = 'منذ يومين';
                else if ($diff > 2){
                    if ($diff == 7)
                        $pdate = 'منذ اسبوع';
                    else if ($diff == 14)
                        $pdate = 'منذ اسبوعين';
                    else if ($diff == 21)
                        $pdate - 'منذ ثلاث اسابيع';
                    else if ($diff == 30)
                        $pdate = 'منذ شهر';
                    else
                        $pdate = 'منذ ' . $diff . ' ايام';
                }


                $id_us = $r['user_id'];
                $stmt  = $conn->prepare("SELECT * FROM avatars WHERE user_id = $id_us ORDER BY avatar_id DESC");
                $stmt->execute();
                $post_img = $stmt->fetch();

        ?>

        <div class="col-sm-8 col-sm-offset-4 full-post">

            <div class="media"> <!-- post  -->
                <div class="pull-left">
                    <a href="profile.php?user-id=<?php echo $post_img['user_id'] ?>">
                        <?php if(empty($post_img['avatar_name'])) { ?>
                            <img class="media-object" src="upload/avatar/user-profile-default.png" alt="photo" style="height:40px;width:40px">
                        <?php  } else { ?>
                            <img class="media-object" src="upload/avatar/<?php echo $post_img['avatar_name'] ?>" alt="photo" style="height:40px;width:40px">
                        <?php } ?>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><?php echo ucfirst($userName['fname']) . ' ' .  ucfirst($userName['lname']) ?><span class="time"><?php echo $pdate . ' ' . $r['time'] ?></span></h4>
                    <p><?php echo $r['content'] ?></p>
                </div>
            </div>

            <hr style="border:1px solid #ccc">
            <?php
                $stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = ?");
                $stmt->execute(array($r['post_id']));
                $num_likes = $stmt->rowCount();
            ?>
            <?php if($num_likes > 0) { ?>
            <p style="direction:rtl"><?php echo $num_likes ?> اشخاص اعجبوا بهذا المنشور</p>
            <?php } ?>
            <div class="btn-group btn-group-justified" role="group" aria-label="..."> <!-- buttons  -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default"><i class="fa fa-share"></i> مشاركة</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default btn-comm"><i class="fa fa-comments"></i> تعليق</button>
                </div>
                <div id="<?php echo $r['post_id'] ?>" class="btn-group" role="group">
                    <?php
                        $userId = $_SESSION['user-id'];
                        $postId = $r['post_id'];

                        $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id =?");
                        $stmt->execute(array($userId, $postId));
                        $countlike = $stmt->rowCount();
                    ?>
                    <?php if ($countlike > 0 ) {?>
                        <a onclick="toggleLike('inc/post/dislike.php?postId=<?php echo $r['post_id'] ?>&userId=<?php echo $_SESSION['user-id'] ?>','<?php echo $r['post_id'] ?>')" class="btn btn-default"><i class="fa fa-thumbs-up"></i> الغاء الاعجاب</a>
                    <?php } else { ?>
                        <a onclick="toggleLike('inc/post/like.php?postId=<?php echo $r['post_id'] ?>&userId=<?php echo $_SESSION['user-id'] ?>','<?php echo $r['post_id'] ?>')" class="btn btn-default"><i class="fa fa-thumbs-o-up"></i> اعجاب</a>
                    <?php } ?>
                    
                </div>
            </div>

            <div class="number_comment">
                <?php
                    $pidcomm = $r['post_id'];
                    $stmt = $conn->prepare("SELECT * FROM posts P INNER JOIN comments C ON P.post_id = C.post_id WHERE P.post_id = $pidcomm "); 
                    $stmt->execute();
                    $nmcomm = $stmt->fetchAll();
                    $numberComments = $stmt->rowCount();
                ?>
                <p class="numberComments"><?php echo $numberComments . ' Comments' ?></p>
            </div>

            <?php 
                $pid = $r['post_id'];
                $stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = $pid ");
                $stmt->execute();
                $comments = $stmt->fetchAll();
                $countComment = $stmt->rowCount();

                foreach($comments as $comment) {
                    
                    $uid  = $comment['user_id'];
                    $stmt = $conn->prepare("SELECT * FROM users WHERE id = $uid ");
                    $stmt->execute();
                    $usid = $stmt->fetch();

                    $stmt = $conn->prepare("SELECT * FROM avatars WHERE user_id = $uid ORDER BY avatar_id DESC");
                    $stmt->execute();
                    $ava = $stmt->fetch();
            ?>
            
            <div class="media"> <!-- comments  -->
                <div class="pull-left">
                <a href="profile.php?user-id=<?php echo $comment['user_id'] ?>">
                        <?php if(empty($ava['avatar_name'])) { ?>
                            <img class="media-object" src="upload/avatar/user-profile-default.png" alt="photo" style="height:30px;width:30px">
                        <?php } else { ?>
                            <img class="media-object" src="upload/avatar/<?php echo $ava['avatar_name'] ?>" alt="photo" style="height:30px;width:30px">
                        <?php  } ?>
                    </a>
                </div>
                <div class="media-body comment-body">
                    <h5 class="media-heading name-comment"><?php echo ucfirst($usid['fname']) . ' ' .  ucfirst($usid['lname']) ?></h5>
                    <p><?php echo $comment['content'] ?></p>
                </div>
            </div>

            <?php } ?>

            <hr class="hr-comment fc-hidd-vis" style="border:1px solid #ccc">

            <div class="form-comment fc-hidd-vis"> <!-- form comment  -->
                <form method="post">
                    <input type="hidden" name="userId" value="<?php echo $_SESSION['user-id'] ?>">
                    <input type="hidden" name="postId" value="<?php echo $r['post_id']  ?>">
                    <textarea class="form-control txt-comment" name="content" placeholder="...اكتب تعليقك هنا"></textarea><br>
                    <button type="submit" name="btnComment" class="form-control btn btn-primary">ارسال التعليق</button>
                </form>
            </div>

        </div>
        <?php } ?>
        
    </div>
</div>
<div style="margin-top:40px"></div>

<script>
    function toggleLike(page, id) {
        var myRequest;
        if(window.XMLHttpRequest) {
            myRequest = new XMLHttpRequest();
        } else {
            myRequest = new ActiveXobject("Microsoft.XMLHTTP");
        }

        myRequest.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
            }
        }
        myRequest.open("GET",page,true);
        myRequest.send();
    }
</script>

<?php 
    include $tmbl . 'footer.php';
?>