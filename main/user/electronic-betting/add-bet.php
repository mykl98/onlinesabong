<?php
    if($_POST){
        include_once "../../../system/backend/config.php";
        
        function getFightStatus($idx){
            global $conn;
            $status = "unknown";
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

        function getWalletAmount($idx){
            global $conn;
            $amount = "";
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

        function deductAmountToWallet($idx,$amount){
            global $conn;
            $table = "account";
            $sql = "UPDATE `$table` SET wallet=wallet-'$amount' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                return "true";
            }else{
                return "System Error!" . $conn->error;
            }
        }

        function checkIfBetExist($fightIdx,$userIdx,$side){
            global $conn;
            $betIdx = "";
            $table = "bet";
            $sql = "SELECT idx FROM `$table` WHERE fightidx='$fightIdx' && useridx='$userIdx' && side='$side'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $betIdx = $row["idx"];
                }
            }
            return $betIdx;
        }

        function addBet($fightIdx,$userIdx,$side,$bet){
            global $conn;
            $table = "bet";
            $status = getFightStatus($fightIdx);
            if($status != "open"){
                return "You could no longer add bet for this fight!";
            }
            $amount = getWalletAmount($userIdx);
            if($bet > $amount){
                return "You don't have enough amount in your wallet to place this bet!";
            }
            $betIdx = checkIfBetExist($fightIdx,$userIdx,$side);
            if($betIdx == ""){
                $sql = "INSERT INTO `$table` (useridx,side,amount,fightidx) VALUES ('$userIdx','$side','$bet','$fightIdx')";
                if(mysqli_query($conn,$sql)){
                    $deduct = deductAmountToWallet($userIdx,$bet);
                    if($deduct == "true"){
                        return "true*_*";
                    }else{
                        return $deduct;
                    }
                }else{
                    return "System Error!";
                }
            }else{
                $sql = "UPDATE `$table` SET amount=amount+'$bet' WHERE idx='$betIdx'";
                if(mysqli_query($conn,$sql)){
                    $deduct = deductAmountToWallet($userIdx,$bet);
                    if($deduct == "true"){
                        return "true*_*";
                    }else{
                        return $deduct;
                    }
                }else{
                    return "System Error!";
                }
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "user"){
            $fightIdx = sanitize($_POST["fightidx"]);
            $userIdx = $_SESSION["loginidx"];
            $side = sanitize($_POST["side"]);
            $bet = sanitize($_POST["bet"]);
            echo addBet($fightIdx,$userIdx,$side,$bet);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>