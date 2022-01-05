<?php
if($_POST){
    include_once "../../../system/backend/config.php";
    function getEntryStatus($idx){
        global $conn;
        $status = "";
        $table = "entry";
        $sql = "SELECT status FROM `$table` WHERE idx='$idx'";
        if($result=mysqli_query($conn,$sql)){
            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_array($result);
                $status = $row["status"];
            }
        }
        return $status;
    }

    function saveEntry($idx,$number,$meron,$wala,$description){
        global $conn;
        if($idx != ""){
            $status = getEntryStatus($idx);
            if($status != "waiting"){
                return "You are not allowed to edit or update this entry! This entry is already on " .$status. " state.";
            }
        }
        $date = date("Y-m-d");
        $table = "entry";
        if($idx == ""){
            $sql = "INSERT INTO `$table` (date,number,meron,wala,description,status) VALUES ('$date','$number','$meron','$wala','$description','waiting')";
            if(mysqli_query($conn,$sql)){
                systemLog("Add new entry with fight number: ".$number,$_SESSION["loginidx"]);
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }else{
            $sql = "UPDATE `$table` SET number='$number',meron='$meron',wala='$wala',description='$description' WHERE idx='$idx'";
            if(mysqli_query($conn,$sql)){
                systemLog("Edit entry with fight number: ".$number,$_SESSION["loginidx"]);
                return "true*_*";
            }else{
                return "System Failed!";
            }
        }
    }

    session_start();
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "operator"){
        $idx = sanitize($_POST["idx"]);
        $number = sanitize($_POST["number"]);
        $meron = sanitize($_POST["meron"]);
        $wala = sanitize($_POST["wala"]);
        $description = sanitize($_POST["description"]);

        if(!empty($number) && !empty($meron) && !empty($wala) && !empty($description)){
            echo saveEntry($idx,$number,$meron,$wala,$description);
        }else{
            echo "Network Error!";
        }
    }else{
        echo "Access Denied!";
    }
}else{
    echo "Access Denied!";
}
?>