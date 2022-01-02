<?php
    if($_POST){
        include_once "../../../system/backend/config.php";
        $total = 0;
        $finish = 0;
        $waiting = 0;
        $cancelled = 0;

        function getEntryDetails(){
            global $conn,$total,$finish,$waiting,$cancelled;
            $table = "entry";
            $sql = "SELECT status FROM `$table`";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $status = $row["status"];
                        if($status == "waiting"){
                            $waiting += 1;
                        }
                        if($status == "finish"){
                            $finish += 1;
                        }
                        if($status == "cancelled"){
                            $cancelled += 1;
                        }
                        $total += 1;
                    }
                }
            }
        }

        function getDashboardDetails(){
            global $total,$finish,$waiting,$cancelled;
            getEntryDetails();
            $data = array();
            $value = new \StdClass();
            $value -> total = $total;
            $value -> finish = $finish;
            $value -> waiting = $waiting;
            $value -> cancelled = $cancelled;
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "operator"){
            echo getDashboardDetails();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>