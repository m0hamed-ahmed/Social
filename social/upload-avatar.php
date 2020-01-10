<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name     = $_FILES['avatar']['name'];
            $type     = $_FILES['avatar']['type'];
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $size     = $_FILES['avatar']['size'];

            $allowed   = array('jpg', 'jpeg', 'png', 'gif');
            $explode   = explode('.',$name);
            $extension = strtolower(end($explode));

            if(!in_array($extension, $allowed)){
                $error =  "هذا الملف غير مدعوم";
            }else{
                $newname = rand(0,1000) . '_' . $name;
                move_uploaded_file($tmp_name, 'upload/avatar/' . $newname);

                $id = $_SESSION['user-id'];
                $stmt = $conn->prepare("INSERT INTO avatars (`avatar_name`, `avatar_date`, `user_id`) VALUES (:avatar_name, now(), :id)");
                $stmt->execute(array(
                    'avatar_name' => $newname,
                    'id' => $id
                ));

                if($stmt)
                    $success = "تم رفع الصورة بنجاح";
            }

        }

    }else{
        header('location: login.php');
    }

?>

<section class="container">
    <div class="row">
        <?php

            $id = $_SESSION['user-id'];
            $stmt = $conn->prepare("SELECT * FROM avatars WHERE user_id = '$id' ORDER BY avatar_id DESC limit 1");
            $stmt->execute();
            $row = $stmt->fetch();

        ?>
        <div class="col-xs-12 ">
            <div class="col-xs-4 col-xs-offset-4">
                <?php  if(empty($row['avatar_name'])){ ?>
                <img src="upload/avatar/user-profile-default.png" alt="photo" class="img-responsive img-thumbnail" style="width:100%">
                <?php }else{ ?>
                <img src="upload/avatar/<?php echo $row['avatar_name'] ?>" alt="photo" class="img-responsive img-thumbnail" style="width:100%">
                <?php } ?>
                <form actio="<?php echo $_SERVER['PHP-SELF'] ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="avatar" class="form-control">
                    <button type="submit" class="login-btn btn-block">رفع</button>
                </form>
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger text-center"> <?php echo $error ?> </div>
                <?php } ?>
                <?php if(isset($success)){ ?>
                    <div class="alert alert-success text-center"> <?php echo $success ?> </div>
                <?php } ?>
            </div>
        </div>

    </div>
</section>

<?php include  $tmbl . '/footer.php'; ?>