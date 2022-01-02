<?php
    if($_POST){
        include_once "../../../system/backend/config.php";
        $cashIn = 0;
        $cashOut = 0;

        function getTransaction($tellerIdx){
            global $conn,$cashIn,$cashOut;
            $table = "transaction";
            $sql = "SELECT amount,transaction FROM `$table` WHERE telleridx='$tellerIdx'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row=mysqli_fetch_array($result)){
                        $transaction = $row["transaction"];
                        $amount = $row["amount"];
                        if($transaction == "cashin"){
                            $cashIn += $amount;
                        }else{
                            $cashOut += $amount;
                        }
                    }
                }
            }else{
                return "System Error!";
            }
        }

        function getDashboardDetails($tellerIdx){
            global $cashIn,$cashOut;
            getTransaction($tellerIdx);
            $data = array();
            $value = new \StdClass();
            $value -> cashin = $cashIn;
            $value -> cashout = $cashOut;
            array_push($data,$value);
            $data = json_encode($data);
            return "true*_*" . $data;
        }

        session_start();
        if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "teller"){
            $tellerIdx = $_SESSION["loginidx"];
            echo getDashboardDetails($tellerIdx);
        }else{
            echo "Access Denied!";
        }
    }else{
        echo "Access Denied!";
    }
?>