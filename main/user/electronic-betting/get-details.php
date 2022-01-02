<?php
    if($_POST){
        include_once "../../../system/backend/config.php";
        $fightNumber = "0";
        $fightIdx = "";
        $fightStatus = "";

        function getMeronTotalBet($fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE fightidx='$fightIdx' && side='meron'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $amount += (int)$row["amount"];
                    }
                }
            }
            return $amount;
        }

        function getWalaTotalBet($fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE fightidx='$fightIdx' && side='wala'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $amount += (int)$row["amount"];
                    }
                }
            }
            return $amount;
        }

        function getFight(){
            global $conn,$fightNumber,$fightIdx,$fightStatus;
            $table = "entry";
            $sql = "SELECT idx,number,status FROM `$table` WHERE status='open'||status='lastcall'||status='locked'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $fightNumber = $row["number"];
                    $fightIdx = $row["idx"];
                    $fightStatus = $row["status"];
                }
            }
        }

        function getMeronBet($userIdx,$fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE useridx='$userIdx' && fightidx='$fightIdx' && side='meron'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $amount = $row["amount"];
                }
            }
            return $amount;
        }

        function getWalaBet($userIdx,$fightIdx){
            global $conn;
            $amount = 0;
            $table = "bet";
            $sql = "SELECT amount FROM `$table` WHERE useridx='$userIdx' && fightidx='$fightIdx' && side='wala'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $amount = $row["amount"];
                }
            }
            return $amount;
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

        function getDetails($userIdx){
            global $fightNumber,$fightIdx,$fightStatus;
            getFight();
            $data = array();
            $value = new \StdClass();
            $value -> wallet = getWalletAmount($userIdx);
            $value -> fightnumber = $fightNumber;
            $value -> fightidx = $fightIdx;
            $value -> fightstatus = $fightStatus;
            $value -> meronmainbet = getMeronTotalBet($fightIdx);
            $value -> walamainbet = getWalaTotalBet($fightIdx);
            $value -> meronbet = getMeronBet($userIdx,$fightIdx);
            $value -> walabet = getWalaBet($userIdx,$fightIdx);
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "user"){
            $idx = $_SESSION["loginidx"];
            echo getDetails($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>