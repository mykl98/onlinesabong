<?php
    if($_POST){
        include_once "../../system/backend/config.php";
        $fightIdx = "";
        $walaDescription = "";

        function getFightDetail(){
            global $conn,$fightIdx,$walaDescription;
            $fightIdx = "";
            $table = "entry";
            $sql = "SELECT idx,wala FROM `$table` WHERE status='open'||status='lastcall'||status='locked'||status='meronlocked'||status='walalocked'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $fightIdx = $row["idx"];
                    $walaDescription = $row["wala"];
                }
            }
        }

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

        function getDetails(){
            global $fightIdx,$walaDescription;
            getFightDetail();
            $data = array();
            $value = new \StdClass();
            $value -> waladescription = $walaDescription;
            $value -> walamainbet = getWalaTotalBet($fightIdx);
            $value -> meronmainbet = getMeronTotalBet($fightIdx);
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        echo getDetails();
    }else{
        echo "Access Denied!";
    }
?>