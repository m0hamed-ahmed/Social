<?php

    session_start();
    if($_SESSION['email'])
    {
        include 'init.php';

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $fname     = $_POST['fname'];
            $lname     = $_POST['lname'];
            $email     = $_POST['email'];
            $pass      = $_POST['pass'];
            $sex       = $_POST['sex'];
            $birthday  = $_POST['birthday'];
            $town      = $_POST['town'];
            $relstatus = $_POST['relstatus'];
            $id        = $_SESSION['user-id'];

            $stmt = $conn->prepare("UPDATE users SET fname = ?, lname = ?, email = ?, password = ?, sex = ?, birthday =?, town = ?, relstatus =? WHERE id = ?");
            $stmt->execute(array($fname, $lname, $email, $pass, $sex, $birthday, $town, $relstatus, $id));
        }

        $id = $_SESSION['user-id'];
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute(array($id));
        $row = $stmt->fetch();

    }else{
        header('location: login.php');
    }
?>

<div class="container">
    <div class=row>
        <div class="col-xs-12 col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>تعديل البيانات الشخصية</p>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="fname" class="form-control" value="<?php echo $row['fname'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">الاسم الاول</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="lname" class="form-control" value="<?php echo $row['lname'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">الاسم الثاني</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="email" class="form-control" value="<?php echo $row['email'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">البريد الالكتروني</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="password" name="pass" class="form-control" value="<?php echo $row['password'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">كلمة السر</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="sex" class="form-control" value="<?php echo $row['sex'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">النوع</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="birthday" class="form-control" value="<?php echo $row['birthday'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">العمر</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="town" class="form-control" value="<?php echo $row['town'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">المدينة</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <input type="text" name="relstatus" class="form-control" value="<?php echo $row['relstatus'] ?>">
                            </div>
                            <label class="col-xs-12 col-md-3">الحالة الاجتماعية</label>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-md-9">
                                <button type="submit" class="login-btn btn-block">حفظ</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

    include $tmbl . 'footer.php';

?>