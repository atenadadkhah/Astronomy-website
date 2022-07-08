<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/profile/profile.css">
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../connection/connection.php"?>
    <?php require_once "../common/common.php"?>
    <?php require_once "../checks/userExistUpdateFunction.php"?>
    <?php require_once "../common/commonFunctions.php" ?>
    <?php
    //sanitize get and post method values
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    //edit personal information function
    function editInfo($id,$name,$lastName,$userName,$age=null,$city=null,$about=null,$avatar=null){
        global $connect,$tb_users;
        //sanitize values
        $id=intval($id);
        $name=sanitize($name);
        $lastName=sanitize($lastName);
        $userName=sanitize($userName);
        $age=sanitize($age);
        $city=sanitize($city);
        $about=addslashes(sanitize($about));
        $avatar=sanitize($avatar);
        if (strlen($age) ==0){
            $age=NULL;
        }
        if (strlen($city) ==0){
            $city=NULL;
        }
        $sql="UPDATE `$tb_users` SET `name`=?,`lastName`=?,`userName`=?,`age`=?,`city`=?,`about`=?,`avatar`=? WHERE `id`=? LIMIT 1";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$name);
        $result->bindValue(2,$lastName);
        $result->bindValue(3,$userName);
        $result->bindValue(4,$age);
        $result->bindValue(5,$city);
        $result->bindValue(6,$about);
        $result->bindValue(7,$avatar);
        $result->bindValue(8,$id);
        $result->execute();
        $userScore=getScore(getId($userName));
        $userSession=array(
            "id"=>$id,
            "name"=>$name,
            "lastName"=>$lastName,
            "fullName"=>$name . " ".$lastName,
            "age"=>$age,
            "city"=>$city,
            "userName"=>$userName,
            "avatar"=>$avatar,
            "about"=>$about,
            "exam_num"=>$userScore['exam_num'],
            "score"=>$userScore['score'],
            "exam_names"=>$userScore['exam_names']
        );
        $_SESSION['userInfo']=$userSession;
        return $result;
    }
    //get user contact
    function getContacts($user_id){
        global $tb_message,$connect;
        $sql="SELECT * FROM `$tb_message` WHERE `message_from`=? OR `message_to`=? ORDER BY `id` ASC ";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$user_id);
        $result->bindValue(2,$user_id);
        $result->execute();
        if ($result->rowCount()){
            return $result;
        }else return false;
    }

    $thisUser=false;
    $userInfo=null;
    //put info in the page
    if (isset($_SESSION['userInfo'])){
        if (isset($_GET['userId']) ){
            if (!empty($_GET['userId']) ){
                if ($_GET['userId']==$_SESSION['userInfo']['id']){
                    $thisUser=true;
                    $userInfo=getUserById($_SESSION['userInfo']['id']);
                }
                else{
                    $thisUser=false;
                    $userInfo=getUserById($_GET['userId']);
                }
            }else{
                $thisUser=false;
                $userInfo=false;
            }
        }else{
            $thisUser=true;
            $userInfo=getUserById($_SESSION['userInfo']['id']);
        }
    }
    else if (isset($_GET['userId']) and !empty($_GET['userId'])){
        $userInfo=getUserById($_GET['userId']) ?? '';
    }
    else if (!isset($_GET['userId'])){
        $userInfo=null;
    }
    //validation patterns using regex
    $checkName="/^([A-Za-zالف-ی][ ]?){2,30}$/";
    $checkUserName='/^(?=.*[الف-یA-Za-z])[الف-یA-Z0-9a-z-_.]{1,80}$/';
    //update info
    $editInfo=null;
    $getId=null;
    $age=null;
    $city=null;
    if (isset($_POST["btn-edit"])){
        if (preg_match($checkName,$_POST['name']) and preg_match($checkName,$_POST['lastName']) and preg_match($checkUserName,$_POST['userName'])){
            //check if age input is  empty or wrong
            if (empty($_POST['age'])) $age=true;
            else if (preg_match("/^[1-9][0-9]$/",$_POST['age'])) $age=true;
            else $age=false;
            //check if city input is  empty or wrong
            if (empty($_POST['city'])) $city=true;
            else if (preg_match("/^([A-Za-zالف-ی][ ]?){2,30}$/",$_POST['city'])) $city=true;
            else $city=false;
            if ($age==true && $city==true){
                $getId=getId($userInfo['userName']);
                if ($getId){
                    $editInfo=editInfo($getId,$_POST['name'],$_POST['lastName'],$_POST['userName'],$_POST['age'],$_POST['city'],$_POST['about'],$_POST['avatar']);
                    if ($editInfo){
                        $_SESSION['editSuccess'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'اطلاعات با موفقیت ویرایش شد.',
                                        theme:'dark',
                                        progressBarColor:'#04d404',
                                        timeout:3500,
                                        rtl:true,
                                        zindex:99999999999,
                                    });
                           })
                        </script>
                           ";
                        header("location:profile.php");
                        exit;
                    }
                    else header("location:profile.php");
                }
            }
        }
    }
    //message
    $message=null;
    if (isset($_POST['btn-message'])){
        if (isset($_GET['userId']) && !empty($_GET['userId']) && is_numeric($_GET['userId'])){
            if (!empty($_POST['message'])){
                if (isset($_SESSION['userInfo'])){
                    $message=sendMessage($_POST['message'],$_GET['userId'],$_SESSION['userInfo']['id']);
                    if ($message){
                        $_SESSION['messageSuccess'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'پیام با موفقیت ارسال شد.',
                                        theme:'dark',
                                        progressBarColor:'#04d404',
                                        timeout:3500,
                                        rtl:true,
                                        zindex:99999999999,
                                    });
                           })
                        </script>
                           ";
                        header("location:profile.php?userId=".$_GET['userId']);
                        exit;

                    }
                }else if (isset($_SESSION['adminInfo'])){
                    $message=sendMessage($_POST['message'],$_GET['userId'],$_SESSION['adminInfo']['id']);
                    if ($message){
                        $_SESSION['messageSuccess'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'پیام با موفقیت ارسال شد.',
                                        theme:'dark',
                                        progressBarColor:'#04d404',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                        header("location:profile.php?userId=".$_GET['userId']);
                        exit;

                    }
                }
            }
            else{
                $_SESSION['emptyMessage'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'پیام شما نمی تواند خالی باشد',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3000,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                header("location:profile.php?userId=".$_GET['userId']);
                exit;
            }
        }
    }
    ?>
    <script src="../js/form.js"></script>
