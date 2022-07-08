<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>آزمون بساز | UniVard</title>
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
    <?php require_once "../checks/examExistFunction.php"?>
    <?php
    //sanitize get and post method values
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    //check whether the true answer value is valid
    function rightAnswerCheck($rightAnswers){
        foreach ($rightAnswers as $rightAnswer){
            if (!is_numeric($rightAnswer) or $rightAnswer >4 or $rightAnswer<1) return false;
            else return true;
        }
    }
    //add exam here
if (isset($_POST['addQuestion'])){
    //check whether each value is empty
    if (!empty($_POST['question_num']) && !empty($_POST['each_score']) && !empty($_POST['exam_name']) && !empty($_POST['exam_name']) && count(array_filter(array_map("trim",$_POST['questions']))) == count($_POST['questions']) && count(array_filter(array_map("trim",$_POST['items']))) == count($_POST['items']) && count(array_filter(array_map("trim",$_POST['right_answers']))) == count($_POST['right_answers'])){
        /*------------------------------------*/
        //check whether the values are acceptable
        $examNamePattern="/^([A-Z?!0-9a-zالف-ی][ ]?){2,80}$/";
        if (is_numeric($_POST['question_num']) && is_numeric($_POST['each_score']) && $_POST['question_num'] <=20 && $_POST['each_score'] <=10 && $_POST['each_score']>=1 && $_POST['question_num'] >=1 && preg_match($examNamePattern,$_POST['exam_name'])){
            /*------------------------------------*/
            //check whether the exam name is duplicated
            if (!isExamExist($_POST['exam_name'])){
                /*-------------------------*/
                //check if all right answer values are true
                if (rightAnswerCheck($_POST['right_answers'])){
                    $addExam=addExam($_POST['exam_name'],$_POST['questions'],$_POST['items'],$_POST['right_answers'],$_POST['each_score']);
                    if ($addExam){
                        unset($_SESSION['examValues']);
                        if (!isset($_SESSION['adminInfo'])){
                            $_SESSION['userExamSaved'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'آزمون شما با موفقیت ثبت شد. در صورت تایید، این آزمون قابل مشاهده خواهد شد.',
                                        theme:'dark',
                                        progressBarColor:'#04d404',
                                        timeout:4500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                        }
                        header("location:../info/exam.php");
                        exit;
                    }
                }else{
                    $_SESSION['wrongRightAnswer'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'لطفا فیلد گزینه های صحیح را دوباره چک کنید',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                    header("location:addExam.php");
                    exit;
                }
            }
        }else{
            $_SESSION['wrongExamValue'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'لطفا تنظیمات آزمون را دوباره چک کنید',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
            header("location:addExam.php");
            exit;
        }
    }else {
        //save values in session if an error occurred
        $_SESSION['examValues'] = array(
            "exam_name" => $_POST['exam_name'],
            "each_score" => $_POST['each_score'],
            "question_num" => $_POST['question_num'],
            "questions" => $_POST['questions'],
            "items" => $_POST['items'],
            "right_answers" => $_POST['right_answers'],
        );
        $_SESSION['emptyQuestion'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'پر کردن فیلد هایی که با * مشخص شده اجباری است',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                        zindex:99999999999,
                                    });
                           })
                        </script>
                           ";
        header("location:addExam.php");
        exit;

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
if(isset($_SESSION['wrongExamValue'])) {
    $message = $_SESSION['wrongExamValue'];
    unset($_SESSION['wrongExamValue']);
    echo $message;
}
if(isset($_SESSION['wrongRightAnswer'])) {
    $message = $_SESSION['wrongRightAnswer'];
    unset($_SESSION['wrongRightAnswer']);
    echo $message;
}
?>
<?php if (isset($_SESSION['userInfo']) || isset($_SESSION['adminInfo'])){ ?>
    <!--navigation-->
    <header class="pr-lg-5 p-0">
        <h1 class="text-light font-weight-bold text-lg-right text-center title-exam mr-lg-5 m-0">آزمون بساز!</h1>
    </header>
    <div class="hero">
        <?php require_once "../components/user.php" ?>
        <?php require_once "../components/admin.php" ?>
        <nav class="nav mx-5 text-light ">
            <a class="navbar-brand brand-name mt-3 ml-4" href="../index.php"> Univard</a>
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
    <!--change setting of each exam-->
    <section class="addExam-section my-5">
        <div class="container-fluid">
            <div class="col-12 ">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-10 mx-auto mx-lg-0 change-settings mb-5">
                        <span  class="fa fa-angle-left text-dark"></span>
                        <div class="mb-5  mx-auto">
                            <div class="select-part p-3 text-light">
                                <form action="" method="post" id="exam-setting">
                                    <div>
                                        <label for="question-num">تعداد سوالات <span class="require">*</span></label>
                                        <input type="number" name="question_num" id="question-num" class="uk-input" max="20" min="1" value="<?php if (isset($_SESSION['examValues'])) echo $_SESSION['examValues']['question_num'];else echo 5;?>">
                                    </div>
                                    <div class="mt-2">
                                        <label for="each-score">امتیاز هر سوال <span class="require">*</span></label>
                                        <input type="number" name="each_score" id="each-score" class="uk-input" max="10" min="1" value="<?php if (isset($_SESSION['examValues'])) echo $_SESSION['examValues']['each_score'];else echo 1;?>">
                                    </div>
                                </form>
                            </div>
                            <div class="col">
                                <div class="mt-3">
                                    <label for="name text-dark">نام آزمون <span class="require">*</span></label>
                                    <input type="text" name="exam_name" id="name" class="uk-input" form="exam-setting" value="<?php if (isset($_SESSION['examValues'])) echo $_SESSION['examValues']['exam_name'];else echo '';?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 mx-auto mr-lg-5">
                        <div class="exam-questions p-4 mb-5">
                        </div>
                        <div class="action-btn mb-5">
                            <button class="btn btn-info" type="submit" name="addQuestion" form="exam-setting">ثبت آزمون</button>
                            <a class="btn btn-danger" type="submit" href="../info/exam.php">لغو</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../js/addExam.js"></script>
    <script src="../AreYouSure/jquery.are-you-sure.js"></script>
<!--    AreYouSure script-->
    <script>
            $('form').areYouSure();
    </script>
    <!-- to top scroll element -->
    <div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
    <?php require_once "../components/footer.php";
    echo footer();
    ?>
<?php }else header("location:../info/exam.php"); ?>
<?php require_once "../components/modal.php"?>

</body>
</html>
