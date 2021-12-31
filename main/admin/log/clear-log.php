<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function clearLog(){
            global $conn;
            $table = "log";
            $sql = "DELETE FROM `$table`";
            if(mysqli_query($conn,$sql)){
                return "true*_*";
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
            echo clearLog();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>