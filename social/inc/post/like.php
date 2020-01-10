<?php

    include '../../connection.php';

    $postId = isset($_GET['postId']) && is_numeric($_GET['postId']) ? intval($_GET['postId']) : 0;
    $userId = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;

    $insertLike = $conn->prepare("INSERT INTO `likes` (`user_id`, `post_id`)
                                  VALUES (:userId, :postId)");
    $insertLike->execute(array(
        'userId' => $userId,
        'postId' => $postId
    ));

?>

    <a onclick="toggleLike('inc/post/dislike.php?postId=<?php echo $postId ?>&userId=<?php echo $userId ?>','<?php echo $postId ?>')" class="btn btn-default"><i class="fa fa-thumbs-up"></i> Dislike</a>
