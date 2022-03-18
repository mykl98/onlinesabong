<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getFightDetail($idx){
            global $conn;
            $data = array();
            $table = "entry";
            $sql = "SELECT * FROM `$table` WHERE idx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $value = new \StdClass();
                    $value -> date = $row["date"];
                    $value -> number = $row["number"];
                    $value -> winner = $row["winner"];
                    array_push($data,$value);
                }
            }
            $data = json_encode($data);
            return $data;
        }

        function getBettingHistory($idx){
            global $conn;
            $data = array();
            $table = "bet";
            $sql = "SELECT * FROM `$table` WHERE useridx='$idx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $value = new \StdClass();
                        $value -> side = $row["side"];
                        $value -> amount = $row["amount"];
                        $value -> fightdetail = getFightDetail($row["fightidx"]);
                        array_push($data,$value);
                    }
                }
                $data = json_encode($data);
                return "true*_*" . $data;
            }else{
                return "System Error!";
            }
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "user"){
            $idx = $_SESSION["loginidx"];
            echo getBettingHistory($idx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>