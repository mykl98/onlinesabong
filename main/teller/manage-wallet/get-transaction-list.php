<?php
if(isset($_POST)){
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

    function getTransactionList($tellerIdx){
        global $conn;
        $data = array();
        $table = "transaction";
        $sql = "SELECT * FROM `$table` WHERE telleridx='$tellerIdx' ORDER BY idx DESC";
        if($result=mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_array($result)){
                    $value = new \StdClass();
                    $value -> date = $row["date"];
                    $value -> time = $row["time"];
                    $value -> amount = $row["amount"];
                    $value -> transaction = $row["transaction"];
                    $value -> user = getAccountName($row["user"]);
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
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "teller"){
        $tellerIdx = $_SESSION["loginidx"];
        echo getTransactionList($tellerIdx);
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>