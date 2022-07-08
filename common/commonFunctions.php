<?php
//get the id of a user
function getId($userName){
    global $connect,$tb_users;
    $sql="SELECT `id` FROM `$tb_users` WHERE `userName`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$userName);
    $result->execute();
    if ($result->rowCount()) {
        $row=$result->fetch();
        return intval($row['id']);
    }
    else return  false;
}
//get user score in users_score table
function getScore($id){
    global $tb_score,$connect;
    $sql="SELECT `exam_num`,`exam_names`,`score` FROM `$tb_score` WHERE `user_id`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$id);
    $result->execute();
    if ($result->rowCount()) {
        $row=$result->fetch(PDO::FETCH_ASSOC);
        $userScore=array(
            "exam_num"=>$row['exam_num'],
            "exam_names"=>$row['exam_names'],
            "score"=>$row['score']
        );
        return $userScore;
    }
    else return false;
}
//get the content is published or not
function getPublished($id){
    global $tb_content,$connect;
    $sql="SELECT `published` FROM `$tb_content` WHERE `id`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$id);
    $result->execute();
    if ($result->rowCount()) return $result->fetch()['published'];
    else return false;
}
//get all user information by id
function getUserById($id){
    global $connect,$tb_users;
    $sql="SELECT `name`,`lastName`,`userName`,`avatar`,`about`,`age`,`city` FROM `$tb_users` WHERE `id`=? ";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$id);
    $result->execute();
    if ($result->rowCount()) {
        $row=$result->fetch(PDO::FETCH_ASSOC);
        $userScore=getScore(getId($row['userName']));
        $userInfo=array(
            "name"=>$row['name'],
            "lastName"=>$row['lastName'],
            "fullName"=>$row['name']." ".$row['lastName'],
            "userName"=>$row['userName'],
            "age"=>$row['age'],
            "city"=>$row['city'],
            "avatar"=>$row['avatar'],
            "about"=>$row['about'],
            "exam_num"=>$userScore['exam_num'],
            "exam_names"=>json_decode($userScore['exam_names'],JSON_UNESCAPED_UNICODE),
        );
        return $userInfo;
    }
    else return false;
}
//get exam info
function examInfo($accessKey){
    global $tb_exam,$connect;
    $sql="SELECT * FROM `$tb_exam` WHERE `access_key`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$accessKey);
    $result->execute();
    if ($result->rowCount()){
        $row=$result->fetch();
        return array(
            "exam_name"=>$row['exam_name'],
            "each_score"=>$row['each_score'],
            "question_num"=>count(json_decode($row['right_answers'])),
        );
    }
    else return false;
}
//get exam access key
function examAccess($examName){
    global $tb_exam,$connect;
    $sql="SELECT `access_key` FROM `$tb_exam` WHERE `exam_name`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$examName);
    $result->execute();
    if ($result->rowCount()){
        $row=$result->fetch()['access_key'];
        return $row;
    }
    else return false;
}
//temp exam info
function tempExamInfo($accessKey){
    global $tb_temp_exam,$connect;
    $sql="SELECT * FROM `$tb_temp_exam` WHERE `access_key`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$accessKey);
    $result->execute();
    if ($result->rowCount()){
        $row=$result->fetch();
        return array(
            "exam_name"=>$row['exam_name'],
            "each_score"=>$row['each_score'],
            "question_num"=>count(json_decode($row['right_answers'])),
            "user"=>$row['user'],
            "questions"=>json_decode($row['questions'],JSON_UNESCAPED_UNICODE),
            "items"=>json_decode($row['items'],JSON_UNESCAPED_UNICODE),
            "right_answers"=>json_decode($row['right_answers'],JSON_UNESCAPED_UNICODE),
        );
    }
    else return false;
}
//insert exam in exam table
function addExam($exam_name,$questions,$items,$right_answers,$each_score,$publish_mode=0){
    global $tb_exam,$connect,$tb_temp_exam;
    $exam_name=sanitize($exam_name);
    $questions=json_encode($questions,JSON_UNESCAPED_UNICODE);
    $items=json_encode($items);
    $right_answers=json_encode($right_answers);
    $each_score=intval(sanitize($each_score));
    $access_key=hash('crc32',round(microtime(true)));
    $exam_table=null;
    $user=null;
    $exam_images=array(
        1=>"../icons/exam/exam_1.png",
        2=>"../icons/exam/exam_2.png",
        3=>"../icons/exam/exam_3.png",
        4=>"../icons/exam/exam_4.png",
        5=>"../icons/exam/exam_5.png",
    );
    //check if the posted exam is from admin or normal user
    if ($publish_mode ===0){
        if (isset($_SESSION['adminInfo'])) {
            $exam_table=$tb_exam;
            $user=0;
        }
        else if (isset($_SESSION['userInfo'])) {
            $exam_table=$tb_temp_exam;
            $user=$_SESSION['userInfo']['userName'];
        }
    }else{
        $exam_table=$tb_exam;
        $user=$publish_mode;
    }

    ////////////////
    $sql="INSERT `$exam_table` SET `exam_name`=?, `questions`=?,`items`=?,`right_answers`=?,`each_score`=?,`access_key`=?,`user`=?,`exam_img`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$exam_name);
    $result->bindValue(2,$questions);
    $result->bindValue(3,$items);
    $result->bindValue(4,$right_answers);
    $result->bindValue(5,$each_score);
    $result->bindValue(6,$access_key);
    $result->bindValue(7,$user);
    $result->bindValue(8,$exam_images[rand(1,4)]);
    $result->execute();
    return $result;
}
//delete exam from mentioned table
function deleteExam($accessKey,$table){
    global $tb_exam,$tb_temp_exam,$connect;
    $sql="DELETE  FROM `$table` WHERE `access_key`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$accessKey);
    $result->execute();
    return $result;
}
//get messages with specified person
function getContactWith($message_to){
    global $tb_message,$connect;
    $message_from=null;
    if (isset($_SESSION['userInfo']))$message_from=$_SESSION['userInfo']['id'];
    else if(isset($_SESSION['adminInfo'])) $message_from="admin_".$_SESSION['adminInfo']['id'];
    $sql="SELECT * FROM `$tb_message` WHERE (`message_to`=? AND `message_from`=?) OR (`message_to`=? AND `message_from`=?) ORDER BY `id` ASC ";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$message_to);
    $result->bindValue(2,$message_from);
    $result->bindValue(3,$message_from);
    $result->bindValue(4,$message_to);
    $result->execute();
    if ($result->rowCount()){
        return $result;
    }else return false;
}
//send message function
function sendMessage($message_body,$message_to,$message_from=null){
    global $tb_message,$connect;
    $message_body=sanitize($message_body);
    $sql="INSERT `$tb_message` SET `message_body`=?, `message_to`=?, `message_from`=?";
    $result=$connect->prepare($sql);
    $result->bindValue(1,$message_body);
    $result->bindValue(2,$message_to);
    if (isset($_SESSION['userInfo']))$message_from=$_SESSION['userInfo']['id'];
    else if(isset($_SESSION['adminInfo'])) $message_from="admin_".$_SESSION['adminInfo']['id'];
    $result->bindValue(3,$message_from);
    $result->execute();
    return $result;
}
//convert date function
function dateToJalali($date=null){
    $array=explode(" ",$date);
    list($year,$month,$day)=explode("-",$array[0]);
    list($hour,$min,$sec)=explode(":",$array[1]);
    $timeStamp=mktime($hour,$min,$sec,$month,$day,$year);
    return jdate(" Y/m/d , H:i:s",$timeStamp);
}
