<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> محتوا | UniVard</title>
    <link rel="icon" href="../icons/logo.ico" sizes="32x32">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../node_modules/animate.css/animate.css">
    <script src="../js/wow.min.js"></script>
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="../node_modules/izitoast/dist/css/iziToast.css">
    <link rel="stylesheet" href="../uikit-3.9.4/css/uikit-rtl.css">
    <script src="../node_modules/jquery/dist/jquery.js"></script>
    <script src="../uikit-3.9.4/js/uikit.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="../node_modules/izitoast/dist/js/iziToast.js"></script>
    <script src="https://ucarecdn.com/libs/widget/3.x/uploadcare.full.min.js"></script>
    <script src="https://ucarecdn.com/libs/widget-tab-effects/1.x/uploadcare.tab-effects.lang.en.min.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="../css/EA/addInfo.css">
    <?php require_once "../XYZ.php" ?>
    <?php require_once "../connection/connection.php"?>
    <?php require_once "../common/common.php"?>
    <?php
    //sanitize get and post method values
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    // get all information about specified subject
    function getInfo($id){
        global $tb_content,$connect;
        $sql="SELECT * FROM `$tb_content` WHERE `id`=?";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$id);
        $result->execute();
        if ($result->rowCount()) return $result;
        else return false;
    }
    // edit info function to update content in database
    function editInfo($id,$first_pic,	$title,$every_content,$cute_pic,$section_pics,$section_titles,$section_contents,$end_contents,$end_pic,$source){
        global $tb_content,$connect;
        $sql="UPDATE `$tb_content` SET `first_pic`=?,`title`=?,`every_content`=?,`cute_pic`=?,`section_pics`=?,`section_titles`=?,`section_contents`=?,`end_contents`=?,`end_pic`=?,`source`=? WHERE `id`=?";
        $title=sanitize($title);
        $every_content=sanitize($every_content);
        $section_pics=implode(",",$section_pics);
        $section_titles=sanitize(implode(",",$section_titles));
        $section_contents=addslashes(sanitize(implode(",",$section_contents)));
        $end_contents=addslashes(sanitize(implode(",",$end_contents)));
        $source=sanitize($source);
        $result=$connect->prepare($sql);
        $result->bindValue(1,$first_pic);
        $result->bindValue(2,$title);
        $result->bindValue(3,$every_content);
        $result->bindValue(4,$cute_pic);
        $result->bindValue(5,$section_pics);
        $result->bindValue(6,$section_titles);
        $result->bindValue(7,$section_contents);
        $result->bindValue(8,$end_contents);
        $result->bindValue(9,$end_pic);
        $result->bindValue(10,$source);
        $result->bindValue(11,$id);
        $result->execute();
        return $result;

    }
    if (isset($_POST['btn-edit'])){
        if (isset($_SESSION['adminInfo'])){
            //check whether each value is empty
            if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                if (!empty($_POST['first_pic']) && !empty($_POST['title']) && !empty($_POST['every_content']) && !empty($_POST['section_pics'][0]) && !empty($_POST['section_pics'][1]) && !empty($_POST['section_titles'][0]) && !empty($_POST['section_titles'][2]) && !empty($_POST['section_contents'][0]) && !empty($_POST['section_contents'][2]) && !empty($_POST['end_contents'][0]) && !empty($_POST['end_contents'][1]) && !empty($_POST['end_pic']) && !empty($_POST['source'])) {
                    //end checking
                    $insertInfo = editInfo($_GET['id'], $_POST['first_pic'], $_POST['title'], $_POST['every_content'], $_POST['cute_pic'], $_POST['section_pics'], $_POST['section_titles'], $_POST['section_contents'], $_POST['end_contents'], $_POST['end_pic'],$_POST['source']);
                    if ($insertInfo) {
                        if (isset($_SESSION['editedValues'])) unset($_SESSION['editedValues']);
                        header("location:../info/info.php");
                    } else {
                        $_SESSION['errorUpload'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'هنگام آپلود خطایی رخ داد.',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:4000,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                        header("location:editInfo.php");
                        exit;
                    }
                } else {
                    $id = $_GET['id'];
                    $_SESSION['emptyField'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'پرکردن فیلد هایی که با * مشخص شده، اجباری است.',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:4000,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";
                    $_SESSION['editedValues'] = array(
                        "title" => $_POST['title'],
                        "every_content" => $_POST['every_content'],
                        "section_titles_1" => $_POST['section_titles'][0],
                        "section_titles_2" => $_POST['section_titles'][1],
                        "section_titles_3" => $_POST['section_titles'][2],
                        "section_titles_4" => $_POST['section_titles'][3],
                        "section_contents_1" => $_POST['section_contents'][0],
                        "section_contents_2" => $_POST['section_contents'][1],
                        "section_contents_3" => $_POST['section_contents'][2],
                        "section_contents_4" => $_POST['section_contents'][3],
                        "end_contents_1" => $_POST['end_contents'][0],
                        "end_contents_2" => $_POST['end_contents'][1],
                        "source"=>$_POST['source'],
                    );
                    $_SESSION['editImgUpload'] = "
                           <script>
                           $(function (){
                                iziToast.show({
                                        message: 'لطفا مجددا عکس ها را بارگزاری نمایید.',
                                        theme:'dark',
                                        progressBarColor:'#e2043e',
                                        timeout:3500,
                                        rtl:true,
                                    });
                           })
                        </script>
                           ";

                    header("location:editInfo?id=$id");
                    exit;
                }
            }
        }
    }
    ?>
</head>
<body>
<?php
if (isset($_SESSION['adminInfo'])){
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
        $getInfo=getInfo($_GET['id']);
        if ($getInfo){
            $rows=$getInfo->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row){?>
                <?php
                //handle errors
                if(isset($_SESSION['emptyField'])) {
                    $message = $_SESSION['emptyField'];
                    unset($_SESSION['emptyField']);
                    echo $message;
                }
                if(isset($_SESSION['editImgUpload'])) {
                    $message = $_SESSION['editImgUpload'];
                    unset($_SESSION['editImgUpload']);
                    echo $message;
                }
                if(isset($_SESSION['errorUpload'])) {
                    $message = $_SESSION['errorUpload'];
                    unset($_SESSION['errorUpload']);
                    echo $message;
                }
                ?>
                <!--navigation-->
                <div class="hero pb-5 pb-md-0">
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
                        <a class="nav-link  mt-4 " href="../about.php"><i class="fa fa-info-circle " ></i> درباره ما </a>
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
                    <form method="post" id="content" enctype="multipart/form-data">
                        <div class="row mb-3 text-muted">
                            <label for="title">موضوع <span class="require">*</span></label>
                            <input type="text" name="title" class="uk-input text-center" placeholder="موضوع" id="title" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['title'] : $row['title'];?>">
                        </div>
                        <div class="row text-muted " >
                            <label for="first_pic" class="input-group">عکس اولیه <span class="require">*</span></label>
                            <input type="hidden" role="uploadcare-uploader" data-public-key="f7132a7ffef09fea16de"  data-tabs="file camera url gdrive gphotos dropbox" data-effects="crop, blur, flip, rotate, sharp" name="first_pic"  id="first_pic" value="<?php echo $row['first_pic']; ?>"/>
                        </div>
                    </form>
                </header>
                <!--short explanation-->
                <div class="explain">
                    <div class="container-fluid  py-5">
                        <div class="row justify-content-around">
                            <div class="col-lg-5 col-9 mx-lg-0 mx-auto mt-5 pt-5 ">
                                <h3 class="display-4 font-weight-normal text-light title-explain  text-lg-right text-center mb-5"> </h3>
                                <div class="row text-muted mb-4">
                                    <label for="every_content">توضیح مختصر <span class="require">*</span></label>
                                    <textarea name="every_content" class="uk-textarea" rows="10" placeholder="توضیحاتی کوتاه وارد کن..." id="every_content" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['every_content'] : $row['every_content'] ;?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4 d-flex  justify-content-center align-items-center ">
                                <div class="text-muted mr-5">
                                    <label for="cute_pic" class="input-group" >عکس را بارگذاری کنید <span class="require">*</span></label>
                                    <input type="hidden" role="uploadcare-uploader" data-public-key="f7132a7ffef09fea16de"  data-tabs="file camera url gdrive gphotos dropbox" data-effects="crop, blur, flip, rotate, sharp" name="cute_pic"  id="cute_pic" form="content" value="<?php echo $row['cute_pic']; ?>"/>
                                </div>
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
                                    <div class="mb-2 text-muted text-center text-md-right">
                                        <label for="section-title-1"><span class="require">*</span> </label>
                                        <input type="text" name="section_titles[]" class="uk-input  w-50" placeholder="یک ویژگی" id="section-title-1" form="content" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_titles_1'] : $section_titles[0] ;?>">
                                    </div>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property">
                                        <div class=" text-muted mb-2">
                                            <label for="section_content-1">توضیح  <span class="require">*</span></label>
                                            <textarea name="section_contents[]" class="uk-textarea" rows="5" placeholder="توضیح مربوطه را وارد کن..." id="section_content-1" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_contents_1'] : $section_contents[0] ;?></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="property py-5">
                                    <div class="mb-2 text-muted text-center text-md-right">
                                        <label for="section-title-2 "></label>
                                        <input type="text" name="section_titles[]" class="uk-input  w-50" placeholder="یک ویژگی" id="section-title-2" form="content" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_titles_2'] : $section_titles[1] ;?>" >
                                    </div>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property">
                                        <div class=" text-muted mb-2">
                                            <label for="section_content-2">توضیح </label>
                                            <textarea name="section_contents[]" class="uk-textarea" rows="5" placeholder="توضیح مربوطه را وارد کن..." id="section_content-2" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_contents_2'] : $section_contents[1] ;?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6 img img-1 pt-5">
                                <div class="row pt-5">
                                    <div class="text-muted m-auto pt-5">
                                        <label for="section-pic-1" class="input-group">عکس مربوطه <span class="require">*</span></label>
                                        <input type="hidden" role="uploadcare-uploader" data-public-key="f7132a7ffef09fea16de"  data-tabs="file camera url gdrive gphotos dropbox" data-effects="crop, blur, flip, rotate, sharp" name="section_pics[]"  id="section-pic-1" form="content" value="<?php echo $section_pics[0]; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 img img-1 pt-5">
                                <div class="row pt-5">
                                    <div class="text-muted m-auto pt-5">
                                        <label for="section-pic-2" class="input-group">عکس مربوطه <span class="require">*</span></label>
                                        <input type="hidden" role="uploadcare-uploader" data-public-key="f7132a7ffef09fea16de"  data-tabs="file camera url gdrive gphotos dropbox" data-effects="crop, blur, flip, rotate, sharp" name="section_pics[]"  id="section-pic-2" form="content" value="<?php echo $section_pics[1]; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 py-5 properties order-md-2 order-1">
                                <div class="property pt-5">
                                    <div class="mb-2 text-muted  text-center text-md-right">
                                        <label for="section-title-3"><span class="require">*</span> </label>
                                        <input type="text" name="section_titles[]" class="uk-input  w-50 " placeholder="یک ویژگی" id="section-title-3" form="content" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_titles_3'] : $section_titles[2] ;?>">
                                    </div>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property">
                                        <div class=" text-muted mb-2">
                                            <label for="section_content-3">توضیح <span class="require">*</span></label>
                                            <textarea name="section_contents[]" class="uk-textarea" rows="5" placeholder="توضیح مربوطه را وارد کن..." id="section_content-3" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_contents_3'] : $section_contents[2] ;?></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="property py-5">
                                    <div class="mb-2 text-muted  text-center text-md-right">
                                        <label for="section-title-4"> </label>
                                        <input  type="text" name="section_titles[]" class="uk-input w-50 " placeholder="یک ویژگی" id="section-title-4" form="content" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_titles_4'] : $section_titles[3] ;?>">
                                    </div>
                                    <div class="divider mx-auto mx-md-0 my-4"></div>
                                    <div class="explain-property">
                                        <div class=" text-muted mb-2">
                                            <label for="section_content-4">توضیح </label>
                                            <textarea name="section_contents[]" class="uk-textarea" rows="5" placeholder="توضیح مربوطه را وارد کن..." id="section_content-4" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['section_contents_4'] : $section_contents[3] ;?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="end-info">
                    <?php
                    $end_contents=explode(",",$row['end_contents']);
                    ?>
                    <svg width="250" height="80">
                        <polygon points="0,0 250,0 250,80"></polygon>
                    </svg>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6 py-5 ">
                                <div class=" text-muted mb-4">
                                    <label for="end-content-1"><span class="require">*</span></label>
                                    <textarea name="end_contents[]" class="uk-textarea" rows="10" placeholder="محتوای پایانی..." id="end-content-1" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['end_contents_1'] : $end_contents[0];?></textarea>
                                </div>
                                <div class="divider mx-auto my-5 bg-warning"></div>
                                <div class=" text-muted mb-4">
                                    <label for="end-content-2"><span class="require">*</span></label>
                                    <textarea name="end_contents[]" class="uk-textarea" rows="10" placeholder="محتوای پایانی..." id="end-content-2" form="content"><?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['end_contents_2'] : $end_contents[1] ;?></textarea>
                                </div>
                                <div class="col-lg-6">
                                    <div class=" text-muted mb-4">
                                        <label for="source">منبع<span class="require">*</span></label>
                                        <input name="source" class="uk-input" placeholder="منبع/منابع" id="end-content-2" form="content" value="<?php echo isset($_SESSION['editedValues']) ? $_SESSION['editedValues']['source'] : $row['source'] ;?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex justify-content-center align-items-center ">
                                <div class="text-muted mb-5">
                                    <label for="end_pic" class="input-group">عکس  پایانی <span class="require">*</span></label>
                                    <input type="hidden" role="uploadcare-uploader" data-public-key="f7132a7ffef09fea16de"  data-tabs="file camera url gdrive gphotos dropbox" data-effects="crop, blur, flip, rotate, sharp" name="end_pic"  id="end_pic" form="content" value="<?php echo $row['end_pic']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <hr class="mx-auto mb-5">
                        <!--    submit edit or create content-->
                        <section class="submit  pb-5">
                            <div class="info">
                                <div class="container-fluid">
                                    <div class="row">
                                        <button class="btn-info btn mx-auto" type="submit" name="btn-edit" form="content"><i class="fa fa-edit"></i> ویرایش مطلب </button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            <?php require_once "../components/footer.php";
            echo footer();
            ?>
                <!-- to top scroll element -->
                <div class="float-right toTop d-none"><a href="#" uk-totop uk-scroll class="d-flex justify-content-center align-items-center"  ><i class="fa fa-chevron-up text-light"></i></a></div>
                <script src="../js/jquery.fittext.js"></script>
                <script>
                    $(function (){
                        $('[name="title"]').keyup(function (){
                            let value=$(this).val();
                            if (value.trim().length !== 0) $(".explain h3").text("همه چیز درباره "+value)
                            else $(".explain h3").text("");
                        })
                        if ($('[name="title"]').val().length != 0) $(".explain h3").text("همه چیز درباره "+$('[name="title"]').val())
                        uploadcare.registerTab('preview', uploadcareTabEffects)
                        UPLOADCARE_LOCALE_TRANSLATIONS = {
                            buttons: {
                                choose: {
                                    files: {
                                        one: 'عکس را ویرایش کنید'
                                    }
                                }
                            }
                        }
                    })
                </script>
            <?php }}else{header("location:../index.php");}
        }else{header("location:../index.php");}
    }else header("location:../index.php"); ?>
<?php require_once "../components/modal.php"?>
</body>
</html>


