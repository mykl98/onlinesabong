<?php
    if($_POST){
        include_once "../../system/backend/config.php";

        $fightNumber = "";
        $fightIdx = "";
        $fightStatus = "";
        $fightDescription = "";
        $walaDescription = "";
        $meronDescription = "";

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
            global $conn,$fightNumber,$fightIdx,$fightStatus,$fightDescription,$meronDescription,$walaDescription;
            $table = "entry";
            $sql = "SELECT * FROM `$table` WHERE status='open'||status='lastcall'||status='locked'||status='meronlocked'||status='walalocked'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $fightNumber = $row["number"];
                    $fightIdx = $row["idx"];
                    $fightStatus = $row["status"];
                    $fightDescription = $row["description"];
                    $meronDescription = $row["meron"];
                    $walaDescription = $row["wala"];
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

        function getDetails(){
            global $fightNumber,$fightIdx,$fightStatus,$fightDescription,$meronDescription,$walaDescription;
            getFight();
            $data = array();
            $value = new \StdClass();
            $value -> fightnumber = $fightNumber;
            $value -> fightidx = $fightIdx;
            $value -> fightstatus = $fightStatus;
            $value -> fightdescription = $fightDescription;
            $value -> merondescription = $meronDescription;
            $value -> waladescription = $walaDescription;
            $value -> meronmainbet = getMeronTotalBet($fightIdx);
            $value -> walamainbet = getWalaTotalBet($fightIdx);
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "video-operator"){
            echo getDetails();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>