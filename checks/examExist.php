<?php
require_once "examExistFunction.php";
if (isExamExist($_POST['examName'])){
    echo "true";
}else{
    echo "false";
}