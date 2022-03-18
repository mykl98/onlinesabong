<?php
    include_once "../../../system/backend/config.php";
    session_start();
    if($_SESSION["isLoggedIn"] == "true" && $_SESSION["access"] == "user"){
    
    }else{
        session_destroy();
        header("location:".$baseUrl."/index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User | Electronic Betting</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/fontawesome/css/fontawesome.css">
    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo $baseUrl?>/system/plugin/bootstrap/css/bootstrap.min.css">
    <!--Datatable-->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/datatables/css/dataTables.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/adminlte/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo $baseUrl;?>/system/plugin/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="<?php echo $baseUrl;?>/system/plugin/googlefont/css/googlefont.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Top Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <p id="global-user-name" class="mr-2 mt-2">Michael Martin G. Abellana</p>
                    <p id="base-url" class="d-none"><?php echo $baseUrl;?></p>
                </li>
                <li class="nav-item">
                    <a class="" data-toggle="dropdown" href="#">
                        <img id="global-user-image" class="rounded-circle" src="<?php echo $baseUrl;?>/system/images/blank-profile.png" width="40px" height="40px">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mt-13" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="../profile-setting"><i class="fa fa-user pr-2"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="$('#logout-modal').modal('show');"><i class="fa fa-power-off pr-2"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /Top Navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link text-center pb-0">
                <p class="">User</p>
            </a>
            <?php include "../side-nav-bar.html"?>
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="<?php echo $baseUrl?>/system/images/sabong.jpg" class="w-100">
                            <div class="form-group">
                                <textarea class="form-control mt-1" id="description"></textarea>
                            </div>
                            <!--p id="description" class="p-2"></p-->
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="pl-2 font-weight-bold mb-0">Wallet: <span id="wallet" class="text-success">0</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <p class="pl-2 font-weight-bold">Fight# <span id="fight-number">0</span></p>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div id="fight-status-container"></div>
                        </div>
                    </div>
                    <div class="row pb-5">
                        <div class="col-lg-3 col-6">
                            <button id="meron-button" class="btn btn-success w-100 p-3" onclick="addBetMeron()" disabled="true">MERON</button>
                            <p class="pl-2 mt-3 font-weight-bold" id="meron-main-bet">0</p>
                            <p class="pl-2 mt-4 mb-0">Bet</p>
                            <p class="pl-2" id="meron-bet">0</p>
                            <p class="pl-2 mt-4 mb-0">Payout</p>
                            <p class="pl-2" id="meron-payout">0</p>
                            <p class="pl-2 text-info" id="meron-per100">0</p>
                        </div>
                        <div class="col-lg-3 col-6">
                            <button id="wala-button" class="btn btn-danger w-100 p-3" onclick="addBetWala()" disabled="true">WALA</button>
                            <p class="mt-3 ml-2 font-weight-bold" id="wala-main-bet">0</p>
                            <p class="pl-2 mt-4 mb-0">Bet</p>
                            <p class="pl-2" id="wala-bet">0</p>
                            <p class="pl-2 mt-4 mb-0">Payout</p>
                            <p class="pl-2" id="wala-payout">0</p>
                            <p class="pl-2 text-info" id="wala-per100">0</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-4">
                            <p>Statistics</p>
                            <div id="statistics-container"></div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        <footer class="main-footer">
        </footer>
    </div>
    <!-- ./wrapper -->
    
    <!-- Modals -->
<div class="modal fade" id="add-bet-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-bet-modal-title">Add Bet to Meron</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="bet-amount" class="col-form-label">Amount:</label>
                        <input type="text" class="form-control currency" id="bet-amount">
                    </div>
                </form>
                <p id="add-bet-modal-error" class="text-danger font-italic small"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="confirmBet()">Confirm</button>
            </div>
        </div>
    </div>
</div>
    <!-- Logout Modal -->
    <div class="modal fade" id="logout-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-secondary"><strong>Logout</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Do you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="logout()">Yes</button>
                </div>
            </div>
        </div>
    </div>

<!-- jQuery -->
<script src="<?php echo $baseUrl;?>/system/plugin/jquery/js/jquery.min.js"></script>
<script src="<?php echo $baseUrl;?>/system/plugin/jquery/js/jquery.dataTables.min.js"></script>
<!--Popper JS-->
<script src="<?php echo $baseUrl;?>/system/plugin/popper/js/popper.min.js"></script>
<!--Bootstrap-->
<script src="<?php echo $baseUrl;?>/system/plugin/bootstrap/js/bootstrap.min.js"></script>
<!-- Admin LTE -->
<script src="<?php echo $baseUrl;?>/system/plugin/adminlte/js/adminlte.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $baseUrl;?>/system/plugin/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!--Datatables-->
<script src="<?php echo $baseUrl;?>/system/plugin/datatables/js/dataTables.bootstrap4.min.js"></script>

<!-- Page Level Script -->
<script src="script.js"></script>
</body>
</html>
