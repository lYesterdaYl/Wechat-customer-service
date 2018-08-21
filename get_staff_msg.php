<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/2/2018
 * Time: 12:34 AM
 */

header('Access-Control-Allow-Origin:https://astore.kmud.net');

include_once("include/inc.php");

$openid = $_REQUEST['openid'];





$sql = "select * from customer_service where openid = '".$openid."'";
$result = $mysqli->query($sql);
$row = $result->fetch_array();

echo $row['staff_text'];