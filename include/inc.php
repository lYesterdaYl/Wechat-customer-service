<?php
error_reporting(NULL);
session_start();
$sys_user = $_SESSION['user'];
//$is_needlogin = null;
if($is_needlogin){
	if($sys_user == null){
		echo "<script>window.top.location='login.php';</script>";
		exit();
		//header('Location:login.php');
	}
}

$db_server = '';
$db_acc = '';
$db_pwd = '';
$db_name = '';
$db_code = 'utf8';

$all_company_id = "(1001,1002,1003,1004)";

$mysqli = @new mysqli($db_server, $db_acc, $db_pwd);

if ($mysqli->connect_errno) {
    die("could not connect to the database:\n" . $mysqli->connect_error);//诊断连接错误
}
$mysqli->query("set names " . $db_code);//编码转化
$select_db = $mysqli->select_db($db_name);
if (!$select_db) {
    die("could not connect to the db:\n" .  $mysqli->error);
}
header('Content-Type: text/html; charset=utf-8');
include_once("fun.php");


?>