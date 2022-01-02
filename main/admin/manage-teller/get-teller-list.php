<?php
if(isset($_POST)){
    include_once "../../../system/backend/config.php";

    function getCashIn($tellerIdx){
        global $conn;
        $amount = 0;
        $table = "transaction";
        $sql = "SELECT amount FROM `$table` WHERE telleridx='$tellerIdx' && transaction='cashin'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_array($result)){
                    $amount += $row["amount"];
                }
            }
        }
        return $amount;
    }

    function getCashOut($tellerIdx){
        global $conn;
        $amount = 0;
        $table = "transaction";
        $sql = "SELECT amount FROM `$table` WHERE telleridx='$tellerIdx' && transaction='cashout'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_array($result)){
                    $amount += $row["amount"];
                }
            }
        }
        return $amount;
    }

    function getTellerList(){
        global $conn;
        $data = array();
        $table = "account";
        $sql = "SELECT * FROM `$table` WHERE access='teller' ORDER BY idx DESC";
        if($result=mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_array($result)){
                    $value = new \StdClass();
                    $idx = $row["idx"];
                    $value -> idx = $idx;
                    $value -> name = $row["name"];
                    $value -> cashin = getCashIn($idx);
                    $value -> cashout = getCashOut($idx);
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
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
        echo getTellerList();
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>