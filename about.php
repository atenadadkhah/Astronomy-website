<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>درباره ما | UniVard</title>
    <link rel="icon" href="icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="node_modules/animate.css/animate.css">
    <script src="js/wow.min.js"></script>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="uikit-3.9.4/js/uikit.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" href="./slick-1.8.1/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick-1.8.1/slick/slick-theme.css">
    <link rel="stylesheet" href="css/about/baseMain.css">
    <script src="js/main.js"></script>
    <?php require_once "XYZ.php" ?>
    <?php require_once "connection/connection.php"?>
    <?php require_once "common/common.php"?>
    <?php require_once "common/commonFunctions.php" ?>
</head>
<body>
<div class="hero pb-5 pb-md-0">
    <nav class="nav mx-5 text-light">
        <a class="navbar-brand brand-name  mt-4 ml-4" href="../index.php">Univard</a>
        <?php if (!isset($_SESSION['userInfo']) && !isset($_SESSION['adminInfo'])){
            echo '<a class="nav-link mt-4" href="index.php"> <i class="fa fa-home"></i> خانه </a>';
        }else echo ''; ?>
        <a class="nav-link  mt-4" href="info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
        <a class="nav-link  mt-4 " href="#"  data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
        <?php
        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
            echo ' <a class="nav-link mt-4" href="info/exam.php"><i class="fa fa-tasks " ></i> آزمون ها </a>';
        }else echo '';
        ?>
        <?php
        if (isset($_SESSION['userInfo'])  or isset($_SESSION['adminInfo'])){
            echo '<a class="nav-link mt-4" href="log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
        }else{
            echo '<a class="nav-link mt-4" href="log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
        }
        ?>
        <a class="nav-link  mt-4 active-nav " href=""><i class="fa fa-info-circle " ></i> درباره ما </a>
    </nav>
    <div class="w3-sidebar w3-bar-block w3-card w3-animate-right nav-side" style="right:0;top:0" id="rightMenu">
        <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large text-right text-info"> <span class="h4">&times;</span></button>
        <?php if (!isset($_SESSION['userInfo'])){
            echo ' <a href="index.php" class="w3-bar-item w3-button active text-right"><i class="fa fa-home"></i> خانه </a>';
        }else echo ''; ?>
        <a href="info/info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
        <a href="#" class="w3-bar-item w3-button text-right" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
        <?php
        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
            echo ' <a href="info/exam.php" class="w3-bar-item w3-button text-right"><i class="fa fa-tasks " ></i> آزمون ها </a>';
        }else echo '';
        ?>

        <?php
        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
            echo '<a class="w3-bar-item w3-button text-right" href="log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
        }else{
            echo '<a class="w3-bar-item w3-button text-right" href="log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
        }
        ?>
        <a href="" class="w3-bar-item w3-button text-right"><i class="fa fa-info-circle " ></i> درباره ما </a>
        <br>
        <br>
        <a href="" class="w3-bar-item w3-button  text-center navbar-brand brand-name mx-auto">UniVard</a>
    </div>
    <div class="">
        <button class="w3-button w3-xlarge w3-right nav-btn" onclick="openRightMenu()" style="right:0;top:0">&#9776;</button>
    </div>
    <!--    start header content -->
    <header class="header col-xl-11 mx-auto mt-5">
        <section class="m-5 ">
            <h1 class="display-4">درباره من</h1>
            <p class="text-light">سلام! من آتنا دادخواه هستم</p>
            <p class="text-light">و این سایت رو ایجاد کردم</p>
            <address class="wow animate__fadeInRight" data-wow-duration="1s" data-wow-delay="0s">
                <span class="school text-muted">  شماره تماس:  989020036770+</span>
            </address>
        </section>

    </header>
</div>
<?php require_once "components/footer.php";
echo footer(1);
?>
<!-- Modal -->
<div class="modal fade" id="game" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header mx-auto text-center">
                <h5 class="modal-title text-center"  id="exampleModalLabel">سرگرمی ها</h5>
            </div>
            <div class="modal-body mx-auto text-center">
                <h6 class="text-center"><a href="games/planetsFeature.php" target="_blank">سیارات</a></h6>
                <h6 class="text-center"><a href="games/earth.php" target="_blank">زمین</a></h6>
                <h6 class="text-center"><a href="games/earthCanvas.php" target="_blank">زمین سبز</a></h6>
                <h6 class="text-center"><a href="games/singleEarth.php" target="_blank">زمین و جو</a></h6>
                <h6 class="text-center"><a href="games/flatSolarSystem.php" target="_blank">منظومه شمسی از بالا</a></h6>
                <h6 class="text-center"><a href="games/solarSystem.php" target="_blank">منظومه شمسی سه بعدی</a></h6>
                <h6 class="text-center"><a href="games/jupiter.php" target="_blank">یک سیاره خاص</a></h6>
            </div>
        </div>
    </div>
</div>
</body>
</html>