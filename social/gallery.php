<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';
    }else{
        header('location: login.php');
    }
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>الصور الشخصية</p>
                </div>
                <div class="panel-body">
                    <?php
                        $id = isset($_GET['uid']) && is_numeric($_GET['uid']) ? intval($_GET['uid']) : 0;

                        $stmt = $conn->prepare("SELECT * FROM avatars WHERE user_id = '$id' ORDER BY AVATAR_ID DESC");
                        $stmt->execute();
                        $row = $stmt->fetchAll();

                        foreach($row as $r) {
                    ?>
                        <div class="col-sm-6 col-md-3" style="padding-right:5px;padding-left:5px">
                            <img src="upload/avatar/<?php echo $r['avatar_name'] ?>" class="img-responsive" style="height:250px;width:100%;margin-bottom:5px">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include $tmbl . 'footer.php';
?>