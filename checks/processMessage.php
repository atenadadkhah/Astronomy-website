<?php require_once "../XYZ.php" ?>
<?php require_once "../connection/connection.php"?>
<?php require_once "../common/common.php"?>
<?php require_once "../checks/userExistUpdateFunction.php"?>
<?php require_once "../common/commonFunctions.php" ?>
<?php require_once "../common/jdf.php";?>
<?php
$contact=getContactWith($_POST['contact_id']);
if ($contact){
    $rows=$contact->fetchAll(PDO::FETCH_ASSOC);
    $user="";
    foreach ($rows as $row) {
        if ($_SESSION['userInfo']['id']==$row['message_from']) $user="me";
        else $user="friend";
        echo "<div class='".$user."'>";
        if($user==="me") echo "<span class=' mx-1'><img src='".getUserById($row['message_from'])['avatar']."' alt='' class='avatar'></span>";
        echo "<li class='mx-3'>".$row['message_body']."<small class='time d-block mt-2'>".dateToJalali($row['create_time'])."</small></li>";
        if($user!=="me") echo "<span class=' mx-1'><img src='".getUserById($row['message_from'])['avatar']."' alt='' class='avatar'></span>";
        echo "</div>";

     }
}