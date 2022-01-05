<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getAccountName($idx){
            global $conn;
            $name = "";
            $table = "account";
            $sql = "SELECT name FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $name = $row["name"];
                }
            }
            return $name;
        }

        function getBetList($fightIdx){
            global $conn;
            $data = array();
            $table = "bet";
            $sql = "SELECT * FROM `$table` WHERE fightidx='$fightIdx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $value = new \StdClass();
                        $value -> user = getAccountName($row["useridx"]);
                        $value -> side = $row["side"];
                        $value -> amount = $row["amount"];
                        array_push($data,$value);
                    }
                }
                $data = json_encode($data);
                return "true*_*".$data;
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
            $fightIdx = sanitize($_POST["idx"]);
            echo getBetList($fightIdx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>