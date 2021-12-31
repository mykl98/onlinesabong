<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function deleteAccount($idx){
            global $conn;
            $table = "account";
            $sql = "DELETE FROM `$table` WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Deleted the account with index number ".$idx,$_SESSION["loginidx"]);
                return "true*_*Successfully deleted this account.";
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "super-admin"){
            $idx = sanitize($_POST["idx"]);
            echo deleteAccount($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>