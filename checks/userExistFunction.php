<?php
require_once "../connection/connection.php";
require_once "../common/common.php";
function isUserExist($userName){
    global $connect,$tb_users;
    $userName=sanitize($userName);
    $sql="SELECT `userName` FROM `$tb_users` WHERE `userName`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$userName);
    $result->execute();
    if ($result->rowCount()) return true;
    else return  false;
}