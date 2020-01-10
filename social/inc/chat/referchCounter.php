<?php

    session_start();
    include '../../connection.php';

    $my_session = $_SESSION['user-id'];
    $stmt = $conn->prepare("select * from chats WHERE reciver = $my_session AND seen = 0 ");
    $stmt->execute();

    if($stmt->rowCount() > 0)
    
        echo $stmt->rowCount();
