<?php

    session_start();
    $nonav = "";

    if(isset($_SESSION['email']))
    {
        header('location: index.php');
    }

    include 'init.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        $email = $_POST['email'];
        $pass  = $_POST['pass'];
        
        $stmt1 = $conn->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
        $stmt1->execute(array($email, $pass));
        $row = $stmt1->fetch();
        $count = $stmt1->rowCount();

        if($count > 0)
        {
            $_SESSION['email'] = $email;
            $_SESSION['user-id'] = $row['id'];

            header('location: index.php');
        }    
    }
?>

<section class="section-login-page">
<div class="container">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="form-login">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="pass" placeholder="Password">
                </div>
                <div class="form-group">
                    <button type="submit" class="form-control login-btn">دخول</button>
                </div>
            </form>
            <div class="text-center"><a style="text-decoration:none;font-size:17px;color:#000" href="sign-up.php">انشاء حساب جديد</a></div>
        </div>
    </div>
</div>
</section>