</head>
<body>
<?php if ($userInfo !== null){?>
<?php
//handle error
if(isset($_SESSION['editSuccess'])) {
    $message = $_SESSION['editSuccess'];
    unset($_SESSION['editSuccess']);
    echo $message;
}
if(isset($_SESSION['messageSuccess'])) {
    $message = $_SESSION['messageSuccess'];
    unset($_SESSION['messageSuccess']);
    echo $message;
}
if (isset($_SESSION['emptyMessage'])) {
    $message = $_SESSION['emptyMessage'];
    unset($_SESSION['emptyMessage']);
    echo $message;
}
?>
<div class="main-content">
    <!--navigation-->
    <div class="hero pb-2">
        <?php require_once "../components/user.php"?>
        <?php require_once "../components/admin.php"?>
        <nav class="nav mx-5 text-light ">
            <a class="navbar-brand brand-name  ml-4 mt-3" href="../index.php"> Univard</a>
            <?php if (!isset($_SESSION['userInfo']) && !isset($_SESSION['adminInfo'])){
                echo '<a class="nav-link mt-4" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
            }else echo ''; ?>
            <a class="nav-link mt-4 " href="../info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
            <a class="nav-link mt-4" href="#" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
            <?php
            if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                echo ' <a class="nav-link  mt-4 " href="../info/exam.php"><i class="fa fa-tasks " ></i> آزمون ها </a>';
            }else echo '';
            ?>
            <?php
            if (isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo'])){
                $_SESSION['logToken']=time()+rand(1,99999);
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
    <!-- Header -->
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; ">
        <!-- Mask -->
        <span class="mask bg-gradient-default opacity-8"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <?php if ($thisUser){ echo ' <div class="row">' ;}?>
            <?php if ($userInfo !== false){?>
            <div class="col-10 col-sm-7 col-lg-8">
                <svg width="90" height="90">
                    <polygon points="0,10 50,0 90,60" fill="yellow"></polygon>
                </svg>
                <svg width="30" height="30">
                    <polygon points="0,0 10,30 30,25" fill="dodgerblue"></polygon>
                </svg>
                <svg width="50" height="50">
                    <polygon points="0,0 0,50 50,30" fill="orangered"></polygon>
                </svg>

                <h1 class="display-4 text-white title-name ">
                    <?php if ($thisUser) echo "سلام ".$userInfo['name'];else if ($userInfo !== null) echo $userInfo['fullName'] ?>
                </h1>

                <p class=" mt-0 mb-5">
                    <?php if ($thisUser) echo 'این پروفایل شماست. شما می توانید در اینجا اطلاعات خود را مشاهده کنید و یا آن ها را تغییر دهید.';else if ($userInfo !== null) echo 'این پروفایل '.$userInfo['userName'].' است.' ; ?>
                </p>

                <a href="#edit" class="btn btn-info"><?php if ($thisUser) echo 'ویرایش پروفایل';else if ($userInfo != null ) echo 'دیدن پروفایل' ;?></a>
                <?php }else{
                    echo "
                <div class='col-lg-5 col-md-8 mx-auto'>
               <div class='alert alert-danger'>
                <p class='text-center text-danger m-auto'>کاربر نامعتبر است</p>
                </div> 
            </div>
               
                ";
                }?>
                <?php if ($thisUser){ echo "</div>" ;}?>
            </div>
        </div>
    </div>
    <?php if ($userInfo !== false){ ?>
        <!-- Page content -->
        <div class="container-fluid mt--7 mb-5" id="edit">
            <div class="row">
                <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                    <div class="card card-profile shadow">
                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <a href="" class="">
                                        <?php
                                        echo '<img src="'.$userInfo['avatar'].'" class="rounded-circle img-fluid profile-circle">';
                                        ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 py-5 py-xl-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span  class="btn btn-sm  btn-info  no-cursor align-self-center my-auto d-block">فعالیت</span>
                                <span  class="btn btn-sm btn-default mr-4 no-cursor">امتیاز</span>
                            </div>
                        </div>
                        <div class="card-body pt-0 pt-md-4">
                            <div class="row">
                                <div class="col">
                                    <div class="card-profile-stats mt-md-5 mx-auto">
                                        <h3 class="h1 text-center mb-4 mt-4 mt-xs-5">
                                            <?php
                                            echo $userInfo['fullName']
                                            ?>
                                            <span class="font-weight-light">
                                        <?php
                                        if ($userInfo['age'] != null){
                                            echo ",".$userInfo['age'];
                                        }
                                        ?>
                                     </span>
                                        </h3>
                                        <?php
                                        if ($userInfo['exam_names']){?>
                                            <div class="d-flex row">
                                                <?php foreach ($userInfo['exam_names'] as $key=>$value) {?>
                                                    <div class=" mx-auto justify-content-center d-flex">
                                                        <a href="../info/showExam.php?id=<?php echo examAccess($key); ?>"><span  class="btn btn-sm btn-info mx-1 mb-2"><?php echo $key; ?></span></a>
                                                        <span  class="btn-default btn btn-sm  mx-1 no-cursor mb-2" title="امتیاز در این آزمون"><?php echo $value; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <div class="whole d-flex justify-content-center">
                                            <div class="mx-3">
                                                <span class="heading text-center"><?php echo $userInfo['exam_num'] ?></span>
                                                <span class="description">آزمون ها</span>
                                            </div>
                                            <div class="mx-3">
                                                <span class="heading text-center"><?php
                                                    if ($userInfo['exam_names']){
                                                        $score=0;
                                                        foreach ($userInfo['exam_names'] as $exam_name) {
                                                            $score+=$exam_name;
                                                        }
                                                        echo $score;
                                                    }else echo 0;
                                                    ?></span>
                                                <span class="description">امتیاز</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center ">
                                <hr class="my-4">
                                <p class="mx-auto text-center about">
                                    <?php
                                    echo $userInfo['about'];
                                    ?>
                                </p>
                                <?php if (strlen($userInfo['about']) > 200) {?>
                                    <span  class="show-more btn text-info">بیشتر</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($thisUser){ ?>
                        <div class="card card-profile  shadow mt-4">
                            <div class="card-header text-center bg-default border-0  py-4">
                                <div class="d-flex  align-items-center flex-wrap">
                                    <span  class="btn btn-sm  btn-info d-block no-cursor mb-2 align-self-center  d-block">مخاطب ها</span>
                                    <?php
                                    $getContacts=getContacts($_SESSION['userInfo']['id']);
                                    if ($getContacts) {
                                        $rows = $getContacts->fetchAll(PDO::FETCH_ASSOC);
                                        $listContacts = [];
                                        foreach ($rows as $row) {
                                            if ($row['message_to'] != $_SESSION['userInfo']['id']){
                                                if (!in_array($row['message_to'],$listContacts,true)) $listContacts[] = $row['message_to'] ;
                                            }
                                            if ($row['message_from'] != $_SESSION['userInfo']['id']) {
                                                if (!in_array($row['message_from'], $listContacts, true)) $listContacts[] = $row['message_from'];
                                            }
                                        }

                                        foreach ($listContacts as $listContact){?>

                                            <button value="<?php echo $listContact?>" class="btn d-block mb-2 contact btn-sm  contact-button mx-2 btn-primary align-self-center  d-block"><?php echo getUserById($listContact)['userName'] ?></button>
                                        <?php   }
                                    } ?>
                                </div>
                            </div>
                            <div class="card-body chat pt-3">
                                <div class="row">
                                    <div class="col p-0 chat-height">
                                        <div class="card-profile-stats  mt-md-5 pt-0">
                                            <div class="chat-thread p-0 ">
                                                <?php
                                                $getContacts=getContacts($_SESSION['userInfo']['id']);
                                                if (!$getContacts){?>
                                                    <div class="error box mx-auto p-0 pt-2 ">
                                                        <div class="face sad">
                                                            <div class="eye-left"></div>
                                                            <div class="mouth frown"></div>
                                                            <div class="eye-right"></div>
                                                        </div>
                                                        <div class="shadow follow"></div>
                                                        <div class="message-error">
                                                            <h5>تاکنون ارتباطی نداشته اید!</h5>
                                                        </div>
                                                    </div>
                                                <?php }?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php
                            $getContacts=getContacts($_SESSION['userInfo']['id']);
                            if ($getContacts == true){ ?>
                                <div class="card-footer chat-footer pb-0">
                                    <?php
                                    $getContacts=getContacts($_SESSION['userInfo']['id']);
                                    $first_contact=null;
                                    $message_to="message_to";
                                    if (isset($_SESSION['userInfo']) || isset($_SESSION['adminInfo'])){
                                        if ($getContacts){
                                            $temp_contact=$getContacts->fetchAll(PDO::FETCH_ASSOC);
                                            if ($temp_contact[0][$message_to]===$_SESSION['userInfo']['id']) $message_to="message_from";
                                            $first_contact=$temp_contact[0][$message_to];
                                        }
                                        ?>
                                        <div class="text-center ">
                                            <form action="" method="post" data-query="form-chat">
                                                <div class="form-group focused d-flex">
                                                    <label class="form-control-label" for="message" ></label>
                                                    <input type="hidden" class="chat-to" value="<?php echo $first_contact;?>" name="contact_id">
                                                    <input  id="message"  name="message"  class="form-control form-control-alternative " placeholder="پیامی بنویسید ">
                                                    <button type="submit " class="mx-2 img-button" name="btn-contact-message"><img width="50" height="50" src="../img/paper-plane.png" alt="send"></button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php }?>
                                </div>
                            <?php }?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h2 class="mb-0">  <?php if ($thisUser) echo "اطلاعات من"; else echo "اطلاعات حساب"?></h2>
                                </div>
                                <?php if ($thisUser): ?>
                                    <div class="col-4 text-right">
                                        <span class="btn btn-sm btn-primary no-cursor">تنظیمات</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($thisUser):?>
                                <form method="post" class="form-yes">
                                    <h6 class="heading-small text-muted mb-4">حساب کاربری</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-username" >نام کاربری</label>
                                                    <input type="text" id="input-username"  name="userName" class="form-control form-control-alternative" placeholder="نام کاربری" value="<?php echo $userInfo['userName'] ?>" onblur="validation(this);checkUserExist(this,'update')" data-validation="userName">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-first-name">نام</label>
                                                    <input type="text" id="input-first-name"  name="name" class="form-control form-control-alternative" placeholder="نام" value="<?php echo $userInfo['name'] ?>" onblur="validation(this)" data-validation="name">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-last-name">نام خانوادگی</label>
                                                    <input type="text" id="input-last-name"  name="lastName" class="form-control form-control-alternative" placeholder="نام خانوادگی" value="<?php echo $userInfo['lastName'] ?>" onblur="validation(this)" data-validation="lastName">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-age">سن</label>
                                                    <input type="text" id="input-age" max="120" min="1"  name="age" class="form-control form-control-alternative" placeholder="سن" value="<?php echo $userInfo['age']?>" onblur="validation(this)" data-validation="age">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group focused">
                                                    <label class="form-control-label" for="input-about">شهر</label>
                                                    <input type="text" id="input-about"  name="city" class="form-control form-control-alternative" placeholder="شهر" value="<?php echo $userInfo['city']?>" onblur="validation(this)" data-validation="city">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <!-- Description -->
                                    <h6 class="heading-small text-muted mb-4">درباره من</h6>
                                    <div >
                                        <div class="form-group focused">
                                            <label for="about">درباره من</label>
                                            <textarea rows="4" id="about"  name="about"  class="form-control form-control-alternative" placeholder="می توانی درباره خودت بنویسی..."><?php echo $userInfo['about'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-5">
                                        <h6 class="heading-small text-muted mb-4">آواتار</h6>
                                        <div class="row">
                                            <?php
                                            for ($i=1;$i<=11;$i++){
                                                if (strpos($userInfo['avatar'],"-".$i."-.png")) $active="active-avatar";
                                                else $active="";
                                                echo "<div class='img-avatar m-1 $active'><img src='../img/avatar/-".$i."-.png' alt='Avatar' class='avatar'></div>";
                                            }
                                            ?>
                                            <input type="hidden" name="avatar" value="<?php echo $userInfo['avatar'] ?>">
                                        </div>
                                    </div>
                                    <button class="btn btn-info" type="submit" name="btn-edit">ویرایش</button>
                                </form>
                            <?php endif;?>
                            <?php if ($thisUser != true):?>
                                <h6 class="heading-small text-muted mb-4">حساب کاربری</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <details open>
                                                <summary >
                                                    <span class="label">نام کاربری</span>
                                                </summary>
                                                <dfn style="font-style: normal" ><span><?php echo $userInfo['userName'] ?></span></dfn>
                                            </details>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <details open>
                                                <summary>
                                                    <span class="label">نام</span>
                                                </summary>
                                                <dfn style="font-style: normal" ><span><?php echo $userInfo['name'] ?></span></dfn>
                                            </details>
                                        </div>
                                        <div class="col-lg-6">
                                            <details open>
                                                <summary>
                                                    <span class="label">نام خانوادگی</span>
                                                </summary>
                                                <dfn style="font-style: normal" ><span><?php echo $userInfo['lastName'] ?></span></dfn>
                                            </details>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <details open>
                                                <summary>
                                                    <span class="label">سن</span>
                                                </summary>
                                                <dfn style="font-style: normal" ><span><?php echo $userInfo['age'] ?></span></dfn>
                                            </details>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <details open>
                                                <summary>
                                                    <span class="label">شهر</span>
                                                </summary>
                                                <dfn style="font-style: normal" ><span><?php echo $userInfo['city'] ?></span></dfn>
                                            </details>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <!-- Description -->
                                <h6 class="heading-small text-muted mb-4">درباره من</h6>
                                <div class="pl-lg-4">
                                    <details open>
                                        <summary>
                                            <span class="label"> درباره <?php echo $userInfo['name'] ?></span>
                                        </summary>
                                        <dfn style="font-style: normal" ><span><?php echo $userInfo['about'] ?></span></dfn>
                                    </details>
                                </div>
                                <?php if(isset($_SESSION['userInfo']) || isset($_SESSION['adminInfo'])){ ?>
                                    <br>
                                    <hr>
                                    <br>
                                    <div class="col-lg-6">
                                        <form action="" method="post">
                                            <div class="form-group focused d-flex">
                                                <label class="form-control-label" for="message" ></label>
                                                <input  id="message"  name="message"  class="form-control form-control-alternative " placeholder="پیام به <?php echo $userInfo['name']?>">
                                                <button type="submit " class="mx-2 img-button" name="btn-message"><img width="50" height="50" src="../img/paper-plane.png" alt="send"></button>
                                            </div>
                                        </form>
                                    </div>
                                <?php } ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../js/profile.js"></script>
    <?php }?>
    <?php require_once "../components/footer.php"; echo footer();?>
    <!-- to top scroll element -->
    <div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
    <?php require_once "../components/modal.php"?>
    <?php }else header("location:../log/login.php") ?>
</body>
</html>