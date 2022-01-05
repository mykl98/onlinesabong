<?php
    if($_POST){
        include_once "../../../system/backend/config.php";

        function getEntryList(){
            global $conn;
            $data = array();
            $table = "entry";
            $sql = "SELECT * FROM `$table`";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $value = new \StdClass();
                        $value -> idx = $row["idx"];
                        $value -> date = $row["date"];
                        $value -> number = $row["number"];
                        $value -> meron = $row["meron"];
                        $value -> wala = $row["wala"];
                        $value -> description = $row["description"];
                        $value -> status = $row["status"];
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
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "operator"){
            echo getEntryList();
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>