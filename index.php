<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="هیجان ماجراجویی میان فضا و کیهان و به دست آوردن اطلاعات فراوان درباره جهان اطراف مان. رقابت و سرگرمی و همچنین آماده شدن برای چالش های پیش رو.">
    <title>محل گردش در کیهان - یادگیری و آموزش درباره جهان اطراف ما | UniVard</title>
    <link rel="icon" href="icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" type="text/css">
    <link rel="stylesheet" href="node_modules/animate.css/animate.min.css" type="text/css">
    <script src="js/wow.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <script src="node_modules/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="uikit-3.9.4/js/uikit.min.js" type="text/javascript"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="./slick-1.8.1/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick-1.8.1/slick/slick-theme.css">
    <link rel="stylesheet" href="css/mainPage/mainPage.css" type="text/css">
    <script src="js/main.js" type="text/javascript"></script>
    <style>
        html, body {margin: 0;padding: 0;}* {box-sizing: border-box;}.slider {width: 85%;margin: 100px auto;}.slick-slide {margin: 0px 20px;}.slick-slide img {width: 100%;}.slick-prev:before, .slick-next:before {color: black;}.slick-slide {transition: all ease-in-out .3s;opacity: .2;}.slick-active {opacity: .5;}.slick-current {opacity: 1;}
    </style>
    <?php require_once "XYZ.php" ?>
    <?php
    /*--------------------------check if user has been logged in---------------------------------*/
    if (isset($_SESSION['userInfo'])) header("location:profile/profile.php");
    else if (isset($_SESSION['adminInfo'])) header("location:info/info.php");
    ?>
</head>
<body>
<!-- site hero -->
<div class="hero pb-5 pb-md-0">
&#8204;
        <nav class="nav mx-5 text-light">
            <a class="navbar-brand brand-name mt-4  ml-4" href="../index.php">UniVard</a>
            <?php if (!isset($_SESSION['userInfo'])){
                echo '<a class="nav-link mt-4 active-nav" href=""> <i class="fa fa-home"></i> خانه </a>';
            }else echo ''; ?>
            <a class="nav-link  mt-4" href="info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
            <a class="nav-link  mt-4 " href="#"  data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
            <?php
            if (isset($_SESSION['userInfo'])){
                echo '<a class="nav-link mt-4" href="log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
            }else{
                echo '<a class="nav-link mt-4" href="log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
            }
            ?>
            <a class="nav-link  mt-4 " href="about.php"><i class="fa fa-info-circle " ></i> درباره ما </a>
        </nav>
        <div class="w3-sidebar w3-bar-block w3-card w3-animate-right nav-side" style="right:0;top:0" id="rightMenu">
            <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large text-right text-info"> <span class="h4">&times;</span></button>
            <?php if (!isset($_SESSION['userInfo'])){
                echo ' <a href="" class="w3-bar-item w3-button active text-right"><i class="fa fa-home"></i> خانه </a>';
            }else echo ''; ?>
            <a href="info/info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
            <a href="#" class="w3-bar-item w3-button text-right" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
            <?php
            if (isset($_SESSION['userInfo'])){
                echo '<a class="w3-bar-item w3-button text-right" href="log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
            }else{
                echo '<a class="w3-bar-item w3-button text-right" href="log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
            }
            ?>
            <a href="about.php" class="w3-bar-item w3-button text-right"><i class="fa fa-info-circle " ></i> درباره ما </a>
            <br>
            <br>
            <a href="" class="w3-bar-item w3-button  text-center navbar-brand brand-name mx-auto">UniVard</a>
        </div>


<!--    start header content -->
    <header class="header d-lg-flex justify-content-center col-xl-11 mx-auto pt-5 pt-lg-0">
        <div class="">
            <button class="w3-button w3-xlarge  w3-right nav-btn" onclick="openRightMenu()" style="right:0;top:0">&#9776;</button>
        </div>
            <section class="m-5 pt-5 pt-lg-0">
                <h1 class="text-light font-weight-bold text-lg-right text-center title-site">ماجراجویی کیهان برای همه</h1>
                <p class="mt-5 text-muted text-lg-right text-center p-hero">
                    با گشت و گذار در میان کیهان خودتان را محک بزنید و یک کارنامه نجومی درست کنید، امتیاز جمع کنید، خودتان را با بقیه بسنجید و دیگران را به چالش بکشید!
                </p>
                <a href="log/login.php" class="btn btn-info mt-4">شروع کنید</a>

            </section>
            <div id="earth" class=" p-md-5">
                <canvas id="scene" class="mx-auto"></canvas>
            </div>
    </header>
