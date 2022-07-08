<?php require_once "../XYZ.php" ?>
<?php require_once "../connection/connection.php"?>
<?php require_once "../common/common.php"?>
<?php require_once "../checks/userExistUpdateFunction.php"?>
<?php require_once "../common/commonFunctions.php" ?>
<?php require_once "../common/jdf.php";?>
<?php
$message=sanitize($_POST['message']);
$messageF=null;
if (!empty($message)){
            if (isset($_SESSION['userInfo'])){
                $messageF=sendMessage($message,$_POST['contact_id'],$_SESSION['userInfo']['id']);
                if($messageF){
                    echo "<div class='me'>";
                    echo "<span class=' mx-1'><img src='".$_SESSION['userInfo']['avatar']."' alt='' class='avatar'></span><li class='mx-3'>$message<small class='time d-block mt-2'>".dateToJalali(date('Y-m-d H:i:s'))."</small></li>";
                    echo "</div>";

                }
            }else if (isset($_SESSION['adminInfo'])){
                $messageF=sendMessage($message,$_POST['contact_id'],$_SESSION['adminInfo']['id']);
            }
        }

