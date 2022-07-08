<?php
require_once "../connection/connection.php";
require_once "../common/common.php";
function isExamExist($examName){
    global $connect,$tb_exam;
    $examName=sanitize($examName);
    $sql="SELECT `exam_name` FROM `$tb_exam` WHERE `exam_name`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$examName);
    $result->execute();
    if ($result->rowCount()) return true;
    else return  false;
}