</div>
<!--introduction part of website-->
<div class="introduction w-100 d-flex ">
    <svg width="220" height="100" class="tri-introduction">
        <polygon points="0,0 200,0 200,85" />
    </svg>
    <div class="col-xl-4 col-md-6 col-9 card-explain " data-wow-duration=".7s" data-wow-delay="0s">
        <div class="card">
            <div class="card-header bg-white">
                <h2 class="card-title text-center">تجربه خودتان را در نجوم بالا ببرید </h2>
            </div>
            <div class="card-body"><p class="text-center text-muted">از مطالب متنوع استفاده کنید و از بقیه جلو بزنبید! با آزمون هایی که ميسازید، می توانید امتیازتان را بالا ببرید وآزمون های دیگران را پشت سر بگذارید.  سعی کنید پروفایل خود را به پروفایل های برتر نزدیک کنید.
                    <br><strong class="text-center mt-3 d-inline-block"> میان ستارگان بدرخشید...</strong> </p>

            </div>
        </div>
    </div>
    <div class="introduction-img">
    </div>
</div>
<!--guide to how to work with-->
<div class="guide-section">
    <div class="container-fluid pt-5">
        <div class="row px-5 pt-5 pb-2">
            <div class="col-lg-3 col-md-6 mb-5 wow animate__slideInUp" data-wow-duration="0.7s" data-wow-delay="0s">
                <div class="box mx-auto box-purple d-flex mb-4">
                    <img src="icons/world-book-day.png" alt="book-open-icon" class="m-auto">
                </div>
                <div class="guide-description">
                <h4 class="text-center text-muted">بیاموزید</h4>
                <p class="text-center">می توانید از مطالب جالب و مورد علاقه ی خود بهره بگیرید و دانشتان را روز به روز بیشتر کنید.</p>
         </div>
   </div>

            <div class="col-lg-3 col-md-6 mb-5 wow animate__slideInUp" data-wow-duration="0.9s" data-wow-delay="0s">
                <div class="box mx-auto box-red d-flex mb-4">
                    <img src="icons/answer.png" alt="response-icon" class="m-auto">
                </div>
                <div class="guide-description">
                <h4 class="text-center text-muted">ارزیابی کنید</h4>
                <p class="text-center">از میان آزمون های منتخب بهترین ها را انتخاب کنید. خودتان هم می توانید آزمون طراحی کنید و امتیاز های بیشتری جمع آوری کنید.</p>
                </div>
                </div>

            <div class="col-lg-3 col-md-6 mb-5 wow animate__slideInUp" data-wow-duration="1s" data-wow-delay="0s">
                <div class="box mx-auto box-yellow d-flex mb-4">
                    <img src="icons/puzzle.png" alt="puzzle-icon" class="m-auto">
                </div>
                <div class="guide-description">
                <h4 class="text-center text-muted">سرگرم شوید</h4>
                <p class="text-center">با بازی های مرتبط، در حین سرگرمی، بیشتر و بیشتر می آموزید و لذت می برید.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5 wow animate__slideInUp" data-wow-duration="1.2s" data-wow-delay="0s">
                <div class="box mx-auto box-blue d-flex mb-4">
                    <img src="icons/winner.png" alt="prize-icon" class="m-auto">
                </div>
                <div class="guide-description">
                    <h4 class="text-center text-muted">امتیاز بگیرید</h4>
                    <p class="text-center">در نهایت حاصل تلاش های شما در پروفایلی که می سازید، قابل مشاهده خواهد بود.
                        <br> وقتشه به خودتان افتخار کنید!</p>
                </div>
            </div>
        </div>
    </div>
    <svg width="280" height="100" class="tri-guide">
        <polygon points="0,0 0,100 280,100" />
    </svg>
