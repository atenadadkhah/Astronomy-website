<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>آزمون ها | UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <script src="../js/wow.min.js"></script>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/info/exam.css">
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/commonFunctions.php";?>
    <?php
    //get all exam and their content
    function getExam(){
        global $connect,$tb_exam;
        $sql="SELECT * FROM `$tb_exam` ORDER BY `id` ASC ";
        $result=$connect->query($sql);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    //delete a exam
    function deleteSelectedExam(){
        global $tb_exam,$connect;
        if(isset($_POST['select'])){
            $check=$_POST['select'];
            for ($i=0;$i<count($_POST['select']);$i++){
                $checked=$check[$i];
                $sql="DELETE FROM `$tb_exam` WHERE `access_key`=?";
                $result=$connect->prepare($sql);
                $result->bindValue(1,$checked);
                $result->execute();
            }
            return $result;
        }else{
            return false;
        }
    }
    //get the temporary exams created by users
    function getTempExam(){
        global $connect,$tb_temp_exam;
        $sql="SELECT * FROM `$tb_temp_exam` ORDER BY `id` ASC ";
        $result=$connect->query($sql);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    //get the best user profiles
    function getBest(){
        global $connect,$tb_score,$tb_users;
        $sql="SELECT $tb_users.*,score FROM `$tb_users` INNER JOIN $tb_score ON $tb_users.id = $tb_score.user_id ORDER BY score DESC  LIMIT 5";
                $result=$connect->query($sql);
                $result->execute();
                if ($result->rowCount())return $result;
                else return false;
    }
    $deleteSelectedExam=null;
    if (isset($_SESSION['adminInfo'])){
        if (isset($_POST['btn-deleteExam'])){
            if (!empty($_POST['select'])){
                $deleteSelectedExam=deleteSelectedExam();
                header("location:exam.php");
            }else{
                $_SESSION['notSelectedExam'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'هیچ آزمونی انتخاب نشده است',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                header("location:exam.php");
                exit;
            }
        }
    }
    ?>
</head>
<body>
<?php
//handle errors
if(isset($_SESSION['notSelectedExam'])) {
    $message = $_SESSION['notSelectedExam'];
    unset($_SESSION['notSelectedExam']);
    echo $message;
}
if(isset($_SESSION['userExamSaved'])) {
    $message = $_SESSION['userExamSaved'];
    unset($_SESSION['userExamSaved']);
    echo $message;
}
?>
<?php if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){ ?>
<div class="hero">
    <?php require_once "../components/user.php" ?>
    <?php require_once "../components/admin.php" ?>
<!--    navigation-->
    <nav class="nav mx-5 text-light ">
        <a class="navbar-brand brand-name mt-4 ml-4" href="../index.php">Univard</a>
        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
            echo '<a class="nav-link mt-4" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
        }else echo ''; ?>
        <a class="nav-link mt-4" href="info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
        <a class="nav-link mt-4" href="#" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
        <?php
        if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
            echo ' <a class="nav-link  mt-4 active-nav " href="../info/exam.php"><i class="fa fa-tasks " ></i> آزمون ها </a>';
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
        <a href="info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
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
<section class="showInfo py-5">
    <div class="container">
        <div class="row mx-auto">
                <a href="../EA/addExam.php" class="btn btn-primary btn-sm mb-5 mx-2  text-center"> <i class="fa fa-plus"></i> ساخت آزمون </a>
            <?php if (isset($_SESSION['adminInfo'])){?>
                <form method="post" id="delete-exam">
                    <button class="btn btn-danger btn-sm mb-5 mx-2  text-center" type="submit" name="btn-deleteExam"> <i class="fa fa-trash"></i> حذف آزمون</button>
                </form>
            <?php } ?>
        </div>
        <div class="row">
<!--            show temporary exams if admin was login-->
            <?php
            if (isset($_SESSION['adminInfo'])) $getTempExam=getTempExam();
            else $getTempExam=null;
            $getExam=getExam();
            if ($getExam){
                $rows=$getExam->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) :?>
                    <div class="col-lg-3 col-sm-6 col-12 card-showInfo">
                        <div class="card" style="border-radius: 0 !important;">
                            <?php if (isset($_SESSION['adminInfo'])){?>
                                <div class="float-right">
                                    <label  for="cbx"></label>
                                    <input  id="cbx" type="checkbox" name="select[]" value="<?php echo $row['access_key'] ?>" form="delete-exam"/>
                                </div>
                            <?php } ?>
                            <img src="<?php echo $row['exam_img'] ?>" alt="exam_icon" style="width: 100%" class="w-50 mx-auto">
                            <div class="container mt-2">
                                <h4 class="text-center my-2"><?php echo $row['exam_name'] ?></h4>
                                <section class="details text-muted d-flex justify-content-sm-between mt-2 justify-content-around">
                                    <span>تعداد سوالات: <?php echo count(json_decode($row['right_answers'])) ?></span>
                                    <span>امتیاز کل: <?php echo count(json_decode($row['right_answers']))*$row['each_score'] ?></span>
                                </section>
                                <div class="d-flex justify-content-sm-between mt-2 justify-content-around">
                                    <address class="text-muted mt-3">نویسنده: <a href="<?php echo $row['user'] == "0" ? '' : '../profile/profile.php?userId='.getId($row['user']); ?>"><?php echo $row['user']=="0" ? "مدیر": $row['user']; ?></a></address>
                                    <a href="showExam.php?id=<?php echo $row['access_key'] ?>" class="btn btn-info btn-sm mb-3 mt-2 text-center">آزمون بده!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;};
            if ($getTempExam && isset($_SESSION['adminInfo'])){
                $rows=$getTempExam->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) :?>
                    <div class="col-lg-3 col-sm-6 col-12 card-showInfo">
                        <div class="card" style="border-radius: 0 !important;">
                            <img src="<?php echo $row['exam_img'] ?>" alt="exam_icon" style="width: 100%" class="w-50 mx-auto">
                            <div class="container mt-2">
                                <h4 class="text-center my-2"><?php echo $row['exam_name'] ?></h4>
                                <section class="details text-muted d-flex justify-content-sm-between mt-2 justify-content-around">
                                    <span>تعداد سوالات: <?php echo count(json_decode($row['right_answers'])) ?></span>
                                    <span>امتیاز کل: <?php echo count(json_decode($row['right_answers']))*$row['each_score'] ?></span>
                                </section>
                                <div class="d-flex justify-content-sm-between mt-2 justify-content-around">
                                <address class="text-muted mt-3">نویسنده: <a href="<?php echo $row['user'] == "0" ? '' : '../profile/profile.php?userId='.getId($row['user']); ?>"><?php echo $row['user']=="0" ? "مدیر": $row['user']; ?></a></address>
                                <a href="showTempExam.php?id=<?php echo $row['access_key'] ?>" class="btn btn-warning btn-sm mb-3 mt-2 text-center">بررسی آزمون</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;};
            ?>

        </div>
<!--        best users-->
        <div class="row">
            <div class="col-lg-5 float-right mt-5">
                <section class="ranking">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="best">برترین ها</div>
                        <div class="best">امتیاز</div>
                    </div>
                    <hr>
                    <?php
                    $getBest=getBest();
                    if ($getBest){
                        $rows=$getBest->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {?>
                            <section class="avatar-section ml-4 mt-3 mb-1 d-flex justify-content-between">
                                <a href="../profile/profile.php?userId=<?php echo $row['id'] ?>">
                                    <img src="<?php echo $row['avatar'] ?>" alt="Avatar" class="avatar">
                                    <span class="userName mx-5" style="font-weight: 600;font-family: 'vazir-regular';"><?php echo $row['userName'] ?></span>
                                </a>
                                <div class="score best"><?php echo getScore($row['id'])['score']?></div>
                            </section>

                     <?php  }} ?>
                </section>
            </div>
        </div>
    </div>
</section>
<?php require_once "../components/footer.php";
    echo footer();
?>
<?php } else header("location:../index.php")?>
<?php require_once "../components/modal.php"?>
</body>
</html>
