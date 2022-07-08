<?php
require_once "userExistFunction.php";
if (isUserExist($_POST['userName'])){
    echo "true";
}else{
    echo "false";
}