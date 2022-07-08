<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>آزمون بده | UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="../node_modules/normalize.css/normalize.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <script src="../js/wow.min.js"></script>
    <link rel="stylesheet" href="../uikit-3.9.4/css/uikit-rtl.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/EA/addExam.css">
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../common/commonFunctions.php";?>
    <?php
    //sanitize get and post method values
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    //get all about that specified exam by access key
    function getExam($accessKey){
        global $tb_exam,$connect;
        $sql="SELECT * FROM `$tb_exam` WHERE `access_key`=? ";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$accessKey);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    //this function checks the user answers with true answers and calculates the score
    function checkAnswers($each_score,$values,$id){
        global $tb_exam,$connect;
        $sql="SELECT `right_answers` FROM `$tb_exam` WHERE `access_key`=?";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if ($result->rowCount()){
            $row=json_decode($result->fetch()[0]);
            $rightAnswers=0;
            foreach ($row as $key=>$value){
                if ($value==$values[$key]) $rightAnswers+=1;
            }
            return array(
                "score"=>$rightAnswers * $each_score,
                "right_answers"=>$rightAnswers,
            );
        }else return false;
    }
    //Insert Exam
    function insertExam($each_score,$examName,$value,$id){
        global $tb_score,$connect;
        $getScore=0;
        if (isset($_SESSION['userInfo']))  $getScore=getScore($_SESSION['userInfo']['id']);
        else if (isset($_SESSION['adminInfo'])) $getScore=getScore(0);
        $sql="UPDATE `$tb_score` SET `exam_num`=?,`exam_names`=? WHERE `user_id`=?";
        $result=$connect->prepare($sql);
        $exam_num=$getScore['exam_num'];
        $examLastNames=json_decode($getScore['exam_names'],JSON_UNESCAPED_UNICODE);
        if ($examLastNames) {
            if (!array_key_exists($examName,$examLastNames))$exam_num+=1;
        }else $exam_num+=1;
        if($examLastNames) {
                if ($checkAnswers = checkAnswers($each_score, $value, $id)) {
                    $examLastNames[$examName] = $checkAnswers['score'];
                }
        }
        else{
            if ($checkAnswers = checkAnswers($each_score, $value, $id)) {
                $examLastNames = array(
                    $examName => $checkAnswers['score'],
                );
            }
        }

        $result->bindValue(1,$exam_num);
        $result->bindValue(2,json_encode($examLastNames,JSON_UNESCAPED_UNICODE));
        if (isset($_SESSION['userInfo'])) $result->bindValue(3,$_SESSION['userInfo']['id']);
        else if (isset($_SESSION['adminInfo'])) $result->bindValue(3,0);

        $result->execute();
        return $result;
    }
    //set whole score
    function setScore($id){
        global $connect,$tb_score;
        $sql="SELECT `exam_names` FROM `$tb_score` WHERE `user_id`=?";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if ($result->rowCount()){
            $row=$result->fetch();
            $exam_names=json_decode($row['exam_names'],JSON_UNESCAPED_UNICODE);
            $score=0;
            foreach ($exam_names as $key=>$value){
                $score+=$value;
            }
            $sql_2="UPDATE `$tb_score` SET `score`=? WHERE `user_id`=?";
            $result_2=$connect->prepare($sql_2);
            $result_2->bindValue(1,$score);
            $result_2->bindValue(2,$id);
            $result_2->execute();
            return $result;
        }else return false;
    }
    if (isset($_POST['insertAnswer'])){
             if (isset($_GET['id']) && !empty($_GET['id'])) {
               if (isset($_SESSION['userInfo']) || isset($_SESSION['adminInfo'])){
                   $answers=array();
                   for ($i=1;$i<=examInfo($_GET['id'])['question_num'];$i++){
                       isset($_POST['items_'.$i]) ? array_push($answers,$_POST['items_'.$i][0]): array_push($answers,0);
                   }
                   $insertExam = insertExam(examInfo($_GET['id'])['each_score'],examInfo($_GET['id'])['exam_name'],$answers,$_GET['id']);
                   if ($insertExam){
                       if (isset($_SESSION['userInfo'])) $setScore=setScore($_SESSION['userInfo']['id']);
                       else if (isset($_SESSION['adminInfo'])) $setScore=setScore(0);

                       header("location:../profile/profile.php");
                   }
               }
            }
    }
    ?>
