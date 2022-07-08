<?php
require_once "../connection/connection.php";
require_once "../common/common.php";
require_once "../common/commonFunctions.php";
function isUserExist($userNameFirst,$userName){
    global $connect,$tb_users;
    $userName=sanitize($userName);
    $sql="SELECT `userName` FROM `$tb_users` WHERE `userName`=? AND NOT `id`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$userName);
    $result->bindValue(2,getId($userNameFirst));
    $result->execute();
    if ($result->rowCount()) return true;
    else return  false;
}

