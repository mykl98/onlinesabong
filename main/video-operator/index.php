<?php
    include_once "../../system/backend/config.php";
    session_start();
    if($_SESSION["isLoggedIn"] != "true" && $_SESSION["access"] != "video-operator"){
        session_destroy();
        header("location:" . $baseUrl . "/index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="" >
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--Meta Responsive tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/bootstrap/css/bootstrap.min.css">
    <!--Custom style.css-->
    <link rel="stylesheet" href="style.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/fontawesome/css/fontawesome.css">

    <title>Online Betting System</title>
  </head>

  <body style="background-color:black;">
    <div class="container-fluid page">
        <div class="row">
            <div class="col-2 bg-success m-0" style="height:100vh">
                <h1 class="text-center text-white mt-4">MERON</h1>
                <h2 id="meron-main-bet" class="text-center text-white">0</h2>
                <h4 id="meron-per100" class="text-center text-white">0</h4>
                <div class="form-group">
                    <textarea class="form-control bg-success text-white p-0 border-0 mt-5" id="meron-description" style="font-size:20px;">Test</textarea>
                </div>
            </div>
            <div class="col-8 m-0 p-0">
                <div class="row p-2"> 
                    <div class="col-3">
                        <h5 class="text-white p-2 m-0">Fight Number: <span id="fight-number"></span></h5>
                    </div>
                    <div class="col-6">
                        <div id="result-container"></div>
                    </div>
                    <div class="col-3">
                        <div id="fight-status-container"></div>
                    </div>
                </div>
                <div class="row" style="height:85vh;">
                    <div class="col">
                        <div id="fight-break" class="camera">
                            <div id="fight-break-frame">
                                <img src="<?php echo $baseUrl;?>/system/images/login-background.jpg" width="120%">
                        </div>
                        </div>
                        <div id="side-by-side" class="camera text-center">
                            <div id="meron-side-frame">
                                <!--iframe id="meron-side-iframe" src="http://192.168.1.10/cgi-bin/mjpg/video.cgi?channel=1&subtype=1" width="750" height="487" scrolling="no"></iframe-->
                            </div>
                            <div id="wala-side-frame">
                                <!--iframe id="wala-side-iframe" src="http://192.168.1.11/cgi-bin/mjpg/video.cgi?channel=1&subtype=1" width="750" height="487" scrolling="no"></iframe-->
                            </div>
                        </div>
                        <div id="fight-ongoing" class="camera">
                            <div id="fight-ongoing-frame">
                                <!--iframe id="fight-ongoing-iframe" src="http://192.168.1.11/cgi-bin/mjpg/video.cgi?channel=1&subtype=1" width="750" height="487" scrolling="no"></iframe-->
                            </div>
                        </div>
                        <div id="declare-winner" class="camera">
                            <div id="declare-winner-frame">
                                <!--iframe id="declare-winner-iframe" src="http://192.168.1.11/cgi-bin/mjpg/video.cgi?channel=1&subtype=1" width="750" height="487" scrolling="no"></iframe-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col">
                        <div id="button" style="display:none;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="switchPage('fight-break');">Fight Break</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="switchPage('side-by-side');">Side By Side</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="switchPage('fight-ongoing');">Fight Ongoing</button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="switchPage('declare-winner');">Declare Winner</button>
                            <button type="button" class="btn btn-info btn-sm" onclick="declareWinner('draw');">Draw</button>
                            <button type="button" class="btn btn-success btn-sm" onclick="declareWinner('meron');">Meron Win</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="declareWinner('wala');">Wala Win</button>
                        </div>
                        <div id="slider">
                            <a href="#" class="text-white" onclick="showButton()">Show</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 bg-danger m-0" style="height:100vh">
                <h1 class="text-center text-white mt-4">WALA</h1>
                <h2 id="wala-main-bet" class="text-center text-white">0</h2>
                <h4 id="wala-per100" class="text-center text-white">0</h4>
                <div class="form-group">
                    <textarea class="form-control bg-danger text-white p-0 border-0 mt-5" id="wala-description" style="font-size:20px;">Test</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Page JavaScript Files-->
    <script src="<?php echo $baseUrl;?>/system/plugin/jquery/js/jquery.min.js"></script>
    <!--Popper JS-->
    <script src="<?php echo $baseUrl;?>/system/plugin/popper/js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="<?php echo $baseUrl;?>/system/plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>