<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/form.js"></script>
    <link rel="stylesheet" href="../css/login/login.css">
    <title>ورود/عضویت | UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <?php
    require_once "../XYZ.php";
    require_once "../common/common.php";
    require_once "../connection/connection.php";
    require_once "../checks/userExistFunction.php";
    require_once "../common/browser.php";
    require_once "../common/commonFunctions.php";
    //sanitize get and post method values
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /*--------------------------check if user has been logged in---------------------------------*/
    if (isset($_SESSION['userInfo'])) header("location:../profile/profile.php?userId=" . $_SESSION['userInfo']['id'] . "");
    else if (isset($_SESSION['adminInfo'])) header("location:../info/info.php");
    /*----------------------------------------------------------------------------------------------*/
    //create and save users in a database
   function setUsers($name,$lastName,$userName,$password){
       global  $connect,$tb_users;
       $name=sanitize($name);
       $lastName=sanitize($lastName);
       $userName=sanitize($userName);
       $unHashPass=$password;
       $password=hashPass(sanitize($password));
       $sql="INSERT `$tb_users` SET `name`=?,`lastName`=?,`userName`=?,`password`=?";
       $result=$connect->prepare($sql);
       $result->bindValue(1,$name);
       $result->bindValue(2,$lastName);
       $result->bindValue(3,$userName);
       $result->bindValue(4,$password);
       $result->execute();
       return $result;
   }
   //login function
    function doLogin($userName,$password){
       global $connect,$tb_users,$tb_log;
       $userName=sanitize($userName);
       $unHashedPass=sanitize($password);
       $password=hashPass($unHashedPass);
       $sql="SELECT `id`,`name`,`lastName`,`userName`,`avatar`,`about`,`age`,`city` FROM `$tb_users` WHERE `userName`=? AND `password`=?";
       $result=$connect->prepare($sql);
       $result->bindValue(1,$userName);
       $result->bindValue(2,$password);
       $result->execute();
       if ($result->rowCount()) {
           $row=$result->fetch(PDO::FETCH_ASSOC);
           $userScore=getScore(getId($row['userName']));
           $userSession=array(
                   "id"=>$row['id'],
               "name"=>$row['name'],
               "lastName"=>$row['lastName'],
               "fullName"=>$row['name'] . " ".$row['lastName'],
               "age"=>$row['age'],
               "city"=>$row['city'],
               "userName"=>$row['userName'],
               "avatar"=>$row['avatar'],
               "about"=>$row['about'],
               "exam_num"=>$userScore['exam_num'],
               "exam_names"=>json_decode($userScore['exam_names'],JSON_UNESCAPED_UNICODE),
           );
           //set session if success login happened
           $_SESSION['userInfo']=$userSession;
           $sql="INSERT `$tb_log` SET `userId`=?,`userIp`=?,`userName`=?,`browser`=?,`details`=?";
           $result=$connect->prepare($sql);
           $result->bindValue(1,$row['id']);
           $result->bindValue(2,$_SERVER["REMOTE_ADDR"]);
           $result->bindValue(3,$row['userName']);
           $result->bindValue(4,getUserBrowser($_SERVER['HTTP_USER_AGENT']));
           $result->bindValue(5,'login');
           $result->execute();
           //set cookie for `remember me` option
           if (!empty($_POST['rememberMe'])){
               setcookie("userName",$userName,time()+2628000);
               setcookie("password",$unHashedPass,time()+2628000);
           }else{
               if (isset($_COOKIE['userName'])){
                   setcookie("userName","");
               }
               if (isset($_COOKIE['password'])){
                   setcookie("password","");
               }
           }
           return $result;
       }
       else return false;
    }
    //insert user in users_score table when sign up
    function insertUserScore($id){
       global $tb_score,$connect;
       $sql="INSERT `$tb_score` SET `user_id`=?";
       $result=$connect->prepare($sql);
       $result->bindValue(1,$id);
       $result->execute();
       return $result;
    }
   // validation patterns using Regex
    $checkName='/^([A-Za-zالف-ی][ ]?){2,40}$/';
    $checkUserName='/^(?=.*[الف-یA-Za-z])[الف-یA-Z0-9a-z-_.]{1,80}$/';
    $checkPassword='/^(?=.*[A-Za-z])(?=.*\d)[A-za-z\d@^#$!%*?&]{8,100}$/';
    //end validation patterns
    $query=null;
    $userExist=null;
    $login=null;
    //sign up process
   if (isset($_POST['btn-signUp'])){
      if (!isset($_SESSION['userInfo'])){
          if (preg_match($checkName,$_POST['name']) and preg_match($checkName,$_POST['lastName']) and preg_match($checkUserName,$_POST['userName']) and preg_match($checkPassword,$_POST['password'])){
              $userExist=isUserExist($_POST['userName']);
              if ($userExist != true){
                  $query=setUsers($_POST['name'],$_POST['lastName'],$_POST['userName'],$_POST['password']);
                  if ($query){
                      $insertUserScore=insertUserScore(getId($_POST['userName']));
                      $_SESSION['successRegister'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'اطلاعات شما با موفقیت ثبت شد',
                                        theme:'dark',
                                        progressBarColor:'#04d404',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                      header("location:login.php");
                      exit;
                  }
              }
          }
      }
   }
   //sign in process
   if (isset($_POST['btn-signIn'])){
        if (!isset($_SESSION['userInfo'])) {
            if (!empty($_POST['username-signIn']) and !empty($_POST['password-signUp'])) {
                //check if remember me checkbox checked
                if (empty($_POST['rememberMe'])){
                    $_POST['rememberMe']=null;
                }
                $login = doLogin($_POST['username-signIn'], $_POST['password-signUp']);
                if ($login) {
                    header("location:../profile/profile.php?userId=" . $_SESSION['userInfo']['id'] . "");
                    exit;
                } else {
                    $_SESSION['wrongAnswer'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'نام کاربری یا رمز عبور اشتباه است',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                    header("location:login.php");
                    exit;
                }
            }
            else if (empty($_POST['username-signIn'])) {
                $_SESSION['emptyUserName'] = "
                   <script>
                   $(function (){
                        iziToast.show({
                                message: 'نام کاربری نمی تواند خالی باشد',
                                theme:'dark',
                              progressBarColor:'#e2043e',
                               timeout:3500,
                               rtl:true,
                            });
                   })
                </script>
                   ";
                header("location:login.php");
                exit;
            }
            else if (empty($_POST['password-signUp'])) {
                $_SESSION['emptyPass'] = "
                   <script>
                   $(function (){
                        iziToast.show({
                                message: 'رمز عبور نمی تواند خالی باشد',
                                theme:'dark',
                                progressBarColor:'#e2043e',
                                timeout:3500,
                                rtl:true,
                            });
                   })
                </script>
                   ";
                header("location:login.php");
                exit;
            }
        }
   }
   /*---------------     handle errors      ---------------*/
   //error for empty inputs
    if(isset($_SESSION['emptyUserName'])) {
        $message = $_SESSION['emptyUserName'];
        unset($_SESSION['emptyUserName']);
        echo $message;
    }
    if(isset($_SESSION['emptyPass'])) {
        $message = $_SESSION['emptyPass'];
        unset($_SESSION['emptyPass']);
        echo $message;
    }
    //error for wrong values
    if(isset($_SESSION['wrongAnswer'])) {
        $message = $_SESSION['wrongAnswer'];
        unset($_SESSION['wrongAnswer']);
        echo $message;
    }
    if(isset($_SESSION['successRegister'])) {
        $message = $_SESSION['successRegister'];
        unset($_SESSION['successRegister']);
        echo $message;
    }
    ?>
</head>
<body>
<?php
if (!isset($_SESSION['userInfo']) && !isset($_SESSION['adminInfo'])){?>
    <section class='log-section'>
        <svg width="100" height="40" class="color-1">
            <polygon points="0,0 100,0 0,40" ></polygon>
        </svg>
        <svg width="300" height="100" class="color-2">
            <polygon points="300,0 300,100 0,100" ></polygon>
        </svg>
        <!--navigation-->
        <div class="hero">
            <nav class="nav mx-5 text-light ">
                <a class="navbar-brand brand-name  ml-4" href="../index.php"> Univard</a>
                <?php if (!isset($_SESSION['userInfo'])){
                    echo '<a class="nav-link" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
                }else echo ''; ?>
                <a class="nav-link " href="../info/info.php">  <i class="fa fa-book-open"></i> مطالب  </a>
                <a class="nav-link " href="#" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                <a class="nav-link active-nav" href=""><i class="fa fa-sign-in " ></i> ورود</a>
                <a class="nav-link" href="../about.php"><i class="fa fa-info-circle " ></i> درباره ما </a>
            </nav>
            <div class="w3-sidebar w3-bar-block w3-card w3-animate-right nav-side" style="right:0;top:0" id="rightMenu">
                <button onclick="closeRightMenu()" class="w3-bar-item w3-button w3-large text-right text-info"> <span class="h4">&times;</span></button>
                <?php if (!isset($_SESSION['userInfo'])){
                    echo ' <a href="../index.php" class="w3-bar-item w3-button active text-right"><i class="fa fa-home"></i> خانه </a>';
                }else echo ''; ?>
                <a href="../info/info.php" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
                <a href="#" class="w3-bar-item w3-button text-right" data-toggle="modal" data-target="#game"><i class="fa fa-gamepad " ></i> سرگرمی </a>
                <a href="" class="w3-bar-item w3-button text-right"> <i class="fa fa-sign-in " ></i> ورود</a>
                <a href="../about.php" class="w3-bar-item w3-button text-right"><i class="fa fa-info-circle " ></i> درباره ما </a>
                <br>
                <br>
                <a href="" class="w3-bar-item w3-button  text-center navbar-brand brand-name mx-auto">UniVard</a>
            </div>
            <div class="">
                <button class="w3-button w3-xlarge w3-right nav-btn mt-2" onclick="openRightMenu()" style="right:0;top:0">&#9776;</button>
            </div>
        </div>
        <!--form-->
        <div class="form-section mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <div class="container-fluid">
                            <div class="frame">
                                <div class="nav">
                                    <ul class="links">
                                        <li class="signIn-active"><a class="btn-change">ورود</a></li>
                                        <li class="signUp-inactive"><a class="btn-change">حساب ندارید؟</a></li>
                                    </ul>
                                </div>
                                <div class="form-box">
                                    <form class="form-signIn" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                        <div class="form-group">
                                            <label for="username-login">نام کاربری</label>
                                            <input class="form-styling" type="text" id="username-login" name="username-signIn" placeholder="" value="<?php if (isset($_COOKIE['userName'])) echo $_COOKIE['userName']?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="password-login">رمز عبور</label>
                                            <input class="form-styling" type="password" name="password-signUp" id="password-login" placeholder="" value="<?php if (isset($_COOKIE['password'])) echo $_COOKIE['password']?>"/>
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" id="checkbox" name="rememberMe" <?php if (isset($_COOKIE['userName']) && isset($_COOKIE['password'])) echo 'checked';else echo '' ?>/>
                                            <label for="checkbox" ><span class="ui"></span>مرا به خاطر بسپار!</label>
                                        </div>
                                        <button  class="btn-signIn" type="submit" name="btn-signIn" ><span><i class="fa fa-sign-in-alt"></i> ورود</span></button>
                                    </form>

                                    <form class="form-signUp form-yes" action="" method="post" name="form" enctype="multipart/form-data">
                                        <div class="form-group ">
                                            <label for="name">نام</label>
                                            <input class="form-styling signUp" type="text" name="name" id="name" onblur="validation(this)" data-validation="name"/>
                                        </div>
                                        <div class="form-group ">
                                            <label for="lastName"> نام خانوادگی</label>
                                            <input class="form-styling signUp" type="text" name="lastName" id="lastName" onblur="validation(this)" data-validation="lastName"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="userName">نام کاربری</label>
                                            <input class="form-styling signUp" type="text" name="userName" id="userName" onblur="validation(this);checkUserExist(this)" data-validation="userName"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">رمز عبور</label>
                                            <input class="form-styling signUp" type="password" name="password" id="password" onblur="validation(this)" data-validation="password" />
                                        </div>
                                        <button  class="btn-signUp " type="submit" name="btn-signUp"><span><i class="fa fa-user-plus"></i> عضویت</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--footer-->
<?php require_once "../components/footer.php";
echo footer();
?>
    <script>
        $(function() {
            $(".btn-change").click(function() {
                $(".form-signIn").toggleClass("form-signIn-left");
                $(".form-signUp").toggleClass("form-signUp-left")
                $(".frame").toggleClass("frame-long");
                $(".signUp-inactive").toggleClass("signUp-active");
                $(".signIn-active").toggleClass("signIn-inactive");
                $(this).removeClass("idle").addClass("active");
            });
        });
    </script>
    <?php require_once "../components/modal.php"?>
<?php }?>
</body>
</html>
