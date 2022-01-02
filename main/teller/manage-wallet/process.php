<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getWalletAmount($idx){
            global $conn;
            $amount = 0;
            $table = "account";
            $sql = "SELECT wallet FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $amount = $row["wallet"];
                }
            }
            return $amount;
        }

        function process($idx,$amount,$type,$tellerIdx){
            global $conn;
            if($type == "cashin"){
                $table = "account";
                $sql = "UPDATE `$table` SET wallet=wallet+'$amount' WHERE idx='$idx'";
                if(mysqli_query($conn,$sql)){
                    $date = date("Y-m-d");
                    $time = date("h:m:ia");
                    $table = "transaction";
                    $sql = "INSERT INTO `$table` (date,time,amount,useridx,telleridx,transaction) VALUES ('$date','$time','$amount','$idx','$tellerIdx','cashin')";
                    if(mysqli_query($conn,$sql)){
                        systemLog("Cashout ".$amount." to ".$idx." account.",$tellerIdx);
                        return "true*_*";
                    }else{
                        return "System Error!";
                    }
                }else{
                    return "System Error!";
                }
            }else{
                $wallet = getWalletAmount($idx);
                if($amount > $wallet){
                    return "Cannot process this transaction.\nRequested amount is higher than the amount available in the User's wallet!";
                }
                $table = "account";
                $sql = "UPDATE `$table` SET wallet=wallet-'$amount' WHERE idx='$idx'";
                if(mysqli_query($conn,$sql)){
                    $date = date("Y-m-d");
                    $time = date("h:m:ia");
                    $table = "transaction";
                    $sql = "INSERT INTO `$table` (date,time,amount,useridx,telleridx,transaction) VALUES ('$date','$time','$amount','$idx','$tellerIdx','cashout')";
                    if(mysqli_query($conn,$sql)){
                        systemLog("Cashout ".$amount." from ".$idx." account.",$tellerIdx);
                        return "true*_*";
                    }else{
                        return "System Error!";
                    }
                }else{
                    return "System Error!";
                }
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "teller"){
            $idx = sanitize($_POST["idx"]);
            $amount = sanitize($_POST["amount"]);
            $type = sanitize($_POST["type"]);
            $tellerIdx = $_SESSION["loginidx"];
            if(!empty($idx) && !empty($amount) && !empty($type)){
                echo process($idx,$amount,$type,$tellerIdx);
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