<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>مطالب | UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/animate.css/animate.css">
    <script src="../js/wow.min.js"></script>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/EA/addInfo.css">
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../connection/connection.php"?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../common/commonFunctions.php";?>
    <?php
    //get all information about specified subject
    function getInfo($id){
        global $tb_content,$connect;
        $sql="SELECT * FROM `$tb_content` WHERE `id`=?";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    ?>
</head>
<body>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) ){?>
    <?php
    if ((isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])) or getPublished($_GET['id'])!=0) {?>
        <?php $getInfo=getInfo($_GET['id']);
        if ($getInfo){
            $rows=$getInfo->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {?>
                <!--navigation-->
                <div class="hero pb-5 pb-md-0" style=' background-image:linear-gradient(to bottom,#000, rgba(0, 0, 0, 0.75),rgba(0, 0, 0, 0.25)), url(<?php echo $row["first_pic"] ?>);'>
                    &#8204;
                    <?php require_once "../components/user.php"?>
                    <?php require_once "../components/admin.php"?>
                    <nav class="nav mx-5 text-light">
                        <a class="navbar-brand brand-name  mt-4 ml-4" href="../index.php">Univard</a>
                        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
                            echo '<a class="nav-link mt-4" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
                        }else echo ''; ?>
                        <a class="nav-link  mt-4" href="../info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
                        <a class="nav-link  mt-4 " href="#" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo ' <a class="nav-link  mt-4 " href="../info/exam.php"><i class="fa fa-tasks " ></i> آزمون ها </a>';
                        }else echo '';
                        ?>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo '<a class="nav-link mt-4" href="../log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
                        }else{
                            echo '<a class="nav-link mt-4" href="../log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
                        }
                        ?>
                        <a class="nav-link  mt-4 "href="../about.php"><i class="fa fa-info-circle " ></i> درباره ما </a>
                    </nav>
                    <div class="w3-sidebar w3-bar-block w3-card w3-animate-right nav-side" style="right:0;top:0" id="rightMenu">
                        <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large text-right text-info"> <span class="h4">&times;</span></button>
                        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
                            echo ' <a href="../index.php" class="w3-bar-item w3-button active text-right"><i class="fa fa-home"></i> خانه </a>';
                        }else echo ''; ?>
                        <a href="../info/info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo ' <a href="../info/exam.php" class="w3-bar-item w3-button text-right"><i class="fa fa-tasks " ></i> آزمون ها </a>';
                        }else echo '';
                        ?>
                        <a href="#" class="w3-bar-item w3-button text-right" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo '<a class="w3-bar-item w3-button text-right" href="../log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
                        }else{
                            echo '<a class="w3-bar-item w3-button text-right" href="../log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
                        }
                        ?>
                        <a href="../about.php" class="w3-bar-item w3-button text-right"><i class="fa fa-info-circle " ></i> درباره ما </a>
                        <br>
                        <br>
                        <a href="" class="w3-bar-item w3-button  text-center navbar-brand brand-name mx-auto">UniVard</a>
                    </div>
                    <div class="">
                        <button class="w3-button w3-xlarge w3-right nav-btn mt-2" onclick="openRightMenu()" style="right:0;top:0">&#9776;</button>
                    </div>
                </div>
                <!--header-->
                <header class=" ">
                    <h1 class=" text-center title-info"><?php echo $row['title'] ?></h1>
                </header>
                <!--short explanation-->
                <div class="explain">
                    <div class="container-fluid  py-5">
                        <div class="row">
                            <div class="col-lg-5 col-11 mt-5 pt-5 mx-auto">
                                <h3 class="display-4 font-weight-normal text-light title-explain  text-lg-right text-center mb-5">همه چیز درباره <?php echo $row['title'] ?></h3>
                                <p class="h2 text-muted">
                                    <?php echo  nl2br($row['every_content']) ?>
                                </p>
                            </div>
                            <div class="col-lg-6 ">
                                <img src="<?php echo $row['cute_pic'] ?>" alt="astronaut-planet" class="img-fluid wow wow animate__fadeInUp" data-wow-duration="1.2s" data-wow-delay="0s">
                            </div>
                        </div>
                    </div>
                </div>
                <!--more information about subject-->
                <div class="info">
                    <?php
                    $section_titles=explode(",",$row['section_titles']);
                    $section_contents=explode(",",$row['section_contents']);
                    $section_pics=explode(",",$row['section_pics']);
                    ?>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 py-5 properties">
                                <div class="property pt-5">
                                    <h5 class="text-center text-md-right"><?php echo $section_titles[0] ?></h5>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property text-center text-md-right">
                                        <?php echo nl2br($section_contents[0]); ?>
                                    </div>
                                </div>
                                <?php if (!empty($section_titles[1]) && !empty($section_contents[1])){?>
                                    <div class="property py-5">
                                        <h5 class="text-center text-md-right"><?php echo $section_titles[1] ?></h5>
                                        <div class="divider mx-auto mx-md-0 my-4"></div>
                                        <div class="explain-property text-center text-md-right">
                                            <?php echo nl2br($section_contents[1]); ?>
                                        </div>

                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6 img img-1" style='background-image: linear-gradient(to left, rgba(0, 0, 0, 0.4),transparent),url(<?php echo $section_pics[0] ?>);'>
                                <div class="row">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 py-5 img img-2 order-md-1 order-2" style='background-image: linear-gradient(to left, rgba(0, 0, 0, 0.4),transparent),url(<?php echo $section_pics[1] ?>);'>
                                <div class="row">
                                </div>
                            </div>
                            <div class="col-md-6 py-5 properties order-md-2 order-1">
                                <div class="property pt-5">
                                    <h5 class="text-center text-md-right"><?php echo $section_titles[2] ?></h5>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property text-center text-md-right">
                                        <?php echo nl2br($section_contents[2]); ?>
                                    </div>
                                </div>
                                <?php if (!empty($section_titles[3]) && !empty($section_contents[3])){?>
                                    <div class="property py-5">
                                        <h5 class="text-center text-md-right"><?php echo $section_titles[3] ?></h5>
                                        <div class="divider mx-auto mx-md-0 my-4"></div>
                                        <div class="explain-property text-center text-md-right">
                                            <?php echo nl2br($section_contents[3]); ?>
                                        </div>

                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!--      The last Information          -->
                    <div class="end-info">
                        <?php
                        $end_contents=explode(",",$row['end_contents']);
                        ?>
                        <svg width="250" height="80">
                            <polygon points="0,0 250,0 250,80"></polygon>
                        </svg>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 mx-auto py-5 ">
                                    <p class="text-center ">
                                        <?php
                                        echo nl2br($end_contents[0]);
                                        ?>
                                    </p>
                                    <div class="divider mx-auto my-5 bg-warning"></div>
                                    <p class="text-center">
                                        <?php
                                        echo nl2br($end_contents[1]);
                                        ?>
                                    </p>
                                </div>
                                <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                    <img src="<?php echo $row['end_pic'] ?>" alt="planet-graphic" class="img-fluid">
                                </div>
                                <div class="col-lg-6 ">
                                    <span class="source">منبع: </span>
                                    <p class=" text-muted mb-4">
                                        <?php
                                        echo nl2br($row['source']);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php require_once "../components/footer.php";
            echo footer();
            ?>
                <!-- to top scroll element -->
                <div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
                <script src="../js/jquery.fittext.js"></script>
            <?php }} else { header("location:../info/info.php");}?>
    <?php  } else header("location:../info/info.php");?>
  <?php }else  header("location:../info/info.php"); ?>
<?php require_once "../components/modal.php"?>
</body>
</html>