</div>
<!--more explanations-->
<div class="adventure-section pb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="astronaut">
                    <svg width="100" height="70" class="tri-astronaut tri-astronaut-1" >
                        <polygon points="90,0 0,20 70,60"/>
                    </svg>
                    <svg width="100" height="70" class="tri-astronaut tri-astronaut-2">
                        <polygon points="30,0 30,50 80,60" />
                    </svg>
                    <svg width="100" height="70" class="tri-astronaut tri-astronaut-3">
                        <polygon points="80,0 10,20 60,50" />
                    </svg>
                    <img src="img/space_travel.png" alt="astronaut-travel-earth" class="img-fluid wow animate__backInDown" data-wow-duration="1.2s" data-wow-delay="0s">
                </div>
            </div>
            <div class="col-lg-6 col-11 mt-5 pt-5 mx-auto">
                <h3 class="display-4 font-weight-normal text-light adventure-title text-lg-right text-center mb-5">با سفر کردن میان سیارات ماجراجویی کنید</h3>
                <p class="text-muted">سفر شما در فضا از اینجا آغاز می شود. مانند یک فضانورد در میان کیهان ماجراجویی کنید و موانع را کنار بزنید؛ با چالش ها رو به رو شوید و فعالیت های دیگران را زیر نظر بگیرید.</p>
                <br>
                <p class="text-muted">کارنامه نجومی شما با فعالیت هایی که داشته اید، تکمیل می شود پس هرچقدر فعال تر باشید پروفایل  <strong>برتری</strong> خواهید داشت.</p>
            </div>
        </div>
    </div>
</div>
<!--some information-->
<div class="galaxy-count">
    <div class="container-fluid py-5">
        <div class="row ">
            <div class="col-12 py-5">
                <h4 class="text-center text-muted info">برخی مطالب</h4>
                <div class="content mr-xl-5">
                    <section class="center slider">
                        <a href="info/showInfo.php?id=19">
                            <div>
                                <img src="img/planets/earth.png"  title="زمین" alt="earth-vector">
                                <h6 class="text-center planet-title">زمین</h6>
                            </div>
                        </a>
                        <a href="info/showInfo.php?id=18">
                            <div>
                                <img src="img/planets/jupiter.png" title="مشتری" alt="jupiter-vector">
                                <h6 class="text-center planet-title">مشتری</h6>
                            </div>
                        </a>
                        <a href="info/showInfo.php?id=15">
                            <div>
                                <img src="img/planets/mars.png" title="مریخ" alt="mars-vector">
                                <h6 class="text-center planet-title">مریخ</h6>
                            </div>
                        </a>
                        <a href="info/showInfo.php?id=16">
                            <div>
                                <img src="img/planets/neptune.png" title="نپتون" alt="neptune-vector">
                                <h6 class="text-center planet-title">نپتون</h6>
                            </div>
                        </a>
                        <a href="info/showInfo.php?id=20">
                            <div>
                                <img src="img/planets/uranus.png" title="اورانوس" alt="uranus-vector">
                                <h6 class="text-center planet-title">اورانوس</h6>
                            </div>
                        </a>
                        <a href="info/showInfo.php?id=21">
                            <div>
                                <img src="img/planets/venus.png" title="ناهید" alt="venus-vector">
                                <h6 class="text-center planet-title">ناهید</h6>
                            </div>
                        </a>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<!--footer-->
<?php require_once "components/footer.php"; echo footer(1);?>
<!-- to top scroll element -->
<div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
<script src="js/canvasHeader.js"></script>
<script type="text/javascript" src="js/jquery.fittext.js">
</script>
<script type="text/javascript" src="slick-1.8.1/slick/slick.min.js"></script>
<script type="text/javascript">
        $(".center").slick({
            dots: true,
            infinite: true,
            centerMode: true,
            slidesToShow: 3,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll:2,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 930,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll:1,
                        infinite: true,
                        dots: true
                    }
                },
            ]

    });
</script>
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
