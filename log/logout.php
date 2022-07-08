<?php require_once "../connection/connection.php"; ?>
<?php require_once "../common/common.php"?>
<?php require_once "../common/browser.php";?>
<?php require_once "../XYZ.php" ?>
<?php
if (isset($_GET['logToken']) && !empty($_GET['logToken']) && $_GET['logToken']==$_SESSION['logToken']){
    if (isset($_SESSION['userInfo'])){
        global $tb_log,$connect;
        $sql="INSERT `$tb_log` SET `userId`=?,`userIp`=?,`userName`=?,`browser`=?,`details`=?";
        $result=$connect->prepare($sql);
        $result->bindValue(1,$_SESSION['userInfo']['id']);
        $result->bindValue(2,$_SERVER["REMOTE_ADDR"]);
        $result->bindValue(3,$_SESSION['userInfo']['userName']);
        $result->bindValue(4,getUserBrowser($_SERVER['HTTP_USER_AGENT']));
        $result->bindValue(5,"logout");
        $result->execute();
        session_unset();
        session_destroy();
    }else if (isset($_SESSION['adminInfo'])){
        session_unset();
        session_destroy();
    }
}

header("location:../index.php");
