<?php
    if($_POST){
        include_once "../../../system/backend/config.php";
        $fightIdx = "";
        $meronDescription = "";

        function getFightIdx(){
            global $conn,$fightIdx,$meronDescription;
            $fightIdx = "";
            $table = "entry";
            $sql = "SELECT idx,meron FROM `$table` WHERE status='open'||status='lastcall'||status='locked'||status='meronlocked'||status='walalocked'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $fightIdx = $row["idx"];
                    $meronDescription = $row["meron"];
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

        function getDetails(){
            global $fightIdx,$meronDescription;
            getFightDetail();
            $data = array();
            $value = new \StdClass();
            $value -> merondescription = $meronDescription;
            $value -> meronmainbet = getMeronTotalBet($fightIdx);
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "user"){
            echo getDetails();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>