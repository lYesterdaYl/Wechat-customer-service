<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/2/2018
 * Time: 12:50 AM
 */

header('Access-Control-Allow-Origin:https://astore.kmud.net');
//header('Access-Control-Allow-Origin:http://localhost:63342');



$question = $_REQUEST['question'];
$openid = $_REQUEST['openid'];
$nickname = $_REQUEST['nickname'];



include_once("include/inc.php");

$check_old = "select * from customer_service where openid = '".$openid."'";
$check_old_result = $mysqli->query($check_old);
$check_old_row = $check_old_result->fetch_array();
if($check_old_row['nickname'] == NULL){
    $initialize = "insert into customer_service (openid, customer_text, sender,nickname) VALUES ('".$openid."', '".$question."','customer','".$nickname."')";
    echo $initialize;
    $mysqli->query($initialize);
}
else{
    $delete_old = "delete from customer_service where openid = '".$openid."'";
    $mysqli->query($delete_old);
    $initialize = "insert into customer_service (openid, customer_text, sender,nickname) VALUES ('".$openid."', '".$question."','customer','".$nickname."')";
    $mysqli->query($initialize);
}


