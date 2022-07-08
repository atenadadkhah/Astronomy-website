<?php
$host="localhost";
$userName="root";
$password="";
$dbName="univard";
$setName=array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES UTF8");
try {
    $connect=new PDO("mysql:host=$host;dbname=$dbName;",$userName,$password,$setName);
}
catch (PDOException $err){
    echo "Error While Connecting".$err->getMessage();
}
//Content table
$tb_content="admin_content";
//users table
$tb_users="users";
//log table
$tb_log="log";
//admin table
$tb_admin="admin";
// user scores table
$tb_score="users_score";
//exam table
$tb_exam="exam";
//temp exam table (when *user* insert an exam)
$tb_temp_exam="temp_exam";
//message table
$tb_message="message";