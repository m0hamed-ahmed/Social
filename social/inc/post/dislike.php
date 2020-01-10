<?php

    include '../../connection.php';

    $postId = isset($_GET['postId']) && is_numeric($_GET['postId']) ? intval($_GET['postId']) : 0;
    $userId = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;

    $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id =? ");
    $delete->execute(array($userId, $postId));

?>

    <a onclick="toggleLike('inc/post/like.php?postId=<?php echo $postId ?>&userId=<?php echo $userId ?>','<?php echo $postId ?>')" class="btn btn-default"><i class="fa fa-thumbs-o-up"></i> like</a>
