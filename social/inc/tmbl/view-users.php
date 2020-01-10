<div class="panel panel-default">
    <div class="panel-heading">
        <p>الاعضاء</p>
    </div>
    <div class="panel-body">
        <?php
            $stmt = $conn->prepare('SELECT * FROM users');
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        <div class="row">
            <?php 
                foreach($rows as $row)
                {
                    $id_img = $row['id'];
                    $stmt = $conn->prepare("SELECT avatar_name FROM avatars WHERE user_id = $id_img ORDER BY avatar_id DESC");
                    $stmt->execute();
                    $img = $stmt->fetch();
            ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                <?php echo '<a href="profile.php?user-id='.$row['id'].' ">' ?>
                    <?php if(empty($img['avatar_name'])) { ?>
                        <img src="upload\avatar\user-profile-default.png" alt="photo" class="img-responsive" style="height:150px">
                    <?php  } else { ?>
                        <img src="upload\avatar\<?php echo $img['avatar_name'] ?>" alt="photo" class="img-responsive" style="height:150px">
                    <?php }?>
                </a>
                <div class="caption text-center">
                    <h5><strong><?php echo $row['fname'] . ' ' . $row['lname']; ?></strong></h5>
                    <p><span> <?php echo $row['town']; ?> </span> | <span> <?php echo $row['age']; ?> </span></p>
                    <p>
                        <a href="#" class="btn btn-primary" role="button"><i class="fa fa-user-plus"></i></a>
                        <a href="chat.php?friend=<?php echo $row['id'] ?>" class="btn btn-primary" role="button"><i class="fa fa-comments"></i></a>
                    </p>
                </div>
                </div>
            </div>
                <?php } ?>
        </div>
    </div>
</div>