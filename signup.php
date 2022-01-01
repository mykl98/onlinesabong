<?php
    session_start();
    include_once "system/backend/config.php";
    $error = "";
    $name = "";
    $username = "";
    $password = "";
    $retype = "";

    function checkIfContainNumbers($string){
        if (strcspn($string, '0123456789') != strlen($string)){
            return "true";
        }else{
            return "false";
        }
    }

    function checkUserName($username){
        global $conn;
        $table = "account";
        $sql = "SELECT idx FROM `$table` WHERE username='$username'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "*Username already in use, Please use another username";
            }else{
                return "true";
            }
        }else{
            return "*System Error!";
        }
    }

    if(isset($_SESSION["isLoggedIn"])){
        if($_SESSION["isLoggedIn"] == "true"){
            header("location:main");
            exit();
        }
    }
    if($_POST){
        $name = sanitize($_POST["name"]);
        $username = sanitize($_POST["username"]);
        $password = sanitize($_POST["password"]);
        $retype = sanitize($_POST["retype"]);
        $check = checkUserName($username);
        if($name == ""){
            $error = "*Name field should not be empty!";
        }else if(checkIfContainNumbers($name) == "true"){
            $error = "Name should not contain numbers!";
        }else if($username == ""){
            $error = "*Username field should not be empty!";
        }else if($password == ""){
            $error = "*Password field should not be empty!";
        }else if(strlen($password) < 8){
            $error = "*Password should be atleast 8 characters long.";
        }else if($password != $retype){
            $error = "*Password and retype password does not match!";
        }else if($check != "true"){
            $error = $check;
        }else{
            $table = "account";
            $qr = generateCode(100);
            $sql = "INSERT INTO `$table` (name,username,password,access,qr,wallet) VALUES ('$name','$username','$password','user','$qr','0')";
            if(mysqli_query($conn,$sql)){
                $last_id = $conn->insert_id;
                $_SESSION["loginidx"] = $last_id;
                $_SESSION["access"] = "user";
                $_SESSION["isLoggedIn"] = "true";
                header("location:main");
                exit();
            }else{
                $error = "*System Error!";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="" >
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--Meta Responsive tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="system/plugin/bootstrap/css/bootstrap.min.css">
    <!--Custom style.css-->
    <link rel="stylesheet" href="style.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="system/plugin/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="system/plugin/fontawesome/css/fontawesome.css">

    <title>Church Booking System</title>
  </head>

  <body>
    
    <!--Login Wrapper-->

    <div class="container-fluid login-wrapper d-flex justify-content-center">
        <div class="login-box mt-5">
            <div class="row d-flex justify-content-center">
                <div class="col-sm-5 col-md-5 bg-white p-4 mb-5">
                    <h3 class="mb-2 text-success">Signup</h3>
                    <form method="post" class="mt-2">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="name" value="<?php echo $name;?>" class="form-control mt-0" placeholder="Your name">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" name="username" value="<?php echo $username;?>" class="form-control mt-0" placeholder="Username">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="password" value="<?php echo $password;?>" class="form-control mt-0" placeholder="Password">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" name="retype" value="<?php echo $retype;?>" class="form-control mt-0" placeholder="Re-Type Password">
                        </div>

                        <div class="form-group">
                            <small class="text-danger font-italic"><?php echo $error;?></small>
                            <input type="submit" class="btn btn-success btn-block p-2 mb-1" value="Sign Up">
                        </div>
                    </form>
                    <p class="text-secondary text-wrap">Already have an account? <a href="index.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>    

    <!--Login Wrapper-->

    <!-- Page JavaScript Files-->
    <script src="system/plugin/jquery/js/jquery.min.js"></script>
    <!--Popper JS-->
    <script src="system/plugin/popper/js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="system/plugin/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>