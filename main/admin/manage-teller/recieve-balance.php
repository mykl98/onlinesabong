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

    function getBalanceAmount($idx){
        global $conn;
        $amount = 0;
        $table = "account";
        $sql = "SELECT cashin,cashout FROM `$table` WHERE idx='$idx'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $cashin = $row["cashin"];
                $cashout = $row["cashout"];
                $amount = (int)$cashin - (int)$cashout;
            }
        }
        return $amount;
    }

    function recieveBalance($idx,$balance){
        global $conn;
        $amount = getBalanceAmount($idx);
        if($amount != $balance){
            return "The balance you want to clear out does not match to the system records. Balance clear out fails.";
        }
        if(empty($amount)){
            return "This teller has no balance to clear out. Blance clear out failed.";
        }
        $table = "account";
        $sql = "UPDATE `$table` SET cashin='0',cashout='0' WHERE idx='$idx'";
        if(mysqli_query($conn,$sql)){
            systemLog("Colleted " .$amount." pesos from teller ".getAccountName($idx),$_SESSION["loginidx"]);
            return "true*_*";
        }else{
            return "false";
        }
    }

    session_start();
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "admin"){
        $idx = sanitize($_POST["idx"]);
        $balance = sanitize($_POST["balance"]);
        echo recieveBalance($idx,$balance);
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>