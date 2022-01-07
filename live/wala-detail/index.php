<?php
    include_once "../../system/backend/config.php";
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
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/bootstrap/css/bootstrap.min.css">
    <!--Custom style.css-->
    <link rel="stylesheet" href="style.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/fontawesome/css/fontawesome.css">

    <title>Online Betting System</title>
  </head>

  <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col text-center bg-danger rounded">
                <h1 class="text-white m-0 mt-2">WALA</h1>
                <p class="text-white m-0 mb-2"><span id="wala-main-bet" >0</span> - <span id="wala-per100">0</span></p>
                <div class="form-group">
                    <textarea class="form-control p-1 border-0 bg-danger text-white" id="description"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Page JavaScript Files-->
    <script src="<?php echo $baseUrl?>/system/plugin/jquery/js/jquery.min.js"></script>
    <!--Popper JS-->
    <script src="<?php echo $baseUrl?>/system/plugin/popper/js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="<?php echo $baseUrl?>/system/plugin/bootstrap/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>