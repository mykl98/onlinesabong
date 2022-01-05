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

        function getMeronTotalAmount($fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE fightidx='$fightIdx' && side='meron'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    While($row=mysqli_fetch_array($result)){
                        $betAmount = $row["amount"];
                        $amount += $betAmount;
                    }
                }
            }
            return $amount;
        }

        function getWalaTotalAmount($fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE fightidx='$fightIdx' && side='wala'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    While($row=mysqli_fetch_array($result)){
                        $betAmount = $row["amount"];
                        $amount += $betAmount;
                    }
                }
            }
            return $amount;
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
            $meronTotalAmount = getMeronTotalAmount($fightIdx);
            $walaTotalAmount = getWalaTotalAmount($fightIdx);
            $income = $meronTotalAmount * 0.07;
            $meronRemainingAmount = $meronTotalAmount - $income;
            $date = date("Y-m-d");
            $time = date("h:m:ia");
            $table = "income";
            $sql = "INSERT INTO `$table` (date,time,fightidx,side,amount) VALUES ('$date','$time','$fightIdx','wala','$income')";
            if(mysqli_query($conn,$sql)){
                $table = "bet";
                $sql = "SELECT * FROM `$table` WHERE fightidx='$fightIdx' && side='wala'";
                if($result=mysqli_query($conn,$sql)){
                    if(mysqli_num_rows($result) > 0){
                        while($row=mysqli_fetch_array($result)){
                            $userIdx = $row["useridx"];
                            $amount = $row["amount"];
                            $payment = $meronRemainingAmount * $amount/$walaTotalAmount;
                            $payment = $amount + $payment;
                            $update = updateWallet($userIdx,$payment);
                            if($update != "true"){
                                return $update;
                            }
                        }
                        return "true";
                    }
                }else{
                    return "System Error!";
                }
            }else{
                return "System Error!";
            }
        }

        function declareWala($idx){
            global $conn;
            $status = getEntryStatus($idx);
            if($status != "locked"){
                return "You are not allowed to declare winner for this entry. This entry is already on " .$status. " state.";
            }
            $table = "entry";
            $sql = "UPDATE `$table` SET status='finish' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Declare wala as winner for the entry with index number ".$idx,$_SESSION["loginidx"]);
                $release = releasePayment($idx);
                if($release == "true"){
                    return "true*_*";
                }else{
                    return $release;
                }
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
            $idx = sanitize($_POST["idx"]);
            echo declareWala($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>