<?php
require_once "userExistUpdateFunction.php";
require_once "../common/commonFunctions.php";
require_once "../XYZ.php";
if (isUserExist($_SESSION['userInfo']['userName'],$_POST['userName'])){
    echo "true";
}else{
    echo "false";
}