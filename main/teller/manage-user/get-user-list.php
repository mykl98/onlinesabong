<?php
if(isset($_POST)){
    include_once "../../../system/backend/config.php";

    function getUserList(){
        global $conn;
        $data = array();
        $table = "account";
        $sql = "SELECT * FROM `$table` WHERE access='user' ORDER BY idx DESC";
        if($result=mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row=mysqli_fetch_array($result)){
                    $value = new \StdClass();
                    $value -> name = $row["name"];
                    $value -> username = $row["username"];
                    $value -> wallet = $row["wallet"];
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
        echo getUserList();
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>