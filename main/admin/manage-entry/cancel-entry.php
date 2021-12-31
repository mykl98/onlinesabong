<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getEntryStatus($idx){
            global $conn;
            $status = "";
            $table = "entry";
            $sql = "SELECT status FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $status = $row["status"];
                }
            }
            return $status;
        }

        function cancelEntry($idx){
            global $conn;
            $status = getEntryStatus($idx);
            if($status != "waiting"){
                return "You are not allowed to cancel this entry. This entry is already on " .$status. " state.";
            }
            $table = "entry";
            $sql = "UPDATE `$table` SET status='cancelled' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Cancelled the entry with index number ".$idx,$_SESSION["loginidx"]);
                return "true*_*";
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
            $idx = sanitize($_POST["idx"]);
            echo cancelEntry($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>