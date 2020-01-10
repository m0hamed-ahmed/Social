<?php

    session_start();
    $nonav = "";
    include 'init.php';

    if(isset($_SESSION['email']))
        header('location: index.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'):
    
        // Get Data From Form
        $fname     = $_POST['fname'];
        $lname     = $_POST['lname'];
        $sex       = $_POST['sex'];
        $email     = $_POST['email'];
        $pass1     = $_POST['pass1'];
        $pass2     = $_POST['pass2'];
        $town      = $_POST['town'];
        $relstatus = $_POST['relstatus'];

        // Calculate Age From Date Of Birth
        $birthday  = $_POST['birthday'];
        $today     = date("Y-m-d");
        $diff      = date_diff(date_create($birthday), date_create($today));
        $age       = $diff->format('%y');
        
        // Empty Array To Store Errors And Print Them Later
        $errorlist = array();
        
        // Check Error 1
        if(empty($fname) || empty($lname) || empty($sex) || empty($email) || empty($pass1) || empty($pass2) || empty($town) || empty($birthday) || empty($relstatus))
            $errorlist[] = 'برجاء عدم ترك ايا من الحقول فارغا';

        // Check Error 2
        $chkemail = $conn->prepare("SELECT email FROM users WHERE email = '$email' ");
        $chkemail->execute();
        $count = $chkemail->rowCount();
        if($count > 0)
            $errorlist[] = 'هذا البريد الالكتروني موجود من قبل';

        // Check Error 3
        if($pass1 != $pass2)
            $errorlist[] = 'كلمتا المرور غير متطابقتين';

        // If Not Found Error
        if(count($errorlist) == 0)
        {

            $stmt = $conn->prepare("INSERT INTO `users` (`fname`, `lname`, `sex`, `email`, `password`, `birthday`, `age`, `town`, `relstatus`)
                                    VALUES (:fname,:lname,:sex,:email,:pass,:birthday,:age,:town,:relstatus)");
            $stmt->execute(array(
                                'fname'     => $fname,
                                'lname'     => $lname,
                                'sex'       => $sex,
                                'email'     => $email,
                                'pass'      => $pass1,
                                'birthday'  => $birthday,
                                'age'       => $age,
                                'town'      => $town,
                                'relstatus' => $relstatus));


            if($stmt)
                header('location: login.php');
            
        }
        
    endif;
    
?>

<section class="section-login-page">
<div class="container">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="form-signup">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form-horizontal">
                <div class="form-group">
                    <h2 class="text-center" style="color:#fff">انشاء حساب جديد</h2>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="text" name="fname" placeholder="الاسم الاول">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="text" name="lname" placeholder="الاسم الثاني">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <select name="sex" class="form-control">
                            <option value="">النوع</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="email" name="email" placeholder="الايميل">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="password" name="pass1" placeholder="كلمة السر">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="password" name="pass2" placeholder="تاكيد كلمة السر">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="text" name="town" placeholder="المدينة">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <input class="form-control" type="date" name="birthday">
                    </div>
                    <div class="col-sm-6 margin-bottom-12">
                        <select class="form-control" name="relstatus">
                            <option value="">الحالة الاجتماعية</option>
                            <option value="single">Single</option>
                            <option value="engaged">Engaged</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                        </select>
                    </div>
                    <div class="col-sm-12 margin-bottom-12">
                        <button type="submit" class="form-control signup-btn">تسجيل</button>
                    </div>
                    <?php 
                        if(isset($errorlist)) {
                            foreach($errorlist as $error) { 
                    ?>
                        <div class="col-sm-6 text-center">
                            <div class="alert alert-danger"><p> <?php echo $error; ?> </p></div>
                        </div>
                    <?php
                            }
                        }
                    ?>
                </div>

            </form>
            <div class="text-center"><a style="text-decoration:none;font-size:17px;color:#000" href="login.php">لدي حساب بالفعل</a></div>
        </div>
    </div>
</div>
</section>