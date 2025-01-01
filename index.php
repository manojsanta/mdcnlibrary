<?php
session_start();
error_reporting(1);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    // $password = md5($_POST['password']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM admin WHERE username=:username and Password=:password ";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['sid'] = $result->admin_id;
            $_SESSION['name'] = $result->firstname;
            $_SESSION['lastname'] = $result->lastname;
            $_SESSION['permission'] = $result->permission;
            $_SESSION['email'] = $result->email;
        }

        if (!empty($_POST["remember"])) {
            //COOKIES for username
            setcookie("user_login", $_POST["username"], time() + (10 * 365 * 24 * 60 * 60));
            //COOKIES for password
            setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if (isset($_COOKIE["user_login"])) {
                setcookie("user_login", "");
                if (isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "");
                }
            }
        }
        $aa = $_SESSION['sid'];
        // echo $aa;
        $sql = "SELECT * from admin  WHERE admin_id=:aa";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aa', $aa, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $cnt = 1;
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {

                if ($row->status == "1") {

                    $extra = "dashboard.php";
                    $username = $_POST['username'];
                    // $email = $_SESSION['email'];
                    $name = $_SESSION['name'];
                    $lastname = $_SESSION['lastname'];
                    $_SESSION['login'] = $_POST['username'];
                    $_SESSION['id'] = $num['admin_id'];
                    $_SESSION['username'] = $num['name'];
                    $uip = $_SERVER['REMOTE_ADDR'];
                    $status = 1;
                    $sql = "insert into userlog_admin(userip,status,username,name,lastname)values(:uip,:status,:username,:name,:lastname)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':username', $username, PDO::PARAM_STR);
                    $query->bindParam(':name', $name, PDO::PARAM_STR);
                    $query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
                    // $query->bindParam(':email', $email, PDO::PARAM_STR);
                    $query->bindParam(':uip', $uip, PDO::PARAM_STR);
                    $query->bindParam(':status', $status, PDO::PARAM_STR);
                    $query->execute();
                    $host = $_SERVER['HTTP_HOST'];
                    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                    header("location:http://$host$uri/$extra");
                    exit();
                } else {

                    echo "<script>alert('Your account was blocked please approach Admin');document.location ='index.php';</script>";
                }
            }
        }
    } else {
        $extra = "index.php";
        $username = $_POST['username'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        $email = 'Not registered in system';
        $name = 'Potential Hacker';
        $sql = "insert into userlog(userEmail,userip,status,username,name)values(:email,:uip,:status,:username,:name)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':uip', $uip, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        $host  = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        echo "<script>alert('Username or Password is incorrect');document.location ='http://$host$uri/$extra';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Libary Model Degree College,Nayagarh </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">

</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-4 h-100 align-items-center d-flex flex-column my-auto p-0 pl-0">
                                <img src="images/weblogo.png" alt="" class="img-fluid ml-2 pt-2" height="150px" width="150px">
                            </div>
                            <div class="col-xl-8">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form action=" " method="post">
                                        <div class="form-group">
                                            <label><strong>Username</strong></label>

                                            <input type="text" name="username" class="form-control input-rounded" placeholder="Username" required value="<?php if (isset($_COOKIE["user_login"])) {
                                                                                                                                                                echo $_COOKIE["user_login"];
                                                                                                                                                            } ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" name="password" class="form-control input-rounded" placeholder="Password" required value="<?php if (isset($_COOKIE["userpassword"])) {
                                                                                                                                                                    echo $_COOKIE["userpassword"];
                                                                                                                                                                } ?>">
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                    <!-- <input class="form-check-input" type="checkbox" id="basic_checkbox_1"> -->
                                                    <!-- <label class="form-check-label" for="basic_checkbox_1">Remember me</label> -->
                                                    <input type="checkbox" id="remember" name="remember" class="form-check-input" <?php if (isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                                                    <label for="remember">
                                                        Remember Me
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="page-forgot-password.html">Forgot Password?</a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" name="login" class="btn btn-primary btn-block input-rounded">Sign me in</button>


                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <!-- <p>Don't have an account? <a class="text-primary" href="./page-register.html">Sign up</a></p> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

</body>

</html>