</head>
<body>
<?php
//handle errors
if(isset($_SESSION['emptyQuestion'])) {
    $message = $_SESSION['emptyQuestion'];
    unset($_SESSION['emptyQuestion']);
    echo $message;
}
?>
<?php if (isset($_GET['id']) && !empty($_GET['id'])){ ?>
<?php if (isset($_SESSION['userInfo']) || isset($_SESSION['adminInfo'])){ ?>
            <?php   $getExam=getExam($_GET['id']);
            if ($getExam){?>
                <!--navigation-->
                <header class="pr-lg-5 p-0">
                    <h1 class="text-light font-weight-bold text-lg-right text-center title-exam mr-lg-5 m-0">آزمون بده!</h1>
                </header>
                <div class="hero">
                    <?php require_once "../components/user.php" ?>
                    <?php require_once "../components/admin.php" ?>
                    <nav class="nav mx-5 text-light ">
                        <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php">Univard</a>
                        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
                            echo '<a class="nav-link mt-4" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
                        }else echo ''; ?>
                        <a class="nav-link mt-4" href="../info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
                        <a class="nav-link mt-4" href="#" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo ' <a class="nav-link  mt-4 " href="../info/exam.php"><i class="fa fa-tasks " ></i> آزمون ها </a>';
                        }else echo '';
                        ?>
                        <?php
                        if (isset($_SESSION['userInfo'])  or isset($_SESSION['adminInfo'])){
                            echo '<a class="nav-link mt-4" href="../log/logout.php?logToken='.$_SESSION['logToken'].'"><i class="fa fa-sign-out-alt " ></i> خروج </a>';
                        }else{
                            echo '<a class="nav-link mt-4" href="../log/login.php"><i class="fa fa-sign-in-alt" ></i> ورود </a>';
                        }
                        ?>
                        <a class="nav-link mt-4" href="../about.php"><i class="fa fa-info-circle " ></i> درباره ما </a>
                    </nav>
                    <div class="w3-sidebar w3-bar-block w3-card w3-animate-right nav-side" style="right:0;top:0" id="rightMenu">
                        <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large text-right text-info"> <span class="h4">&times;</span></button>
                        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
                            echo ' <a href="../index.php" class="w3-bar-item w3-button active text-right"><i class="fa fa-home"></i> خانه </a>';
                        }else echo ''; ?>
                        <a href="../info/info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
                        <a href="#" class="w3-bar-item w3-button text-right" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                        <?php
                        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                            echo ' <a href="../info/exam.php" class="w3-bar-item w3-button text-right"><i class="fa fa-tasks " ></i> آزمون ها </a>';
                        }else echo '';
                        ?>
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
                <!--animation part-->
                <div class="canvas">
                    <div class="animation-container  ">
                        <div class="lightning-container">
                            <div class="lightning white"></div>
                            <div class="lightning red"></div>
                        </div>
                        <div class="boom-container">
                            <div class="shape circle big white"></div>
                            <div class="shape circle white"></div>
                            <div class="shape triangle big yellow"></div>
                            <div class="shape disc white"></div>
                            <div class="shape triangle blue"></div>
                        </div>
                        <div class="boom-container second">
                            <div class="shape circle big white"></div>
                            <div class="shape circle white"></div>
                            <div class="shape disc white"></div>
                            <div class="shape triangle blue"></div>
                        </div>
                    </div>
                </div>
                <?php

                $rows=$getExam->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {?>
                    <!--change setting of each exam-->
                    <section class="addExam-section my-5">
                        <div class="container-fluid">
                            <div class="col-12 ">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-10 mx-auto mx-lg-0 change-settings mb-5">
                                        <span  class="fa fa-angle-left text-dark"></span>
                                        <div class="mb-5  mx-auto ">
                                            <div class="select-part p-3 text-light">
                                                <div>
                                                    <span class="d-inline-block label">تعداد سوالات </span>
                                                    <span class="d-block"><?php echo count(json_decode($row['right_answers']));?></span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="d-inline-block label">امتیاز هر سوال </span>
                                                    <span class="d-block"><?php echo $row['each_score'];?></span>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="d-inline-block label">طراح آزمون </span>
                                                    <span class="d-block"><a class="important-text" href="<?php echo $row['user'] == "0" ? '' : '../profile/profile.php?userId='.getId($row['user']); ?>"><?php echo $row['user']=="0" ? "ادمین": $row['user']; ?></a></span>
                                                </div>
                                            </div>
                                            <div class="col mx-auto">
                                                <div class="mt-5 text-center">
                                                    <span class="d-inline-block label">نام آزمون</span>
                                                    <span class="d-block important-text text-center"><?php echo $row['exam_name'];?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <!-- exam questions-->
                                    <div class="col-lg-7 mx-auto mr-lg-5">
                                        <div class="exam-questions p-4 mb-5">
                                            <form action="" method="post" id="exam">
                                                <?php   $question_num=count(json_decode($row['right_answers']));
                                                $questionNums=array(
                                                    1 => 'یک',
                                                    2 => 'دو',
                                                    3 => 'سه',
                                                    4 => 'چهار',
                                                    5 => 'پنج',
                                                    6 => 'شش',
                                                    7 => 'هفت',
                                                    8 => 'هشت',
                                                    9 => 'نه',
                                                    10 => 'ده',
                                                    11 => 'یازده',
                                                    12 => 'دوازده',
                                                    13 => 'سیزده',
                                                    14 => 'چهارده',
                                                    15 => 'پانزده',
                                                    16 => 'شانزده',
                                                    17 => 'هفده',
                                                    18 => 'هجده',
                                                    19 => 'نوزده',
                                                    20 => 'بیست',
                                                    21 => 'بیست و یک',
                                                    22 => 'بیست و دو',
                                                    23 => 'بیست و سه',
                                                    24 => 'بیست و چهار',
                                                    25 => 'بیست و پنج',
                                                    26 => 'بیست و شش',
                                                    27 => 'بیست و هفت',
                                                    28 => 'بیست و هشت',
                                                    29 => 'بیست و نه',
                                                    30=>'سی');
                                                for ($i=1;$i<=$question_num;$i++) {
                                                    $question = json_decode($row['questions'],JSON_UNESCAPED_UNICODE)[$i - 1];
                                                    $items = array_chunk(json_decode($row['items'],JSON_UNESCAPED_UNICODE), 4);
                                                    $questionItems = $items[$i-1] ??  '';
                                                    $questionItems_1= $questionItems ? $questionItems[0]:'';
                                                    $questionItems_2=$questionItems ? $questionItems[1]:'';
                                                    $questionItems_3=$questionItems ? $questionItems[2]:'';
                                                    $questionItems_4=$questionItems ? $questionItems[3]:'';
                                                    ?>
                                                    <div class="mb-2">
                                                        <label for="question-name-<?php echo $i ;?>" class="text-info"> سوال <?php echo $questionNums[$i]; ?></label>
                                                        <p class="question"><?php echo $question; ?></p>
                                                    </div>
                                    <!-- exam items-->
                                                    <div class="row justify-content-between">
                                                        <div class="items col-lg-10 ">
                                                            <div class="mb-2  d-flex">
                                                                <label for="" class="text-dark">
                                                                    <input  type="radio" name="items_<?php echo $i ?>[]" class="mx-1" value="1">
                                                                </label>
                                                                <p class="item"><?php echo $questionItems_1; ?></p>
                                                            </div>
                                                            <div class="mb-2  d-flex">
                                                                <label for="" class="text-dark">
                                                                    <input  type="radio" name="items_<?php echo $i ?>[]" class="mx-1" value="2">
                                                                </label>
                                                                <p class="item"><?php echo $questionItems_2; ?></p>
                                                            </div>
                                                            <div class="mb-2 d-flex">
                                                                <label for="" class="text-dark">
                                                                    <input  type="radio" name="items_<?php echo $i ?>[]" class="mx-1" value="3">
                                                                </label>
                                                                <p class="item"><?php echo $questionItems_3; ?></p>
                                                            </div>
                                                            <div class="mb-2 d-flex">
                                                                <label for="" class="text-dark">
                                                                    <input  type="radio" name="items_<?php echo $i ?>[]" class="mx-1" value="4">
                                                                </label>
                                                                <p class="item"><?php echo $questionItems_4; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                <?php } ?>
                                            </form>
                                        </div>

                                        <div class="action-btn d-flex mb-5">
                                            <section class="mr-5"><button class="btn btn-info" type="submit" name="insertAnswer" form="exam">ثبت پاسخ ها</button>
                                                <a class="btn btn-danger" type="submit" href="../info/exam.php">لغو</a></section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php require_once "../components/footer.php";
                    echo footer();
                    ?>
                <?php  } ?>
           <?php }else header("location:../info/exam.php");  ?>
<?php }else header("location:../info/exam.php"); ?>
<?php }else header("location:../info/exam.php"); ?>
<script src="../js/showExam.js"></script>
<script src="../AreYouSure/jquery.are-you-sure.js"></script>
<!-- to top scroll element -->
<div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
<script>
    $(function() {
        $('form').areYouSure(
            {
                message: 'It looks like you have been editing something. '
                    + 'If you leave before saving, your changes will be lost.'
            }
        );
    });

</script>
<?php require_once "../components/modal.php"?>
</body>
</html>
