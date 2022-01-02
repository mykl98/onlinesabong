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

        function updateWallet($idx,$amount){
            global $conn;
            $table = "account";
            $sql = "UPDATE `$table` SET wallet=wallet+'$amount' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                return "true";
            }else{
                return "System Error!";
            }
        }

        function releasePayment($fightIdx){
            global $conn;
            $table = "bet";
            $sql = "SELECT useridx,amount FROM `$table` WHERE fightidx='$fightIdx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $update = updateWallet($row["useridx"],$row["amount"]);
                        if($update != "true"){
                            return $update;
                        }
                    }
                    return "true";
                }
            }else{
                return "System Error!";
            }
        }

        function clearAllBets(){
            global $conn;
            $table = "bet";
            $sql = "DELETE FROM `$table`";
            if(mysqli_query($conn,$sql)){
                return "true";
            }else{
                return "System Error!";
            }
        }

        function declareDraw($idx){
            global $conn;
            $status = getEntryStatus($idx);
            if($status != "locked"){
                return "You are not allowed to declare draw for this entry. This entry is already on " .$status. " state.";
            }
            $table = "entry";
            $sql = "UPDATE `$table` SET status='finish' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Declare draw for the entry with index number ".$idx,$_SESSION["loginidx"]);
                $release = releasePayment($idx);
                if($release == "true"){
                    $clear = clearAllBets();
                    if($clear == "true"){
                        return "true*_*";
                    }else{
                        return $clear;
                    }
                }else{
                    return $release;
                }
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "super-admin"){
            $idx = sanitize($_POST["idx"]);
            echo declareDraw($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>