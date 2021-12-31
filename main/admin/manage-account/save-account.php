<?php
if($_POST){
    include_once "../../../system/backend/config.php";

    function checkIfContainNumbers($string){
        if (strcspn($string, '0123456789') != strlen($string)){
            return "true";
        }else{
            return "false";
        }
    }

    function checkIfUsernameExist($username){
        global $conn;
        $table = "account";
        $sql = "SELECT idx FROM `$table` WHERE username='$username'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                return "true";
            }else{
                return "false";
            }
        }else{
            return "false";
        }
    }

    function saveAccount($idx,$name,$username,$access){
        global $conn;
        if(checkIfContainNumbers($name) == "true"){
            return "Invalid name, it contains numbers.";
        }
        if(checkIfUsernameExist($username) == "true" && $idx == ""){
            return "Username already exist, please use another username.";
        }
        $table = "account";
        if($idx == ""){
            $sql = "INSERT INTO `$table` (name,username,password,access) VALUES ('$name','$username','123456','$access')";
            if(mysqli_query($conn,$sql)){
                systemLog("Add new account with name: ".$name.",username: ".$username.",access: ".$access,$_SESSION["loginidx"]);
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }else{
            $sql = "UPDATE `$table` SET name='$name',username='$username',access='$access' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Edit account with name: ".$name.",username: ".$username.",access: ".$access,$_SESSION["loginidx"]);
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }
    }

    session_start();
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
        $idx = sanitize($_POST["idx"]);
        $name = sanitize($_POST["name"]);
        $username = sanitize($_POST["username"]);
        $access = sanitize($_POST["access"]);

        if(!empty($name) && !empty($username) && !empty($access)){
            echo saveAccount($idx,$name,$username,$access);
        }else{
            echo "Network Error!";
        }
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>