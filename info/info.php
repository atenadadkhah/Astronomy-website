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
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <script src="../js/wow.min.js"></script>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/info/info.css">
    <?php require_once "../connection/connection.php" ?>
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../common/commonFunctions.php";?>
    <?php
    //get all info by limit (depend on is user logged in or not)
    function getInfo($limit){
        global $connect,$tb_content;
        $sql="SELECT * FROM `$tb_content` ORDER BY `id` ASC $limit";
        $result=$connect->query($sql);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    //delete info function
    function deleteContent(){
        global $tb_content,$connect;
        if(isset($_POST['select'])){
            $check=$_POST['select'];
            for ($i=0;$i<count($_POST['select']);$i++){
                $checked=$check[$i];
                $sql="DELETE FROM `$tb_content` WHERE `id`=?";
                $result=$connect->prepare($sql);
                $result->bindValue(1,$checked);
                $result->execute();
            }
            return $result;
        }else{
            return false;
        }
    }
    $deleteContent=null;
    if (isset($_SESSION['adminInfo'])){
        if (isset($_POST['btn-delete'])){
            if (!empty($_POST['select'])){
                $deleteContent=deleteContent();
            }else{
                $_SESSION['notSelected'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'هیچ محتوایی انتخاب نشده است',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                header("location:info.php");
                exit;
            }
        }
    }
    ?>
</head>
<body>
<?php
//handle error
if(isset($_SESSION['notSelected'])) {
    $message = $_SESSION['notSelected'];
    unset($_SESSION['notSelected']);
    echo $message;
}
?>
<div class="hero">
    <?php require_once "../components/user.php" ?>
    <?php require_once "../components/admin.php" ?>
    <nav class="nav mx-5 text-light ">
        <a class="navbar-brand brand-name mt-4 ml-4" href="../index.php">Univard</a>
        <?php if (!isset($_SESSION['userInfo']) and !isset($_SESSION['adminInfo'])){
            echo '<a class="nav-link mt-4" href="../index.php"> <i class="fa fa-home"></i> خانه </a>';
        }else echo ''; ?>
        <a class="nav-link active-nav mt-4" href="">  <i class="fa fa-book-open"></i> مطالب  </a>
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
        <a href="" class="w3-bar-item w3-button  text-right"><i class="fa fa-book-open"></i> مطالب</a>
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
            <?php
            if (isset($_SESSION['adminInfo'])){?>
                <a href="../EA/addInfo.php" class="btn btn-primary btn-sm mb-5 mx-2  text-center"> <i class="fa fa-plus"></i> اضافه کردن مطلب </a>
            <form method="post" id="delete-content">
                <button class="btn btn-danger btn-sm mb-5 mx-2  text-center" type="submit" name="btn-delete"> <i class="fa fa-trash"></i> حذف مطلب </button>
            </form>
            <?php } ?>
        </div>
        <div class="row">
                    <?php
                    $getInfo=null;
                    $limit=null;
                    $signIn=null;
                   isset($_SESSION['userInfo']) or isset($_SESSION['adminInfo']) ? $limit='' : $limit="LIMIT 0,6";
                    $getInfo=getInfo($limit);
                    $limit != '' ? $signIn="<div class='alert alert-danger'><p class='text-danger text-center'>برای دیدن مطالب بیشتر لطفا <a href='../log/login.php' class='text-dark'>وارد</a> شوید</p></div>" : $signIn='';
                    if ($getInfo){
                        $rows=$getInfo->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) :?>
                        <div class="col-lg-3 col-6 card-showInfo">
                            <div class="card" style="border-radius: 0 !important;">
                               <?php if (isset($_SESSION['adminInfo'])){?>
                                <div class="float-right">
                                    <label  for="cbx"></label>
                                    <input  id="cbx" type="checkbox" name="select[]" value="<?php echo $row['id'] ?>" form="delete-content"/>
                                </div>
                                <?php } ?>
                                <img src="<?php echo $row['first_pic'] ?>" alt="img_show" style="width: 100%">
                                <div class="container">
                                    <h4 class="text-center my-2"><?php echo $row['title'] ?></h4>
                                    <p class="text-center"> <?php if (strlen($row['every_content']) > 30) echo mb_substr($row['every_content'],0,50) ."..."?></p>
                                    <a href="showInfo.php?id=<?php echo $row['id'] ?>" class="btn btn-info btn-sm mb-3 mx-auto text-center">ادامه مطلب</a>
                                    <?php
                                    if (isset($_SESSION['adminInfo'])){?>
                                        <a href="../EA/editInfo.php?id=<?php echo $row['id'] ?>" class="mb-3 float-left text-info" title="ویرایش"><i class="fa fa-edit"></i></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                       <?php endforeach;}?>
        </div>
        <?php echo $signIn; ?>

    </div>
</section>
<?php require_once "../components/footer.php";
echo footer();
?>
<?php require_once "../components/modal.php"?>
</body>
</html>